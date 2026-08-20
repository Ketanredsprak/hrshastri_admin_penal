<?php

namespace App\Exports;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;

class EmployeesExport implements FromCollection, WithHeadings
{
     protected $statusFilter;

     public function __construct($statusFilter = 'all')
     {
          $this->statusFilter = $statusFilter;
     }

     /**
      * @return \Illuminate\Support\Collection
      */
     public function collection()
     {
          // Build query based on user type (same logic as index method)
          if (Auth::user()->type == 'employee')
          {
               $data = Employee::with('branch', 'department', 'designation', 'playslab')
                    ->where('user_id', '=', Auth::user()->id);
          }
          else if (Auth::user()->type == 'company')
          {
               $data = Employee::with('branch', 'department', 'designation', 'playslab')
                    ->where('created_by', Auth::user()->creatorId());
          }
          else
          {
               $data = Employee::whereIn('id', selectedEmployee() ?? [])
                    ->with('branch', 'department', 'designation', 'playslab')
                    ->where('created_by', Auth::user()->creatorId());
          }

          // Apply status filter if provided and not "all"
          if ($this->statusFilter && $this->statusFilter !== 'all')
          {
               $data->where('employe_status', $this->statusFilter);
          }

          $data = $data->orderBy('id', 'DESC')->get();

          $exportData = [];
          foreach ($data as $k => $employees)
          {
               $exportData[$k]["id"] = $employees->employee_id;
               $exportData[$k]["name"] = $employees->name;
               $exportData[$k]["dob"] = Carbon::parse($employees->getRawOriginal('dob'))->format('d-m-Y');
               $exportData[$k]["gender"] = $employees->gender;
               $exportData[$k]["phone"] = $employees->phone;
               $exportData[$k]["address"] = $employees->address;
               $exportData[$k]["email"] = $employees->email;
               // $exportData[$k]["password"] = $employees->password;
               $exportData[$k]["employee_id"] = $employees->employee_id;
               $exportData[$k]["branch"] = !empty($employees->branch) ? $employees->branch->name : '-';
               $exportData[$k]["department"] = !empty($employees->department) ? $employees->department->name : '-';
               $exportData[$k]["designation"] = !empty($employees->designation) ? $employees->designation->name : '-';
               $exportData[$k]["employee_status"] = $employees->employe_status;
               $exportData[$k]["employee_types"] = $employees->employe_types;
               $exportData[$k]["type_of_staff"] = ($employees->office_staff == "1") ? "Office staff" : "Field staff";
               $exportData[$k]["pay_slab"] = !empty($employees->playslab) ? $employees->playslab->name : '-';
               $exportData[$k]["salary"] = !empty($employees->ctc_annual) ? $employees->ctc_annual / 12 : '-';
               $exportData[$k]["company_doj"] = $employees->getRawOriginal('company_doj');
               $exportData[$k]["probation_period_date"] = $employees->getRawOriginal('probation_period_date');
               $exportData[$k]["account_holder_name"] = $employees->account_holder_name;
               $exportData[$k]["account_number"] = $employees->account_number;
               $exportData[$k]["bank_name"] = $employees->bank_name;
               $exportData[$k]["bank_identifier_code"] = $employees->bank_identifier_code;
               $exportData[$k]["branch_location"] = $employees->branch_location;
               $exportData[$k]["tax_payer_id"] = $employees->tax_payer_id;
               $exportData[$k]["created_by"] = Employee::login_user($employees->created_by);
          }
          //    foreach($data as $k => $employees)
          //    {
          //        unset($employees->id,$employees->user_id,$employees->documents,$employees->tax_payer_id,$employees->is_active,$employees->created_at,$employees->updated_at);
          //        $data[$k]["branch_id"]=!empty($employees->branch->name);
          //        $data[$k]["department_id"]=!empty($employees->department->name);
          //        $data[$k]["designation_id"]= !empty($employees->designation) ? $employees->designation->name : '-';
          //        $data[$k]["salary_type"]=!empty($employees->salary_type) ? $employees->salaryType->name :'-';
          //        $data[$k]["salary"]=Employee::employee_salary($employees->salary);
          //        $data[$k]["created_by"]=Employee::login_user($employees->created_by);
          //    }
          // return $data;
          $exportData = collect($exportData);
          return $exportData;
     }

     public function headings(): array
     {
          return [
               "Emp ID",
               "Name",
               "Date of Birth",
               "Gender",
               "Phone Number",
               "Address",
               "Email ID",
               // "Password",
               "Employee ID",
               "Branch",
               "Department",
               "Designation",
               "Employee Status",
               "Employee Type",
               "Type Of Staff",
               "Pay Slab",
               "Salary",
               "Date of Join",
               "Probation Date",
               "Account Holder Name",
               "Account Number",
               "Bank Name",
               "Bank Identifier Code",
               "Branch Location",
               "Tax Payer",
               "Created By"
          ];
          // return [
          //      "Name",
          //      "Date of Birth",
          //      "Gender",
          //      "Phone Number",
          //      "Address",
          //      "Email ID",
          //      "Password",
          //      "Employee ID",
          //      "Branch",
          //      "Department",
          //      "Designation",
          //      "Date of Join",
          //      "Account Holder Name",
          //      "Account Number",
          //      "Bank Name",
          //      "Bank Identifier Code",
          //      "Branch Location",
          //      "Salary Type",
          //      "Salary",
          //      "Created By"
          // ];
     }
}
