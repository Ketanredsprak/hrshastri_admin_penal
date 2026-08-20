<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Employee;
use App\Models\Utility;
use Illuminate\Http\Request;
use App\Models\NotificationModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\EarlyLateCommingLeave;
use Carbon\Carbon;
use Exception;
use Throwable;

class EarlyLeaveController extends Controller
{
     public function get_early_Leave(request $request)
     {
          try
          {
               $settings = Utility::settings();
               $short_leave_hours = $settings['short_leave_hours'] ?? 0;
               $short_leave_limit = $settings['short_leave_limit'] ?? 0;

               $employee = Employee::where('user_id', Auth::id())->first();
               $keyword = $request->input('search_key');
               $data = EarlyLateCommingLeave::where('employee_id', $employee->id)->where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'DESC')
                    ->where(function ($query) use ($keyword)
                    {
                         $query->Where('status', 'like', '%' . $keyword . '%')
                              ->orWhere('resone', 'like', '%' . $keyword . '%')
                              ->orWhere('date', 'like', '%' . $keyword . '%');
                    });

               $result = paginate($data, ($request->page ?? null));

               $short_leave_count = EarlyLateCommingLeave::where('employee_id', $employee->id)
                                   ->where('status', '!=', 2)
                                   ->whereRaw('YEAR(date) = ?', [now()->year])
                                   ->whereRaw('MONTH(date) = ?', [now()->month])
                                   ->count();
               $result["statistics"] = [
                    "pending" => $result["status"] == 1 ? ($result["data"]->where('status', 0)->count() ?? 0) : 0,
                    "approved" => $result["status"] == 1 ? ($result["data"]->where('status', 1)->count() ?? 0) : 0,
                    "rejected" => $result["status"] == 1 ? ($result["data"]->where('status', 2)->count() ?? 0) : 0,
                    "short_leave_limit" => intval($short_leave_limit),
                    "short_leave_count" => $short_leave_count,
               ];

               return ApiResponse($result);
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function create_early_Leave(request $request)
     {
          try
          {
               $settings = Utility::settings();
               $short_leave_hours = $settings['short_leave_hours'] ?? 0;
               $short_leave_limit = $settings['short_leave_limit'] ?? 0;

               DB::beginTransaction();
               // $inTime = $request->in_time;
               // $outTime = $request->out_time;

               $employee = Employee::where('user_id', '=', Auth::id())->first();

               $short_leave_count = EarlyLateCommingLeave::where('employee_id', $employee->id)
                              ->where('status', '!=', 2)
                              ->whereRaw('YEAR(date) = ?', [now()->year])
                              ->whereRaw('MONTH(date) = ?', [now()->month])
                              ->count();
               if($short_leave_limit <= $short_leave_count) {
                    $response = [
                         'status' => 0,
                         'message' => __('You can take up to ' . $short_leave_limit . ' short leaves per month. Otherwise, please apply for Half/Full Day leave.'),
                    ];
               }

               $data               = new EarlyLateCommingLeave();
               $data->name         = $request->leave_type_id;
               $data->employee_id  = $employee->id;

               $data->date      = $request->start_date;
               // $data->in_time   = $inTime;
               // $data->out_time  = $outTime;
               $data->in_time   = "";
               $data->out_time  = "";
               $data->resone    = $request->leave_reason;
               $data->veryfy_status    = 0;
               $data->status           = 0;
               $data->created_by       = Auth::user()->creatorId();
               $data->save();

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
                                   $notification->title        = "Short leave applied by " . $employee->name . " ";
                                   $notification->details      = "Short leave applied by " . $employee->name . " ";
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->redirect   = 'earlyandlate_leave';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification

               DB::commit();

               $response = [
                    'status' => 1,
                    'message' => __("Short leave created successfully."),
               ];
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function update_early_Leave(request $request)
     {
          try
          {
               DB::beginTransaction();
               // $inTime = $request->in_time;
               // $outTime = $request->out_time;

               $data = EarlyLateCommingLeave::find($request->early_Leave_id);

               $data->name      = $request->leave_type_id;
               $data->date      = $request->start_date;
               // $data->in_time   = $inTime;
               // $data->out_time  = $outTime;
               $data->in_time   = "";
               $data->out_time  = "";
               $data->resone    = $request->leave_reason;
               $data->veryfy_status    = 0;
               $data->status    = 0;
               $data->created_by       = Auth::user()->creatorId();
               $data->save();

               $Employee = Employee::where('user_id', Auth::id())->first();

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
                                   $notification->title        = "Short leave applied by " . $employee->name . " ";
                                   $notification->details      = "Short leave applied by " . $employee->name . " ";
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->redirect   = 'earlyandlate_leave';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification
               DB::commit();
               $response = [
                    'status' => 1,
                    'message' => __("Short leave updated successfully."),
               ];
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function delete_early_Leave(request $request)
     {
          try
          {
               $data = EarlyLateCommingLeave::find($request->early_Leave);
               $data->delete();
               $response = [
                    'status' => 1,
                    'message' => __("Short leave deleted successfully."),
               ];
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }
}
