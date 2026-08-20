<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Models\PaySlip;

use Excel;
use PDF;
use App\Exports\WageReportsExport;
use App\Models\AllowanceOption;
use App\Models\AttendanceEmployee;
use App\Models\Commission_option;
use App\Models\DeductionOption;
use App\Models\Department;
use App\Models\Designation;
use App\Models\OtherPaymentotion;
use App\Models\PayslipType;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;

use DB;
use Exception;
use Throwable;

class ReportsManageController extends Controller
{
     public function wage_reports_index(Request $request)
     {
          $data = Employee::where('created_by', Auth::user()->creatorId())->get();
          foreach ($data as $row)
          {
               $employeeAttendance = AttendanceEmployee::where('employee_id', $row->id)->count();
               $row->attdebdance  = $employeeAttendance;
          }

          $requestMonth = $request->month ?? date("Y-m");
          $requestStatus = $request->status ?? -1;
          $requestPaySlabTypeId = $request->paySlabTypeId ?? 0;

          $paySlabTypes =  ["0" => __("All")] + PayslipType::where('created_by', '=', Auth::user()->creatorId())->get()->pluck("name", "id")->toArray();

          $statusArray = [-1 => __("All"), 1 => __("Paid"), 0 => __("Unpaid")];

          if ($request->has("btn_reset"))
          {
               $requestMonth = date("Y-m");
               $requestStatus = -1;
               $requestPaySlabTypeId = 0;
          }

          // Earnings Columns Data
          $allowances = AllowanceOption::where('is_display', 'Yes')->where('created_by', Auth::user()->creatorId())
               ->when($requestPaySlabTypeId > 0, function ($query) use ($requestPaySlabTypeId)
               {
                    return $query->where('play_slab_id', $requestPaySlabTypeId);
               })
               ->get();

          $commissions  = Commission_option::where('created_by', Auth::user()->creatorId())
               ->when($requestPaySlabTypeId > 0, function ($query) use ($requestPaySlabTypeId)
               {
                    return $query->where('play_slab_id', $requestPaySlabTypeId);
               })
               ->get();

          $other_payments = OtherPaymentotion::where('created_by', Auth::user()->creatorId())
               ->when($requestPaySlabTypeId > 0, function ($query) use ($requestPaySlabTypeId)
               {
                    return $query->where('play_slab_id', $requestPaySlabTypeId);
               })
               ->get();

          // Earnings Columns Data



          // Deductions Columns Data
          $saturation_deductions = DeductionOption::where('is_display', 'Yes')
               ->where('created_by', Auth::user()->creatorId())
               // ->where('play_slab_id', '=', $play_slab_id)
               ->when($requestPaySlabTypeId > 0, function ($query) use ($requestPaySlabTypeId)
               {
                    return $query->where('play_slab_id', $requestPaySlabTypeId);
               })
               ->get();
          // Deductions Columns Data



          $paylip_employee = PaySlip::where("salary_month", DB::raw("'" . $requestMonth . "'"))
               ->where('created_by', Auth::user()->creatorId())

               ->when($requestPaySlabTypeId != 0, function ($query) use ($requestPaySlabTypeId)
               {
                    return $query->where('play_slab_id', $requestPaySlabTypeId);
               })
               ->when($requestStatus != -1, function ($query) use ($requestStatus)
               {
                    return $query->where('status', $requestStatus);
               })
               ->when(Auth::user()->type == "employee", function ($query) use ($requestStatus)
               {
                    $employeeData = Employee::where("user_id", Auth::user()->id)->first();
                    return $query->where('employee_id', $employeeData->id);
               })

               ->orderBy("play_slab_id")
               ->get();


          $employeesPayrollData = [];

          $srNo = 1;
          foreach ($paylip_employee as $paySlip)
          {
               $employeeDetails = Employee::find($paySlip->employee_id);

               try
               {
                    if ($employeeDetails != null)
                    {
                         $designation = Designation::find($employeeDetails->designation_id);
                         $departments = Department::find($employeeDetails->department_id);
                         $payslipTypeData = PayslipType::find($paySlip->play_slab_id);

                         $employeesPayrollDataRow = [];

                         $employeesPayrollDataRow["sr_no"] = $srNo;
                         $employeesPayrollDataRow["pay_slap_type"] = ($payslipTypeData->name ?? "");
                         // $employeesPayrollDataRow["employee_id"] = Auth::user()->employeeIdFormat($employeeDetails->employee_id);
                         $employeesPayrollDataRow["employee_id"] = $employeeDetails->employee_id;
                         $employeesPayrollDataRow["name"] = $employeeDetails->name;
                         $employeesPayrollDataRow["designation"] = ($designation->name ?? "");
                         $employeesPayrollDataRow["department"] = ($departments->name ?? "");
                         $employeesPayrollDataRow["salary_month"] = $paySlip->salary_month;
                         $employeesPayrollDataRow["basic_salary"] = Auth::user()->priceFormat((float)$paySlip->basic_salary);


                         $employeesPayrollDataRow["allowances"] = [];
                         $employeesPayrollDataRow["commission"] = [];
                         $employeesPayrollDataRow["otherPayment"] = [];
                         $employeesPayrollDataRow["deduction"] = [];
                         $employeesPayrollDataRow["deduction"] = [];

                         $payslipDetail = Utility::employeePayslipDetail($employeeDetails->id, $paySlip->play_slab_id, $paySlip);

                         $totalEarnings = 0;
                         $totalDeductions = 0;

                         // earnings columns data

                         // allowances
                         $allowanceData = collect($payslipDetail['earning']['allowance_DB']);

                         $iteration = 1;


                         foreach ($allowances as $allowancesRow)
                         {
                              $checkAllowance = $allowanceData->where("id", $allowancesRow->id)->first();

                              $allowanceAmount = 0;
                              $allowancePercentage = "";

                              if ($checkAllowance != null)
                              {
                                   $allowanceAmount = ($checkAllowance->amount * $employeeDetails->salary) / 100;

                                   if ($checkAllowance->amount == 0 || $checkAllowance->amount == 00)
                                   {
                                        if ($checkAllowance->is_display_slary_slip == 1)
                                        {
                                             if ($checkAllowance->type != 'percentage')
                                             {
                                                  $allowanceAmount = $checkAllowance->amount;
                                             }
                                             else
                                             {
                                                  if ($checkAllowance->allowance_types == 0 || $checkAllowance->allowance_types == null)
                                                  {
                                                       $allowancePercentage = $checkAllowance->amount;
                                                  }
                                                  else
                                                  {
                                                       $allowancePercentage = $checkAllowance->amount;
                                                       if ($checkAllowance->type == 'percentage')
                                                       {
                                                            $getvalue = $this->get_allowance_value($checkAllowance->id, $checkAllowance->allowance_types, $employeeDetails);
                                                            $allowanceAmount = ($getvalue * $checkAllowance->amount) / 100;
                                                       }
                                                  }
                                             }
                                        }
                                   }
                                   else
                                   {
                                        if ($checkAllowance->type != 'percentage')
                                        {
                                             $allowanceAmount = $checkAllowance->amount;
                                        }
                                        else
                                        {
                                             if ($checkAllowance->allowance_types == 0 || $checkAllowance->allowance_types == null)
                                             {
                                                  $allowancePercentage = $checkAllowance->amount;
                                             }
                                             else
                                             {
                                                  $allowancePercentage = $checkAllowance->amount;
                                                  if ($checkAllowance->type == 'percentage')
                                                  {
                                                       $getvalue = $this->get_allowance_value($checkAllowance->id, $checkAllowance->allowance_types, $employeeDetails);

                                                       $allowanceAmount = ($getvalue * $checkAllowance->amount) / 100;
                                                  }
                                             }
                                        }
                                   }
                              }

                              $allowancePercentageShow = (empty($allowancePercentage) || $allowancePercentage <= 0) ? "" : " ($allowancePercentage %)";
                              $employeesPayrollDataRow["allowances"][] = Auth::user()->priceFormat((float)$allowanceAmount) . $allowancePercentageShow;

                              $totalEarnings += $allowanceAmount;
                              // $employeesPayrollDataRow["allowances"][] = $checkAllowance->name . " | " . Auth::user()->priceFormat((float)$allowanceAmount) . "($allowancePercentage %)";

                              $iteration++;
                         }
                         // allowances


                         // commissions
                         $commissionData = collect($payslipDetail['earning']['commission_DB']);
                         foreach ($commissions as $commission)
                         {
                              $checkCommission = $commissionData->where("id", $commission->id)->first();

                              $commissionAmount = 0;
                              $commissionPercentage = "";

                              if ($checkCommission != null)
                              {
                                   $empcomm = ($checkCommission->amount * $employeeDetails->salary) / 100;

                                   if ($checkCommission->employees_id == 0 || $checkCommission->employees_id == $employeeDetails->id)
                                   {

                                        if ($checkCommission->type != 'percentage')
                                        {
                                             $commissionAmount = $checkCommission->amount;
                                        }
                                        else
                                        {
                                             if ($checkCommission->commision_types == 0 || $checkCommission->commision_types == null)
                                             {
                                                  $commissionPercentage = $checkCommission->amount;
                                             }
                                             else
                                             {
                                                  $commissionPercentage = $checkCommission->amount;
                                                  if ($checkCommission->type == 'percentage')
                                                  {
                                                       $getvaluedata = $this->get_allowance_value($checkCommission->id, $checkCommission->commision_types, $employess);

                                                       $empcomm = ($getvaluedata * $checkCommission->amount) / 100;
                                                  }
                                             }
                                             $commissionAmount = $empcomm;
                                        }
                                   }
                              }

                              // dd($commissionAmount);

                              $commissionPercentageShow = (empty($commissionPercentage) || $commissionPercentage <= 0) ? "" : " ($commissionPercentage %)";
                              $employeesPayrollDataRow["commission"][] = Auth::user()->priceFormat((float)$commissionAmount) . $commissionPercentageShow;

                              $totalEarnings += $commissionAmount;
                         }
                         // commissions

                         // other payments
                         $otherPaymentData = collect($payslipDetail['earning']['otherPayment_DB']);

                         foreach ($other_payments as $other_payment)
                         {
                              $checkOtherPayment = $otherPaymentData->where("id", $other_payment->id)->first();

                              $otherPaymentAmount = 0;
                              $otherPaymentPercentage = "";

                              if ($checkOtherPayment != null)
                              {
                                   $otherPaymentAmount = ($checkOtherPayment->amount * $employeeDetails->salary) / 100;

                                   if ($checkOtherPayment->employees_id == 0 || $checkOtherPayment->employees_id == $employeeDetails->id)
                                   {
                                        if ($checkOtherPayment->type != 'percentage')
                                        {
                                             $otherPaymentAmount = $checkOtherPayment->amount;
                                        }
                                        else
                                        {
                                             $otherPaymentPercentage = $checkOtherPayment->amount;
                                        }
                                   }
                              }

                              $otherPaymentPercentageShow = (empty($otherPaymentPercentage) || $otherPaymentPercentage <= 0) ? "" : " ($otherPaymentPercentage %)";
                              $employeesPayrollDataRow["otherPayment"][] = Auth::user()->priceFormat((float)$otherPaymentAmount) . $otherPaymentPercentageShow;

                              $totalEarnings += $otherPaymentAmount;
                         }
                         // other payments

                         // earnings columns data


                         // -----------------------------------

                         // deductions columns data


                         // Saturation Deduction
                         $deductionData = collect($payslipDetail['deduction']['deduction_DB']);


                         foreach ($saturation_deductions as $saturation_deduction)
                         {
                              $checkDeduction = $deductionData->where("id", $saturation_deduction->id)->first();

                              $deductionAmount = 0;
                              $deductionPercentage = "";

                              if ($checkDeduction != null)
                              {
                                   $deductionAmount = ($checkDeduction->amount * $employeeDetails->salary) / 100;

                                   if ($checkDeduction->employees_id == 0 || $checkDeduction->employees_id == $employeeDetails->id)
                                   {
                                        if ($checkDeduction->amount == 0 || $checkDeduction->amount == 00)
                                        {
                                             if ($checkDeduction->is_display_slary_slip == 1)
                                             {
                                                  if ($checkDeduction->type != 'percentage')
                                                  {
                                                       $deductionAmount = $checkDeduction->amount;
                                                  }
                                                  else
                                                  {
                                                       $deductionPercentage = $checkDeduction->amount;
                                                  }
                                             }
                                        }
                                        else
                                        {
                                             if ($checkDeduction->type != 'percentage')
                                             {
                                                  $deductionAmount = $checkDeduction->amount;
                                             }
                                             else
                                             {
                                                  $deductionPercentage = $checkDeduction->amount;
                                             }
                                        }
                                   }
                              }

                              $deductionPercentageShow = (empty($deductionPercentage) || $deductionPercentage <= 0) ? "" : " ($deductionPercentage %)";
                              $employeesPayrollDataRow["deduction"][] = Auth::user()->priceFormat((float)$deductionAmount) . $deductionPercentageShow;

                              $totalDeductions += $deductionAmount;
                         }
                         // Saturation Deduction



                         // Other Deductions like PF, ESIC

                         $employeesPayrollDataRow["pf"] = Auth::user()->priceFormat(number_format(0));
                         $employeesPayrollDataRow["esic"] = Auth::user()->priceFormat(number_format(0));

                         $other_payments_deductions = collect(json_decode($paySlip->other_payments_deductions) ?? []);

                         if ($paySlip->other_payments_deductions_total_amount > 0 && !empty($other_payments_deductions))
                         {
                              // type 0 = pf, 1 = esic
                              try
                              {
                                   $employeesPayrollDataRow["pf"] = Auth::user()->priceFormat(number_format($other_payments_deductions->where("type", 0)->first()->amount ?? 0));
                                   $employeesPayrollDataRow["esic"] = Auth::user()->priceFormat(number_format($other_payments_deductions->where("type", 1)->first()->amount ?? 0));
                              }
                              catch (\Throwable $th)
                              {
                              }
                         }

                         // 

                         // Other Deductions like PF, ESIC

                         // Loan Deduction
                         $loanData = collect($payslipDetail['deduction']['loan_DB']);

                         $loanAmount = 0;
                         $loanPercentage = "";

                         if (!empty($loanData))
                         {
                              foreach ($loanData as $loanRow)
                              {
                                   $loanAmount += ($loanRow->amount * $employeeDetails->salary) / 100;

                                   if ($loanRow->type != 'percentage')
                                   {
                                        $loanAmount += $loanRow->amount;
                                   }
                                   else
                                   {
                                        $loanPercentage = $loanRow->amount;
                                   }
                              }
                         }

                         $loanPercentageShow = (empty($loanPercentage) || $loanPercentage <= 0) ? "" : " ($loanPercentage %)";
                         $employeesPayrollDataRow["loanAmount"] = Auth::user()->priceFormat((float)$loanAmount) . $loanPercentageShow;

                         $totalDeductions += $loanAmount;

                         // Loan Deduction

                         // Leave Deduction

                         $leaveAmount = 0;

                         if (!empty($paySlip['leave_deduction']) && $paySlip['leave_deduction'] != '[]')
                         {
                              $leaveData = collect(json_decode($paySlip['leave_deduction']));

                              foreach ($leaveData as $loanRow)
                              {
                                   $leaveAmount += $loanRow->amountdeduct;
                              }
                         }
                         $employeesPayrollDataRow["leaveAmount"] = Auth::user()->priceFormat((float)$leaveAmount);

                         $totalDeductions += $leaveAmount;
                         // Leave Deduction

                         // deductions columns data


                         // totalEarnings
                         $employeesPayrollDataRow["totalEarnings"] = Auth::user()->priceFormat((float)$totalEarnings);

                         // totalDeductions
                         $employeesPayrollDataRow["totalDeductions"] = Auth::user()->priceFormat((float)$totalDeductions);

                         $employeesPayrollDataRow["grossEarnings"] = Auth::user()->priceFormat((float)$totalEarnings + $paySlip->basic_salary);
                         $employeesPayrollDataRow["grossDeductions"] = Auth::user()->priceFormat((float)$totalDeductions);
                         $employeesPayrollDataRow["netPayableSalary"] = Auth::user()->priceFormat((float)$paySlip->net_payble);

                         $employeesPayrollDataRow["paySlipStatus"] = ($paySlip->status == 1 ? __("Paid") : __("Unpaid"));

                         $employeesPayrollData[] = $employeesPayrollDataRow;

                         $srNo++;
                    }
               }
               catch (Throwable $th)
               {
               }
               catch (Exception $th)
               {
               }
          }

          if ($request->has("btn_export"))
          {
               if (!empty($employeesPayrollData))
               {
                    $fileName = "Employees Payslip Data of $requestMonth.xlsx";

                    $excelExportColumns = [
                         __("Sr.no"),
                         __("Pay Slab"),
                         __("EMP Code"),
                         __("Name"),
                         __("Designation"),
                         __("Department"),

                         __("Basic Salary"),
                    ];

                    // earning columns

                    // allowances columns
                    foreach ($allowances as $allowancesRow)
                    {
                         $excelExportColumns[] = $allowancesRow->name;
                    }
                    // allowances columns

                    // commissions columns
                    foreach ($commissions as $commissionsRow)
                    {
                         $excelExportColumns[] = $commissionsRow->name;
                    }
                    // commissions columns

                    // other_payments columns
                    foreach ($other_payments as $other_paymentsRow)
                    {
                         $excelExportColumns[] = $other_paymentsRow->name;
                    }
                    // other_payments columns
                    // earning columns

                    $excelExportColumns[] = __("Gross Earning");

                    // deduction columns
                    // saturation_deductions columns
                    foreach ($saturation_deductions as $saturation_deductionsRow)
                    {
                         $excelExportColumns[] = $saturation_deductionsRow->name;
                    }
                    // saturation_deductions columns

                    // Other Deductions
                    $excelExportColumns[] = __("PF");
                    $excelExportColumns[] = __("ESIC");
                    // Other Deductions

                    $excelExportColumns[] = __("Loan Amount");
                    $excelExportColumns[] = __("Leave Amount");
                    // deduction columns

                    $excelExportColumns[] = __("Gross Deduction");
                    $excelExportColumns[] = __("Net Pay");
                    $excelExportColumns[] = __("Status");

                    $settings = Utility::settings();



                    $exportData = [
                         "companyName" => $settings['company_name'],
                         "salaryMonth" => $requestMonth,
                         "columns" => $excelExportColumns,
                         "data" => $employeesPayrollData,
                    ];

                    // dd($exportData);


                    $data = Excel::download(new WageReportsExport($exportData), $fileName);
                    return $data;
               }
               else
               {
                    return redirect()->back()->with('error', __("No data found."));
               }
          }
          else
          {
               return view('ReportsManage.index', compact('data', 'allowances', 'commissions', 'other_payments', 'saturation_deductions', 'employeesPayrollData', 'requestMonth', 'statusArray', 'requestMonth', 'requestStatus', 'requestPaySlabTypeId', 'paySlabTypes'));
          }
     }


     function get_allowance_value($id, $parent_id, $employess)
     {
          $get_parentData = AllowanceOption::find($parent_id);

          if (!empty($get_parentData))
          {
               if ($get_parentData->allowance_types == 0 || $get_parentData->allowance_types == null)
               {
                    if ($get_parentData->type == 'percentage')
                    {
                         $empBasicSalary = ($get_parentData->amount * $employess->salary) / 100;

                         return $empBasicSalary;
                    }
                    else
                    {
                         return $get_parentData->amount;
                    }
               }
               else
               {
                    get_allowance_value($id, $get_parentData->id, $employess);
               }
          }
     }

     function get_commission_value($id, $parent_id, $employess)
     {
          $get_parentData = Commission_option::find($parent_id);

          $get_CurruntData = Commission_option::find($id);

          if (!empty($get_parentData))
          {
               if ($get_parentData->commision_types == 0 || $get_parentData->commision_types == null)
               {
                    if ($get_parentData->type == 'percentage')
                    {
                         $empBasicSalary = ($get_parentData->amount * $employess->salary) / 100;

                         return $empBasicSalary;
                    }
                    else
                    {
                         return $get_parentData->amount;
                    }
               }
               else
               {
                    get_commission_value($id, $get_parentData->id, $employess);
               }
          }
     }



     public function wagereports_export()
     {
          return Excel::download(new WageReportsExport, 'users.xlsx');
     }


     public function esic_register_reports(Request $request)
     {
          $data  = PaySlip::where('created_by', \Auth::user()->creatorId());
          if ($request->type == 'monthly')
          {
               $data->where('salary_month', '=', $request->month);
          }
          else
          {
               $data->where('salary_month', '=', date('Y-m'));
          }
          $data = $data->get();
          foreach ($data as $row)
          {
               $employeedata = Employee::find($row->employee_id);
               if ($employeedata != null)
               {
                    $row->empName = $employeedata->name;
                    $row->gender  = $employeedata->gender;
                    $row->basic_salary  = $employeedata->salary;
                    $row->bank_identifier_code  = $employeedata->bank_identifier_code;
                    $employeeAttendance = AttendanceEmployee::where('employee_id', $row->employee_id)->count();
                    $row->attdebdance  = $employeeAttendance;
               }
          }
          return view('ReportsManage.esic_register_reports', compact('data'));
     }

     public function esic_register_reports_export(request $request)
     {
          $company = User::find(\Auth::user()->creatorId());
          $data  = PaySlip::where('created_by', \Auth::user()->creatorId());
          if (!empty($request->month))
          {
               $data->where('salary_month', $request->month);
          }
          $data = $data->get();
          if (count($data) > 0)
          {
               foreach ($data as $row)
               {
                    $deduction =  json_decode($row->saturation_deduction);
                    if(!empty($deduction)) {
                         foreach ($deduction as $tex)
                         {
                              if ($tex->name == "ESIC")
                              {
                                   $row->Esic_tex = $tex;
                              }
                         }
                    }
               }
               $pdf = PDF::loadView('ReportsManage.esic_register_reports_export_pdf', compact('data', 'company'));
               return $pdf->download('esic_register_reports_export_pdf.pdf');
          }
          else
          {
               return redirect()->back()->with('error', 'Record not found.');
          }
     }
}
