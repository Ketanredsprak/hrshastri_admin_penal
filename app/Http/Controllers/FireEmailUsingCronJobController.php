<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\User;
use App\Models\NotificationModel;
use App\Models\UserLeaveBalance;
use App\Models\UserLeaveBalanceYear;
use App\Models\Utility;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Throwable;

class FireEmailUsingCronJobController extends Controller
{
     public function test_email()
     {
          return $this->getActiveUsers();
          // dd(Auth::user()->id);
          // $employees = Employee::where('created_by', Auth::user()->creatorId())->get();
          // // Return employee ids as a comma separated string
          // $employeeIds = $employees->pluck('id')->toArray();
          // $employeeIdsCommaSeparated = implode(',', $employeeIds);
          // // dd($employeeIds, $employeeIdsCommaSeparated);
          // return $employeeIdsCommaSeparated;

          // return $this->sendResignEmails();

          $email = "sumit@redsparkinfo.co.in";
          $content = "Content writing is the process of creating and publishing written content for a variety of purposes, including marketing, education, and entertainment. Content writers must be able to research topics, write in a clear and concise style, and edit their work for grammar and spelling";

          Mail::send(
               ['html' => 'emails.demoMail'],
               array(
                    'content' => $content,
               ),
               function ($message) use ($email)
               {
                    $message->from(env('MAIL_USERNAME'), 'Employee Birthday');
                    $message->to($email)->subject("Employee Birthday");
               }
          );
          dd("success");
     }

     public function sendResignEmails()
     {

          // $employeeIds = [760, 751];
          $employeeIds = [];

          $sendEmails = [];
          foreach ($employeeIds as $employeeId)
          {
               $resignation_email = [];
               $employee           = Employee::find($employeeId);
               $resignation_email_notification = Utility::getValByName('resignation_email_notification') ?? '';
               if (!empty($resignation_email_notification))
               {
                    $resignation_email = explode(',', $resignation_email_notification);

                    foreach ($resignation_email as $email)
                    {
                         $uArr = [
                              'resigned_user' => $employee->name,
                              'resignation_date'  => date('d-m-Y'),
                              'notice_date' => '',
                         ];
                         $resignation_email[] = Utility::sendEmailTemplate('employee_resignation', [$email], $uArr);
                    }
               }

               $uArr = [
                    'assign_user' => $employee->name,
                    'resignation_date'  => date('Y-m-d'),
                    // 'resignation_date'  => $request->notice_date,
                    'notice_date' => '',
               ];

               $resp1 = Utility::sendEmailTemplate('employee_resignation', [$employee->email], $uArr);
               $user = User::find($employee->created_by);

               $uArr = [
                    'assign_user' => $user->name,
                    'resignation_date'  => date('Y-m-d'),
                    'notice_date' => '',
               ];

               $resp = Utility::sendEmailTemplate('employee_resignation', [$user->email], $uArr);

               $sendEmails[$employeeId] = [
                    'to_employee' => $resp1,
                    'to_admin' => $resp,
                    'to_resignation_emails' => $resignation_email,
               ];
          }

          dd($sendEmails);
     }


     public function employeeBday()
     {

          $data = Employee::whereDay('dob', Carbon::now())->whereMonth('dob', Carbon::now())->get();

          foreach ($data as $row)
          {
               $email = $row->email;
               $companies = User::where('id', $row->created_by)->first();
               $content = "<h2>Employee Birthday</h2>";
               Mail::send(
                    ['html' => 'system_emails.employee_email_birthday'],
                    array(
                         'content' => $content,
                         'user'    => $row,
                         'companies' => $companies
                    ),
                    function ($message) use ($email)
                    {
                         $message->from(env('MAIL_USERNAME'), 'Employee Birthday');
                         $message->to($email)->subject("Employee Birthday");
                    }
               );
          }

          $datamployee = Employee::whereDay('dob', Carbon::now())->whereMonth('dob', Carbon::now())->get();

          if (!empty($datamployee))
          {
               foreach ($datamployee as $row)
               {
                    $companies = User::where('created_by', $row->created_by)->get();
                    foreach ($companies as $company)
                    {
                         $notification  = new NotificationModel();
                         $notification->user_id      = $company->id;
                         $notification->employee_id  = $row->id;
                         $notification->title        = "Happy BirthDay " . $row->name;
                         $notification->details      = "Happy BirthDay " . $row->name . " wish you all company";
                         $notification->status       = 0;
                         $notification->date         = Carbon::now();
                         $notification->created_by   = $row->created_by;
                         $notification->redirect   = 'employee birthday';
                         $notification->save();
                    }
               }
          }
     }

     public function employeeWorkAnyversary()
     {



          $data = Employee::whereDay('company_doj', Carbon::now())->whereMonth('company_doj', Carbon::now())->get();
          foreach ($data as $row)
          {
               $email = $row->email;
               $companies = User::where('id', $row->created_by)->first();
               $content = "<h2>Employee Anniversary</h2>";
               Mail::send(
                    ['html' => 'system_emails.employee_email_anniversary'],
                    array(
                         'content' => $content,
                         'user'    => $row,
                         'companies' => $companies
                    ),
                    function ($message) use ($email)
                    {
                         $message->from(env('MAIL_USERNAME'), 'Employee Anniversary');
                         $message->to($email)->subject("Employee Anniversary");
                    }
               );
          }



          $datamployee = Employee::whereDay('company_doj', Carbon::now())->whereMonth('company_doj', Carbon::now())->get();

          if (!empty($datamployee))
          {

               foreach ($datamployee as $row)
               {
                    $companies = User::where('created_by', $row->created_by)->get();
                    foreach ($companies as $company)
                    {
                         $notification  = new NotificationModel();
                         $notification->user_id      = $company->id;
                         $notification->employee_id  = $row->id;
                         $notification->title        = "Happy Work Anniversary " . $row->name;
                         $notification->details      = "Happy Work Anniversary " . $row->name . " wish you all company";
                         $notification->status       = 0;
                         $notification->date         = Carbon::now();
                         $notification->created_by   = $row->created_by;
                         $notification->redirect   = 'employee work anniversary';
                         $notification->save();
                    }
               }
          }
     }

     public function addMonthlyLeave(Request $request)
     {
          Log::channel('employee_leave_balance_operations_log')->info("________________________________________________");
          Log::channel('employee_leave_balance_operations_log')->info("add Monthly Leave Cron Job Hit");

          $employeeIds = $request->employeeIds ?? [];
          $employeeIds = [167, 156];

          try
          {
               $leaveAddedCount = 0;

               // check if probacion period is over then change probation status

               Employee::whereDate('probation_period_date', '<', Carbon::today())
                    ->where('confirm_probation_period', 0)
                    ->update(['confirm_probation_period' => 1]);

               // check if probacion period is over then change probation status


               // if ((date("Y-m-d") == Carbon::now()->startOfMonth()->format("Y-m-d")) || true)
               if (Carbon::now()->isSameDay(Carbon::now()->startOfMonth()) || true)
               {
                    $employeeController = new EmployeeController();
                    $appendEmployeeLeaveBalance = $employeeController->appendEmployeeLeaveBalance(new Request([
                         "employeeIds" => $employeeIds,
                         "leaveBalanceType" => 0, // 0 = device leave type days by number of months of year (12 months) and add accordingly, 1 = add passed leave balance
                         "customLeaveBalance" => 0,
                    ]))->getData();

                    // return $appendEmployeeLeaveBalance;

                    if ($appendEmployeeLeaveBalance->status == 1)
                    {
                         // $added_leave_balance_data = $appendEmployeeLeaveBalance->data->added_leave_balance_data ?? [];
                         $leaveAddedCount = $appendEmployeeLeaveBalance->data->added_leave_balance_total;
                    }
               }

               Log::channel('employee_leave_balance_operations_log')->info("________________________________________________");

               return "Total $leaveAddedCount Employees Leave Added.";
          }
          catch (Exception | Throwable $ex)
          {
               Log::channel('employee_leave_balance_operations_log')->error("Error in Monthly Leave Balance add", [
                    "exception" => $ex->getMessage(),
                    "line_no" => $ex->getLine(),
                    "filename" => $ex->getFile(),
               ]);

               return [
                    "exception" => $ex->getMessage(),
                    "line_no" => $ex->getLine(),
                    "filename" => $ex->getFile(),
               ];
          }
          return 1;
     }

     public function addMonthlyLeaveByEmployee(Request $request)
     {
          // try
          // {
          //      $email = $request->email ?? "";

          //      $leaveAddedCount = 0;

          //      $data = Employee::where('email', $email)->get();
          //      if (empty($data) || count($data) <= 0)
          //      {
          //           return "Employee Data Not Found.";
          //      }

          //      foreach ($data as $employee)
          //      {
          //           $probation_period_date = $employee->probation_period_date;
          //           $confirm_probation_period = $employee->confirm_probation_period;

          //           $addLeave = 0;

          //           if (
          //                date("Y-m-d") > date("Y-m-d", strtotime($probation_period_date)) &&
          //                empty($confirm_probation_period)
          //           )
          //           {
          //                $leaveTypes = LeaveType::where("play_slab_id", $employee->play_slab_id)->where('created_by', '=', $employee->created_by)->get();
          //                foreach ($leaveTypes as $leaveType)
          //                {
          //                     $addLeave = ($leaveType->days / 12);
          //                     UserLeaveBalance::where("leave_type_id", $data->leave_type_id)->where("user_id", $employee->id)->increment('pending_leaves', $addLeave);

          //                     $leaveAddedCount++;
          //                }

          //                Employee::where("id", $employee->id)->update(['confirm_probation_period' => 1]);
          //           }

          //           if (date("Y-m-d") == Carbon::now()->startOfMonth()->format("Y-m-d"))
          //           {
          //                $leaveTypes = LeaveType::where("play_slab_id", $employee->play_slab_id)->where('created_by', '=', $employee->created_by)->get();
          //                foreach ($leaveTypes as $leaveType)
          //                {
          //                     $addLeave = ($leaveType->days / 12);
          //                     UserLeaveBalance::where("leave_type_id", $data->leave_type_id)->where("user_id", $employee->id)->increment('pending_leaves', $addLeave);
          //                     $leaveAddedCount++;
          //                }
          //           }
          //      }
          //      return "Total $leaveAddedCount Employees Leave Added.";
          // }
          // catch (Exception $th)
          // {
          //      return 0;
          // }
          // catch (Throwable $th)
          // {
          //      return 0;
          // }
          // return 1;
     }

     public function getActiveUsers()
     {
          $companies = [
               400  => 'Adishwaram Corportation',
               602  => 'Jhaveri Securities',
               2119 => 'Sure Safety India Pvt Ltd',
          ];

          $fromMonth = '2026-01';
          $toMonth   = '2026-08';

          $parseDate = function ($value)
          {
               if (empty($value) || $value == '0000-00-00' || $value == '0000-00-00 00:00:00')
               {
                    return null;
               }

               try
               {
                    return \Carbon\Carbon::parse($value)->format('Y-m-d');
               }
               catch (\Exception $e)
               {
                    return null;
               }
               catch (\Throwable $e)
               {
                    return null;
               }
          };

          $months     = [];
          $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $fromMonth)->startOfMonth();
          $monthEnd   = \Carbon\Carbon::createFromFormat('Y-m', $toMonth)->startOfMonth();

          while ($monthStart->lte($monthEnd))
          {
               $months[] = $monthStart->copy();
               $monthStart->addMonth();
          }

          $employees = \Illuminate\Support\Facades\DB::table('employees as e')
               ->join('users as u', 'u.id', '=', 'e.user_id')
               ->where('u.type', '=', 'employee')
               ->whereIn('e.created_by', array_keys($companies))
               ->select(
                    'e.id',
                    'e.name',
                    'e.employee_id',
                    'e.email',
                    'e.created_by',
                    'e.company_doj',
                    'e.date_of_exit',
                    'e.employe_status',
                    'e.is_active',
                    'e.employee_exit',
                    'e.created_at'
               )
               ->orderBy('e.name', 'ASC')
               ->get();

          // approved resignations give an exit date when date_of_exit was never filled
          $resignationDates = \Illuminate\Support\Facades\DB::table('resignations')
               ->whereIn('created_by', array_keys($companies))
               ->where('status', '=', 1)
               ->whereNotNull('resignation_date')
               ->orderBy('resignation_date', 'ASC')
               ->pluck('resignation_date', 'employee_id');

          $employeeData = [];

          foreach ($employees as $row)
          {
               $joinDate = $parseDate($row->company_doj);
               if (empty($joinDate))
               {
                    $joinDate = $parseDate($row->created_at);
               }

               $exitDate = $parseDate($row->date_of_exit);
               if (empty($exitDate) && isset($resignationDates[$row->id]))
               {
                    $exitDate = $parseDate($resignationDates[$row->id]);
               }

               $hasLeft = (in_array($row->employe_status, ['Resigned', 'Terminated', 'Deceased']) || $row->employee_exit == 1 || $row->is_active == 0);

               $employeeData[] = [
                    'id'             => $row->id,
                    'employee_id'    => $row->employee_id,
                    'name'           => $row->name,
                    'email'          => $row->email,
                    'created_by'     => $row->created_by,
                    'join_date'      => $joinDate,
                    'exit_date'      => $exitDate,
                    'employe_status' => $row->employe_status,
                    'is_active'      => $row->is_active,
                    'has_left'       => $hasLeft,
                    'exit_unknown'   => ($hasLeft && empty($exitDate)),
               ];
          }

          $response = [];

          foreach ($companies as $companyId => $companyName)
          {
               $companyEmployees = array_values(array_filter($employeeData, function ($row) use ($companyId)
               {
                    return $row['created_by'] == $companyId;
               }));

               $monthWiseData = [];

               foreach ($months as $month)
               {
                    $firstDay = $month->copy()->startOfMonth()->format('Y-m-d');
                    $lastDay  = $month->copy()->endOfMonth()->format('Y-m-d');

                    $activeEmployees      = [];
                    $exitUnknownEmployees = [];

                    foreach ($companyEmployees as $row)
                    {
                         // not joined yet in this month
                         if (!empty($row['join_date']) && $row['join_date'] > $lastDay)
                         {
                              continue;
                         }

                         // already left before this month started
                         if (!empty($row['exit_date']) && $row['exit_date'] < $firstDay)
                         {
                              continue;
                         }

                         $employeeRow = [
                              'id'             => $row['id'],
                              'employee_id'    => $row['employee_id'],
                              'name'           => $row['name'],
                              'email'          => $row['email'],
                              'join_date'      => $row['join_date'],
                              'exit_date'      => $row['exit_date'],
                              'employe_status' => $row['employe_status'],
                         ];

                         // left the company but exit date was never recorded, so month cannot be decided
                         if ($row['exit_unknown'])
                         {
                              $exitUnknownEmployees[] = $employeeRow;
                              continue;
                         }

                         $activeEmployees[] = $employeeRow;
                    }

                    $monthWiseData[] = [
                         'month'                       => $month->format('Y-m'),
                         'month_name'                  => $month->format('F Y'),
                         'active_count'                => count($activeEmployees),
                         'exit_date_unknown_count'     => count($exitUnknownEmployees),
                         'active_count_with_unknown'   => count($activeEmployees) + count($exitUnknownEmployees),
                         // 'active_employees'            => $activeEmployees,
                         // 'exit_date_unknown_employees' => $exitUnknownEmployees,
                    ];
               }

               $response[] = [
                    'company_id'      => $companyId,
                    'company_name'    => $companyName,
                    'total_employees' => count($companyEmployees),
                    'months'          => $monthWiseData,
               ];
          }

          return response()->json([
               'status'     => 1,
               'from_month' => $fromMonth,
               'to_month'   => $toMonth,
               'data'       => $response,
          ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
     }
}
