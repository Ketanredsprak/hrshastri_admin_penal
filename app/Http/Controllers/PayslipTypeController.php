<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PayslipType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use DataTables;

class PayslipTypeController extends Controller
{
     public function index(Request $request)
     {
          if (Auth::user()->can('Manage Payslip Type'))
          {
               $paysliptypes = PayslipType::where('created_by', Auth::user()->creatorId());
               if ($request->ajax())
               {
                    return DataTables::of($paysliptypes)
                         ->addIndexColumn()
                         ->addColumn('action', function ($row)
                         {
                              $action = '';
                              if (Auth::user()->can('Edit Payslip Type'))
                              {
                                   $editUrl = route('paysliptype.edit', $row->id);
                                   $action .= '
                                        <div class="action-btn bg-info ms-2">
                                             <a href="#" class="mx-3 btn btn-sm align-items-center"
                                                  data-url="' . $editUrl . '" data-ajax-popup="true" data-size="md"
                                                  data-bs-toggle="tooltip" data-title="' . __('Edit Pay Slab Type') . '"
                                                  data-bs-original-title="' . __('Edit') . '">
                                                  <i class="ti ti-pencil text-white"></i>
                                             </a>
                                        </div>';
                              }

                              if (Auth::user()->can('Delete Payslip Type'))
                              {
                                   $deleteUrl = route('paysliptype.destroy', $row->id);
                                   $action .= '
                                        <div class="action-btn bg-danger ms-2">
                                             <form method="POST" action="' . $deleteUrl . '" id="delete-form-' . $row->id . '">
                                                  ' . method_field('DELETE') . csrf_field() . '
                                                  <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para"
                                                       data-bs-toggle="tooltip" title="' . __('Delete') . '" aria-label="Delete">
                                                       <i class="ti ti-trash text-white"></i>
                                                  </a>
                                             </form>
                                        </div>';
                              }
                              return $action;
                         })
                         ->rawColumns(['action'])
                         ->make(true);
               }
               else
               {
                    $paysliptypes = $paysliptypes->get();
                    return view('paysliptype.index', compact('paysliptypes'));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function create()
     {
          if (\Auth::user()->can('Create Payslip Type'))
          {
               return view('paysliptype.create');
          }
          else
          {
               return response()->json(['error' => __('Permission denied.')], 401);
          }
     }

     public function store(Request $request)
     {

          if (\Auth::user()->can('Create Payslip Type'))
          {

               $validator = \Validator::make(
                    $request->all(),
                    [
                         'name' => 'required|max:20',
                    ]
               );
               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
               }
               $paysliptype             = new PayslipType();
               $paysliptype->name       = $request->name;
               $paysliptype->created_by = \Auth::user()->creatorId();
               $paysliptype->save();

               return redirect()->route('paysliptype.index')->with('success', __('Pay Slab successfully created.'));
          }
          else
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }
     }

     public function show(PayslipType $paysliptype)
     {
          return redirect()->route('paysliptype.index');
     }

     public function edit(PayslipType $paysliptype)
     {
          if (\Auth::user()->can('Edit Payslip Type'))
          {
               if ($paysliptype->created_by == \Auth::user()->creatorId())
               {

                    return view('paysliptype.edit', compact('paysliptype'));
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

     public function update(Request $request, PayslipType $paysliptype)
     {
          if (\Auth::user()->can('Edit Payslip Type'))
          {
               if ($paysliptype->created_by == \Auth::user()->creatorId())
               {
                    $validator = \Validator::make(
                         $request->all(),
                         [
                              'name' => 'required|max:20',

                         ]
                    );

                    if ($validator->fails())
                    {
                         $messages = $validator->getMessageBag();

                         return redirect()->back()->with('error', $messages->first());
                    }

                    $paysliptype->name = $request->name;
                    $paysliptype->save();

                    return redirect()->route('paysliptype.index')->with('success', __('Pay Slab successfully updated.'));
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

     public function destroy(PayslipType $paysliptype)
     {
          if (\Auth::user()->can('Delete Payslip Type'))
          {
               if ($paysliptype->created_by == \Auth::user()->creatorId())
               {
                    $employee     = Employee::where('salary_type', $paysliptype->id)->get();
                    if (count($employee) == 0)
                    {
                         $paysliptype->delete();
                    }
                    else
                    {
                         return redirect()->route('paysliptype.index')->with('error', __('This Payslip Type has Set Salary. Please remove the Set Salary from this Payslip Type.'));
                    }

                    return redirect()->route('paysliptype.index')->with('success', __('Pay Slab successfully deleted.'));
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
