<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use App\Models\AllowanceOption;
use App\Models\PayslipType;
use Illuminate\Http\Request;

class AllowanceOptionController extends Controller
{
     public function index()
     {
          if (\Auth::user()->can('Manage Allowance Option'))
          {
               $allowanceoptions = AllowanceOption::with('playslab')->where('created_by', '=', \Auth::user()->creatorId())->orderBy('id', 'DESC')->get();
               return view('allowanceoption.index', compact('allowanceoptions'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function create()
     {
          if (\Auth::user()->can('Create Allowance Option'))
          {
               $data = PayslipType::where('created_by', '=', \Auth::user()->creatorId())->get();
               return view('allowanceoption.create', compact('data'));
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function store(Request $request)
     {

          if (\Auth::user()->can('Create Allowance Option'))
          {

               $validator = \Validator::make(
                    $request->all(),
                    [
                         'play_slab' => 'required',
                         'type' => 'required',
                         'name' => 'required|max:20',
                         'short_name' => 'required|max:20',
                    ]
               );

               if ($request->type == 'percentage')
               {
                    $validator = \Validator::make(
                         $request->all(),
                         [
                              'play_slab' => 'required',
                              'hra_types_percentage' => 'required',
                              //     'hra_types' => 'required',
                         ],
                         [
                              'hra_types_percentage' => 'The percentage field is required.',
                              //     'hra_types' => 'The allowance field is required.'
                         ]
                    );
               }
               else
               {
                    $validator = \Validator::make(
                         $request->all(),
                         [
                              'amount' => 'required',
                         ]
                    );
               }

               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
               }

               $allowanceoption                = new AllowanceOption();
               $allowanceoption->name          = $request->name;
               $allowanceoption->short_name    = $request->short_name;
               $allowanceoption->play_slab_id  = $request->play_slab;
               $allowanceoption->type          = $request->type;

               $allowanceoption->is_display                     = $request->is_display;
               $allowanceoption->is_display_slary_slip          = $request->is_display_slary_slip;

               if ($request->type == 'percentage')
               {
                    $allowanceoption->amount    = $request->hra_types_percentage;
               }
               else
               {
                    $allowanceoption->amount        = $request->amount;
               }

               $allowanceoption->allowance_types  = ($request->hra_types ?? "");
               $allowanceoption->created_by    = \Auth::user()->creatorId();
               $allowanceoption->save();

               return redirect()->route('allowanceoption.index')->with('success', __('Allowance successfully created.'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function show(AllowanceOption $allowanceoption)
     {
          return redirect()->route('allowanceoption.index');
     }

     public function edit(AllowanceOption $allowanceoption)
     {

          if (\Auth::user()->can('Edit Allowance Option'))
          {
               if ($allowanceoption->created_by == \Auth::user()->creatorId())
               {

                    $data = PayslipType::where('created_by', '=', \Auth::user()->creatorId())->get();
                    return view('allowanceoption.edit', compact('allowanceoption', 'data'));
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

     public function update(Request $request, AllowanceOption $allowanceoption)
     {
          if (\Auth::user()->can('Edit Allowance Option'))
          {
               if ($allowanceoption->created_by == \Auth::user()->creatorId())
               {
                    $validator = \Validator::make(
                         $request->all(),
                         [
                              'play_slab' => 'required',
                              'type' => 'required',
                              'amount' => 'required',
                              'name' => 'required|max:20',
                              'short_name'    => 'required|max:20',
                         ]
                    );

                    if ($request->type == 'percentage')
                    {
                         $validator = \Validator::make(
                              $request->all(),
                              [
                                   'play_slab' => 'required',
                                   'hra_types_percentage' => 'required',
                                   // 'hra_types' => 'required',
                              ],
                              [
                                   'hra_types_percentage' => 'The percentage field is required.',
                                   // 'hra_types' => 'The allowance field is required.'
                              ]
                         );
                    }
                    else
                    {
                         $validator = \Validator::make(
                              $request->all(),
                              [
                                   'amount' => 'required',
                              ]
                         );
                    }


                    if ($validator->fails())
                    {
                         $messages = $validator->getMessageBag();

                         return redirect()->back()->with('error', $messages->first());
                    }


                    $allowanceoption->name             = $request->name;
                    $allowanceoption->short_name       = $request->short_name;
                    $allowanceoption->play_slab_id     = $request->play_slab;
                    $allowanceoption->type             = $request->type;
                    $allowanceoption->is_display       = $request->is_display;

                    $allowanceoption->is_display_slary_slip = $request->is_display_slary_slip;
                    if ($request->is_display == 'No')
                    {
                         $allowanceoption->is_display_slary_slip = 0;
                    }
                    if ($request->type == 'percentage')
                    {
                         $allowanceoption->amount    = $request->hra_types_percentage;
                    }
                    else
                    {
                         $allowanceoption->amount  = $request->amount;
                    }
                    $allowanceoption->allowance_types  = ($request->hra_types ?? "");
                    // $allowanceoption->amount  = $request->amount;
                    $allowanceoption->save();

                    return redirect()->route('allowanceoption.index')->with('success', __('Allowance successfully updated.'));
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

     public function destroy(AllowanceOption $allowanceoption)
     {
          if (\Auth::user()->can('Delete Allowance Option'))
          {
               if ($allowanceoption->created_by == \Auth::user()->creatorId())
               {
                    $allowance     = Allowance::where('allowance_option', $allowanceoption->id)->get();
                    if (count($allowance) == 0)
                    {
                         $allowanceoption->delete();
                    }
                    else
                    {
                         return redirect()->route('allowanceoption.index')->with('error', __('This Allowance has Allowance. Please remove the Allowance from this Allowance option.'));
                    }

                    return redirect()->route('allowanceoption.index')->with('success', __('Allowance successfully deleted.'));
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

     public function getAllowance($id)
     {
          $AllowanceOptionData = AllowanceOption::where('created_by', '=', \Auth::user()->creatorId())->where('play_slab_id', $id)->get();
          return $AllowanceOptionData;
     }
}
