<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\PayslipType;
use App\Models\WorkingShiftEmployees;
use App\Models\WorkingShifts;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class WorkingShiftsController extends Controller
{
     public function index()
     {
          $workingShifts = WorkingShifts::where('created_by', '=', Auth::user()->creatorId())->get();
          return view('shift.index', compact('workingShifts'));
     }

     public function create()
     {
          $user  = Auth::user();
          $employees = Employee::where('user_id', '!=', $user->id)->where('created_by', Auth::user()->creatorId())->get();
          return view('shift.create', compact('employees'));
     }

     public function store(Request $request)
     {
          try
          {
               $validator = Validator::make(
                    $request->all(),
                    [
                         'name' => [
                              'required',
                         ],
                         'start_time' => 'required',
                         'end_time' => 'required', //|after:start_time
                    ]
               );
               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
               }

               DB::beginTransaction();

               $workingShift = new WorkingShifts();
               $workingShift->name = $request->name;
               $workingShift->start_time = $request->start_time;
               $workingShift->end_time = $request->end_time;
               $workingShift->flexi_hours = $request->flexi_hours ?? 0;
               $workingShift->flexi_days = $request->flexi_days ?? 0;
               $workingShift->created_by = Auth::user()->creatorId();
               $workingShift->save();


               $assign_employees = $request->assign_employees ?? [];

               // WorkingShiftEmployees::where("working_shifts_id", $workingShift->id)->delete();

               foreach ($assign_employees as $employeeId)
               {
                    WorkingShiftEmployees::where("employee_id", $employeeId)->delete();
                    WorkingShiftEmployees::create([
                         "working_shifts_id" => $workingShift->id,
                         "employee_id" => $employeeId
                    ]);
               }

               DB::commit();

               return redirect()->back()->with('success', __('Shift successfully created.'));
          }
          catch (Exception $ex)
          {
               DB::rollback();
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
          catch (Throwable $ex)
          {
               DB::rollback();
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
     }

     public function show(LeaveType $leavetype)
     {
          return redirect()->route('shift.index');
     }

     public function edit(Request $request, $id)
     {
          try
          {
               $workingShifts = WorkingShifts::where("id", $id)->first();
               if ($workingShifts != null)
               {
                    $assignedEmployees = WorkingShiftEmployees::where("working_shifts_id", $workingShifts->id)->get()->pluck("employee_id")->toArray();
                    $user  = Auth::user();
                    $employees = Employee::where('user_id', '!=', $user->id)->where('created_by', Auth::user()->creatorId())->get();
                    return view('shift.edit', compact('workingShifts', 'employees', 'assignedEmployees'));
               }
               else
               {
                    return redirect()->back()->with('error', __("No Data Found"));
               }
          }
          catch (Exception $ex)
          {
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
          catch (Throwable $ex)
          {
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
     }

     public function update(Request $request, $id)
     {
          try
          {
               $validator = Validator::make(
                    $request->all(),
                    [
                         'name' => [
                              'required',
                         ],
                         'start_time' => 'required',
                         'end_time' => 'required', //|after:start_time
                    ]
               );
               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
               }

               DB::beginTransaction();

               $workingShift = WorkingShifts::where("id", $id)->first();
               if ($workingShift == null)
               {
                    return redirect()->back()->with('error', __("No data found."));
               }

               $workingShift->name = $request->name;
               $workingShift->start_time = $request->start_time;
               $workingShift->end_time = $request->end_time;
               $workingShift->flexi_hours = $request->flexi_hours ?? 0;
               $workingShift->flexi_days = $request->flexi_days ?? 0;
               $workingShift->created_by = Auth::user()->creatorId();
               $workingShift->save();

               $assign_employees = $request->assign_employees ?? [];

               WorkingShiftEmployees::where("working_shifts_id", $workingShift->id)->delete();

               foreach ($assign_employees as $employeeId)
               {
                    WorkingShiftEmployees::where("employee_id", $employeeId)->delete();
                    WorkingShiftEmployees::create([
                         "working_shifts_id" => $workingShift->id,
                         "employee_id" => $employeeId
                    ]);
               }

               DB::commit();

               return redirect()->back()->with('success', __('Shift successfully updated.'));
          }
          catch (Exception $ex)
          {
               // dd($ex);
               DB::rollback();
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
          catch (Throwable $ex)
          {
               // dd($ex);
               DB::rollback();
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
     }

     public function destroy(Request $request, $id)
     {
          try
          {
               if ($id != null)
               {
                    DB::beginTransaction();

                    WorkingShifts::where("id", $id)->delete();
                    WorkingShiftEmployees::where("working_shifts_id", $id)->delete();

                    DB::commit();

                    return redirect()->back()->with('success', __('Shift successfully deleted.'));
               }
               else
               {
                    return redirect()->back()->with('error', __('No data found.'));
               }
          }
          catch (Exception $ex)
          {
               DB::rollback();
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
          catch (Throwable $ex)
          {
               DB::rollback();
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
     }
}
