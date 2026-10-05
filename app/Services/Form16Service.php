<?php

namespace App\Services;

use App\Mail\Form16Send;
use App\Models\Employee;
use App\Models\Form16AuditLog;
use App\Models\Form16EmailLog;
use App\Models\Form16Record;
use App\Models\PaySlip;
use App\Models\Utility;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Form 16 (Section 203 / Rule 31) service.
 *
 * Part A: Official TRACES PDF only — upload/store; never fabricate certificate numbers.
 * Part B: Employer annexure built from HRMS payroll/TDS for the selected FY.
 */
class Form16Service
{
    public const STORAGE_DISK = 'local';
    public const STORAGE_ROOT = 'form16';
    public const MAX_PART_A_BYTES = 10485760; // 10 MB

    /**
     * Indian FY starts April. FY label "2025-26" => AY "2026-27".
     */
    public function parseFinancialYear(string $fy): array
    {
        $fy = trim($fy);
        if (!preg_match('/^(\d{4})-(\d{2})$/', $fy, $m)) {
            throw new \InvalidArgumentException('Invalid Financial Year. Use format YYYY-YY (e.g. 2025-26).');
        }

        $startYear = (int) $m[1];
        $endYearShort = (int) $m[2];
        $expectedEnd = ($startYear + 1) % 100;
        if ($endYearShort !== $expectedEnd) {
            throw new \InvalidArgumentException('Invalid Financial Year range.');
        }

        $endYear = $startYear + 1;
        $ay = sprintf('%d-%02d', $endYear, ($endYear + 1) % 100);

        return [
            'financial_year' => sprintf('%d-%02d', $startYear, $endYearShort),
            'assessment_year' => $ay,
            'fy_start_date' => Carbon::create($startYear, 4, 1)->startOfDay(),
            'fy_end_date' => Carbon::create($endYear, 3, 31)->endOfDay(),
            'months' => $this->fyMonthKeys($startYear),
        ];
    }

    /**
     * @return string[] YYYY-MM for Apr..Mar
     */
    public function fyMonthKeys(int $startYear): array
    {
        $months = [];
        for ($m = 4; $m <= 12; $m++) {
            $months[] = sprintf('%d-%02d', $startYear, $m);
        }
        for ($m = 1; $m <= 3; $m++) {
            $months[] = sprintf('%d-%02d', $startYear + 1, $m);
        }
        return $months;
    }

    /**
     * Available FY options (current FY +/- range).
     */
    public function availableFinancialYears(int $past = 5, int $future = 1): array
    {
        $now = Carbon::now();
        $currentStart = $now->month >= 4 ? $now->year : $now->year - 1;
        $options = [];
        for ($y = $currentStart + $future; $y >= $currentStart - $past; $y--) {
            $label = sprintf('%d-%02d', $y, ($y + 1) % 100);
            $parsed = $this->parseFinancialYear($label);
            $options[] = [
                'financial_year' => $parsed['financial_year'],
                'assessment_year' => $parsed['assessment_year'],
            ];
        }
        return $options;
    }

    public function companyId(): int
    {
        return (int) Auth::user()->creatorId();
    }

    public function assertEmployeeBelongsToCompany(int $employeeId, ?int $companyId = null): Employee
    {
        $companyId = $companyId ?? $this->companyId();
        $employee = Employee::where('id', $employeeId)
            ->where('created_by', $companyId)
            ->first();

        if (!$employee) {
            throw new \RuntimeException(__('Unauthorized employee access.'));
        }

        return $employee;
    }

    public function assertRecordBelongsToCompany(int $form16Id, ?int $companyId = null): Form16Record
    {
        $companyId = $companyId ?? $this->companyId();
        $record = Form16Record::where('id', $form16Id)
            ->where('company_id', $companyId)
            ->first();

        if (!$record) {
            throw new \RuntimeException(__('Unauthorized Form 16 access.'));
        }

        return $record;
    }

    public function companySettings(?int $companyId = null): array
    {
        // Utility::settings() is scoped to logged-in creator
        return Utility::settings();
    }

    public function employerSnapshot(?array $settings = null): array
    {
        $settings = $settings ?? $this->companySettings();
        $addressParts = array_filter([
            $settings['company_address'] ?? null,
            $settings['company_city'] ?? null,
            $settings['company_state'] ?? null,
            $settings['company_zipcode'] ?? null,
        ]);

        return [
            'name' => trim((string) ($settings['company_name'] ?? '')),
            'address' => implode(', ', $addressParts),
            'pan' => trim((string) ($settings['company_pan_number'] ?? $settings['company_pan'] ?? '')),
            'tan' => trim((string) ($settings['company_tan_numbar'] ?? '')),
            'email' => trim((string) ($settings['company_email'] ?? '')),
        ];
    }

    public function employmentPeriod(Employee $employee, Carbon $fyStart, Carbon $fyEnd): array
    {
        $doj = $employee->company_doj ? Carbon::parse($employee->company_doj)->startOfDay() : null;
        $exit = null;
        if (!empty($employee->date_of_exit) && $employee->date_of_exit !== '0000-00-00') {
            try {
                $exit = Carbon::parse($employee->date_of_exit)->startOfDay();
            } catch (\Throwable $e) {
                $exit = null;
            }
        }

        // No DOJ: assume full FY (company must correct later)
        if (!$doj) {
            return [
                'from' => $fyStart->toDateString(),
                'to' => $fyEnd->toDateString(),
                'worked_in_fy' => true,
                'months' => $this->monthsBetween($fyStart, $fyEnd),
            ];
        }

        // Joined after FY ended, or exited before FY started
        if ($doj->gt($fyEnd) || ($exit && $exit->lt($fyStart))) {
            return [
                'from' => null,
                'to' => null,
                'worked_in_fy' => false,
                'months' => [],
            ];
        }

        $from = $doj->gt($fyStart) ? $doj->copy() : $fyStart->copy();
        $to = ($exit && $exit->lt($fyEnd)) ? $exit->copy() : $fyEnd->copy();

        return [
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'worked_in_fy' => true,
            'months' => $this->monthsBetween($from, $to),
        ];
    }

    /**
     * @return string[] YYYY-MM list inclusive of from/to months
     */
    public function monthsBetween(Carbon $from, Carbon $to): array
    {
        $months = [];
        $cursor = $from->copy()->startOfMonth();
        $end = $to->copy()->startOfMonth();
        while ($cursor->lte($end)) {
            $months[] = $cursor->format('Y-m');
            $cursor->addMonth();
        }
        return $months;
    }

    /**
     * Aggregate payslips for FY months. Falls back to employee salary structure when no payslips exist.
     */
    public function aggregatePayroll(Employee $employee, array $fyMeta): array
    {
        $period = $this->employmentPeriod($employee, $fyMeta['fy_start_date'], $fyMeta['fy_end_date']);
        $activeMonths = $period['worked_in_fy'] ? ($period['months'] ?: $fyMeta['months']) : [];

        $monthWise = [];
        $totals = [
            'basic_salary' => 0.0,
            'gross_earning' => 0.0,
            'net_payable' => 0.0,
            'tds' => 0.0,
            'professional_tax' => 0.0,
            'epf_employee' => 0.0,
            'months_present' => 0,
            'slip_count' => 0,
            'data_source' => 'none',
            'worked_in_fy' => (bool) ($period['worked_in_fy'] ?? false),
        ];

        if (empty($activeMonths)) {
            return [
                'month_wise' => [],
                'totals' => $totals,
                'employment_period' => $period,
            ];
        }

        // Match salary_month flexibly: Y-m, Y-m-01, Y/m, m-Y
        $slips = PaySlip::where('employee_id', $employee->id)
            ->where(function ($q) use ($activeMonths) {
                foreach ($activeMonths as $ym) {
                    $q->orWhere('salary_month', $ym)
                        ->orWhere('salary_month', 'like', $ym . '%')
                        ->orWhere('salary_month', Carbon::createFromFormat('Y-m', $ym)->format('m-Y'));
                }
            })
            ->orderBy('salary_month')
            ->get();

        // Index slips by normalized Y-m
        $slipsByMonth = [];
        foreach ($slips as $slip) {
            $norm = $this->normalizeSalaryMonth((string) $slip->salary_month);
            if ($norm) {
                $slipsByMonth[$norm] = $slip;
            }
        }

        if (count($slipsByMonth) > 0) {
            $totals['data_source'] = 'payslips';
            $totals['slip_count'] = count($slipsByMonth);

            foreach ($activeMonths as $ym) {
                if (!isset($slipsByMonth[$ym])) {
                    continue;
                }
                $slip = $slipsByMonth[$ym];
                $raw = [];
                if (!empty($slip->pay_slip_data)) {
                    $decoded = json_decode($slip->pay_slip_data, true);
                    $raw = is_array($decoded) ? $decoded : [];
                }

                $basic = (float) ($slip->basic_salary ?? 0);
                $net = (float) ($slip->net_payble ?? 0);
                $tds = $this->extractTdsFromSlip($slip, $raw);
                $pt = $this->extractNamedDeduction($raw, ['Professional Tax', 'PT', 'professional_tax']);
                $epf = $this->extractNamedDeduction($raw, ['EPF', 'PF', 'Provident Fund']);
                $gross = $this->extractGross($slip, $raw, $basic);

                $monthWise[$ym] = [
                    'salary_month' => $ym,
                    'basic_salary' => $basic,
                    'gross_earning' => $gross,
                    'net_payable' => $net,
                    'tds' => $tds,
                    'professional_tax' => $pt,
                    'epf_employee' => $epf,
                    'status' => (int) $slip->status,
                    'source' => 'payslip',
                ];

                $totals['basic_salary'] += $basic;
                $totals['gross_earning'] += $gross;
                $totals['net_payable'] += $net;
                $totals['tds'] += $tds;
                $totals['professional_tax'] += $pt;
                $totals['epf_employee'] += $epf;
                $totals['months_present']++;
            }
        } else {
            // Fallback: estimate from employee salary structure for each active month
            $estimate = $this->estimateMonthlySalaryFromStructure($employee);
            $totals['data_source'] = 'salary_structure_estimate';
            $totals['slip_count'] = 0;

            foreach ($activeMonths as $ym) {
                // Partial first/last month not pro-rated here for Form 16 draft; full month used.
                $monthWise[$ym] = [
                    'salary_month' => $ym,
                    'basic_salary' => $estimate['basic_salary'],
                    'gross_earning' => $estimate['gross_earning'],
                    'net_payable' => $estimate['net_payable'],
                    'tds' => $estimate['tds'],
                    'professional_tax' => $estimate['professional_tax'],
                    'epf_employee' => $estimate['epf_employee'],
                    'status' => 0,
                    'source' => 'salary_structure_estimate',
                ];

                $totals['basic_salary'] += $estimate['basic_salary'];
                $totals['gross_earning'] += $estimate['gross_earning'];
                $totals['net_payable'] += $estimate['net_payable'];
                $totals['tds'] += $estimate['tds'];
                $totals['professional_tax'] += $estimate['professional_tax'];
                $totals['epf_employee'] += $estimate['epf_employee'];
                $totals['months_present']++;
            }
        }

        return [
            'month_wise' => $monthWise,
            'totals' => $totals,
            'employment_period' => $period,
        ];
    }

    public function normalizeSalaryMonth(string $value): ?string
    {
        $value = trim($value);
        if (preg_match('/^(\d{4})-(\d{2})/', $value, $m)) {
            return $m[1] . '-' . $m[2];
        }
        if (preg_match('/^(\d{2})-(\d{4})$/', $value, $m)) {
            return $m[2] . '-' . $m[1];
        }
        try {
            return Carbon::parse($value)->format('Y-m');
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Build one-month salary estimate from employee master + earnings/deductions helpers.
     */
    public function estimateMonthlySalaryFromStructure(Employee $employee): array
    {
        $basic = (float) ($employee->salary ?? 0);
        $extra = [
            'companyWorkingDays' => 30,
            'employeeWorkingDays' => 30,
        ];

        $allowanceTotal = 0.0;
        $commissionTotal = 0.0;
        $otherEarningTotal = 0.0;
        $pt = 0.0;
        $epf = 0.0;
        $tds = 0.0;

        try {
            $allow = Employee::payslipAllowanceEmployeeWise($employee->id, $extra, [], false);
            $allowanceTotal = (float) ($allow['total'] ?? 0);
        } catch (\Throwable $e) {
            // ignore
        }

        try {
            if (method_exists(Employee::class, 'paySlipCommissionEmployeeWise')) {
                $comm = Employee::paySlipCommissionEmployeeWise($employee->id, $extra, false);
                $commissionTotal = (float) ($comm['total'] ?? 0);
            }
        } catch (\Throwable $e) {
        }

        try {
            if (method_exists(Employee::class, 'paySlipOtherPaymentEarningsEmployeeWise')) {
                $oth = Employee::paySlipOtherPaymentEarningsEmployeeWise($employee->id, $extra, false);
                $otherEarningTotal = (float) ($oth['total'] ?? 0);
            }
        } catch (\Throwable $e) {
        }

        $gross = $basic + $allowanceTotal + $commissionTotal + $otherEarningTotal;

        try {
            $ptData = Employee::employeeProfessionalTaxDeduction($employee->id, $gross, $extra);
            $pt = (float) ($ptData['total'] ?? 0);
        } catch (\Throwable $e) {
        }

        try {
            $epfData = Employee::providentFundEmployeeDeduction($employee->id, $gross, $extra, false);
            $epf = (float) ($epfData['total'] ?? 0);
        } catch (\Throwable $e) {
        }

        try {
            $tdsData = Employee::tdsDeductionEmployeeWise($employee->id, $extra, false);
            $tds = (float) ($tdsData['total'] ?? 0);
        } catch (\Throwable $e) {
            if ((int) ($employee->tax_deducted_at_source_status ?? 0) === 1) {
                $tds = (float) ($employee->tax_deducted_at_source ?? 0);
            }
        }

        // If helpers returned empty, still use basic salary so Form 16 is not blank
        if ($gross <= 0 && $basic > 0) {
            $gross = $basic;
        }

        $net = max(0, $gross - $pt - $epf - $tds);

        return [
            'basic_salary' => round($basic, 2),
            'gross_earning' => round($gross, 2),
            'net_payable' => round($net, 2),
            'tds' => round($tds, 2),
            'professional_tax' => round($pt, 2),
            'epf_employee' => round($epf, 2),
            'allowance_total' => round($allowanceTotal, 2),
        ];
    }

    protected function extractTdsFromSlip(PaySlip $slip, array $raw): float
    {
        $keys = ['tds', 'TDS', 'tax_deducted_at_source', 'Tax Deducted at Source'];
        foreach ($keys as $key) {
            if (isset($raw[$key]) && is_numeric($raw[$key])) {
                return (float) $raw[$key];
            }
        }

        if (!empty($raw['other_payments_deductions']) && is_array($raw['other_payments_deductions'])) {
            foreach ($raw['other_payments_deductions'] as $row) {
                $title = strtolower((string) ($row['title'] ?? $row['name'] ?? ''));
                if (str_contains($title, 'tds') || str_contains($title, 'tax deducted')) {
                    return (float) ($row['amount'] ?? $row['amountdeduct'] ?? 0);
                }
            }
        }

        // Decode other_payments_deductions column if present
        if (!empty($slip->other_payments_deductions)) {
            $ded = json_decode($slip->other_payments_deductions, true);
            if (is_array($ded)) {
                foreach ($ded as $row) {
                    $title = strtolower((string) ($row['title'] ?? $row['name'] ?? ''));
                    if (str_contains($title, 'tds') || str_contains($title, 'tax deducted')) {
                        return (float) ($row['amount'] ?? $row['amountdeduct'] ?? 0);
                    }
                }
            }
        }

        return 0.0;
    }

    protected function extractNamedDeduction(array $raw, array $names): float
    {
        foreach ($names as $name) {
            if (isset($raw[$name]) && is_numeric($raw[$name])) {
                return (float) $raw[$name];
            }
        }
        return 0.0;
    }

    protected function extractGross(PaySlip $slip, array $raw, float $basic): float
    {
        foreach (['gross_salary', 'gross_earning', 'total_earning', 'gross'] as $key) {
            if (isset($raw[$key]) && is_numeric($raw[$key])) {
                return (float) $raw[$key];
            }
        }
        $allowance = 0.0;
        if (!empty($slip->allowance)) {
            $a = json_decode($slip->allowance, true);
            if (is_array($a)) {
                foreach ($a as $row) {
                    $allowance += (float) ($row['amount'] ?? 0);
                }
            }
        }
        return $basic + $allowance;
    }

    /**
     * Build Part B annexure data from payroll. Does NOT invent tax slabs/rates.
     * Tax payable fields remain 0 unless supplied via verified payroll TDS totals.
     */
    public function buildPartBData(Employee $employee, array $fyMeta, array $payroll, string $taxRegime = 'unknown'): array
    {
        $employer = $this->employerSnapshot();
        $period = $payroll['employment_period'] ?? $this->employmentPeriod($employee, $fyMeta['fy_start_date'], $fyMeta['fy_end_date']);
        $totals = $payroll['totals'];

        $gross = round((float) $totals['gross_earning'], 2);
        $tds = round((float) $totals['tds'], 2);

        // Standard deduction is regime/AY dependent — do not hard-code outdated limits.
        // Stored as null so PDF shows "As applicable / to be confirmed" unless company sets it later.
        $standardDeduction = null;

        $salaryUnder171 = $gross; // section 17(1) proxy from payroll gross
        $perquisites172 = 0.0;
        $profits173 = 0.0;
        $exemptAllowances = 0.0; // HRA/LTA etc. — not separately tracked in core HRMS yet
        $netSalary = round($salaryUnder171 + $perquisites172 + $profits173 - $exemptAllowances, 2);
        $deduction16 = 0.0;
        $incomeChargeable = round($netSalary - $deduction16, 2);

        return [
            'document_type' => 'employer_part_b_annexure',
            'disclaimer' => 'This Part B / Annexure is prepared by the employer using HRMS payroll records. '
                . 'It is NOT generated by the Income Tax Department or TRACES. '
                . 'Official Form 16 Part A must be downloaded from TRACES after Form 24Q processing.',
            'financial_year' => $fyMeta['financial_year'],
            'assessment_year' => $fyMeta['assessment_year'],
            'tax_regime' => $taxRegime,
            'employer' => $employer,
            'employee' => [
                'name' => $employee->name,
                'employee_id' => $employee->employee_id,
                'pan' => $employee->tax_payer_id,
                'email' => $employee->email,
                'address' => $employee->address,
                'period_from' => $period['from'],
                'period_to' => $period['to'],
            ],
            'salary' => [
                'salary_as_per_provisions_u_s_17_1' => $salaryUnder171,
                'value_of_perquisites_u_s_17_2' => $perquisites172,
                'profits_in_lieu_of_salary_u_s_17_3' => $profits173,
                'gross_salary' => $gross,
                'less_exempt_allowances_u_s_10' => $exemptAllowances,
                'net_salary' => $netSalary,
                'deductions_u_s_16' => [
                    'standard_deduction' => $standardDeduction,
                    'entertainment_allowance' => 0,
                    'tax_on_employment' => round((float) $totals['professional_tax'], 2),
                    'total' => $deduction16,
                ],
                'income_chargeable_under_salaries' => $incomeChargeable,
            ],
            'other_income_reported_to_employer' => 0,
            'chapter_via_deductions' => [
                'total' => 0,
                'note' => 'Chapter VI-A declarations are not stored in core HRMS; enter via Form 16 review if applicable (old regime).',
            ],
            'tax_computation' => [
                'total_taxable_income' => $incomeChargeable,
                'tax_on_total_income' => null,
                'rebate' => null,
                'surcharge' => null,
                'health_education_cess' => null,
                'tax_payable' => null,
                'relief_under_section_89' => 0,
                'tds_deducted_as_per_payroll' => $tds,
                'note' => 'Tax liability figures are not reinvented by this module. Use verified TDS from payroll / TRACES Part A.',
            ],
            'payroll_aggregation' => [
                'months' => $payroll['month_wise'],
                'totals' => $totals,
            ],
            'generated_at' => now()->toDateTimeString(),
        ];
    }

    public function validateForGeneration(Employee $employee, array $fyMeta, array $partB, ?Form16Record $record = null): array
    {
        $errors = [];
        $employer = $partB['employer'] ?? $this->employerSnapshot();
        $empEdit = route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id));
        $companySetting = route('company.setting');

        if (empty($employee->name)) {
            $errors[] = ['field' => 'employee_name', 'message' => 'Employee name is missing.', 'fix' => $empEdit];
        }
        if (empty($employee->tax_payer_id)) {
            $errors[] = ['field' => 'employee_pan', 'message' => 'Employee PAN (Tax Payer ID) is missing.', 'fix' => $empEdit];
        }
        if (empty($employee->email)) {
            $errors[] = ['field' => 'employee_email', 'message' => 'Employee email is missing (required to send Form 16).', 'fix' => $empEdit];
        }
        if (empty($employee->employee_id)) {
            $errors[] = ['field' => 'employee_code', 'message' => 'Employee reference / ID is missing.', 'fix' => $empEdit];
        }
        if (empty($employer['name'])) {
            $errors[] = ['field' => 'employer_name', 'message' => 'Company name is missing in settings.', 'fix' => $companySetting];
        }
        if (empty($employer['tan'])) {
            $errors[] = ['field' => 'employer_tan', 'message' => 'Company TAN is missing in settings.', 'fix' => $companySetting];
        }
        if (empty($employer['pan'])) {
            $errors[] = ['field' => 'employer_pan', 'message' => 'Company PAN is missing in settings (company_pan_number).', 'fix' => $companySetting];
        }
        if (empty($employer['address'])) {
            $errors[] = ['field' => 'employer_address', 'message' => 'Company address is incomplete in settings.', 'fix' => $companySetting];
        }

        $slipCount = (int) ($partB['payroll_aggregation']['totals']['slip_count'] ?? 0);
        $dataSource = (string) ($partB['payroll_aggregation']['totals']['data_source'] ?? 'none');
        $monthsPresent = (int) ($partB['payroll_aggregation']['totals']['months_present'] ?? 0);
        $gross = (float) ($partB['payroll_aggregation']['totals']['gross_earning'] ?? 0);
        $workedInFy = (bool) ($partB['payroll_aggregation']['totals']['worked_in_fy'] ?? true);

        if (!$workedInFy) {
            $errors[] = [
                'field' => 'employment_period',
                'message' => 'Employee did not work in FY ' . $fyMeta['financial_year'] . '. Choose a Financial Year that overlaps joining/exit dates.',
                'fix' => $empEdit,
            ];
        } elseif ($slipCount === 0 && $dataSource === 'salary_structure_estimate' && $gross > 0) {
            // Soft warning only - not a blocking error. Stored separately via missing flags if needed.
            // Keep as informational warning in validation list with clear wording.
            $errors[] = [
                'field' => 'salary_data_warning',
                'message' => 'No payslips found for FY ' . $fyMeta['financial_year'] . '. Using employee salary structure estimate for ' . $monthsPresent . ' month(s). Generate payslips for exact Form 16 figures.',
                'fix' => route('payslip.index'),
                'level' => 'warning',
            ];
        } elseif ($slipCount === 0 && $gross <= 0) {
            $errors[] = [
                'field' => 'salary_data',
                'message' => 'No payslip records and no salary structure amount found for FY ' . $fyMeta['financial_year'] . '.',
                'fix' => route('payslip.index'),
            ];
        }

        return $errors;
    }

    public function findLatest(int $companyId, int $employeeId, string $fy): ?Form16Record
    {
        return Form16Record::where('company_id', $companyId)
            ->where('employee_id', $employeeId)
            ->where('financial_year', $fy)
            ->where('is_latest', 1)
            ->first();
    }

    public function createOrLoadDraft(Employee $employee, string $fy, ?string $taxRegime = null): Form16Record
    {
        $companyId = $this->companyId();
        $this->assertEmployeeBelongsToCompany($employee->id, $companyId);
        $fyMeta = $this->parseFinancialYear($fy);
        $existing = $this->findLatest($companyId, $employee->id, $fyMeta['financial_year']);
        if ($existing) {
            return $existing;
        }

        $employer = $this->employerSnapshot();
        $period = $this->employmentPeriod($employee, $fyMeta['fy_start_date'], $fyMeta['fy_end_date']);
        $payroll = $this->aggregatePayroll($employee, $fyMeta);
        $regime = $taxRegime ?: 'unknown';
        $partB = $this->buildPartBData($employee, $fyMeta, $payroll, $regime);
        $errors = $this->validateForGeneration($employee, $fyMeta, $partB);

        $blockingErrors = array_values(array_filter($errors, fn ($e) => ($e['level'] ?? 'error') !== 'warning'));

        $record = Form16Record::create([
            'company_id' => $companyId,
            'employee_id' => $employee->id,
            'financial_year' => $fyMeta['financial_year'],
            'assessment_year' => $fyMeta['assessment_year'],
            'fy_start_date' => $fyMeta['fy_start_date']->toDateString(),
            'fy_end_date' => $fyMeta['fy_end_date']->toDateString(),
            'employment_period_from' => $period['from'],
            'employment_period_to' => $period['to'],
            'version' => 1,
            'is_latest' => 1,
            'status' => count($blockingErrors) ? 'data_pending' : 'draft',
            'tax_regime' => $regime,
            'employee_name' => $employee->name,
            'employee_code' => $employee->employee_id,
            'employee_pan' => $employee->tax_payer_id,
            'employee_email' => $employee->email,
            'employee_address' => $employee->address,
            'employer_name' => $employer['name'],
            'employer_address' => $employer['address'],
            'employer_pan' => $employer['pan'],
            'employer_tan' => $employer['tan'],
            'employer_email' => $employer['email'],
            'part_b_data' => json_encode($partB),
            'gross_salary' => $partB['salary']['gross_salary'],
            'exempt_allowances' => $partB['salary']['less_exempt_allowances_u_s_10'],
            'net_salary' => $partB['salary']['net_salary'],
            'deduction_under_section_16' => $partB['salary']['deductions_u_s_16']['total'],
            'income_chargeable_salaries' => $partB['salary']['income_chargeable_under_salaries'],
            'tds_deducted' => $partB['tax_computation']['tds_deducted_as_per_payroll'],
            'validation_errors' => json_encode($errors),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        $this->audit($record, 'created', ['financial_year' => $fyMeta['financial_year']]);

        return $record;
    }

    public function refreshData(Form16Record $record, ?string $taxRegime = null): Form16Record
    {
        $employee = $this->assertEmployeeBelongsToCompany($record->employee_id, $record->company_id);
        $fyMeta = $this->parseFinancialYear($record->financial_year);
        $employer = $this->employerSnapshot();
        $period = $this->employmentPeriod($employee, $fyMeta['fy_start_date'], $fyMeta['fy_end_date']);
        $payroll = $this->aggregatePayroll($employee, $fyMeta);
        $regime = $taxRegime ?: ($record->tax_regime ?: 'unknown');
        $partB = $this->buildPartBData($employee, $fyMeta, $payroll, $regime);
        $errors = $this->validateForGeneration($employee, $fyMeta, $partB, $record);

        $status = $record->status;
        $blockingErrors = array_values(array_filter($errors, fn ($e) => ($e['level'] ?? 'error') !== 'warning'));
        if (count($blockingErrors)) {
            // Keep generated/draft PDF statuses; only force pending when no PDF yet.
            if (!$record->hasPartBPdf() && !in_array($status, ['issued', 'sent', 'verified', 'part_b_generated', 'generated', 'draft'], true)) {
                $status = 'data_pending';
            } elseif ($record->hasPartBPdf() && in_array($status, ['not_started', 'data_pending'], true)) {
                $status = 'draft';
            }
        } elseif (in_array($status, ['not_started', 'data_pending'], true)) {
            $status = $record->hasPartBPdf() ? 'part_b_generated' : 'draft';
        }

        $record->update([
            'employment_period_from' => $period['from'],
            'employment_period_to' => $period['to'],
            'tax_regime' => $regime,
            'employee_name' => $employee->name,
            'employee_code' => $employee->employee_id,
            'employee_pan' => $employee->tax_payer_id,
            'employee_email' => $employee->email,
            'employee_address' => $employee->address,
            'employer_name' => $employer['name'],
            'employer_address' => $employer['address'],
            'employer_pan' => $employer['pan'],
            'employer_tan' => $employer['tan'],
            'employer_email' => $employer['email'],
            'part_b_data' => json_encode($partB),
            'gross_salary' => $partB['salary']['gross_salary'],
            'exempt_allowances' => $partB['salary']['less_exempt_allowances_u_s_10'],
            'net_salary' => $partB['salary']['net_salary'],
            'deduction_under_section_16' => $partB['salary']['deductions_u_s_16']['total'],
            'income_chargeable_salaries' => $partB['salary']['income_chargeable_under_salaries'],
            'tds_deducted' => $partB['tax_computation']['tds_deducted_as_per_payroll'],
            'validation_errors' => json_encode($errors),
            'status' => $status,
            'updated_by' => Auth::id(),
        ]);

        $this->audit($record, 'data_updated');

        return $record->fresh();
    }

    public function uploadPartA(Form16Record $record, UploadedFile $file, ?string $certificateNumber = null): Form16Record
    {
        $this->assertRecordBelongsToCompany($record->id, $record->company_id);

        if ($file->getClientOriginalExtension() !== 'pdf' && $file->getMimeType() !== 'application/pdf') {
            throw new \InvalidArgumentException('Part A must be a PDF file downloaded from TRACES.');
        }
        if ($file->getSize() > self::MAX_PART_A_BYTES) {
            throw new \InvalidArgumentException('Part A PDF exceeds 10 MB limit.');
        }

        $dir = self::STORAGE_ROOT . '/' . $record->company_id . '/' . $record->employee_id . '/' . $record->financial_year;
        if ($record->part_a_file_path) {
            Storage::disk(self::STORAGE_DISK)->delete($record->part_a_file_path);
        }

        $path = $file->storeAs($dir, 'part_a_v' . $record->version . '_' . time() . '.pdf', self::STORAGE_DISK);

        $record->update([
            'part_a_source' => 'upload',
            'part_a_file_path' => $path,
            'part_a_original_name' => $file->getClientOriginalName(),
            'part_a_mime' => $file->getMimeType(),
            'part_a_size' => $file->getSize(),
            'part_a_uploaded_by' => Auth::id(),
            'part_a_uploaded_at' => now(),
            'part_a_certificate_number' => $certificateNumber ?: $record->part_a_certificate_number,
            'status' => $record->hasPartB() ? $record->status : 'part_a_uploaded',
            'updated_by' => Auth::id(),
        ]);

        $this->audit($record, 'part_a_uploaded', ['file' => $file->getClientOriginalName()]);

        return $record->fresh();
    }

    public function deletePartA(Form16Record $record): Form16Record
    {
        $this->assertRecordBelongsToCompany($record->id, $record->company_id);
        if (in_array($record->status, ['issued', 'sent'], true)) {
            throw new \RuntimeException('Cannot delete Part A after Form 16 is issued/sent. Regenerate a new version instead.');
        }
        if ($record->part_a_file_path) {
            Storage::disk(self::STORAGE_DISK)->delete($record->part_a_file_path);
        }
        $record->update([
            'part_a_file_path' => null,
            'part_a_original_name' => null,
            'part_a_mime' => null,
            'part_a_size' => null,
            'part_a_uploaded_by' => null,
            'part_a_uploaded_at' => null,
            'updated_by' => Auth::id(),
        ]);
        $this->audit($record, 'part_a_deleted');
        return $record->fresh();
    }

    public function generatePartBPdf(Form16Record $record, bool $allowIncomplete = true): Form16Record
    {
        $record = $this->refreshData($record);
        $errors = $record->validation_errors_array;
        $blocking = array_values(array_filter($errors, fn ($e) => ($e['level'] ?? 'error') !== 'warning'));
        $isIncomplete = count($blocking) > 0;

        // Allow draft PDF so company can preview/download; block only final issue/email elsewhere.
        if ($isIncomplete && !$allowIncomplete) {
            throw new \RuntimeException('Cannot generate Part B while required data is missing.');
        }

        $partB = $record->part_b_data_array;
        $partB['is_incomplete_draft'] = $isIncomplete;
        $partB['incomplete_reasons'] = $errors;

        $pdf = Pdf::loadView('form16.pdf.part_b', [
            'record' => $record,
            'partB' => $partB,
        ])->setPaper('a4');

        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'isFontSubsettingEnabled' => true,
        ]);

        $dir = self::STORAGE_ROOT . '/' . $record->company_id . '/' . $record->employee_id . '/' . $record->financial_year;
        $path = $dir . '/part_b_v' . $record->version . '_' . time() . '.pdf';
        $pdfBytes = $pdf->output();
        Storage::disk(self::STORAGE_DISK)->put($path, $pdfBytes);

        // Final package = Part B employer annexure (Part A remains separate TRACES PDF)
        $finalPath = $dir . '/form16_package_v' . $record->version . '_' . time() . '.pdf';
        Storage::disk(self::STORAGE_DISK)->put($finalPath, $pdfBytes);

        $status = $isIncomplete ? 'draft' : 'part_b_generated';

        $record->update([
            'part_b_data' => json_encode($partB),
            'part_b_pdf_path' => $path,
            'part_b_generated_at' => now(),
            'part_b_generated_by' => Auth::id(),
            'final_pdf_path' => $finalPath,
            'status' => $status,
            'generated_by' => Auth::id(),
            'generated_at' => now(),
            'updated_by' => Auth::id(),
        ]);

        $this->audit($record, 'part_b_generated', ['incomplete' => $isIncomplete]);
        $this->audit($record, 'pdf_generated', ['incomplete' => $isIncomplete]);

        return $record->fresh();
    }

    public function verify(Form16Record $record): Form16Record
    {
        $this->assertRecordBelongsToCompany($record->id, $record->company_id);
        if (!$record->hasPartBPdf()) {
            throw new \RuntimeException('Generate Part B PDF before verification.');
        }
        if (count($record->blockingValidationErrors())) {
            throw new \RuntimeException('Fix missing information before verification.');
        }
        $record->update([
            'status' => 'verified',
            'verified_by' => Auth::id(),
            'verified_at' => now(),
            'updated_by' => Auth::id(),
        ]);
        $this->audit($record, 'verified');
        return $record->fresh();
    }

    public function issue(Form16Record $record): Form16Record
    {
        $this->assertRecordBelongsToCompany($record->id, $record->company_id);
        if (!$record->isIssuable()) {
            throw new \RuntimeException('Form 16 cannot be issued until Part B PDF exists and required data is complete.');
        }
        $record->update([
            'status' => 'issued',
            'issued_at' => now(),
            'updated_by' => Auth::id(),
        ]);
        $this->audit($record, 'issued');
        return $record->fresh();
    }

    public function regenerate(Form16Record $record, string $reason): Form16Record
    {
        $this->assertRecordBelongsToCompany($record->id, $record->company_id);
        $employee = $this->assertEmployeeBelongsToCompany($record->employee_id, $record->company_id);

        $record->update(['is_latest' => 0, 'updated_by' => Auth::id()]);

        $fyMeta = $this->parseFinancialYear($record->financial_year);
        $employer = $this->employerSnapshot();
        $period = $this->employmentPeriod($employee, $fyMeta['fy_start_date'], $fyMeta['fy_end_date']);
        $payroll = $this->aggregatePayroll($employee, $fyMeta);
        $partB = $this->buildPartBData($employee, $fyMeta, $payroll, $record->tax_regime ?: 'unknown');
        $errors = $this->validateForGeneration($employee, $fyMeta, $partB);

        $new = Form16Record::create([
            'company_id' => $record->company_id,
            'employee_id' => $record->employee_id,
            'financial_year' => $record->financial_year,
            'assessment_year' => $record->assessment_year,
            'fy_start_date' => $record->fy_start_date,
            'fy_end_date' => $record->fy_end_date,
            'employment_period_from' => $period['from'],
            'employment_period_to' => $period['to'],
            'version' => $record->version + 1,
            'previous_version_id' => $record->id,
            'is_latest' => 1,
            'regeneration_reason' => $reason,
            'status' => count($errors) ? 'data_pending' : 'draft',
            'tax_regime' => $record->tax_regime,
            'employee_name' => $employee->name,
            'employee_code' => $employee->employee_id,
            'employee_pan' => $employee->tax_payer_id,
            'employee_email' => $employee->email,
            'employee_address' => $employee->address,
            'employer_name' => $employer['name'],
            'employer_address' => $employer['address'],
            'employer_pan' => $employer['pan'],
            'employer_tan' => $employer['tan'],
            'employer_email' => $employer['email'],
            'part_b_data' => json_encode($partB),
            'gross_salary' => $partB['salary']['gross_salary'],
            'exempt_allowances' => $partB['salary']['less_exempt_allowances_u_s_10'],
            'net_salary' => $partB['salary']['net_salary'],
            'deduction_under_section_16' => $partB['salary']['deductions_u_s_16']['total'],
            'income_chargeable_salaries' => $partB['salary']['income_chargeable_under_salaries'],
            'tds_deducted' => $partB['tax_computation']['tds_deducted_as_per_payroll'],
            'validation_errors' => json_encode($errors),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        $this->audit($new, 'regenerated', [
            'previous_version' => $record->version,
            'reason' => $reason,
        ]);

        return $new;
    }

    public function sendEmail(Form16Record $record): Form16EmailLog
    {
        $this->assertRecordBelongsToCompany($record->id, $record->company_id);
        $employee = $this->assertEmployeeBelongsToCompany($record->employee_id, $record->company_id);

        if (!$record->hasPartBPdf() && !$record->hasFinalPdf()) {
            throw new \RuntimeException('Generate Form 16 PDF before sending email.');
        }
        if (count($record->blockingValidationErrors())) {
            throw new \RuntimeException('Fix missing information before sending Form 16 to the employee.');
        }

        $email = $employee->email ?: $record->employee_email;
        if (empty($email)) {
            throw new \RuntimeException('Employee email is missing.');
        }

        $subject = sprintf('Form 16 - %s - %s', $record->financial_year, $record->employer_name ?: 'Employer');
        $attempt = (int) Form16EmailLog::where('form16_id', $record->id)->count() + 1;

        $log = Form16EmailLog::create([
            'form16_id' => $record->id,
            'company_id' => $record->company_id,
            'employee_id' => $record->employee_id,
            'email' => $email,
            'subject' => $subject,
            'status' => 'queued',
            'attempt' => $attempt,
            'sent_by' => Auth::id(),
        ]);

        try {
            $pdfPath = $record->final_pdf_path ?: $record->part_b_pdf_path;
            $absolute = Storage::disk(self::STORAGE_DISK)->path($pdfPath);

            Mail::to($email)->send(new Form16Send($record, $absolute, $subject));

            $log->update(['status' => 'sent', 'sent_at' => now()]);
            $record->update([
                'email_status' => 'sent',
                'last_emailed_at' => now(),
                'last_emailed_to' => $email,
                'status' => 'sent',
                'updated_by' => Auth::id(),
            ]);
            $this->audit($record, 'emailed', ['email' => $email, 'attempt' => $attempt]);
        } catch (Throwable $e) {
            Log::error('Form16 email failed: ' . $e->getMessage());
            $log->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            $record->update(['email_status' => 'failed', 'updated_by' => Auth::id()]);
            $this->audit($record, 'email_failed', ['error' => $e->getMessage()]);
            throw $e;
        }

        return $log->fresh();
    }

    public function audit(Form16Record $record, string $action, array $metadata = []): void
    {
        Form16AuditLog::create([
            'form16_id' => $record->id,
            'company_id' => $record->company_id,
            'employee_id' => $record->employee_id,
            'user_id' => Auth::id(),
            'action' => $action,
            'metadata' => $metadata ? json_encode($metadata) : null,
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 500),
        ]);
    }

    public function storagePath(string $relative): string
    {
        return Storage::disk(self::STORAGE_DISK)->path($relative);
    }

    public function downloadAuthorized(Form16Record $record, string $which = 'final'): array
    {
        $this->assertRecordBelongsToCompany($record->id, $record->company_id);
        $path = match ($which) {
            'part_a' => $record->part_a_file_path,
            'part_b' => $record->part_b_pdf_path,
            default => $record->final_pdf_path ?: $record->part_b_pdf_path,
        };

        if (!$path || !Storage::disk(self::STORAGE_DISK)->exists($path)) {
            $label = match ($which) {
                'part_a' => 'Part A',
                'part_b' => 'Part B',
                default => 'Form 16 package',
            };
            throw new \RuntimeException(__(':label PDF is not available yet. Generate / upload it first.', ['label' => $label]));
        }

        $this->audit($record, 'downloaded', ['which' => $which]);
        $name = sprintf(
            'Form16_%s_%s_v%d_%s.pdf',
            preg_replace('/[^A-Za-z0-9_\-]/', '_', (string) $record->employee_code),
            $record->financial_year,
            $record->version,
            $which
        );
        return [$this->storagePath($path), $name];
    }
}
