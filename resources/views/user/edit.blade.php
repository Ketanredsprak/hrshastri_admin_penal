{{ Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
            <div class="form-icon-user">
                {!! Form::text('name', null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter Name',
                ]) !!}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                {!! Form::text('email', null, [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'required' => 'required',
                    'placeholder' => 'Enter Email',
                ]) !!}
            </div>
        </div>
        @if (\Auth::user()->type == 'super admin')
            <div class="form-group">
                {{ Form::label('plan_id', __('Plans'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                <div class="form-icon-user">
                    <select name="plan_id" class="form-control select2" id="plan_id">
                        <option value="">{{ __('Please select as plan') }}</option>
                        @foreach ($plans as $key => $plan)
                            @if ($key == $user->plan)
                                <option value="{{ $key }}" selected>{{ $plan }}</option>
                            @else
                                <option value="{{ $key }}">{{ $plan }}</option>
                            @endif
                        @endforeach
                    </select>
                    <span class="dropdown-wrapper" aria-hidden="true"></span>
                </div>
            </div>
        @endif

        @if (\Auth::user()->type != 'super admin')
            <div class="form-group  col-lg-12 col-md-12">
                {{ Form::label('role', __('User Role')) }} <span class="text-danger"> * </span>
                {!! Form::select('role', $roles, $user->roles, ['class' => 'form-control font-style select2']) !!}
                @error('role')
                    <span class="invalid-role" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        @endif
        @if (\Auth::user()->type != 'super admin')
            <div class="form-group">
                {{ Form::label('role', __('Assigen Employee'), ['class' => 'form-label']) }}
                <div class="form-icon-user">

                    <select name="assign_employees[]" class="select2" id="assign_employees" multiple>
                        <option value="">Select employee</option>
                        @foreach ($employees as $row)
                            @if (in_array($row->id, $selectemployees ?? []))
                                <option selected value="{{ $row->id }}">{{ $row->name }}</option>
                            @else
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{!! Form::close() !!}
