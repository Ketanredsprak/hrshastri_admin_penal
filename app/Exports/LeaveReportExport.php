<?php

namespace App\Exports;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\UserLeaveBalanceYear;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LeaveReportExport implements FromArray, WithHeadings
{
     /**
      * @return \Illuminate\Support\Collection
      */

     protected $data;

     public function __construct($data = [])
     {
          $this->data = $data;
     }

     public function array(): array
     {
          $year = $this->data['year'];
          $start_date = $this->data['start_date'];
          $end_date = $this->data['end_date'];
          $branch = $this->data['branch'];
          $department = $this->data['department'];

          if (Auth::user()->type == 'company')
          {
               $employees = Employee::where('created_by', Auth::user()->creatorId())->where("employee_exit", 0);
          }
          else
          {
               $assign_employees = json_decode(Auth::user()->assign_employees) ?? [];
               $employees = Employee::whereIn('id', $assign_employees)->where('created_by', Auth::user()->creatorId())->where("employee_exit", 0);
          }

          if (!empty($branch))
          {
               $employees->where('branch_id', $branch);
          }

          if (!empty($department))
          {
               $employees->where('department_id', $department);
          }

          $employees = $employees->get();

          $leaves = [];
          $totalApproved = $totalReject = $totalPending = 0;
          $totalLeaveBalance = 0;

          $leaveTypes = LeaveType::where('created_by', Auth::user()->creatorId())->get();

          foreach ($employees as $employee)
          {
               $employeeLeave['id']          = $employee->id;
               $employeeLeave['employee_id'] = $employee->employee_id;
               $employeeLeave['employee']    = $employee->name;
               $employeeLeave['employee_id_cus']    = $employee->id;

               $approved = Leave::where('employee_id', $employee->id)->where('status', 'Approved')->whereDate("start_date", '>=', $start_date)->whereDate("start_date", '<=', $end_date)->sum('total_leave_days');
               $reject   = Leave::where('employee_id', $employee->id)->where('status', 'Reject')->whereDate("start_date", '>=', $start_date)->whereDate("start_date", '<=', $end_date)->sum('total_leave_days');
               $pending  = Leave::where('employee_id', $employee->id)->where('status', 'Pending')->whereDate("start_date", '>=', $start_date)->whereDate("start_date", '<=', $end_date)->sum('total_leave_days');

               $leaveBalance = UserLeaveBalanceYear::where("leaves_year", $year)->where("user_id", $employee->id)->sum("pending_leaves");

               $totalApproved += $approved;
               $totalReject   += $reject;
               $totalPending  += $pending;
               $totalLeaveBalance  += $leaveBalance;

               $employeeLeave['approved'] = ($approved ?? 0);
               $employeeLeave['reject']   = ($reject ?? 0);
               $employeeLeave['pending']  = ($pending ?? 0);
               $employeeLeave['leaveBalance']  = formatNumber($leaveBalance ?? 0);

               $leaveDataRow = [$employee->employee_id, $employee->name];

               foreach ($leaveTypes as $leaveType)
               {
                    // $leaveBalanceOfLeaveType = UserLeaveBalanceYear::where("leave_type_id", $leaveType->id)->where("leaves_year", $year)->where("user_id", $employee->id)->get();
                    // if (!empty($leaveBalanceOfLeaveType))
                    // {
                    //      $leaveDataRow[] = ($leaveBalanceOfLeaveType->sum("pending_leaves") ?? 0);
                    // }
                    // else
                    // {
                    //      $leaveDataRow[] = 0;
                    // }

                    $leaveCount = Leave::where('employee_id', $employee->id)->where("leave_type_id", $leaveType->id)->where('status', 'Approved')->whereDate("start_date", '>=', $start_date)->whereDate("start_date", '<=', $end_date)->sum('total_leave_days');
                    $leaveDataRow[] = ($leaveCount <= 0 ? "0" : $leaveCount);
               }

               $leaveDataRow[] = ($approved <= 0 ? "0" : $approved);
               $leaveDataRow[] = ($reject <= 0 ? "0" : $reject);
               $leaveDataRow[] = ($leaveBalance <= 0 ? "0" : $leaveBalance);

               $leaves[] = $leaveDataRow;
          }

          if (empty($leaves))
          {
               throw new ModelNotFoundException('No data found to export.');
          }

          return $leaves;
     }

     public function collectionOld()
     {
          $year = $this->data['year'];
          $start_date = $this->data['start_date'];
          $end_date = $this->data['end_date'];
          $branch = $this->data['branch'];
          $department = $this->data['department'];

          $data    = Leave::all();
          $employees = Employee::where('created_by', \Auth::user()->creatorId());
          $employees = $employees->get();

          foreach ($employees as $employee)
          {

               $approved = Leave::where('employee_id', $employee->id)->where('status', 'Approved');
               $reject   = Leave::where('employee_id', $employee->id)->where('status', 'Reject');
               $pending  = Leave::where('employee_id', $employee->id)->where('status', 'Pending');
               $totalApproved = $totalReject = $totalPending = 0;

               $approved = $approved->count();
               $reject   = $reject->count();
               $pending  = $pending->count();

               $totalApproved += $approved;
               $totalReject   += $reject;
               $totalPending  += $pending;

               $employeeLeave['approved'] = $approved;
               $employeeLeave['reject']   = $reject;
               $employeeLeave['pending']  = $pending;


               $leaves[] = $employeeLeave;
          }

          // dd($data);

          foreach ($data as $k => $leave)
          {
               // dd($k, $data[$k]);
               $data[$k]["employee_id"] = !empty($leave->employees) ? User::employeeIdFormat($leave->employees->employee_id) : '';
               $data[$k]["employee"] = ($leave->employees->name ?? "");

               $data[$k]["approved_leaves"] = ($leaves[$k]['approved'] ?? 0) == 0 ? '0' : $leaves[$k]['approved'];
               $data[$k]["rejected_leaves"] = ($leaves[$k]['reject'] ?? 0) == 0 ? '0' : $leaves[$k]['reject'];
               $data[$k]["pending_leaves"] = ($leaves[$k]['pending'] ?? 0);

               unset(
                    $data[$k]['id'],
                    $data[$k]['leave_type_id'],
                    $data[$k]['start_date'],
                    $data[$k]['end_date'],
                    $data[$k]['applied_on'],
                    $data[$k]['total_leave_days'],
                    $data[$k]['leave_reason'],
                    $data[$k]['created_at'],
                    $data[$k]['created_by'],
                    $data[$k]['remark'],
                    $data[$k]['status'],
                    $data[$k]['updated_at'],
                    $data[$k]['account_id'],
                    $data[$k]['leave_day'],
                    $data[$k]['secound_leave_status'],
                    $data[$k]['superior_user'],
               );
          }
          return $data;
     }


     public function headings(): array
     {
          $headers = ["Employee ID", "Employee Name"];

          $leaveTypes = LeaveType::where('created_by', Auth::user()->creatorId())->get();

          foreach ($leaveTypes as $leaveType)
          {
               $headers[] = $leaveType->title;
          }

          $headers[] = "Approved Leaves";
          $headers[] = "Rejected Leaves";
          $headers[] = "Leaves Balance";

          return $headers;
     }
}
