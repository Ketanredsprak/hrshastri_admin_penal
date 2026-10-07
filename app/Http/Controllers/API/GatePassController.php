<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\GetPass;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\NotificationModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class GatePassController extends Controller
{

     public function update_gate_pass(request $request)
     {
          Log::info("Gate Pass Create Data UPDATE", $request->all());
          try
          {
               DB::beginTransaction();
               $inTime = $request->in_time;
               $outTime = $request->out_time;
               $employeeId = '';
               if (Auth::user()->type == 'company')
               {
                    $employeeId = $request->employee_id;
               }
               else
               {
                    $employeeId = Auth::id();
               }
               $gatePassId = $request->gate_pass_id ?? $request->id;
               $data = GetPass::find($gatePassId);
               if ($data == null)
               {
                    DB::rollBack();
                    return ApiResponse([
                         'status' => 0,
                         'message' => __("No Data Found"),
                    ]);
               }
               $data->name = $request->leave_type_id;
               // $data->employee_id = $employeeId;

               $data->date = $request->start_date;
               $data->in_time = $inTime;
               $data->out_time = $outTime;
               $data->resone = $request->leave_reason;
               $data->veryfy_status = 0;
               $data->status = 0;
               $data->created_by = Auth::user()->creatorId();
               $data->save();

               if (Auth::user()->type == 'employee')
               {
                    $Employee = Employee::where('user_id', $employeeId)->first();
               }
               else
               {
                    $Employee = User::find(Auth::id());
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
                                   $notification = new NotificationModel();
                                   $notification->user_id = $row->id;
                                   $notification->employee_id = $employee->id;
                                   $notification->title = "Gate pass apply by  " . $employee->name . " ";
                                   $notification->details = "Gate pass apply by  " . $employee->name . " ";
                                   $notification->status = 0;
                                   $notification->date = date('d-m-Y');
                                   $notification->created_by = $employee->created_by;
                                   $notification->redirect = 'getpass';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification
               DB::commit();
               $response = [
                    'status' => 1,
                    'message' => __("Gatepass updated successfully."),
               ];
          }
          catch (Exception | Throwable $e)
          {
               Log::error("Gate Pass Update Error", [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
               ]);
               Log::error("Gate Pass Update Error Data", $request->all());
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function get_gate_pass(request $request)
     {
          try
          {
               $emp = Employee::where('user_id', '=', Auth::user()->id)->first();
               $keyword = $request->input('search_key');
               $data = GetPass::where('employee_id', $emp->id)->where('created_by', '=', Auth::user()->creatorId())
                    ->where(function ($query) use ($keyword)
                    {
                         $query->Where('status', 'like', '%' . $keyword . '%')
                              ->orWhere('date', 'like', '%' . $keyword . '%')
                              ->orWhere('resone', 'like', '%' . $keyword . '%');
                    })->orderBy('id', 'desc');

               $response = paginate($data, ($request->page ?? null));

               $response["statistics"] = [
                    "pending" => (!empty($response["data"])) ? $response["data"]->where('status', 0)->count() : 0,
                    "approved" => (!empty($response["data"])) ? $response["data"]->where('status', 1)->count() : 0,
                    "rejected" => (!empty($response["data"])) ? $response["data"]->where('status', 2)->count() : 0,
               ];
          }
          catch (Exception | Throwable $e)
          {
               $response = ['status' => 0, 'message' => showExceptionMessage($e)];
          }
          return ApiResponse($response);
     }

     public function create_gate_pass(request $request)
     {
          Log::info("Gate Pass Create Data CREATE", $request->all());
          try
          {
               DB::beginTransaction();
               $inTime = $request->in_time;
               $outTime = $request->out_time;
               $employeeId = '';

               $employee = Employee::where('user_id', '=', Auth::user()->id)->first();

               $data = new GetPass();
               $data->name = $request->leave_type_id;
               $data->employee_id = $employee->id;
               $data->date = $request->start_date;
               $data->in_time = $inTime;
               $data->out_time = $outTime;
               $data->resone = $request->leave_reason;
               $data->veryfy_status = 0;
               $data->status = 0;
               $data->created_by = Auth::user()->creatorId();
               $data->save();

               DB::commit();

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
                                   $notification = new NotificationModel();
                                   $notification->user_id = $row->id;
                                   $notification->employee_id = $employee->id;
                                   $notification->title = "Gate pass apply by " . $employee->name . " ";
                                   $notification->details = "Gate pass apply by " . $employee->name . " ";
                                   $notification->status = 0;
                                   $notification->date = date('d-m-Y');
                                   $notification->created_by = $employee->created_by;
                                   $notification->redirect = 'getpass';
                                   $notification->save();
                              }
                         }
                    }
               }
               //end notification


               $response = [
                    'status' => 1,
                    'message' => __("Gatepass created successfully."),
               ];
          }
          catch (Exception | Throwable $e)
          {
               Log::error("Gate Pass Create Error", [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
               ]);
               Log::error("Gate Pass Create Error Data", $request->all());

               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }


     public function delete_gate_pass(request $request)
     {
          try
          {
               $data = GetPass::find($request->id);
               if ($data != null)
               {
                    DB::beginTransaction();
                    $data->delete();
                    DB::commit();
                    $response = ['status' => 1, 'message' => __("Gatepass deleted successfully.")];
               }
               else
               {
                    $response = ['status' => 0, 'message' => __("No Data Found")];
               }
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }
}
