@extends('layouts.admin')

@section('page-title')
    {{ __('Create Company Invoice') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('crm.invoices.index') }}">{{ __('Company Invoices') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create') }}</li>
@endsection

@section('content')
<div class="crm-invoice-module">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            @include('crm_invoice.partials.admin_help', ['helpId' => 'crmCreateHelp', 'helpOpen' => true])

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Create Company Invoice') }}</h5>
                    <small class="text-muted">
                        {{ __('Only custom-billing companies (plan #9). Global subscription companies are excluded. Auto generate is paused — use manual create.') }}
                    </small>
                </div>
                <div class="card-body">
                    @if($companies->isEmpty())
                        <div class="alert alert-warning mb-0">
                            {{ __('No custom-billing companies found. Create a company with “Custom Employee / User Plan” first.') }}
                        </div>
                    @else
                    <form method="post" action="{{ route('crm.invoices.store') }}" id="crmInvoiceCreateForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">{{ __('Company') }} <span class="text-danger">*</span></label>
                                <select name="company_id" id="crmCompanyId" class="form-control" required>
                                    <option value="">{{ __('Select company') }}</option>
                                    @foreach($companies as $c)
                                        <option value="{{ $c->id }}" @selected(old('company_id') == $c->id)>{{ $c->name }} ({{ $c->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label">{{ __('Billing Year') }}</label>
                                <input type="number" name="billing_year" class="form-control" value="{{ old('billing_year', date('Y')) }}" required>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label">{{ __('Billing Month') }}</label>
                                <input type="number" name="billing_month" min="1" max="12" class="form-control" value="{{ old('billing_month', date('n')) }}" required>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label">{{ __('Active Employees') }}</label>
                                <input type="number" name="active_employees_count" id="crmEmpCount" class="form-control" min="0" value="{{ old('active_employees_count') }}">
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label">{{ __('Price per Employee') }}</label>
                                <input type="number" step="0.01" name="price_per_employee" id="crmEmpPrice" class="form-control" value="{{ old('price_per_employee') }}">
                            </div>

                            <div class="col-12">
                                <div class="border rounded p-3 bg-light">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="exclude_users" value="1"
                                            id="crmExcludeUsers" @checked(old('exclude_users') || old('active_users_count') === '0' || old('active_users_count') === 0)>
                                        <label class="form-check-label fw-semibold" for="crmExcludeUsers">
                                            {{ __('Do not charge for Users (set Active Users = 0)') }}
                                        </label>
                                    </div>
                                    <p class="small text-muted mb-3">
                                        {{ __('Use this when the company should only pay for employees this period. A note is added on the invoice automatically, and the Users line is not included.') }}
                                    </p>
                                    <div class="row g-3">
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">{{ __('Active Users') }}</label>
                                            <input type="number" name="active_users_count" id="crmUserCount" class="form-control" min="0"
                                                value="{{ old('active_users_count', old('exclude_users') ? 0 : null) }}">
                                            <small class="text-muted" id="crmDetectedUsersHint"></small>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <label class="form-label">{{ __('Price per User') }}</label>
                                            <input type="number" step="0.01" name="price_per_user" id="crmUserPrice" class="form-control" value="{{ old('price_per_user') }}">
                                        </div>
                                    </div>
                                    <div class="alert alert-warning mt-3 mb-0 py-2 d-none" id="crmUsersZeroAlert">
                                        <i class="ti ti-info-circle me-1"></i>
                                        {{ __('Users will not be billed. Invoice note will include: “User seats not billed on this invoice (Active Users = 0).”') }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('Invoice Date') }}</label>
                                <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', date('Y-m-d')) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Due Date') }}</label>
                                <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Buyer GSTIN') }}</label>
                                <input type="text" name="company_gstin" class="form-control" value="{{ old('company_gstin') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Place Of Supply') }}</label>
                                <input type="text" name="place_of_supply" class="form-control" value="{{ old('place_of_supply', $settings->seller_state ?: 'Gujarat') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('State Code') }}</label>
                                <input type="text" name="place_of_supply_code" class="form-control" value="{{ old('place_of_supply_code', $settings->seller_state_code ?: '24') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Terms') }}</label>
                                <input type="text" name="terms" class="form-control" value="{{ old('terms', $settings->default_terms ?: 'Due on Receipt') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Ship To Name') }}</label>
                                <input type="text" name="ship_to_name" class="form-control" value="{{ old('ship_to_name') }}" placeholder="{{ __('Same as Bill To if blank') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('Ship To Address') }}</label>
                                <textarea name="ship_to_address" class="form-control" rows="2" placeholder="{{ __('Same as Bill To if blank') }}">{{ old('ship_to_address') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Notes') }}</label>
                                <textarea name="notes" id="crmNotes" class="form-control" rows="3">{{ old('notes', $settings->default_notes) }}</textarea>
                                <small class="text-muted">{{ __('If users are not charged, the system appends an automatic note here.') }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Payment Instructions') }}</label>
                                <textarea name="payment_instructions" class="form-control" rows="3">{{ old('payment_instructions', $settings->payment_instructions) }}</textarea>
                            </div>
                            <div class="col-12 d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary">{{ __('Create Invoice') }}</button>
                                <a href="{{ route('crm.invoices.index') }}" class="btn btn-light">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
(function () {
    const excludeEl = document.getElementById('crmExcludeUsers');
    const userCountEl = document.getElementById('crmUserCount');
    const userPriceEl = document.getElementById('crmUserPrice');
    const alertEl = document.getElementById('crmUsersZeroAlert');
    const hintEl = document.getElementById('crmDetectedUsersHint');
    let lastDetectedUsers = null;
    let lastDetectedUserPrice = null;

    function syncExcludeUsersUi() {
        const exclude = !!(excludeEl && excludeEl.checked);
        const zeroManual = userCountEl && String(userCountEl.value) === '0';
        if (exclude && userCountEl) {
            userCountEl.value = 0;
            userCountEl.readOnly = true;
        } else if (userCountEl) {
            userCountEl.readOnly = false;
            if (lastDetectedUsers !== null && (userCountEl.value === '' || userCountEl.value === '0') && !zeroManual) {
                // keep as-is after uncheck unless empty
            }
        }
        if (alertEl) {
            alertEl.classList.toggle('d-none', !(exclude || zeroManual));
        }
    }

    excludeEl?.addEventListener('change', function () {
        if (this.checked) {
            if (userCountEl) userCountEl.value = 0;
        } else if (userCountEl && lastDetectedUsers !== null) {
            userCountEl.value = lastDetectedUsers;
        }
        syncExcludeUsersUi();
    });

    userCountEl?.addEventListener('input', function () {
        if (String(this.value) === '0' && excludeEl && !excludeEl.checked) {
            // optional: do not auto-check; just show alert
        }
        if (String(this.value) !== '0' && excludeEl && excludeEl.checked) {
            excludeEl.checked = false;
        }
        syncExcludeUsersUi();
    });

    document.getElementById('crmCompanyId')?.addEventListener('change', function () {
        const id = this.value;
        if (!id) return;
        fetch(`{{ route('crm.invoices.company-count') }}?company_id=${id}`)
            .then(r => r.json())
            .then(data => {
                document.getElementById('crmEmpCount').value = data.active_employees ?? 0;
                lastDetectedUsers = data.active_users ?? 0;
                lastDetectedUserPrice = data.price_per_user ?? null;
                if (hintEl) {
                    hintEl.textContent = `{{ __('Detected in system') }}: ${lastDetectedUsers} {{ __('active users') }}`;
                }
                if (!(excludeEl && excludeEl.checked)) {
                    document.getElementById('crmUserCount').value = lastDetectedUsers;
                } else {
                    document.getElementById('crmUserCount').value = 0;
                }
                if (data.price_per_employee !== null && data.price_per_employee !== undefined) {
                    document.getElementById('crmEmpPrice').value = data.price_per_employee;
                }
                if (data.price_per_user !== null && data.price_per_user !== undefined) {
                    document.getElementById('crmUserPrice').value = data.price_per_user;
                }
                syncExcludeUsersUi();
            });
    });

    syncExcludeUsersUi();
})();
</script>
@endpush
