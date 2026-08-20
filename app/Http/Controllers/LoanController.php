<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Loan;
use App\Models\LoanInstallments;
use App\Models\LoanOption;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class LoanController extends Controller
{
     // public function loanCreate($id)
     // {
     //     $employee = Employee::find($id);
     //     $loan_options      = LoanOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
     //     $loan =loan::$Loantypes;
     //     return view('loan.create', compact('employee','loan_options','loan'));
     // }

     public function loanCreate()
     {
          $employee = Employee::where('created_by', \Auth::user()->creatorId())->get();
          $loan_options      = LoanOption::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
          $loan = loan::$Loantypes;
          return view('loan.create', compact('employee', 'loan_options', 'loan'));
     }


     public function index()
     {
          $loans = loan::where('created_by', \Auth::user()->creatorId())->orderBy("id", "DESC")->get();
          return view('loan.index', compact('loans'));
     }

     public function store(Request $request)
     {
          if (!Auth::user()->can('Create Loan'))
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }

          $validator = Validator::make(
               $request->all(),
               [
                    'employee_id' => 'required',
                    'loan_option' => 'required',
                    'title' => 'required',
                    'amount' =>  ['required', 'numeric', 'min:1'],
                    'installments' =>  ['required', 'numeric', 'min:1'],
                    'start_date' => 'required|after_or_equal:today',
                    'reason' => 'required',
               ]
          );
          if ($validator->fails())
          {
               $messages = $validator->getMessageBag();
               return redirect()->back()->with('error', $messages->first());
          }

          try
          {
               $amount = $request->amount;
               $loan_installments = $request->installments;
               $loan_status = 0;

               $loadEndDate = Carbon::parse($request->start_date)->addMonths($loan_installments);
               // dd($request->start_date, $loan_installments, $loadEndDate->format("Y-m-d"));

               $loan = new Loan();
               $loan->employee_id = $request->employee_id;
               $loan->loan_option = $request->loan_option;
               $loan->title = $request->title;
               $loan->amount = $amount;
               $loan->loan_installments = $loan_installments; // number of loan installments
               $loan->loan_status = $loan_status; // 0 = loan not started, 1 = loan started, 2 completed
               $loan->type = 0;
               $loan->start_date = $request->start_date ?? date("Y-m-d");
               $loan->end_date = $loadEndDate->format("Y-m-d");
               $loan->reason = $request->reason;
               $loan->created_by = Auth::user()->creatorId();
               $loan->save();

               $eachInstallmentAmount = ($amount / $loan_installments);
               for ($i = 1; $i <= $loan_installments; $i++)
               {
                    $installment_amount = Carbon::parse($request->start_date)->addMonths($i);

                    $installmentMonth = $installment_amount->startOfMonth()->format("Y-m-d");

                    $loanInstallments = new LoanInstallments();

                    $loanInstallments->loans_id = $loan->id;
                    $loanInstallments->installment_date = $installmentMonth;
                    $loanInstallments->installment_amount = $eachInstallmentAmount;
                    $loanInstallments->installment_status = 0; //0 = not paid, 1 = paid
                    $loanInstallments->created_by = $loan->created_by;

                    $loanInstallments->save();
               }

               return redirect()->back()->with('success', __('Loan successfully created.'));
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

     public function show(Loan $loan)
     {
          try
          {
               // $loan = Loan::find($loan);

               // dd($loan->created_by);
               if ($loan->created_by == Auth::user()->creatorId())
               {
                    $loan_options = LoanOption::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');

                    $employee = Employee::where('id', $loan->employee_id)->first();

                    $checkLoadStarted = false;
                    if (date("Y-m-d") > date("Y-m-d", strtotime($loan->start_date)) && $loan->loan_status == 1) //0 = loan not started, 1 = loan started, 2 completed
                    {
                         $checkLoadStarted = true;
                    }
                    // $checkLoadStarted = true;

                    $loanInstallments = LoanInstallments::where("loans_id", $loan->id)->get();
                    $loan_options_text = LoanOption::where("id", $loan->loan_option)->first()->name ?? "";

                    // 0 = loan not started, 1 = loan started, 2 completed
                    $loanStatusArray = [
                         0 => __("Loan not started"),
                         1 => __("Loan started"),
                         2 => __("Loan Completed"),
                    ];

                    $loan_status_text = $loanStatusArray[$loan->loan_status];

                    return view('loan.show', compact('loan', 'loan_options', 'employee', 'checkLoadStarted', 'loanInstallments', 'loan_options_text', 'loan_status_text'));
               }
               else
               {
                    return response()->json(['error' => __('Permission denied.')], 401);
               }
          }
          catch (Exception $ex)
          {
               dd($ex);
               return response()->json(['error' => $ex], 500);
          }
          catch (Throwable $ex)
          {
               dd($ex);
               return response()->json(['error' => $ex], 500);
          }
     }

     public function edit($loan)
     {
          try
          {
               $loan = Loan::find($loan);
               if (Auth::user()->can('Edit Loan'))
               {
                    if ($loan->created_by == Auth::user()->creatorId())
                    {
                         $loan_options = LoanOption::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');

                         $employee = Employee::where('created_by', Auth::user()->creatorId())->get();

                         $checkLoadStarted = false;
                         if (date("Y-m-d") > date("Y-m-d", strtotime($loan->start_date)) && $loan->loan_status == 1) //0 = loan not started, 1 = loan started, 2 completed
                         {
                              $checkLoadStarted = true;
                         }
                         // $checkLoadStarted = true;

                         return view('loan.edit', compact('loan', 'loan_options', 'employee', 'checkLoadStarted'));
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
          catch (Exception $ex)
          {
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
          catch (Throwable $ex)
          {
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
     }

     public function update(Request $request, Loan $loan)
     {
          if (!Auth::user()->can('Edit Loan'))
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }

          if (!Auth::user()->can('Create Loan'))
          {
               return redirect()->back()->with('error', __('Permission denied.'));
          }

          // $loan = Loan::where("id", $id)->first();
          if ($loan === null)
          {
               return redirect()->back()->with('error', __('No Data Found.'));
          }

          $checkLoadStarted = false;
          if (date("Y-m-d") > date("Y-m-d", strtotime($loan->start_date)) && $loan->loan_status == 1) //0 = loan not started, 1 = loan started, 2 completed
          {
               $checkLoadStarted = true;
          }

          if ($checkLoadStarted) // loadn started
          {
               $validator = Validator::make(
                    $request->all(),
                    [
                         // 'employee_id' => 'required',
                         // 'loan_option' => 'required',
                         'title' => 'required',
                         // 'amount' =>  ['required', 'numeric', 'min:1'],
                         // 'installments' =>  ['required', 'numeric', 'min:1'],
                         // 'start_date' => 'required|after_or_equal:today',
                         'reason' => 'required',
                    ]
               );
               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
               }
          }
          else
          {
               $validator = Validator::make(
                    $request->all(),
                    [
                         'employee_id' => 'required',
                         'loan_option' => 'required',
                         'title' => 'required',
                         'amount' =>  ['required', 'numeric', 'min:1'],
                         'installments' =>  ['required', 'numeric', 'min:1'],
                         'start_date' => 'required|after_or_equal:today',
                         'reason' => 'required',
                    ]
               );
               if ($validator->fails())
               {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
               }
          }

          try
          {
               if ($checkLoadStarted) // loadn started
               {
                    $loan->loan_option = $request->loan_option;
                    $loan->title = $request->title;
                    $loan->reason = $request->reason;
                    $loan->save();
               }
               else
               {
                    $amount = $request->amount;
                    $loan_installments = $request->installments;
                    $loan_status = 0;

                    $loadEndDate = Carbon::parse($request->start_date)->addMonths($loan_installments);
                    // dd($request->start_date, $loan_installments, $loadEndDate->format("Y-m-d"));


                    $loan->employee_id = $request->employee_id;
                    $loan->loan_option = $request->loan_option;
                    $loan->title = $request->title;
                    $loan->amount = $amount;
                    $loan->loan_installments = $loan_installments; // number of loan installments
                    $loan->loan_status = $loan_status; // 0 = loan not started, 1 = loan started, 2 completed
                    $loan->type = 0;
                    $loan->start_date = $request->start_date ?? date("Y-m-d");
                    $loan->end_date = $loadEndDate->format("Y-m-d");
                    $loan->reason = $request->reason;
                    $loan->created_by = Auth::user()->creatorId();
                    $loan->save();

                    LoanInstallments::where("loans_id", $loan->id)->delete(); // if loan is not started then delete old installments data and create new

                    $eachInstallmentAmount = ($amount / $loan_installments);
                    for ($i = 1; $i <= $loan_installments; $i++)
                    {
                         $installment_amount = Carbon::parse($request->start_date)->addMonths($i);

                         $installmentMonth = $installment_amount->startOfMonth()->format("Y-m-d");

                         $loanInstallments = new LoanInstallments();

                         $loanInstallments->loans_id = $loan->id;
                         $loanInstallments->installment_date = $installmentMonth;
                         $loanInstallments->installment_amount = $eachInstallmentAmount;
                         $loanInstallments->installment_status = 0; //0 = not paid, 1 = paid
                         $loanInstallments->created_by = $loan->created_by;

                         $loanInstallments->save();
                    }
               }

               return redirect()->back()->with('success', __('Loan successfully updated.'));
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

     public function destroy(Loan $loan)
     {
          if (\Auth::user()->can('Delete Loan'))
          {
               if ($loan->created_by == \Auth::user()->creatorId())
               {
                    LoanInstallments::where("loans_id", $loan->id)->delete();
                    $loan->delete();

                    return redirect()->back()->with('success', __('Loan successfully deleted.'));
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
