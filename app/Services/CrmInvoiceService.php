<?php

namespace App\Services;

use App\Mail\CrmInvoiceMail;
use App\Models\CompanyBillingProfile;
use App\Models\CrmInvoice;
use App\Models\CrmInvoiceEmailLog;
use App\Models\CrmInvoiceItem;
use App\Models\CrmInvoiceSetting;
use App\Models\Employee;
use App\Models\User;
use App\Support\IndianCurrency;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CrmInvoiceService
{
    public function settings(): CrmInvoiceSetting
    {
        return CrmInvoiceSetting::current();
    }

    public function countActiveEmployees(int $companyId): int
    {
        return (int) Employee::query()
            ->from('employees as e')
            ->leftJoin('users as u', 'u.id', '=', 'e.user_id')
            ->where('e.created_by', $companyId)
            ->where('e.employee_exit', 0)
            ->where(function ($q) {
                $q->whereNull('e.user_id')
                    ->orWhere('e.user_id', 0)
                    ->orWhere('u.is_active', 1);
            })
            ->count('e.id');
    }

    public function countActiveUsers(int $companyId): int
    {
        return (int) User::query()
            ->where('created_by', $companyId)
            ->where('type', '!=', 'super admin')
            ->where('type', '!=', 'company')
            ->where('type', '!=', 'employee')
            ->where('is_active', 1)
            ->count();
    }

    public function billingProfileFor(int $companyId): ?CompanyBillingProfile
    {
        return CompanyBillingProfile::forCompany($companyId);
    }

    /**
     * Only custom-billing companies are invoiceable via CRM.
     */
    public function isInvoiceableCompany(User $company): bool
    {
        $profile = $this->billingProfileFor($company->id);
        return $profile && $profile->isCustom();
    }

    /**
     * Calculate CGST/SGST from settings (percentage based).
     *
     * @return array{cgst_percent:float,sgst_percent:float,cgst_amount:float,sgst_amount:float,tax_amount:float,total_amount:float}
     */
    public function calculateGst(float $subtotal, ?CrmInvoiceSetting $settings = null, ?array $overrides = null): array
    {
        $settings = $settings ?: $this->settings();
        $cgstPercent = (float) ($overrides['cgst_percent'] ?? $settings->cgst_percent ?? 0);
        $sgstPercent = (float) ($overrides['sgst_percent'] ?? $settings->sgst_percent ?? 0);

        $cgstAmount = round($subtotal * ($cgstPercent / 100), 2);
        $sgstAmount = round($subtotal * ($sgstPercent / 100), 2);
        $taxAmount = round($cgstAmount + $sgstAmount, 2);

        return [
            'cgst_percent' => $cgstPercent,
            'sgst_percent' => $sgstPercent,
            'cgst_amount' => $cgstAmount,
            'sgst_amount' => $sgstAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => round($subtotal + $taxAmount, 2),
        ];
    }

    /**
     * Build invoice line description: product + billing type + month name.
     */
    public function buildLineDescription(string $kind, int $year, int $month, ?string $override = null, ?CrmInvoiceSetting $settings = null): string
    {
        if ($override !== null && trim($override) !== '') {
            return trim($override);
        }

        $settings = $settings ?: $this->settings();
        $product = trim((string) ($settings->product_name ?: 'HR Shastri'));
        $period = Carbon::create($year, $month, 1)->format('F Y');
        $label = $kind === 'user' ? 'Active users' : 'Active employees';

        return sprintf('%s — %s (%s)', $product, $label, $period);
    }

    /**
     * Standard note when user seats are excluded from billing (qty = 0).
     */
    public function usersNotBilledNote(): string
    {
        return 'User seats not billed on this invoice (Active Users = 0).';
    }

    /**
     * Apply "Do not charge users" / Active Users = 0 rules.
     * When exclude_users is on (or qty is 0), force qty to 0 and append invoice note.
     */
    public function applyUserBillingOptions(array $data, ?int $detectedUserCount = null): array
    {
        if (!empty($data['exclude_users'])) {
            $data['active_users_count'] = 0;
        }

        $userQty = array_key_exists('active_users_count', $data)
            ? (int) $data['active_users_count']
            : null;

        $excludeUsers = !empty($data['exclude_users']) || $userQty === 0;

        if ($excludeUsers) {
            $data['active_users_count'] = 0;
            $note = trim((string) ($data['notes'] ?? ''));
            $marker = $this->usersNotBilledNote();
            if ($note === '' || stripos($note, 'User seats not billed') === false) {
                $data['notes'] = $note === '' ? $marker : ($note . "\n" . $marker);
            }
            if ($detectedUserCount !== null && $detectedUserCount > 0) {
                $extra = sprintf('Detected active users in system: %d (not charged).', $detectedUserCount);
                if (stripos((string) $data['notes'], 'Detected active users') === false) {
                    $data['notes'] .= "\n" . $extra;
                }
            }
        }

        $data['exclude_users'] = $excludeUsers;

        return $data;
    }

    public function shouldBillUsers(int $userQty): bool
    {
        return $userQty > 0;
    }

    public function resolveCompanyDetails(User $company): array
    {
        $settings = [];
        try {
            $rows = DB::table('settings')->where('created_by', $company->id)->pluck('value', 'name');
            if ($rows->isEmpty()) {
                $rows = DB::table('settings')->where('created_by', 1)->pluck('value', 'name');
            }
            $settings = $rows->toArray();
        } catch (Throwable $e) {
            // ignore settings lookup failures
        }

        $addressParts = array_filter([
            $settings['company_address'] ?? null,
            $settings['company_city'] ?? null,
            $settings['company_state'] ?? null,
            $settings['company_zipcode'] ?? null,
            $settings['company_country'] ?? null,
        ]);

        return [
            'company_id' => $company->id,
            'company_name' => $company->name,
            'company_email' => $company->email ?: ($settings['company_email'] ?? null),
            'company_address' => !empty($addressParts) ? implode(', ', $addressParts) : null,
            'company_phone' => $settings['company_telephone'] ?? null,
        ];
    }

    public function nextInvoiceNumber(CrmInvoiceSetting $settings, ?Carbon $date = null): string
    {
        $number = (int) $settings->next_number;
        $prefix = trim((string) ($settings->invoice_prefix ?: 'INV'));
        $date = $date ?: Carbon::now();

        if ($settings->use_fy_numbering ?? true) {
            // Indian FY: Apr–Mar → INV/26-27/000004
            $fyStart = $date->month >= 4 ? $date->year : $date->year - 1;
            $fyLabel = sprintf('%02d-%02d', $fyStart % 100, ($fyStart + 1) % 100);
            $invoiceNumber = sprintf('%s/%s/%06d', $prefix, $fyLabel, $number);
        } else {
            $invoiceNumber = sprintf('%s-%s-%05d', $prefix, $date->format('Ym'), $number);
        }

        $settings->next_number = $number + 1;
        $settings->save();

        return $invoiceNumber;
    }

    /**
     * Snapshot TAX INVOICE header/meta fields from settings + request overrides.
     */
    public function taxInvoiceMeta(CrmInvoiceSetting $settings, array $data = [], ?array $companyDetails = null): array
    {
        $billAddress = $data['company_address'] ?? ($companyDetails['company_address'] ?? null);
        $billName = $data['company_name'] ?? ($companyDetails['company_name'] ?? null);

        return [
            'company_gstin' => $data['company_gstin'] ?? null,
            'ship_to_name' => $data['ship_to_name'] ?? $billName,
            'ship_to_address' => $data['ship_to_address'] ?? $billAddress,
            'place_of_supply' => $data['place_of_supply'] ?? ($settings->seller_state ?: 'Gujarat'),
            'place_of_supply_code' => $data['place_of_supply_code'] ?? ($settings->seller_state_code ?: '24'),
            'terms' => $data['terms'] ?? ($settings->default_terms ?: 'Due on Receipt'),
            'seller_gstin' => $settings->seller_gstin,
            'seller_name_snapshot' => $settings->seller_name,
            'seller_address_snapshot' => $settings->seller_address,
            'notes' => $data['notes'] ?? ($settings->default_notes ?: null),
            'payment_instructions' => $data['payment_instructions'] ?? $this->formatBankPaymentInstructions($settings),
        ];
    }

    public function formatBankPaymentInstructions(CrmInvoiceSetting $settings): ?string
    {
        $lines = array_filter([
            $settings->seller_name,
            $settings->bank_name,
            $settings->bank_account_no ? ('ACCOUNT NO -' . $settings->bank_account_no) : null,
            $settings->bank_ifsc ? ('IFS CODE - ' . $settings->bank_ifsc) : null,
            $settings->bank_swift ? ('SWIFT CODE - ' . $settings->bank_swift) : null,
        ]);

        if (!empty($lines)) {
            return implode("\n", $lines);
        }

        return $settings->payment_instructions;
    }

    /**
     * Line-level CGST/SGST for Zoho-style columns.
     *
     * @return array{cgst_percent:float,sgst_percent:float,cgst_amount:float,sgst_amount:float,hsn_sac:?string,unit:string}
     */
    public function lineTaxMeta(float $lineTotal, CrmInvoiceSetting $settings, ?array $gst = null): array
    {
        $gst = $gst ?: $this->calculateGst($lineTotal, $settings);
        $cgstPercent = (float) ($gst['cgst_percent'] ?? $settings->cgst_percent ?? 0);
        $sgstPercent = (float) ($gst['sgst_percent'] ?? $settings->sgst_percent ?? 0);

        return [
            'hsn_sac' => $settings->default_hsn_sac ?: '997331',
            'unit' => 'Nos',
            'cgst_percent' => $cgstPercent,
            'cgst_amount' => round($lineTotal * ($cgstPercent / 100), 2),
            'sgst_percent' => $sgstPercent,
            'sgst_amount' => round($lineTotal * ($sgstPercent / 100), 2),
        ];
    }

    public function withAmountInWords(array $payload): array
    {
        $payload['total_in_words'] = IndianCurrency::toWords((float) ($payload['total_amount'] ?? 0));

        return $payload;
    }

    public function autoPeriodKey(int $companyId, int $year, int $month): string
    {
        return sprintf('%d-%04d-%02d', $companyId, $year, $month);
    }

    /**
     * Generate monthly invoices for active companies.
     */
    public function generateMonthly(int $year, int $month, ?int $companyId = null, bool $dryRun = false, bool $sendEmail = true): array
    {
        $settings = $this->settings();
        $summary = [
            'year' => $year,
            'month' => $month,
            'dry_run' => $dryRun,
            'companies_scanned' => 0,
            'created' => 0,
            'skipped_existing' => 0,
            'skipped_zero' => 0,
            'skipped_global' => 0,
            'skipped_auto_disabled' => 0,
            'emails_sent' => 0,
            'errors' => [],
            'invoices' => [],
            'message' => null,
        ];

        // Temporary pause: custom-billing companies are invoiced manually only.
        if (!(bool) ($settings->auto_generate_enabled ?? false)) {
            $summary['skipped_auto_disabled'] = 1;
            $summary['message'] = 'Automatic CRM invoice generation is disabled. Create invoices manually for custom-billing companies.';
            return $summary;
        }

        $query = User::query()
            ->where('type', 'company')
            ->where('is_active', 1)
            ->orderBy('id');

        if ($companyId) {
            $query->where('id', $companyId);
        }

        $query->chunkById(50, function ($companies) use ($settings, $year, $month, $dryRun, $sendEmail, &$summary) {
            foreach ($companies as $company) {
                $summary['companies_scanned']++;

                if (!$this->isInvoiceableCompany($company)) {
                    $summary['skipped_global']++;
                    continue;
                }

                try {
                    $result = $this->generateForCompany($company, $year, $month, $settings, $dryRun, $sendEmail);
                    if ($result['status'] === 'created') {
                        $summary['created']++;
                        if (!empty($result['email_sent'])) {
                            $summary['emails_sent']++;
                        }
                        $summary['invoices'][] = $result;
                    } elseif ($result['status'] === 'skipped_existing') {
                        $summary['skipped_existing']++;
                    } elseif ($result['status'] === 'skipped_zero') {
                        $summary['skipped_zero']++;
                    }
                } catch (Throwable $e) {
                    $summary['errors'][] = [
                        'company_id' => $company->id,
                        'message' => $e->getMessage(),
                    ];
                    Log::error('CRM invoice generate failed', [
                        'company_id' => $company->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });

        return $summary;
    }

    public function generateForCompany(
        User $company,
        int $year,
        int $month,
        ?CrmInvoiceSetting $settings = null,
        bool $dryRun = false,
        bool $sendEmail = true
    ): array {
        $settings = $settings ?: $this->settings();
        $periodKey = $this->autoPeriodKey($company->id, $year, $month);

        $existing = CrmInvoice::query()
            ->where(function ($q) use ($periodKey, $company, $year, $month) {
                $q->where('auto_period_key', $periodKey)
                    ->orWhere(function ($q2) use ($company, $year, $month) {
                        $q2->where('company_id', $company->id)
                            ->where('billing_year', $year)
                            ->where('billing_month', $month)
                            ->where('source', CrmInvoice::SOURCE_AUTO);
                    });
            })
            ->first();

        if ($existing) {
            return [
                'status' => 'skipped_existing',
                'company_id' => $company->id,
                'invoice_id' => $existing->id,
                'invoice_number' => $existing->invoice_number,
            ];
        }

        $activeCount = $this->countActiveEmployees($company->id);
        if ($activeCount <= 0 && $settings->skip_zero_employee) {
            return [
                'status' => 'skipped_zero',
                'company_id' => $company->id,
                'active_employees' => 0,
            ];
        }

        $price = (float) $settings->price_per_employee;
        $subtotal = round($activeCount * $price, 2);
        $gst = $this->calculateGst($subtotal, $settings);
        $details = $this->resolveCompanyDetails($company);
        $invoiceDate = Carbon::create($year, $month, 1)->endOfMonth()->toDateString();
        $dueDate = Carbon::parse($invoiceDate)->addDays((int) $settings->due_days)->toDateString();

        if ($dryRun) {
            return [
                'status' => 'created',
                'dry_run' => true,
                'company_id' => $company->id,
                'company_name' => $company->name,
                'active_employees' => $activeCount,
                'total_amount' => $gst['total_amount'],
            ];
        }

        $invoice = DB::transaction(function () use (
            $settings, $details, $year, $month, $invoiceDate, $dueDate,
            $activeCount, $price, $subtotal, $gst, $periodKey
        ) {
            $invoiceNumber = $this->nextInvoiceNumber($settings, Carbon::parse($invoiceDate));
            $meta = $this->taxInvoiceMeta($settings, [], $details);
            $lineTax = $this->lineTaxMeta($subtotal, $settings, $gst);

            $invoice = CrmInvoice::create($this->withAmountInWords(array_merge($details, $meta, [
                'invoice_number' => $invoiceNumber,
                'billing_year' => $year,
                'billing_month' => $month,
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'source' => CrmInvoice::SOURCE_AUTO,
                'status' => CrmInvoice::STATUS_GENERATED,
                'auto_period_key' => $periodKey,
                'active_employees_count' => $activeCount,
                'price_per_employee' => $price,
                'subtotal' => $subtotal,
                'cgst_percent' => $gst['cgst_percent'],
                'sgst_percent' => $gst['sgst_percent'],
                'cgst_amount' => $gst['cgst_amount'],
                'sgst_amount' => $gst['sgst_amount'],
                'tax_amount' => $gst['tax_amount'],
                'total_amount' => $gst['total_amount'],
                'currency' => $settings->currency ?: 'INR',
                'created_by' => Auth::id(),
            ])));

            CrmInvoiceItem::create(array_merge($lineTax, [
                'invoice_id' => $invoice->id,
                'description' => $this->buildLineDescription('employee', $year, $month, null, $settings),
                'quantity' => max($activeCount, 0),
                'unit_price' => $price,
                'line_total' => $subtotal,
                'sort_order' => 1,
            ]));

            return $invoice->fresh(['items']);
        });

        $this->generatePdf($invoice);

        $emailSent = false;
        if ($sendEmail && $settings->auto_email_on_generate && !empty($invoice->company_email)) {
            $emailSent = $this->sendInvoiceEmail($invoice, [$invoice->company_email], Auth::id());
            if ($emailSent && $invoice->status !== CrmInvoice::STATUS_PAID) {
                $invoice->status = CrmInvoice::STATUS_SENT;
                $invoice->save();
            }
        }

        return [
            'status' => 'created',
            'company_id' => $company->id,
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'active_employees' => $activeCount,
            'total_amount' => $gst['total_amount'],
            'email_sent' => $emailSent,
        ];
    }

    public function createManual(array $data, int $actorId): CrmInvoice
    {
        $settings = $this->settings();
        $company = User::where('type', 'company')->findOrFail($data['company_id']);

        if (!$this->isInvoiceableCompany($company)) {
            throw new \RuntimeException(
                'This company uses a global subscription plan. CRM invoices are only for custom employee/user billing companies.'
            );
        }

        $details = $this->resolveCompanyDetails($company);
        $profile = $this->billingProfileFor($company->id);

        $detectedUsers = $this->countActiveUsers($company->id);
        $data = $this->applyUserBillingOptions($data, $detectedUsers);

        $empQty = (int) ($data['active_employees_count'] ?? $this->countActiveEmployees($company->id));
        $userQty = array_key_exists('active_users_count', $data)
            ? (int) $data['active_users_count']
            : $detectedUsers;

        if (!empty($data['exclude_users'])) {
            $userQty = 0;
        }

        $empPrice = isset($data['price_per_employee'])
            ? (float) $data['price_per_employee']
            : (float) ($profile->price_per_employee ?? $settings->price_per_employee);
        $userPrice = isset($data['price_per_user'])
            ? (float) $data['price_per_user']
            : (float) ($profile->price_per_user ?? 0);

        // When users are not billed, keep price snapshot but charge 0.
        if (!$this->shouldBillUsers($userQty)) {
            $userQty = 0;
        }

        $empLine = round($empQty * $empPrice, 2);
        $userLine = round($userQty * $userPrice, 2);
        $subtotal = round($empLine + $userLine, 2);
        $gst = $this->calculateGst($subtotal, $settings, [
            'cgst_percent' => $data['cgst_percent'] ?? null,
            'sgst_percent' => $data['sgst_percent'] ?? null,
        ]);

        $year = (int) $data['billing_year'];
        $month = (int) $data['billing_month'];
        $invoiceDate = $data['invoice_date'] ?? Carbon::now()->toDateString();
        $dueDate = $data['due_date'] ?? Carbon::parse($invoiceDate)->addDays((int) $settings->due_days)->toDateString();

        $invoice = DB::transaction(function () use (
            $settings, $details, $data, $year, $month, $invoiceDate, $dueDate,
            $empQty, $userQty, $empPrice, $userPrice, $empLine, $userLine, $subtotal, $gst, $actorId
        ) {
            $meta = $this->taxInvoiceMeta($settings, $data, $details);
            $empTax = $this->lineTaxMeta($empLine, $settings, $gst);
            $userTax = $this->lineTaxMeta($userLine, $settings, $gst);

            $invoice = CrmInvoice::create($this->withAmountInWords(array_merge($details, $meta, [
                'invoice_number' => $this->nextInvoiceNumber($settings, Carbon::parse($invoiceDate)),
                'company_name' => $data['company_name'] ?? $details['company_name'],
                'company_email' => $data['company_email'] ?? $details['company_email'],
                'company_address' => $data['company_address'] ?? $details['company_address'],
                'company_phone' => $data['company_phone'] ?? $details['company_phone'],
                'billing_year' => $year,
                'billing_month' => $month,
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'source' => CrmInvoice::SOURCE_MANUAL,
                'status' => CrmInvoice::STATUS_DRAFT,
                'auto_period_key' => null,
                'active_employees_count' => $empQty,
                'active_users_count' => $userQty,
                'price_per_employee' => $empPrice,
                'price_per_user' => $userPrice,
                'subtotal' => $subtotal,
                'cgst_percent' => $gst['cgst_percent'],
                'sgst_percent' => $gst['sgst_percent'],
                'cgst_amount' => $gst['cgst_amount'],
                'sgst_amount' => $gst['sgst_amount'],
                'tax_amount' => $gst['tax_amount'],
                'total_amount' => $gst['total_amount'],
                'currency' => $settings->currency ?: 'INR',
                'created_by' => $actorId,
                'updated_by' => $actorId,
            ])));

            CrmInvoiceItem::create(array_merge($empTax, [
                'invoice_id' => $invoice->id,
                'description' => $this->buildLineDescription(
                    'employee',
                    $year,
                    $month,
                    $data['employee_description'] ?? $data['description'] ?? null,
                    $settings
                ),
                'quantity' => max($empQty, 0),
                'unit_price' => $empPrice,
                'line_total' => $empLine,
                'sort_order' => 1,
            ]));

            if ($this->shouldBillUsers($userQty)) {
                CrmInvoiceItem::create(array_merge($userTax, [
                    'invoice_id' => $invoice->id,
                    'description' => $this->buildLineDescription(
                        'user',
                        $year,
                        $month,
                        $data['user_description'] ?? null,
                        $settings
                    ),
                    'quantity' => max($userQty, 0),
                    'unit_price' => $userPrice,
                    'line_total' => $userLine,
                    'sort_order' => 2,
                ]));
            }

            return $invoice->fresh(['items']);
        });

        $this->generatePdf($invoice);

        return $invoice->fresh(['items']);
    }

    public function updateInvoice(CrmInvoice $invoice, array $data, int $actorId): CrmInvoice
    {
        if (!$invoice->isEditable()) {
            throw new \RuntimeException('Paid or cancelled invoices cannot be edited.');
        }

        $detectedUsers = null;
        if (!empty($invoice->company_id)) {
            $detectedUsers = $this->countActiveUsers((int) $invoice->company_id);
        }
        $data = $this->applyUserBillingOptions($data, $detectedUsers);

        $empQty = (int) ($data['active_employees_count'] ?? $invoice->active_employees_count);
        $userQty = (int) ($data['active_users_count'] ?? $invoice->active_users_count ?? 0);
        if (!empty($data['exclude_users'])) {
            $userQty = 0;
        }
        $empPrice = (float) ($data['price_per_employee'] ?? $invoice->price_per_employee);
        $userPrice = (float) ($data['price_per_user'] ?? $invoice->price_per_user ?? 0);

        if (!$this->shouldBillUsers($userQty)) {
            $userQty = 0;
        }

        $empLine = round($empQty * $empPrice, 2);
        $userLine = round($userQty * $userPrice, 2);
        $subtotal = round($empLine + $userLine, 2);

        $gst = $this->calculateGst($subtotal, $this->settings(), [
            'cgst_percent' => $data['cgst_percent'] ?? $invoice->cgst_percent,
            'sgst_percent' => $data['sgst_percent'] ?? $invoice->sgst_percent,
        ]);

        DB::transaction(function () use (
            $invoice, $data, $empQty, $userQty, $empPrice, $userPrice,
            $empLine, $userLine, $subtotal, $gst, $actorId
        ) {
            $settings = $this->settings();
            $meta = $this->taxInvoiceMeta($settings, array_merge([
                'company_name' => $data['company_name'] ?? $invoice->company_name,
                'company_address' => $data['company_address'] ?? $invoice->company_address,
                'company_gstin' => $data['company_gstin'] ?? $invoice->company_gstin,
                'ship_to_name' => $data['ship_to_name'] ?? $invoice->ship_to_name,
                'ship_to_address' => $data['ship_to_address'] ?? $invoice->ship_to_address,
                'place_of_supply' => $data['place_of_supply'] ?? $invoice->place_of_supply,
                'place_of_supply_code' => $data['place_of_supply_code'] ?? $invoice->place_of_supply_code,
                'terms' => $data['terms'] ?? $invoice->terms,
                'notes' => array_key_exists('notes', $data) ? $data['notes'] : $invoice->notes,
                'payment_instructions' => array_key_exists('payment_instructions', $data)
                    ? $data['payment_instructions']
                    : $invoice->payment_instructions,
            ], $data));

            $invoice->fill($this->withAmountInWords(array_merge($meta, [
                'company_name' => $data['company_name'] ?? $invoice->company_name,
                'company_email' => $data['company_email'] ?? $invoice->company_email,
                'company_address' => $data['company_address'] ?? $invoice->company_address,
                'company_phone' => $data['company_phone'] ?? $invoice->company_phone,
                'billing_year' => (int) ($data['billing_year'] ?? $invoice->billing_year),
                'billing_month' => (int) ($data['billing_month'] ?? $invoice->billing_month),
                'invoice_date' => $data['invoice_date'] ?? $invoice->invoice_date,
                'due_date' => $data['due_date'] ?? $invoice->due_date,
                'active_employees_count' => $empQty,
                'active_users_count' => $userQty,
                'price_per_employee' => $empPrice,
                'price_per_user' => $userPrice,
                'subtotal' => $subtotal,
                'cgst_percent' => $gst['cgst_percent'],
                'sgst_percent' => $gst['sgst_percent'],
                'cgst_amount' => $gst['cgst_amount'],
                'sgst_amount' => $gst['sgst_amount'],
                'tax_amount' => $gst['tax_amount'],
                'total_amount' => $gst['total_amount'],
                'updated_by' => $actorId,
            ])));

            if ($invoice->status === CrmInvoice::STATUS_DRAFT) {
                $invoice->status = CrmInvoice::STATUS_GENERATED;
            }

            $invoice->save();

            $year = (int) $invoice->billing_year;
            $month = (int) $invoice->billing_month;
            $empDesc = $data['employee_description'] ?? $data['description'] ?? null;
            $userDesc = $data['user_description'] ?? null;
            $empTax = $this->lineTaxMeta($empLine, $settings, $gst);
            $userTax = $this->lineTaxMeta($userLine, $settings, $gst);

            $items = $invoice->items()->orderBy('sort_order')->get();
            if ($items->count() >= 1) {
                $items[0]->update(array_merge($empTax, [
                    'description' => $this->buildLineDescription('employee', $year, $month, $empDesc, $settings),
                    'quantity' => max($empQty, 0),
                    'unit_price' => $empPrice,
                    'line_total' => $empLine,
                ]));
            }
            if ($items->count() >= 2) {
                if ($this->shouldBillUsers($userQty)) {
                    $items[1]->update(array_merge($userTax, [
                        'description' => $this->buildLineDescription('user', $year, $month, $userDesc, $settings),
                        'quantity' => max($userQty, 0),
                        'unit_price' => $userPrice,
                        'line_total' => $userLine,
                    ]));
                } else {
                    $items[1]->delete();
                }
            } elseif ($this->shouldBillUsers($userQty)) {
                CrmInvoiceItem::create(array_merge($userTax, [
                    'invoice_id' => $invoice->id,
                    'description' => $this->buildLineDescription('user', $year, $month, $userDesc, $settings),
                    'quantity' => max($userQty, 0),
                    'unit_price' => $userPrice,
                    'line_total' => $userLine,
                    'sort_order' => 2,
                ]));
            }
        });

        $invoice = $invoice->fresh(['items']);
        $this->generatePdf($invoice);

        return $invoice;
    }

    public function markPaid(CrmInvoice $invoice, int $actorId): CrmInvoice
    {
        if ($invoice->status === CrmInvoice::STATUS_PAID) {
            return $invoice;
        }

        $invoice->status = CrmInvoice::STATUS_PAID;
        $invoice->paid_at = now();
        $invoice->paid_by = $actorId;
        $invoice->updated_by = $actorId;
        $invoice->save();

        return $invoice;
    }

    public function generatePdf(CrmInvoice $invoice): string
    {
        $invoice->loadMissing('items');
        $settings = $this->settings();

        $pdf = Pdf::loadView('crm_invoice.pdf', compact('invoice', 'settings'))->setPaper('a4');
        $safeNumber = str_replace(['/', '\\'], '-', $invoice->invoice_number);
        $relative = sprintf(
            'app/crm_invoices/%04d/%02d/%s.pdf',
            $invoice->billing_year,
            $invoice->billing_month,
            $safeNumber
        );

        Storage::disk('local')->put($relative, $pdf->output());

        // Prefer Laravel-conventional path under storage/app (local disk root is storage_path('/')).
        // Also remove legacy storage/crm_invoices copies if present.
        $legacy = sprintf(
            'crm_invoices/%04d/%02d/%s.pdf',
            $invoice->billing_year,
            $invoice->billing_month,
            $safeNumber
        );
        if ($legacy !== $relative && Storage::disk('local')->exists($legacy)) {
            Storage::disk('local')->delete($legacy);
        }

        $invoice->pdf_path = $relative;
        $invoice->save();

        return $relative;
    }

    /**
     * Resolve PDF path for download/email, regenerating if missing.
     * Supports legacy paths stored as crm_invoices/... (without app/).
     */
    public function resolvePdfPath(CrmInvoice $invoice): string
    {
        $candidates = array_filter([
            $invoice->pdf_path,
            $invoice->pdf_path && str_starts_with($invoice->pdf_path, 'app/')
                ? null
                : (isset($invoice->pdf_path) ? 'app/' . ltrim($invoice->pdf_path, '/') : null),
            $invoice->pdf_path && str_starts_with((string) $invoice->pdf_path, 'app/')
                ? substr($invoice->pdf_path, 4)
                : null,
        ]);

        foreach ($candidates as $path) {
            if ($path && Storage::disk('local')->exists($path)) {
                if ($invoice->pdf_path !== $path) {
                    $invoice->pdf_path = $path;
                    $invoice->save();
                }
                return $path;
            }
        }

        return $this->generatePdf($invoice);
    }

    public function sendInvoiceEmail(CrmInvoice $invoice, array $recipients, ?int $sentBy = null): bool
    {
        $recipients = array_values(array_unique(array_filter(array_map('trim', $recipients))));
        if (empty($recipients)) {
            return false;
        }

        $settings = $this->settings();
        $subject = sprintf(
            '%s Invoice %s — %s',
            $settings->product_name ?: 'HR Shastri',
            $invoice->invoice_number,
            $invoice->periodLabel()
        );

        $log = CrmInvoiceEmailLog::create([
            'invoice_id' => $invoice->id,
            'recipients' => implode(',', $recipients),
            'subject' => $subject,
            'status' => 'queued',
            'sent_by' => $sentBy,
        ]);

        try {
            $pdfPath = $this->resolvePdfPath($invoice);
            $invoice->refresh();
            $pdfBinary = Storage::disk('local')->get($pdfPath);

            Mail::to($recipients)->send(new CrmInvoiceMail($invoice, $settings, $subject, $pdfBinary));

            $log->status = 'sent';
            $log->sent_at = now();
            $log->save();

            if ($invoice->isEditable() && $invoice->status !== CrmInvoice::STATUS_PAID) {
                $invoice->status = CrmInvoice::STATUS_SENT;
                $invoice->save();
            }

            return true;
        } catch (Throwable $e) {
            $log->status = 'failed';
            $log->error_message = $e->getMessage();
            $log->save();

            Log::error('CRM invoice email failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
