@extends('layouts.admin')

<style>
     .text-right {
          text-align: right;
     }

     .text-center {
          text-align: center;
     }
</style>

@section('page-title')

{{ __('Wage Reports') }}

@endsection

@section('breadcrumb')

<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>

<li class="breadcrumb-item">{{ __('Wage Reports') }}</li>

@endsection

@section('action-button')

@can('Create Promotion')

{{-- <a href="{{ route('wagereports_export')}}" class="btn btn-success btn-sm">Excel </a> --}}

@endcan

@endsection

@section('content')

<div class="row">

     <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
          <div class=" mt-2 " id="" style="">
               <div class="card">
                    <div class="card-body">
                         {{ Form::open(['route' => ['wage_reports_index'], 'method' => 'get']) }}

                         <div class="row align-items-center">
                              <div class="col-sm-10">
                                   <div class="row ">
                                        <div class="col-sm-6 col-lg-3">
                                             <div class="btn-box">
                                                  {{ Form::label('month', __(' Month'), ['class' => 'form-label']) }}
                                                  {{ Form::month('month', $requestMonth, ['class' => 'month-btn form-control current_date', 'autocomplete' => 'off', 'placeholder' => 'Select month']) }}
                                             </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-3" style="display: {{Auth::user()->type == "employee" ? 'none' : ''}}">
                                             <div class="btn-box">
                                                  {{ Form::label('paySlabTypeId', __('Pay Slab'), ['class' => 'form-label']) }}
                                                  {{ Form::select('paySlabTypeId', $paySlabTypes, $requestPaySlabTypeId ?? 0, ['class' => 'form-control select2Element paySlabTypeId', 'id' => 'paySlabTypeId']) }}
                                             </div>
                                        </div>
                                        <div class="col-sm-6 col-lg-3">
                                             <div class="btn-box">
                                                  {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                                                  {{ Form::select('status', $statusArray, $requestStatus ?? 1, ['class' => 'form-control select2Element status', 'id' => 'status']) }}
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="col-sm-2 text-right pt-4">
                                   <button type="submit" name="btn_search" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="" data-bs-original-title="apply" value="0">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                   </button>

                                   <button type="submit" name="btn_reset" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="" data-bs-original-title="Reset" value="1">
                                        <span class="btn-inner--icon"><i class="ti ti-refresh  text-white-off "></i></span>
                                   </button>

                                   <button type="submit" name="btn_export" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="" data-bs-original-title="Export" value="2">
                                        <span class="btn-inner--icon"><i class="ti ti-file-export text-white-off "></i></span>
                                   </button>
                              </div>
                         </div>

                         {{ Form::close() }}
                    </div>
               </div>
          </div>
     </div>

     <div class="col-xl-12">

          <div class="card">

               <div class="card-header card-body table-border-style">

                    <div class="table-responsive">

                         <table class="table">

                              <thead>

                                   <tr>

                                        <th class="text-center">{{ __('Sr.no') }}</th>
                                        @if ($requestPaySlabTypeId == 0)
                                        <th class="text-center">{{ __('Pay Slab') }}</th>
                                        @endif

                                        <th class="text-center">{{ __('EMP Code') }}</th>
                                        <th class="text-center">{{ __('Name') }}</th>
                                        <th class="text-center">{{ __('Designation') }}</th>
                                        <th class="text-center">{{ __('Department') }}</th>

                                        <th class="text-center">{{ __('Basic Salary') }}</th>

                                        {{-- Earnings Columns --}}

                                        {{-- allowances --}}
                                        @foreach ($allowances as $allowancesRow)
                                        <th class="text-center">{{ $allowancesRow->name }}</th>
                                        @endforeach
                                        {{-- allowances --}}

                                        {{-- commissions --}}
                                        @foreach ($commissions as $commissionsRow)
                                        <th class="text-center">{{ $commissionsRow->name }}</th>
                                        @endforeach
                                        {{-- commissions --}}

                                        {{-- other_payments --}}
                                        @foreach ($other_payments as $other_paymentsRow)
                                        <th class="text-center">{{ $other_paymentsRow->name }}</th>
                                        @endforeach
                                        {{-- other_payments --}}

                                        {{-- Earnings Columns --}}

                                        <th class="text-center">Gross Earning</th>

                                        {{-- Deductions Columns --}}
                                        {{-- saturation_deductions --}}
                                        @foreach ($saturation_deductions as $saturation_deductionsRow)
                                        <th class="text-center">{{ $saturation_deductionsRow->name }}</th>
                                        @endforeach
                                        {{-- saturation_deductions --}}

                                        {{-- other deductions like pf,esis --}}
                                        <th class="text-center">PF</th>
                                        <th class="text-center">ESIC</th>
                                        {{-- other deductions like pf,esis --}}


                                        {{-- loan amount --}}
                                        <th class="text-center">Loan Amount</th>
                                        {{-- loan amount --}}

                                        {{-- leave amount --}}
                                        <th class="text-center">Leave Amount</th>
                                        {{-- leave amount --}}

                                        {{-- Deductions Columns --}}


                                        <th class="text-center">Gross Deduction</th>
                                        <th class="text-center">Net Pay</th>

                                        @if ($requestStatus == -1)
                                        <th class="text-center">Status</th>
                                        @endif
                                   </tr>

                              </thead>

                              <tbody>

                                   <?php $srNo = 1; ?>
                                   @foreach ($employeesPayrollData as $employeesPayrollRow)

                                   <tr>

                                        <td class="text-center">{{ $srNo}}</td>

                                        @if ($requestPaySlabTypeId == 0)
                                        <td class="text-center">{{ $employeesPayrollRow['pay_slap_type'] }}</td>
                                        @endif

                                        <td class="text-center">{{ $employeesPayrollRow['employee_id'] }}</td>
                                        <td class="text-center">{{ $employeesPayrollRow['name'] }}</td>
                                        <td class="text-center">{{ $employeesPayrollRow['designation'] }}</td>
                                        <td class="text-center">{{ $employeesPayrollRow['department'] }}</td>

                                        <td class="text-right">{{ $employeesPayrollRow['basic_salary'] }}</td>

                                        {{-- Earnings Columns --}}

                                        {{-- Allowance --}}
                                        @foreach (($employeesPayrollRow['allowances'] ?? []) as $payrollAllowancesRow)
                                        <td class="text-right">{{ $payrollAllowancesRow }}</td>
                                        @endforeach
                                        {{-- Allowance --}}

                                        {{-- commission --}}
                                        @foreach (($employeesPayrollRow['commission'] ?? []) as $payrollcommissionsRow)
                                        <td class="text-right">{{ $payrollcommissionsRow }}</td>
                                        @endforeach
                                        {{-- commission --}}

                                        {{-- otherPayment --}}
                                        @foreach (($employeesPayrollRow['otherPayment'] ?? []) as $payrollotherPaymentRow)
                                        <td class="text-right">{{ $payrollotherPaymentRow }}</td>
                                        @endforeach
                                        {{-- otherPayment --}}

                                        {{-- Earnings Columns --}}

                                        {{-- Gross Earnings --}}
                                        <td class="text-right">{{ $employeesPayrollRow['grossEarnings'] }}</td>
                                        {{-- Gross Earnings --}}


                                        {{-- Deduction Columns --}}

                                        {{-- deduction --}}
                                        @foreach (($employeesPayrollRow['deduction'] ?? []) as $payrolldeductionRow)
                                        <td class="text-right">{{ $payrolldeductionRow }}</td>
                                        @endforeach
                                        {{-- deduction --}}

                                        {{-- other deductions --}}
                                        <td class="text-right">{{ $employeesPayrollRow['pf'] }}</td>
                                        <td class="text-right">{{ $employeesPayrollRow['esic'] }}</td>
                                        {{-- other deductions --}}


                                        {{-- Loan Amount --}}
                                        <td class="text-right">{{ $employeesPayrollRow['loanAmount'] }}</td>
                                        {{-- Loan Amount --}}

                                        {{-- Loan Amount --}}
                                        <td class="text-right">{{ $employeesPayrollRow['leaveAmount'] }}</td>
                                        {{-- Loan Amount --}}

                                        {{-- Deduction Columns --}}



                                        {{-- Gross Desuctions --}}
                                        <td class="text-right">{{ $employeesPayrollRow['grossDeductions'] }}</td>
                                        {{-- Gross Desuctions --}}

                                        {{-- netPayableSalary --}}
                                        <td class="text-right">{{ $employeesPayrollRow['netPayableSalary'] }}</td>
                                        {{-- netPayableSalary --}}

                                        {{-- status --}}
                                        @if ($requestStatus == -1)
                                        <td class="text-center">{{ $employeesPayrollRow['paySlipStatus'] }}</td>
                                        @endif
                                        {{-- status --}}

                                   </tr>

                                   <?php $srNo++; ?>

                                   @endforeach

                              </tbody>

                         </table>

                    </div>

               </div>

          </div>

     </div>

</div>

@endsection


<script>
     $('.select2Element').select2();
</script>