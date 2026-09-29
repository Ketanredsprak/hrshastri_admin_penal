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

        <div class="form-group col-12">
            <label class="form-label d-block">{{ __('Billing Mode') }} <span class="text-danger">*</span></label>
            <div class="d-flex flex-wrap gap-3">
                <label class="form-check border rounded p-3 flex-fill mb-0" style="cursor:pointer;min-width:220px;">
                    <input class="form-check-input me-2" type="radio" name="billing_mode" id="billing_mode_global" value="global" checked>
                    <span class="fw-semibold">{{ __('Global Subscription Plan') }}</span>
                    <div class="small text-muted mt-1">{{ __('Use Basic / Monthly / Yearly plans. Subscription lock applies on expiry. No CRM employee invoice.') }}</div>
                </label>
                <label class="form-check border rounded p-3 flex-fill mb-0" style="cursor:pointer;min-width:220px;">
                    <input class="form-check-input me-2" type="radio" name="billing_mode" id="billing_mode_custom" value="custom">
                    <span class="fw-semibold">{{ __('Custom Employee / User Plan') }}</span>
                    <div class="small text-muted mt-1">{{ __('Auto-selects Unlimited plan (#9). Set per-company employee & user price. Manual CRM invoices only.') }}</div>
                </label>
            </div>
        </div>

        <div class="form-group" id="global_plan_wrap">
            {{ Form::label('plan_id', __('Plans'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
            <div class="form-icon-user">
                <select name="plan_id" class="form-control select2" id="plan_id">
                    <option value="">{{ __('Please select as plan') }}</option>
                    @foreach ($plans as $key => $plan)
                        @if ((int) $key !== 9)
                            <option value="{{ $key }}">{{ $plan }}</option>
                        @endif
                    @endforeach
                </select>
                <span class="dropdown-wrapper" aria-hidden="true"></span>
            </div>
        </div>

        <div id="custom_plan_wrap" class="d-none">
            <input type="hidden" name="custom_plan_id" value="9">
            <div class="alert alert-light border small mb-3">
                {{ __('Plan') }}: <strong>{{ $plans[9] ?? __('Unlimited Plan with monthly invoice') }} (#9)</strong>
                — {{ __('No subscription lock. Invoice manually from Company Invoices.') }}
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    {{ Form::label('price_per_employee', __('Employee Price'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                    {!! Form::number('price_per_employee', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'placeholder' => 'e.g. 100', 'id' => 'price_per_employee']) !!}
                </div>
                <div class="form-group col-md-6">
                    {{ Form::label('price_per_user', __('User Price'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                    {!! Form::number('price_per_user', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'placeholder' => 'e.g. 150', 'id' => 'price_per_user']) !!}
                </div>
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

@if(\Auth::user()->type == 'super admin')
<script>
(function () {
    function toggleBillingMode() {
        var mode = document.querySelector('input[name="billing_mode"]:checked');
        var isCustom = mode && mode.value === 'custom';
        var globalWrap = document.getElementById('global_plan_wrap');
        var customWrap = document.getElementById('custom_plan_wrap');
        var planSelect = document.getElementById('plan_id');
        if (!globalWrap || !customWrap) return;
        if (isCustom) {
            globalWrap.classList.add('d-none');
            customWrap.classList.remove('d-none');
            if (planSelect) planSelect.removeAttribute('required');
        } else {
            globalWrap.classList.remove('d-none');
            customWrap.classList.add('d-none');
            if (planSelect) planSelect.setAttribute('required', 'required');
        }
    }
    document.querySelectorAll('input[name="billing_mode"]').forEach(function (el) {
        el.addEventListener('change', toggleBillingMode);
    });
    toggleBillingMode();
})();
</script>
@endif
