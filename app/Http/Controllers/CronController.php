<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\Utility;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\WorkingShifts;
use App\Models\WorkingShiftEmployees;
use App\Models\AttendanceEmployee;
use App\Models\GetPass;
use App\Models\NotificationModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Leave as LocalLeave;
use App\Models\EarlyLateCommingLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CronController extends Controller
{
     public function auto_leave_deduction()
     {
          // Log::info('Auto leave deduction cron started');
          // return;
          $today_date = date('Y-m-d');
          $yesterday_date = date('Y-m-d', strtotime('-1 day'));

          $requestCompanyId = 0;

          $companies = User::where('type', 'company');
          if ($requestCompanyId > 0)
          {
               $companies->where('id', $requestCompanyId);
          }
          $companies = $companies->get();

          foreach ($companies as $company)
          {
               $companyId = $company->id;
               $data = Utility::where('created_by', $companyId)->get();
               $configuration = [
                    "company_start_time",
                    "company_end_time",
                    "short_leave_hours",
                    "short_leave_limit",
                    "half_day_working_hours",
                    "half_day_working_minute",
                    "company_flexi_hours",
                    "company_flexi_days",
                    "auto_leave_deduction",
               ];
               foreach ($data as $row)
               {
                    if (in_array($row->name, $configuration))
                    {
                         $settings[$row->name] = $row->value;
                    }
               }
               $auto_leave_deduction = $settings['auto_leave_deduction'] ?? 'off';
               $short_leave_hours = $settings['short_leave_hours'] ?? 6;
               $short_leave_limit = $settings['short_leave_limit'] ?? 2;
               $half_day_working_hours = $settings['half_day_working_hours'] ?? 4;
               $half_day_working_minute = $settings['half_day_working_minute'] ?? 0;
               $total_halfday_second = (($half_day_working_hours * 60) + $half_day_working_minute) * 60;
               if ($auto_leave_deduction == 'on')
               {
                    $employeesIds = [1917, 1921, 1922];
                    $employeesIds = [];
                    $employees = Employee::where('created_by', $companyId)
                         ->when(!empty($employeesIds), function ($query) use ($employeesIds)
                         {
                              $query->whereIn('id', $employeesIds);
                         })
                         ->get();

                    // dd($employees);

                    foreach ($employees as $employee)
                    {
                         DB::beginTransaction();
                         $startTime = date("H:i:s", strtotime($settings['company_start_time']));
                         $endTime = date("H:i:s", strtotime($settings['company_end_time']));
                         $flexiHours = $settings['company_flexi_hours'] ?? "";
                         $flexiDays = $settings['company_flexi_days'] ?? "";

                         $workingShiftEmployees = WorkingShiftEmployees::where("employee_id", $employee->id)->first();
                         $employeeShift = null;

                         $query = AttendanceEmployee::where('employee_id', $employee->id);


                         if ($workingShiftEmployees != null)
                         {
                              $employeeShift = WorkingShifts::where("id", $workingShiftEmployees->working_shifts_id)->first();
                              if ($employeeShift != null)
                              {
                                   $startTime = date("H:i:s", strtotime($employeeShift->start_time));
                                   $endTime = date("H:i:s", strtotime($employeeShift->end_time));
                                   $flexiHours = $employeeShift->flexi_hours;
                                   $flexiDays = $employeeShift->flexi_days;
                                   if (strtotime($endTime) < strtotime('12:00:00'))
                                   {
                                        $query->where(function ($subQuery) use ($today_date, $yesterday_date)
                                        {
                                             $subQuery->where('date', $today_date);
                                             $subQuery->orWhere('date', $yesterday_date);
                                        });
                                   }
                                   else
                                   {
                                        $query->where(function ($subQuery) use ($today_date)
                                        {
                                             $subQuery->where('date', $today_date);
                                        });
                                   }
                              }
                         }
                         else
                         {
                              if (strtotime($endTime) < strtotime('12:00:00'))
                              {
                                   $query->where(function ($subQuery) use ($today_date, $yesterday_date)
                                   {
                                        $subQuery->where('date', $today_date);
                                        $subQuery->orWhere('date', $yesterday_date);
                                   });
                              }
                              else
                              {
                                   $query->where(function ($subQuery) use ($today_date)
                                   {
                                        $subQuery->where('date', $today_date);
                                   });

                                   // this is for development
                                   // $query->where(function ($subQuery) use ($yesterday_date)
                                   // {
                                   //      $subQuery->where('date', $yesterday_date);
                                   // });
                              }
                         }

                         $attendance_first_clockin = $query->orderBy('id', 'asc')->first();
                         $attendance_data = $query->get();

                         // dd($attendance_data->toArray());

                         // dd($endTime, date('H:i:s', strtotime("20:00:00")), strtotime($endTime) < strtotime("20:00:00"));
                         // strtotime($endTime) < strtotime(date('H:i:s'))

                         // if (strtotime($endTime) < strtotime(date('H:i:s')))
                         if (strtotime($endTime) < strtotime("20:00:00"))
                         {

                              $startTime_new = Carbon::parse($startTime);
                              $endTime_new = Carbon::parse($endTime);
                              if ($endTime_new->lt($startTime_new))
                              {
                                   $endTime_new->addDay();
                              }
                              $companyWorking = $endTime_new->diffInSeconds($startTime_new);

                              if (count($attendance_data) > 0)
                              {
                                   if (count($attendance_data) == count($attendance_data->where('clock_out', '!=', '00:00:00')))
                                   {
                                        if ($attendance_first_clockin)
                                        {
                                             $attendanceDate = Carbon::parse($attendance_first_clockin->getRawOriginal("date"));
                                             if (strtotime($startTime) > strtotime($endTime))
                                             {
                                                  if (strtotime($attendance_first_clockin->clock_in) >= strtotime('23:59:59'))
                                                  {
                                                       $attendanceDate->subDay();
                                                  }
                                             }
                                             $attendance_date = $attendanceDate->format('d-m-Y');
                                        }
                                        $totalWorked = 0;
                                        $clock_in = '';
                                        foreach ($attendance_data as $attendance)
                                        {
                                             $clock_out = '';
                                             $clock_out = $attendance->clock_out;
                                             if (empty($clock_in))
                                             {
                                                  $clock_in = $attendance->clock_in;
                                             }
                                             $inTime = $attendance->clock_in;
                                             $outTime = $attendance->clock_out;
                                             $inParseTime = Carbon::parse($inTime);
                                             $outParseTime = Carbon::parse($outTime);
                                             if (!empty($outTime) && $outTime != '00:00:00' && $outParseTime->lt($inParseTime))
                                             {
                                                  $outParseTime->addDay();
                                             }
                                             $shiftWorked = $inParseTime->diffInSeconds($outParseTime);
                                             $totalWorked += $shiftWorked;
                                        }

                                        $get_pass = GetPass::where('employee_id', $employee->id)->where('date', $today_date)->where('name', 2)->where('status', 1)->get();

                                        $getPassSeconds = 0;

                                        if (count($get_pass) > 0)
                                        {
                                             $get_out = '';
                                             $get_in = '';
                                             foreach ($get_pass as $get)
                                             {
                                                  if (empty($get_out))
                                                  {
                                                       $get_out = $get->out_time;
                                                  }
                                                  $get_in = $get->in_time;
                                                  $getPassInTime = Carbon::parse($get->in_time);
                                                  $getPassOutTime = Carbon::parse($get->out_time);
                                                  $getPassSeconds = $getPassInTime->diffInSeconds($getPassOutTime);
                                                  $totalWorked += $getPassSeconds;
                                             }
                                        }

                                        if ($totalWorked < $companyWorking)
                                        {
                                             $short_leave_second = $short_leave_hours * 60 * 60;

                                             if ($short_leave_second != 0 && $short_leave_limit != 0)
                                             {
                                                  if ($totalWorked >= $short_leave_second && $totalWorked >= $total_halfday_second)
                                                  {
                                                       $short_Leave_type = 1;
                                                       if ($flexiHours != '')
                                                       {
                                                            $flexi_check = $startTime_new->addMinutes($flexiHours)->format('H:i:s');
                                                       }
                                                       else
                                                       {
                                                            $flexi_check = $startTime_new->format('H:i:s');
                                                       }

                                                       if (strtotime($clock_in) <= strtotime($flexi_check))
                                                       {
                                                            $short_Leave_type = 1; //Early
                                                       }
                                                       else
                                                       {
                                                            if (count($get_pass) > 0)
                                                            {
                                                                 if (strtotime($get_out) <= strtotime($flexi_check))
                                                                 {
                                                                      $short_Leave_type = 1; //Early
                                                                 }
                                                            }
                                                       }
                                                       if (strtotime($clock_out) >= strtotime($endTime_new->format('H:i:s')))
                                                       {
                                                            $short_Leave_type = 2; //late
                                                       }
                                                       else
                                                       {
                                                            if (count($get_pass) > 0)
                                                            {
                                                                 if (strtotime($get_in) >= strtotime($endTime_new->format('H:i:s')))
                                                                 {
                                                                      $short_Leave_type = 2; //late
                                                                 }
                                                            }
                                                       }
                                                       $short_leave_count = EarlyLateCommingLeave::where('employee_id', $employee->id)->where('date', $today_date)->count();
                                                       if ($short_leave_count == 0)
                                                       {
                                                            $approve_short_leave = EarlyLateCommingLeave::where('employee_id', $employee->id)
                                                                 ->where('status', 1)
                                                                 ->whereYear('date', date('Y'))
                                                                 ->whereMonth('date', date('m'))
                                                                 ->count();

                                                            if ($short_leave_limit <= $approve_short_leave)
                                                            {
                                                                 // $leave_count = LocalLeave::where('employee_id', $employee->id)->where('start_date', $today_date)->count();
                                                                 // if($leave_count == 0) {
                                                                 $leavetypes      = LeaveType::where('play_slab_id', $employee->play_slab_id)->where('created_by', $companyId)->first();
                                                                 if (!empty($leavetypes))
                                                                 {
                                                                      $leave    = new LocalLeave();
                                                                      $leave->employee_id = $employee->id;
                                                                      $leave->leave_type_id    = $leavetypes->id;
                                                                      $leave->applied_on       = $today_date;
                                                                      $leave->start_date       = $attendance->date;
                                                                      $leave->end_date         = $attendance->date;
                                                                      $leave->total_leave_days = 0.5;
                                                                      $leave->leave_reason     = "Absent";
                                                                      $leave->remark           = "";
                                                                      $leave->status           = 'Approved';
                                                                      $leave->secound_leave_status = 'Pending';
                                                                      $leave->leave_day        = 0.5;
                                                                      $leave->created_by       = $companyId;
                                                                      $update_logs = json_decode($leave->update_logs ?? "", true);
                                                                      $userLeaveBalance = UserLeaveBalanceYear::where("leave_type_id", $leavetypes->id)->where("leaves_year", date("Y"))->where("user_id", $employee->id)->sum("pending_leaves");
                                                                      $update_logs[] = [
                                                                           "updated_by" => $companyId,
                                                                           "updated_at" => Carbon::now()->format("d-m-Y h:i A"),
                                                                           "userLeaveBalance" => $userLeaveBalance,
                                                                      ];
                                                                      $leave->update_logs = json_encode($update_logs);
                                                                      $leave->save();
                                                                      Log::channel('leave_log')->info("leave auto leave cron userLeaveBalance: ", [$userLeaveBalance]);
                                                                      Log::channel('leave_log')->info("leave auto leave cron employee_id: ", [$leave->employee_id]);

                                                                      // leave balance operations
                                                                      $response = employeeLeaveBalanceOperations($leave->id, $leave->status, 'Pending', $companyId); // this will add or deduct leave balance base on leave status
                                                                 }
                                                                 // }
                                                            }
                                                            else
                                                            {

                                                                 $data               = new EarlyLateCommingLeave();
                                                                 $data->name         = $short_Leave_type;
                                                                 $data->employee_id  = $employee->id;

                                                                 $data->date      = $today_date;
                                                                 $data->in_time   = "";
                                                                 $data->out_time  = "";
                                                                 $data->resone    = "Short Leave";
                                                                 $data->status           = 1;
                                                                 $data->created_by       = $companyId;
                                                                 $data->save();
                                                            }
                                                       }
                                                  }
                                                  else if ($totalWorked >= $total_halfday_second)
                                                  {
                                                       // $leave_count = LocalLeave::where('employee_id', $employee->id)->where('start_date', $today_date)->count();
                                                       // if($leave_count == 0) {
                                                       $leavetypes      = LeaveType::where('play_slab_id', $employee->play_slab_id)->where('created_by', $companyId)->first();
                                                       if (!empty($leavetypes))
                                                       {
                                                            $leave    = new LocalLeave();
                                                            $leave->employee_id = $employee->id;
                                                            $leave->leave_type_id    = $leavetypes->id;
                                                            $leave->applied_on       = $today_date;
                                                            $leave->start_date       = date('Y-m-d', strtotime($attendance_date));
                                                            $leave->end_date         = date('Y-m-d', strtotime($attendance_date));
                                                            $leave->total_leave_days = 0.5;
                                                            $leave->leave_reason     = "Absent";
                                                            $leave->remark           = "";
                                                            $leave->status           = 'Approved';
                                                            $leave->secound_leave_status = 'Pending';
                                                            $leave->leave_day        = 0.5;
                                                            $leave->created_by       = $companyId;
                                                            $update_logs = json_decode($leave->update_logs ?? "", true);
                                                            $userLeaveBalance = UserLeaveBalanceYear::where("leave_type_id", $leavetypes->id)->where("leaves_year", date("Y"))->where("user_id", $employee->id)->sum("pending_leaves");
                                                            $update_logs[] = [
                                                                 "updated_by" => $companyId,
                                                                 "updated_at" => Carbon::now()->format("d-m-Y h:i A"),
                                                                 "userLeaveBalance" => $userLeaveBalance,

                                                            ];
                                                            $leave->update_logs = json_encode($update_logs);
                                                            $leave->save();
                                                            Log::channel('leave_log')->info("leave auto leave cron userLeaveBalance: ", [$userLeaveBalance]);
                                                            Log::channel('leave_log')->info("leave auto leave cron employee_id: ", [$leave->employee_id]);
                                                            // leave balance operations
                                                            $response = employeeLeaveBalanceOperations($leave->id, $leave->status, 'Pending', $companyId); // this will add or deduct leave balance base on leave status
                                                       }
                                                       // }
                                                  }
                                                  else if ($totalWorked < $total_halfday_second)
                                                  {
                                                       // $leave_count = LocalLeave::where('employee_id', $employee->id)->where('start_date', $today_date)->count();
                                                       // if($leave_count == 0) {
                                                       $leavetypes      = LeaveType::where('play_slab_id', $employee->play_slab_id)->where('created_by', $companyId)->first();
                                                       if (!empty($leavetypes))
                                                       {
                                                            $leave    = new LocalLeave();
                                                            $leave->employee_id = $employee->id;
                                                            $leave->leave_type_id    = $leavetypes->id;
                                                            $leave->applied_on       = $today_date;
                                                            $leave->start_date       = date('Y-m-d', strtotime($attendance_date));
                                                            $leave->end_date         = date('Y-m-d', strtotime($attendance_date));
                                                            $leave->total_leave_days = 1;
                                                            $leave->leave_reason     = "Absent";
                                                            $leave->remark           = "";
                                                            $leave->status           = 'Approved';
                                                            $leave->secound_leave_status = 'Pending';
                                                            $leave->leave_day        = 1;
                                                            $leave->created_by       = $companyId;
                                                            $update_logs = json_decode($leave->update_logs ?? "", true);
                                                            $userLeaveBalance = UserLeaveBalanceYear::where("leave_type_id", $leavetypes->id)->where("leaves_year", date("Y"))->where("user_id", $employee->id)->sum("pending_leaves");
                                                            $update_logs[] = [
                                                                 "updated_by" => $companyId,
                                                                 "updated_at" => Carbon::now()->format("d-m-Y h:i A"),
                                                                 "userLeaveBalance" => $userLeaveBalance,

                                                            ];
                                                            $leave->update_logs = json_encode($update_logs);
                                                            $leave->save();
                                                            Log::channel('leave_log')->info("leave auto leave cron userLeaveBalance: ", [$userLeaveBalance]);
                                                            Log::channel('leave_log')->info("leave auto leave cron employee_id: ", [$leave->employee_id]);

                                                            // leave balance operations
                                                            $response = employeeLeaveBalanceOperations($leave->id, $leave->status, 'Pending', $companyId); // this will add or deduct leave balance base on leave status
                                                       }
                                                       // }
                                                  }
                                             }
                                             else
                                             {
                                                  if ($totalWorked >= $total_halfday_second)
                                                  {
                                                       // $leave_count = LocalLeave::where('employee_id', $employee->id)->where('start_date', $today_date)->count();
                                                       // if($leave_count == 0) {
                                                       $leavetypes      = LeaveType::where('play_slab_id', $employee->play_slab_id)->where('created_by', $companyId)->first();
                                                       if (!empty($leavetypes))
                                                       {
                                                            $leave    = new LocalLeave();
                                                            $leave->employee_id = $employee->id;
                                                            $leave->leave_type_id    = $leavetypes->id;
                                                            $leave->applied_on       = $today_date;
                                                            $leave->start_date       = date('Y-m-d', strtotime($attendance_date));
                                                            $leave->end_date         = date('Y-m-d', strtotime($attendance_date));
                                                            $leave->total_leave_days = 0.5;
                                                            $leave->leave_reason     = "Absent";
                                                            $leave->remark           = "";
                                                            $leave->status           = 'Approved';
                                                            $leave->secound_leave_status = 'Pending';
                                                            $leave->leave_day        = 0.5;
                                                            $leave->created_by       = $companyId;
                                                            $update_logs = json_decode($leave->update_logs ?? "", true);
                                                            $userLeaveBalance = UserLeaveBalanceYear::where("leave_type_id", $leavetypes->id)->where("leaves_year", date("Y"))->where("user_id", $employee->id)->sum("pending_leaves");
                                                            $update_logs[] = [
                                                                 "updated_by" => $companyId,
                                                                 "updated_at" => Carbon::now()->format("d-m-Y h:i A"),
                                                                 "userLeaveBalance" => $userLeaveBalance,

                                                            ];
                                                            $leave->update_logs = json_encode($update_logs);
                                                            $leave->save();
                                                            Log::channel('leave_log')->info("leave auto leave cron userLeaveBalance: ", [$userLeaveBalance]);
                                                            Log::channel('leave_log')->info("leave auto leave cron employee_id: ", [$leave->employee_id]);

                                                            // leave balance operations
                                                            $response = employeeLeaveBalanceOperations($leave->id, $leave->status, 'Pending', $companyId); // this will add or deduct leave balance base on leave status
                                                       }
                                                       // }
                                                  }
                                                  else if ($totalWorked < $total_halfday_second)
                                                  {
                                                       // $leave_count = LocalLeave::where('employee_id', $employee->id)->where('start_date', $today_date)->count();
                                                       // if($leave_count == 0) {


                                                       $leavetypes = LeaveType::where('play_slab_id', $employee->play_slab_id)
                                                            // ->where('created_by', $companyId)
                                                            ->first();
                                                       // dd($employee->play_slab_id);

                                                       // dd($employee->play_slab_id, $leavetypes);
                                                       if (!empty($leavetypes))
                                                       {
                                                            $leave    = new LocalLeave();
                                                            $leave->employee_id = $employee->id;
                                                            $leave->leave_type_id    = $leavetypes->id;
                                                            $leave->applied_on       = $today_date;
                                                            $leave->start_date       = date('Y-m-d', strtotime($attendance_date));
                                                            $leave->end_date         = date('Y-m-d', strtotime($attendance_date));
                                                            $leave->total_leave_days = 1;
                                                            $leave->leave_reason     = "Absent";
                                                            $leave->remark           = "";
                                                            $leave->status           = 'Approved';
                                                            $leave->secound_leave_status = 'Pending';
                                                            $leave->leave_day        = 1;
                                                            $leave->created_by       = $companyId;
                                                            $update_logs = json_decode($leave->update_logs ?? "", true);
                                                            $userLeaveBalance = UserLeaveBalanceYear::where("leave_type_id", $leavetypes->id)->where("leaves_year", date("Y"))->where("user_id", $employee->id)->sum("pending_leaves");

                                                            $update_logs[] = [
                                                                 "updated_by" => $companyId,
                                                                 "updated_at" => Carbon::now()->format("d-m-Y h:i A"),
                                                                 "userLeaveBalance" => $userLeaveBalance,

                                                            ];
                                                            $leave->update_logs = json_encode($update_logs);
                                                            $leave->save();
                                                            Log::channel('leave_log')->info("leave auto leave cron userLeaveBalance: ", [$userLeaveBalance]);
                                                            Log::channel('leave_log')->info("leave auto leave cron employee_id: ", [$leave->employee_id]);

                                                            // leave balance operations
                                                            $response = employeeLeaveBalanceOperations($leave->id, $leave->status, 'Pending', $companyId); // this will add or deduct leave balance base on leave status
                                                       }
                                                       // }
                                                  }
                                             }
                                        }
                                   }
                              }
                         }

                         // dd("commit");
                         DB::commit();
                    }
               }
          }
     }

     public function third_shift()
     {
          $startTime = '12:00:00';
          $endTime = '18:00:00';

          $companies = User::where('users.type', 'company')
               ->whereHas('settings', function ($query) use ($startTime, $endTime)
               {
                    $query->where('name', 'company_end_time') // Ensure the setting is 'company_end_time'
                         ->whereRaw("TIME_FORMAT(value, '%H:%i:%s') > ?", [$startTime]) // Handle both formats (HH:MM or HH:MM:SS)
                         ->whereRaw("TIME_FORMAT(value, '%H:%i:%s') <= ?", [$endTime]);
               })
               ->get();

          foreach ($companies as $company)
          {
               $data = Utility::where('created_by', $companyId)->get();
               $configuration = [
                    "company_start_time",
                    "company_end_time",
                    "short_leave_hours",
                    "short_leave_limit",
                    "half_day_working_hours",
                    "half_day_working_minute",
               ];
               foreach ($data as $row)
               {

                    if (in_array($row->name, $configuration))
                    {
                         $settings[$row->name] = $row->value;
                    }
               }

               $startTime = Carbon::parse(date("H:i", strtotime($settings['company_start_time'])));
               $endTime = Carbon::parse(date("H:i", strtotime($settings['company_end_time'])));
               $companyWorking = $endTime->diffInMinutes($startTime);

               $short_leave_hours = $settings['short_leave_hours'] ?? 6;
               $short_leave_limit = $settings['short_leave_limit'] ?? 2;
               $half_day_working_hours = $settings['half_day_working_hours'] ?? 4;
               $half_day_working_minute = $settings['half_day_working_minute'] ?? 0;

               $employees = Employee::where('created_by', $companyId)->get()->get();
               foreach ($employees as $employee)
               {
                    $attendance_data = AttendanceEmployee::where('employee_id', '=', $employee->id)
                         ->where('date', $date)
                         ->get();

                    if (count($attendance_data) > 0)
                    {
                         $inTime = $attendance_data->clock_in;
                         $outTime = $attendance_data->clock_out;
                         $inParseTime = Carbon::parse($inTime);
                         $outParseTime = Carbon::parse($outTime);
                         $totalWorked = $outParseTime->diff($inParseTime);
                         dd($totalWorked, $companyWorking);
                         $workingShiftEmployees = WorkingShiftEmployees::where("employee_id", $employee->id)->first();
                         $employeeShift = null;
                         if ($workingShiftEmployees != null)
                         {
                              $employeeShift = WorkingShifts::where("id", $workingShiftEmployees->working_shifts_id)->first();
                              if ($employeeShift != null)
                              {
                                   $startTime = date("Y-m-d H:i:s", strtotime($employeeShift->start_time));
                                   $flexiHours = $employeeShift->flexi_hours;
                                   $flexiDays = $employeeShift->flexi_days;
                              }
                         }
                    }
               }
          }
     }

     public function auto_clockout(Request $request)
     {
          $today_date = date('Y-m-d');
          $yesterday_date = date('Y-m-d', strtotime('-1 day'));

          $companies = User::where('type', 'company');
          if ($request->company_id)
          {
               $companies->where('id', $request->company_id);
          }
          $companies = $companies->get();

          foreach ($companies as $company)
          {
               $companyId = $company->id;
               $data = Utility::where('created_by', $companyId)->get();
               $configuration = [
                    "company_start_time",
                    "company_end_time",
                    "short_leave_hours",
                    "short_leave_limit",
                    "half_day_working_hours",
                    "half_day_working_minute",
                    "company_flexi_hours",
                    "company_flexi_days",
               ];
               foreach ($data as $row)
               {
                    if (in_array($row->name, $configuration))
                    {
                         $settings[$row->name] = $row->value;
                    }
               }
               $short_leave_hours = $settings['short_leave_hours'] ?? 6;
               $short_leave_limit = $settings['short_leave_limit'] ?? 2;
               $half_day_working_hours = $settings['half_day_working_hours'] ?? 4;
               $half_day_working_minute = $settings['half_day_working_minute'] ?? 0;
               $total_halfday_second = (($half_day_working_hours * 60) + $half_day_working_minute) * 60;
               $employees = Employee::where('created_by', $companyId)->get();
               foreach ($employees as $employee)
               {
                    $startTime = date("H:i:s", strtotime($settings['company_start_time']));
                    $endTime = date("H:i:s", strtotime($settings['company_end_time']));
                    $flexiHours = $settings['company_flexi_hours'] ?? "";
                    $flexiDays = $settings['company_flexi_days'] ?? "";

                    $workingShiftEmployees = WorkingShiftEmployees::where("employee_id", $employee->id)->first();
                    $employeeShift = null;

                    $query = AttendanceEmployee::where('employee_id', $employee->id);
                    if ($workingShiftEmployees != null)
                    {
                         $employeeShift = WorkingShifts::where("id", $workingShiftEmployees->working_shifts_id)->first();
                         if ($employeeShift != null)
                         {
                              $startTime = date("H:i:s", strtotime($employeeShift->start_time));
                              $endTime = date("H:i:s", strtotime($employeeShift->end_time));
                              $flexiHours = $employeeShift->flexi_hours;
                              $flexiDays = $employeeShift->flexi_days;
                              if (strtotime($endTime) < strtotime('12:00:00'))
                              {
                                   $query->where(function ($subQuery) use ($today_date, $yesterday_date)
                                   {
                                        $subQuery->where('date', $today_date);
                                        $subQuery->orWhere('date', $yesterday_date);
                                   });
                              }
                              else
                              {
                                   $query->where(function ($subQuery) use ($today_date)
                                   {
                                        $subQuery->where('date', $today_date);
                                   });
                              }
                         }
                    }
                    else
                    {
                         if (strtotime($endTime) < strtotime('12:00:00'))
                         {
                              $query->where(function ($subQuery) use ($today_date, $yesterday_date)
                              {
                                   $subQuery->where('date', $today_date);
                                   $subQuery->orWhere('date', $yesterday_date);
                              });
                         }
                         else
                         {
                              $query->where(function ($subQuery) use ($today_date)
                              {
                                   $subQuery->where('date', $today_date);
                              });
                         }
                    }
                    $final_query = $query;
                    $attendance_data = $query->get();
                    $attendance_first_clockin = $final_query->orderBy('id', 'asc')->first();

                    if (strtotime($endTime) < strtotime(date('H:i:s')))
                    {
                         $startTime_new = Carbon::parse($startTime);
                         $endTime_new = Carbon::parse($endTime);

                         if ($endTime_new->lt($startTime_new))
                         {
                              $endTime_new->addDay();
                         }
                         $companyWorking = $endTime_new->diffInSeconds($startTime_new);

                         if (count($attendance_data) > 0)
                         {
                              if ($attendance_first_clockin)
                              {

                                   try
                                   {
                                        $attendanceDate = Carbon::parse($attendance_first_clockin->getRawOriginal("date"));
                                        if (strtotime($startTime) > strtotime($endTime))
                                        {
                                             if (strtotime($attendance_first_clockin->clock_in) >= strtotime('23:59:59'))
                                             {
                                                  $attendanceDate->subDay();
                                             }
                                        }
                                        $attendance_date = $attendanceDate->format('d-m-Y');
                                   }
                                   catch (Exception | Throwable $th)
                                   {
                                        // dd($th, $attendance_first_clockin);
                                   }
                              }
                              $totalWorked = 0;
                              $clock_in = '';
                              foreach ($attendance_data as $attendance)
                              {
                                   if (empty($attendance->clock_out) || $attendance->clock_out == '00:00:00' || $attendance->clock_out == '')
                                   {
                                        $clock_out = '';
                                        $clock_out = $attendance->clock_out;
                                        if (empty($clock_in))
                                        {
                                             $clock_in = $attendance->clock_in;
                                        }
                                        $inTime = $attendance->clock_in;
                                        $outTime = $attendance->clock_out;
                                        $inParseTime = Carbon::parse($inTime);
                                        if (!empty($outTime) && $outTime != '00:00:00')
                                        {
                                             $outParseTime = Carbon::parse($outTime);
                                        }
                                        else
                                        {
                                             $outParseTime = Carbon::now();
                                        }
                                        if (!empty($outTime) && $outTime != '00:00:00' && $outParseTime->lt($inParseTime))
                                        {
                                             $outParseTime->addDay();
                                        }
                                        $shiftWorked = $inParseTime->diffInSeconds($outParseTime);
                                        $totalWorked += $shiftWorked;
                                   }
                              }
                              $get_pass = GetPass::where('employee_id', $employee->id)->where('date', $today_date)->where('name', 2)->where('status', 1)->get();
                              $getPassSeconds = 0;
                              if (count($get_pass) > 0)
                              {
                                   $get_out = '';
                                   $get_in = '';
                                   foreach ($get_pass as $get)
                                   {
                                        if (empty($get_out))
                                        {
                                             $get_out = $get->out_time;
                                        }
                                        $get_in = $get->in_time;
                                        $getPassInTime = Carbon::parse($get->in_time);
                                        $getPassOutTime = Carbon::parse($get->out_time);
                                        $getPassSeconds = $getPassInTime->diffInSeconds($getPassOutTime);
                                        $totalWorked += $getPassSeconds;
                                   }
                              }
                              if ($totalWorked > $companyWorking)
                              {
                                   $checkLastAttendance = AttendanceEmployee::where('employee_id', '=', $employee->id)->orderBy("id", "DESC")->first();
                                   $date = date("Y-m-d");
                                   if ($checkLastAttendance != null && ($checkLastAttendance->clock_out ?? "") == "00:00:00") // if null then create new clock in entry
                                   {
                                        $late = "00:00:00";
                                        $earlyLeaving = "00:00:00";
                                        $overtime = '00:00:00';

                                        // Attandence Calculation Late Comming, Early Leaving, Overtime,
                                        $clockInTime = date("H:i", strtotime($checkLastAttendance->clock_in));

                                        $clockInDatetime = date("Y-m-d H:i:s", strtotime($checkLastAttendance->clock_in));
                                        $clockOutDatetime = date("Y-m-d H:i:s");

                                        $calculateAttendanceData = (object)calculateAttendanceData($employee->id, $date, $clockInDatetime, $clockOutDatetime, $companyId, 'cron');

                                        if ($calculateAttendanceData->status == 1)
                                        {
                                             if (!empty($calculateAttendanceData->data))
                                             {
                                                  $calculateAttendanceDataRow = (object)$calculateAttendanceData->data ?? [];

                                                  $late = $calculateAttendanceDataRow->late_comming ?? $late;
                                                  $earlyLeaving = $calculateAttendanceDataRow->early_leaving ?? $earlyLeaving;
                                                  $overtime = $calculateAttendanceDataRow->overtime ?? $overtime;
                                             }
                                        }
                                        $currentDateTime = date("Y-m-d H:i:s");
                                        $time = date("H:i:s");
                                        AttendanceEmployee::where("id", $checkLastAttendance->id)
                                             ->update([
                                                  "clock_out_date_time" => $currentDateTime,
                                                  "clock_out" => $time,
                                                  "early_leaving" => $earlyLeaving,
                                                  "overtime" => $overtime,
                                                  "is_system_clockout" => 1,
                                             ]);
                                        $title_notification = __("Attendance Notification");
                                        $body_notification = __("You have clocked out successfully.");
                                        // notification Code
                                        $notification               = new NotificationModel();
                                        $notification->user_id      = $employee->user_id;
                                        $notification->employee_id  = $employee->id;
                                        $notification->title        = $title_notification;
                                        $notification->details      = $body_notification;
                                        $notification->status       = 0;
                                        $notification->date         = Carbon::now();
                                        $notification->created_by   = $companyId;
                                        $notification->redirect     = 'dashboard_user_clock_out';
                                        $notification->save();
                                        //end notification

                                        // Send Push Notification to App
                                        $usersData = User::where('id', $employee->user_id)->where("device_token", "!=", NULL)->select("id", "device_type", "device_token")->get();
                                        if (!$usersData->isEmpty())
                                        {
                                             $title = $title_notification;
                                             $body = $body_notification;

                                             $click_action = "dashboard_user_clock_out";
                                             $extraData = ['id' => (string)$notification->id];

                                             sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
                                        }
                                   }
                              }
                         }
                    }
               }
          }
     }
}
