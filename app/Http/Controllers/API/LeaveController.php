<?php

namespace App\Http\Controllers\API;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\Utility;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Leave as LocalLeave;

use App\Http\Controllers\Controller;
use App\Models\NotificationModel;
use App\Models\UserLeaveBalance;
use App\Models\UserLeaveBalanceYear;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{

     public function get_leave_type(request $request)
     {
          try
          {
               $user     = Auth::user();
               $employee = Employee::where('user_id', '=', $user->id)->first();
               $data = LeaveType::where('play_slab_id', $employee->play_slab_id)->where('created_by', '=', Auth::user()->creatorId())->get();
               if (!empty($data))
               {
                    $response = [
                         'status' => 1,
                         'message' => __("Leave types list."),
                         'data'    => $data
                    ];
               }
               else
               {
                    $response = [
                         'status' => 0,
                         'message' => 'Data Not Found',
                    ];
               }
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function get_leaves(request $request)
     {
          try
          {

               $user     = Auth::user();
               $employee = $user->employee ?: Employee::where('user_id', '=', $user->id)->first();

               $year = $request->year ?? 0;

               // DB::enableQueryLog();
               $data = LocalLeave::join('leave_types', 'leaves.leave_type_id', 'leave_types.id')

                    ->select(
                         'leaves.*',
                         'leaves.applied_on as applied_on_formatted',
                         'leaves.start_date as start_date_formatted',
                         'leaves.end_date as end_date_formatted',
                         'leave_types.title'
                    )
                    ->when(($year > 0), function ($query) use ($year)
                    {
                         $query->whereYear("start_date", $year)
                              // ->orWhereYear("end_date", $year)
                         ;
                    })
                    ->where('employee_id', '=', $employee->id)
                    ->where('leaves.created_by', '=', Auth::user()->creatorId())
                    ->orderBy('leaves.id', 'DESC')->get();

               // dd(DB::getQueryLog());

               // Leave Statistics
               $leaveStatistics = [];

               $pendingLeaveCount = 0;
               $approveLeaveCount = 0;
               $rejectedLeaveCount = 0;

               $pendingLeaveData = [];
               $approvedLeaveData = [];
               $rejectedLeaveData = [];

               $leaveTypes = LeaveType::where('play_slab_id', $employee->play_slab_id)->where('created_by', '=', Auth::user()->creatorId())->get();

               foreach ($leaveTypes as $leaveTypeRow)
               {
                    $leaveFilterData = $data->where("leave_type_id", $leaveTypeRow->id);

                    $pendingLeaveCount += $leaveFilterData->where("status", "Pending")->count();
                    $approveLeaveCount += $leaveFilterData->where("status", "Approved")->count();
                    $rejectedLeaveCount += $leaveFilterData->where("status", "Reject")->count();

                    $leaveFilterApproved = $leaveFilterData->where("status", "Approved")->count();

                    $pendingLeaveData[$leaveTypeRow->title] = ($pendingLeaveData[$leaveTypeRow->title] ?? 0) + $leaveFilterData->where("status", "Pending")->count();
                    $approvedLeaveData[$leaveTypeRow->title] = ($approvedLeaveData[$leaveTypeRow->title] ?? 0) + $leaveFilterData->where("status", "Approved")->count();
                    $rejectedLeaveData[$leaveTypeRow->title] = ($rejectedLeaveData[$leaveTypeRow->title] ?? 0) + $leaveFilterData->where("status", "Reject")->count();
               }

               $leaveStatistics = [
                    "pendingLeaveCount" => $pendingLeaveCount,
                    "approveLeaveCount" => $approveLeaveCount,
                    "rejectedLeaveCount" => $rejectedLeaveCount,

                    "pendingLeaveData" => $pendingLeaveData,
                    "approvedLeaveData" => $approvedLeaveData,
                    "rejectedLeaveData" => $rejectedLeaveData,
               ];

               // Leave Statistics


               if (!empty($data))
               {
                    $response = [
                         'status' => 1,
                         'message' => __('Leaves list.'),
                         'data'    => $data,
                         'leaveStatistics'    => $leaveStatistics,
                    ];
               }
               else
               {
                    $response = [
                         'status' => 0,
                         'message' => 'Data Not Found',
                    ];
               }
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function create_leave(request $request)
     {
          try
          {
               // Log::info("Leave Create Request", $request->all());
               $employee = Employee::where('user_id', '=', Auth::user()->id)->first();

               if ($employee == null)
               {
                    $response = ['status' => 0, 'message' => __("You are not an employee, please contact your administrator.")];
                    return ApiResponse($response);
               }

               $leave_type = LeaveType::find($request->leave_type_id);

               if ($leave_type == null)
               {
                    return ApiResponse(['status' => 0, 'message' => __("Invalid Leave Type")]);
               }

               $startDate = new \DateTime($request->start_date);
               $endDate = new \DateTime($request->end_date);
               $endDate->add(new \DateInterval('P1D'));
               $total_leave_days = !empty($startDate->diff($endDate)) ? $startDate->diff($endDate)->days : 0;
               $starting_date = $request->start_date;
               $enddate = $request->end_date;
               $start_month = date('m', strtotime($starting_date));
               $end_month = date('m', strtotime($enddate));

               if (Auth::user()->type != 'company')
               {
                    $appliedLeaveDays = getAppliedLeaveDays($request->leave_type_day, $request->start_date, $request->end_date);
                    $leaveBalanceValidation = validateEmployeeLeaveBalance($employee->id, $request->leave_type_id, $appliedLeaveDays, $request->start_date);

                    if ((int) $leaveBalanceValidation['status'] !== 1)
                    {
                         return ApiResponse([
                              'status' => 0,
                              'message' => $leaveBalanceValidation['message'],
                         ]);
                    }

                    // if ((int) $employee->id == 156)
                    // {
                    //      return ApiResponse([
                    //           'status' => 1,
                    //           'message' => "You can apply for leave. Your leave balance is " . $leaveBalanceValidation['leave_balance'] . " and you applied for " . $leaveBalanceValidation['applied_leave_days'] . " day(s).",
                    //      ]);
                    // }
               }

               if ($leave_type != null)
               {
                    DB::beginTransaction();

                    // if ($leave_type->days >= $total_leave_days)
                    // {
                    if ($start_month == $end_month || $request->leave_type_day == 0)
                    {
                         $leave    = new LocalLeave();
                         $leave->employee_id = $employee->id;

                         $leave->leave_type_id    = $request->leave_type_id;
                         $leave->applied_on       = date('Y-m-d');
                         $leave->start_date       = $request->start_date;

                         $leave_day = 0;

                         $leave_type_day = 1;
                         if ($request->leave_type_day == 0)
                         {
                              $leave->end_date         = $request->start_date;
                              $leave->total_leave_days = 0.5;
                              $leave_type_day = 0.5;
                         }
                         else
                         {
                              $leave->end_date         = $request->end_date;
                              $leave->total_leave_days = $total_leave_days;
                              $leave_type_day = 1;
                         }

                         $leave->leave_reason     = $request->leave_reason;
                         $leave->remark           = $request->remark;
                         $leave->status           = 'Pending';
                         // $leave->leave_day        = $request->leave_type_day;
                         $leave->leave_day        = $leave_type_day;
                         $leave->created_by       = Auth::user()->creatorId();

                         $userLeaveBalance = UserLeaveBalanceYear::where("leave_type_id", $request->leave_type_id)->where("leaves_year", date("Y"))->where("user_id", $employee->id)->sum("pending_leaves");

                         $update_logs[] = [
                              "updated_by" => Auth::user()->creatorId(),
                              "updated_at" => Carbon::now()->format("d-m-Y h:i A"),
                              "userLeaveBalance" => $userLeaveBalance,
                         ];
                         $leave->update_logs = json_encode($update_logs);
                         $leave->save();
                         Log::channel('leave_log')->info("leave create_leave userLeaveBalance: ", [$userLeaveBalance]);
                         Log::channel('leave_log')->info("leave create_leave employee_id: ", [$leave->employee_id]);

                         // Google celander
                         if ($request->get('synchronize_type')  == 'google_calender')
                         {
                              $type = 'leave';
                              $request1 = new GoogleEvent();
                              $request1->title = !empty(Auth::user()->getLeaveType($leave->leave_type_id)) ? Auth::user()->getLeaveType($leave->leave_type_id)->title : '';
                              $request1->start_date = $request->start_date;
                              $request1->end_date = $request->end_date;
                              Utility::addCalendarData($request1, $type);
                         }
                    }
                    else
                    {
                         $for_starting_start_date = $starting_date;
                         $for_starting_end_date = date("Y-m-t", strtotime($for_starting_start_date));
                         $datediff = strtotime($for_starting_end_date) - strtotime($for_starting_start_date);
                         $count_start_days = round($datediff / (60 * 60 * 24)) + 1;
                         //next month leave 0
                         $leave    = new LocalLeave();
                         $leave->employee_id = $employee->id;

                         $leave->leave_type_id    = $request->leave_type_id;
                         $leave->applied_on       = date('Y-m-d');
                         $leave->start_date       = $for_starting_start_date;
                         $leave->end_date         = $for_starting_end_date;
                         $leave->total_leave_days = $count_start_days;
                         $leave->leave_reason     = $request->leave_reason;
                         $leave->remark           = $request->remark;
                         $leave->status           = 'Pending';
                         $leave->leave_day        = $request->leave_type_day;
                         $leave->created_by       = Auth::user()->creatorId();

                         $userLeaveBalance = UserLeaveBalanceYear::where("leave_type_id", $request->leave_type_id)->where("leaves_year", date("Y"))->where("user_id", $employee->id)->sum("pending_leaves");
                         $update_logs[] = [
                              "updated_by" => Auth::user()->creatorId(),
                              "updated_at" => Carbon::now()->format("d-m-Y h:i A"),
                              "userLeaveBalance" => $userLeaveBalance,
                         ];
                         $leave->update_logs = json_encode($update_logs);

                         $leave->save();
                         Log::channel('leave_log')->info("leave create_leave userLeaveBalance: ", [$userLeaveBalance]);
                         Log::channel('leave_log')->info("leave create_leave employee_id: ", [$leave->employee_id]);

                         // Google celander
                         if ($request->get('synchronize_type')  == 'google_calender')
                         {
                              $type = 'leave';
                              $request1 = new GoogleEvent();
                              $request1->title = !empty(Auth::user()->getLeaveType($leave->leave_type_id)) ? Auth::user()->getLeaveType($leave->leave_type_id)->title : '';
                              $request1->start_date = $for_starting_start_date;
                              $request1->end_date = $for_starting_end_date;
                              Utility::addCalendarData($request1, $type);
                         }

                         //next month leave 1
                         $end_start_date = date("Y-m-01", strtotime($enddate));
                         $end_end_date = $enddate;
                         $datediffend = strtotime($end_end_date) - strtotime($end_start_date);
                         $count_end_days = round($datediffend / (60 * 60 * 24)) + 1;

                         $leave    = new LocalLeave();

                         $leave->employee_id = $employee->id;

                         $leave->leave_type_id    = $request->leave_type_id;
                         $leave->applied_on       = date('Y-m-d');
                         $leave->start_date       = $end_start_date;
                         $leave->end_date         = $end_end_date;
                         $leave->total_leave_days = $count_end_days;
                         $leave->leave_reason     = $request->leave_reason;
                         $leave->remark           = $request->remark;
                         $leave->status           = 'Pending';
                         $leave->leave_day        = $request->leave_type_day;
                         $leave->created_by       = Auth::user()->creatorId();

                         $userLeaveBalance = UserLeaveBalanceYear::where("leave_type_id", $request->leave_type_id)->where("leaves_year", date("Y"))->where("user_id", $employee->id)->sum("pending_leaves");
                         $update_logs[] = [
                              "updated_by" => Auth::user()->creatorId(),
                              "updated_at" => Carbon::now()->format("d-m-Y h:i A"),
                              "userLeaveBalance" => $userLeaveBalance,
                         ];
                         $leave->update_logs = json_encode($update_logs);

                         $leave->save();
                         Log::channel('leave_log')->info("leave create_leave userLeaveBalance: ", [$userLeaveBalance]);
                         Log::channel('leave_log')->info("leave create_leave employee_id: ", [$leave->employee_id]);

                         // Google celander
                         if ($request->get('synchronize_type')  == 'google_calender')
                         {
                              $type = 'leave';
                              $request1 = new GoogleEvent();
                              $request1->title = !empty(Auth::user()->getLeaveType($leave->leave_type_id)) ? Auth::user()->getLeaveType($leave->leave_type_id)->title : '';
                              $request1->start_date = $for_starting_start_date;
                              $request1->end_date = $for_starting_end_date;
                              Utility::addCalendarData($request1, $type);
                         }
                    }

                    DB::commit();

                    try
                    {
                         //email fire leave modules
                         $setings = Utility::settings();
                         if ($setings['leave_status'] == 1)
                         {
                              $employee     = Employee::where('id', $leave->employee_id)->where('created_by', '=', Auth::user()->creatorId())->first();

                              $uArr = [
                                   'leave_email'       => $employee->email,
                                   'leave_status_name' => $employee->name,
                                   'name' => $employee->name,
                                   'leave_status'      => $request->status,
                                   'leave_reason'      => $leave->leave_reason,
                                   'leave_start_date'  => $leave->start_date,
                                   'leave_end_date'    => $leave->end_date,
                                   'total_leave_days' => $leave->total_leave_days,
                              ];
                              $usersData  = User::find(Auth::user()->creatorId());
                              if (!empty($employee->users_types_id) || !empty($usersData->email))
                              {  //TL and SR shulbe fire this Email
                                   $resp = Utility::sendEmailTemplate('leave_status', [$employee->email], $uArr);
                                   // $resp = Utility::sendEmailTemplate('leave_status', ['test@mailnator.com'], $uArr);
                              }
                         }

                         //notification Code
                         $employee = Employee::where('user_id', '=', Auth::id())->first();
                         $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();
                         foreach ($upUser as $row)
                         {
                              foreach (json_decode($row['assign_employees']) ?? [] as $key => $user)
                              {
                                   if (!empty($user))
                                   {
                                        if ($user == $employee->id)
                                        {
                                             $notification               = new NotificationModel();
                                             $notification->user_id      = $row->id;
                                             $notification->employee_id  = $employee->id;
                                             $notification->title        = "Leave request added by " . $employee->name . " ";
                                             $notification->details      = "Leave request added by " . $employee->name . " ";
                                             $notification->status       = 0;
                                             $notification->date         = Carbon::now();
                                             $notification->created_by   = $employee->created_by;
                                             $notification->redirect   = 'leave';
                                             $notification->save();
                                        }
                                   }
                              }
                         }
                         //end notification
                    }
                    catch (Exception | Throwable $e)
                    {
                         Log::info("Leave Request Error", $e);
                         $response = error_msg(showExceptionMessage($e));
                    }

                    $response = [
                         'status' => 1,
                         'message' => __("Leave created successfully."),
                    ];
                    return ApiResponse($response);
                    // }
                    // else
                    // {
                    //      $response = [
                    //           'status' => 0,
                    //           'message' => __('Leave type ' . $leave_type->name . ' is provide maximum ' . $leave_type->days . "  days please make sure your selected days is under " . $leave_type->days . ' days.'),
                    //      ];
                    //      return ApiResponse($response);
                    // }
               }
               else
               {
                    $response = ['status' => 0, 'message' => __("Invalid Leave Type")];
               }
          }
          catch (Exception | Throwable $e)
          {
               Log::info("Leave Request Error", $e);
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function update_leave(Request $request)
     {
          $leave = LocalLeave::find($request->leave_id);
          $employee = Employee::where('user_id', '=', Auth::user()->id)->first();
          $leave_type = LeaveType::find($request->leave_type_id);

          if ($leave == null)
          {
               return ApiResponse(['status' => 0, 'message' => __('Leave not found.')]);
          }

          if (Auth::user()->type == 'employee' && $employee == null)
          {
               return ApiResponse(['status' => 0, 'message' => __("You are not an employee, please contact your administrator.")]);
          }

          $oldLeaveStatus = $leave->status;

          $validator = Validator::make(
               $request->all(),
               [
                    'leave_type_id' => 'required',
                    'start_date' => 'required|date_format:Y-m-d|before_or_equal:end_date',
                    //'end_date' => 'required_if:leave_type_day,1|date_format:Y-m-d|after_or_equal:start_date',
                    'leave_reason'  => 'required',
                    'remark'        => 'required',
               ]
          );
          if ($validator->fails())
          {
               $messages = $validator->messages();
               $response = ['status' => 0, 'message' => $messages->first()];
               return ApiResponse($response);
          }

          $leave_type = LeaveType::find($request->leave_type_id);
          if ($leave_type == null)
          {
               return ApiResponse(['status' => 0, 'message' => __("Invalid Leave Type")]);
          }

          $startDate = new \DateTime($request->start_date);
          $endDate = new \DateTime($request->end_date);
          $endDate->add(new \DateInterval('P1D'));
          $total_leave_days = !empty($startDate->diff($endDate)) ? $startDate->diff($endDate)->days : 0;

          $employeeId = null;
          $employee = [];

          if (Auth::user()->type == 'employee')
          {
               $employee = Employee::where('user_id', '=', Auth::user()->id)->first();
               $employeeId = $employee->id;
          }
          else
          {
               $employeeId = $request->employee_id;
          }

          if (Auth::user()->type == 'employee' && $leave->employee_id != $employeeId)
          {
               return ApiResponse(['status' => 0, 'message' => __('Permission denied.')]);
          }

          if (Auth::user()->type != 'company')
          {
               $appliedLeaveDays = getAppliedLeaveDays($request->leave_type_day, $request->start_date, $request->end_date);
               $balanceEmployeeId = !empty($employeeId) ? $employeeId : $leave->employee_id;
               $leaveBalanceValidation = validateEmployeeLeaveBalance($balanceEmployeeId, $request->leave_type_id, $appliedLeaveDays, $request->start_date);

               if ((int) $leaveBalanceValidation['status'] !== 1)
               {
                    return ApiResponse([
                         'status' => 0,
                         'message' => $leaveBalanceValidation['message'],
                    ]);
               }
          }

          // if ($leave_type->days >= $total_leave_days)
          // {
          $starting_date = $request->start_date;
          $enddate = $request->end_date;
          $start_month = date('m', strtotime($starting_date));
          $end_month = date('m', strtotime($enddate));

          if ($start_month == $end_month)
          {
               DB::beginTransaction();

               try
               {

                    $leave->leave_type_id = $request->leave_type_id;
                    $leave->start_date = $request->start_date;

                    $leave->end_date = ($request->leave_type_day == 0 ? $request->start_date : $request->end_date);

                    if ($request->leave_type_day == 0)
                    {
                         $leave->total_leave_days = 0.5;
                    }
                    else
                    {
                         $leave->total_leave_days = $total_leave_days;
                    }

                    $leave->total_leave_days = $total_leave_days;
                    $leave->leave_day        = $request->leave_type_day;
                    $leave->leave_reason     = $request->leave_reason;
                    $leave->remark           = $request->remark;

                    $update_logs = json_decode($leave->update_logs ?? "", true) ?? [];

                    $userLeaveBalance = UserLeaveBalanceYear::where("leave_type_id", $request->leave_type_id)->where("leaves_year", date("Y"))->where("user_id", $leave->employee_id)->sum("pending_leaves");

                    $update_logs[] = [
                         "updated_by" => Auth::user()->id,
                         "updated_at" => Carbon::now()->format("d-m-Y h:i A"),
                         "userLeaveBalance" => $userLeaveBalance,
                    ];

                    $leave->update_logs = json_encode($update_logs);
                    if (Auth::user()->type != 'employee')
                    {
                         $leave->status = $request->status ?? "Pending";
                    }
                    else
                    {
                         $leave->status = "Pending";
                    }

                    $leave->save();
                    Log::channel('leave_log')->info("leave update_leave same month userLeaveBalance: ", [$userLeaveBalance]);
                    Log::channel('leave_log')->info("leave update_leave same month employee_id: ", [$leave->employee_id]);

                    if (Auth::user()->type != 'employee')
                    {
                         // leave balance operations
                         if ($leave->deducted_leave_balance > 0) // add this leave balance to
                         {
                              $incrementLeaveBalance = number_format($leave->deducted_leave_balance, 2);

                              UserLeaveBalance::where("leave_type_id", $leave->leave_type_id)
                                   ->where("user_id", $leave->employee_id)
                                   ->increment('pending_leaves', $incrementLeaveBalance);

                              UserLeaveBalanceYear::where("leave_type_id", $leave->leave_type_id)
                                   ->where("user_id", $leave->employee_id)
                                   ->where("leaves_year", date("Y", strtotime($leave->start_date)))
                                   ->increment('pending_leaves', $incrementLeaveBalance);
                         }
                         $leave->deducted_leave_balance = 0;
                         $leave->extra_leave_days = 0;
                         $leave->save();

                         employeeLeaveBalanceOperations($leave->id, $leave->status, $oldLeaveStatus);

                         // if this leave is rejected or pending then deducted leave balance of this leave will be added back in leave balance from above function ,
                         // so check if there is any other leave are approved then calculate its leave balance which should deduct and add extra days it there is extra days.
                         if ($leave->status == "Reject" || $leave->status == "Pending")
                         {
                              reCalculateLeaveBalanceData($leave->employee_id, [$leave->id], date("Y-m-d"));
                         }
                         // leave balance operations

                    }
               }
               catch (Exception | Throwable $ex)
               {
                    DB::rollBack();
                    $response = ['status' => 0, 'message' => showExceptionMessage($ex)];
                    return ApiResponse($response);
               }
               DB::commit();
          }
          else
          {

               DB::beginTransaction();

               try
               {
                    if (Auth::user()->type != 'employee')
                    {
                         // leave bvalance operations
                         if ($leave->deducted_leave_balance > 0) // add this leave balance to
                         {
                              $incrementLeaveBalance = number_format($leave->deducted_leave_balance, 2);

                              UserLeaveBalance::where("leave_type_id", $leave->leave_type_id)
                                   ->where("user_id", $leave->employee_id)
                                   ->increment('pending_leaves', $incrementLeaveBalance);

                              UserLeaveBalanceYear::where("leave_type_id", $leave->leave_type_id)
                                   ->where("user_id", $leave->employee_id)
                                   ->where("leaves_year", date("Y", strtotime($leave->start_date)))
                                   ->increment('pending_leaves', $incrementLeaveBalance);
                         }
                         // leave bvalance operations
                    }
               }
               catch (Exception | Throwable $ex)
               {
                    DB::rollBack();
                    return redirect()->back()->with('error', showExceptionMessage($ex));
               }

               $employeeId = $leave->employee_id;

               $leave->delete();

               $oldLeaveStatus = "";

               $for_starting_start_date = $starting_date;
               $for_starting_end_date = date("Y-m-t", strtotime($for_starting_start_date));
               $datediff = strtotime($for_starting_end_date) - strtotime($for_starting_start_date);
               $count_start_days = round($datediff / (60 * 60 * 24)) + 1;

               $superior_user = null;

               if (Auth::user()->type == 'employee')
               {
                    $employee = Employee::where('user_id', '=', Auth::user()->id)->first();
                    $superior_user = $employee->users_types_id;
               }
               else
               {
                    $users = User::where('type', '!=', 'employee')->where('created_by', '=', Auth::user()->creatorId())->find(Auth::id());
                    if (!empty($users->assign_user))
                    {
                         $superior_user = $users->assign_user;
                    }
               }

               //next month leave 0
               try
               {
                    $leave_1    = new LocalLeave();
                    $leave_1->employee_id = $employeeId;

                    $leave_1->leave_type_id    = $request->leave_type_id;
                    $leave_1->applied_on       = date('Y-m-d');
                    $leave_1->start_date       = $for_starting_start_date;
                    $leave_1->end_date         = $for_starting_end_date;
                    $leave_1->total_leave_days = $count_start_days;
                    $leave_1->leave_reason     = $request->leave_reason;
                    $leave_1->remark           = $request->remark;
                    // $leave_1->status           = 'Pending';
                    $leave_1->secound_leave_status           = 'Pending';
                    $leave_1->leave_day        = $request->leave_type_day;
                    $leave_1->superior_user    = $superior_user;
                    $leave_1->created_by       = Auth::user()->creatorId();

                    $userLeaveBalance = UserLeaveBalanceYear::where("leave_type_id", $request->leave_type_id)->where("leaves_year", date("Y"))->where("user_id", $employeeId)->sum("pending_leaves");
                    $update_logs = [
                         "updated_by" => Auth::user()->id,
                         "updated_at" => Carbon::now()->format("d-m-Y h:i A"),
                         "userLeaveBalance" => $userLeaveBalance,
                    ];

                    $leave_1->update_logs = json_encode($update_logs);

                    if (Auth::user()->type != 'employee')
                    {
                         $leave_1->status = $request->status ?? "Pending";
                    }
                    else
                    {
                         $leave_1->status = "Pending";
                    }

                    $leave_1->save();
                    Log::channel('leave_log')->info("leave update_leave diff month userLeaveBalance: ", [$userLeaveBalance]);
                    Log::channel('leave_log')->info("leave update_leave diff month employee_id: ", [$leave_1->employee_id]);
               }
               catch (Exception | Throwable $ex)
               {
                    DB::rollBack();
                    $response = ['status' => 0, 'message' => showExceptionMessage($ex)];
                    return ApiResponse($response);
               }

               // leave bvalance operations
               if ($leave_1->status == "Approved" && Auth::user()->type != 'employee')
               {
                    employeeLeaveBalanceOperations($leave_1->id, $leave_1->status, $oldLeaveStatus);
               }
               // leave bvalance operations





               // Google celander
               // if ($request->get('synchronize_type')  == 'google_calender')
               // {
               //      $type = 'leave';
               //      $request1 = new GoogleEvent();
               //      $request1->title = !empty(Auth::user()->getLeaveType($leave->leave_type_id)) ? Auth::user()->getLeaveType($leave->leave_type_id)->title : '';
               //      $request1->start_date = $for_starting_start_date;
               //      $request1->end_date = $for_starting_end_date;
               //      Utility::addCalendarData($request1, $type);
               // }

               //next month leave 1
               $end_start_date = date("Y-m-01", strtotime($enddate));
               $end_end_date = $enddate;

               $datediffend = strtotime($end_end_date) - strtotime($end_start_date);
               $count_end_days = round($datediffend / (60 * 60 * 24)) + 1;

               try
               {
                    $leave_2    = new LocalLeave();

                    $leave_2->employee_id = $employeeId;

                    $leave_2->leave_type_id    = $request->leave_type_id;
                    $leave_2->applied_on       = date('Y-m-d');
                    $leave_2->start_date       = $end_start_date;
                    $leave_2->end_date         = $end_end_date;
                    $leave_2->total_leave_days = $count_end_days;
                    $leave_2->leave_reason     = $request->leave_reason;
                    $leave_2->remark           = $request->remark;
                    // $leave_2->status           = 'Pending';
                    $leave_2->secound_leave_status = 'Pending';
                    $leave_2->leave_day        = $request->leave_type_day;
                    $leave_2->superior_user    = $superior_user;
                    $leave_2->created_by       = Auth::user()->creatorId();

                    $leave_2->update_logs = json_encode($update_logs);

                    if (Auth::user()->type != 'employee')
                    {
                         $leave_2->status = $request->status ?? "Pending";
                    }
                    else
                    {
                         $leave_2->status = "Pending";
                    }

                    $leave_2->save();
                    Log::channel('leave_log')->info("leave update_leave diff month 2 userLeaveBalance: ", [$userLeaveBalance]);
                    Log::channel('leave_log')->info("leave update_leave diff month 2 employee_id: ", [$leave_2->employee_id]);

                    // leave bvalance operations
                    if ($leave_2->status == "Approved" && Auth::user()->type != 'employee')
                    {
                         employeeLeaveBalanceOperations($leave_2->id, $leave_2->status, $oldLeaveStatus);
                    }
                    // leave bvalance operations
               }
               catch (Exception | Throwable $ex)
               {
                    DB::rollBack();
                    $response = ['status' => 0, 'message' => showExceptionMessage($ex)];
                    return ApiResponse($response);
               }

               DB::commit();
          }

          $employee = Employee::where('id', '=', $employeeId)->first();

          // Send Push Notification to App
          if (Auth::user()->type != 'employee')
          {
               if (in_array($leave->status, ["Approved", "Approve", "Reject"]))
               {
                    $usersData = User::where("id", $employee->user_id)->where("device_token", "!=", NULL)->select("id", "device_type", "device_token")->get();

                    if (!$usersData->isEmpty())
                    {
                         $title = __("Your Leave has been $leave->status");
                         $body = __("Your Leave has been $leave->status");

                         $click_action = "leave_status_changed";
                         $extraData = ["id" => $leave->id];

                         sendpPushNotification($title, $body, $usersData, $click_action, $extraData);
                    }
               }
          }
          // Send Push Notification to App

          //notification Code
          if (Auth::user()->type != 'employee') // send notification to employe
          {
               $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();
               foreach ($upUser as $row)
               {
                    foreach (json_decode($row['assign_employees']) ?? [] as $key => $user)
                    {
                         if (!empty($user))
                         {
                              if ($user == $employee->id)
                              {
                                   $notification               = new NotificationModel();
                                   $notification->user_id      = $row->id;
                                   $notification->employee_id  = $employee->id;
                                   $notification->title        = "Leave request updated by " . $employee->name . " ";
                                   $notification->details      = "Leave request updated by " . $employee->name . " ";
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->redirect     = 'leave';
                                   $notification->save();
                              }
                         }
                    }
               }
          }
          else
          {
               $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();
               foreach ($upUser as $row)
               {
                    foreach (json_decode($row['assign_employees']) ?? [] as $key => $user)
                    {
                         if (!empty($user))
                         {
                              if ($user == $employee->id)
                              {
                                   $notification               = new NotificationModel();
                                   $notification->user_id      = $row->id;
                                   $notification->employee_id  = $employee->id;
                                   $notification->title        = "Leave request updated by " . $employee->name . " ";
                                   $notification->details      = "Leave request updated by " . $employee->name . " ";
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->redirect     = 'leave';
                                   $notification->save();
                              }
                         }
                    }
               }
          }
          //end notification

          $response = ['status' => 1, 'message' => __('Leave successfully updated.')];
          // }
          // else
          // {
          //      $response = ['status' => 0, 'message' => __('Leave type ' . $leave_type->name . ' is provide maximum ' . $leave_type->days . "  days please make sure your selected days is under " . $leave_type->days . ' days.')];
          // }

          return ApiResponse($response);
     }

     public function update_leave_original(request $request)
     {
          try
          {
               DB::beginTransaction();
               $leave = LocalLeave::find($request->leave_id);
               $employee = Employee::where('user_id', '=', Auth::user()->id)->first();
               $leave_type = LeaveType::find($request->leave_type_id);

               $startDate = new \DateTime($request->start_date);

               $endDate = new \DateTime($request->end_date);
               $endDate->add(new \DateInterval('P1D'));
               $total_leave_days = !empty($startDate->diff($endDate)) ? $startDate->diff($endDate)->days : 0;

               // if ($leave_type->days >= $total_leave_days)
               // {
               $leave->employee_id      = $employee->id;
               $leave->leave_type_id    = $request->leave_type_id;
               $leave->start_date       = $request->start_date;
               // $leave->end_date         = $request->end_date;
               // $leave->total_leave_days = $total_leave_days;

               if ($request->leave_type_day == 0)
               {
                    $leave->end_date         = $request->start_date;
                    $leave->total_leave_days = 0.5;
               }
               else
               {
                    $leave->end_date         = $request->end_date;
                    $leave->total_leave_days = $total_leave_days;
               }

               $leave->leave_day        = $request->leave_type_day;
               $leave->leave_reason     = $request->leave_reason;
               $leave->remark           = $request->remark;

               $leave->save();
               DB::commit();
               $response = [
                    'status' => 1,
                    'message' => __("Leave updated successfully."),
               ];
               // }
               // else
               // {
               //      $response = [
               //           'status' => 0,
               //           'message' => __('Leave type ' . $leave_type->name . ' is provide maximum ' . $leave_type->days . "  days please make sure your selected days is under " . $leave_type->days . ' days.')
               //      ];
               // }
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function delete_leave(request $request)
     {
          try
          {
               $data = LocalLeave::find($request->id);
               if ($data != null)
               {
                    DB::beginTransaction();

                    $data->delete();

                    DB::commit();

                    $response = [
                         'status' => 1,
                         'message' => __("Leave deleted successfully."),
                    ];
               }
               else
               {
                    $response = [
                         'status' => 0,
                         'message' => __("No data found."),
                    ];
               }
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function my_leave_balance(request $request)
     {
          $user     = Auth::user();
          $employee = Employee::where('user_id', '=', $user->id)->first();
          $monthAndYear = $request->year ?? date('Y-m');

          $month = date("m", strtotime($monthAndYear));
          $year = date("Y", strtotime($monthAndYear));

          $userLeaveBalanceTotal = 0;

          try
          {
               $totalLeaveBlance = [];
               $user     = Auth::user();
               $employee = Employee::where('user_id', '=', $user->id)->first();

               $leave_counts =  LeaveType::where('play_slab_id', $employee->play_slab_id)->where('created_by', Auth::user()->creatorId())->get();

               if (!empty($leave_counts))
               {
                    foreach ($leave_counts as $row)
                    {
                         $userLeaveBalance = UserLeaveBalanceYear::where("leave_type_id", $row->id)->where("leaves_year", $year)->where("user_id", $employee->id)->sum("pending_leaves");
                         $row->used_leaved = (string)$userLeaveBalance;
                         $row->leave_balance = (string)$userLeaveBalance;
                         $totalLeaveBlance[] = $userLeaveBalance;

                         $applied_leaves = LocalLeave::where('leave_type_id', $row->id)
                              ->where('employee_id', $employee->id)
                              ->whereYear("start_date", $year)
                              ->whereMonth("start_date", $month)
                              ->where('status', 'Approved')
                              ->sum('total_leave_days');
                         $row->applied_leaves = $applied_leaves;
                    }
               }

               $data = $leave_counts;
          }
          catch (Exception $ex)
          {
               return view('leave.my_leave_balance')->with('error', showExceptionMessage($ex));
          }
          catch (Throwable $ex)
          {
               return view('leave.my_leave_balance')->with('error', showExceptionMessage($ex));
          }

          $yearsCount = 4;

          $yearsData = [];
          for ($i = 0; $i <= $yearsCount; $i++)
          {
               $value = date('Y', strtotime("-$i year"));
               $yearsData[] = ["id" => $value, "name" => $value];
          }

          if (!empty($data))
          {
               $response = [
                    'status' => 1,
                    'message' => __("Leave Balance"),
                    'data'  => $data,
                    'yearsData'  => $yearsData,
                    'selectedYear'  => $year,
                    //'total_leave_balance'  => $userLeaveBalanceTotal,
                    'total_leave_balance'  => (string)array_sum($totalLeaveBlance),
               ];
               return ApiResponse($response);
          }
          else
          {
               $response = [
                    'status' => 0,
                    'message' => 'data not found'
               ];
               return ApiResponse($response);
          }
     }

     public function my_leave_balance_original(request $request)
     {
          $user     = Auth::user();
          $employee = Employee::where('user_id', '=', $user->id)->first();

          $data =  LeaveType::where('play_slab_id', $employee->play_slab_id)->where('created_by', Auth::user()->creatorId())->get();

          if (!empty($data))
          {
               foreach ($data as $row)
               {
                    $leave = LocalLeave::where('leave_type_id', $row->id)->where('employee_id', $employee->id)->where('status', 'Approved')->sum('total_leave_days');
                    $row->assign_leave = $row->days;
                    $row->year = date('M-Y');
                    $row->utilized_leave = $leave;
                    $row->balance_leave = $row->days - $leave;
               }
          }
          if (!empty($data))
          {
               $response = [
                    'status' => 1,
                    'message' => 'success',
                    'data'  => $data
               ];
               return ApiResponse($response);
          }
          else
          {
               $response = [
                    'status' => 0,
                    'message' => 'data not found'
               ];
               return ApiResponse($response);
          }
     }
}
