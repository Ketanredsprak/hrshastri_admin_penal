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
    <div class="">
        <div class="">

            {{ Form::model($employee, ['route' => ['employee.update', $employee->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
            <div class="row">
                <div class="col-md-6 ">
                    <div class="card em-card">
                        <div class="card-header">
                            <h5>{{ __('Personal Detail') }}</h5>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    {!! Form::label('name', __('Name'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('phone', __('Phone'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {!! Form::label('dob', __('Date of Birth'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                        {!! Form::text('dob', null, ['class' => 'form-control d_week', 'id' => 'data_picker1']) !!}
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group ">
                                        {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                        <div class="d-flex radio-check">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="g_male" value="Male" name="gender"
                                                    class="form-check-input"
                                                    {{ $employee->gender == 'Male' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="g_male">{{ __('Male') }}</label>
                                            </div>
                                            <div class="custom-control custom-radio ms-1 custom-control-inline">
                                                <input type="radio" id="g_female" value="Female" name="gender"
                                                    class="form-check-input"
                                                    {{ $employee->gender == 'Female' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="g_female">{{ __('Female') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                {{ Form::label('marital_status', __('Select Marital*'), ['class' => 'form-label']) }}
                                <div class="form-icon-user">
                                    <select name="marital_status" id="marital_status" class="form-control select2">
                                        <option value="">-Select Marital-</option>
                                        <option class="form-check-input"
                                        {{ $employee->marital_status == 'Single' ? 'selected' : '' }} value="Single">Single</option>
                                        <option class="form-check-input"
                                        {{ $employee->marital_status == 'Married' ? 'selected' : '' }} value="Married">Married</option>
                                        <option class="form-check-input"
                                        {{ $employee->marital_status == 'Widowed' ? 'selected' : '' }} value="Widowed">Widowed</option>
                                        <option class="form-check-input"
                                        {{ $employee->marital_status == 'Separated' ? 'selected' : '' }}value="Separated">Separated</option>
                                        <option class="form-check-input"
                                        {{ $employee->marital_status == 'Divorced' ? 'selected' : '' }}value="Divorced">Divorced</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('address', __('Address'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                {!! Form::textarea('address', null, ['class' => 'form-control', 'rows' => 2]) !!}
                            </div>
                            @if (\Auth::user()->type == 'employee')
                                {!! Form::submit('Update', ['class' => 'btn-create btn-xs badge-blue radius-10px float-right']) !!}
                            @endif
                        </div>
                    </div>
                </div>
                @if (\Auth::user()->type != 'employee')
                    <div class="col-md-6 ">
                        <div class="card em-card">
                            <div class="card-header">
                                <h5>{{ __('Company Detail') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @csrf
                                    <div class="form-group ">
                                        {!! Form::label('employee_id', __('Employee ID'), ['class' => 'form-label']) !!}
                                        {!! Form::text('employee_id', $employeesId, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{ Form::label('branch_id', __('Select Branch*'), ['class' => 'form-label']) }}
                                        {{ Form::select('branch_id', $branches, null, ['class' => 'form-control select2', 'required' => 'required', 'placeholder' => 'Select Branch']) }}
                                    </div>
                                    <div class="form-group col-md-6">
                                        {{ Form::label('department_id', __('Select Department*'), ['class' => 'form-label']) }}
                                        {{ Form::select('department_id', $departments, null, ['class' => 'form-control select2', 'id' => 'department_id', 'required' => 'required']) }}
                                    </div>
                                    <div class="form-group col-md-12">
                                        {{ Form::label('designation_id', __('Select Designation'), ['class' => 'form-label']) }}


                                        <div class="form-icon-user">
                                            <div class="designation_div">
                                                <select class="form-control designation_id select2" name="designation_id"
                                                    id="choices-multiple" placeholder="Select Designation">
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        {{ Form::label('employe_status', __('Employee Status *'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            <select name="employe_status" id="employe_status" class="form-control select2">

                                                <option @if($employee->employe_status == "Active") selected @else  @endif value="Active">Active</option>
                                                <option @if($employee->employe_status == "Terminated") selected @else  @endif value="Terminated">Terminated</option>
                                                <option @if($employee->employe_status == "Deceased") selected @else  @endif value="Deceased">Deceased</option>
                                                <option @if($employee->employe_status == "Resigned") selected @else  @endif value="Resigned">Resigned</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        {{ Form::label('employe_types', __('Employee Types *'), ['class' => 'form-label']) }}

                                        <div class="form-icon-user">
                                            <select name="employe_types" id="employe_types" class="form-control select2">
                                                <option @if($employee->employe_types == "Permanent") selected @else  @endif value="Permanent">Permanent</option>
                                                <option @if($employee->employe_types == "Temporary") selected @else  @endif value="Temporary">Temporary</option>
                                                <option @if($employee->employe_types == "On Contract") selected @else @endif value="On Contract">On Contract</option>
                                                <option @if($employee->employe_types == "Trainee") selected @else @endif value="Trainee">Trainee</option>


                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        {!! Form::label('Experience', __('Experience'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                        {!! Form::text('employee_experience', $employee->employee_experience, ['class' => 'form-control', 'placeholder' => 'Enter Experience']) !!}
                                    </div>

                                    <div class="form-group col-md-6">
                                        {!! Form::label('date_of_exit', __('Date on Exit'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                        {!! Form::text('date_of_exit', $employee->date_of_exit, ['class' => 'form-control', 'placeholder' => 'Enter Date on Exit']) !!}
                                    </div>

                                    <div class="form-group col-md-12">
                                        {!! Form::label('Job_description', __('Job Description'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>

                                        {!! Form::textarea('Job_description', $employee->Job_description, [
                                        'class' => 'form-control',
                                        'rows' => 2,
                                        'placeholder' => 'Enter job description',
                                    ]) !!}
                                    </div>


                                    <div class="form-group col-md-12">
                                        {!! Form::label('company_doj', 'Company Date Of Joining', ['class' => 'form-label']) !!}
                                        {!! Form::date('company_doj', $employee->company_doj, ['class' => 'form-control ', 'id' => 'data_picker2', 'required' => 'required']) !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-6 ">
                        <div class="employee-detail-wrap ">
                            <div class="card em-card">
                                <div class="card-header">
                                    <h5>{{ __('Company Detail') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info">
                                                <strong>{{ __('Branch') }}</strong>
                                                <span>{{ !empty($employee->branch) ? $employee->branch->name : '' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info font-style">
                                                <strong>{{ __('Department') }}</strong>
                                                <span>{{ !empty($employee->department) ? $employee->department->name : '' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="info font-style">
                                                <strong>{{ __('Designation') }}</strong>
                                                <span>{{ !empty($employee->designation) ? $employee->designation->name : '' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info">
                                                <strong>{{ __('Date Of Joining') }}</strong>
                                                <span>{{ \Auth::user()->dateFormat($employee->company_doj) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @if (\Auth::user()->type != 'employee')
                <div class="row">
                    <div class="col-md-12 ">
                        <div class="card em-card">
                            <div class="card-header">
                                <h5>{{ __('Document') }}</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $employeedoc = $employee->documents()->pluck('document_value', __('document_id'));
                                @endphp

                                @foreach ($documents as $key => $document)
                                    <div class="row">
                                        <div class="form-group col-12 d-flex">
                                            <div class="float-left col-4">
                                                <label for="document" class=" form-label">{{ $document->name }}
                                                    @if ($document->is_required == 1)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                            </div>
                                            <div class="float-right col-8">
                                                <input type="hidden" name="emp_doc_id[{{ $document->id }}]" id=""
                                                    value="{{ $document->id }}">

                                                @php
                                                $employeedoc = !empty($employee->documents)?$employee->documents()->pluck('document_value',__('document_id')):[];
                                                @endphp
                                                <div class="choose-files ">
                                                    <label for="document[{{ $document->id }}]">
                                                        <div class=" bg-primary document "> <i
                                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file"
                                                            class="form-control file  d-none @error('document') is-invalid @enderror"
                                                            @if ($document->is_required == 1)  @endif
                                                            name="document[{{ $document->id }}]" id="document[{{ $document->id }}]"
                                                            data-filename="{{ $document->id . '_filename' }}" onchange="document.getElementById('{{'blah'.$key}}').src = window.URL.createObjectURL(this.files[0])">
                                                    </label>
                                                    @php
                                                    $logo=\App\Models\Utility::get_file('uploads/document/');

                                                    @endphp
                                                    {{-- <a href="#"><p class="{{ $document->id . '_filename' }} "></p></a> --}}
                                                    <img id="{{'blah'.$key}}" src="{{ (isset($employeedoc[$document->id]) && !empty($employeedoc[$document->id])?$logo.'/'.$employeedoc[$document->id]:'') }}"  width="50%" />

                                                </div>

                                                @if (!empty($employeedoc[$document->id]))
                                                     <span class="text-xs-1"><a
                                                            href="{{ !empty($employeedoc[$document->id]) ? asset(Storage::url('uploads/document')) . '/' . $employeedoc[$document->id] : '' }}"
                                                            target="_blank"></a>
                                                    </span>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- //Work Experience-TABLE --}}

                    @foreach ($EXPTableData as $key=>$exp)

                    <div class="col-md-12">
                        <div class="card em-card">
                            <div class="card-header">
                                <h5>{{ __('Work Experience-TABLE') }}</h5>
                                    <div class="float-end">
                                        @if($key == 0)
                                            <p class="btn btn-primary" id='add_sub_form'>{{ '+'}}</p>
                                        @else
                                            <a href="{{ route('exp_table_remove',$exp->id)}}" class="btn btn-danger" id='add_sub_form'>{{ 'remove'}}</a>
                                        @endif
                                    </div>
                            </div>
                            <div class="card-body employee-detail-create-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {!! Form::label('previous_company_name', __('Previous Company'), ['class' => 'form-label']) !!}
                                        {!! Form::text('previous_company_name[]', $exp->previous_company_name, [
                                            'class' => 'form-control',
                                            'required'  => 'required',
                                            'placeholder' => 'Enter previous company',
                                        ]) !!}

                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('previous_job_title', __('Job Title'), ['class' => 'form-label']) !!}
                                        {!! Form::text('previous_job_title[]', $exp->previous_job_title, [
                                            'class' => 'form-control',
                                            'required'  => 'required',
                                            'placeholder' => 'Enter job title',
                                        ]) !!}

                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('previous_from_date', __('From Date'), ['class' => 'form-label']) !!}
                                        {{ Form::date('previous_from_date[]', $exp->previous_from_date, ['class' => 'form-control current_date', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => 'Select Date of Birth']) }}
                                    </div>

                                    <div class="form-group col-md-6">
                                        {!! Form::label('previous_to_date', __('To Date'), ['class' => 'form-label']) !!}
                                        {{ Form::date('previous_to_date[]', $exp->previous_to_date, ['class' => 'form-control current_date', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => 'Select Date of Birth']) }}
                                    </div>

                                    <div class="form-group col-md-12">
                                        {{ Form::label('previous_company_designation_id', __('Select Designation'), ['class' => 'form-label']) }}
                                        {{-- {!! Form::text('previous_company_designation_id[]', $exp->previous_company_designation_id, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter job designation',
                                        ]) !!} --}}
                                        <?php $data = App\Models\Designation::get()?>

                                        <select class="form-control" required name="previous_company_designation_id[]" id="previous_company_designation_id">
                                            @if(!empty($data))
                                                @foreach ($data as $row)
                                                    @if($row->id == $exp->previous_company_designation_id)
                                                        <option value="{{ $row->id }}" selected >{{ $row->name }}</option>
                                                    @else
                                                        <option value="{{ $row->id }}" >{{ $row->name }}</option>

                                                    @endif
                                                @endforeach
                                            @endif

                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        {!! Form::label('experiance_Job_description', __('Experience Job Description'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>

                                        {!! Form::textarea('experiance_Job_description[]', $exp->experiance_Job_description, [
                                        'class' => 'form-control',
                                        'required'  => 'required',
                                        'rows' => 2,
                                        'placeholder' => 'Enter Experience job description',
                                    ]) !!}
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <div id='append_sub_form'></div>


                    {{-- //Education tables --}}
                    @foreach ($EducationData as $key=>$edu)
                    <div class="col-md-12 ">
                        <div class="card em-card">
                            <div class="card-header">
                                <h5>{{ __('Education-TABLE') }}</h5>
                                <div class="float-end">
                                    @if($key == 0)
                                        <p class="btn btn-primary" id='append_sub_form_eduction_table'>{{ '+' }}</p>
                                    @else
                                        <a href="{{ route('eduction_table_remove',$edu->id)}}" class="btn btn-danger" id='add_sub_form'>{{ 'remove'}}</a>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body employee-detail-create-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {!! Form::label('institute_name', __('Institute Name'), ['class' => 'form-label']) !!}
                                        {!! Form::text('institute_name[]', $edu->institute_name, [
                                            'class' => 'form-control',
                                            'required'  => 'required',
                                            'placeholder' => 'Enter institute name',
                                        ]) !!}

                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('education', __('Education'), ['class' => 'form-label']) !!}
                                        {!! Form::text('education[]',$edu->education, [
                                            'class' => 'form-control',
                                            'required'  => 'required',
                                            'placeholder' => 'Enter education',
                                        ]) !!}

                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('specialization', __('Specialization'), ['class' => 'form-label']) !!}
                                        {!! Form::text('specialization[]', $edu->specialization, [
                                            'class' => 'form-control',
                                            'required'  => 'required',
                                            'placeholder' => 'Enter specialization',
                                        ]) !!}
                                    </div>

                                    <div class="form-group col-md-6">
                                        {!! Form::label('date_of_completion', __('Date of completion'), ['class' => 'form-label']) !!}
                                        {{ Form::date('date_of_completion[]', $edu->date_of_completion, ['class' => 'form-control current_date', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => 'Select Date of Birth']) }}
                                    </div>



                                    <div class="form-group col-md-12">
                                        {!! Form::label('notes', __('Notes'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>

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
                    <div id='append_sub_form_eduction_table_add_form'></div>
                     {{-- //Dependenttables --}}
                     <div class="col-md-6 ">
                        <div class="card em-card">
                            <div class="card-header">
                                <h5>{{ __('Dependent') }}</h5>
                            </div>
                            <div class="card-body employee-detail-create-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        {!! Form::label('dependent_name', __('Name'), ['class' => 'form-label']) !!}
                                        {!! Form::text('dependent_name', old('dependent_name'), [
                                            'class' => 'form-control',
                                            'placeholder' => 'Enter dependent name',
                                        ]) !!}

                                    </div>

                                    <div class="form-group col-md-12">
                                        {{ Form::label('dependent_relation', __('Relation Types *'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            <select name="dependent_relation" id="dependent_relation" class="form-control select2">

                                                <option @if($employee->dependent_relation == "Permanent") selected @else  @endif value="Father">Father</option>
                                                <option @if($employee->dependent_relation == "Mother") selected @else  @endif value="Mother">Mother</option>
                                                <option @if($employee->dependent_relation == "Brother") selected @else  @endif value="Brother">Brother</option>
                                                <option @if($employee->dependent_relation == "Sister") selected @else  @endif value="Sister">Sister</option>
                                                <option @if($employee->dependent_relation == "Husband") selected @else  @endif value="Husband">Husband</option>
                                                <option @if($employee->dependent_relation == "Wife") selected @else  @endif value="Wife">Wife</option>
                                                <option @if($employee->dependent_relation == "Child") selected @else  @endif value="Child">Child</option>

                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group col-md-12">
                                        {!! Form::label('dependent_dob', __('Date of completion'), ['class' => 'form-label']) !!}
                                        {{ Form::date('dependent_dob', null, ['class' => 'form-control current_date', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => 'Select Date of Birth']) }}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- // --}}

                    <div class="col-md-6">
                        <div class="card em-card">
                            <div class="card-header">
                                <h5>{{ __('Bank Account Detail') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        {!! Form::label('account_holder_name', __('Account Holder Name'), ['class' => 'form-label']) !!}
                                        {!! Form::text('account_holder_name', null, ['class' => 'form-control']) !!}

                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('account_number', __('Account Number'), ['class' => 'form-label']) !!}
                                        {!! Form::number('account_number', null, ['class' => 'form-control']) !!}

                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('bank_name', __('Bank Name'), ['class' => 'form-label']) !!}
                                        {!! Form::text('bank_name', null, ['class' => 'form-control']) !!}

                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('bank_identifier_code', __('Bank Identifier Code'), ['class' => 'form-label']) !!}
                                        {!! Form::text('bank_identifier_code', null, ['class' => 'form-control']) !!}
                                    </div>
                                    <div class="form-group col-md-6">
                                        {!! Form::label('branch_location', __('Branch Location'), ['class' => 'form-label']) !!}
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
            @else
                <div class="row">
                    <div class="col-md-6 ">
                        <div class="employee-detail-wrap">
                            <div class="card em-card">
                                <div class="card-header">
                                    <h5>{{ __('Document Detail') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @php
                                            $employeedoc = $employee->documents()->pluck('document_value', __('document_id'));
                                            $logo=\App\Models\Utility::get_file('uploads/document/');

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
                            <div class="card em-card">
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

            @if (\Auth::user()->type != 'employee')
                <div class="float-end">
                    <button type="submit" class="btn  btn-primary">{{ 'Update' }}</button>
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
                url: '{{ route('employee.json') }}',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('.designation_id').empty();
                    var emp_selct = ` <select class="form-control col-md-12 designation_id select2" name="designation_id" id="choices-multiple"
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

        $(document).ready(function() {
            var d_id = $('#department_id').val();
            var designation_id = '{{ $employee->designation_id }}';
            getDesignation(d_id);

            //ajax
            var index = {{$EXPTableDatacount}}
            $("#add_sub_form").click(function() {

            index++;
            var designations = <?php echo json_encode($designations); ?>;

            $('#append_sub_form').append('<div class="col-md-12" id="delete_sub_form">'+
                '<div class="card em-card">'+
                '<div class="card-header">'+
                    '<h5>Work Experience-TABLE</h5>'+
                    '<p class="btn btn-danger float-end" id="programings_btn_remove">+ remove</p>'+'</div>'+'<div class="card-body employee-detail-create-body">'+
                        '<div class="row">'+
                            '<div class="form-group col-md-6">'+
                                '<lable class="form-label">Previous Company</lable>'+
                                '<input type="text" name="previous_company_name[' +index+ ']" class="form-control" placeholder="Enter previous company" required></input>'+
                            '</div>'+
                            '<div class="form-group col-md-6">'+
                                '<lable class="form-label">Job Title</lable>'+
                                    '<input type="text" name="previous_job_title[' +index+ ']" class="form-control" placeholder="Enter previous job title"></input>'+
                            '</div>'+
                        '<div class="form-group col-md-6">'+
                            '<lable class="form-label">From Date</lable>'+
                                '<input type="date" name="previous_from_date[' +index+ ']" class="form-control" placeholder="Enter previous job title"></input>'+
                        '</div>'+
                        '<div class="form-group col-md-6">'+
                            '<lable class="form-label">To Date</lable>'+
                                '<input type="date" name="previous_to_date[' +index+ ']" class="form-control" placeholder="Enter previous job title"></input>'+'</div>'+

                        '<div class="form-group col-md-12">'+
                            '<select  id="previous_company_designation_id'+index+'" name="previous_company_designation_id['+index+']"class="form-control" >'+
                                '<option>select option</option>'+
                            '</select></div>'+
                        '<div class="form-group col-md-12">'+
                            '<lable class="form-label">Experience Job Description</lable>'+
                                '<textarea type="date" name="experiance_Job_description[' +index+ ']" class="form-control" placeholder="Enter Experience job description"></textarea>'+
                            '</div>'+
                        '</div></div></div></div>');

                        $.each(designations, function(key, value) {
                            $('#previous_company_designation_id'+index+'').append('<option value="' + key + '">' + value + '</option>');
                        });

                });
                $(document).on('click', '#programings_btn_remove', function() {
                    $(this).closest('div#delete_sub_form').remove();
                });
        });

        //education table
        var eduction_table = {{$EducationDatacount}}
        $("#append_sub_form_eduction_table").click(function() {

            eduction_table++;

            $('#append_sub_form_eduction_table_add_form').append('<div class="col-md-12" id="delete_sub_form_eduction_table">'+
                '<div class="card em-card">'+
                '<div class="card-header">'+
                    '<h5>Education-TABLE</h5>'+
                    '<p class="btn btn-danger float-end" id="programings_btn_remove_eduction_table">+ remove</p>'+'</div>'+'<div class="card-body employee-detail-create-body">'+
                        '<div class="row">'+
                            '<div class="form-group col-md-6">'+
                                '<lable class="form-label">Institute Name</lable>'+
                                '<input type="text" name="institute_name[' + eduction_table + ']" class="form-control" placeholder="Enter institute name" required></input>'+
                            '</div>'+
                            '<div class="form-group col-md-6">'+
                                '<lable class="form-label">Education</lable>'+
                                    '<input type="text" name="education[' +eduction_table + ']" class="form-control" placeholder="Enter previous job title"></input>'+
                            '</div>'+
                        '<div class="form-group col-md-6">'+
                            '<lable class="form-label">Specialization</lable>'+
                                '<input type="text" name="specialization[' + eduction_table + ']" class="form-control" placeholder="Enter specialization"></input>'+
                        '</div>'+
                        '<div class="form-group col-md-6">'+
                            '<lable class="form-label">Date of completion</lable>'+
                                '<input type="date" name="date_of_completion[' + eduction_table + ']" class="form-control" placeholder="Enter previous job title"></input>'+'</div>'+

                        '<div class="form-group col-md-12">'+
                            '<lable class="form-label">Notes</lable>'+
                                '<textarea type="date" name="notes[' + eduction_table + ']" class="form-control" placeholder="Enter notes"></textarea>'+
                            '</div>'+
                        '</div></div></div></div>');
                });
                $(document).on('click', '#programings_btn_remove_eduction_table', function() {
                    $(this).closest('div#delete_sub_form_eduction_table').remove();
                });

        $(document).on('change', 'select[name=department_id]', function() {
            var department_id = $(this).val();
            getDesignation(department_id);
        });
    </script>
@endpush
