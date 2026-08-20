@extends('layouts.admin')

@section('page-title')
    {{ __('Edit Employee') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('employee') }}">{{ __('Employee') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit Employee') }}</li>
@endsection

@section('content')


    <div class="row">
        <div class="col-sm-12">
            {{ Form::model($employee, ['route' => ['employee.update', $employee->id], 'id' => 'employee_update', 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
            <div class="card">
                <div class="card-body">

                    <div class="employ_dtl">
                        <div class="emploimg">
                            <img alt="#"
                                src="https://redsparkte.a2hosted.com/hrms_staging//storage/uploads/avatar/Max-R_Headshot (1)_1681820818.jpg"
                                class="">
                        </div>
                        <div class="employctn">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6">
                                    <h5 class="mt-2"> {{ $employee->name }}</h5>
                                    {{-- <p> {{ $employeesId }}</p> --}}
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
                                        <span>{{ date('d-m-Y', strtotime(\Auth::user()->dateFormat($employee->getRawOriginal('dob')))) }}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="info mb-3 text-sm">
                                        <input type="checkbox" @if ($employee->employee_exit == 1) checked @endif
                                            class="form-check-input isscheck isscheck_User" id="employee_exit"
                                            name="employee_exit" value="1">
                                        <strong class="form-label font-weight-500" for="employee_exit">&nbsp;Exit</strong>
                                    </div>

                                    <div class="info mb-3 text-sm">
                                        <input type="checkbox" @if ($employee->confirm_probation_period == 1) checked @endif
                                            class="form-check-input isscheck isscheck_User" id="confirm_probation_period"
                                            name="confirm_probation_period" value="1">
                                        <strong class="form-label font-weight-500"
                                            for="employee_exit">&nbsp;Confirm</strong>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <hr>
                    <nav>
                        <div class="nav nav-tabs tablists mb-3" id="nav-tab" role="tablist">

                            <button class="nav-link active" id="nav-personal-details-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-personal-details" type="button" role="tab"
                                aria-controls="nav-personal-details" aria-selected="true">Personal Detail</button>

                            <button class="nav-link" id="nav-Allowance-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Allowance" type="button" role="tab" aria-controls="nav-Allowance"
                                aria-selected="false">Company</button>

                            <button class="nav-link" id="nav-salary-and-deductions-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-salary-and-deductions" type="button" role="tab"
                                aria-controls="nav-salary-and-deductions" aria-selected="false">Salary & Deductions</button>

                            <button class="nav-link" id="nav-Experience-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Experience" type="button" role="tab"
                                aria-controls="nav-Experience" aria-selected="false">Experience</button>
                            <button class="nav-link" id="nav-Education-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Education" type="button" role="tab" aria-controls="nav-Education"
                                aria-selected="false"> Education</button>
                            <button class="nav-link" id="nav-Dependent-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Dependent" type="button" role="tab" aria-controls="nav-Dependent"
                                aria-selected="false">Dependent</button>
                            <button class="nav-link" id="nav-Commission-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Commission" type="button" role="tab"
                                aria-controls="nav-Commission" aria-selected="false">Document</button>
                            <button class="nav-link" id="nav-Loan-tab" data-bs-toggle="tab" data-bs-target="#nav-Loan"
                                type="button" role="tab" aria-controls="nav-Loan" aria-selected="false">Bank
                                Account</button>

                        </div>
                    </nav>

                    <div class="tab-content tab_ctn" id="nav-tabContent">

                        <div class="tab-pane fade active show" id="nav-personal-details" role="tabpanel"
                            aria-labelledby="nav-personal-details-tab">

                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __('Personal Detail') }}</h5>
                                </div>
                                <div class="card-body">

                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {!! Form::label('name', __('Name'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('phone', __('Phone'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('dob', __('Date of Birth'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>

                                                <input type="date" name="dob"
                                                    value="{{ date('Y-m-d', strtotime($employee->getRawOriginal('dob'))) }}"
                                                    class='form-control' max="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group ">
                                                {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                                <div class="d-flex radio-check">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="g_male" value="Male"
                                                            name="gender" class="form-check-input"
                                                            {{ $employee->gender == 'Male' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="g_male">{{ __('Male') }}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio ms-1 custom-control-inline">
                                                        <input type="radio" id="g_female" value="Female"
                                                            name="gender" class="form-check-input"
                                                            {{ $employee->gender == 'Female' ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="g_female">{{ __('Female') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            {{ Form::label('marital_status', __('Select Marital '), ['class' => 'form-label']) }}
                                            <div class="form-icon-user">
                                                <select name="marital_status" id="marital_status"
                                                    class="form-control select2">
                                                    <option value="">-Select Marital-</option>
                                                    <option @if ($employee->marital_status == 'Single') selected @endif
                                                        class="form-check-input" value="Single">Single</option>
                                                    <option @if ($employee->marital_status == 'Married') selected @endif
                                                        class="form-check-input" value="Married">Married</option>
                                                    <option @if ($employee->marital_status == 'Widowed') selected @endif
                                                        class="form-check-input" value="Widowed">Widowed</option>
                                                    <option @if ($employee->marital_status == 'Separated') selected @endif
                                                        class="form-check-input" value="Separated">Separated</option>
                                                    <option @if ($employee->marital_status == 'Divorced') selected @endif
                                                        class="form-check-input"value="Divorced">Divorced</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('email', __('Email'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Enter employee email']) !!}
                                        </div>
                                        <div class="form-group  col-sm-6">
                                            {!! Form::label('address', __('Address'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::textarea('address', null, ['class' => 'form-control', 'rows' => 2]) !!}
                                        </div>
                                    </div>
                                    @if (\Auth::user()->type == 'employee')
                                        {!! Form::submit('Update', ['class' => 'btn-create btn-xs badge-blue radius-10px float-right']) !!}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-Allowance" role="tabpanel"
                            aria-labelledby="nav-Allowance-tab">
                            @if (\Auth::user()->type != 'employee')
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Company') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @csrf
                                            <div class="form-group col-sm-6">
                                                {!! Form::label('employee_id', __('Employee ID'), ['class' => 'form-label']) !!}
                                                {!! Form::text('employee_id', $employeesId, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                            </div>
                                            <div class="form-group col-sm-6">
                                                {{ Form::label('branch_id', __('Select Branch'), ['class' => 'form-label']) }}<span
                                                    class="text-danger pl-1">*</span>
                                                {{ Form::select('branch_id', $branches, null, ['class' => 'form-control select2', 'id' => 'branch_id', 'placeholder' => 'Select Branch']) }}
                                            </div>
                                            <div class="form-group col-sm-6">
                                                {{ Form::label('department_id', __('Select Department'), ['class' => 'form-label']) }}<span
                                                    class="text-danger pl-1">*</span>
                                                {{ Form::select('department_id', $departments, null, ['class' => 'form-control', 'id' => 'department_id']) }}
                                            </div>
                                            <div class="form-group col-sm-6">
                                                {{ Form::label('designation_id', __('Select Designation'), ['class' => 'form-label']) }}<span
                                                    class="text-danger pl-1">*</span>


                                                <div class="form-icon-user">
                                                    <div class="designation_div">
                                                        <select class="form-control designation_id select2"
                                                            name="designation_id" id="choices-multiple"
                                                            placeholder="Select Designation">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                {{ Form::label('employe_status', __('Employee Status'), ['class' => 'form-label']) }}<span
                                                    class="text-danger pl-1">*</span>
                                                <div class="form-icon-user">
                                                    <select name="employe_status" id="employe_status"
                                                        class="form-control select2">

                                                        <option @if ($employee->employe_status == 'Active') selected @else @endif
                                                            value="Active">Active</option>
                                                        <option @if ($employee->employe_status == 'Terminated') selected @else @endif
                                                            value="Terminated">Terminated</option>
                                                        <option @if ($employee->employe_status == 'Deceased') selected @else @endif
                                                            value="Deceased">Deceased</option>
                                                        <option @if ($employee->employe_status == 'Resigned') selected @else @endif
                                                            value="Resigned">Resigned</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                {{ Form::label('employe_types', __('Employee Types'), ['class' => 'form-label']) }}<span
                                                    class="text-danger pl-1">*</span>

                                                <div class="form-icon-user">
                                                    <select name="employe_types" id="employe_types"
                                                        class="form-control select2">
                                                        <option @if ($employee->employe_types == 'Permanent') selected @else @endif
                                                            value="Permanent">Permanent</option>
                                                        <option @if ($employee->employe_types == 'Temporary') selected @else @endif
                                                            value="Temporary">Temporary</option>
                                                        <option @if ($employee->employe_types == 'On Contract') selected @else @endif
                                                            value="On Contract">On Contract</option>
                                                        <option @if ($employee->employe_types == 'Trainee') selected @else @endif
                                                            value="Trainee">Trainee</option>


                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                {!! Form::label('Experience', __('Experience'), ['class' => 'form-label']) !!}
                                                {!! Form::text('employee_experience', $employee->employee_experience, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Enter Experience',
                                                ]) !!}
                                            </div>


                                            <div class="form-group col-sm-6">
                                                {!! Form::label('Job_description', __('Job Description'), ['class' => 'form-label']) !!}

                                                {!! Form::textarea('Job_description', $employee->Job_description, [
                                                    'class' => 'form-control',
                                                    'rows' => 2,
                                                    'placeholder' => 'Enter job description',
                                                ]) !!}
                                            </div>


                                            <div class="form-group col-sm-6">
                                                {!! Form::label('company_doj', 'Company date of joining', ['class' => 'form-label']) !!} <span class="text-danger pl-1">*</span>
                                                <input type="date" name="company_doj"
                                                    value="{{ $employee->getRawOriginal('company_doj') }}"
                                                    class='form-control' max="<?php echo date('Y-m-d'); ?>">
                                            </div>

                                            <div class="form-group col-sm-6">
                                                {!! Form::label('date_of_exit', __('Date on Exit'), ['class' => 'form-label']) !!}
                                                <input type="date" name="date_of_exit"
                                                    value="{{ $employee->getRawOriginal('date_of_exit') }}"
                                                    class='form-control' max="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <div class="form-group col-sm-6">
                                                {!! Form::label('company_doj', 'Probation Period Date', ['class' => 'form-label']) !!} <span class="text-danger pl-1">*</span>
                                                <input type="date"
                                                    value="{{ $employee->getRawOriginal('probation_period_date') }}"
                                                    name="probation_period_date" class='form-control'>
                                                <span>Probation Period Months : {{ $probation_period_months }}</span>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                {!! Form::label('assign_shifts', 'Assign Shift', ['class' => 'form-label']) !!}
                                                <select name="assign_shifts[]" class="select2" id="assign_shifts">
                                                    <option value="">Select shift</option>
                                                    @foreach ($workingShifts as $shift)
                                                        <option value="{{ $shift->id }}"
                                                            {{ in_array($shift->id, $assignedWorkingShifts) ? 'selected' : '' }}>
                                                            {{ $shift->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="employee-detail-wrap ">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Company Detail') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="info">
                                                        <strong>{{ __('Branch') }}</strong>
                                                        <span>{{ !empty($employee->branch) ? $employee->branch->name : '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="info font-style">
                                                        <strong>{{ __('Department') }}</strong>
                                                        <span>{{ !empty($employee->department) ? $employee->department->name : '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="info font-style">
                                                        <strong>{{ __('Designation') }}</strong>
                                                        <span>{{ !empty($employee->designation) ? $employee->designation->name : '' }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="info">
                                                        <strong>{{ __('Date Of Joining') }}</strong>
                                                        <span>{{ \Auth::user()->dateFormat($employee->getRawOriginal('company_doj')) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif


                        </div>

                        <div class="tab-pane fade" id="nav-salary-and-deductions" role="tabpanel"
                            aria-labelledby="nav-salary-and-deductions-tab">
                            <div
                                class="card                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ">
                                <div class="card-header">
                                    <h5>{{ __('Salary & Deductions') }}</h5>
                                </div>
                                <div class="card-body employee-detail-create-body">
                                    <div class="row">

                                        <div class="form-group col-md-4">
                                            {!! Form::label('play_slab_id', __('Pay Slab'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            <select name="play_slab_id" id="play_slab_id" class="form-control select2">
                                                <option value="">Select Pay Slab</option>
                                                @foreach ($PayslipType as $playslab)
                                                    <option @if ($employee->play_slab_id == $playslab->id) selected @endif
                                                        value="{{ $playslab->id }}">{{ $playslab->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            {!! Form::label('office_staff', __('This facility is for Field'), ['class' => 'form-label']) !!}
                                            <select name="office_staff" id="office_staff" class="form-control">
                                                <option value="">Select visit option</option>
                                                <option @if ($employee->office_staff == 1) selected @endif value="1">
                                                    Office Staff</option>
                                                <option @if ($employee->office_staff == 2) selected @endif value="2">
                                                    Field Staff</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4 select_grade_type d-none">
                                            {!! Form::label('grade_type_id', __('Grade Type'), ['class' => 'form-label']) !!}
                                            <select name="grade_type_id" id="grade_type_id" class="form-control">
                                                <option value="">Select Grade</option>
                                                @foreach ($gradeTypes as $gradetype)
                                                    <option @if ($gradetype->id == $employee->grade_type_id) selected @endif
                                                        value="{{ $gradetype->id }}">{{ $gradetype->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            {!! Form::label('ctc_annual', __('CTC Annual'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::number('ctc_annual', old('ctc_annual'), [
                                                'class' => 'form-control numberValidation',
                                                'min' => '0',
                                                'placeholder' => 'Enter Employee Annual CTC',
                                                'id' => 'ctc_annual',
                                            ]) !!}
                                        </div>

                                        <div class="form-group col-md-2">
                                            {!! Form::label('ctc_monthely', __('CTC Monthly'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::number(
                                                'ctc_monthely',
                                                old('ctc_monthely', $employee->ctc_annual > 0 ? $employee->ctc_annual / 12 : 0),
                                                [
                                                    'class' => 'form-control numberValidation',
                                                    'min' => '0',
                                                    'placeholder' => 'Employee Monthly CTC',
                                                    'id' => 'ctc_monthely',
                                                    'readonly',
                                                ],
                                            ) !!}
                                        </div>

                                        <div class="form-group col-md-2">
                                            {!! Form::label('ctc_percentage', __('Basic(%)'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::number('ctc_percentage', old('ctc_percentage', number_format($company_ctc_percentage, 0)), [
                                                'class' => 'form-control numberValidation',
                                                'min' => '0',
                                                'placeholder' => 'Basic(%)',
                                                'id' => 'ctc_percentage',
                                            ]) !!}
                                        </div>

                                        <div class="form-group col-md-2">
                                            {!! Form::label('salary', __('Salary'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::number('salary', old('salary'), [
                                                'class' => 'form-control numberValidation',
                                                'min' => '0',
                                                'placeholder' => 'Enter Employee Salary',
                                                'id' => 'salary',
                                                'readonly',
                                            ]) !!}
                                        </div>
                                    </div>

                                    {{-- EPF | Employee Provident Fund --}}
                                    @if ($companyEpf != null)
                                        <hr>
                                        <div class="row">

                                            <div class="form-group col-md-12">
                                                <div class="form-check form-switch">
                                                    <label for="epf_status">
                                                        <h5 class="">{{ __('EPF | Employee Provident Fund') }}</h5>
                                                    </label>
                                                    <input type="checkbox" class="form-check-input" id="epf_status"
                                                        name="epf_status"
                                                        {{ $employee->epf_status == 1 ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="epf_div"
                                            style="display: {{ $employee->epf_status == 0 ? 'none' : '' }};">
                                            <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                {{ Form::label('epf_number', __('PF Account No / Member Id'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('epf_number', old('epf_number', $employee->epf_number), ['id' => 'epf_number', 'class' => 'form-control', 'placeholder' => 'PF Account No / Member Id']) }}
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                {{ Form::label('uan_no', __('UAN Number'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('uan_no', old('uan_no', $employee->uan_no), ['id' => 'uan_no', 'class' => 'form-control', 'placeholder' => 'UAN Number']) }}
                                            </div>

                                            @if ($companyEpf->override_employee_pf == 1)
                                                <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                    {{ Form::label('employee_contribution_rate', __('Employee Contribution Rate (%)'), ['class' => 'col-form-label']) }}
                                                    {{ Form::text('employee_contribution_rate', old('employee_contribution_rate', $employee->employee_contribution_rate), ['id' => 'employee_contribution_rate', 'class' => 'form-control numberValidation text-right', 'placeholder' => 'Employee Contribution Rate (%)', 'max' => '100']) }}
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                    {{ Form::label('employer_contribution_rate', __('Employer Contribution Rate (%)'), ['class' => 'col-form-label']) }}
                                                    {{ Form::text('employer_contribution_rate', old('employer_contribution_rate', $employee->employer_contribution_rate), ['id' => 'employer_contribution_rate', 'class' => 'form-control numberValidation text-right', 'placeholder' => 'Employer Contribution Rate (%)', 'max' => '100']) }}
                                                </div>
                                            @endif
                                        </div>
                                        <hr>
                                    @endif
                                    {{-- EPF | Employee Provident Fund --}}

                                    {{-- ESI | Employees State Insurance --}}
                                    @if ($companyEsi != null)
                                        <hr>
                                        <div class="row">

                                            <div class="form-group col-md-12">
                                                <div class="form-check form-switch">
                                                    <label for="esi_status">
                                                        <h5 class="">{{ __('ESI | Employees State Insurance') }}
                                                        </h5>
                                                        Note: ESI deductions will be made only if the employee’s monthly
                                                        salary is less than or equal to ₹21,000. If the employee gets a
                                                        salary revision which increases their monthly salary above ₹21,000,
                                                        they would have to continue making ESI contributions till the end of
                                                        the contribution period in which the salary was revised
                                                        (April-September or October-March).
                                                    </label>
                                                    <input type="checkbox" class="form-check-input" id="esi_status"
                                                        name="esi_status"
                                                        {{ $employee->esi_status == 1 ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="esi_div"
                                            style="display: {{ $employee->esi_status == 0 ? 'none' : '' }};">
                                            <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                {{ Form::label('esi_number', __('Esi Account No'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('esi_number', old('esi_number', $employee->esi_number), ['id' => 'esi_number', 'class' => 'form-control', 'placeholder' => 'Esi Account No']) }}
                                            </div>
                                        </div>
                                        <hr>
                                    @endif
                                    {{-- ESI | Employees State Insurance --}}

                                    {{-- EPT | Employees Professional Tax --}}
                                    @if ($professional_tax_status == 1)
                                        <hr>
                                        <div class="row">

                                            <div class="form-group col-md-12">
                                                <div class="form-check form-switch">
                                                    <label for="ept_status">
                                                        <h5 class="">{{ __('EPT | Employees Professional Tax') }}
                                                        </h5>
                                                    </label>
                                                    <input type="checkbox" class="form-check-input" id="ept_status"
                                                        name="ept_status"
                                                        {{ $employee->ept_status == 1 ? 'checked' : '' }}>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="ept_div"
                                            style="display: {{ $employee->ept_number == 0 ? 'none' : '' }};">
                                            <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                {{ Form::label('ept_number', __('Ept Account No'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('ept_number', old('ept_number', $employee->ept_number), ['id' => 'ept_number', 'class' => 'form-control', 'placeholder' => 'Ept Account No']) }}
                                            </div>
                                        </div>
                                        <hr>
                                    @endif
                                    {{-- EPT | Employees Professional Tax --}}

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-Commission" role="tabpanel"
                            aria-labelledby="nav-Commission-tab">
                            @if (\Auth::user()->type != 'employee')
                                <div class="col-md-12 ">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Document') }}</h5>
                                        </div>
                                        <div class="card-body">

                                            @foreach ($documents as $key => $document)
                                                <div class="row">
                                                    <div class="form-group col-12 d-flex">
                                                        <div class="float-left col-4">
                                                            <label for="document"
                                                                class=" form-label">{{ $document->name }}
                                                                @if ($document->is_ == 1)
                                                                    <span class="text-danger">*</span>
                                                                @endif
                                                            </label>
                                                        </div>
                                                        <div class="float-right col-8">
                                                            <input type="hidden" name="emp_doc_id[{{ $document->id }}]"
                                                                id="" value="{{ $document->id }}">

                                                            @php
                                                                $employeedoc = !empty($employee->documents()) ? $employee->documents()->pluck('document_value', __('document_id')) : [];
                                                            @endphp
                                                            <div class="choose-files ">
                                                                <label for="document[{{ $document->id }}]">

                                                                    <div class=" bg-primary document "> <i
                                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                    </div>
                                                                    @trunc
                                                                    <input type="file"
                                                                        class="form-control file d-none @error('document') is-invalid @enderror"
                                                                        @if ($document->is_ == 1)  @endif
                                                                        name="document[{{ $document->id }}]"
                                                                        id="document[{{ $document->id }}]"
                                                                        data-filename="{{ $document->id . '_filename' }}"
                                                                        onchange="document.getElementById('{{ 'blah' . $key }}').src = window.URL.createObjectURL(this.files[0])">
                                                                </label>
                                                                @php
                                                                    $logo = \App\Models\Utility::get_file('uploads/document/');

                                                                @endphp
                                                                @if (!empty($employeedoc[$document->id]))
                                                                    @if (substr($employeedoc[$document->id], -4) == '.pdf')
                                                                        <span class="text-xs-1"><a
                                                                                href="{{ !empty($employeedoc[$document->id]) ? asset(Storage::url('uploads/document')) . '/' . $employeedoc[$document->id] : '' }}"
                                                                                target="_blank">{{ $employeedoc[$document->id] }}</a>
                                                                        </span>
                                                                    @else
                                                                        <img id="{{ 'blah' . $key }}"
                                                                            src="{{ isset($employeedoc[$document->id]) && !empty($employeedoc[$document->id]) ? $logo . '/' . $employeedoc[$document->id] : '' }}"
                                                                            width="50%" />
                                                                    @endif
                                                                @else
                                                                    <img id="{{ 'blah' . $key }}" src=""
                                                                        width="50%" />
                                                                @endif
                                                            </div>

                                                            {{-- @if (!empty($employeedoc[$document->id]))
                                                                <span class="text-xs-1"><a
                                                                        href="{{ !empty($employeedoc[$document->id]) ? asset(Storage::url('uploads/document')) . '/' . $employeedoc[$document->id] : '' }}"
                                                            target="_blank"></a>
                                                            </span>
                                                            @endif --}}
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>


                        <div class="tab-pane fade" id="nav-Experience" role="tabpanel"
                            aria-labelledby="nav-Experience-tab">


                            {{-- //Work Experience-TABLE --}}
                            @if (!empty($EXPTableData[0]))
                                @foreach ($EXPTableData as $key => $exp)
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h5>{{ __('Experience') }}</h5>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="text-end">
                                                            @if ($key == 0)
                                                                <p class="btn btn-primary" id='add_sub_form'>
                                                                    {{ '+' }}
                                                                </p>
                                                            @else
                                                                <a href="{{ route('exp_table_remove', $exp->id) }}"
                                                                    class="btn btn-danger"
                                                                    id='add_sub_form'>{{ 'remove' }}</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="card-body employee-detail-create-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        {!! Form::label('previous_company_name', __('Previous Company'), ['class' => 'form-label']) !!}
                                                        {!! Form::text('previous_company_name[]', $exp->previous_company_name, [
                                                            'class' => 'form-control',
                                                        
                                                            'placeholder' => 'Enter previous company',
                                                        ]) !!}

                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        {!! Form::label('previous_job_title', __('Job Title'), ['class' => 'form-label']) !!}
                                                        {!! Form::text('previous_job_title[]', $exp->previous_job_title, [
                                                            'class' => 'form-control',
                                                        
                                                            'placeholder' => 'Enter job title',
                                                        ]) !!}
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        {!! Form::label('previous_from_date', __('From Date'), ['class' => 'form-label']) !!}
                                                        <input type="date" name="previous_from_date[]"
                                                            value="{{ $exp->getRawOriginal('previous_from_date') }}"
                                                            class='form-control' max="<?php echo date('Y-m-d'); ?>">
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        {!! Form::label('previous_to_date', __('To Date'), ['class' => 'form-label']) !!}
                                                        <input type="date" name="previous_to_date[]"
                                                            value="{{ $exp->getRawOriginal('previous_to_date') }}"
                                                            class='form-control' max="<?php echo date('Y-m-d'); ?>">

                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        {{ Form::label('previous_company_designation_id', __('Previous Company designation'), ['class' => 'form-label']) }}
                                                        <input type="text" class='form-control'
                                                            name='previous_company_designation_id[]'
                                                            placeholder='Enter previous company designation'
                                                            value='{{ $exp->previous_company_designation_id }}'>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        {!! Form::label('experiance_Job_description', __('Experience Job Description'), ['class' => 'form-label']) !!}

                                                        {!! Form::textarea('experiance_Job_description[]', $exp->experiance_Job_description, [
                                                            'class' => 'form-control',
                                                        
                                                            'rows' => 2,
                                                            'placeholder' => 'Enter Experience job description',
                                                        ]) !!}
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h5>{{ __('Experience') }}</h5>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="text-end">
                                                        <p class="btn btn-primary" id='add_sub_form'>{{ '+' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <div class="card-body employee-detail-create-body">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    {!! Form::label('previous_company_name', __('Previous Company'), ['class' => 'form-label']) !!}
                                                    {!! Form::text('previous_company_name[]', null, [
                                                        'class' => 'form-control',
                                                    
                                                        'placeholder' => 'Enter previous company',
                                                    ]) !!}

                                                </div>
                                                <div class="form-group col-md-6">
                                                    {!! Form::label('previous_job_title', __('Job Title'), ['class' => 'form-label']) !!}
                                                    {!! Form::text('previous_job_title[]', null, [
                                                        'class' => 'form-control',
                                                    
                                                        'placeholder' => 'Enter job title',
                                                    ]) !!}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {!! Form::label('previous_from_date', __('From Date'), ['class' => 'form-label']) !!}
                                                    <input type="date" name="previous_from_date[]"
                                                        class='form-control' max="<?php echo date('Y-m-d'); ?>">

                                                </div>

                                                <div class="form-group col-md-6">
                                                    {!! Form::label('previous_to_date', __('To Date'), ['class' => 'form-label']) !!}
                                                    <input type="date" name="previous_to_date[]" class='form-control'
                                                        max="<?php echo date('Y-m-d'); ?>">

                                                </div>

                                                <div class="form-group col-md-12">
                                                    {{ Form::label('previous_company_designation_id', __('Previous Company designation'), ['class' => 'form-label']) }}
                                                    <input type="text" class='form-control'
                                                        name='previous_company_designation_id'
                                                        id='previous_company_designation_id'
                                                        placeholder='Enter previous company designation'>

                                                </div>

                                                <div class="form-group col-md-12">
                                                    {!! Form::label('experiance_Job_description', __('Experience Job Description'), ['class' => 'form-label']) !!}
                                                    {!! Form::textarea('experiance_Job_description[]', null, [
                                                        'class' => 'form-control',
                                                        'rows' => 2,
                                                        'placeholder' => 'Enter Experience job description',
                                                    ]) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div id='append_sub_form'></div>



                        </div>

                        <div class="tab-pane fade" id="nav-Education" role="tabpanel"
                            aria-labelledby="nav-Education-tab">

                            {{-- //Education tables --}}

                            @if (count($EducationData) > 0)
                                @foreach ($EducationData as $key => $edu)
                                    <div class="col-md-12 ">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h5>{{ __('Education') }}</h5>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="text-end">
                                                            @if ($key == 0)
                                                                <p class="btn btn-primary"
                                                                    id='append_sub_form_eduction_table'>
                                                                    {{ '+' }}
                                                                </p>
                                                            @else
                                                                <a href="{{ route('eduction_table_remove', $edu->id) }}"
                                                                    class="btn btn-danger"
                                                                    id='add_sub_form'>{{ 'remove' }}</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            <div class="card-body employee-detail-create-body">
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        {!! Form::label('institute_name', __('Institute Name'), ['class' => 'form-label']) !!}
                                                        {!! Form::text('institute_name[]', $edu->institute_name, [
                                                            'class' => 'form-control',
                                                            'placeholder' => 'Enter institute name',
                                                        ]) !!}

                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        {!! Form::label('education', __('Education'), ['class' => 'form-label']) !!}
                                                        {!! Form::text('education[]', $edu->education, [
                                                            'class' => 'form-control',
                                                        
                                                            'placeholder' => 'Enter education',
                                                        ]) !!}

                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        {!! Form::label('specialization', __('Specialization'), ['class' => 'form-label']) !!}
                                                        {!! Form::text('specialization[]', $edu->specialization, [
                                                            'class' => 'form-control',
                                                        
                                                            'placeholder' => 'Enter specialization',
                                                        ]) !!}
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        {!! Form::label('date_of_completion', __('Date of birth'), ['class' => 'form-label']) !!}
                                                        <input type="date" name="date_of_completion[]"
                                                            value="{{ $edu->getRawOriginal('date_of_completion') }}"
                                                            class='form-control' max="<?php echo date('Y-m-d'); ?>">

                                                    </div>



                                                    <div class="form-group col-md-12">
                                                        {!! Form::label('notes', __('Notes'), ['class' => 'form-label']) !!}

                                                        {!! Form::textarea('notes[]', $edu->notes, [
                                                            'class' => 'form-control',
                                                            'rows' => 2,
                                                            'placeholder' => 'Enter notes',
                                                        ]) !!}
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-12 ">
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h5>{{ __('Education') }}</h5>

                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="text-end">
                                                        @php $i = 0; @endphp
                                                        @if ($i == 0)
                                                            <p class="btn btn-primary"
                                                                id='append_sub_form_eduction_table'>
                                                                {{ '+' }}
                                                            </p>
                                                        @else
                                                            <a href="{{ route('eduction_table_remove', null) }}"
                                                                class="btn btn-danger"
                                                                id='add_sub_form'>{{ 'remove' }}</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="card-body employee-detail-create-body">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    {!! Form::label('institute_name', __('Institute Name'), ['class' => 'form-label']) !!}
                                                    {!! Form::text('institute_name[]', null, ['class' => 'form-control', 'placeholder' => 'Enter institute name']) !!}
                                                </div>
                                                <div class="form-group col-md-6">
                                                    {!! Form::label('education', __('Education'), ['class' => 'form-label']) !!}
                                                    {!! Form::text('education[]', null, [
                                                        'class' => 'form-control',
                                                        'placeholder' => 'Enter education',
                                                    ]) !!}

                                                </div>
                                                <div class="form-group col-md-6">
                                                    {!! Form::label('specialization', __('Specialization'), ['class' => 'form-label']) !!}
                                                    {!! Form::text('specialization[]', null, [
                                                        'class' => 'form-control',
                                                    
                                                        'placeholder' => 'Enter specialization',
                                                    ]) !!}
                                                </div>

                                                <div class="form-group col-md-6">
                                                    {!! Form::label('date_of_completion', __('Date of birth'), ['class' => 'form-label']) !!}
                                                    <input type="date" name="date_of_completion[]"
                                                        class='form-control' max="<?php echo date('Y-m-d'); ?>">

                                                </div>



                                                <div class="form-group col-md-12">
                                                    {!! Form::label('notes', __('Notes'), ['class' => 'form-label']) !!}

                                                    {!! Form::textarea('notes[]', null, [
                                                        'class' => 'form-control',
                                                        'rows' => 2,
                                                        'placeholder' => 'Enter notes',
                                                    ]) !!}
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div id='append_sub_form_eduction_table_add_form'></div>

                        </div>

                        <div class="tab-pane fade" id="nav-Dependent" role="tabpanel"
                            aria-labelledby="nav-Dependent-tab">


                            {{-- //Dependenttables --}}

                            @if (!empty($DependentData[0]))
                                @foreach ($DependentData as $key => $exp)
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <h5>{{ __('Dependent') }}</h5>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="text-end">
                                                            @if ($key == 0)
                                                                <p class="btn btn-primary"
                                                                    id='Dependent_sub_form_eduction_table'>
                                                                    {{ '+' }}
                                                                </p>
                                                            @else
                                                                <a href="{{ route('Dependent_table_remove', $exp->id) }}"
                                                                    class="btn btn-danger"
                                                                    id='add_sub_form'>{{ 'remove' }}</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body employee-detail-create-body">
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        {!! Form::label('dependent_name', __('Name'), ['class' => 'form-label']) !!}
                                                        {!! Form::text('dependent_name[]', $exp->dependent_name, [
                                                            'class' => 'form-control dependent_name',
                                                            'placeholder' => 'Enter dependent name',
                                                        ]) !!}

                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        {{ Form::label('dependent_relation', __('Relation Types '), ['class' => 'form-label']) }}<span
                                                            class="text-danger pl-1 relation_type_label"
                                                            style="display: {{ empty($exp->dependent_name) ? 'none' : '' }}">
                                                            *</span>
                                                        <div class="form-icon-user">
                                                            <select name="dependent_relation[]" id="dependent_relation"
                                                                class="form-control select2">

                                                                <option
                                                                    @if ($exp->dependent_relation == 'Permanent') selected @else @endif
                                                                    value="Father">Father</option>
                                                                <option
                                                                    @if ($exp->dependent_relation == 'Mother') selected @else @endif
                                                                    value="Mother">Mother</option>
                                                                <option
                                                                    @if ($exp->dependent_relation == 'Brother') selected @else @endif
                                                                    value="Brother">Brother</option>
                                                                <option
                                                                    @if ($exp->dependent_relation == 'Sister') selected @else @endif
                                                                    value="Sister">Sister</option>
                                                                <option
                                                                    @if ($exp->dependent_relation == 'Husband') selected @else @endif
                                                                    value="Husband">Husband</option>
                                                                <option
                                                                    @if ($exp->dependent_relation == 'Wife') selected @else @endif
                                                                    value="Wife">Wife</option>
                                                                <option
                                                                    @if ($exp->dependent_relation == 'Child') selected @else @endif
                                                                    value="Child">Child</option>

                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="form-group col-md-12">
                                                        {!! Form::label('dependent_dob', __('Date of birth'), ['class' => 'form-label']) !!}
                                                        <input type="date" name="dependent_dob[]"
                                                            value="{{ $exp->getRawOriginal('dependent_dob') }}"
                                                            class='form-control' max="<?php echo date('Y-m-d'); ?>">

                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        {!! Form::label('dependent_dob', __('Emergency phone no.'), ['class' => 'form-label']) !!}
                                                        {{ Form::number('emergency_phone_no[]', $exp->emergency_phone_no, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Emergency phone no']) }}
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>{{ __('Dependent') }}</h5>
                                            <div class="float-end">

                                                <p class="btn btn-primary" id='Dependent_sub_form_eduction_table'>
                                                    {{ '+' }}
                                                </p>

                                            </div>
                                        </div>
                                        <div class="card-body employee-detail-create-body">
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    {!! Form::label('dependent_name', __('Name'), ['class' => 'form-label']) !!}
                                                    {!! Form::text('dependent_name[]', null, [
                                                        'class' => 'form-control dependent_name',
                                                        'placeholder' => 'Enter dependent name',
                                                    ]) !!}

                                                </div>

                                                <div class="form-group col-md-12">
                                                    {{ Form::label('dependent_relation', __('Relation Types '), ['class' => 'form-label']) }}
                                                    <span class="text-danger pl-1 relation_type_label"
                                                        style="display:none"> *</span>
                                                    <div class="form-icon-user">
                                                        <select name="dependent_relation[]" id="dependent_relation"
                                                            class="form-control select2">
                                                            <option value="">Select option</option>
                                                            <option value="Father">Father</option>
                                                            <option value="Mother">Mother</option>
                                                            <option value="Brother">Brother</option>
                                                            <option value="Sister">Sister</option>
                                                            <option value="Husband">Husband</option>
                                                            <option value="Wife">Wife</option>
                                                            <option value="Child">Child</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    {!! Form::label('dependent_dob', __('Date of birth'), ['class' => 'form-label']) !!}
                                                    <input type="date" name="dependent_dob[]" class='form-control'
                                                        max="<?php echo date('Y-m-d'); ?>">
                                                </div>

                                                <div class="form-group col-md-12">
                                                    {!! Form::label('dependent_dob', __('Emergency phone no.'), ['class' => 'form-label']) !!}
                                                    {{ Form::number('emergency_phone_no[]', null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Emergency phone no']) }}
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div id='Dependent_sub_form_eduction_table_add_form'></div>
                        </div>

                        <div class="tab-pane fade" id="nav-Loan" role="tabpanel" aria-labelledby="nav-Loan-tab">

                            {{-- // --}}

                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Bank Account') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                {!! Form::label('account_holder_name', __('Account Holder Name'), ['class' => 'form-label']) !!}
                                                <span class="text-danger">*</span>
                                                {!! Form::text('account_holder_name', null, ['class' => 'form-control']) !!}

                                            </div>
                                            <div class="form-group col-md-6">
                                                {!! Form::label('account_number', __('Account Number'), ['class' => 'form-label']) !!}
                                                <span class="text-danger">*</span>
                                                {!! Form::number('account_number', null, ['class' => 'form-control']) !!}

                                            </div>
                                            <div class="form-group col-md-6">
                                                {!! Form::label('bank_name', __('Bank Name'), ['class' => 'form-label']) !!}
                                                {!! Form::text('bank_name', null, ['class' => 'form-control']) !!}

                                            </div>
                                            <div class="form-group col-md-6">
                                                {!! Form::label('bank_identifier_code', __('Bank Identifier Code'), ['class' => 'form-label']) !!}
                                                <span class="text-danger">*</span>
                                                {!! Form::text('bank_identifier_code', null, ['class' => 'form-control']) !!}
                                            </div>
                                            <div class="form-group col-md-6">
                                                {!! Form::label('branch_location', __('Branch Location'), ['class' => 'form-label']) !!}
                                                <span class="text-danger">*</span>
                                                {!! Form::text('branch_location', null, ['class' => 'form-control']) !!}
                                            </div>
                                            <div class="form-group col-md-6">
                                                {!! Form::label('tax_payer_id', __('Tax Payer Id'), ['class' => 'form-label']) !!}
                                                {!! Form::text('tax_payer_id', null, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        @if (\Auth::user()->type != 'employee')
                            <div class="float-end">
                                <button type="submit" class="btn  btn-primary">{{ 'Update' }}</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>



    <div class="row">
        <div class="">
            <div class="">


                <div class="row">
                    <div class="col-md-6 ">

                    </div>

                </div>
                @if (\Auth::user()->type != 'employee')
                    <div class="row">
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-6 ">
                            <div class="employee-detail-wrap">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Document Detail') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @php
                                                $employeedoc = $employee->documents()->pluck('document_value', __('document_id'));
                                                $logo = \App\Models\Utility::get_file('uploads/document/');

                                            @endphp
                                            @foreach ($documents as $key => $document)
                                                <div class="col-md-12">
                                                    <div class="info">

                                                        <strong>{{ $document->name }}</strong>
                                                        <span><a href="{{ !empty($employeedoc[$document->id]) ? $logo . '/' . $employeedoc[$document->id] : '' }}"
                                                                target="_blank">{{ !empty($employeedoc[$document->id]) ? $employeedoc[$document->id] : '' }}</a></span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="employee-detail-wrap">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ __('Bank Account Detail') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info">
                                                    <strong>{{ __('Account Holder Name') }}</strong>
                                                    <span>{{ $employee->account_holder_name }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info font-style">
                                                    <strong>{{ __('Account Number') }}</strong>
                                                    <span>{{ $employee->account_number }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info font-style">
                                                    <strong>{{ __('Bank Name') }}</strong>
                                                    <span>{{ $employee->bank_name }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info">
                                                    <strong>{{ __('Bank Identifier Code') }}</strong>
                                                    <span>{{ $employee->bank_identifier_code }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info">
                                                    <strong>{{ __('Branch Location') }}</strong>
                                                    <span>{{ $employee->branch_location }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info">
                                                    <strong>{{ __('Tax Payer Id') }}</strong>
                                                    <span>{{ $employee->tax_payer_id }}</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif


                <div class="col-12">
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script type="text/javascript">
        function getDesignation(did) {


            $.ajax({
                url: "{{ route('employee.json') }}",
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('.designation_id').empty();
                    var emp_selct = `<select class="form-control col-md-12 designation_id select2" name="designation_id" id="choices-multiple"
                                            placeholder="Select Designation" >
                                            </select>`;
                    $('.designation_div').html(emp_selct);
                    $('.designation_id').append('<option value="">Select any Designation</option>');
                    $.each(data, function(key, value) {
                        var select = '';
                        if (key == '{{ $employee->designation_id }}') {
                            select = 'selected';
                        }

                        $('.designation_id').append('<option value="' + key + '"  ' + select + '>' +
                            value + '</option>');
                    });
                    new Choices('#choices-multiple', {
                        removeItemButton: true,
                    });
                }
            });
        }

        function calculateSalary() {
            var salary = 0;

            try {
                var ctc_annual = $("#ctc_annual").val() ?? 0;
                var ctc_monthely = parseFloat(ctc_annual / 12).toFixed(2);
                $("#ctc_monthely").val(ctc_monthely);

                var ctc_percentage = parseFloat($("#ctc_percentage").val() ?? 0).toFixed(2);
                if (isNaN(ctc_percentage)) {
                    ctc_percentage = 0;
                }

                var salary = parseFloat(parseFloat(ctc_monthely * ctc_percentage / 100)).toFixed(0);
                $("#salary").val(salary);
            } catch (error) {
                console.log("error", error);
                $("#salary").val(0);
            }
        }

        $(document).ready(function() {

            $('.numberValidation').on('input keypress keyup paste dragover', function(e) {
                // Check if the entered value is a valid number
                var inputValue = $(this).val() + String.fromCharCode(e.which);
                if (!$.isNumeric(inputValue)) {
                    // Prevent the input if it's not a number
                    e.preventDefault();
                }
                calculateSalary();
            });


            //branch_id
            $(document).on('change', 'select[name=branch_id]', function() {
                var branch_id = $(this).val();

                $.ajax({
                    url: "{{ route('employee.Branch_department_json') }}",
                    type: 'POST',
                    data: {
                        "branch_id": branch_id,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        $('#department_id').empty('');
                        $('#department_id').append(
                            '<option value="">{{ __('Select Department ') }}</option>');
                        $.each(data, function(key, value) {
                            console.log('<option value="' + key + '">' + value +
                                '</option>');
                            $('#department_id').append('<option value="' + key + '">' +
                                value + '</option>');
                        });
                    }
                });
            });

            var checked = $('#office_staff').find(":selected").val();

            if (checked == 1) {
                $('.select_grade_type').addClass('d-none');
            } else {
                if (checked == '') {
                    $('.select_grade_type').addClass('d-none');
                } else {
                    $('.select_grade_type').removeClass('d-none');
                }
            }

            //check select_office_staff
            $(document).on('change', '#office_staff', function() {
                var office_staff = $(this).val();
                if (office_staff == 2) {
                    $('.select_grade_type').removeClass('d-none');
                } else {
                    $('#grade_type_id').val('');
                    $('.select_grade_type').addClass('d-none');
                }
            });
            $("#employee_update").submit(function(e) {
                event.preventDefault();
                var form_cust = this;
                var formData = new FormData(form_cust);
                var url = $(this).attr('action');
                var type = "POST";
                $.ajax({
                    context: 'commonModal',
                    type: type,
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 1) {
                            show_toastr('Success', response.message, 'success');
                            window.location.href = "{{ route('employee.index') }}";
                        }
                        if (response.status == 0) {
                            $.each(response.message, function(key, value) {
                                toastr.error(value)
                            });
                        }
                    },
                })
                return false;
            });

            var now = new Date();
            var month = (now.getMonth() + 1);
            var day = now.getDate();
            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;
            var today = now.getFullYear() + '-' + month + '-' + day;


            var d_id = $('#department_id').val();
            var designation_id = '{{ $employee->designation_id }}';
            getDesignation(d_id);


            //autometically ajax call
            //  var country_id = $('#select_roles').val();
            //     var selct = ''
            //     var state_id = {{ $employee->users_types_id }}
            //     $.ajax({
            //         type:'get',
            //         url: '{{ url('get_users_data') }}' + '/' + country_id,
            //         data:{},
            //             success:function(state){
            //             $.each(state, function (key, value) {
            //                     if(value.id == state_id){
            //                         selct  = 'selected';
            //                     }else{
            //                         selct  = '';
            //                     }
            //                     $("#select_users").append('<option ' + selct + ' value="' + value.id + '">' + value.name + '</option>');
            //             });
            //         }
            // });

            // $("#select_roles").change(function() {

            //     var id = $('#select_roles').val();
            //     $.ajax({
            //         type: 'get',
            //         url: '{{ url('get_users_data') }}' + '/' + id,
            //         data: {},
            //         success: function(state) {
            //             $('#select_users').html('');
            //             $('#select_users').append('<option value="">Select a users</option>');
            //             $.each(state, function(key, value) {
            //                 $("#select_users").append('<option value="' + value
            //                 .id + '">' + value.name + '</option>');
            //             });
            //         }
            //     });
            // });



            //ajax
            var index = "{{ $EXPTableDatacount }}";
            $("#add_sub_form").click(function() {

                var now = new Date();
                var month = (now.getMonth() + 1);
                var day = now.getDate();
                if (month < 10) month = "0" + month;
                if (day < 10) day = "0" + day;
                var today = now.getFullYear() + '-' + month + '-' + day;


                index++;
                var designations = <?php echo json_encode($designations); ?>;

                $('#append_sub_form').append('<div class="col-md-12" id="delete_sub_form">' +
                    '<div class="card">' +
                    '<div class="card-header">' +
                    '<h5>Experience</h5>' +
                    '<p class="btn btn-danger float-end" id="programings_btn_remove">- remove</p>' +
                    '</div>' + '<div class="card-body employee-detail-create-body">' +
                    '<div class="row">' +
                    '<div class="form-group col-md-6">' +
                    '<lable class="form-label">Previous Company</lable>' +
                    '<input type="text" name="previous_company_name[' + index +
                    ']" class="form-control" placeholder="Enter previous company" ></input>' +
                    '</div>' +
                    '<div class="form-group col-md-6">' +
                    '<lable class="form-label">Job Title</lable>' +
                    '<input type="text" name="previous_job_title[' + index +
                    ']" class="form-control" placeholder="Enter previous job title"></input>' +
                    '</div>' +
                    '<div class="form-group col-md-6">' +
                    '<lable class="form-label">From Date</lable>' +
                    '<input type="date" max=' + today + ' name="previous_from_date[' + index +
                    ']" class="form-control" placeholder="Enter previous job title"></input>' +
                    '</div>' +
                    '<div class="form-group col-md-6">' +
                    '<lable class="form-label">To Date</lable>' +
                    '<input type="date" max=' + today + ' name="previous_to_date[' + index +
                    ']" class="form-control" placeholder="Enter previous job title"></input>' +
                    '</div>' +

                    '<div class="form-group col-md-12">' +

                    '<lable class="form-label">Previous Company designation</lable>' +
                    '<input type="text" name="previous_company_designation_id[' + index +
                    ']" id="previous_company_designation_id[' + index +
                    ']"class="form-control" placeholder="Enter previous company designation"></input>' +
                    '</div>' +
                    '<div class="form-group col-md-12">' +
                    '<lable class="form-label">Experience Job Description</lable>' +
                    '<textarea type="text" name="experiance_Job_description[' + index +
                    ']" class="form-control" placeholder="Enter Experience job description"></textarea>' +
                    '</div>' +
                    '</div></div></div></div>');
            });
            $(document).on('click', '#programings_btn_remove', function() {
                $(this).closest('div#delete_sub_form').remove();
            });
        });

        //education table
        var eduction_table = "{{ $EducationDatacount }}";
        $("#append_sub_form_eduction_table").click(function() {


            var now = new Date();
            var month = (now.getMonth() + 1);
            var day = now.getDate();
            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;
            var today = now.getFullYear() + '-' + month + '-' + day;

            eduction_table++;

            $('#append_sub_form_eduction_table_add_form').append(
                '<div class="col-md-12" id="delete_sub_form_eduction_table">' +
                '<div class="card">' +
                '<div class="card-header">' +
                '<h5>Education</h5>' +
                '<p class="btn btn-danger float-end" id="programings_btn_remove_eduction_table">- remove</p>' +
                '</div>' + '<div class="card-body employee-detail-create-body">' +
                '<div class="row">' +
                '<div class="form-group col-md-6">' +
                '<lable class="form-label">Institute Name</lable>' +
                '<input type="text" name="institute_name[' + eduction_table +
                ']" class="form-control" placeholder="Enter institute name" ></input>' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<lable class="form-label">Education</lable>' +
                '<input type="text" name="education[' + eduction_table +
                ']" class="form-control" placeholder="Enter previous job title"></input>' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<lable class="form-label">Specialization</lable>' +
                '<input type="text" name="specialization[' + eduction_table +
                ']" class="form-control" placeholder="Enter specialization"></input>' +
                '</div>' +
                '<div class="form-group col-md-6">' +
                '<lable class="form-label">Date of birth</lable>' +
                '<input type="date" max=' + today + ' name="date_of_completion[' + eduction_table +
                ']" class="form-control" placeholder="Enter previous job title"></input>' + '</div>' +

                '<div class="form-group col-md-12">' +
                '<lable class="form-label">Notes</lable>' +
                '<textarea type="text" name="notes[' + eduction_table +
                ']" class="form-control" placeholder="Enter notes"></textarea>' +
                '</div>' +
                '</div></div></div></div>');
        });
        $(document).on('click', '#programings_btn_remove_eduction_table', function() {
            $(this).closest('div#delete_sub_form_eduction_table').remove();
        });

        //Dependent
        //Dependent-TABLE add-more
        var Dependent = "{{ $DependentDatacount }}";
        $("#Dependent_sub_form_eduction_table").click(function() {


            var now = new Date();
            var month = (now.getMonth() + 1);
            var day = now.getDate();
            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;
            var today = now.getFullYear() + '-' + month + '-' + day;

            Dependent++;

            $('#Dependent_sub_form_eduction_table_add_form').append(
                '<div class="col-md-12" id="delete_programings_btn_remove_Dependent_table">' +
                '<div class="card">' +
                '<div class="card-header">' +
                '<h5>Dependent</h5>' +
                '<p class="btn btn-danger float-end" id="programings_btn_remove_Dependent_table">- remove</p>' +
                '</div>' + '<div class="card-body employee-detail-create-body">' +
                '<div class="row">' +
                '<div class="form-group col-md-12">' +
                '<lable class="form-label">Name</lable>' +
                '<input type="text" name="dependent_name[' + Dependent +
                ']" class="form-control dependent_name" placeholder="Enter dependent name" ></input>' +
                '</div>' +
                '<div class="form-group col-md-12">' +
                '<lable class="form-label">Education</lable>' +
                '<span class="text-danger pl-1 relation_type_label" style="display:none"> *</span>' +
                '<select  name="dependent_relation[' + Dependent + ']" id="dependent_relation' + Dependent +
                '" class="form-control select2">' +
                '<option value="">-Select Relation</option>' +
                '<option vale="Father">Father</option>' +
                '<option value="Mother">Mother</option>' +
                '<option value="Brother">Brother</option>' +
                '<option value="Sister">Sister</option>' +
                '<option value="Husband">Husband</option>' +
                '<option value="Wife">Wife</option>' +
                '<option value="Child">Child</option>' +
                '</select>' +
                '</div>' +
                '<div class="form-group col-md-12">' +
                '<lable class="form-label">Date of birth</lable>' +
                '<input type="date" max=' + today + ' name="dependent_dob[' + Dependent +
                ']" class="form-control" placeholder="Enter specialization"></input>' +
                '</div>' +

                '<div class="form-group col-md-12">' +
                '<lable class="form-label">Emergency phone no.</lable>' +
                '<input type="number" name="emergency_phone_no[' + Dependent +
                ']" class="form-control" placeholder="Emergency phone no."></input>' +
                '</div>' +
                '</div></div></div></div>');
        });
        $(document).on('click', '#programings_btn_remove_Dependent_table', function() {
            $(this).closest('div#delete_programings_btn_remove_Dependent_table').remove();
        });


        $(document).on('change', 'select[name=department_id]', function() {
            var department_id = $(this).val();
            getDesignation(department_id);
        });

        $('.dependent_name').on('input', function() {

            var inputValue = $(this).val();

            var closestRequiredLabel = $(this).parent().parent().find('.relation_type_label');
            //console.log('closestRequiredLabel:', closestRequiredLabel);

            if ($.trim(inputValue) == '') {
                closestRequiredLabel.hide();
            } else {
                closestRequiredLabel.show();
            }
        });
    </script>

    <script>
        $('#epf_status').change(function() {
            if ($(this).prop('checked')) {
                $("#epf_div").show();
            } else {
                $("#epf_div").hide();
            }
        });

        $('#esi_status').change(function() {
            if ($(this).prop('checked')) {
                $("#esi_div").show();
            } else {
                $("#esi_div").hide();
            }
        });

        $('#ept_status').change(function() {
            if ($(this).prop('checked')) {
                $("#ept_div").show();
            } else {
                $("#ept_div").hide();
            }
        });
    </script>
@endpush
