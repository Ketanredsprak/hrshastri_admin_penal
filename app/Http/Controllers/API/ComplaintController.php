<?php

namespace App\Http\Controllers\API;

use App\Models\Utility;
use App\Models\Employee;
use App\Models\Complaint;
use App\Mail\ComplaintsSend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

class ComplaintController extends Controller
{
     public function get_complaint_against(request $request)
     {
          try
          {
               $user = Auth::user();
               $data = Employee::where('created_by', Auth::user()->creatorId())->where('user_id', '!=', $user->id)->select('id', 'name');
               $result = paginate($data, ($request->page ?? null));
               return ApiResponse($result);
          }
          catch (Exception | Throwable $e)
          {
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function get_complaint(request $request)
     {
          try
          {
               $emp   = Employee::where('user_id', '=', Auth::user()->id)->first();

               $data  = Complaint::select('id', 'complaint_from', 'complaint_against', 'title', 'description', 'complaint_date')
                    ->with(['complaintFromR' => function ($q)
                    {
                         $q->select('id', 'name');
                    }, 'complaintAgainstR' => function ($q)
                    {
                         $q->select('id', 'name');
                    }])
                    ->where('complaint_from', '=', $emp->id)->orderBy('id', 'desc')->get();

               if (count($data) > 0)
               {
                    $response = ['status' => 1, 'message' => __("Complaint List"), 'data' => $data];
               }
               else
               {
                    $response = ['status' => 0, 'message' => 'Data not found'];
               }
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function create_complaint(request $request)
     {
          try
          {
               DB::beginTransaction();

               $complaint = new Complaint();
               $emp = Employee::where('user_id', '=', Auth::user()->id)->first();
               $complaint->complaint_from = $emp->id;

               $complaint->complaint_against = $request->complaint_against;
               $complaint->title             = $request->title;
               $complaint->complaint_date    = $request->complaint_date;
               $complaint->description       = $request->description;
               $complaint->created_by        = Auth::user()->creatorId();
               $complaint->save();
               $setings = Utility::settings();
               if ($setings['employee_complaints'] == 1)
               {

                    $employee   = Employee::find($complaint->complaint_against);
                    $uArr = [
                         'employee_complaints_name' => $employee->name,
                    ];
                    $resp = Utility::sendEmailTemplate('employee_complaints', ['viral@mailinator.com'], $uArr);
               }
               DB::commit();
               $response = ['status' => 1, 'message' => __("Complaint created successfully.")];
               return ApiResponse($response);
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function update_complaint(request $request)
     {
          try
          {
               DB::beginTransaction();

               $complaint = Complaint::find($request->id);
               $emp                          = Employee::where('user_id', '=', Auth::id())->first();
               $complaint->complaint_from    = $emp->id;
               $complaint->complaint_against = $request->complaint_against;
               $complaint->title             = $request->title;
               $complaint->complaint_date    = $request->complaint_date;
               $complaint->description       = $request->description;
               $complaint->save();

               DB::commit();

               $response = ['status' => 1, 'message' => __("Complaint updated successfully.")];
               return ApiResponse($response);
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }

     public function delete_complaint(request $request)
     {
          try
          {
               DB::beginTransaction();
               $complaint = Complaint::find($request->id);
               $complaint->delete();
               DB::commit();

               $response = ['status' => 1, 'message' => __("Complaint deleted successfully.")];
               return ApiResponse($response);
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               return ApiResponse(error_msg(showExceptionMessage($e)));
          }
     }
}
