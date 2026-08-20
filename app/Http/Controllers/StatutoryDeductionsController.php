<?php

namespace App\Http\Controllers;

use App\Models\AllowanceOption;
use App\Models\EmployeesStateInsurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use App\Models\StatutoryDeductionsEpf;
use App\Models\StatutoryDeductionsProfessionalTaxRange;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Throwable;

class StatutoryDeductionsController extends Controller
{
     public function index(Request $request)
     {
          try
          {

               $userId = Auth::user()->creatorId();
               $user = User::where("id", $userId)->first();


               if (Auth::user()->can('Create Saturation Deduction') || Auth::user()->can('Edit Saturation Deduction') || Auth::user()->can('Delete Saturation Deduction'))
               {
                    $statutoryDeductionsEpf = StatutoryDeductionsEpf::where('created_by', Auth::user()->creatorId())->first();

                    $professional_tax_status = $user->professional_tax_status;
                    $professional_tax_number = $user->professional_tax_number;

                    $statutoryDeductionsPt = StatutoryDeductionsProfessionalTaxRange::where('created_by', Auth::user()->creatorId())->get();

                    $employeesStateInsurance = EmployeesStateInsurance::where('created_by', Auth::user()->creatorId())->first();

                    $allowanceOption = AllowanceOption::where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get()->pluck('name', 'id')->prepend('Select an option', '');

                    // dd($allowanceOption);
                    return view('statutory-deductions.index',)
                         ->with('statutoryDeductionsEpf', $statutoryDeductionsEpf)
                         ->with('statutoryDeductionsPt', $statutoryDeductionsPt)
                         ->with('professional_tax_status', $professional_tax_status)
                         ->with('professional_tax_number', $professional_tax_number)
                         ->with('employeesStateInsurance', $employeesStateInsurance)
                         ->with('allowanceOption', $allowanceOption);
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          catch (Throwable $ex)
          {
               // dd($ex);
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
     }

     public function storeEpf(Request $request)
     {
          try
          {
               if (Auth::user()->can('Create Saturation Deduction'))
               {
                    $status = $request->has("status") ? 1 : 0;

                    $validationRules = [];
                    if ($status == 1)
                    {
                         $validationRules = [
                              'epf_number' => 'required',
                              'employee_contribution_rate' => 'nullable|numeric',
                              'employer_contribution_rate' => 'nullable|numeric',
                         ];
                    }
                    $validator = Validator::make(
                         $request->all(),
                         $validationRules
                    );

                    if ($validator->fails())
                    {
                         $messages = $validator->messages();
                         return redirect()->back()->with('error', $messages->first())->withInput();
                    }

                    DB::beginTransaction();

                    $statutoryDeductionsEpf = StatutoryDeductionsEpf::where('created_by', Auth::user()->creatorId())->first();
                    if ($statutoryDeductionsEpf == null)
                    {
                         $statutoryDeductionsEpf = new StatutoryDeductionsEpf();
                         $statutoryDeductionsEpf->created_by = Auth::user()->creatorId();
                    }

                    if ($status == 1)
                    {
                         $statutoryDeductionsEpf->epf_number = $request->epf_number;
                         $statutoryDeductionsEpf->employee_contribution_rate = $request->employee_contribution_rate ?? 0;
                         $statutoryDeductionsEpf->employer_contribution_rate = $request->employer_contribution_rate ?? 0;

                         $statutoryDeductionsEpf->override_employee_pf = $request->has("override_employee_pf") ? 1 : 0;
                         $statutoryDeductionsEpf->is_min_max_salary_limit = $request->has("is_min_max_salary_limit") ? 1 : 0;

                         $statutoryDeductionsEpf->epf_limit = $request->epf_limit ?? 0;

                         $epf_deductions_allowances_ids = json_encode($request->epf_deductions_allowances_ids ?? []);

                         $statutoryDeductionsEpf->epf_deductions_allowances_ids = $epf_deductions_allowances_ids;
                    }

                    $statutoryDeductionsEpf->status = $request->has("status") ? 1 : 0;

                    $statutoryDeductionsEpf->save();

                    DB::commit();

                    return redirect()->back()->with('success', __('EPF Details Updated Successfully.'));
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          catch (Throwable $ex)
          {
               DB::rollBack();
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
     }

     public function storeProfessionalTax(Request $request)
     {
          try
          {
               if (Auth::user()->can('Create Saturation Deduction'))
               {
                    $status = $request->has("status") ? 1 : 0;

                    $validationRules = [];
                    if ($status == 1)
                    {
                         $validationRules = [
                              'pt_number' => 'required',
                         ];
                    }
                    $validator = Validator::make(
                         $request->all(),
                         $validationRules
                    );

                    if ($validator->fails())
                    {
                         $messages = $validator->messages();
                         return redirect()->back()->with('error', $messages->first())->withInput();
                    }

                    DB::beginTransaction();

                    $user = User::where("id", Auth::user()->creatorId())->first();

                    $user->professional_tax_status = $status;
                    if ($status == 1)
                    {
                         $user->professional_tax_number = $request->pt_number;
                    }
                    $user->save();


                    if ($status == 1)
                    {
                         StatutoryDeductionsProfessionalTaxRange::where('created_by', Auth::user()->creatorId())->delete();

                         foreach ($request->salary_start_range as $key => $value)
                         {
                              $salary_start_range = $request->salary_start_range[$key];
                              $salary_end_range = $request->salary_end_range[$key];
                              $deduction_amount = $request->deduction_amount[$key];
                              $work_location = $request->work_location[$key];

                              if ($salary_start_range != null && $salary_end_range != null && $deduction_amount != null)
                              {
                                   StatutoryDeductionsProfessionalTaxRange::create([
                                        "salary_start_range" => $salary_start_range,
                                        "salary_end_range" => $salary_end_range,
                                        "deduction_amount" => $deduction_amount,
                                        "work_location" => $work_location,
                                        "status" => 1,
                                        "created_by" => Auth::user()->creatorId(),
                                   ]);
                              }
                         }
                    }

                    DB::commit();

                    return redirect()->back()->with('success', __('Professional Tax Details Updated Successfully.'));
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          catch (Throwable $ex)
          {
               DB::rollBack();
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
     }

     public function statutoryDeductionsEsi(Request $request)
     {
          try
          {
               if (Auth::user()->can('Create Saturation Deduction'))
               {
                    $status = $request->has("status") ? 1 : 0;

                    $validationRules = [];
                    if ($status == 1)
                    {
                         $validationRules = [
                              'esi_number' => 'required',
                              'esi_employee_contribution_rate' => 'nullable|numeric',
                              'esi_employer_contribution_rate' => 'nullable|numeric',
                         ];
                    }
                    $validator = Validator::make(
                         $request->all(),
                         $validationRules
                    );

                    if ($validator->fails())
                    {
                         $messages = $validator->messages();
                         return redirect()->back()->with('error', $messages->first())->withInput();
                    }

                    DB::beginTransaction();

                    $esi = EmployeesStateInsurance::where('created_by', Auth::user()->creatorId())->first();
                    if ($esi == null)
                    {
                         $esi = new EmployeesStateInsurance();
                         $esi->created_by = Auth::user()->creatorId();
                    }

                    if ($status == 1)
                    {
                         $esi->esi_number = $request->esi_number;
                         $esi->employee_contribution_rate = $request->esi_employee_contribution_rate ?? 0;
                         $esi->employer_contribution_rate = $request->esi_employer_contribution_rate ?? 0;
                    }

                    $esi->status = $request->has("status") ? 1 : 0;

                    $esi->save();

                    DB::commit();

                    return redirect()->back()->with('success', __('EPF Details Updated Successfully.'));
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          catch (Throwable $ex)
          {
               DB::rollBack();
               return redirect()->back()->with('error', showExceptionMessage($ex));
          }
     }
}
