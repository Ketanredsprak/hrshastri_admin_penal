{{-- Admin help: how Company Invoices (CRM TAX INVOICE) work --}}
@php
    $helpId = $helpId ?? 'crmInvoiceHelp';
    $helpOpen = !empty($helpOpen);
@endphp
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-light d-flex align-items-center justify-content-between py-2">
        <h6 class="mb-0">
            <i class="ti ti-help-circle text-primary me-1"></i>
            {{ __('How Company Invoices Work (Admin Guide)') }}
        </h6>
        <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse"
            data-bs-target="#{{ $helpId }}" aria-expanded="{{ $helpOpen ? 'true' : 'false' }}">
            {{ __('Show / Hide') }}
        </button>
    </div>
    <div id="{{ $helpId }}" class="collapse {{ $helpOpen ? 'show' : '' }}">
        <div class="card-body small">
            <div class="row g-3">
                <div class="col-lg-6">
                    <h6 class="fw-semibold">{{ __('Who can be invoiced?') }}</h6>
                    <ul class="mb-3 ps-3">
                        <li>{{ __('Only companies with Custom Employee / User billing (plan #9 style).') }}</li>
                        <li>{{ __('Global subscription plan companies are excluded — they pay via plan subscription.') }}</li>
                        <li>{{ __('Auto monthly generate is paused; Super Admin creates invoices manually.') }}</li>
                    </ul>

                    <h6 class="fw-semibold">{{ __('Employees vs Users on invoice') }}</h6>
                    <ul class="mb-0 ps-3">
                        <li><strong>{{ __('Employees') }}</strong> — {{ __('Active employees count × price per employee.') }}</li>
                        <li><strong>{{ __('Users') }}</strong> — {{ __('Active HR/staff users count × price per user (optional).') }}</li>
                        <li>{{ __('CGST + SGST are applied on the combined subtotal (TAX INVOICE).') }}</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <h6 class="fw-semibold text-warning">
                        <i class="ti ti-user-off me-1"></i>{{ __('Do not charge for Users') }}
                    </h6>
                    <p class="mb-2">
                        {{ __('When you do not want to bill user seats for a period:') }}
                    </p>
                    <ol class="ps-3 mb-2">
                        <li>{{ __('Tick “Do not charge for Users” on Create/Edit, OR') }}</li>
                        <li>{{ __('Manually set Active Users = 0.') }}</li>
                    </ol>
                    <ul class="ps-3 mb-2">
                        <li>{{ __('User line is removed from the invoice.') }}</li>
                        <li>{{ __('Invoice total uses employees (and tax) only.') }}</li>
                        <li>{{ __('A clear note is added automatically, e.g.') }}
                            <em>{{ __('“User seats not billed on this invoice (Active Users = 0).”') }}</em>
                        </li>
                        <li>{{ __('Detected system user count may be recorded in notes for audit (not charged).') }}</li>
                    </ul>
                    <div class="alert alert-info mb-0 py-2">
                        {{ __('Tip: Selecting a company auto-fills employee/user counts and prices from the billing profile. Adjust before saving.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
