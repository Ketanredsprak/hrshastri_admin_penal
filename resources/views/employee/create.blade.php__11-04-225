@extends('layouts.admin')
@section('page-title')
    {{ __('Create Employee') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('employee') }}">{{ __('Employee') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create Employee') }}</li>
@endsection

@section('header-section')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/main.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }

        .selected-event {
            position: relative;
            background-color: #4caf50;
            /* Green fill for selected dates */
            /* color: #fff; */
            height: 100%;
            width: 100%
        }

        .delete-button {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #fff;
            border: none;
            cursor: pointer;
            z-index: 10;
            padding: 5px;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .delete-button .material-icons {
            color: #f44336;
            /* Red color for the delete icon */
            font-size: 18px;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            {{ Form::open(['route' => ['employee.store'], 'method' => 'post', 'id' => 'employee_create', 'enctype' => 'multipart/form-data']) }}
            <div class="card">
                <div class="card-body">
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
                                aria-controls="nav-salary-and-deductions" aria-selected="false">Salary Structure</button>

                            <button class="nav-link" id="nav-Experience-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Experience" type="button" role="tab"
                                aria-controls="nav-Experience" aria-selected="false">Experience</button>
                            <button class="nav-link" id="nav-Education-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Education" type="button" role="tab" aria-controls="nav-Education"
                                aria-selected="false"> Education</button>
                            <button class="nav-link" id="nav-Dependent-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Dependent" type="button" role="tab" aria-controls="nav-Dependent"
                                aria-selected="false">Dependent</button>
                            <button class="nav-link" id="nav-document-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-document" type="button" role="tab" aria-controls="nav-document"
                                aria-selected="false">Document</button>
                            <button class="nav-link" id="nav-Account-tab" data-bs-toggle="tab" data-bs-target="#nav-Account"
                                type="button" role="tab" aria-controls="nav-Account" aria-selected="false">Bank
                                Account</button>

                            <button class="nav-link" id="nav-weekoff-tab" data-bs-toggle="tab" data-bs-target="#nav-weekoff"
                                type="button" role="tab" aria-controls="nav-weekoff" aria-selected="false">Week
                                off</button>
                        </div>
                    </nav>
                    <div class="tab-content tab_ctn" id="nav-tabContent">

                        <div class="tab-pane fade active show" id="nav-personal-details" role="tabpanel"
                            aria-labelledby="nav-personal-details-tab">
                            <div
                                class="card                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ">
                                <div class="card-header">
                                    <h5>{{ __('Personal Detail') }}</h5>
                                </div>
                                <div class="card-body">

                                    <div class="row">

                                        <div class="form-group col-md-6">
                                            {!! Form::label('employee_id', __('Employee Id'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::text('employee_id', old('employee_id'), [
                                                'class' => 'form-control',
                                            
                                                'placeholder' => 'Enter employee id',
                                            ]) !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('name', __('Name'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::text('name', old('name'), [
                                                'class' => 'form-control',
                                            
                                                'placeholder' => 'Enter employee name',
                                            ]) !!}
                                        </div>

                                        <div class="form-group col-md-4">
                                            {!! Form::label('phone', __('Phone'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::text('phone', old('phone'), [
                                                'class' => 'form-control',
                                                'placeholder' => 'Enter employee phone',
                                                'maxlength' => 10,
                                            ]) !!}
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('dob', __('Date of Birth '), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                                <input type="date" name="dob" class='form-control current_date'
                                                    max="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Form::label('employee_profile', __('Upload Employee Profile Picture'), ['class' => 'form-label']) !!}
                                                <input type="file" class="form-control" id="employee_profile"
                                                    name="employee_profile" accept="image/*">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                                <div class="d-flex radio-check">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="g_male" value="Male"
                                                            name="gender" class="form-check-input" checked>
                                                        <label class="form-check-label "
                                                            for="g_male">{{ __('Male') }}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio ms-1 custom-control-inline">
                                                        <input type="radio" id="g_female" value="Female"
                                                            name="gender" class="form-check-input">
                                                        <label class="form-check-label "
                                                            for="g_female">{{ __('Female') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            {{ Form::label('marital_status', __('Select Marital'), ['class' => 'form-label']) }}
                                            <div class="form-icon-user">
                                                <select name="marital_status" id="marital_status"
                                                    class="form-control select2">
                                                    <option value="">-Select Marital-</option>
                                                    <option value="Single">Single</option>
                                                    <option value="Married">Married</option>
                                                    <option value="Widowed">Widowed</option>
                                                    <option value="Separated">Separated</option>
                                                    <option value="Divorced">Divorced</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 marriage_anniversary_date_div"
                                            style="display: none;">
                                            {!! Form::label('marriage_anniversary_date', __('Marriage Anniversary Date '), ['class' => 'form-label']) !!}
                                            <input type="date" name="marriage_anniversary_date"
                                                class="form-control current_date" max="<?php echo date('Y-m-d'); ?>">
                                        </div>


                                        <div class="form-group col-md-6">
                                            {!! Form::label('email', __('Email'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Enter employee email']) !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('personal_email', __('Personal Email'), ['class' => 'form-label']) !!}
                                            {!! Form::email('personal_email', old('personal_email'), ['class' => 'form-control', 'placeholder' => 'Enter employee personal email']) !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('password', __('Password'), ['class' => 'form-label']) !!}<span class="text-danger pl-1 password">*</span>
                                            <div class="password_box">
                                                {!! Form::password('password', [
                                                    'class' => 'form-control enterpassword',
                                                    'placeholder' => 'Enter password',
                                                ]) !!}
                                                <div class="fa fa-eye icon_password"></div>
                                            </div>

                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('password', __('Confirm password'), ['class' => 'form-label']) !!}<span class="text-danger pl-1 password">*</span>
                                            <div class="password_box">
                                                {!! Form::password('password_confirmation', [
                                                    'class' => 'form-control password',
                                                    'placeholder' => 'Enter confirm password',
                                                ]) !!}
                                                <div class="fa fa-eye icon_Confirmpassword"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('address', __('Address'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                        {!! Form::textarea('address', old('address'), [
                                            'class' => 'form-control',
                                            'rows' => 2,
                                            'placeholder' => 'Enter employee address',
                                        ]) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-Allowance" role="tabpanel"
                            aria-labelledby="nav-Allowance-tab">
                            <div
                                class="card                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ">
                                <div class="card-header">
                                    <h5>{{ __('Company Detail') }}</h5>
                                </div>
                                <div class="card-body employee-detail-create-body">
                                    <div class="row">
                                        @csrf
                                        {{-- <div class="form-group ">
                                            {!! Form::label('employee_id', __('Employee ID'), ['class' => 'form-label']) !!}
                                            {!! Form::text('employee_id', $employeesId, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                        </div> --}}

                                        <div class="form-group col-md-6">
                                            {{ Form::label('branch_id', __('Select Branch'), ['class' => 'form-label']) }}
                                            <span class="text-danger pl-1">*</span>
                                            <div class="form-icon-user">

                                                <div class="col-lg-6 col-md-6 col-sm-6" id="geofencing_div">
                                                    <div class="form-check form-switch">
                                                        <label for="geofencing_status">
                                                            <h5 class="">{{ __('Enable Geofencing') }}
                                                            </h5>
                                                        </label>
                                                        <input type="checkbox" class="form-check-input"
                                                            id="geofencing_status" name="geofencing_status" disabled>
                                                    </div>
                                                </div>

                                                {{ Form::select('branch_id', $branches->pluck('name', 'id'), null, ['class' => 'form-control select2', 'id' => 'branch_id', 'placeholder' => 'Select Branch']) }}
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            {{ Form::label('department_id', __('Select Department'), ['class' => 'form-label']) }}<span
                                                class="text-danger pl-1">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('department_id', $departments, null, ['class' => 'form-control', 'id' => 'department_id', 'placeholder' => 'Select Department']) }}
                                            </div>
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
                                        <div class="form-group col-md-6">
                                            {{ Form::label('branch_id', __('Employee Status '), ['class' => 'form-label']) }}<span
                                                class="text-danger pl-1">*</span>
                                            <div class="form-icon-user">
                                                <select name="employe_status" id="employe_status"
                                                    class="form-control select2">
                                                    <option value="">-Select Employee Status-</option>
                                                    <option value="Active">Active</option>
                                                    <option value="Terminated">Terminated</option>
                                                    <option value="Deceased">Deceased</option>
                                                    <option value="Resigned">Resigned</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('employe_types', __('Employee Types '), ['class' => 'form-label']) }}
                                            <span class="text-danger pl-1">*</span>
                                            <div class="form-icon-user">
                                                <select name="employe_types" id="employe_types"
                                                    class="form-control select2">
                                                    <option value="">-Select Employee Status-</option>
                                                    <option value="Permanent">Permanent</option>
                                                    <option value="Temporary">Temporary</option>
                                                    <option value="On Contract">On Contract</option>
                                                    <option value="Trainee">Trainee</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('Experience', __('Experience'), ['class' => 'form-label']) !!}
                                            {!! Form::text('employee_experience', old('employee_experience'), [
                                                'class' => 'form-control',
                                                'placeholder' => 'Enter Experience',
                                            ]) !!}
                                        </div>

                                        <div class="form-group col-md-6">
                                            {!! Form::label('Job_description', __('Job Description'), ['class' => 'form-label']) !!}
                                            {!! Form::textarea('Job_description', old('Job_description'), [
                                                'class' => 'form-control',
                                                'rows' => 2,
                                                'placeholder' => 'Enter job description',
                                            ]) !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('company_doj', __('Company date of joining'), ['class' => ' form-label']) !!}<span class="text-danger pl-1">*</span>
                                            <input type="date" name="company_doj" value='{{ old('company_doj') }}'
                                                class='form-control' max="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('date_of_exit', __('Date on Exit'), ['class' => 'form-label']) !!}
                                            <input type="date" name="date_of_exit" value='{{ old('date_of_exit') }}'
                                                class='form-control' max="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            {!! Form::label('company_doj', 'Probation Period Date', ['class' => 'form-label']) !!} <span class="text-danger pl-1">*</span>
                                            <input type="date" name="probation_period_date" class='form-control'>
                                        </div>

                                        <div class="form-group col-sm-6">
                                            {!! Form::label('assign_shifts', 'Assign Shift', ['class' => 'form-label']) !!}
                                            <select name="assign_shifts[]" class="select2" id="assign_shifts">
                                                <option value="">Select shift</option>
                                                @foreach ($workingShifts as $shift)
                                                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-salary-and-deductions" role="tabpanel"
                            aria-labelledby="nav-salary-and-deductions-tab">
                            <div
                                class="card                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ">
                                <div class="card-header">
                                    <h5>{{ __('Salary & Deductions') }}</h5>
                                </div>
                                <div class="card-body employee-detail-create-body">

                                    {{-- Salary Details Start --}}
                                    <div class="row">
                                        @csrf
                                        <div class="form-group col-md-4">
                                            {!! Form::label('play_slab_id', __('Pay Slab'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            <select name="play_slab_id" id="play_slab_id" class="form-control select2">
                                                <option value="">Select Pay Slab</option>
                                                @foreach ($PayslipType as $playslab)
                                                    <option {{ old('play_slab_id') == $playslab->id ? 'selected' : '' }}
                                                        value="{{ $playslab->id }}">{{ $playslab->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            {!! Form::label('select_office_staff', __('This facility is for Field'), ['class' => 'form-label']) !!}
                                            <select name="select_office_staff" id="select_office_staff"
                                                class="form-control">
                                                <option value="">Select visit option</option>
                                                <option value="1">Office Staff</option>
                                                <option value="2">Field Staff</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4 select_grade_type d-none">
                                            {!! Form::label('select_grade_type', __('Grade Type'), ['class' => 'form-label']) !!}
                                            <select name="select_grade_type" id="select_grade_type" class="form-control">
                                                <option value="">Select Grade</option>
                                                @foreach ($gradeTypes as $gradetype)
                                                    <option value="{{ $gradetype->id }}">{{ $gradetype->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="form-group col-md-2">
                                            {!! Form::label('ctc_annual', __('CTC Annual'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::number('ctc_annual', old('ctc_annual'), [
                                                'class' => 'form-control salaryAmountValidation',
                                                'min' => '0',
                                                'placeholder' => 'Enter Employee Annual CTC',
                                                'id' => 'ctc_annual',
                                            ]) !!}
                                        </div>

                                        <div class="form-group col-md-2">
                                            {!! Form::label('ctc_monthely', __('CTC Monthly'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::number('ctc_monthely', old('ctc_monthely'), [
                                                'class' => 'form-control salaryAmountValidation',
                                                'min' => '0',
                                                'placeholder' => 'Employee Monthly CTC',
                                                'id' => 'ctc_monthely',
                                                'readonly',
                                            ]) !!}
                                        </div>

                                        <div class="form-group col-md-2">
                                            {!! Form::label('ctc_percentage', __('Basic(%)'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::text('ctc_percentage', old('ctc_percentage', number_format($company_ctc_percentage, 0)), [
                                                'class' => 'form-control salaryAmountValidation ctcPercentageValidator',
                                                'min' => '0',
                                                'placeholder' => 'Basic(%)',
                                                'id' => 'ctc_percentage',
                                                'min' => 1,
                                                'max' => 100,
                                            ]) !!}
                                        </div>

                                        <div class="form-group col-md-2">
                                            {!! Form::label('salary', __('Salary'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::number('salary', old('salary'), [
                                                'class' => 'form-control salaryAmountValidation',
                                                'min' => '0',
                                                'placeholder' => 'Enter Employee Salary',
                                                'id' => 'salary',
                                                'readonly',
                                            ]) !!}
                                        </div>
                                    </div>

                                    {{-- Salary Details End --}}


                                    {{-- Earnings Section Start --}}

                                    {{-- Statutory Bonus Start --}}
                                    <hr>
                                    <div class="row">

                                        <div class="form-group col-md-12">
                                            <div class="form-check form-switch">
                                                <label for="statutory_bonus_status">
                                                    <h5 class="">{{ __('Statutory Bonus') }}
                                                    </h5>
                                                </label>
                                                <input type="checkbox" class="form-check-input"
                                                    id="statutory_bonus_status" name="statutory_bonus_status" checked>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="statutory_bonus_div" style="display: ">
                                        <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                            {{ Form::label('statutory_bonus', __('Statutory Bonus'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('statutory_bonus', old('statutory_bonus'), ['id' => 'statutory_bonus', 'class' => 'form-control salaryAmountValidation', 'placeholder' => 'Enter Amount']) }}
                                        </div>
                                    </div>
                                    {{-- Statutory Bonus End --}}

                                    {{-- Allowances Start --}}
                                    @if ($allowancesList->count() >= 1)
                                        <hr>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <h5 class="">{{ __('Allowances') }}</h5>
                                            </div>
                                        </div>

                                        <div class="row" id="allowances_div">

                                            @foreach ($allowancesList as $allowancesRow)
                                                <div class="form-group col-md-12">
                                                    <div class="form-check form-switch">
                                                        <label for="allowances_status_{{ $allowancesRow->id }}">
                                                            <h5 class="">{{ $allowancesRow->name }}</h5>
                                                        </label>
                                                        <input type="checkbox" class="form-check-input hideShowDiv"
                                                            id="allowances_status_{{ $allowancesRow->id }}"
                                                            name="earning_id[]" value="{{ $allowancesRow->id }}"
                                                            divId="allowancesDiv_{{ $allowancesRow->id }}" checked>
                                                        <input type="hidden"
                                                            name="earning_type[{{ $allowancesRow->id }}]" value="0">
                                                        <input type="hidden"
                                                            name="earning_amount_type[{{ $allowancesRow->id }}]"
                                                            value="{{ $allowancesRow->type }}">
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-12 form-group"
                                                        id="allowancesDiv_{{ $allowancesRow->id }}">
                                                        {{ Form::label('allowances_amount_' . $allowancesRow->id, __('Amount'), ['class' => 'col-form-label']) }}
                                                        @if ($allowancesRow->type == 'percentage')
                                                            <small>(%)</small>
                                                        @endif
                                                        {{ Form::text('earning_amount[' . $allowancesRow->id . ']', $allowancesRow->amount, ['id' => 'allowances_amount_' . $allowancesRow->id, 'class' => 'form-control numberValidation', 'placeholder' => 'Enter Amount']) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <hr>
                                    @endif
                                    {{-- Allowances End --}}

                                    {{-- Commission Start --}}
                                    @if ($commissionList->count() >= 1)
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <h5 class="">{{ __('Commission') }}</h5>
                                            </div>
                                        </div>

                                        <div class="row" id="allowances_div">

                                            @foreach ($commissionList as $commissionRow)
                                                <div class="form-group col-md-12">
                                                    <div class="form-check form-switch">
                                                        <label for="commission_status_{{ $commissionRow->id }}">
                                                            <h5 class="">{{ $commissionRow->name }}</h5>
                                                        </label>

                                                        <input type="checkbox" class="form-check-input hideShowDiv"
                                                            id="commission_status_{{ $commissionRow->id }}"
                                                            name="earning_id[]" value="{{ $commissionRow->id }}"
                                                            divId="commissionDiv_{{ $commissionRow->id }}" checked>
                                                        <input type="hidden"
                                                            name="earning_type[{{ $commissionRow->id }}]" value="1">
                                                        <input type="hidden"
                                                            name="earning_amount_type[{{ $commissionRow->id }}]"
                                                            value="{{ $commissionRow->type }}">
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-12 form-group"
                                                        id="commissionDiv_{{ $commissionRow->id }}">
                                                        {{ Form::label('commission_amount_' . $commissionRow->id, __('Amount'), ['class' => 'col-form-label']) }}
                                                        @if ($commissionRow->type == 'percentage')
                                                            <small>(%)</small>
                                                        @endif
                                                        {{ Form::text('earning_amount[' . $commissionRow->id . ']', $commissionRow->amount, ['id' => 'commission_amount_' . $commissionRow->id, 'class' => 'form-control numberValidation', 'placeholder' => 'Enter Amount']) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <hr>
                                    @endif
                                    {{-- Commission End --}}


                                    {{-- Other Payment Start --}}

                                    @if ($otherPaymentList->count() >= 1)
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <h5 class="">{{ __('Other Payments') }}</h5>
                                            </div>
                                        </div>

                                        <div class="row" id="allowances_div">

                                            @foreach ($otherPaymentList as $otherPaymentRow)
                                                <div class="form-group col-md-12">
                                                    <div class="form-check form-switch">
                                                        <label for="other_payment_status_{{ $otherPaymentRow->id }}">
                                                            <h5 class="">{{ $otherPaymentRow->name }}</h5>
                                                        </label>
                                                        <input type="checkbox" class="form-check-input hideShowDiv"
                                                            id="other_payment_status_{{ $otherPaymentRow->id }}"
                                                            name="earning_id[]" value="{{ $otherPaymentRow->id }}"
                                                            divId="other_paymentDiv_{{ $otherPaymentRow->id }}" checked>
                                                        <input type="hidden"
                                                            name="earning_type[{{ $otherPaymentRow->id }}]"
                                                            value="2">

                                                        <input type="hidden"
                                                            name="earning_amount_type[{{ $otherPaymentRow->id }}]"
                                                            value="{{ $otherPaymentRow->type }}">
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-12 form-group"
                                                        id="other_paymentDiv_{{ $otherPaymentRow->id }}">
                                                        {{ Form::label('other_payment_amount_' . $otherPaymentRow->id, __('Amount'), ['class' => 'col-form-label']) }}
                                                        @if ($otherPaymentRow->type == 'percentage')
                                                            <small>(%)</small>
                                                        @endif
                                                        {{ Form::text('earning_amount[' . $otherPaymentRow->id . ']', $otherPaymentRow->amount, ['id' => 'other_payment_amount_' . $otherPaymentRow->id, 'class' => 'form-control numberValidation', 'placeholder' => 'Enter Amount']) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <hr>
                                    @endif
                                    {{-- Other Payment End --}}

                                    {{-- Earnings Section End --}}



                                    {{-- ------------------------------------------------------------------------------------------------------ --}}

                                    {{-- Deductions Section Start --}}


                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <h5 class="">{{ __('Deductions') }}</h5>
                                        </div>
                                    </div>

                                    @if ($deductionList->count() >= 1)
                                        <div class="row" id="allowances_div">

                                            @foreach ($deductionList as $deductionRow)
                                                <div class="form-group col-md-12">
                                                    <div class="form-check form-switch">
                                                        <label for="deduction_status_{{ $deductionRow->id }}">
                                                            <h5 class="">{{ $deductionRow->name }}</h5>
                                                        </label>
                                                        <input type="checkbox" class="form-check-input hideShowDiv"
                                                            id="deduction_status_{{ $deductionRow->id }}"
                                                            name="deduction_id[]" value="{{ $deductionRow->id }}"
                                                            divId="other_paymentDiv_{{ $deductionRow->id }}" checked>
                                                        <input type="hidden"
                                                            name="deduction_type[{{ $deductionRow->id }}]"
                                                            value="0">

                                                        <input type="hidden"
                                                            name="deduction_amount_type[{{ $deductionRow->id }}]"
                                                            value="{{ $deductionRow->type }}">
                                                    </div>
                                                    <div class="col-lg-3 col-md-3 col-sm-12 form-group"
                                                        id="other_paymentDiv_{{ $deductionRow->id }}">
                                                        {{ Form::label('deduction_amount_' . $deductionRow->id, __('Amount'), ['class' => 'col-form-label']) }}
                                                        @if ($deductionRow->type == 'percentage')
                                                            <small>(%)</small>
                                                        @endif
                                                        {{ Form::text('deduction_amount[' . $deductionRow->id . ']', $deductionRow->amount, ['id' => 'deduction_amount_' . $deductionRow->id, 'class' => 'form-control numberValidation', 'placeholder' => 'Enter Amount']) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif


                                    {{-- EPF | Employee Provident Fund Start --}}
                                    @if ($companyEpf != null)
                                        <div class="row">

                                            <div class="form-group col-md-12">
                                                <div class="form-check form-switch">
                                                    <label for="epf_status">
                                                        <h5 class="">{{ __('EPF | Employee Provident Fund') }}</h5>
                                                    </label>
                                                    <input type="checkbox" class="form-check-input" id="epf_status"
                                                        name="epf_status">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="epf_div" style="display: none;">
                                            <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                {{ Form::label('epf_number', __('PF Account No / Member Id'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('epf_number', old('epf_number'), ['id' => 'epf_number', 'class' => 'form-control', 'placeholder' => 'PF Account No / Member Id']) }}
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                {{ Form::label('uan_no', __('UAN Number'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('uan_no', old('uan_no'), ['id' => 'uan_no', 'class' => 'form-control', 'placeholder' => 'UAN Number']) }}
                                            </div>

                                            @if ($companyEpf->override_employee_pf == 1)
                                                <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                    {{ Form::label('employee_contribution_rate', __('Employee Contribution Rate (%)'), ['class' => 'col-form-label']) }}
                                                    {{ Form::text('employee_contribution_rate', old('employee_contribution_rate'), ['id' => 'employee_contribution_rate', 'class' => 'form-control salaryAmountValidation text-right', 'placeholder' => 'Employee Contribution Rate (%)', 'max' => '100']) }}
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                    {{ Form::label('employer_contribution_rate', __('Employer Contribution Rate (%)'), ['class' => 'col-form-label']) }}
                                                    {{ Form::text('employer_contribution_rate', old('employer_contribution_rate'), ['id' => 'employer_contribution_rate', 'class' => 'form-control salaryAmountValidation text-right', 'placeholder' => 'Employer Contribution Rate (%)', 'max' => '100']) }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    {{-- EPF | Employee Provident Fund End --}}

                                    {{-- ESI | Employees State Insurance Start --}}
                                    @if ($companyEsi != null)
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
                                                        name="esi_status">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="esi_div" style="display: none">
                                            <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                {{ Form::label('esi_number', __('Esi Account No'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('esi_number', old('esi_number'), ['id' => 'esi_number', 'class' => 'form-control', 'placeholder' => 'Esi Account No']) }}
                                            </div>
                                        </div>
                                    @endif
                                    {{-- ESI | Employees State Insurance End --}}

                                    {{-- EPT | Employees Professional Tax Start --}}
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
                                                        name="ept_status">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="ept_div" style="display: none">
                                            <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                                {{ Form::label('ept_number', __('Ept Account No'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('ept_number', old('ept_number'), ['id' => 'ept_number', 'class' => 'form-control', 'placeholder' => 'Ept Account No']) }}
                                            </div>
                                        </div>
                                    @endif
                                    {{-- EPT | Employees Professional Tax End --}}

                                    {{-- Tax Deducted at Source Start --}}
                                    <hr>
                                    <div class="row">

                                        <div class="form-group col-md-12">
                                            <div class="form-check form-switch">
                                                <label for="tax_deducted_at_source_status">
                                                    <h5 class="">{{ __('TDS | Tax Deducted at Source') }}
                                                    </h5>
                                                </label>
                                                <input type="checkbox" class="form-check-input"
                                                    id="tax_deducted_at_source_status"
                                                    name="tax_deducted_at_source_status">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" id="tax_deducted_at_source_div" style="display: ">
                                        <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                            {{ Form::label('tax_deducted_at_source', __('Tax Deducted at Source'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('tax_deducted_at_source', old('tax_deducted_at_source'), ['id' => 'tax_deducted_at_source', 'class' => 'form-control salaryAmountValidation', 'placeholder' => 'Enter Amount']) }}
                                        </div>
                                    </div>
                                    {{-- Tax Deducted at Source End --}}

                                    {{-- Deductions Section End --}}

                                </div>
                            </div>
                        </div>


                        <div class="tab-pane fade" id="nav-Experience" role="tabpanel"
                            aria-labelledby="nav-Experience-tab">
                            <div class="col-md-12 ">
                                <div
                                    class="card                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-6">
                                                <h5>{{ __('Work Experience') }}</h5>
                                            </div>
                                            <div class="col text-end">
                                                <p class="btn btn-primary" id='add_sub_form'>{{ '+' }}</p>
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
                                                <input type="date" name="previous_from_date[]" class='form-control'
                                                    max="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                {!! Form::label('previous_to_date', __('To Date'), ['class' => 'form-label']) !!}
                                                <input type="date" name="previous_to_date[]" class='form-control'
                                                    max="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                {{ Form::label('previous_company_designation_id', __('Previous Company designation'), ['class' => 'form-label']) }}
                                                <input type="text" class='form-control'
                                                    name='previous_company_designation_id[]'
                                                    id='previous_company_designation_id'
                                                    placeholder='Enter previous company designation'>
                                            </div>

                                            <div class="form-group col-sm-6">
                                                {!! Form::label('experiance_Job_description', __('Experience Job Description'), ['class' => 'form-label']) !!}<span class="text-danger pl-1"></span>
                                                {!! Form::textarea('experiance_Job_description[]', null, [
                                                    'class' => 'form-control',
                                                    'rows' => 2,
                                                    'placeholder' => 'Enter Experience job description',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div id='append_sub_form'></div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-Education" role="tabpanel"
                            aria-labelledby="nav-Education-tab">
                            <div class="col-md-12 ">
                                <div
                                    class="card                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-6">
                                                <h5>{{ __('Education') }}</h5>
                                            </div>
                                            <div class="col text-end">
                                                <p class="btn btn-primary" id='append_sub_form_eduction_table'>
                                                    {{ '+' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body employee-detail-create-body">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                {!! Form::label('institute_name', __('Institute Name'), ['class' => 'form-label']) !!}
                                                {!! Form::text('institute_name[]', null, [
                                                    'class' => 'form-control',
                                                    '' => '',
                                                    'placeholder' => 'Enter institute name',
                                                ]) !!}
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
                                                {!! Form::label('date_of_completion', __('Date of completion'), ['class' => 'form-label']) !!}
                                                <input type="date" name="date_of_completion[]" class='form-control'
                                                    max="<?php echo date('Y-m-d'); ?>">
                                            </div>



                                            <div class="form-group col-md-6">
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
                                <div id='append_sub_form_eduction_table_add_form'></div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-Dependent" role="tabpanel"
                            aria-labelledby="nav-Dependent-tab">
                            <div class="col-md-12">
                                <div
                                    class="card                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-6">
                                                <h5>{{ __('Dependent') }}</h5>
                                            </div>
                                            <div class="col text-end">
                                                <p class="btn btn-primary" id='Dependent_sub_form_eduction_table'>
                                                    {{ '+' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body employee-detail-create-body">
                                        <div class="row">
                                            <div class="form-group col-sm-6">
                                                {!! Form::label('dependent_name', __('Name'), ['class' => 'form-label']) !!}
                                                {!! Form::text('dependent_name[]', null, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Enter dependent name',
                                                ]) !!}
                                            </div>
                                            <div class="form-group col-sm-6">
                                                {{ Form::label('dependent_relation', __('Relation Types'), ['class' => 'form-label']) }}
                                                <div class="form-icon-user">
                                                    <select name="dependent_relation[]" id="dependent_relation"
                                                        class="form-control select2">
                                                        <option value="">-Select Relation</option>
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

                                            <div class="form-group col-sm-6">
                                                {!! Form::label('dependent_dob', __('Date of birth'), ['class' => 'form-label']) !!}
                                                <input type="date" name="dependent_dob[]" class='form-control'
                                                    max="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <div class="form-group col-md-6">
                                                {!! Form::label('dependent_dob', __('Emergency phone no.'), ['class' => 'form-label']) !!}
                                                {{ Form::number('emergency_phone_no[]', null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'Emergency phone no']) }}
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div id='Dependent_sub_form_eduction_table_add_form'></div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-document" role="tabpanel"
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

                                                            <div class="choose-files ">
                                                                <label for="document[{{ $document->id }}]">
                                                                    <div class=" bg-primary document "> <i
                                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                    </div>
                                                                    <input type="file"
                                                                        class="form-control file  d-none @error('document') is-invalid @enderror"
                                                                        @if ($document->is_ == 1)  @endif
                                                                        name="document[{{ $document->id }}]"
                                                                        id="document[{{ $document->id }}]"
                                                                        data-filename="{{ $document->id . '_filename' }}"
                                                                        onchange="document.getElementById('{{ 'blah' . $key }}').src = window.URL.createObjectURL(this.files[0])">
                                                                </label>
                                                                @php
                                                                    $logo = \App\Models\Utility::get_file(
                                                                        'uploads/document/',
                                                                    );

                                                                @endphp

                                                                <img id="{{ 'blah' . $key }}" src=""
                                                                    width="50%" />

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>

                        <div class="tab-pane fade" id="nav-Account" role="tabpanel" aria-labelledby="nav-Account-tab">
                            <div class="col-md-12 ">
                                <div
                                    class="card                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ">
                                    <div class="card-header">
                                        <h5>{{ __('Bank Account Detail') }}</h5>
                                    </div>
                                    <div class="card-body employee-detail-create-body">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                {!! Form::label('account_holder_name', __('Account Holder Name'), ['class' => 'form-label']) !!}
                                                <span class="text-danger">*</span>
                                                {!! Form::text('account_holder_name', null, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Enter account holder name',
                                                ]) !!}
                                            </div>
                                            <div class="form-group col-md-6">
                                                {!! Form::label('account_number', __('Account Number'), ['class' => 'form-label']) !!}
                                                <span class="text-danger">*</span>
                                                {!! Form::number('account_number', null, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Enter account number',
                                                ]) !!}
                                            </div>
                                            <div class="form-group col-md-6">
                                                {!! Form::label('bank_name', __('Bank Name'), ['class' => 'form-label']) !!}
                                                {!! Form::text('bank_name', null, ['class' => 'form-control', 'placeholder' => 'Enter bank name']) !!}
                                            </div>
                                            <div class="form-group col-md-6">
                                                {!! Form::label('bank_identifier_code', __('Bank Identifier Code'), ['class' => 'form-label']) !!}
                                                <span class="text-danger">*</span>
                                                {!! Form::text('bank_identifier_code', null, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Enter bank identifier code',
                                                ]) !!}
                                            </div>
                                            <div class="form-group col-md-6">
                                                {!! Form::label('branch_location', __('Branch Location'), ['class' => 'form-label']) !!}
                                                <span class="text-danger">*</span>
                                                {!! Form::text('branch_location', null, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Enter branch location',
                                                ]) !!}
                                            </div>
                                            <div class="form-group col-md-6">
                                                {!! Form::label('tax_payer_id', __('Tax Payer Id'), ['class' => 'form-label']) !!}
                                                {!! Form::text('tax_payer_id', null, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Enter tax payer id',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-weekoff" role="tabpanel" aria-labelledby="nav-weekoff-tab">

                            <div class="col-md-12">

                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-2"><span class="text-dark"><i
                                                        class="badge bg-dark p-2  rounded">{{ __('WO') }}</i>&nbsp;
                                                    {{ __('Week Off') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12 ">
                                <hr>
                                <div class="row">

                                    <div class="form-group col-md-12">
                                        <div class="form-check form-switch">
                                            <label for="is_custom_weekoffs">
                                                <h5 class="">{{ __('Enable Custom Weekoff') }}
                                                </h5>
                                            </label>
                                            <input type="checkbox" class="form-check-input" id="is_custom_weekoffs"
                                                name="is_custom_weekoffs">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- calender --}}
                                    <input type="hidden" name="weekoffs_data" id="weekoffs_data" class="weekoffs_data">
                                    <div id='calendar'></div>

                                </div>
                            </div>
                        </div>

                        <div class="float-end">
                            <button type="submit" class="btn btn-primary">{{ 'Create' }}</button>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

@php
    $branchesGeofencingStatus = $branches->pluck('geofencing_status', 'id')->toArray();
@endphp

@push('script-page')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js"></script>



    <script>
        var isCalenderInitialize = false;
        $('#is_custom_weekoffs').change(function() {
            if ($(this).prop('checked')) {

                $("#calendar").show();

                showCalender()
            } else {
                $("#calendar").hide()
            }
        });
        var selectedDates = {};

        $('#nav-tab >button').on('shown.bs.tab', function(e) {
            console.log("clicked id", e.target.id);

            if (e.target.id == "nav-weekoff-tab" && $("#is_custom_weekoffs").prop('checked')) {
                $("#calendar").show();
                showCalender()
            } else {
                $("#calendar").hide();
                //  $("#weekoffs_data").val("");
            }
        });

        function showCalender() {

            if (isCalenderInitialize) {
                return;
            }

            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                buttonText: {
                    today: 'Today'
                },
                dateClick: function(info) {
                    var dateStr = info.dateStr;

                    // Check if the date is already selected
                    if (selectedDates[dateStr]) {
                        // Date is already selected, remove it
                        //     selectedDates[dateStr].remove();
                        //     delete selectedDates[dateStr];
                    } else {
                        // Select new date and add event
                        var event = calendar.addEvent({
                            title: 'Week Off',
                            start: dateStr,
                            allDay: true,
                            className: 'selected-event',
                            color: '#1C232F'
                        });

                        selectedDates[dateStr] = event;
                    }

                    // saveToLocalStorage(Object.keys(selectedDates));

                    $("#weekoffs_data").val(JSON.stringify(Object.keys(selectedDates)));
                    // sendSelectedDates();
                },
                eventContent: function(info) {
                    var deleteButton = document.createElement('button');
                    deleteButton.className = 'delete-button';
                    deleteButton.innerHTML = '<span class="material-icons">delete</span>';
                    deleteButton.onclick = function() {
                        info.event.remove();
                        delete selectedDates[info.event.startStr];
                        //     saveToLocalStorage(Object.keys(selectedDates));
                        //     sendSelectedDates();
                        $("#weekoffs_data").val(JSON.stringify(Object.keys(selectedDates)));
                    };

                    var arrayOfDomNodes = [deleteButton]
                    return {
                        domNodes: arrayOfDomNodes
                    }
                }
            });
            calendar.render();


            //    add data
            //   var storedDates = "";
            //   if (localStorage.getItem('selectedDates')) {
            //       storedDates = JSON.parse(localStorage.getItem('selectedDates'));
            //   }

            //   for (var dateStr of storedDates) {
            //       var event = calendar.addEvent({
            //           title: 'Week Off',
            //           start: dateStr,
            //           allDay: true,
            //           className: 'selected-event',
            //           color: '#1C232F'
            //       });

            //       selectedDates[dateStr] = event;
            //   }
            //    add data

            isCalenderInitialize = true;
            console.log("Calendar initialize.");
        }
    </script>


    <script>
        var branchesGeofencingStatus = <?php echo json_encode($branchesGeofencingStatus); ?>;
        //    console.log("branchesGeofencingStatus", branchesGeofencingStatus);

        //    var value = branchesGeofencingStatus[35];
        //    console.log("Value for key " + 35 + ": " + value);

        $('input[type="file"]').change(function(e) {
            var file = e.target.files[0].name;
            var file_name = $(this).attr('data-filename');
            $('.' + file_name).append(file);
        });
    </script>
    <script>
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
            });

            $('.salaryAmountValidation').on('input keypress keyup paste dragover', function(e) {
                // Check if the entered value is a valid number
                var inputValue = $(this).val() + String.fromCharCode(e.which);
                if (!$.isNumeric(inputValue)) {
                    // Prevent the input if it's not a number
                    e.preventDefault();
                }
                calculateSalary();
            });

            $('.ctcPercentageValidator').on('input keypress keyup paste dragover', function(e) {
                // Check if the entered value is a valid number
                var inputValue = parseFloat($(this).val());
                if (inputValue > 100) {
                    $(this).val(100);
                }
                calculateSalary();
            });

            $(document).on('change', 'select[name=branch_id]', function() {
                var branch_id = $(this).val();

                try {
                    if (branchesGeofencingStatus[branch_id] == 1 && branch_id != "") {
                        $("#geofencing_status").prop("disabled", false);
                    } else {
                        $("#geofencing_status").prop("disabled", true);
                    }

                } catch (error) {
                    $("#geofencing_status").prop("disabled", true);
                }



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
                            "<option value=''>{{ __('Select Department ') }}</option>");
                        $.each(data, function(key, value) {
                            console.log('<option value="' + key + '">' + value +
                                '</option>');
                            $('#department_id').append('<option value="' + key + '">' +
                                value + '</option>');
                        });
                    }
                });
            });

            $('.icon_Confirmpassword').click(function() {
                if ($('.password').attr('type') == 'text') {
                    $('.password').attr('type', 'password');
                } else {
                    $('.password').attr('type', 'text');
                }
            });

            $('.icon_password').click(function() {
                if ($('.enterpassword').attr('type') == 'text') {
                    $('.enterpassword').attr('type', 'password');
                } else {
                    $('.enterpassword').attr('type', 'text');
                }
            });

            // $("#employee_create").on("submit", 'form', function(event) {
            $("#employee_create").submit(function(e) {
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
                        if (response.status_error == 1) {
                            toastr.error(response.message)
                            // $.each(response.message, function(key, value) {
                            // });
                        }
                    },
                })
                return false;
            });

            var d_id = $('.department_id').val();
            getDesignation(d_id);


        });

        $(document).on('change', 'select[name=department_id]', function() {
            var department_id = $(this).val();
            getDesignation(department_id);
        });

        //check select_office_staff
        $(document).on('change', '#select_office_staff', function() {
            var office_staff = $(this).val();

            if (office_staff == 2) {
                $('.select_grade_type').removeClass('d-none');
            } else {
                $('.select_grade_type').addClass('d-none');
            }
        });


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
                    var emp_selct = ` <select class="form-control  designation_id" name="designation_id" id="choices-multiple"
                                       placeholder="Select Designation" >
                                       </select>`;
                    $('.designation_div').html(emp_selct);

                    $('.designation_id').append('<option value="">Select any Designation</option>');
                    $.each(data, function(key, value) {
                        $('.designation_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    new Choices('#choices-multiple', {
                        removeItemButton: true,
                    });
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {

            var designations = <?php echo json_encode($designations); ?>;

            var now = new Date();
            var month = (now.getMonth() + 1);
            var day = now.getDate();
            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;
            var today = now.getFullYear() + '-' + month + '-' + day;
            $('.current_date').val(today);
            var index = 0;
            $("#add_sub_form").click(function() {
                index++;


                $('#append_sub_form').append('<div class="col-md-12" id="delete_sub_form">' +
                    '<div class="card                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               ">' +
                    '<div class="card-header">' +
                    '<h5>Work Experience</h5>' +
                    '<p class="btn btn-danger float-end" id="programings_btn_remove">+ remove</p>' +
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


                    '<div class="form-group col-md-6">' +

                    '<lable class="form-label">Previous Company designation</lable>' +
                    '<input type="text" name="previous_company_designation_id[' + index +
                    ']" id="previous_company_designation_id[' + index +
                    ']"class="form-control" placeholder="Enter previous company designation"></input>' +
                    '</div>' +

                    '<div class="form-group col-md-6">' +
                    '<lable class="form-label">Experience Job Description</lable>' +
                    '<textarea type="text" name="experiance_Job_description[' + index +
                    ']" class="form-control" placeholder="Enter Experience job description"></textarea>' +
                    '</div>' +
                    '</div></div></div></div>');



            });
            $(document).on('click', '#programings_btn_remove', function() {
                $(this).closest('div#delete_sub_form').remove();
            });


            //Education-TABLE add-more
            var eduction_table = 0;
            $("#append_sub_form_eduction_table").click(function() {

                eduction_table++;

                $('#append_sub_form_eduction_table_add_form').append(
                    '<div class="col-md-12" id="delete_sub_form_eduction_table">' +
                    '<div class="card                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               ">' +
                    '<div class="card-header">' +
                    '<h5>Education</h5>' +
                    '<p class="btn btn-danger float-end" id="programings_btn_remove_eduction_table">+ remove</p>' +
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
                    '<lable class="form-label">Date of completion</lable>' +
                    '<input type="date" max=' + today + '  name="date_of_completion[' + eduction_table +
                    ']" class="form-control" placeholder="Enter previous job title"></input>' +
                    '</div>' +

                    '<div class="form-group col-md-6">' +
                    '<lable class="form-label">Notes</lable>' +
                    '<textarea type="date" name="notes[' + eduction_table +
                    ']" class="form-control" placeholder="Enter notes"></textarea>' +
                    '</div>' +
                    '</div></div></div></div>');
            });
            $(document).on('click', '#programings_btn_remove_eduction_table', function() {
                $(this).closest('div#delete_sub_form_eduction_table').remove();
            });


            //Dependent
            //Dependent-TABLE add-more
            var Dependent = 0;
            $("#Dependent_sub_form_eduction_table").click(function() {

                Dependent++;

                $('#Dependent_sub_form_eduction_table_add_form').append(
                    '<div class="col-md-12" id="delete_programings_btn_remove_Dependent_table">' +
                    '<div class="card                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               ">' +
                    '<div class="card-header">' +
                    '<h5>Dependent</h5>' +
                    '<p class="btn btn-danger float-end" id="programings_btn_remove_Dependent_table">+ remove</p>' +
                    '</div>' + '<div class="card-body employee-detail-create-body">' +
                    '<div class="row">' +
                    '<div class="form-group col-md-6">' +
                    '<lable class="form-label">Name</lable>' +
                    '<input type="text" name="dependent_name[' + Dependent +
                    ']" class="form-control" placeholder="Enter dependent name" ></input>' +
                    '</div>' +
                    '<div class="form-group col-md-6">' +
                    '<lable class="form-label">Education</lable>' +
                    '<select  name="dependent_relation[' + Dependent + ']" id="dependent_relation' +
                    Dependent + '" class="form-control select2">' +
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
                    '<div class="form-group col-md-6">' +
                    '<lable class="form-label">Date of birth</lable>' +
                    '<input type="date" max=' + today + '  name="dependent_dob[' + Dependent +
                    ']" class="form-control" placeholder="Enter specialization"></input>' +
                    '</div>' +

                    '<div class="form-group col-md-6">' +
                    '<lable class="form-label">Emergency phone no.</lable>' +
                    '<input type="number" name="emergency_phone_no[' + Dependent +
                    ']" class="form-control" placeholder="Emergency phone no."></input>' +
                    '</div>' +

                    '</div></div></div></div>');
            });
            $(document).on('click', '#programings_btn_remove_Dependent_table', function() {
                $(this).closest('div#delete_programings_btn_remove_Dependent_table').remove();
            });
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

        $('.hideShowDiv').change(function() {

            var divId = $(this).attr("divId");
            if ($(this).prop('checked')) {

                $("#" + divId).show();
            } else {
                $("#" + divId).hide();
            }
        });


        $('#statutory_bonus_status').change(function() {
            if ($(this).prop('checked')) {
                $("#statutory_bonus_div").show();
            } else {
                $("#statutory_bonus_div").hide();
            }
        });

        $('#tax_deducted_at_source_status').change(function() {
            if ($(this).prop('checked')) {
                $("#tax_deducted_at_source_div").show();
            } else {
                $("#tax_deducted_at_source_div").hide();
            }
        });

        $('#marital_status').change(function() {
            console.log("Here", $(this).val());
            if ($(this).val() == "Married" || $(this).val() == "Widowed") {
                $(".marriage_anniversary_date_div").show();
            } else {
                $(".marriage_anniversary_date_div").hide();
            }
        });
    </script>
@endpush
