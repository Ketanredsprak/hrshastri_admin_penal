<?php

namespace App\Http\Controllers\API;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\GetPass;
use App\Models\ApplyWFM;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\NotificationModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ApplyWFHController extends Controller
{
     public function get_apply_wfh(request $request)
     {
          try
          {
               $emp   = Employee::where('user_id', '=', Auth::user()->id)->first();
               $keyword = $request->input('search_key');
               $data = ApplyWFM::select("*", "start_date as start_date_formatted", "end_date as end_date_formatted")->where('employee_id', $emp->id)
                    ->where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'DESC')
                    ->where(function ($query) use ($keyword)
                    {
                         $query->Where('status', 'like', '%' . $keyword . '%')
                              ->orWhere('note', 'like', '%' . $keyword . '%');
                    });
               $result = paginate($data, ($request->page ?? null));

               $result["statistics"] = [
                    "pending" => applywfh_pending(),
                    "approved" => applywfh_approved(),
                    "rejected" => applywfh_rejected()
               ];
               return ApiResponse($result);
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function create_apply_wfh(request $request)
     {
          try
          {
               DB::beginTransaction();
               $employee = Employee::where('user_id', '=', Auth::id())->first();
               $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();

               //notification Code
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
                                   $notification->title        = $employee->name . " has applied for Work-From-Home.";
                                   $notification->details      = $employee->name . " has applied for Work-From-Home.";
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->redirect   = 'applywfm';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification
               $data                = new ApplyWFM();
               $data->employee_id   = $employee->id;
               $data->start_date    = $request->start_date;
               $data->end_date      = $request->end_date;
               $data->note          = $request->note;
               $data->status        = 0;
               $data->created_by    = Auth::user()->creatorId();
               $data->save();


               DB::commit();

               $response = [
                    'status' => 1,
                    'message' => __("Work from home applied successfully."),
               ];
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function update_apply_wfh(request $request)
     {
          try
          {
               DB::beginTransaction();

               $data                =  ApplyWFM::find($request->apply_wfh_id);
               $data->start_date    = $request->start_date;
               $data->end_date      = $request->end_date;
               $data->note          = $request->note;
               $data->status        = 0;
               $data->created_by    = Auth::user()->creatorId();
               $data->save();

               $Employee = User::find(Auth::id());
               $employee = Employee::where('user_id', '=', Auth::id())->first();
               $upUser = User::where('assign_employees', '!=', null)->where('created_by', '=', Auth::user()->creatorId())->select('assign_employees', 'id')->get();
               //notification Code
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
                                   $notification->title        = $employee->name . " has applied for Work-From-Home.";
                                   $notification->details      = $employee->name . " has applied for Work-From-Home.";
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->redirect   = 'applywfm';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification
               DB::commit();
               $response = [
                    'status' => 1,
                    'message' => __("Work from home request updated successfully."),
               ];
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function delete_apply_wfh(request $request)
     {
          try
          {
               DB::beginTransaction();
               $data  =  ApplyWFM::find($request->apply_wfh_id);
               $data->delete();

               $response = [
                    'status' => 1,
                    'message' => __("Work from home request deleted successfully."),
               ];
               DB::commit();
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }
}
