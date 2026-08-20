<?php

namespace App\Http\Controllers;

use App\Exports\EmployeesSalarySlipPreviewExcelFileDownload;
use App\Exports\PayslipExport;
use App\Models\Allowance;
use App\Models\Commission;
use App\Models\Employee;
use App\Models\Loan;
use App\Mail\InvoiceSend;
use App\Mail\PayslipSend;
use App\Models\AllowanceOption;
use App\Models\Commission_option;
use App\Models\DeductionOption;
use App\Models\Department;
use App\Models\Designation;
use App\Models\OtherPayment;
use App\Models\Overtime;
use App\Models\PaySlip;
use App\Models\SaturationDeduction;
use App\Models\Utility;
use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\OtherPaymentotion;
use App\Models\PayslipType;
use App\Models\UserLeaveBalanceYear;
use App\Models\Holiday;
use App\Models\Leave as LocalLeave;
use App\Models\AttendanceEmployee;
use Barryvdh\DomPDF\Facade\Pdf;
use DB;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Google\Service\Sheets\NumberFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use Carbon\Carbon;

class PaySlipController extends Controller
{
     public function index(Request $request)
     {
          if ($request->has("debug"))
          {
               exit;
               return $this->recalculateLeaveBalance($request);
               $formate_month_year = 2026 . '-' . 01;

               $employeeDetails = Employee::where("id", 816)->first();

               $basicSalary = $employeeDetails->salary;

               $companyWorkingDays = getCompanyWorkingDays();
               $companyWorkingDays = 31;

               DB::beginTransaction();
               $leaveDataRaw = Employee::paySlipLeaveDeduction($employeeDetails->id, $formate_month_year, false);
               dd($leaveDataRaw, Auth::user()->creatorId());
               DB::commit();
               $extraLeaveDays = $leaveDataRaw["extraLeaveDays"];
               $extraLeaveDaysTotalAmount = $leaveDataRaw["total"];

               // $employeeWorkingDays = abs($companyWorkingDays - $extraLeaveDays);
               // $employeeWorkingDays = 28;

               // $payableSalary = ($basicSalary / $companyWorkingDays * $employeeWorkingDays);

               // $extraData = [
               //      "companyWorkingDays" => $companyWorkingDays,
               //      "employeeWorkingDays" => $employeeWorkingDays,
               // ];

               // $allowancesDataRaw = Employee::payslipAllowanceEmployeeWise($employeeDetails->id, $extraData);
               // dd($allowancesDataRaw);
          }

          try
          {
               if (\Auth::user()->can('Manage Pay Slip') || \Auth::user()->type == 'employee')
               {
                    $employees = Employee::where(
                         [
                              'created_by' => \Auth::user()->creatorId(),
                         ]
                    )
                         ->first();

                    $month = collect(range(1, 12))->mapWithKeys(function ($m)
                    {
                         return [str_pad($m, 2, '0', STR_PAD_LEFT) => Carbon::create()->month($m)->format('M')];
                    })->toArray();

                    $currentYear = Carbon::now()->year;
                    $year = [];

                    for ($i = $currentYear - 5; $i <= $currentYear + 10; $i++)
                    {
                         $year[$i] = (string)$i;
                    }

                    $employees = Employee::where(['created_by' => \Auth::user()->creatorId(),])->first();

                    $setting = Utility::settings();
                    $working_days_type = $setting["working_days_type"];

                    $is_payslip_not_created = !PaySlip::where('created_by', Auth::user()->creatorId())->exists();

                    $showDateSelectionForFirstTimePaySlipGenerations = false;

                    if ($is_payslip_not_created && $working_days_type == 2 && (\Auth::user()->can('Custom Payslip Generation For Specific Day') || true))
                    {
                         $showDateSelectionForFirstTimePaySlipGenerations = true;
                    }

                    return view('payslip.index', compact('employees', 'month', 'year', 'showDateSelectionForFirstTimePaySlipGenerations'));
               }
               else
               {
                    return redirect()->back()->with('error', __('Permission denied.'));
               }
          }
          catch (\Throwable $th)
          {
               return redirect()->back()->with('error', showExceptionMessage($th));
          }
     }

     public function create()
     {
          //
     }

     public function store(Request $request)
     {
          $validator = Validator::make(
               $request->all(),
               [
                    'month' => 'required',
                    'year' => 'required',

               ]
          );

          if ($validator->fails())
          {
               $messages = $validator->getMessageBag();
               return redirect()->back()->with('error', $messages->first());
          }

          try
          {
               $month = $request->month ?? 0;
               $year  = $request->year ?? 0;
               $form_action  = $request->form_action ?? "";

               $formate_month_year_raw = $year . '-' . $month;
               $formate_month_year = $year . '-' . $month;

               $previewData = [];
               $monthDates = [];

               $setting = Utility::settings();

               $working_days_type = $setting["working_days_type"];

               $salaryCalculationStartDate = "";
               $salaryCalculationEndDate = "";

               $is_payslip_not_created = false;

               $companyWorkingDays = getCompanyWorkingDays();


               if ($working_days_type == 2 && (\Auth::user()->can('Custom Payslip Generation For Specific Day') || true))
               {
                    $working_days_date = $setting["working_days_date"] ?? 1;

                    $salaryCalculationDate = Carbon::createFromDate($year, $month, $working_days_date)->format('Y-m-d');
                    if (Carbon::now()->greaterThan(Carbon::parse($salaryCalculationDate)))
                    {
                         $startDate = Carbon::parse($salaryCalculationDate)->subMonth()->addDay()->format('Y-m-d');
                         // $startDate = Carbon::parse($salaryCalculationDate)->subDays(29)->format('Y-m-d');
                         $endDate = Carbon::parse($salaryCalculationDate)->format('Y-m-d');

                         // dd($startDate, $endDate);

                         try
                         {
                              $is_payslip_not_created = !PaySlip::where('created_by', Auth::user()->creatorId())->exists();
                              if ($is_payslip_not_created)
                              {

                                   $validator = Validator::make(
                                        $request->all(),
                                        [
                                             'start_date_to_generate_payslip_for_first_time' => 'required|date',

                                        ],
                                        [
                                             'start_date_to_generate_payslip_for_first_time.required' => 'Start date is required for first time pay slip generation',
                                             'start_date_to_generate_payslip_for_first_time.date' => 'Start date is not valid date',
                                        ]
                                   );

                                   if ($validator->fails())
                                   {
                                        $messages = $validator->getMessageBag();
                                        return response()->json(['status' => 500, "message" => $messages->first()]);
                                   }

                                   $startDate = Carbon::parse($request->start_date_to_generate_payslip_for_first_time)->format('Y-m-d');
                              }
                         }
                         catch (\Throwable $th)
                         {
                              // dd("ERROR", $th);
                         }

                         $salaryCalculationStartDate = $startDate;
                         $salaryCalculationEndDate = $endDate;

                         $formate_month_year = Carbon::parse($salaryCalculationDate)->format('Y-m');

                         for ($date = Carbon::parse($startDate); $date->lte(Carbon::parse($endDate)); $date->addDay())
                         {
                              $monthDates[] = $date->format('Y-m-d');
                         }

                         $companyWorkingDays = count($monthDates);
                         // dd("companyWorkingDays count", $companyWorkingDays, $monthDates);
                    }
                    else
                    {
                         // return response()->json(['status' => 500, "message" => __('Salary Can not be generated until Salary Cycle will complete for selected month date')]);
                         return response()->json(['status' => 500, "message" => __('Payslip generation is not allowed before ' . Carbon::parse($salaryCalculationDate)->format('d-m-Y') . " for selected month's date")]);
                    }
               }
               else
               {
                    $startOfMonth = Carbon::create($year, $month, 1);
                    $daysInMonth = $startOfMonth->daysInMonth;

                    $salaryCalculationStartDate = Carbon::create($year, $month, 1)->format('Y-m-d');
                    $salaryCalculationEndDate = Carbon::create($year, $month, $daysInMonth)->format('Y-m-d');

                    for ($day = 1; $day <= $daysInMonth; $day++)
                    {
                         $monthDates[] = $startOfMonth->format('Y-m-d');
                         $startOfMonth->addDay();
                    }
                    $companyWorkingDays = getCompanyWorkingDays();
               }

               $validatePaysilp = PaySlip::where('salary_month', '=', $formate_month_year_raw)->where('created_by', Auth::user()->creatorId())->pluck('employee_id')->toArray();

               $searchEmployeeIds = [167];

               $payslip_employee   = Employee::where('created_by', Auth::user()->creatorId())
                    ->where('company_doj', '<=', date($year . '-' . $month . '-t'))
                    ->when(Auth::user()->creatorId() == 308 && !empty($searchEmployeeIds), function ($q) use ($searchEmployeeIds)
                    {
                         return $q->whereIn("id", $searchEmployeeIds);
                    })

                    ->count();

               if ($payslip_employee >= count($validatePaysilp))
               {
                    $employees = Employee::where('created_by', Auth::user()->creatorId())
                         ->where('company_doj', '<=', date($year . '-' . $month . '-t'))
                         // ->whereNotIn('id', $validatePaysilp)
                         ->where('employe_status', "Active")
                         ->when(Auth::user()->creatorId() == 308 && !empty($searchEmployeeIds), function ($q) use ($searchEmployeeIds)
                         {
                              return $q->whereIn("id", $searchEmployeeIds);
                         })
                         ->where("employee_exit", 0)
                         ->where("salary", ">", 0)
                         ->get();


                    if ($employees->isEmpty())
                    {
                         return response()->json(['status' => 500, "message" => __('No employees found.')]);
                    }

                    $weekOffArray = collect([
                         'company_office_week_off_monday' => 'Monday',
                         'company_office_week_off_tuesday' => 'Tuesday',
                         'company_office_week_off_wednesday' => 'Wednesday',
                         'company_office_week_off_thusday' => 'Thursday',
                         'company_office_week_off_friday' => 'Friday',
                         'company_office_week_off_saturday' => 'Saturday',
                         'company_office_week_off_sunday' => 'Sunday',
                    ])->filter(fn($day, $key) => $setting[$key] === 'on')->values()->toArray();

                    $holidayData = Holiday::select("id", "status", "start_date", "end_date", "created_by", "branch_id", "department_id", "occasion")
                         ->where('created_by', Auth::user()->creatorId())
                         ->where(function ($query) use ($monthDates)
                         {
                              $query->whereIn('start_date', $monthDates)
                                   ->orWhereIn('end_date', $monthDates);
                         })
                         ->get();

                    $uniqueEmployeeIds = $employees->pluck('id')->unique()->toArray();
                    // dd(implode(',', $uniqueEmployeeIds));

                    $leaveData = Leave::select("id", "employee_id", "status", "start_date", "end_date", "created_by", "total_leave_days")
                         ->where('status', 'Approved')
                         ->where('created_by', Auth::user()->creatorId())
                         ->whereIn('employee_id', $uniqueEmployeeIds)
                         ->where(function ($query) use ($monthDates)
                         {
                              foreach ($monthDates as $date)
                              {
                                   $query->orWhere(function ($subQuery) use ($date)
                                   {
                                        $subQuery->where('start_date', '<=', $date)->where('end_date', '>=', $date);
                                   });
                              }
                         })
                         ->get();

                    $leaveDataArray = [];
                    foreach ($leaveData as $item)
                    {
                         foreach ($monthDates as $date)
                         {
                              if ($item->start_date <= $date && $item->end_date >= $date)
                              {
                                   $leaveDataArray[$item->employee_id][$date] = $item;
                              }
                         }
                    }
                    $employeeAttendance = AttendanceEmployee::whereIn('employee_id', $uniqueEmployeeIds)->select('employee_id', 'overtime', 'status', 'late', 'early_leaving', 'date')
                         ->whereIn('date', $monthDates)->get();

                    $dateWiseAttendance = [];


                    foreach ($employeeAttendance as $attendance)
                    {
                         try
                         {
                              $attendance_date = Carbon::parse($attendance->getRawOriginal("date"))->format('Y-m-d');

                              if (!isset($dateWiseAttendance[$attendance->employee_id]))
                              {
                                   $dateWiseAttendance[$attendance->employee_id] = []; // Initialize the employee's entry
                              }

                              // Assign attendance data to the corresponding date for the employee
                              $dateWiseAttendance[$attendance->employee_id][$attendance_date] = [
                                   'status' => $attendance->status,
                                   'overtime' => $attendance->overtime,
                                   'late' => $attendance->late,
                                   'early_leaving' => $attendance->early_leaving,
                              ];
                         }
                         catch (\Throwable $th)
                         {
                         }
                    }

                    $employeesAttendance = [];


                    foreach ($employees as $employee)
                    {
                         $is_custom_weekoffs = $employee->is_custom_weekoffs ?? 0;
                         $employee_weekoffs_data = json_decode($employee->weekoffs_data) ?? [];

                         $attendanceStatus = collect($monthDates)->mapWithKeys(function ($date) use ($employee, $leaveDataArray, $holidayData, $weekOffArray, $is_custom_weekoffs, $employee_weekoffs_data, $setting, $dateWiseAttendance)
                         {
                              if (strtotime($employee->getRawOriginal("company_doj")) > strtotime($date))
                              {
                                   return [];
                              }

                              // Check for custom week offs or default week offs
                              $isWeekOff = $is_custom_weekoffs
                                   ? in_array($date, $employee_weekoffs_data)
                                   : in_array(date('l', strtotime($date)), $weekOffArray);

                              $holidays_branch_ids = [];

                              foreach ($holidayData as $holiday)
                              {
                                   $start_date = Carbon::parse($holiday->start_date)->format('Y-m-d');
                                   $end_date = Carbon::parse($holiday->end_date)->format('Y-m-d');
                                   if ($start_date == $date || $end_date == $date)
                                   {
                                        $holidays_branch_ids = json_decode($holiday->branch_id) ?? [];
                                   }
                              }
                              // Determine leave status and update total leave
                              if (!empty($leaveDataArray) && !empty($leaveDataArray[$employee->id]) && !empty($leaveDataArray[$employee->id][$date]))
                              {
                                   if ($leaveDataArray[$employee->id][$date]['total_leave_days'] == 0.5)
                                   {
                                        return [$date => 'HD']; // Half-day leave
                                   }
                                   else
                                   {
                                        return [$date => 'L']; // Full-day leave
                                   }
                              }
                              else
                              {
                                   if (strtotime($date) <= strtotime(date('Y-m-d')))
                                   {
                                        if (empty($dateWiseAttendance) || !isset($dateWiseAttendance[$employee->id]) || !isset($dateWiseAttendance[$employee->id][$date]))
                                        {
                                             if ($isWeekOff)
                                             {
                                                  return [$date => 'WO']; // Week off
                                             }
                                             else
                                             {
                                                  if (array_intersect([0, $employee->branch_id], $holidays_branch_ids))
                                                  {
                                                       return [$date => 'H']; // Holiday
                                                  }
                                                  else
                                                  {
                                                       if ($date == date('Y-m-d') && $setting['company_start_time'] >= date('H:i'))
                                                       {
                                                            return [$date => '']; // No status, it's before the start time
                                                       }
                                                       else
                                                       {
                                                            return [$date => 'A']; // Absent
                                                       }
                                                  }
                                             }
                                        }
                                        else
                                        {
                                             $attendanceStatus = $dateWiseAttendance[$employee->id][$date]['status'];

                                             if ($attendanceStatus == 'Present')
                                             {
                                                  $attendanceStatus = 'P';
                                             }
                                             else
                                             {
                                                  $attendanceStatus = '';
                                             }
                                             return [$date => $attendanceStatus]; // Return attendance status for present days
                                        }
                                   }
                                   else
                                   {
                                        if ($isWeekOff)
                                        {
                                             return [$date => 'WO']; // Week off
                                        }
                                        else
                                        {
                                             if (array_intersect([0, $employee->branch_id], $holidays_branch_ids))
                                             {
                                                  return [$date => 'H']; // Holiday
                                             }
                                             else
                                             {
                                                  return [$date => ''];
                                             }
                                        }
                                   }
                              }
                              return [$date => '']; // Future date, no status
                         })->toArray();

                         if (in_array('A', $attendanceStatus))
                         {
                              $absentDates = array_filter($attendanceStatus, function ($status)
                              {
                                   return $status === 'A';
                              });

                              $new_array['id'] = $employee->id;
                              $new_array['name'] = $employee->name;
                              $leavetypes      = LeaveType::where('play_slab_id', $employee->play_slab_id)->where('created_by', '=', Auth::user()->creatorId())->get();
                              $new_array['leavetypes'] = $leavetypes;
                              $new_array['absentDates'] = array_keys($absentDates);
                              $employeesAttendance[] = $new_array;
                         }
                    }

                    if (!empty($employeesAttendance))
                    {
                         return response()->json(['status' => 0, "message" => __('There are aemployee who has absent please add leave for them.'), 'data' => $employeesAttendance]);
                    }
                    else
                    {
                         if ($form_action == 'download')
                         {
                              return response()->json(['status' => 2]);
                         }
                    }


                    foreach ($employees as $employee)
                    {
                         $employeeDetails = $employee;

                         // Set Pay Slip Data Start

                         DB::beginTransaction();

                         $paySlipData = [];

                         $logo = Utility::get_file('uploads/logo/');
                         $company_logo = Utility::get_company_logo_without_auth($employeeDetails->created_by);
                         $designation = Designation::find($employeeDetails->designation_id);
                         $departments = Department::find($employeeDetails->department_id);

                         $basicSalary = $employeeDetails->salary;


                         // $companyWorkingDays = 31;

                         // Old leave deduction
                         // $leaveDataRaw = Employee::paySlipLeaveDeduction($employeeDetails->id, $formate_month_year, false);
                         // $extraLeaveDays = $leaveDataRaw["extraLeaveDays"];
                         // $extraLeaveDaysTotalAmount = $leaveDataRaw["total"];
                         // Old leave deduction

                         // new leave deduction
                         // $leaveDataRaw = getLeavesDataforPaySlip($employeeDetails->id, $formate_month_year);
                         $companyWorkingDaysCal = $companyWorkingDays;
                         if (strtotime($startDate) < strtotime($employee->company_doj))
                         {
                              $from = strtotime($employee->company_doj);
                              $to   = strtotime($endDate);

                              $companyWorkingDaysCal = floor(($to - $from) / (60 * 60 * 24));
                              $companyWorkingDaysCal += 1;
                         }

                         $leaveDataRaw = getLeavesDataforPaySlip($employeeDetails->id, $salaryCalculationStartDate, $salaryCalculationEndDate);
                         $extraLeaveDays = $leaveDataRaw["extraLeaveDays"];
                         $extraLeaveDaysTotalAmount = $leaveDataRaw["total"];

                         // dd([
                         //      'employee_id' => $employee->id,
                         //      'leaveDataRaw' => $leaveDataRaw,
                         //      'formate_month_year' => $formate_month_year,
                         //      'salaryCalculationStartDate' => $salaryCalculationStartDate,
                         //      'salaryCalculationEndDate' => $salaryCalculationEndDate,
                         // ]);
                         // new leave deduction


                         $employeeWorkingDays = abs($companyWorkingDaysCal - $extraLeaveDays);
                         // dd([
                         //      'employeeWorkingDays' => $employeeWorkingDays,
                         //      'companyWorkingDays' => $companyWorkingDays,
                         //      'extraLeaveDays' => $extraLeaveDays,
                         // ]);
                         // $employeeWorkingDays = 28;

                         if ($basicSalary > 0 && $companyWorkingDays > 0)
                         {
                              $payableSalary = (($basicSalary / $companyWorkingDays) * $employeeWorkingDays);

                              // dd([
                              //      "salaryCalculationStartDate" => $salaryCalculationStartDate,
                              //      "salaryCalculationEndDate" => $salaryCalculationEndDate,
                              //      'basicSalary' => $basicSalary,
                              //      'companyWorkingDays' => $companyWorkingDays,
                              //      'employeeWorkingDays' => $employeeWorkingDays,
                              //      'payableSalary' => $payableSalary,
                              // ]);
                         }
                         else
                         {
                              continue;
                         }

                         // dd(
                         //      [
                         //           "basic Salary" => $basicSalary,
                         //           "company Working Days" => $companyWorkingDays,
                         //           "employee Working Days" => $employeeWorkingDays,
                         //           "payable Salary" => $payableSalary,
                         //           "employee id" => $employeeDetails->id,
                         //      ]
                         // );

                         $extraData = [
                              "companyWorkingDays" => $companyWorkingDaysCal,
                              "employeeWorkingDays" => $employeeWorkingDays,
                              "formate_month_year" => $formate_month_year,
                              "extraLeaveDaysTotalAmount" => $extraLeaveDaysTotalAmount,
                         ];

                         // company data
                         $paySlipData["companyLogo"] = $logo . '/' . isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png';
                         $paySlipData["companyName"] = Utility::getValByName('company_name');
                         $paySlipData["companyAddress"] = Utility::getValByName('company_address');
                         $paySlipData["companyCity"] = Utility::getValByName('company_city');
                         $paySlipData["companyState"] = Utility::getValByName('company_state');
                         $paySlipData["companyZipcode"] = Utility::getValByName('company_zipcode');
                         // company data

                         // employee data
                         // $paySlipData["employeeId"] = employeeIdFormat($employeeDetails->id);
                         $paySlipData["employeeId"] = $employeeDetails->employee_id;
                         $paySlipData["employeeName"] = $employeeDetails->name;
                         $paySlipData["employeeDesignation"] = ($designation->name ?? "");
                         $paySlipData["employeeDepartment"] = ($departments->name ?? "");

                         $paySlipData["employeeSalaryMonth"] = date("Y-m");
                         // $paySlipData["employeeBasicSalary"] = $basicSalary;
                         $paySlipData["employeeBasicSalary"] = $payableSalary;
                         // $paySlipData["employeeBasicSalaryPayable"] = $payableSalary;
                         // employee data

                         // Earnings
                         $allowancesTotal = 0;
                         $commissionTotal = 0;
                         $overtimeTotal = 0;
                         $otherPaymentsTotal = 0;
                         $totalEarnings = 0; // Total of all earnings

                         // dd("extraData", $extraData);

                         $allowancesDataRaw = Employee::payslipAllowanceEmployeeWise($employeeDetails->id, $extraData);
                         $allowancesTotal = $allowancesDataRaw["total"];
                         $allowancesData = $allowancesDataRaw["data"];
                         // dd($allowancesDataRaw);

                         $commissionDataRaw = Employee::paySlipCommissionEmployeeWise($employeeDetails->id, $extraData);
                         $commissionTotal = $commissionDataRaw["total"];
                         $commissionData = $commissionDataRaw["data"];

                         // overtime calculation is pending | need to discuss then calculate this
                         $overtimeDataRaw = Employee::paySlipOvertime($employeeDetails->id);
                         $overtimeTotal = $overtimeDataRaw["total"];
                         $overtimeData = $overtimeDataRaw["data"];

                         $otherPaymentsDataRaw = Employee::paySlipOtherPaymentEarningsEmployeeWise($employeeDetails->id, $extraData);
                         $otherPaymentsTotal = $otherPaymentsDataRaw["total"];
                         $otherPaymentsData = $otherPaymentsDataRaw["data"];

                         $totalEarnings = ($allowancesTotal + $commissionTotal + $overtimeTotal + $otherPaymentsTotal);

                         $paySlipData["earnings"] = [
                              "allowances" => [
                                   "total" => $allowancesTotal,
                                   "data" => $allowancesData
                              ],
                              "commission" => [
                                   "total" => $commissionTotal,
                                   "data" => $commissionData
                              ],

                              // calculation is pending | need to discuss then calculate this
                              "overtime" => [
                                   "total" => $overtimeTotal,
                                   "data" => $overtimeData
                              ],
                              "otherPayments" => [
                                   "total" => $otherPaymentsTotal,
                                   "data" => $otherPaymentsData
                              ],
                              // "totalEarnings" => $totalEarnings,
                         ];

                         $statutoryBonusDataRaw = Employee::statutoryBonusEarningsEmployeeWise($employeeDetails->id, $extraData);
                         $statutoryBonusTotal = $statutoryBonusDataRaw["total"];
                         $statutoryBonusData = $statutoryBonusDataRaw["data"];

                         $paySlipData["statutoryBonus"] = [
                              "total" => $statutoryBonusTotal,
                              "data" => $statutoryBonusData
                         ];

                         $paySlipData["totalEarnings"] = $totalEarnings;
                         // Earnings

                         // Deductions

                         $saturationTotal = 0;
                         $loanTotal = 0;
                         $leaveTotal = 0;
                         $totalDeductions = 0; // total of all deductions

                         $saturationDataRaw = Employee::paySlipSaturationDeductionEmployeeWise($employeeDetails->id, $extraData);
                         $saturationTotal = $saturationDataRaw["total"];
                         $saturationData = $saturationDataRaw["data"];

                         // Loan Deduction
                         $loanDataRaw = Employee::paySlipLoanDeduction($employeeDetails->id, $formate_month_year);
                         $loanTotal = $loanDataRaw["total"];
                         $loanData = $loanDataRaw["data"];

                         // Old Leave Deduction
                         // $leaveDataRaw = Employee::paySlipLeaveDeduction($employeeDetails->id, $formate_month_year, true);
                         // $leaveTotal = $leaveDataRaw["total"];
                         // $leaveData = $leaveDataRaw["data"];
                         // Old Leave Deduction

                         // New Leave Deduction
                         // $leaveDataRaw = getLeavesDataforPaySlip($employeeDetails->id, $formate_month_year);
                         $leaveDataRaw = getLeavesDataforPaySlip($employeeDetails->id, $salaryCalculationStartDate, $salaryCalculationEndDate);
                         // dd($leaveDataRaw);
                         $leaveTotal = $leaveDataRaw["total"];
                         $leaveData = $leaveDataRaw["data"];
                         // New Leave Deduction

                         // Other Payment Deductions Calculation Like (Employee Provident Fund, Employees State Insurance, Employees Professional Tax)
                         $grossSalary = numberFormat(round($payableSalary + $totalEarnings));
                         // $grossSalary = $basicSalary;

                         // Employee Provident Fund of Employee
                         $providentFundEmployeeDeductionDataRaw = Employee::providentFundEmployeeDeduction($employeeDetails->id, $grossSalary, $extraData);
                         $providentFundEmployeeDeductionTotal = $providentFundEmployeeDeductionDataRaw["total"];
                         $providentFundEmployeeDeductionData = $providentFundEmployeeDeductionDataRaw["data"];
                         // Employee Provident Fund of Employee

                         // Employees State Insurance of Employee
                         $employeesStateInsuranceEmployeeDeductionDataRaw = Employee::employeesStateInsuranceEmployeeDeduction($employeeDetails->id, $grossSalary, $extraData);
                         $employeesStateInsuranceEmployeeDeductionTotal = $employeesStateInsuranceEmployeeDeductionDataRaw["total"];
                         $employeesStateInsuranceEmployeeDeductionData = $employeesStateInsuranceEmployeeDeductionDataRaw["data"];
                         // Employees State Insurance of Employee


                         // Employees Professional Tax
                         $employeeProfessionalTaxDeductionDataRaw = Employee::employeeProfessionalTaxDeduction($employeeDetails->id, $grossSalary, $extraData);
                         $employeeProfessionalTaxDeductionTotal = $employeeProfessionalTaxDeductionDataRaw["total"];
                         $employeeProfessionalTaxDeductionData = $employeeProfessionalTaxDeductionDataRaw["data"];
                         // Employees Professional Tax

                         $tdsDeductionDataRaw = Employee::tdsDeductionEmployeeWise($employeeDetails->id, $extraData);
                         $tadDeductionTotal = $tdsDeductionDataRaw["total"];
                         $tadDeductionData = $tdsDeductionDataRaw["data"];

                         // $totalDeductions = ($saturationTotal + $loanTotal + $leaveTotal + $providentFundEmployeeDeductionTotal + $employeesStateInsuranceEmployeeDeductionTotal + $employeeProfessionalTaxDeductionTotal + $tadDeductionTotal);
                         $totalDeductions = ($saturationTotal + $loanTotal + $providentFundEmployeeDeductionTotal + $employeesStateInsuranceEmployeeDeductionTotal + $employeeProfessionalTaxDeductionTotal + $tadDeductionTotal);

                         $paySlipData["deductions"] = [
                              "saturation" => [
                                   "total" => $saturationTotal,
                                   "data" => $saturationData
                              ],

                              // Loan Deduction
                              "loan" => [
                                   "total" => $loanTotal,
                                   "data" => $loanData
                              ],
                              // Loan Deduction

                              // Employee Provident Fund of Employee
                              "providentFundEmployeeDeduction" => [
                                   "total" => $providentFundEmployeeDeductionTotal,
                                   "data" => $providentFundEmployeeDeductionData
                              ],
                              // Employee Provident Fund of Employee

                              // Employees State Insurance of Employee
                              "employeesStateInsuranceEmployeeDeduction" => [
                                   "total" => $employeesStateInsuranceEmployeeDeductionTotal,
                                   "data" => $employeesStateInsuranceEmployeeDeductionData
                              ],
                              // Employees State Insurance of Employee

                              // Employees Professional Tax
                              "employeeProfessionalTaxDeduction" => [
                                   "total" => $employeeProfessionalTaxDeductionTotal,
                                   "data" => $employeeProfessionalTaxDeductionData
                              ],
                              // Employees Professional Tax


                              // Tax Deduction at Sourse
                              "tdsDeductions" => [
                                   "total" => $tadDeductionTotal,
                                   "data" => $tadDeductionData
                              ],
                              // Tax Deduction at Sourse
                         ];

                         // Leave Deduction
                         $paySlipData["leave"] = [
                              "total" => $leaveTotal,
                              "data" => $leaveData
                         ];
                         // Leave Deduction

                         $paySlipData["totalDeductions"] = $totalDeductions;


                         // Employee Provident Fund of Company
                         $providentFundEompanyDeductionDataRaw = Employee::providentFundCompanyDeduction($employeeDetails->id, $grossSalary, $extraData);
                         $providentFundCompanyDeductionTotal = $providentFundEompanyDeductionDataRaw["total"];
                         $providentFundCompanyDeductionData = $providentFundEompanyDeductionDataRaw["data"];

                         $paySlipData["providentFundCompanyDeduction"] = [
                              "total" => $providentFundCompanyDeductionTotal,
                              "data" => $providentFundCompanyDeductionData
                         ];

                         // Employee Provident Fund of Company

                         // Employees State Insurance of Employee
                         $employeesStateInsuranceCompanyDeductionDataRaw = Employee::employeesStateInsuranceCompanyDeduction($employeeDetails->id, $grossSalary, $extraData);
                         $employeesStateInsuranceCompanyDeductionTotal = $employeesStateInsuranceCompanyDeductionDataRaw["total"];
                         $employeesStateInsuranceCompanyDeductionData = $employeesStateInsuranceCompanyDeductionDataRaw["data"];

                         $paySlipData["employeesStateInsuranceCompanyDeduction"] = [
                              "total" => $employeesStateInsuranceCompanyDeductionTotal,
                              "data" => $employeesStateInsuranceCompanyDeductionData
                         ];

                         // Employees State Insurance of Employee


                         // Deductions


                         // $finalPayableSalary = numberFormat(round((($payableSalary + $totalEarnings + $statutoryBonusTotal) - ($totalDeductions + $providentFundCompanyDeductionTotal + $employeesStateInsuranceCompanyDeductionTotal))));
                         // $finalPayableSalary = numberFormat(round((($payableSalary + $totalEarnings + $statutoryBonusTotal) - ($totalDeductions))));
                         $finalPayableSalary = numberFormat(round((($payableSalary + $totalEarnings) - ($totalDeductions))));

                         // dd(
                         //      [
                         //           "payable Salary" => $payableSalary,
                         //           "totalEarnings" => $totalEarnings,
                         //           "totalDeductions" => $totalDeductions,
                         //           "finalPayableSalary" => $finalPayableSalary,
                         //      ]
                         // );

                         $paySlipData["employeeNewPayableSalary"] = $finalPayableSalary;

                         $paySlipData["salaryCalculationStartDate"] = $salaryCalculationStartDate;
                         $paySlipData["salaryCalculationEndDate"] =   $salaryCalculationEndDate;
                         $paySlipData["companyWorkingDays"] = $companyWorkingDays;
                         $paySlipData["employeeWorkingDays"] = $employeeWorkingDays;
                         $paySlipData["extraLeaveDays"] = $extraLeaveDays;

                         // dd("formate_month_year", $formate_month_year);
                         // echo json_encode($paySlipData);
                         // exit;

                         // Set Pay Slip Data 

                         // ----------------------------------------------------------------------------

                         if ($form_action == 'download_again')
                         {
                              $paySlipData["employee_id"] = $employeeDetails->id;
                              $previewData[] = $paySlipData;
                              // if ($extraLeaveDays > 0)
                              // {
                              //      // $previewData[] = $paySlipData;
                              //      // dd(collect($leaveDataRaw["data"])->pluck("leave_id")->toArray());
                              //      // $previewData[] = collect($leaveDataRaw["data"])->pluck("leave_id")->toArray();
                              // }
                         }
                         else
                         {
                              $payslipEmployee = new PaySlip();
                              $payslipEmployee->employee_id = $employee->id;
                              $payslipEmployee->play_slab_id = $employee->play_slab_id;
                              $payslipEmployee->status = 0;
                              $payslipEmployee->salary_month = $formate_month_year;

                              $payslipEmployee->basic_salary = $basicSalary;
                              $payslipEmployee->net_payble = $finalPayableSalary;

                              // For Old Structure
                              // Earnings
                              $payslipEmployee->allowance = null;
                              $payslipEmployee->commission = null;
                              $payslipEmployee->overtime = null;
                              $payslipEmployee->other_payment = null;
                              // Earnings

                              // Deductions
                              $payslipEmployee->saturation_deduction = null;
                              $payslipEmployee->loan = null;
                              $payslipEmployee->leave_deduction = 0;

                              $payslipEmployee->other_payments_deductions = null;
                              $payslipEmployee->other_payments_deductions_total_amount = 0;
                              // Deductions
                              // For Old Structure

                              // New Structure
                              $payslipEmployee->pay_slip_data = json_encode($paySlipData);
                              $payslipEmployee->pay_slip_data_type = 1;
                              // New Structure

                              $payslipEmployee->created_by = Auth::user()->creatorId();
                              $payslipEmployee->save();

                              // dd("before commit");
                              DB::commit();
                         }
                    }

                    if ($form_action == 'download_again')
                    {
                         // Earnings List

                         $totalEarningsColumnsCount = 0;

                         // do not consider this in earnings column
                         // // add Statutory Bonus also
                         // $totalEarningsColumnsCount += 1; // for Statutory Bonus column

                         $allowancesList = AllowanceOption::where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get();
                         $commissionList = Commission_option::where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get();
                         $otherPaymentList = OtherPaymentotion::where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get();

                         $totalEarningsColumnsCount += $allowancesList->count();
                         $totalEarningsColumnsCount += $commissionList->count();
                         $totalEarningsColumnsCount += $otherPaymentList->count();

                         $totalEarningsColumnsCount += 1; // for total earnings total column
                         // Earnings List

                         //   ------------------------------------------------------------------------

                         // Deductions List

                         $totalDeductionsColumnsCount = 0;
                         $deductionList = DeductionOption::where('created_by', '=', Auth::user()->creatorId())->orderBy('name', 'ASC')->get();

                         $totalDeductionsColumnsCount += $deductionList->count();

                         // add below deductions also
                         // ____________________________________
                         // loan amount
                         // EPF | Employee Provident Fund Start
                         // ESI | Employees State Insurance Start
                         // EPT | Employees Professional Tax Start
                         // Tax Deducted at Source Start

                         $totalDeductionsColumnsCount += 1; /// for loan amount
                         $totalDeductionsColumnsCount += 1; /// for EPF | Employee Provident Fund Start column
                         $totalDeductionsColumnsCount += 1; /// for ESI | Employees State Insurance Start
                         $totalDeductionsColumnsCount += 1; /// for EPT | Employees Professional Tax Start
                         $totalDeductionsColumnsCount += 1; /// for Tax Deducted at Source Start

                         $totalDeductionsColumnsCount += 1; /// for total deductions column

                         // Deductions List

                         $salarySlipPreviewFormat = getSalarySlipPreviewAmountFormatConfig();

                         $pasasData = [
                              "earnings" => [
                                   "allowances" => $allowancesList,
                                   "commission" => $commissionList,
                                   "otherPayments" => $otherPaymentList,
                              ],
                              "totalEarningsColumnsCount" => $totalEarningsColumnsCount,
                              "deductions" => [
                                   $deductionList,
                              ],
                              "totalDeductionsColumnsCount" => $totalDeductionsColumnsCount,
                              "salarySlipData" => json_decode(json_encode($previewData)),

                              "salaryCalculationStartDate" => $salaryCalculationStartDate,
                              "salaryCalculationEndDate" => $salaryCalculationEndDate,
                              "showDecimalPoints" => $salarySlipPreviewFormat['showDecimalPoints'],
                              "decimalPoints" => $salarySlipPreviewFormat['decimalPoints'],

                              // "companyWorkingDays" => $companyWorkingDays,
                              // "employeeWorkingDays" => $employeeWorkingDays,
                              // "extraLeaveDays" => $extraLeaveDays,


                         ];

                         // Merge all arrays in $previewData into a single array before returning
                         // $mergedPreviewData = [];
                         // foreach ($previewData as $item)
                         // {
                         //      if (is_array($item))
                         //      {
                         //           $mergedPreviewData = array_merge($mergedPreviewData, $item);
                         //      }
                         // }
                         // return json_encode($mergedPreviewData);
                         // return json_encode($previewData);

                         return Excel::download(new EmployeesSalarySlipPreviewExcelFileDownload($pasasData), "Employees Salary Slip Preview of $formate_month_year.xlsx");
                    }

                    return response()->json(['status' => 1, "message" => __('Payslip successfully created.'), 'is_reload' => $is_payslip_not_created]);
               }
               else
               {
                    return response()->json(['status' => 500, "message" => __('Payslip Already Generated.')]);
               }
          }
          catch (Exception | Throwable $ex)
          {
               return response()->json(['status' => 500, 'message' => showExceptionMessage($ex)]);
          }
     }

     public function destroy($id)
     {
          $payslip = PaySlip::find($id);
          $payslip->delete();
          return true;
     }

     public function showemployee($paySlip)
     {
          $payslip = PaySlip::find($paySlip);
          return view('payslip.show', compact('payslip'));
     }

     public function search_json(Request $request)
     {
          $formate_month_year = $request->datePicker;
          $employee_id = $request->employee_id ?? 0;

          $validatePaysilp = [];
          if ($employee_id > 0)
          {
               $validatePaysilp    = PaySlip::where('salary_month', '=', $formate_month_year)->where("employee_id", $employee_id)->where('created_by', Auth::user()->creatorId())->get()->toarray();
          }
          else
          {
               $validatePaysilp    = PaySlip::where('salary_month', '=', $formate_month_year)->where('created_by', Auth::user()->creatorId())->get()->toarray();
          }
          $data = [];
          if (empty($validatePaysilp))
          {
               $data = [];

               return;
          }
          else
          {

               if (Auth::user()->type == 'employee')
               {
                    $paylip_employee = PaySlip::where('pay_slips.status', 1)->select(
                         [
                              'employees.id',
                              'employees.employee_id',
                              'employees.name',
                              'employees.salary',
                              'payslip_types.name as payroll_type',
                              'pay_slips.basic_salary',

                              'pay_slips.net_payble',
                              'pay_slips.id as pay_slip_id',
                              'pay_slips.status',
                              'employees.user_id',
                         ]
                    )->leftjoin(
                         'employees',
                         function ($join) use ($formate_month_year)
                         {
                              $join->on('employees.id', '=', 'pay_slips.employee_id');
                              $join->on('pay_slips.salary_month', '=', \DB::raw("'" . $formate_month_year . "'"));
                              $join->leftjoin('payslip_types', 'payslip_types.id', '=', 'employees.salary_type');
                         }
                    )->where('employees.created_by', \Auth::user()->creatorId());

                    if ($employee_id > 0)
                    {
                         $paylip_employee = $paylip_employee->where("employees.id", $employee_id)->get();
                    }
                    else
                    {
                         $paylip_employee = $paylip_employee->get();
                    }
               }
               else
               {
                    $paylip_employee = PaySlip::select(
                         [
                              'employees.id',
                              'employees.employee_id',
                              'employees.name',
                              'employees.salary',
                              'payslip_types.name as payroll_type',
                              'pay_slips.basic_salary',
                              'pay_slips.net_payble',
                              'pay_slips.id as pay_slip_id',
                              'pay_slips.status',
                              'employees.user_id',
                         ]
                    )->leftjoin(
                         'employees',
                         function ($join) use ($formate_month_year)
                         {
                              $join->on('employees.id', '=', 'pay_slips.employee_id');
                              $join->on('pay_slips.salary_month', '=', \DB::raw("'" . $formate_month_year . "'"));
                              $join->leftjoin('payslip_types', 'payslip_types.id', '=', 'employees.salary_type');
                         }
                    )
                         ->where('employees.created_by', \Auth::user()->creatorId());

                    if ($employee_id > 0)
                    {
                         $paylip_employee = $paylip_employee->where("employees.id", $employee_id)->get();
                    }
                    else
                    {
                         $paylip_employee = $paylip_employee->get();
                    }
               }

               // dd("Payroll Ids", implode(',', $paylip_employee->pluck('pay_slip_id')->toArray()));
               // dd("Employee Ids", implode(',', $paylip_employee->pluck('id')->toArray()));

               foreach ($paylip_employee as $employee)
               {
                    if (Auth::user()->type == 'employee')
                    {
                         $empdata = Employee::find($employee->id);

                         $playslapdata = PayslipType::find($empdata->play_slab_id);

                         if (Auth::user()->id == $employee->user_id)
                         {
                              $tmp   = [];
                              $tmp[] = $employee->id;
                              // $tmp[] = \Auth::user()->employeeIdFormat($employee->employee_id);
                              $tmp[] = $employee->employee_id;
                              $tmp[] = $employee->name;
                              // $tmp[] = $employee->payroll_type;
                              $tmp[] = !empty($playslapdata->name)  ?  $playslapdata->name : $employee->id;
                              // $tmp[] = $employee->pay_slip_id;
                              $tmp[] = !empty($employee->basic_salary) ? \Auth::user()->priceFormat($employee->salary) : '-';
                              $tmp[] = !empty($employee->net_payble) ? \Auth::user()->priceFormat($employee->net_payble) : '-';
                              if ($employee->status == 1)
                              {
                                   $tmp[] = 'Paid';
                              }
                              else
                              {
                                   $tmp[] = 'UnPaid';
                              }
                              $tmp[]  = !empty($employee->pay_slip_id) ? $employee->pay_slip_id : 0;
                              $tmp['url']  = route('employee.show', Crypt::encrypt($employee->id));
                              $data[] = $tmp;
                         }
                    }
                    else
                    {
                         $empdata = Employee::find($employee->id);
                         $playslapdata = PayslipType::find($empdata->play_slab_id);
                         $tmp   = [];
                         $tmp[] = $employee->id;
                         // $tmp[] = \Auth::user()->employeeIdFormat($employee->employee_id);
                         $tmp[] = $employee->employee_id;
                         $tmp[] = $employee->name;
                         // $tmp[] = $employee->payroll_type;
                         $tmp[] = !empty($playslapdata->name)  ?  $playslapdata->name : $employee->id;
                         $tmp[] = !empty($employee->basic_salary) ? \Auth::user()->priceFormat($employee->basic_salary) : '-';
                         $tmp[] = !empty($employee->net_payble) ? \Auth::user()->priceFormat($employee->net_payble) : '-';
                         if ($employee->status == 1)
                         {
                              $tmp[] = 'Paid';
                         }
                         else
                         {
                              $tmp[] = 'UnPaid';
                         }
                         $tmp[]  = !empty($employee->pay_slip_id) ? $employee->pay_slip_id : 0;
                         $tmp['url']  = route('employee.show', Crypt::encrypt($employee->id));
                         $data[] = $tmp;
                    }
               }
               return $data;
          }
     }

     public function paysalary($id, $date)
     {
          $employeePayslip = PaySlip::where('employee_id', '=', $id)->where('created_by', \Auth::user()->creatorId())->where('salary_month', '=', $date)->first();
          if (!empty($employeePayslip))
          {
               $employeePayslip->status = 1;
               $employeePayslip->save();
               return redirect()->route('payslip.index')->with('success', __('Payslip Payment successfully.'));
          }
          else
          {
               return redirect()->route('payslip.index')->with('error', __('Payslip Payment failed.'));
          }
     }
     public function bulk_pay_create($date)
     {
          $Employees       = PaySlip::where('salary_month', $date)->where('created_by', \Auth::user()->creatorId())->get();
          $unpaidEmployees = PaySlip::where('salary_month', $date)->where('created_by', \Auth::user()->creatorId())->where('status', '=', 0)->get();
          return view('payslip.bulkcreate', compact('Employees', 'unpaidEmployees', 'date'));
     }
     public function apply_leave_absent(Request $request)
     {
          $employeesData = json_decode($request->getContent(), true);
          if (isset($employeesData['employees']))
          {
               $employees = $employeesData['employees'];
               return view('payslip.apply-leave-absent', compact('employees'));
          }
          else
          {
               return response()->json(['error' => 'No employees data found.'], 403);
          }
     }
     public function apply_leave_absent_save(Request $request)
     {
          $leaveData = $request->all(); // Get all the request parameters
          $result = [];
          foreach ($leaveData as $key => $value)
          {
               if (preg_match('/leavetype_(\d+)_(\d{4}-\d{2}-\d{2})/', $key, $matches))
               {
                    $empId = $matches[1];
                    $date = $matches[2];

                    if (!isset($result[$empId]))
                    {
                         $result[$empId] = [];
                    }
                    $result[$empId][$date] = $value;
               }
          }

          foreach ($result as $key => $data)
          {
               foreach ($data as $date => $leave_type_id)
               {
                    $leave    = new LocalLeave();
                    $leave->employee_id = $key;

                    $leave->leave_type_id    = $leave_type_id;
                    $leave->applied_on       = date('Y-m-d');
                    $leave->start_date       = $date;
                    $leave->end_date         = $date;
                    $leave->total_leave_days = 1;
                    $leave->leave_reason     = "Absent";
                    $leave->remark           = "";
                    $leave->status           = 'Approved';
                    $leave->secound_leave_status = 'Pending';
                    $leave->leave_day        = 1;
                    $leave->created_by       = Auth::user()->creatorId();
                    $update_logs = json_decode($leave->update_logs ?? "", true);
                    $update_logs[] = [
                         "updated_by" => Auth::user()->id,
                         "updated_at" => Carbon::now()->format("d-m-Y h:i A"),
                    ];

                    $leave->update_logs = json_encode($update_logs);

                    $leave->save();

                    // leave balance operations
                    $response = employeeLeaveBalanceOperations($leave->id, $leave->status, 'Pending'); // this will add or deduct leave balance base on leave status
               }
          }

          return redirect()->route('payslip.index')->with('success', __('Leave applied successfully.'));
     }
     public function bulkpayment(Request $request, $date)
     {
          $unpaidEmployees = PaySlip::where('salary_month', $date)->where('created_by', \Auth::user()->creatorId())->where('status', '=', 0)->get();
          foreach ($unpaidEmployees as $employee)
          {
               $employee->status = 1;
               $employee->save();
          }
          return redirect()->route('payslip.index')->with('success', __('Payslip Bulk Payment successfully.'));
     }

     public function employeepayslip()
     {
          $employees = Employee::where(
               [
                    'user_id' => \Auth::user()->id,
               ]
          )->first();
          $payslip = PaySlip::where('employee_id', '=', $employees->id)->get();
          return view('payslip.employeepayslip', compact('payslip'));
     }

     public function pdf($id, $month)
     {
          $payslip  = PaySlip::where('employee_id', $id)->where('salary_month', $month)->where('created_by', \Auth::user()->creatorId())->first();

          if ($payslip != null)
          {
               $employee = Employee::find($payslip->employee_id);

               if ($payslip->pay_slip_data_type == 0)
               {
                    $payslipDetail = Utility::employeePayslipDetail($id, $payslip->play_slab_id, $payslip);
                    return view('payslip.new_pdf_salary_new', compact('payslip', 'employee', 'payslipDetail'));
               }
               else
               {
                    $user = Auth::user();
                    $leave_counts =  LeaveType::where('play_slab_id', $employee->play_slab_id)->where('created_by', Auth::user()->creatorId())->get();

                    $payslipDetail = json_decode($payslip->pay_slip_data);

                    $salaryCalculationStartDate = Carbon::parse($payslip->salary_month)->startOfMonth()->format('Y-m-d');
                    $salaryCalculationEndDate = Carbon::parse($payslip->salary_month)->endOfMonth()->format('Y-m-d');

                    try
                    {
                         $salaryCalculationStartDate = $payslipDetail->salaryCalculationStartDate;
                         $salaryCalculationEndDate = $payslipDetail->salaryCalculationEndDate;
                    }
                    catch (\Throwable $th)
                    {
                         $salaryCalculationStartDate = Carbon::parse($payslip->salary_month)->startOfMonth()->format('Y-m-d');
                         $salaryCalculationEndDate = Carbon::parse($payslip->salary_month)->endOfMonth()->format('Y-m-d');
                    }

                    $year = date('Y', strtotime($payslip->salary_month));
                    $month = date('m', strtotime($payslip->salary_month));

                    if (!empty($leave_counts))
                    {
                         foreach ($leave_counts as $row)
                         {
                              $userLeaveBalance = UserLeaveBalanceYear::where("leave_type_id", $row->id)->where("leaves_year", $year)->where("user_id", $employee->id)->sum("pending_leaves");
                              $row->used_leaved = $userLeaveBalance;

                              $applied_leaves = Leave::where('leave_type_id', $row->id)->where('employee_id', $employee->id)
                                   // ->whereYear("start_date", $year)
                                   // ->whereMonth("start_date", $month)

                                   ->whereDate('start_date', '>=', $salaryCalculationStartDate)
                                   ->whereDate('end_date', '<=', $salaryCalculationEndDate)

                                   ->where('status', 'Approved')
                                   ->sum('total_leave_days');
                              $row->applied_leaves = $applied_leaves;
                         }
                    }


                    return view('payslip.salary-slip', compact('payslip', 'employee', 'payslipDetail', 'leave_counts'));
               }
          }
          else
          {
               return redirect()->back()->with('error', __('Payslip not found.'));
          }
     }


     public function pdf_api($id, $month)
     {
          $payslip  = PaySlip::where('employee_id', $id)->where('salary_month', $month)->first();
          if ($payslip != null)
          {
               $employee = Employee::find($payslip->employee_id ?? 0);

               $user = Auth::user();
               //$leave_counts =  LeaveType::where('play_slab_id', $employee->play_slab_id)->where('created_by', Auth::user()->creatorId())->get();
               $leave_counts =  LeaveType::where('play_slab_id', $employee->play_slab_id)->where('created_by', $id)->get();
               $year = date('Y', strtotime($payslip->salary_month));
               $month = date('m', strtotime($payslip->salary_month));

               if (!empty($leave_counts))
               {
                    foreach ($leave_counts as $row)
                    {
                         $userLeaveBalance = UserLeaveBalanceYear::where("leave_type_id", $row->id)->where("leaves_year", $year)->where("user_id", $employee->id)->sum("pending_leaves");
                         $row->used_leaved = $userLeaveBalance;

                         $applied_leaves = Leave::where('leave_type_id', $row->id)->where('employee_id', $employee->id)
                              ->whereYear("start_date", $year)
                              ->whereMonth("start_date", $month)
                              ->where('status', 'Approved')
                              ->sum('total_leave_days');
                         $row->applied_leaves = $applied_leaves;
                    }
               }

               $payslipDetail = json_decode($payslip->pay_slip_data);

               $pdf = Pdf::loadView('payslip.salary-slip-pdf', compact('payslip', 'employee', 'payslipDetail', 'leave_counts'))->setPaper('a4');
               return $pdf->stream("Salary Slip of $month.pdf");
               return $pdf->download("Salary Slip of $month.pdf");
          }
          else
          {
               return ApiResponse(["status" => 0, "message" => "Payslip Not Found"]);
          }



          // $options = new Options();
          // $options->set('isHtml5ParserEnabled', true);
          // $options->set('isPhpEnabled', true);

          // // Set the font configuration
          // $options->set('defaultFont', 'DejaVuSans');

          // $dompdf = new Dompdf($options);

          // $data = [
          //      "payslip" => $payslip,
          //      "employee" => $employee,
          //      "payslipDetail" => $payslipDetail,
          //      "companyID" => $companyID,
          // ];

          // $view = view("payslip.new_pdf_salary_new", $data);

          // $dompdf->loadHtml($view->render());

          // $dompdf->setPaper('A4', 'portrait');

          // $dompdf->render();

          // return $dompdf->stream('example.pdf');


          // return view('payslip.new_pdf_salary_new', compact('payslip', 'employee', 'payslipDetail', 'companyID'));
     }
     public function send($id, $month)
     {
          $payslip  = PaySlip::where('employee_id', $id)->where('salary_month', $month)->where('created_by', \Auth::user()->creatorId())->first();
          $employee = Employee::find($payslip->employee_id);
          $payslip->name  = $employee->name;
          $payslip->email = $employee->email;
          $payslipId    = Crypt::encrypt($payslip->id);
          $payslip->url = route('payslip.payslipPdf', $payslipId);
          $setings = Utility::settings();
          if ($setings['new_payroll'] == 1)
          {
               $uArr = [
                    'payslip_email' => $payslip->email,
                    'name'  => $payslip->name,
                    'url' => $payslip->url,
                    'salary_month' => $payslip->salary_month,
               ];
               $resp = Utility::sendEmailTemplate('new_payroll', [$payslip->email], $uArr);
               // dd($resp);
               return redirect()->back()->with('success', __('Payslip successfully sent.')  . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
          }
          return redirect()->back()->with('success', __('Payslip successfully sent.'));
     }
     public function payslipPdf($id)
     {
          $payslipId = Crypt::decrypt($id);
          $payslip  = PaySlip::where('id', $payslipId)->where('created_by', \Auth::user()->creatorId())->first();
          $employee = Employee::find($payslip->employee_id);
          $payslipDetail = Utility::employeePayslipDetail($payslip->employee_id, $payslip->play_slab_id);
          return view('payslip.payslipPdf', compact('payslip', 'employee', 'payslipDetail'));
     }

     public function editEmployee($paySlip)
     {
          $payslip = PaySlip::find($paySlip);

          $payslipDetail = json_decode($payslip->pay_slip_data);

          $employee = Employee::find($payslip->employee_id);

          return view('payslip.salaryEdit', compact('payslip', 'payslipDetail', 'employee'));
     }

     public function updateEmployee(Request $request, $id)
     {
          if (isset($request->allowance) && !empty($request->allowance))
          {
               $allowances   = $request->allowance;
               $allowanceIds = $request->allowance_id;
               foreach ($allowances as $k => $allownace)
               {
                    $allowanceData         = Allowance::find($allowanceIds[$k]);
                    $allowanceData->amount = $allownace;
                    $allowanceData->save();
               }
          }
          if (isset($request->commission) && !empty($request->commission))
          {
               $commissions   = $request->commission;
               $commissionIds = $request->commission_id;
               foreach ($commissions as $k => $commission)
               {
                    $commissionData         = Commission::find($commissionIds[$k]);
                    $commissionData->amount = $commission;
                    $commissionData->save();
               }
          }
          if (isset($request->loan) && !empty($request->loan))
          {
               $loans   = $request->loan;
               $loanIds = $request->loan_id;
               foreach ($loans as $k => $loan)
               {
                    $loanData         = Loan::find($loanIds[$k]);
                    $loanData->amount = $loan;
                    $loanData->save();
               }
          }
          if (isset($request->saturation_deductions) && !empty($request->saturation_deductions))
          {
               $saturation_deductionss   = $request->saturation_deductions;
               $saturation_deductionsIds = $request->saturation_deductions_id;
               foreach ($saturation_deductionss as $k => $saturation_deductions)
               {
                    $saturation_deductionsData         = SaturationDeduction::find($saturation_deductionsIds[$k]);
                    $saturation_deductionsData->amount = $saturation_deductions;
                    $saturation_deductionsData->save();
               }
          }
          if (isset($request->other_payment) && !empty($request->other_payment))
          {
               $other_payments   = $request->other_payment;
               $other_paymentIds = $request->other_payment_id;
               foreach ($other_payments as $k => $other_payment)
               {
                    $other_paymentData         = OtherPayment::find($other_paymentIds[$k]);
                    $other_paymentData->amount = $other_payment;
                    $other_paymentData->save();
               }
          }
          if (isset($request->rate) && !empty($request->rate))
          {
               $rates   = $request->rate;
               $rateIds = $request->rate_id;
               $hourses = $request->hours;
               foreach ($rates as $k => $rate)
               {
                    $overtime        = Overtime::find($rateIds[$k]);
                    $overtime->rate  = $rate;
                    $overtime->hours = $hourses[$k];
                    $overtime->save();
               }
          }
          $payslipEmployee                       = PaySlip::find($request->payslip_id);
          $payslipEmployee->allowance            = Employee::allowance($payslipEmployee->employee_id);
          $payslipEmployee->commission           = Employee::commission($payslipEmployee->employee_id);
          $payslipEmployee->loan                 = Employee::loan($payslipEmployee->employee_id);
          $payslipEmployee->saturation_deduction = Employee::saturation_deduction($payslipEmployee->employee_id);
          $payslipEmployee->other_payment        = Employee::other_payment($payslipEmployee->employee_id);
          $payslipEmployee->overtime             = Employee::overtime($payslipEmployee->employee_id);
          $payslipEmployee->net_payble           = Employee::find($payslipEmployee->employee_id)->get_net_salary();
          $payslipEmployee->save();
          return redirect()->route('payslip.index')->with('success', __('Employee payroll successfully updated.'));
     }

     public function PayslipExport(Request $request)
     {
          $name = 'payslip_' . date('Y-m-d i:h:s');
          $data = \Excel::download(new PayslipExport($request), $name . '.xlsx');
          // ob_end_clean();
          return $data;
     }

     public function recalculateLeaveBalance(Request $request)
     {
          $leaveIds = [
               13586,
               13623,
               13626,
               13677,
               // 13471,
               13671,
               13571,
               13579,
               13622,
               13617,
               13647,
               13609,
               13533,
               13638,
               13649,
               13628,
               13657,
               13655,
               13618,
               13580,
               13632,
               13661,
               // 13592,
               13604,
               13601,
               13634,
               13664,
               13637,
               13595,
               13620,
               13621,
               13644,
               13673,
               13614,
               13624,
               13619,
               13606,
               13672,
               13705,
               13706,
               13707,
               13708,
               13709,
               13710,
               13711,
               13712,
               13713,
               13714,
               13715,
               13716,
               13717,
               13718,
               13719,
               13720,
               13653,
               13572,
               13663,
               13652,
               13567,
               13568,
               13587,
               13610,
               13611,
               13612,
               13662,
               13575,
               13564,
               13665,
               13561,
               13670,
               13409,
               13627,
               13669,
               13654,
               13659,
               13570,
               13646,
               13585,
               13651,
               13519,
               13588,
               13603,
               13721,
               13576,
               13590,
               13607,
               13678,
               13589,
               13631,
               13608,
               13569,
               13691,
               13645
          ];

          // $leaveIds = [13533];

          $leaveData = Leave::whereIn("id", $leaveIds)
               ->where("deducted_leave_balance", "<=", 0)
               // ->where("deducted_leave_balance", ">", 0)
               // ->where("extra_leave_days", ">", 0)
               ->get();

          DB::beginTransaction();
          foreach ($leaveData as $leave)
          {
               // $employee = Employee::find($leave->employee_id);
               $result = employeeLeaveBalanceOperations($leave->id, "Approved", "Approved", $leave->created_by);
               // $result = employeeLeaveBalanceOperations($leave->id, "Reject", "Approved", $leave->created_by);
               // dd($result);
          }
          // dd("out");
          DB::commit();
          return ApiResponse(["status" => 1, "message" => "Leave balance recalculated successfully"]);
     }
}
