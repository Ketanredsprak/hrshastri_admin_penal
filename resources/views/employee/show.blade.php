@extends('layouts.admin')
@section('page-title')
    {{ __('Employee') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('employee') }}">{{ __('Employee') }}</a></li>
    <li class="breadcrumb-item">{{ __('Manage Employee') }}</li>
@endsection
@section('action-button')
    <div class="float-end">
        @can('edit employee')
            <a href="{{ route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}"
                data-bs-toggle="tooltip" title="{{ __('Edit') }}"class="btn btn-sm btn-primary">
                <i class="ti ti-pencil"></i>
            </a>
        @endcan
    </div>
    <div class="text-end mb-3">
        <div class="d-flex justify-content-end drp-languages">
            <ul class="list-unstyled mb-0 m-2">
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="drp-text hide-mob text-primary"> {{ __('Joining Letter') }}
                            <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">
                        <a href="{{ route('joiningletter.download.pdf', $employee->id) }}" class=" btn-icon dropdown-item"
                            data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                class="ti ti-download ">&nbsp;</i>{{ __('PDF') }}</a>
                        <a href="{{ route('joininglatter.download.doc', $employee->id) }}" class=" btn-icon dropdown-item"
                            data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                class="ti ti-download ">&nbsp;</i>{{ __('DOC') }}</a>
                    </div>
                </li>
            </ul>

            @can('Manage Report')
                <ul class="list-unstyled mb-0 m-2">
                    <li class="dropdown dash-h-item drp-language">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="drp-text hide-mob text-primary"> {{ __('Experience Certificate') }}
                                <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                        </a>
                        <div class="dropdown-menu dash-h-dropdown">
                            <a href="{{ route('exp.download.pdf', $employee->id) }}" class=" btn-icon dropdown-item"
                                data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                    class="ti ti-download ">&nbsp;</i>{{ __('PDF') }}</a>
                            <a href="{{ route('exp.download.doc', $employee->id) }}" class=" btn-icon dropdown-item"
                                data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                    class="ti ti-download ">&nbsp;</i>{{ __('DOC') }}</a>
                        </div>
                    </li>
                </ul>
                <ul class="list-unstyled mb-0 m-2">
                    <li class="dropdown dash-h-item drp-language">
                        <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="drp-text hide-mob text-primary"> {{ __('NOC') }}
                                <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                        </a>
                        <div class="dropdown-menu dash-h-dropdown">
                            <a href="{{ route('noc.download.pdf', $employee->id) }}" class=" btn-icon dropdown-item"
                                data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                    class="ti ti-download ">&nbsp;</i>{{ __('PDF') }}</a>
                            <a href="{{ route('noc.download.doc', $employee->id) }}" class=" btn-icon dropdown-item"
                                data-bs-toggle="tooltip" data-bs-placement="top" target="_blanks"><i
                                    class="ti ti-download ">&nbsp;</i>{{ __('DOC') }}</a>
                        </div>
                    </li>
                </ul>
            @endcan

        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="employ_dtl">
                        <div class="emploimg">

                            @php

                                $profile = \App\Models\Utility::get_file('uploads/avatar/');

                                $imagePath = '';

                                if (Auth::user()->type == 'company') {
                                    $imagePath = !empty($users->avatar)
                                        ? $profile . $users->avatar
                                        : $profile . 'avatar.png';
                                } else {
                                    if (empty($employee->employee_profile)) {
                                        // dd('if');
                                        $imagePath = $profile . 'avatar.png';
                                    } else {
                                        $imagePath = $employee->employee_profile_image;
                                        // dd('else', $imagePath);
                                    }
                                }
                            @endphp

                            <a href="{{ $imagePath }}" target="_blank">
                                <img src="{{ $imagePath }}" class="rounded-circle" style="width: 25%">
                                {{-- <a href="{{ !empty($employee->user->avatar) ? asset(Storage::url('uploads/avatar')) . '/' . $employee->user->avatar : asset(Storage::url('uploads/avatar')) . '/avatar.png' }}"
                                target="_blank">
                                <img src="{{ !empty($employee->user->avatar) ? asset(Storage::url('uploads/avatar')) . '/' . $employee->user->avatar : asset(Storage::url('uploads/avatar')) . '/avatar.png' }}"
                                    class="rounded-circle" style="width: 25%"> --}}
                            </a>
                        </div>
                        <div class="employctn">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <h5 class="mt-2"> {{ $employee->name }}</h5>
                                    <p> {{ $employee->employee_id }}</p>
                                    <a href="{{ route('employeeProfileEdit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id ?? 0)) }}"
                                        class="btn btn-sm btn-info" data-bs-toggle="tooltip" title=""
                                        data-bs-original-title="Edit">
                                        Edit Profile
                                    </a>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="info text-sm mb-3 font-style">
                                        <strong class="font-bold">Email :</strong>
                                        <span>{{ $employee->email }}</span>
                                    </div>
                                    <div class="info text-sm">
                                        <strong class="font-bold">Phone :</strong>
                                        <span>{{ $employee->phone }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="info mb-3 text-sm">
                                        <strong class="font-bold">Phone :</strong>
                                        <span>{{ $employee->phone }}</span>
                                    </div>
                                    <div class="info  text-sm">
                                        <strong class="font-bold">Date of Birth :</strong>
                                        <span>{{ \Auth::user()->dateFormat($employee->dob) }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6">
                                    <div class="info mb-3 text-sm">
                                        <strong class="font-bold">Status :</strong>
                                        @if ($employee->employee_exit == 1)
                                            <span class="text-danger"> Leave</span>
                                        @else
                                            <span class="text-success"> Active</span>
                                        @endif
                                    </div>
                                    <div class="info mb-3 text-sm">
                                        <strong class="font-bold">{{ __('App Login') }} :</strong>
                                        @if (($employee->app_login ?? 1) == 1)
                                            <span class="text-success">{{ __('Allowed') }}</span>
                                        @else
                                            <span class="text-danger">{{ __('Not Allowed') }}</span>
                                        @endif
                                    </div>
                                    <div class="info text-sm">
                                        <strong class="font-bold">{{ __('Web Login') }} :</strong>
                                        @if (($employee->web_login ?? 1) == 1)
                                            <span class="text-success">{{ __('Allowed') }}</span>
                                        @else
                                            <span class="text-danger">{{ __('Not Allowed') }}</span>
                                        @endif
                                    </div>
                                </div>



                            </div>


                        </div>
                    </div>
                    <hr>
                    <nav>
                        <div class="nav nav-tabs tablists mb-3" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-Salary-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Salary" type="button" role="tab" aria-controls="nav-Salary"
                                aria-selected="true">Personal Detail</button>

                            <button class="nav-link" id="nav-Allowance-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Allowance" type="button" role="tab"
                                aria-controls="nav-Allowance" aria-selected="false">Company</button>

                            <button class="nav-link" id="nav-salary-and-deductions-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-salary-and-deductions" type="button" role="tab"
                                aria-controls="nav-salary-and-deductions" aria-selected="false">Salary Structure</button>

                            <button class="nav-link" id="nav-Experience-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Experience" type="button" role="tab"
                                aria-controls="nav-Experience" aria-selected="false">Experience</button>

                            <button class="nav-link" id="nav-Education-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Education" type="button" role="tab"
                                aria-controls="nav-Education" aria-selected="false"> Education</button>

                            <button class="nav-link" id="nav-Dependent-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Dependent" type="button" role="tab"
                                aria-controls="nav-Dependent" aria-selected="false">Dependent</button>

                            <button class="nav-link" id="nav-Commission-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Commission" type="button" role="tab"
                                aria-controls="nav-Commission" aria-selected="false">Document</button>

                            <button class="nav-link" id="nav-Loan-tab" data-bs-toggle="tab" data-bs-target="#nav-Loan"
                                type="button" role="tab" aria-controls="nav-Loan" aria-selected="false">Bank
                                Account</button>

                            <button class="nav-link" id="nav-Payslip-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Payslip" type="button" role="tab" aria-controls="nav-Loan"
                                aria-selected="false">Payslip</button>

                        </div>
                    </nav>
                    <div class="tab-content tab_ctn" id="nav-tabContent">

                        <div class="tab-pane fade active show" id="nav-Salary" role="tabpanel"
                            aria-labelledby="nav-Salary-tab">
                            <div class="card ">
                                <div class="card-body employee-detail-body fulls-card emp-card">
                                    {{-- <h5>{{ __('Personal Detail') }}</h5>
                                    <hr> --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold">{{ __('Employee ID') }} : </strong>
                                                <span>{{ $employee->employee_id }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info text-sm font-style">
                                                <strong class="font-bold">{{ __('Name') }} :</strong>
                                                <span>{{ $employee->name }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info text-sm font-style">
                                                <strong class="font-bold">{{ __('Email') }} :</strong>
                                                <span>{{ $employee->email }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold">{{ __('Date of Birth') }} :</strong>
                                                <span>{{ \Auth::user()->dateFormat($employee->dob) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold">{{ __('Phone') }} :</strong>
                                                <span>{{ $employee->phone }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold">{{ __('Address') }} :</strong>
                                                <span>{{ $employee->address }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold">{{ __('Marital') }} :</strong>
                                                <span>{{ $employee->marital_status }}</span>
                                            </div>
                                        </div>
                                        @if (!empty($userdata))
                                            <div class="col-md-6">
                                                <div class="info text-sm">
                                                    <strong class="font-bold">{{ __('Team Leader') }} :</strong>
                                                    <span>{{ $userdata->name }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold">{{ __('Basic Salary') }} :</strong>
                                                <span>{{ $employee->salary }}</span>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold">{{ __('Gender') }} :</strong>
                                                <span>{{ $employee->gender }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info text-sm">
                                                <strong class="font-bold">{{ __('Personal Email') }} :</strong>
                                                <span>{{ $employee->personal_email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-Allowance" role="tabpanel"
                            aria-labelledby="nav-Allowance-tab">
                            <!-- Company -->
                            <!-- company data hear -->
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                </div>
                                <div class="col-sm-12 col-md-12">
                                    <div class="card ">
                                        <div class="card-body employee-detail-body fulls-card emp-card">
                                            {{-- <h5>{{ __('Company') }}</h5>
                                            <hr> --}}
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Branch') }} : </strong>
                                                        <span>{{ !empty($employee->branch) ? $employee->branch->name : '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm font-style">
                                                        <strong class="font-bold">{{ __('Department') }} :</strong>
                                                        <span>{{ !empty($employee->department) ? $employee->department->name : '' }}</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Designation') }} :</strong>
                                                        <span>{{ !empty($employee->designation) ? $employee->designation->name : '' }}</span>
                                                    </div>
                                                </div>
                                                @php
                                                    $descriptionToShow = '';
                                                    if (!empty($employee->designation_description)) {
                                                        $descriptionToShow = $employee->designation_description;
                                                    } elseif (
                                                        !empty($employee->designation) &&
                                                        !empty($employee->designation->description)
                                                    ) {
                                                        $descriptionToShow = $employee->designation->description;
                                                    }
                                                @endphp
                                                @if (!empty($descriptionToShow))
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold">{{ __('Designation Description') }}
                                                                :</strong>
                                                            <span>{{ $descriptionToShow }}</span>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if (!empty($employee->designation->document))
                                                    <div class="col-md-6">
                                                        <div class="info text-sm font-style">
                                                            <strong class="font-bold">{{ __('Designation Document') }}
                                                                :</strong>
                                                            <a href="{{ $employee->designation->document }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-outline-primary">{{ __('View Document') }}</a>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Employee Status') }} :</strong>
                                                        <span>{{ $employee->employe_status }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Employee Types') }} :</strong>
                                                        <span>{{ $employee->employe_types }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Experience') }} :</strong>
                                                        <span>{{ $employee->employee_experience }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Date on Exit') }} :</strong>
                                                        <span>{{ \Auth::user()->dateFormat($employee->date_of_exit) }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Date Of Joining') }} :</strong>
                                                        <span>{{ \Auth::user()->dateFormat($employee->company_doj) }}</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Pay Slab') }} :</strong>
                                                        <span>{{ $employee->playslab()->get()->first()->name ?? '' }}</span>
                                                    </div>
                                                </div>

                                                <?php
                                                
                                                $date = '';
                                                if ($employee->probation_period_date != null || !empty($employee->probation_period_date)) {
                                                    if (date('Y-m-d') <= date('Y-m-d', strtotime($employee->probation_period_date))) {
                                                        $date = \Auth::user()->dateFormat($employee->probation_period_date);
                                                    } else {
                                                        $date = \Auth::user()->dateFormat($employee->probation_period_date) . ' | Probation Period is over.';
                                                    }
                                                }
                                                ?>

                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Probation Date') }} :</strong>
                                                        <span>{{ $date }}</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Facility For') }} :</strong>
                                                        <span>{{ $employee->office_staff == 1 ? 'Office Staff' : 'Field Staff' }}</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Job Description') }} :</strong>
                                                        <span>{{ $employee->Job_description ?? '' }}</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Salary Structure --}}
                        <div class="tab-pane fade" id="nav-salary-and-deductions" role="tabpanel"
                            aria-labelledby="nav-salary-and-deductions-tab">
                            <div class="card">
                                {{-- <div class="card-header">
                                    <h5>{{ __('Salary Structure') }}</h5>
                                    <hr>
                                </div> --}}
                                <div class="card-body employee-detail-create-body">

                                    {{-- @dd($payslipDetail) --}}
                                    @if (empty($payslipDetail) || $payslipDetail == '')
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="info text-sm text-center">
                                                    <br>
                                                    <br>
                                                    <h5>{{ __('Setup Salary Structure') }}</h5>

                                                    <?php
                                                    
                                                    if (Auth::user()->type != 'employee' && Auth::user()->hasPermissionTo('Edit Employee')) {
                                                        $editLink = '<div class="action-btn bg-info ms-2"><a href="' . route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) . '" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="" data-bs-original-title="' . __('Edit') . '"><i class="ti ti-pencil text-white"></i></a></div>';
                                                    
                                                        echo $editLink;
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        @php
                                            $digit = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                        @endphp

                                        {{-- Monthely Ctc --}}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info text-sm">
                                                    <h5>{{ __('Monthly CTC') }}</h5>
                                                    <span>{{ $payslipDetail->monthlyCtc }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        {{-- Monthely Ctc --}}


                                        {{-- Earnings --}}
                                        <div class="row">

                                            <div class="col-md-12">
                                                <u>
                                                    <h5 style="text-align:left;">{{ __('Earnings') }}
                                                    </h5>
                                                </u>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table" id="">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-left">{{ __('Salary Components') }}</th>
                                                                <th class="text-right">{{ __('Monthly Amount') }}</th>
                                                                <th class="text-right">{{ __('Annual Amount') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php
                                                            
                                                            $calculatedCtcPercentage = 0;
                                                            try {
                                                                $salary = $payslipDetail->monthelySalaryByCtc;
                                                                $ctc_monthly = $employee->ctc_annual / 12;
                                                            
                                                                $calculatedCtcPercentage = ($salary / $ctc_monthly) * 100;
                                                            
                                                                if (is_nan($calculatedCtcPercentage) || !is_finite($calculatedCtcPercentage)) {
                                                                    $calculatedCtcPercentage = 0;
                                                                }
                                                            } catch (\Throwable $th) {
                                                            }
                                                            
                                                            ?>
                                                            <tr>
                                                                <td class="text-left">Basic
                                                                    ({{ numberFormat($calculatedCtcPercentage, 2) }}%
                                                                    of CTC)</td>
                                                                <td class="text-right">
                                                                    {{ priceFormat($payslipDetail->monthelySalaryByCtc) }}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ priceFormat($payslipDetail->yearlySalaryByCtc) }}
                                                                </td>
                                                            </tr>

                                                            @foreach ($payslipDetail->earnings as $earningName => $earnings)
                                                                @if (is_object($earnings))
                                                                    @foreach ($earnings->data as $earnongRow)
                                                                        @php
                                                                            $rowTitle = '';

                                                                            if ($earnongRow->type == 'percentage') {
                                                                                $rowTitle =
                                                                                    $earnongRow->name .
                                                                                    " ($earnongRow->percentage%)";
                                                                            } else {
                                                                                $rowTitle = $earnongRow->name;
                                                                            }
                                                                        @endphp

                                                                        <tr>
                                                                            <td class="text-left">{{ $rowTitle }}</td>
                                                                            <td class="text-right">
                                                                                {{ priceFormat($earnongRow->amount) }}
                                                                            </td>
                                                                            <td class="text-right">
                                                                                {{ priceFormat($earnongRow->amount * 12) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach

                                                        </tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td class="text-right"><b>Gross Earnings :
                                                                    {{ priceFormat($payslipDetail->grossSalary) }}</td>
                                                            <td></b></td>
                                                        </tr>
                                                        <tfoot>

                                                        </tfoot>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                        {{-- Earnings --}}

                                        <br>

                                        {{-- Deductions --}}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <u>
                                                    <h5 style="text-align:left;">{{ __('Deductions') }}
                                                    </h5>
                                                </u>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table" id="">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-left">{{ __('Salary Components') }}</th>
                                                                <th class="text-right">{{ __('Monthly Amount') }}</th>
                                                                <th class="text-right">{{ __('Annual Amount') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            @foreach ($payslipDetail->deductions as $deductionName => $deductions)
                                                                @if (is_object($deductions))
                                                                    @foreach ($deductions->data as $deductionsRow)
                                                                        @php
                                                                            $rowTitle = '';

                                                                            if ($deductionsRow->type == 'percentage') {
                                                                                $rowTitle =
                                                                                    $deductionsRow->name .
                                                                                    " ($deductionsRow->percentage%)";
                                                                            } else {
                                                                                $rowTitle = $deductionsRow->name;
                                                                            }
                                                                        @endphp

                                                                        <tr>
                                                                            <td class="text-left">{{ $rowTitle }}
                                                                            </td>
                                                                            <td class="text-right">
                                                                                {{ priceFormat($deductionsRow->amount) }}
                                                                            </td>
                                                                            <td class="text-right">
                                                                                {{ priceFormat($deductionsRow->amount * 12) }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                @endif
                                                            @endforeach

                                                        </tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td class="text-right"><b>Gross Deductions :
                                                                    {{ priceFormat($payslipDetail->totalDeductions) }}
                                                            </td>
                                                            <td></b></td>
                                                        </tr>
                                                        <tfoot>

                                                        </tfoot>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                        {{-- Deductions --}}

                                        <br>
                                        {{-- Employer's Contribution Deductions --}}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <u>
                                                    <h5 style="text-align:left;">{{ __("Employer's Contribution") }}
                                                    </h5>
                                                </u>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table" id="">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-left">{{ __('Salary Components') }}</th>
                                                                <th class="text-right">{{ __('Monthly Amount') }}</th>
                                                                <th class="text-right">{{ __('Annual Amount') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            {{-- GROSS EARNING  --}}
                                                            <tr>
                                                                <td class="text-left">Gross Earnings </td>
                                                                <td class="text-right">
                                                                    {{ priceFormat($payslipDetail->employeeBasicSalary + $payslipDetail->totalEarnings) }}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{ priceFormat(($payslipDetail->employeeBasicSalary + $payslipDetail->totalEarnings) * 12) }}
                                                                </td>
                                                            </tr>
                                                            {{-- GROSS EARNING  --}}


                                                            {{-- EMPLOYER'S PROVIDENT FUND --}}
                                                            @php
                                                                $providentFundCompanyDeductionTotal =
                                                                    $payslipDetail->providentFundCompanyDeduction
                                                                        ->total;
                                                                $providentFundCompanyDeductionData =
                                                                    $payslipDetail->providentFundCompanyDeduction->data;
                                                            @endphp
                                                            @if (!empty($providentFundCompanyDeductionData))
                                                                <tr>
                                                                    <td class="text-left">
                                                                        {{ $providentFundCompanyDeductionData->name }}
                                                                    </td>
                                                                    <td class="text-right">
                                                                        {{ priceFormat($providentFundCompanyDeductionData->amount) }}
                                                                    </td>
                                                                    <td class="text-right">
                                                                        {{ priceFormat($providentFundCompanyDeductionData->amount * 12) }}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            {{-- EMPLOYER'S PROVIDENT FUND   --}}

                                                            {{-- EMPLOYER'S STATE INSURANCE --}}
                                                            @php
                                                                $employeesStateInsuranceCompanyDeductionTotal =
                                                                    $payslipDetail
                                                                        ->employeesStateInsuranceCompanyDeduction
                                                                        ->total;
                                                                $employeesStateInsuranceCompanyDeductionData =
                                                                    $payslipDetail
                                                                        ->employeesStateInsuranceCompanyDeduction->data;
                                                            @endphp
                                                            @if (!empty($employeesStateInsuranceCompanyDeductionData))
                                                                <tr>
                                                                    <td class="text-left">
                                                                        {{ $employeesStateInsuranceCompanyDeductionData->name }}
                                                                    </td>

                                                                    <td class="text-right">
                                                                        {{ priceFormat($employeesStateInsuranceCompanyDeductionData->amount) }}
                                                                    </td>
                                                                    <td class="text-right">
                                                                        {{ priceFormat($employeesStateInsuranceCompanyDeductionData->amount * 12) }}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            {{-- EMPLOYER'S STATE INSURANCE --}}

                                                            {{-- STATUTORY BONUS  --}}
                                                            @php
                                                                $statutoryBonusTotal =
                                                                    $payslipDetail->statutoryBonus->total;
                                                                $statutoryBonusData =
                                                                    $payslipDetail->statutoryBonus->data;

                                                            @endphp
                                                            @if (!empty($statutoryBonusData))
                                                                <tr class="row-border">
                                                                    <td class="text-left">
                                                                        {{ $statutoryBonusData->name }}
                                                                    </td>

                                                                    <td class="text-right">
                                                                        {{ priceFormat($statutoryBonusData->amount) }}
                                                                    </td>
                                                                    <td class="text-right">
                                                                        {{ priceFormat($statutoryBonusData->amount * 12) }}
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                            {{-- STATUTORY BONUS  --}}

                                                            {{-- Total --}}
                                                            <tr class="row-border" style="display: ;">
                                                                @php
                                                                    $ctc =
                                                                        $payslipDetail->employeeBasicSalary +
                                                                        $payslipDetail->totalEarnings +
                                                                        $providentFundCompanyDeductionTotal +
                                                                        $employeesStateInsuranceCompanyDeductionTotal +
                                                                        $statutoryBonusTotal;
                                                                @endphp

                                                                <td colspan="2" class="text-right">
                                                                    <b>Total : {{ priceFormat($ctc) }}</b>
                                                                </td>
                                                            </tr>
                                                            {{-- Total --}}

                                                            {{-- CTC --}}
                                                            <tr style="display: none;">
                                                                <td class="text-left">
                                                                    <b>Total CTC : {{ priceFormat($ctc) }}</b>
                                                                </td>
                                                            </tr>

                                                            <tr style="display: none;">
                                                                <td class="text-left">
                                                                    <b>In Words :
                                                                        {{ ucwords($digit->format($ctc)) }}</b>
                                                                </td>
                                                            </tr>
                                                            {{-- CTC --}}

                                                        </tbody>

                                                        <tfoot>

                                                        </tfoot>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                        {{-- Employer's Contribution Deductions --}}

                                        <br>
                                        {{-- Net Pay --}}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info text-sm">
                                                    <h5>Net Pay :
                                                        {{ priceFormat($payslipDetail->employeeNewPayableSalary) }}
                                                    </h5>
                                                    <h5>In Words :
                                                        ({{ ucwords($digit->format($payslipDetail->employeeNewPayableSalary)) }})
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        {{-- Net Pay --}}
                                    @endif



                                </div>



                            </div>
                        </div>
                        {{-- Salary Structure --}}

                        <div class="tab-pane fade" id="nav-Commission" role="tabpanel"
                            aria-labelledby="nav-Commission-tab">
                            <!-- Document -->

                            @php
                                $employeedoc = $employee->documents()->pluck('document_value', 'document_id');
                                $logo = \App\Models\Utility::get_file('uploads/document');
                            @endphp


                            @if (!$documents->isEmpty() && !empty($employeedoc->toArray()))
                                <div class="col-sm-12 col-md-12">
                                    <div class="card ">
                                        <div class="card-body employee-detail-body fulls-card emp-card">
                                            <div class="row">

                                                @foreach ($documents as $key => $document)
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold">{{ $document->name }} : </strong>


                                                            <?php
                                                            
                                                            $element = '';
                                                            
                                                            $htmlElement = '';
                                                            $filePath = !empty($employeedoc[$document->id]) ? $logo . '/' . $employeedoc[$document->id] : '';
                                                            if (!empty($filePath)) {
                                                                $fileName = basename(strrchr($filePath, '/'), '/');
                                                            
                                                                // Define a regular expression pattern for common image file extensions
                                                                $imageExtensionsPattern = '/\.(jpg|jpeg|png|gif|bmp|svg)$/i';
                                                            
                                                                // Check if the file name matches the pattern
                                                                if (preg_match($imageExtensionsPattern, $fileName)) {
                                                                    $element = "<img src='$filePath' width='50%' />";
                                                                } else {
                                                                    $element = 'View';
                                                                }
                                                            
                                                                $htmlElement = '<span><a href="' . $filePath . '" target="_blank">' . $element . '</a></span>';
                                                            } else {
                                                                $htmlElement = '<label>No document is added</label>';
                                                            }
                                                            
                                                            ?>

                                                            {!! $htmlElement !!}
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <br>
                                        <h5 class="text-center">No documents detail have been added!</h5>
                                    </div>
                                </div>
                            @endif

                        </div>

                        {{-- //Work Experience-TABLE  --}}
                        <div class="tab-pane fade" id="nav-Experience" role="tabpanel"
                            aria-labelledby="nav-Experience-tab">
                            {{-- //Work Experience-TABLE --}}


                            @if (empty($EXPTableData->toArray()))
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <br>
                                        <h5 class="text-center">No experience detail have been added!</h5>
                                    </div>
                                </div>
                            @else
                                @foreach ($EXPTableData as $key => $exp)
                                    <div class="col-sm-12 col-md-12">
                                        <div class="card ">
                                            <div class="card-body employee-detail-body fulls-card emp-card">
                                                <h5>{{ $key + 1 }} {{ __('Work Experience') }}</h5>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold">{{ __('Previous Company') }} :
                                                            </strong>
                                                            <span>{{ $exp->previous_company_name }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm font-style">
                                                            <strong class="font-bold">{{ __('Job Title') }} :</strong>
                                                            <span>{{ $exp->previous_job_title }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold">{{ __('From Date') }} :</strong>
                                                            <span>{{ $exp->previous_from_date }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold">{{ __('To Date') }} :</strong>
                                                            <span>{{ $exp->previous_to_date }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold">{{ __('Designation') }} :</strong>
                                                            <?php //$data = App\Models\Designation::where('id', $exp->previous_company_designation_id)->first();
                                                            ?>
                                                            {{-- @if (!empty($data))
                                                                      <span>{{ $data->name }}</span>
                                                                 @endif --}}
                                                            <span>{{ $exp->previous_company_designation_id }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong
                                                                class="font-bold">{{ __('Experience Job Description') }}
                                                                :</strong>
                                                            <span>{{ $exp->experiance_Job_description }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            {{-- //Work-TABLE --}}
                        </div>

                        {{-- //Education-TABLE  --}}
                        <div class="tab-pane fade" id="nav-Education" role="tabpanel"
                            aria-labelledby="nav-Education-tab">
                            {{-- //Education-TABLE --}}

                            @if (empty($EducationData->toArray()))
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <br>
                                        <h5 class="text-center">No education detail have been added!</h5>
                                    </div>
                                </div>
                            @else
                                @foreach ($EducationData as $key => $edu)
                                    <div class="col-sm-12 col-md-12">
                                        <div class="card ">
                                            <div class="card-body employee-detail-body fulls-card emp-card">
                                                {{-- <h5>{{ $key + 1 }} {{ __('Education') }}</h5>
                                                  <hr> --}}
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold">{{ __('Institute Name') }} :
                                                            </strong>
                                                            <span>{{ $edu->institute_name }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm font-style">
                                                            <strong class="font-bold">{{ __('Education') }} :</strong>
                                                            <span>{{ $edu->education }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold">{{ __('Specialization') }}
                                                                :</strong>
                                                            <span>{{ $edu->specialization }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold">{{ __('Date of completion') }}
                                                                :</strong>
                                                            <span>{{ $edu->date_of_completion }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold">{{ __('Notes') }} :</strong>
                                                            <span>{{ $edu->notes }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            {{-- //Education-TABLE --}}
                        </div>
                        {{-- //depended --}}

                        {{-- //depended-TABLE  --}}
                        <div class="tab-pane fade" id="nav-Dependent" role="tabpanel"
                            aria-labelledby="nav-Dependent-tab">
                            {{-- //depended-TABLE --}}

                            @if (empty($DependentData->toArray()))
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <br>
                                        <h5 class="text-center">No dependent detail have been added!</h5>
                                    </div>
                                </div>
                            @else
                                @foreach ($DependentData as $key => $edu)
                                    <div class="col-sm-12 col-md-12">
                                        <div class="card ">
                                            <div class="card-body employee-detail-body fulls-card emp-card">
                                                {{-- <h5>{{ $key + 1 }} {{ __('Dependent') }}</h5>
                                                  <hr> --}}
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold">{{ __('Name') }} : </strong>
                                                            <span>{{ $edu->dependent_name }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm font-style">
                                                            <strong class="font-bold">{{ __('Relation ') }} :</strong>
                                                            <span>{{ $edu->dependent_relation }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold">{{ __('Date of completion') }}
                                                                :</strong>
                                                            <span>{{ $edu->dependent_dob }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="info text-sm">
                                                            <strong class="font-bold">{{ __('Emergency phone no') }}
                                                                :</strong>
                                                            <span>{{ $edu->emergency_phone_no }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            {{-- //Education-TABLE --}}
                        </div>

                        <div class="tab-pane fade" id="nav-Loan" role="tabpanel" aria-labelledby="nav-Loan-tab">

                            @if (
                                !empty($employee->account_holder_name) ||
                                    !empty($employee->account_number) ||
                                    !empty($employee->bank_name) ||
                                    !empty($employee->bank_identifier_code) ||
                                    !empty($employee->branch_location) ||
                                    !empty($employee->tax_payer_id))
                                <div class="col-sm-12 col-md-12">
                                    <div class="card ">
                                        <div class="card-body employee-detail-body fulls-card emp-card">
                                            {{-- <h5>{{ __('Bank Account') }}</h5> --}}
                                            {{-- <hr> --}}
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Account Holder Name') }} :
                                                        </strong>
                                                        <span>{{ $employee->account_holder_name }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm font-style">
                                                        <strong class="font-bold">{{ __('Account Number') }} :</strong>
                                                        <span>{{ $employee->account_number }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Bank Name') }} :</strong>
                                                        <span>{{ $employee->bank_name }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Bank Identifier Code') }}
                                                            :</strong>
                                                        <span>{{ $employee->bank_identifier_code }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Branch Location') }} :</strong>
                                                        <span>{{ $employee->branch_location }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info text-sm">
                                                        <strong class="font-bold">{{ __('Tax Payer Id') }} :</strong>
                                                        <span>{{ $employee->tax_payer_id }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-sm-12 col-md-12">
                                    <div class="card">
                                        <br>
                                        <h5 class="text-center"> No bank account have been added!</h5>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="nav-Payslip" role="tabpanel" aria-labelledby="nav-Payslip-tab">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-center justify-content-start mb-4">
                                            {{-- <h5>{{ __('Payslip') }}</h5> --}}
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="row">
                                                    <div class="col-sm-6 col-lg-4">
                                                        <div class="btn-box">
                                                            <select class="form-control month_date " name="year"
                                                                tabindex="-1" aria-hidden="true">
                                                                @foreach ($month as $k => $mon)
                                                                    <?php
                                                                    $selected = '';
                                                                    if ($k == date('m', strtotime('current month'))) {
                                                                        $selected = 'selected';
                                                                    }
                                                                    ?>
                                                                    <option value="{{ $k }}"
                                                                        {{ $selected }}>{{ $mon }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-lg-4">
                                                        <div class="btn-box">

                                                            <select class="form-control year_date " name="year"
                                                                tabindex="-1" aria-hidden="true">
                                                                @for ($i = 0; $i <= $yearsCount; $i++)
                                                                    <?php
                                                                    $selected = '';
                                                                    $value = date('Y', strtotime("-$i year"));
                                                                    
                                                                    if ($value == date('Y')) {
                                                                        $selected = 'selected';
                                                                    }
                                                                    ?>
                                                                    <option value="{{ $value }}"
                                                                        {{ $selected }}>{{ $value }}</option>
                                                                @endfor
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body px-0">
                                        <div class="table-responsive">
                                            <table class="table" id="pc-dt-render-column-cells">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Employee Id') }}</th>
                                                        {{-- 
                                       <th>{{ __('Name') }}</th>
                                       --}}
                                                        <th>{{ __('Payroll Type') }}</th>
                                                        <th>{{ __('Salary') }}</th>
                                                        <th>{{ __('Net Salary') }}</th>
                                                        <th>{{ __('Status') }}</th>
                                                        <th>{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            {{-- <div class="card">
               <table class="table">
                  <thead>
                     <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Latter</th>
                        <th scope="col">Contact</th>
                        <th scope="col">Date</th>
                        <th scope="col">Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                     </tr>
                  </tbody>
               </table>
            </div> --}}
        </div>
    </div>
    </div>
    </div>
@endsection
@push('script-page')
    <script>
        $(document).ready(function() {
            callback();

            function callback() {
                var month = $(".month_date").val() ?? '';
                var year = $(".year_date").val() ?? '';

                $('.filter_month').val(month);
                $('.filter_year').val(year);

                if (month == '') {
                    month = '{{ date('m', strtotime('current month')) }}';
                    year = '{{ date('Y') }}';

                    $('.filter_month').val(month);
                    $('.filter_year').val(year);
                }

                var datePicker = year + '-' + month;

                $.ajax({
                    url: "{{ route('payslip.search_json') }}",
                    type: 'POST',
                    data: {
                        "datePicker": datePicker,
                        "_token": "{{ csrf_token() }}",
                        "employee_id": "{{ $employee->id }}",
                    },
                    success: function(data) {
                        console.log(data);
                        var datatable_data = {
                            data: data
                        };

                        function renderstatus(data, cell, row) {
                            if (data == 'Paid')
                                return '<div class="badge bg-success p-2 px-3 rounded"><a href="#" class="text-white">' +
                                    data + '</a></div>';
                            else
                                return '<div class="badge bg-danger p-2 px-3 rounded"><a href="#" class="text-white">' +
                                    data + '</a></div>';
                        }

                        function renderButton(data, cell, row) {

                            var $div = $(row);
                            employee_id = $div.find('td:eq(0)').text();
                            status = $div.find('td:eq(6)').text();

                            var month = $(".month_date").val();
                            var year = $(".year_date").val();
                            var id = employee_id;
                            var payslip_id = data;

                            var clickToPaid = '';
                            var payslip = '';
                            var view = '';
                            var edit = '';
                            var deleted = '';
                            var form = '';

                            if (data != 0) {
                                var payslip =
                                    '<a href="#" data-url="{{ url('payslip/pdf/') }}/' + id +
                                    '/' + datePicker +
                                    '" data-size="md-pdf"  data-ajax-popup="true" class="btn btn-primary" data-title="{{ __('Employee Payslip') }}">' +
                                    '{{ __('Payslip') }}' + '</a> ';
                            }

                            if (status == "UnPaid" && data != 0) {
                                clickToPaid = '<a href="{{ url('payslip/paysalary/') }}/' + id +
                                    '/' + datePicker + '"  class="view-btn primary-bg btn-sm">' +
                                    '{{ __('Click To Paid') }}' + '</a>  ';
                            }

                            if (data != 0) {
                                view =
                                    '<a href="#" data-url="{{ url('payslip/showemployee/') }}/' +
                                    payslip_id +
                                    '"  data-ajax-popup="true" class="view-btn gray-bg" data-title="{{ __('View Employee Detail') }}">' +
                                    '{{ __('View') }}' + '</a>';
                            }

                            if (data != 0 && status == "UnPaid") {
                                edit =
                                    '<a href="#" data-url="{{ url('payslip/editemployee/') }}/' +
                                    payslip_id +
                                    '"  data-ajax-popup="true" class="view-btn blue-bg" data-title="{{ __('Edit Employee salary') }}">' +
                                    '{{ __('Edit') }}' + '</a>';
                            }

                            var url = '{{ route('payslip.delete', ':id') }}';
                            url = url.replace(':id', payslip_id);

                            @if (\Auth::user()->type != 'employee')
                                if (data != 0) {
                                    deleted = '<a href="#"  data-url="' + url +
                                        '" class="payslip_delete view-btn red-bg" >' +
                                        '{{ __('Delete') }}' + '</a>';
                                }
                            @endif

                            return view + payslip + clickToPaid + edit + deleted + form;
                        }

                        console.clear();
                        var tr = '';
                        if (data.length > 0) {

                            $.each(data, function(indexInArray, valueOfElement) {
                                var status =
                                    '<div class="badge bg-danger p-2 px-3 rounded"><a href="#" class="text-white">' +
                                    valueOfElement[6] + '</a></div>';
                                if (valueOfElement[6] == 'Paid') {
                                    var status =
                                        '<div class="badge bg-success p-2 px-3 rounded"><a href="#" class="text-white">' +
                                        valueOfElement[6] + '</a></div>';
                                }

                                var id = valueOfElement[0];
                                var employee_id = valueOfElement[1];
                                var payslip_id = valueOfElement[7];

                                if (valueOfElement[7] != 0) {
                                    var payslip =
                                        '<a href="#" data-url="{{ url('payslip/pdf/') }}/' +
                                        id +
                                        '/' + datePicker +
                                        '" data-size="lg"  data-ajax-popup="true" class=" btn-sm btn btn-warning" data-title="{{ __('Employee Payslip') }}">' +
                                        '{{ __('Payslip') }}' + '</a> ';
                                }
                                if (valueOfElement[6] == "UnPaid" && valueOfElement[7] != 0) {
                                    var clickToPaid =
                                        '<a href="{{ url('payslip/paysalary/') }}/' + id +
                                        '/' + datePicker +
                                        '"  class="btn-sm btn btn-primary">' +
                                        '{{ __('Click To Paid') }}' + '</a>  ';
                                } else {
                                    var clickToPaid = '';
                                }

                                if (valueOfElement[7] != 0 && valueOfElement[6] == "UnPaid") {
                                    var edit =
                                        '<a href="#" data-url="{{ url('payslip/editemployee/') }}/' +
                                        payslip_id +
                                        '"  data-ajax-popup="true" class="btn-sm btn btn-info" data-title="{{ __('Edit Employee salary') }}">' +
                                        '{{ __('Edit') }}' + '</a>';
                                } else {
                                    var edit = '';
                                }

                                var url = '{{ route('payslip.delete', ':id') }}';
                                url = url.replace(':id', payslip_id);

                                @if (\Auth::user()->type != 'employee')
                                    if (valueOfElement[7] != 0) {
                                        var deleted = '<a href="#"  data-url="' + url +
                                            '" class="payslip_delete view-btn btn btn-danger ms-1 btn-sm"  >' +
                                            '{{ __('Delete') }}' + '</a>';
                                    } else {
                                        var deleted = '';
                                    }
                                @else
                                    var deleted = '';
                                @endif

                                var url_employee = valueOfElement['url'];

                                tr +=
                                    '<tr>' +
                                    '<td> <a class="btn btn-outline-primary" href="' +
                                    url_employee + '">' +
                                    valueOfElement[1] + '</a></td> ' +
                                    '<td>' + valueOfElement[2] + '</td> ' +
                                    {{-- '<td>' + valueOfElement[3] + '</td>' + --}} '<td>' + valueOfElement[4] +
                                    '</td>' +
                                    '<td>' + valueOfElement[5] + '</td>' +
                                    '<td>' + status + '</td>' +

                                    '<td>' + payslip + clickToPaid + edit + deleted + '</td>' +

                                    '</tr>';
                            });
                        } else {
                            var colspan = $('#pc-dt-render-column-cells thead tr th').length;
                            var tr = '<tr><td class="dataTables-empty" colspan="' + colspan +
                                '">{{ __('No entries found') }}</td></tr>';
                        }

                        $('#pc-dt-render-column-cells tbody').html(tr);
                        var table = document.querySelector("#pc-dt-render-column-cells");
                        var datatable = new simpleDatatables.DataTable(table);
                    },
                    error: function(data) {

                    }
                });
            }

            $(document).on("change", ".month_date,.year_date", function() {
                callback();
            });

            //bulkpayment Click
            $(document).on("click", "#bulk_payment", function() {

                var month = $(".month_date").val();
                var year = $(".year_date").val();
                var datePicker = year + '_' + month;

            });
            $(document).on('click', '#bulk_payment',
                'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]',
                function() {

                    var month = $(".month_date").val();
                    var year = $(".year_date").val();
                    var datePicker = year + '-' + month;

                    var title = 'Bulk Payment';
                    var size = 'md';
                    var url = 'payslip/bulk_pay_create/' + datePicker;

                    // return false;

                    $("#commonModal .modal-title").html(title);
                    $("#commonModal .modal-dialog").addClass('modal-' + size);
                    $.ajax({
                        url: url,
                        success: function(data) {
                            if (data.length) {
                                $('#commonModal .body').html(data);
                                $("#commonModal").modal('show');
                                // common_bind();
                            } else {
                                show_toastr('error', 'Permission denied.');
                                $("#commonModal").modal('hide');
                            }
                        },
                        error: function(data) {
                            data = data.responseJSON;
                            show_toastr('error', data.error);
                        }
                    });
                });

            $(document).on("click", ".payslip_delete", function() {
                var confirmation = confirm("are you sure you want to delete this payslip?");
                var url = $(this).data('url');

                if (confirmation) {
                    $.ajax({
                        type: "GET",
                        url: url,
                        dataType: "JSON",
                        success: function(data) {


                            show_toastr('success', 'Payslip Deleted Successfully', 'success');

                            setTimeout(function() {
                                location.reload();
                            }, 800)
                        },
                    });
                }
            });
        });
    </script>
@endpush
