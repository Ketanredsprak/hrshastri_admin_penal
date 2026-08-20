<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Commission;
use App\Models\PayslipType;
use Illuminate\Http\Request;
use App\Models\AllowanceOption;
use App\Models\Commission_option;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Commission_optionController extends Controller
{
    public function getCommission($id)
    {
        $getCommission = Commission_option::where('created_by', '=', Auth::user()->creatorId())->where('play_slab_id', $id)->get();
        return $getCommission;
    }

    public function index()
    {
        $allowanceoptions = Commission_option::with('playslab')->where('created_by', '=', Auth::user()->creatorId())->orderBy('id', 'DESC')->get();
        return view('commission_option.index', compact('allowanceoptions'));
    }

    public function create()
    {
        $data = PayslipType::where('created_by', '=', Auth::user()->creatorId())->get();
        return view('commission_option.create', compact('data'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->can('Create Commission')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'play_slab' => 'required',
                    'name' => 'required',
                    'short_name'  => 'required',
                    'type' => 'required',
                    'employees_id' => 'required',

                ],
                [
                    'employees_id' =>  'Employee field is required',

                ]
            );


            if ($request->type == 'percentage') {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'hra_types_percentage' => 'required',
                        'hra_types' => 'required',
                    ],
                    [
                        'hra_types_percentage' => 'The percentage field is required.',
                        'hra_types' => 'The allowance field is required.'
                    ]
                );
            } else {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'amount' => 'required',
                    ]
                );
            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $data     = new Commission_option();

            $data->play_slab_id = $request->play_slab;
            $data->name       = $request->name;
            $data->short_name = $request->short_name;
            $data->type      = $request->type;

            if ($request->type == 'percentage') {
                $data->amount    = $request->hra_types_percentage;
            } else {
                $data->amount        = $request->amount;
            }
            $data->commision_types  = $request->hra_types;
            $data->employees_id = $request->employees_id;
            $data->created_by = Auth::user()->creatorId();
            $data->save();



            return redirect()->back()->with('success', __('Commission option successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Commission $commission)
    {
        return redirect()->route('commision.index');
    }

    public function edit(Commission_option $Commission_option)
    {
        if (Auth::user()->can('Edit Allowance Option')) {
            if ($Commission_option->created_by == Auth::user()->creatorId()) {

                $data = PayslipType::where('created_by', '=', Auth::user()->creatorId())->get();
                return view('commission_option.edit', compact('Commission_option', 'data'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Commission_option $Commission_option)
    {

        if (Auth::user()->can('Edit Allowance Option')) {
            if ($Commission_option->created_by == Auth::user()->creatorId()) {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'play_slab' => 'required',
                        'type' => 'required',
                        'short_name' => 'required',
                        'name' => 'required|max:20',
                        'employees_id' => 'required',
                    ],
                    [
                        'employees_id' =>  'Employee field is required',
                    ]
                );

                if ($request->type == 'percentage') {
                    $validator = Validator::make(
                        $request->all(),
                        [
                            'hra_types_percentage' => 'required',
                            'hra_types' => 'required',
                        ],
                        [
                            'hra_types_percentage' => 'The percentage field is required.',
                            'hra_types' => 'The allowance field is required.'
                        ]
                    );
                } else {
                    $validator = Validator::make(
                        $request->all(),
                        [
                            'amount' => 'required',
                        ]
                    );
                }


                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }
                $Commission_option->name = $request->name;
                $Commission_option->play_slab_id  = $request->play_slab;
                $Commission_option->type   = $request->type;
                $Commission_option->employees_id = $request->employees_id;

                if ($request->type == 'percentage') {
                    $Commission_option->amount    = $request->hra_types_percentage;
                    $Commission_option->commision_types  = $request->hra_types;
                } else {
                    $Commission_option->amount        = $request->amount;
                    $Commission_option->commision_types  = Null;
                }
                $Commission_option->short_name  = $request->short_name;
                $Commission_option->save();

                return redirect()->route('commission_option.index')->with('success', __('Commission option successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Commission_option $Commission_option)
    {
        if (Auth::user()->can('Delete Allowance Option')) {
            if ($Commission_option->created_by == Auth::user()->creatorId()) {
                Commission_option::where('id', $Commission_option->id)->delete();

                return redirect()->route('commission_option.index')->with('success', __('Commission_option successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
