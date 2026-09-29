{{ Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
            <div class="form-icon-user">
                {!! Form::text('name', null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter Name',
                ]) !!}
            </div>
        </div>
        <div class="form-group col-md-6">
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
            {{-- Company Usage & Capacity Overview Summary Card --}}
            <div class="col-12 mb-3">
                <div class="p-3 bg-light rounded-3 border">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-bold text-dark fs-7">
                            <i class="ti ti-building me-1 text-primary"></i> {{ __('Current Capacity & Usage Overview') }}
                        </span>
                        <span class="badge bg-light-primary text-primary border border-primary-subtle">
                            {{ $user->currentPlan ? $user->currentPlan->name : __('No Plan') }} ({{ ucfirst($user->currentPlan->duration ?? 'N/A') }})
                        </span>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="bg-white p-2 rounded border">
                                <small class="text-muted d-block fs-8">{{ __('Active Employees') }}</small>
                                <span class="fw-bold text-dark fs-6">{{ $user->countEmployees() }}</span>
                                <small class="text-muted">/ {{ $user->getMaxEmployees() == -1 ? __('Unlimited') : $user->getMaxEmployees() }}</small>
                                @if($user->custom_max_employees !== null)
                                    <span class="badge bg-warning text-dark ms-1" style="font-size: 9px;">{{ __('Custom') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-white p-2 rounded border">
                                <small class="text-muted d-block fs-8">{{ __('Active Sub-Users') }}</small>
                                <span class="fw-bold text-dark fs-6">{{ $user->countUsers() }}</span>
                                <small class="text-muted">/ {{ $user->getMaxUsers() == -1 ? __('Unlimited') : $user->getMaxUsers() }}</small>
                                @if($user->custom_max_users !== null)
                                    <span class="badge bg-warning text-dark ms-1" style="font-size: 9px;">{{ __('Custom') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $currentBillingMode = optional($billingProfile ?? null)->billing_mode ?? 'global';
            @endphp

            <div class="form-group col-12">
                <label class="form-label d-block">{{ __('Billing Mode') }} <span class="text-danger">*</span></label>
                <div class="d-flex flex-wrap gap-3">
                    <label class="form-check border rounded p-3 flex-fill mb-0" style="cursor:pointer;min-width:200px;">
                        <input class="form-check-input me-2" type="radio" name="billing_mode" value="global" {{ $currentBillingMode === 'global' ? 'checked' : '' }}>
                        <span class="fw-semibold">{{ __('Global Subscription Plan') }}</span>
                    </label>
                    <label class="form-check border rounded p-3 flex-fill mb-0" style="cursor:pointer;min-width:200px;">
                        <input class="form-check-input me-2" type="radio" name="billing_mode" value="custom" {{ $currentBillingMode === 'custom' ? 'checked' : '' }}>
                        <span class="fw-semibold">{{ __('Custom Employee / User Plan') }}</span>
                    </label>
                </div>
            </div>

            <div class="form-group col-md-6" id="edit_global_plan_wrap">
                {{ Form::label('plan_id', __('Subscription Plan'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                <div class="form-icon-user">
                    <select name="plan_id" class="form-control select2" id="plan_id">
                        <option value="">{{ __('Please select a plan') }}</option>
                        @foreach ($plans as $key => $plan)
                            @if ((int) $key !== 9)
                                <option value="{{ $key }}" @selected((int) $user->plan === (int) $key)>{{ $plan }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group col-md-6" id="edit_plan_expire_wrap">
                {{ Form::label('plan_expire_date', __('Plan Expiry Date'), ['class' => 'form-label']) }}
                {!! Form::date('plan_expire_date', $user->plan_expire_date, ['class' => 'form-control']) !!}
            </div>

            <div id="edit_custom_plan_wrap" class="col-12 {{ $currentBillingMode === 'custom' ? '' : 'd-none' }}">
                <div class="alert alert-light border small mb-3">
                    {{ __('Plan #9 Unlimited — no subscription lock. Manual CRM invoices only.') }}
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        {{ Form::label('price_per_employee', __('Employee Price'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                        <input type="number" step="0.01" min="0" name="price_per_employee" class="form-control"
                            value="{{ old('price_per_employee', optional($billingProfile ?? null)->price_per_employee) }}">
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('price_per_user', __('User Price'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                        <input type="number" step="0.01" min="0" name="price_per_user" class="form-control"
                            value="{{ old('price_per_user', optional($billingProfile ?? null)->price_per_user) }}">
                    </div>
                </div>
            </div>

            <div class="col-12 mt-1 mb-2">
                <div class="d-flex align-items-center gap-1">
                    <i class="ti ti-adjustments text-warning fs-5"></i>
                    <h6 class="fw-bold text-dark mb-0">{{ __('Custom Capacity Limits (Admin Override)') }}</h6>
                </div>
                <small class="text-muted fs-8">{{ __('Set custom limits specifically for this company. Leave blank to inherit plan defaults (-1 for Unlimited).') }}</small>
            </div>

            <div class="form-group col-md-6">
                {{ Form::label('custom_max_employees', __('Custom Max Employees Limit'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {!! Form::number('custom_max_employees', $user->custom_max_employees, [
                        'class' => 'form-control',
                        'placeholder' => __('Plan Default: ') . ($user->currentPlan ? ($user->currentPlan->max_employees == -1 ? __('Unlimited (-1)') : $user->currentPlan->max_employees) : '0'),
                        'min' => '-1',
                    ]) !!}
                </div>
                <small class="text-muted fs-8">{{ __('Leave blank for plan default, or enter limit (-1 = unlimited)') }}</small>
            </div>

            <div class="form-group col-md-6">
                {{ Form::label('custom_max_users', __('Custom Max Users Limit'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {!! Form::number('custom_max_users', $user->custom_max_users, [
                        'class' => 'form-control',
                        'placeholder' => __('Plan Default: ') . ($user->currentPlan ? ($user->currentPlan->max_users == -1 ? __('Unlimited (-1)') : $user->currentPlan->max_users) : '0'),
                        'min' => '-1',
                    ]) !!}
                </div>
                <small class="text-muted fs-8">{{ __('Leave blank for plan default, or enter limit (-1 = unlimited)') }}</small>
            </div>
        @endif

        @if (\Auth::user()->type != 'super admin')
            <div class="form-group col-lg-12 col-md-12">
                {{ Form::label('role', __('User Role')) }} <span class="text-danger"> * </span>
                {!! Form::select('role', $roles, $user->roles, ['class' => 'form-control font-style select2']) !!}
                @error('role')
                    <span class="invalid-role" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group col-12">
                {{ Form::label('role', __('Assign Employee'), ['class' => 'form-label']) }}
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
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update Company & Limits') }}" class="btn btn-primary">
</div>
{!! Form::close() !!}

@if (\Auth::user()->type == 'super admin')
<script>
(function () {
    function toggleEditBilling() {
        var mode = document.querySelector('input[name="billing_mode"]:checked');
        var isCustom = mode && mode.value === 'custom';
        var g = document.getElementById('edit_global_plan_wrap');
        var e = document.getElementById('edit_plan_expire_wrap');
        var c = document.getElementById('edit_custom_plan_wrap');
        if (!g || !c) return;
        if (isCustom) {
            g.classList.add('d-none');
            if (e) e.classList.add('d-none');
            c.classList.remove('d-none');
        } else {
            g.classList.remove('d-none');
            if (e) e.classList.remove('d-none');
            c.classList.add('d-none');
        }
    }
    document.querySelectorAll('input[name="billing_mode"]').forEach(function (el) {
        el.addEventListener('change', toggleEditBilling);
    });
    toggleEditBilling();
})();
</script>
@endif
