<?php

namespace App\Http\Controllers\API;

use Exception;
use Throwable;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CityTier;
use App\Models\Employee;
use App\Models\GradeType;
use App\Models\MyVisitModel;
use Illuminate\Http\Request;
use App\Models\GradeTierData;
use App\Models\NotificationModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VisitController extends Controller
{
     public function get_visit(request $request)
     {
          try
          {
               $staff = null;
               $employee = Employee::where('user_id', Auth::id())->first();
               $keyword = $request->input('search_key');

               $data = MyVisitModel::with('tierData', 'cityData')->where('userid', $employee->id)->where('created_by', '=', Auth::user()->creatorId())
                    ->where(function ($query) use ($keyword)
                    {
                         $query->where('visit_type', 'like', '%' . $keyword . '%')
                              ->orWhere('status', 'like', '%' . $keyword . '%')
                              ->orWhere('description', 'like', '%' . $keyword . '%');
                    })->orderBy('id', 'DESC');
               $response = paginate($data, ($request->page ?? null));

               if (!empty($response['data']))
               {
                    foreach ($response['data'] as $row)
                    {
                         $row->visit_type_name =  ($row->visit_type == 1) ? 'Local visit' : 'Out of City visit';
                         $row->form_date = Auth::user()->dateFormat($row->form_date);
                         $row->to_date = Auth::user()->dateFormat($row->to_date);
                         if (!empty($row->status_changed_by))
                         {
                              $staff = Employee::where('user_id', $row->status_changed_by)->first();
                              $row->status_changed_by_name = $staff->name ?? "";
                         }
                         else
                         {
                              $row->status_changed_by_name = null;
                         }
                    }
               }

               $statisticsData = MyVisitModel::with('tierData', 'cityData')->where('userid', $employee->id)->where('created_by', '=', Auth::user()->creatorId())
                    ->where(function ($query) use ($keyword)
                    {
                         $query->where('visit_type', 'like', '%' . $keyword . '%')
                              ->orWhere('status', 'like', '%' . $keyword . '%')
                              ->orWhere('description', 'like', '%' . $keyword . '%');
                    })
                    ->orderBy('id', 'DESC')
                    ->get();

               $response["statistics"] = [
                    "pending" => $statisticsData->where('status', "Pending")->count() ?? 0,
                    "approved" => $statisticsData->where('status', "Approved")->count() ?? 0,
                    "rejected" => $statisticsData->where('status', "Reject")->count() ?? 0,
               ];
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function create_visit(request $request)
     {
          // Log::info("create_visit", [$request->all()]);
          try
          {
               DB::beginTransaction();
               $employee = Employee::where('user_id', Auth::id())->first();
               $data = new MyVisitModel();
               $data->visit_type   = $request->visit_type;
               $data->visit_name   = $request->visit_name;
               $data->form_date    = $request->form_date;
               $data->to_date      = $request->to_date;
               $data->status       = 'Pending';
               $data->userid       = $employee->id;
               $data->description  = $request->description;
               $data->tier_id      = $request->tier_id;
               $data->city_id      = $request->city_id;
               $data->created_by = Auth::user()->creatorId();
               $data->save();
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
                                   $notification->title        = $employee->name . " has requested for visit";
                                   $notification->details      = $employee->name . " has requested for visit";
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->redirect   = 'my_visit';
                                   $notification->save();
                              }
                         }
                    }
               }
               $response = [
                    'status' => 1,
                    'message' => __('Visit created successfully.')
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

     public function update_visit(request $request)
     {
          try
          {
               DB::beginTransaction();
               $data = MyVisitModel::find($request->visit_id);
               $data->visit_type   = $request->visit_type;
               $data->visit_name   = $request->visit_name;
               $data->form_date    = $request->form_date;
               $data->to_date      = $request->to_date;
               $data->description  = $request->description;
               $data->status       = 'Pending';
               $data->tier_id  = $request->tier_id;
               $data->city_id  = $request->city_id;
               $data->created_by = Auth::user()->creatorId();
               $data->save();
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
                                   $notification->title        = $employee->name . " has updated the request for visit";
                                   $notification->details      = $employee->name . " has updated the request for visit";
                                   $notification->status       = 0;
                                   $notification->date         = Carbon::now();
                                   $notification->created_by   = $employee->created_by;
                                   $notification->redirect   = 'my_visit';
                                   $notification->save();
                              }
                         }
                    }
               }
               $response = [
                    'status' => 1,
                    'message' => __('Visit updated successfully')
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

     public function delete_visit(request $request)
     {
          try
          {
               $data = MyVisitModel::find($request->visit_id);
               $data->delete();
               DB::commit();
               $response = [
                    'status' => 1,
                    'message' => __('Visit deleted successfully.')
               ];
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function get_tier(Request $request)
     {
          try
          {
               $employeeData   = Employee::where('user_id', Auth::id())->first();
               $data           = GradeType::where('assign_grade_id', $employeeData->grade_type_id)->where('created_by', Auth::user()->creatorId())->first();
               $datatier = [];
               if (!empty($data))
               {
                    $datatier       = GradeTierData::where('grade_id', $data->id)->groupBy('tier_id')->orderBy('id', 'asc')->get();
               }
               $tierData = [];
               foreach ($datatier as $row)
               {
                    $tierData[] = CityTier::select('id', 'name')->where('id', $row->tier_id)->first();
               }
               $response = [
                    'status' => 1,
                    'message' => __('success'),
                    'data'    => $tierData
               ];
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function get_tier_city(request $request)
     {
          try
          {
               $citytier = CityTier::find($request->getTierid);
               $citydata = DB::table('city')->select('id', 'name')->whereIn('id', $citytier->citysid)->get();
               $response = [
                    'status' => 1,
                    'message' => __('success'),
                    'data'    => $citydata
               ];
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function get_visit_approved(request $request)
     {
          try
          {
               $employee = Employee::where('user_id', Auth::id())->first();
               $myvisit            = MyVisitModel::where('claim_status', null)->where('status', 'Approved')->where('userid', $employee->id)->where('created_by', '=', Auth::user()->creatorId())->get();
               $response = [
                    'status' => 1,
                    'message' => __('success'),
                    'data'    => $myvisit
               ];
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }
}
