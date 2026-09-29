<?php

namespace App\Services;

use App\Models\DependentData;
use App\Models\EdcutionTable;
use App\Models\Employee;
use App\Models\EmployeeDeductionsDetails;
use App\Models\EmployeeDocument;
use App\Models\EmployeeEarningsDetails;
use App\Models\EXPTable;
use App\Models\User;
use App\Models\WorkingShiftEmployees;

class EmployeeDataSnapshotService
{
    /**
     * Capture a full module-keyed snapshot of an employee and related records.
     */
    public function capture(int $employeeId): array
    {
        $employee = Employee::find($employeeId);
        if ($employee === null) {
            return [];
        }

        $attrs = $employee->getAttributes();

        $earnings = EmployeeEarningsDetails::where('employees_id', $employeeId)
            ->orderBy('id')
            ->get()
            ->map(function ($row) {
                return [
                    'earning_type' => (int) ($row->earning_type ?? 0),
                    'earning_id' => (int) ($row->earning_id ?? 0),
                    'amount' => $row->amount,
                    'amount_type' => (int) ($row->amount_type ?? 0),
                    'status' => (int) ($row->status ?? 1),
                ];
            })
            ->values()
            ->all();

        $deductions = EmployeeDeductionsDetails::where('employees_id', $employeeId)
            ->orderBy('id')
            ->get()
            ->map(function ($row) {
                return [
                    'deduction_type' => (int) ($row->deduction_type ?? 0),
                    'deduction_id' => (int) ($row->deduction_id ?? 0),
                    'amount' => $row->amount,
                    'amount_type' => (int) ($row->amount_type ?? 0),
                    'status' => (int) ($row->status ?? 1),
                ];
            })
            ->values()
            ->all();

        $workingShifts = WorkingShiftEmployees::where('employee_id', $employeeId)
            ->orderBy('working_shifts_id')
            ->pluck('working_shifts_id')
            ->map(function ($id) {
                return (int) $id;
            })
            ->values()
            ->all();

        $experience = EXPTable::where('employee_id', $employeeId)
            ->orderBy('id')
            ->get()
            ->map(function ($row) {
                $a = $row->getAttributes();

                return [
                    'previous_company_name' => $a['previous_company_name'] ?? null,
                    'previous_job_title' => $a['previous_job_title'] ?? null,
                    'previous_from_date' => $a['previous_from_date'] ?? null,
                    'previous_to_date' => $a['previous_to_date'] ?? null,
                    'previous_company_designation_id' => $a['previous_company_designation_id'] ?? null,
                    'experiance_Job_description' => $a['experiance_Job_description'] ?? null,
                ];
            })
            ->values()
            ->all();

        $education = EdcutionTable::where('employee_id', $employeeId)
            ->orderBy('id')
            ->get()
            ->map(function ($row) {
                $a = $row->getAttributes();

                return [
                    'institute_name' => $a['institute_name'] ?? null,
                    'education' => $a['education'] ?? null,
                    'specialization' => $a['specialization'] ?? null,
                    'date_of_completion' => $a['date_of_completion'] ?? null,
                    'notes' => $a['notes'] ?? null,
                ];
            })
            ->values()
            ->all();

        $dependents = DependentData::where('employee_id', $employeeId)
            ->orderBy('id')
            ->get()
            ->map(function ($row) {
                $a = $row->getAttributes();

                return [
                    'dependent_name' => $a['dependent_name'] ?? null,
                    'dependent_relation' => $a['dependent_relation'] ?? null,
                    'dependent_dob' => $a['dependent_dob'] ?? null,
                    'emergency_phone_no' => $a['emergency_phone_no'] ?? null,
                ];
            })
            ->values()
            ->all();

        $documents = EmployeeDocument::where('employee_id', $employeeId)
            ->orderBy('document_id')
            ->get()
            ->map(function ($row) {
                return [
                    'document_id' => (int) ($row->document_id ?? 0),
                    'document_value' => $row->document_value,
                ];
            })
            ->values()
            ->all();

        $userAccount = [
            'name' => null,
            'email' => null,
            'avatar' => null,
        ];
        if (!empty($attrs['user_id'])) {
            $user = User::select('name', 'email', 'avatar')->find($attrs['user_id']);
            if ($user !== null) {
                $userAccount = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                ];
            }
        }

        return [
            'basic_details' => [
                'employee_id' => $attrs['employee_id'] ?? null,
                'name' => $attrs['name'] ?? null,
                'email' => $attrs['email'] ?? null,
                'personal_email' => $attrs['personal_email'] ?? null,
                'phone' => $attrs['phone'] ?? null,
                'dob' => $attrs['dob'] ?? null,
                'gender' => $attrs['gender'] ?? null,
                'address' => $attrs['address'] ?? null,
                'marital_status' => $attrs['marital_status'] ?? null,
                'marriage_anniversary_date' => $attrs['marriage_anniversary_date'] ?? null,
                'employee_profile' => $attrs['employee_profile'] ?? null,
                'employee_experience' => $attrs['employee_experience'] ?? null,
            ],
            'job_org' => [
                'branch_id' => $attrs['branch_id'] ?? null,
                'department_id' => $attrs['department_id'] ?? null,
                'designation_id' => $attrs['designation_id'] ?? null,
                'designation_description' => $attrs['designation_description'] ?? null,
                'company_doj' => $attrs['company_doj'] ?? null,
                'employe_types' => $attrs['employe_types'] ?? null,
                'employe_status' => $attrs['employe_status'] ?? null,
                'grade_type_id' => $attrs['grade_type_id'] ?? null,
                'office_staff' => $attrs['office_staff'] ?? null,
                'Job_description' => $attrs['Job_description'] ?? null,
                'date_of_exit' => $attrs['date_of_exit'] ?? null,
                'probation_period_date' => $attrs['probation_period_date'] ?? null,
                'confirm_probation_period' => $attrs['confirm_probation_period'] ?? null,
                'users_types_id' => $attrs['users_types_id'] ?? null,
            ],
            'salary_ctc' => [
                'play_slab_id' => $attrs['play_slab_id'] ?? null,
                'salary' => $attrs['salary'] ?? null,
                'salary_type' => $attrs['salary_type'] ?? null,
                'ctc_annual' => $attrs['ctc_annual'] ?? null,
                'ctc_percentage' => $attrs['ctc_percentage'] ?? null,
                'statutory_bonus_status' => $attrs['statutory_bonus_status'] ?? null,
                'statutory_bonus' => $attrs['statutory_bonus'] ?? null,
            ],
            'bank_tax' => [
                'account_holder_name' => $attrs['account_holder_name'] ?? null,
                'account_number' => $attrs['account_number'] ?? null,
                'bank_name' => $attrs['bank_name'] ?? null,
                'bank_identifier_code' => $attrs['bank_identifier_code'] ?? null,
                'branch_location' => $attrs['branch_location'] ?? null,
                'tax_payer_id' => $attrs['tax_payer_id'] ?? null,
            ],
            'statutory' => [
                'uan_no' => $attrs['uan_no'] ?? null,
                'epf_status' => $attrs['epf_status'] ?? null,
                'epf_number' => $attrs['epf_number'] ?? null,
                'epf_employee_contribution_rate' => $attrs['epf_employee_contribution_rate'] ?? ($attrs['employee_contribution_rate'] ?? null),
                'epf_employer_contribution_rate' => $attrs['epf_employer_contribution_rate'] ?? ($attrs['employer_contribution_rate'] ?? null),
                'esi_status' => $attrs['esi_status'] ?? null,
                'esi_number' => $attrs['esi_number'] ?? null,
                'esi_employee_contribution_rate' => $attrs['esi_employee_contribution_rate'] ?? null,
                'esi_employer_contribution_rate' => $attrs['esi_employer_contribution_rate'] ?? null,
                'ept_status' => $attrs['ept_status'] ?? null,
                'ept_number' => $attrs['ept_number'] ?? null,
                'tax_deducted_at_source_status' => $attrs['tax_deducted_at_source_status'] ?? null,
                'tax_deducted_at_source' => $attrs['tax_deducted_at_source'] ?? null,
            ],
            'earnings' => $earnings,
            'deductions' => $deductions,
            'working_shifts' => $workingShifts,
            'weekoffs' => [
                'is_custom_weekoffs' => $attrs['is_custom_weekoffs'] ?? null,
                'weekoffs_data' => $attrs['weekoffs_data'] ?? null,
            ],
            'login_access' => [
                'app_login' => $attrs['app_login'] ?? null,
                'web_login' => $attrs['web_login'] ?? null,
                'employee_exit' => $attrs['employee_exit'] ?? null,
                'geofencing_status' => $attrs['geofencing_status'] ?? null,
                'remote_geofencing_status' => $attrs['remote_geofencing_status'] ?? null,
                'latitude' => $attrs['latitude'] ?? null,
                'longitude' => $attrs['longitude'] ?? null,
            ],
            'experience' => $experience,
            'education' => $education,
            'dependents' => $dependents,
            'documents' => $documents,
            'user_account' => $userAccount,
        ];
    }
}
