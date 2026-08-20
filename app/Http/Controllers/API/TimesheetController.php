<?php

namespace App\Http\Controllers\API;

use Exception;
use Throwable;
use App\Models\Employee;
use App\Models\TimeSheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TimesheetController extends Controller
{
     public function get_timesheet(request $request)
     {
          try
          {

               $employee = Employee::where('user_id', '=', Auth::user()->id)->first();

               $data = TimeSheet::select("*", "date as raw_date")->orderBy('id', 'desc')->where('employee_id', $employee->id);
               if (!empty($request->start_date))
               {
                    $data->where('date', '=', $request->start_date);
               }

               // if (!empty($request->end_date))
               // {
               //      $data->where('date', '<=', $request->end_date);
               // }
               $response = paginate($data, ($request->page ?? null));
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function create_timesheet(request $request)
     {
          try
          {
               DB::beginTransaction();
               $employee = Employee::where('user_id', '=', Auth::user()->id)->first();

               $timeSheetCheck = TimeSheet::where('date', $request->date)->where('employee_id', $employee->id)->first();
               if (!empty($timeSheetCheck))
               {
                    $response = [
                         'status' => 0,
                         'message' => __('Timesheet already created in this day.'),
                    ];
                    return ApiResponse($response);
               }

               $timeSheet = new Timesheet();
               $timeSheet->employee_id = $employee->id;
               $timeSheet->date       = $request->date;
               $timeSheet->hours      = $request->hours;
               $timeSheet->remark     = $request->remark;
               $timeSheet->created_by = Auth::user()->creatorId();
               $timeSheet->save();
               DB::commit();

               $response = [
                    'status' => 1,
                    'message' => __("Timesheet created successfully."),
               ];
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function update_timesheet(request $request)
     {
          try
          {
               DB::beginTransaction();

               $employee = Employee::where('user_id', '=', Auth::user()->id)->first();

               $timeSheetCheck = TimeSheet::where('date', $request->date)->where('employee_id', $employee->id)->first();
               if (!empty($timeSheetCheck) && $timeSheetCheck->id != $request->id)
               {
                    $response = [
                         'status' => 0,
                         'message' => __('Timesheet already created in this day.'),
                    ];
                    return ApiResponse($response);
               }

               $timeSheet = Timesheet::find($request->id);
               $timeSheet->employee_id = $employee->id;
               $timeSheet->date   = $request->date;
               $timeSheet->hours  = $request->hours;
               $timeSheet->remark = $request->remark;
               $timeSheet->save();

               DB::commit();
               $response = [
                    'status' => 1,
                    'message' => __("Timesheet updated successfully."),
               ];
          }
          catch (Exception | Throwable $e)
          {
               DB::rollBack();
               $response = error_msg(showExceptionMessage($e));
          }
          return ApiResponse($response);
     }

     public function delete_timesheet(request $request)
     {
          try
          {
               DB::beginTransaction();
               $timeSheet = Timesheet::find($request->id);
               $timeSheet->delete();
               DB::commit();
               $response = [
                    'status' => 1,
                    'message' => __("Timesheet deleted successfully."),
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
