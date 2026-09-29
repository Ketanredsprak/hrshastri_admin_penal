<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\Utility;
use App\Models\Employee;
use App\Models\Termination;
use Illuminate\Http\Request;
use App\Mail\TerminationSend;
use App\Models\TerminationType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class TerminationController extends Controller
{
     public function index()
     {
          if (Auth::user()->can('Manage Termination'))
          {
               if (Auth::user()->type == 'company')
               {
                    $terminations = Termination::where('created_by', '=', Auth::user()->creatorId())->get();
               }
               else if (Auth::user()->type == 'employee')
               {
                    $emp          = Employee::where('user_id', '=', Auth::user()->id)->first();
                    $terminations = Termination::where('created_by', '=', Auth::user()->creatorId())->where('employee_id', '=', $emp->id)->get();
               }
               else
               {
                    $terminations = Termination::where('created_by', '=', Auth::user()->creatorId())->where('main_created_by', '=', Auth::id())->get();
               }
               return view('termination.index', compact('terminations'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function create()
     {
          if (Auth::user()->can('Create Termination'))
          {
               if (Auth::user()->type == 'company')
               {
                    $employees        = Employee::where('created_by', Auth::user()->creatorId())->where("employee_exit", 0)->get()->pluck('name', 'id');
                    $terminationtypes = TerminationType::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
               }
               else
               {
                    $employees        = Employee::whereIn('id', selectedEmployee())->where('created_by', Auth::user()->creatorId())->where("employee_exit", 0)->get()->pluck('name', 'id');
                    $terminationtypes = TerminationType::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
               }
               $employees->prepend('Select employee', '');
               $terminationtypes->prepend('Select termination type', '');
               return view('termination.create', compact('employees', 'terminationtypes'));
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function store(Request $request)
     {

          if (Auth::user()->can('Create Termination'))
          {
               $validator = Validator::make(
                    $request->all(),
                    [
                         'employee_id' => 'required',
                         'termination_type' => 'required',
                         'notice_date' => 'required',
                         'termination_date' => 'required|after_or_equal:notice_date',
                    ],
                    [
                         'employee_id.required' => "The employee field is required."
                    ]
               );

               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
               }
               DB::beginTransaction();
               try
               {
                    $termination                   = new Termination();
                    $termination->employee_id      = $request->employee_id;
                    $termination->termination_type = $request->termination_type;
                    $termination->notice_date      = $request->notice_date;
                    $termination->termination_date = $request->termination_date;
                    $termination->description      = $request->description;
                    $termination->created_by       = Auth::user()->creatorId();
                    $termination->main_created_by  = Auth::id();
                    $termination->save();

                    $employee = Employee::find($request->employee_id);
                    if ($employee)
                    {
                         $employee->employee_exit = 1;
                         $employee->employe_status = 'Terminated';
                         $employee->save();
                    }

                    DB::commit();

                    $setings = Utility::settings();
                    if ($setings['employee_termination'] == 1)
                    {
                         $employee = Employee::find($termination->employee_id);

                         $uArr = [
                              'employee_termination_name' => $employee->name,
                              'notice_date' => $request->notice_date,
                              'termination_date' => $request->termination_date,
                              'termination_type' => $request->termination_type,
                         ];

                         $resp = Utility::sendEmailTemplate('employee_termination', [$employee->email], $uArr);

                         return redirect()->route('termination.index')->with('success', __('Termination  successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                    }

                    return redirect()->route('termination.index')->with('success', __('Termination successfully created.'));
               }
               catch (Exception $e)
               {
                    DB::rollBack();
                    return redirect()->route('termination.index')->with('error', showExceptionMessage($e));
               }
               catch (Throwable $e)
               {
                    DB::rollBack();
                    return redirect()->route('termination.index')->with('error', showExceptionMessage($e));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function show(Termination $termination)
     {
          return redirect()->route('termination.index');
     }

     public function edit(Termination $termination)
     {
          if (Auth::user()->can('Edit Termination'))
          {
               if (Auth::user()->type == 'company')
               {
                    $employees        = Employee::where('created_by', Auth::user()->creatorId())->where("employee_exit", 0)->get()->pluck('name', 'id');
                    $terminationtypes = TerminationType::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
               }
               else
               {
                    $employees        = Employee::whereIn('id', selectedEmployee())->where('created_by', Auth::user()->creatorId())->where("employee_exit", 0)->get()->pluck('name', 'id');
                    $terminationtypes = TerminationType::where('created_by', '=', Auth::user()->creatorId())->get()->pluck('name', 'id');
               }
               $employees->prepend('Select employee', '');
               $terminationtypes->prepend('Select termination type', '');
               return view('termination.edit', compact('termination', 'employees', 'terminationtypes'));
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function update(Request $request, Termination $termination)
     {
          if (Auth::user()->can('Edit Termination'))
          {
               if ($termination->created_by == Auth::user()->creatorId())
               {
                    $validator = Validator::make(
                         $request->all(),
                         [
                              'employee_id' => 'required',
                              'termination_type' => 'required',
                              'notice_date' => 'required',
                              'termination_date' => 'required',
                         ]
                    );

                    if ($validator->fails())
                    {
                         $messages = $validator->getMessageBag();
                         return redirect()->back()->with('error', $messages->first());
                    }


                    DB::beginTransaction();
                    try
                    {
                         $termination->employee_id      = $request->employee_id;
                         $termination->termination_type = $request->termination_type;
                         $termination->notice_date      = $request->notice_date;
                         $termination->termination_date = $request->termination_date;
                         $termination->description      = $request->description;
                         $termination->save();

                         DB::commit();
                         return redirect()->route('termination.index')->with('success', __('Termination successfully updated.'));
                    }
                    catch (Exception $e)
                    {
                         DB::rollBack();
                         return redirect()->route('termination.index')->with('success', $e->getMessage());
                    }
                    catch (Throwable $e)
                    {
                         DB::rollBack();
                         return redirect()->route('termination.index')->with('success', $e->getMessage());
                    }
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function destroy(Termination $termination)
     {
          if (Auth::user()->can('Delete Termination'))
          {
               if ($termination->created_by == Auth::user()->creatorId())
               {
                    DB::beginTransaction();
                    try
                    {

                         $termination->delete();
                         DB::commit();
                         return redirect()->route('termination.index')->with('success', __('Termination successfully deleted.'));
                    }
                    catch (Exception $e)
                    {
                         DB::rollBack();
                         return redirect()->route('termination.index')->with('success', $e->getMessage());
                    }
                    catch (Throwable $e)
                    {
                         DB::rollBack();
                         return redirect()->route('termination.index')->with('success', $e->getMessage());
                    }
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function description($id)
     {

          $termination = Termination::find($id);

          return view('termination.description', compact('termination'));
     }
}
