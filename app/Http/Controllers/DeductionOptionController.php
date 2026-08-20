<?php

namespace App\Http\Controllers;

use App\Models\DeductionOption;
use App\Models\SaturationDeduction;
use App\Models\PayslipType;
use Illuminate\Http\Request;
use App\Models\Document;

class DeductionOptionController extends Controller
{
    public function index()
    {

        

        if(\Auth::user()->can('Manage Deduction Option'))
        {
            $deductionoptions = DeductionOption::with('playslab')->where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'DESC')->get();
            return view('deductionoption.index', compact('deductionoptions'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('Create Deduction Option'))
        {
            $data = PayslipType::where('created_by', '=', \Auth::user()->creatorId())->get();
            return view('deductionoption.create',compact('data'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('Create Deduction Option'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'play_slab' => 'required',
                    'type' => 'required',
                    'amount' => 'required',
                    'name' => 'required',
                    'short_name' => 'required',
                    'employees_id' => 'required',
                ],[
                    'employees_id' =>  'Employee field is required',
                ]
                
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $deductionoption             = new DeductionOption();
            $deductionoption->name       = $request->name;
            $deductionoption->short_name       = $request->short_name;
            $deductionoption->play_slab_id = $request->play_slab;
            $deductionoption->type   = $request->type;
            $deductionoption->employees_id = $request->employees_id;
            $deductionoption->amount  = $request->amount;

            $deductionoption->is_display                     = $request->is_display;
            $deductionoption->is_display_slary_slip          = $request->is_display_slary_slip;

            $deductionoption->created_by = \Auth::user()->creatorId();
            $deductionoption->save();

            return redirect()->route('deductionoption.index')->with('success', __('DeductionOption  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(DeductionOption $deductionoption)
    {
        return redirect()->route('deductionoption.index');
    }

    public function edit($deductionoption)
    {
        $deductionoption = DeductionOption::find($deductionoption);
        if(\Auth::user()->can('Edit Deduction Option'))
        {
            if($deductionoption->created_by == \Auth::user()->creatorId())
            {
                $data = PayslipType::where('created_by', '=', \Auth::user()->creatorId())->get();
                return view('deductionoption.edit', compact('deductionoption','data'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, DeductionOption $deductionoption)
    {
        if(\Auth::user()->can('Edit Deduction Option'))
        {
            if($deductionoption->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                        'play_slab' => 'required',
                        'type' => 'required',
                        'amount' => 'required',
                        'name' => 'required',
                        'short_name' => 'required',
                        'employees_id' => 'required',
                    ],[
                        'employees_id' =>  'Employee field is required',
                    ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $deductionoption->name = $request->name;
                $deductionoption->short_name       = $request->short_name;
                $deductionoption->play_slab_id = $request->play_slab;
                $deductionoption->type   = $request->type;
                $deductionoption->employees_id = $request->employees_id;
                $deductionoption->amount  = $request->amount;

                $deductionoption->is_display  = $request->is_display;
                $deductionoption->is_display_slary_slip = $request->is_display_slary_slip;
                if($request->is_display == 'No'){
                    $deductionoption->is_display_slary_slip = 0;
                }

                $deductionoption->save();

                return redirect()->route('deductionoption.index')->with('success', __('DeductionOption successfully updated.'));
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

    public function destroy(DeductionOption $deductionoption)
    {
        if(\Auth::user()->can('Delete Deduction Option'))
        {
            if($deductionoption->created_by == \Auth::user()->creatorId())
            {
                $saturationdeduction  = SaturationDeduction::where('deduction_option',$deductionoption->id)->get();
                if(count($saturationdeduction) == 0)
                {
                    $deductionoption->delete();
                }
                else
                {
                    return redirect()->route('deductionoption.index')->with('error', __('This Deduction Option has Saturation Deduction. Please remove the Saturation Deduction from this Deduction option.'));
                }

                return redirect()->route('deductionoption.index')->with('success', __('DeductionOption successfully deleted.'));
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
}
