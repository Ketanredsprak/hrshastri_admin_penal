@php

    $logo = \App\Models\Utility::get_file('uploads/logo/');

    $company_logo = Utility::get_company_logo_without_auth($employee->created_by);

    $Designation = App\Models\Designation::find($employee->designation_id);

    $departments = App\Models\Department::find($employee->department_id);

    $employeesId = employeeIdFormat($employee->id);

    // Initial values
    //     $initial_amount = 3000;
    //     $initial_percentage = 20;

    //     // Function to calculate the new percentage
    //     function calculate_percentage($new_amount, $initial_amount, $initial_percentage)
    //     {
    //         // Calculate the base value
    //         $base_value = $initial_amount / ($initial_percentage / 100);

    //         // Calculate the new percentage
    //         $new_percentage = ($new_amount / $base_value) * 100;

    //         return $new_percentage;
    //     }

    //     // Example usage
    //     $new_amount = 4500;
    //     $new_percentage = calculate_percentage($new_amount, $initial_amount, $initial_percentage);

    //     print_r($payslip->pay_slip_data);

    //     print_r($payslipDetail);
    //     exit();

@endphp

<style>
    .table tbody tr:last-child {
        border-bottom: 1px solid #000;
        font-weight: bold;
    }
</style>

<div class="col-form-label" width="100%">
    <div class="row px-3">

        <div class="col-md-12 mb-3 text-center">
            <h5 class="emp-title mb-0">{{ __('Payslip') }}</h5>
            <h5 class="emp-title black-text">
                {{ __('Payment slip for the month of ') }}{{ date('F-Y', strtotime($payslip->salary_month)) }}
            </h5>
        </div>

        <div class="col-md-4 mb-3">
            <h5 class="emp-title mb-0">{{ __('Employee Id') }}</h5>
            <h5 class="emp-title black-text">{{ $payslipDetail->employeeId }}</h5>
        </div>

        <div class="col-md-4 mb-3">
            <h5 class="emp-title mb-0">{{ __('Name') }}</h5>
            <h5 class="emp-title black-text">{{ $payslipDetail->employeeName }}</h5>
        </div>

        <div class="col-md-4 mb-3">
            <h5 class="emp-title mb-0">{{ __('Designation') }}</h5>
            <h5 class="emp-title black-text">{{ $payslipDetail->employeeDesignation }}</h5>
        </div>

        <div class="col-md-4 mb-3">
            <h5 class="emp-title mb-0">{{ __('Department') }}</h5>
            <h5 class="emp-title black-text">{{ $payslipDetail->employeeDepartment }}</h5>
        </div>

        <div class="col-md-4 mb-3">
            <h5 class="emp-title mb-0">{{ __('Salary Date') }}</h5>
            <h5 class="emp-title black-text">{{ dateFormat($payslip->created_at) }}</h5>
        </div>

        {{-- Earnings --}}
        <div class="col-sm-6">
            <table class="table" id="earnings-table">
                <thead>
                    <h5 class="text-center">Earnings</h5>
                    <tr>
                        <th width="50%" valign="middle"
                            style="width:50%; text-align:left; padding:8px 10px; font-weight:bold;">
                            Description</th>

                        <th width="50%" valign="middle"
                            style="width:50%; text-align:right; padding:8px 10px; font-weight:bold;">
                            Amount</th>
                    </tr>
                </thead>
                <tbody>

                    {{-- Basic Salary --}}
                    <tr>
                        <td width="50%" align="left" valign="middle"
                            style="width:50%; text-align:left; padding:8px 10px;">
                            Basic Salary</td>

                        <td width="50%" align="right" valign="middle"
                            style="width:50%; text-align:right; padding:8px 10px;" id="basicSalary">
                            {{ priceFormat($payslipDetail->employeeBasicSalary) }}
                        </td>
                    </tr>
                    {{-- Basic Salary --}}

                    {{-- Earning Rows --}}

                    @foreach ($payslipDetail->earnings as $earningName => $earnings)
                        @if (is_object($earnings))
                            @foreach ($earnings->data as $earnongRow)
                                <tr>
                                    <td width="50%" align="left" valign="middle"
                                        style="width:50%; text-align:left; padding:8px 10px;">
                                        <label for="{{ $earnongRow->id }}">{{ $earnongRow->name }}</label>
                                    </td>

                                    @php
                                        $amoutValue = '';

                                        if ($earnongRow->type == 'percentage') {
                                            $amoutValue =
                                                "$earnongRow->percentage % (" . priceFormat($earnongRow->amount) . ')';
                                        } else {
                                            $amoutValue = priceFormat($earnongRow->amount);
                                        }
                                    @endphp
                                    <td width="50%" align="right" valign="middle"
                                        style="width:50%; text-align:right; padding:8px 10px;">

                                        <input type="text" class="form-control earning-row salaryAmountValidation"
                                            id="{{ $earnongRow->id }}" earning-data='{!! json_encode($earnongRow) !!}'
                                            earning-percentage="{{ $earnongRow->type }}"
                                            value="{{ $earnongRow->amount }}">

                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        {{-- @endif --}}
                    @endforeach
                    {{-- Earning Rows --}}

                    {{-- total --}}
                    <tr>
                        <td align="left" valign="middle"
                            style="text-align:left; padding:8px 10px; border-bottom:1px solid #000000;font-weight:bold;">
                            Gross Earning</td>

                        <td align="right" valign="middle"
                            style="width:100px; text-align:right; padding:8px 10px; border-bottom:1px solid #000000;font-weight:bold;">
                            {{ priceFormat($payslipDetail->employeeBasicSalary + $payslipDetail->totalEarnings) }}

                        </td>
                    </tr>
                    {{-- total --}}

                </tbody>
            </table>
        </div>
        {{-- Earnings --}}


        {{-- Deductions --}}
        <div class="col-sm-6" id="deductions-table">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <h5 class="text-center">Deductions</h5>
                    <tr>
                        <th width="50%" align="left" valign="middle"
                            style="width:50%; text-align:left; padding:8px 10px; font-weight:bold;">
                            Description</th>

                        <th width="50%" align="right" valign="middle"
                            style="width:50%; text-align:right; padding:8px 10px; font-weight:bold;">
                            Amount</th>
                    </tr>
                </thead>

                <tbody>

                    {{-- Deductions Rows --}}

                    @foreach ($payslipDetail->deductions as $deductionName => $deductions)
                        @if (is_object($deductions))
                            @foreach ($deductions->data as $deductionsRow)
                                <tr>
                                    <td width="50%" valign="middle"
                                        style="width:50%; text-align:left; padding:8px 10px;">
                                        {{ $deductionsRow->name }}</td>

                                    @php
                                        $amoutValue = '';

                                        if ($deductionsRow->type == 'percentage') {
                                            $amoutValue =
                                                "$deductionsRow->percentage % (" .
                                                priceFormat($deductionsRow->amount) .
                                                ')';
                                        } else {
                                            $amoutValue = priceFormat($deductionsRow->amount);
                                        }
                                    @endphp
                                    <td width="50%" valign="middle"
                                        style="width:50%; text-align:right; padding:8px 10px;">
                                        {{ $amoutValue }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Total --}}
                    <tr>
                        <td align="left" valign="middle"
                            style="text-align:left; padding:8px 10px; border-bottom:1px solid #000000;font-weight:bold;">
                            Gross Deduction</td>

                        <td align="right" valign="middle"
                            style="width:100px; text-align:right; padding:8px 10px; border-bottom:1px solid #000000;font-weight:bold;">
                            {{ priceFormat($payslipDetail->totalDeductions) }}
                        </td>

                    </tr>
                    {{-- Total --}}

                    {{-- Deductions Rows --}}
                </tbody>
            </table>
        </div>
        {{-- Deductions --}}


        <div class="col-lg-12 our-system">
            {{ Form::open(['route' => ['payslip.updateemployee', $payslip->employee_id], 'method' => 'post']) }}
            {!! Form::hidden('payslip_id', $payslip->id, ['class' => 'form-control']) !!}
            <div class="row">

                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#allowance"
                            role="tab" aria-controls="pills-home" aria-selected="true">{{ __('Allowance') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#commission"
                            role="tab" aria-controls="pills-profile"
                            aria-selected="false">{{ __('Commission') }}</a>
                    </li>
                </ul>

                <div class="tab-content pt-4">

                    <div id="allowance" class="tab-pane in active">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card bg-none mb-0">
                                    <div class="row px-3">
                                        @php
                                            $allowances = json_decode($payslip->allowance) ?? [];
                                        @endphp
                                        @foreach ($allowances as $allownace)
                                            <div class="col-md-12 form-group">
                                                {!! Form::label('title', $allownace->title, ['class' => 'col-form-label']) !!}
                                                {!! Form::text('allowance[]', $allownace->amount, ['class' => 'form-control']) !!}
                                                {!! Form::hidden('allowance_id[]', $allownace->id, ['class' => 'form-control']) !!}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="commission" class="tab-pane">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card bg-none mb-0">
                                    <div class="row px-3">
                                        @php
                                            $commissions = json_decode($payslip->commission) ?? [];
                                        @endphp
                                        @foreach ($commissions as $commission)
                                            <div class="col-md-12 form-group">
                                                {!! Form::label('title', $commission->title, ['class' => 'col-form-label']) !!}
                                                {!! Form::text('commission[]', $commission->amount, ['class' => 'form-control']) !!}
                                                {!! Form::hidden('commission_id[]', $commission->id, ['class' => 'form-control']) !!}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-4 text-right">

                <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
                <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
            </div>
            {{ Form::close() }}
        </div>

    </div>



</div>



<script>
    $(document).ready(function() {

        var basicSalary = {{ $payslipDetail->employeeBasicSalary }};
        console.log('✌️basicSalary --->', basicSalary);


        $('.salaryAmountValidation').on('input keypress keyup paste dragover', function(e) {
            // Check if the entered value is a valid number
            var inputValue = $(this).val() + String.fromCharCode(e.which);
            if (!$.isNumeric(inputValue)) {
                // Prevent the input if it's not a number
                e.preventDefault();
            }
            calculateSalary();
        });


        function calculateSalary() {

            var totalEarningAmount = 0; // Initialize total sum variable

            $('.earning-row').each(function() {
                // Get the value of the input field and convert it to a float (or default to 0 if empty)
                var earningAmount = parseFloat($(this).val()) || 0;

                // Add the earningAmount to the total
                totalEarningAmount += earningAmount;

                // Get the 'earning-data' attribute value and parse it as JSON
                var earningDataJson = $(this).attr('earning-data');
                var earningData = JSON.parse(earningDataJson.replace(/&quot;/g, '"'));

                // Log the earningAmount and earning data
                //  console.log('Earning Amount:', earningAmount);
                //  console.log('Earning Data:', earningData);
            });

            // Format totalEarningAmount to 2 decimal points
            var finalTotalSalaryToPay = (basicSalary + totalEarningAmount).toFixed(2);

            // Log the formatted total
            console.log('Total Earning Amount:', totalEarningAmount.toFixed(2));
            console.log('Total Total Salary To Pay:', finalTotalSalaryToPay);

        }
    });
</script>
