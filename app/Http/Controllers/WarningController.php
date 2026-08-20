<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use Carbon\Carbon;
use App\Models\Utility;
use App\Models\Warning;
use App\Models\Employee;
use App\Mail\WarningSend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WarningController extends Controller
{
    public function index()
    {
        if (Auth::user()->can('Manage Warning')) {
            if (Auth::user()->type == 'employee') {
                $emp      = Employee::where('user_id', '=', Auth::user()->id)->first();
                $warnings = Warning::where('warning_to', '=', $emp->id)->get();
            } else {
                $warnings = Warning::where('created_by', '=', Auth::user()->creatorId())->get();
            }

            return view('warning.index', compact('warnings'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (Auth::user()->can('Create Warning')) {
            $user             = Auth::user();
            if (Auth::user()->type == 'employee') {
                $current_employee = Employee::where('user_id', $user->id)->get()->pluck('name', 'id');
                $employees        = Employee::where('user_id', '!=', $user->id)->get()->pluck('name', 'id');
            } else {
                if (Auth::user()->type == 'company') {
                    $current_employee = Employee::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
                    $employees        = Employee::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
                } else {

                    $current_employee = Employee::whereIn('employee_id', selectedEmployee())->where('user_id', $user->id)->get()->pluck('name', 'id');
                    $employees        = Employee::whereIn('employee_id', selectedEmployee())->where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
                }
            }

            return view('warning.create', compact('employees', 'current_employee'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Warning')) {
            if (Auth::user()->type != 'employee') {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'warning_by' => 'required',
                        'warning_to' => 'required',
                        'subject' => 'required',
                        'description' => 'required',
                    ]
                );
            }

            if (Auth::user()->type == 'employee') {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'warning_to' => 'required',
                        'subject' => 'required',
                        'description' => 'required',
                    ]
                );
            }

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return response()->json(['status' => 0, 'message' => $messages->getMessages()]);
            }

            $warning = new Warning();
            if (Auth::user()->type == 'employee') {
                $emp                 = Employee::where('user_id', '=', Auth::user()->id)->first();
                $warning->warning_by = $emp->id;
            } else {
                $warning->warning_by = $request->warning_by;
            }
            $warning->warning_to   = $request->warning_to;
            $warning->subject      = $request->subject;
            $warning->warning_date = Carbon::now()->format('Y-m-d');
            $warning->description  = $request->description;
            $warning->created_by   = Auth::user()->creatorId();
            $warning->main_created_by   = Auth::id();
            $warning->save();

            $setings = Utility::settings();

            if ($setings['employee_warning'] == 1) {
                $employee       = Employee::find($warning->warning_to);
                $uArr = [
                    'employee_warning_name' => $employee->name,
                    'warning_subject' => $request->subject,
                    'warning_description' => $request->description,


                ];
                $resp = Utility::sendEmailTemplate('employee_warning', [$employee->email], $uArr);

                return response()->json(['status' => 1, 'message' => __('Warning  successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : '')]);
            }
            return response()->json(['status' => 1, 'message' => __('Warning  successfully created.')]);
        } else {
            return response()->json(['status' => 0, 'message' =>  __('Permission denied.')]);
        }
    }

    public function show(Warning $warning)
    {
        return redirect()->route('warning.index');
    }

    public function edit(Warning $warning)
    {

        if (Auth::user()->can('Edit Warning')) {
            if (Auth::user()->type == 'employee') {
                $user             = Auth::user();
                $current_employee = Employee::where('user_id', $user->id)->get()->pluck('name', 'id');
                $employees        = Employee::where('user_id', '!=', $user->id)->get()->pluck('name', 'id');
            } else {
                $user             = Auth::user();
                $current_employee = Employee::where('user_id', $user->id)->get()->pluck('name', 'id');
                $employees        = Employee::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            }
            if ($warning->created_by == Auth::user()->creatorId()) {
                return view('warning.edit', compact('warning', 'employees', 'current_employee'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Warning $warning)
    {
        if (Auth::user()->can('Edit Warning')) {
            if ($warning->created_by == Auth::user()->creatorId()) {
                if (Auth::user()->type != 'employee') {
                    $validator = Validator::make(
                        $request->all(),
                        [
                            'warning_by' => 'required',
                            'warning_to' => 'required',
                            'subject' => 'required',
                            'description' => 'required',
                        ]
                    );
                }

                if (Auth::user()->type == 'employee') {
                    $validator = Validator::make(
                        $request->all(),
                        [
                            'warning_to' => 'required',
                            'subject' => 'required',
                            'description' => 'required',
                        ]
                    );
                }

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return response()->json(['status' => 0, 'message' => $messages->getMessages()]);
                }

                DB::beginTransaction();
                try {
                    if (Auth::user()->type == 'employee') {
                        $emp                 = Employee::where('user_id', '=', Auth::user()->id)->first();
                        $warning->warning_by = $emp->id;
                    } else {
                        $warning->warning_by = $request->warning_by;
                    }

                    $warning->warning_to   = $request->warning_to;
                    $warning->subject      = $request->subject;
                    $warning->warning_date = Carbon::now()->format('Y-m-d');
                    $warning->description  = $request->description;
                    $warning->save();

                    DB::commit();
                    return response()->json(['status' => 1, 'message' => __('Warning successfully updated.')]);
                } catch (Exception $e) {
                    DB::rollBack();
                    return response()->json(['status' => 0, 'message' => showExceptionMessage($e)]);
                } catch (Throwable $e) {
                    DB::rollBack();
                    return response()->json(['status' => 0, 'message' => showExceptionMessage($e)]);
                }
            } else {
                return response()->json(['status' => 0, 'message' =>  __('Permission denied.')]);
            }
        } else {
            return response()->json(['status' => 0, 'message' =>  __('Permission denied.')]);
        }
    }

    public function destroy(Warning $warning)
    {
        if (Auth::user()->can('Delete Warning')) {
            if ($warning->created_by == Auth::user()->creatorId()) {
                $warning->delete();

                return redirect()->route('warning.index')->with('success', __('Warning successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
