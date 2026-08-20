{!! Form::open(['route' => 'user.store', 'method' => 'post','novalidate']) !!}
<div class="modal-body">
    <div class="row">
        @if(\Auth::user()->type != 'super admin')
        <div class="form-group">
            {{ Form::label('employee_id', __('Employee  '), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
            <div class="form-icon-user">
                <select name="employee_id" class="form-control select2" id="employee_id" required="true">
                    <option value="">Please Select</option>
                    @foreach ($employeeuser as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
            </div>
        </div>
        @endif

        @if(\Auth::user()->type == 'super admin')
        <div class="form-group">
            {{ Form::label('name', __('Company Name '), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
            <div class="form-icon-user">
                {!! Form::text('name', null, ['class' => 'form-control','placeholder'=>'Enter Name','id' => 'name']) !!}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
            <div class="form-icon-user">
                {!! Form::text('email', null, ['class' => 'form-control','placeholder'=>'Enter Email','id' => 'email']) !!}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('password', __('Password'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
            <div class="form-icon-user">
                {!! Form::password('password', ['class' => 'form-control','placeholder'=>'Enter password','id' => 'password']) !!}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('plan_id', __('Plans'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
            <div class="form-icon-user">
                <select name="plan_id" class="form-control select2" id="plan_id">
                    <option value="">{{ __('Please select as plan') }}</option>
                    @foreach ($plans as $key => $plan)
                    <option value="{{ $key }}">{{ $plan }}</option>
                    @endforeach
                </select>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
            </div>
        </div>
        @endif

        @if(\Auth::user()->type != 'super admin')
        <div class="form-group col-lg-12 col-md-12">
            {{ Form::label('roles_id', __('User Role')) }} <span class="text-danger"> * </span>
            {!! Form::select('role', $roles, null,array('class' => 'form-control select2','id' => 'roles_id', 'placeholder' => 'Please select a role')) !!}
            @error('role')
            <span class="invalid-role" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        
        <div class="form-group">
            {{ Form::label('assign_employees', __('Assigen Employee'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                <select name="assign_employees[]" class="select2" id="assign_employees" multiple>
                    @foreach ($employees as $emp)
                    <option value="">Select employee</option>
                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{!! Form::close() !!}