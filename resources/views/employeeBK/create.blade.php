
@extends('layouts.admin')
@section('page-title')
    {{ __('Create Employee') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ url('employee') }}">{{ __('Employee') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create Employee') }}</li>
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
                            <button class="nav-link active" id="nav-Salary-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Salary" type="button" role="tab" aria-controls="nav-Salary"
                                aria-selected="true">Personal Detail</button>
                            <button class="nav-link" id="nav-Allowance-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Allowance" type="button" role="tab" aria-controls="nav-Allowance"
                                aria-selected="false">Company</button>
                            <button class="nav-link" id="nav-Experience-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Experience" type="button" role="tab"
                                aria-controls="nav-Experience" aria-selected="false">Experience</button>
                            <button class="nav-link" id="nav-Education-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Education" type="button" role="tab" aria-controls="nav-Education"
                                aria-selected="false"> Education</button>
                            <button class="nav-link" id="nav-Dependent-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-Dependent" type="button" role="tab" aria-controls="nav-Dependent"
                                aria-selected="false">Dependent</button>
                            <button class="nav-link" id="nav-Account-tab" data-bs-toggle="tab" data-bs-target="#nav-Account"
                                type="button" role="tab" aria-controls="nav-Account" aria-selected="false">Bank
                                Account</button>
                        </div>
                    </nav>
                    <div class="tab-content tab_ctn" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="nav-Salary" role="tabpanel"
                            aria-labelledby="nav-Salary-tab">
                            <div
                                class="card                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ">
                                <div class="card-header">
                                    <h5>{{ __('Personal Detail') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {!! Form::label('name', __('Name'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::text('name', old('name'), [
                                                'class' => 'form-control',
                                            
                                                'placeholder' => 'Enter employee name',
                                            ]) !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('phone', __('Phone'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::text('phone', old('phone'), [
                                                'class' => 'form-control',
                                                'placeholder' => 'Enter employee phone',
                                                'maxlength' => 10,
                                            ]) !!}
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('dob', __('Date of Birth '), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                                <input type="date" name="dob" class='form-control current_date'
                                                    max="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                                <div class="d-flex radio-check">
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="g_male" value="Male" name="gender"
                                                            class="form-check-input" checked>
                                                        <label class="form-check-label "
                                                            for="g_male">{{ __('Male') }}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio ms-1 custom-control-inline">
                                                        <input type="radio" id="g_female" value="Female" name="gender"
                                                            class="form-check-input">
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
                                        <div class="form-group col-md-6">
                                            {!! Form::label('email', __('Email'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::email('email', old('email'), [
                                                'class' => 'form-control',
                                            
                                                'placeholder' => 'Enter employee email',
                                            ]) !!}
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
                                                {!! Form::password('password_confirmation', ['class' => 'form-control password', 'placeholder' => 'Enter confirm password']) !!}
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
                                        <div class="form-group ">
                                            {!! Form::label('employee_id', __('Employee ID'), ['class' => 'form-label']) !!}
                                            {!! Form::text('employee_id', $employeesId, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('branch_id', __('Select Branch'), ['class' => 'form-label']) }}<span
                                                class="text-danger pl-1">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('branch_id', $branches, null, ['class' => 'form-control select2', 'placeholder' => 'Select Branch']) }}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('department_id', __('Select Department'), ['class' => 'form-label']) }}<span
                                                class="text-danger pl-1">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::select('department_id', $departments, null, ['class' => 'form-control select2', 'id' => 'department_id', 'placeholder' => 'Select Department']) }}
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
                                            {!! Form::label('Experience', __('Pay Slab'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            <select name="play_slab_id" id="play_slab_id" class="form-control select2">
                                                <option value="">Select Pay Slab</option>
                                                @foreach ($PayslipType as $playslab)
                                                    <option {{ old('play_slab_id') == $playslab->id ? 'selected' : '' }}
                                                        value="{{ $playslab->id }}">{{ $playslab->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            {!! Form::label('Experience', __('This facility is for Field'), ['class' => 'form-label']) !!}
                                            <select name="select_office_staff" id="select_office_staff" class="form-control">
                                                <option value="">Select visit option</option>
                                                <option value="1">Office Staff</option>
                                                <option value="2">Field Staff</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6 select_grade_type d-none">
                                            {!! Form::label('Experience', __('Grade Type'), ['class' => 'form-label']) !!}
                                            <select name="select_grade_type" id="select_grade_type" class="form-control">
                                                <option value="">Select Grade</option>
                                                @foreach ($gradeTypes as $gradetype)
                                                    <option value="{{ $gradetype->id }}">{{ $gradetype->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            {!! Form::label('salary', __('Salary'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::number('salary', old('salary'), ['class' => 'form-control', 'placeholder' => 'Enter Employee Salary']) !!}
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
                                            {!! Form::label('company_doj', __('Company date of joining'), ['class' => '  form-label']) !!}<span class="text-danger pl-1">*</span>
                                            <input type="date" name="company_doj" value='{{ old('company_doj') }}'
                                                class='form-control' max="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('date_of_exit', __('Date on Exit'), ['class' => 'form-label']) !!}
                                            <input type="date" name="date_of_exit" value='{{ old('date_of_exit') }}'
                                                class='form-control' max="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
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
                                                <span class="text-danger pl-1">*</span>
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
                                                    {{ '+' }}</p>
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
                                                    {{ '+' }}</p>
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
                                                <input type="date" name="dependent_dob[]" class='form-control'max="<?php echo date('Y-m-d'); ?>">
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
                                                {!! Form::text('account_holder_name', null, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Enter account holder name',
                                                ]) !!}
                                            </div>
                                            <div class="form-group col-md-6">
                                                {!! Form::label('account_number', __('Account Number'), ['class' => 'form-label']) !!}
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
                                                {!! Form::text('bank_identifier_code', null, [
                                                    'class' => 'form-control',
                                                    'placeholder' => 'Enter bank identifier code',
                                                ]) !!}
                                            </div>
                                            <div class="form-group col-md-6">
                                                {!! Form::label('branch_location', __('Branch Location'), ['class' => 'form-label']) !!}
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
                            <div class="float-end">
                                <button type="submit" class="btn  btn-primary">{{ 'Create' }}</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

@push('script-page')
    <script>
        $('input[type="file"]').change(function(e) {
            var file = e.target.files[0].name;
            var file_name = $(this).attr('data-filename');
            $('.' + file_name).append(file);
        });
    </script>
    <script>
        $(document).ready(function() {
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
            
            if(office_staff == 2){
                $('.select_grade_type').removeClass('d-none');
            }else{
                $('.select_grade_type').addClass('d-none');
            }
        });


        function getDesignation(did) {

            $.ajax({
                url: '{{ route('employee.json') }}',
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
                    '<lable class="form-label">Date of birth</lable>' +
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
@endpush
