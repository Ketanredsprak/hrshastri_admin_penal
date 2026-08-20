<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Exports\PayslipExport;
use App\Models\Allowance;
use App\Models\Commission;
use App\Models\Employee;
use App\Models\Loan;
use App\Mail\InvoiceSend;
use App\Mail\PayslipSend;
use App\Models\OtherPayment;
use App\Models\Overtime;
use App\Models\PaySlip;
use App\Models\SaturationDeduction;
use App\Models\Utility;
use App\Models\Leave;
use App\Models\PayslipType;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Throwable;

class MySlipController extends Controller
{
     public function my_slip(request $request)
     {
          $response = [];

          try
          {
               $employeeData = Employee::where("user_id", Auth::user()->id)->first();
               if (Auth::user()->type == 'employee' && $employeeData != null)
               {
                    $year = $request->datePicker;

                    $payslipData = PaySlip::select('*', 'id as pay_slip_download_link', 'pay_slip_data as pay_slip_raw_data', 'status as pay_slip_status', 'play_slab_id as pay_slab_name')
                         ->where('employee_id', $employeeData->id)
                         ->where(DB::raw("SUBSTRING(salary_month, 1, 4)"), '=', DB::raw("'" . $year . "'"))
                         ->get();

                    $response = [
                         'status'  => 1,
                         'message' => 'Payslip List',
                         'data'    => $payslipData,
                    ];
               }
               else
               {
                    $response = [
                         'status'  => 0,
                         'message' => 'Invalid User',
                         'data'    => [],
                    ];
               }
          }
          catch (Exception | Throwable $e)
          {
               $response = error_msg(showExceptionMessage($e));
          }

          return ApiResponse($response);
     }

     public function my_slip_old(request $request)
     {
          // return $this->employeePayslipList($request);
          $formate_month_year = $request->datePicker;


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
                         'pay_slips.salary_month',
                         'pay_slips.pay_slip_data',
                         'employees.user_id',
                    ]
               )->leftjoin(
                    'employees',
                    function ($join) use ($formate_month_year)
                    {
                         $join->on('employees.id', '=', 'pay_slips.employee_id');
                         // $join->on('pay_slips.salary_month', '=', \DB::raw("'" . $formate_month_year . "'"));
                         $join->on(\DB::raw("SUBSTRING(pay_slips.salary_month, 1, 4)"), '=', \DB::raw("'" . $formate_month_year . "'"));
                         $join->leftjoin('payslip_types', 'payslip_types.id', '=', 'employees.salary_type');
                    }
               )->where('employees.created_by', \Auth::user()->creatorId())->get();
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
                         'pay_slips.salary_month',
                         'pay_slips.pay_slip_data',
                         'employees.user_id',
                    ]
               )->leftjoin(
                    'employees',
                    function ($join) use ($formate_month_year)
                    {
                         $join->on('employees.id', '=', 'pay_slips.employee_id');
                         // $join->on('pay_slips.salary_month', '=', \DB::raw("'" . $formate_month_year . "'"));
                         $join->on(\DB::raw("SUBSTRING(pay_slips.salary_month, 1, 4)"), '=', \DB::raw("'" . $formate_month_year . "'"));
                         $join->leftjoin('payslip_types', 'payslip_types.id', '=', 'employees.salary_type');
                    }
               )->where('employees.created_by', \Auth::user()->creatorId())->get();
          }

          foreach ($paylip_employee as $employee)
          {
               if (Auth::user()->type == 'employee')
               {
                    $empdata = Employee::find($employee->id);
                    $playslapdata = PayslipType::find($empdata->play_slab_id);
                    if (Auth::user()->id == $employee->user_id)
                    {
                         $tmp   = [];
                         $tmp['id'] = $employee->id;
                         $tmp['employee_code'] = \Auth::user()->employeeIdFormat($employee->employee_id);
                         $tmp['name'] = $employee->name;


                         $tmp['pay_slab'] = !empty($playslapdata->name)  ?  $playslapdata->name : $employee->id;
                         $tmp['basic_salary'] = !empty($employee->basic_salary) ? \Auth::user()->priceFormat($employee->salary) : '-';
                         $tmp['net_payble'] = !empty($employee->net_payble) ? \Auth::user()->priceFormat($employee->net_payble) : '-';
                         if ($employee->status == 1)
                         {
                              $tmp['status'] = 'Paid';
                         }
                         else
                         {
                              $tmp['status'] = 'UnPaid';
                         }
                         $tmp['pay_slip_id']  = !empty($employee->pay_slip_id) ? $employee->pay_slip_id : 0;
                         $tmp['url']  = route('employee.show', Crypt::encrypt($employee->id));
                         $tmp['my_slip_pdf']  = asset('payslip/pdf_api/' . $employee->id . '/' . $employee->salary_month);
                         $tmp['salary_month']  = $employee->salary_month;
                         $tmp['pay_slip_data']  = json_decode($employee->pay_slip_data);
                         $data[] = $tmp;
                    }
               }
          }

          if (!empty($data))
          {
               $response = [
                    'status'  => 1,
                    'message' => 'success',
                    'data' => $data,
               ];
          }
          else
          {
               $response = [
                    'status'  => 0,
                    'message' => 'Data not found',
                    'data' => []
               ];
          }
          return $response;
     }
}
