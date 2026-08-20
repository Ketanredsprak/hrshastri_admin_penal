<?php

namespace App\Http\Controllers;

use App\Models\PayslipType;
use Illuminate\Http\Request;
use App\Models\AllowanceOption;
use App\Models\OtherPaymentotion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OtherPaymentOptionController extends Controller
{
     public function index()
     {
          if (Auth::user()->can('Manage Allowance Option'))
          {
               $OtherPaymentotion = OtherPaymentotion::with('playslab')->where('created_by', '=', Auth::user()->creatorId())->get();
               return view('otherpaymentoption.index', compact('OtherPaymentotion'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function create()
     {
          if (Auth::user()->can('Create Allowance Option'))
          {
               $data = PayslipType::where('created_by', '=', Auth::user()->creatorId())->get();
               return view('otherpaymentoption.create', compact('data'));
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }


     public function store(Request $request)
     {
          if (Auth::user()->can('Create Allowance Option'))
          {
               $validator = Validator::make(
                    $request->all(),
                    [
                         'play_slab' => 'required',
                         'name' => 'required',
                         'employees_id' => 'required',
                    ],
                    [
                         'employees_id' =>  'Employee field is required',
                    ]
               );
               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
               }
               $data = new OtherPaymentotion();
               $data->name = $request->name;
               $data->play_slab_id = $request->play_slab;
               $data->type = $request->type;
               $data->employees_id = $request->employees_id;
               $data->amount = $request->amount;
               $data->created_by = Auth::user()->creatorId();
               $data->save();
               return redirect()->route('otherpaymentotion.index')->with('success', __('Other payment Option  successfully created.'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function show($id)
     {
          //
     }

     public function edit($id)
     {
          $otherdata = OtherPaymentotion::find($id);
          if ($otherdata->created_by == Auth::user()->creatorId())
          {
               $data = PayslipType::where('created_by', '=', Auth::user()->creatorId())->get();
               return view('otherpaymentoption.edit', compact('otherdata', 'data'));
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function update(Request $request, $id)
     {
          $otherdata = OtherPaymentotion::find($id);
          if (Auth::user()->can('Edit Allowance Option'))
          {
               if ($otherdata->created_by == Auth::user()->creatorId())
               {
                    $validator = Validator::make(
                         $request->all(),
                         [
                              'play_slab' => 'required',
                              // 'name' => 'required|max:20',
                              'name' => 'required',
                              'employees_id' => 'required',
                         ],
                         [
                              'employees_id' =>  'Employee field is required',
                         ]
                    );
                    if ($validator->fails())
                    {
                         $messages = $validator->getMessageBag();
                         return redirect()->back()->with('error', $messages->first());
                    }
                    $otherdata->name = $request->name;
                    $otherdata->play_slab_id  = $request->play_slab;
                    $otherdata->type   = $request->type;
                    $otherdata->employees_id = $request->employees_id;
                    $otherdata->amount  = $request->amount;
                    $otherdata->save();
                    return redirect()->route('otherpaymentotion.index')->with('success', __('Other payment successfully updated.'));
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
     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
          $otherdata = OtherPaymentotion::find($id);
          if (Auth::user()->can('Delete Allowance Option'))
          {
               if ($otherdata->created_by == Auth::user()->creatorId())
               {
                    OtherPaymentotion::where('id', $id)->delete();
                    return redirect()->route('otherpaymentotion.index')->with('success', __('Other payment successfully deleted.'));
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
