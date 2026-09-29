@extends('layouts.admin')

@section('page-title')
    {{ __('CRM Invoice Settings') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('crm.invoices.index') }}">{{ __('Company Invoices') }}</a></li>
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection

@section('action-button')
    <a href="{{ route('crm.invoices.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="ti ti-arrow-left"></i> {{ __('Back to Invoices') }}
    </a>
@endsection

@section('content')
<div class="crm-invoice-module">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('TAX Invoice Settings') }}</h5>
                    <small class="text-muted">{{ __('Zoho-style TAX INVOICE defaults (seller, GST, bank, numbering).') }}</small>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('crm.invoices.settings.update') }}" enctype="multipart/form-data">
                        @csrf
                        <h6 class="text-muted text-uppercase small mb-2">{{ __('Billing Rates') }}</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Price per Employee') }}</label>
                                <input type="number" step="0.01" name="price_per_employee" class="form-control" value="{{ old('price_per_employee', $settings->price_per_employee) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('CGST %') }}</label>
                                <input type="number" step="0.01" min="0" max="100" name="cgst_percent" class="form-control"
                                    value="{{ old('cgst_percent', $settings->cgst_percent ?? 0) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('SGST %') }}</label>
                                <input type="number" step="0.01" min="0" max="100" name="sgst_percent" class="form-control"
                                    value="{{ old('sgst_percent', $settings->sgst_percent ?? 0) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Currency') }}</label>
                                <input type="text" name="currency" class="form-control" value="{{ old('currency', $settings->currency) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Due Days') }}</label>
                                <input type="number" name="due_days" class="form-control" value="{{ old('due_days', $settings->due_days) }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Invoice Prefix') }}</label>
                                <input type="text" name="invoice_prefix" class="form-control" value="{{ old('invoice_prefix', $settings->invoice_prefix) }}" required>
                                <small class="text-muted">{{ __('Example: INV → INV/26-27/000001') }}</small>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('Default HSN/SAC') }}</label>
                                <input type="text" name="default_hsn_sac" class="form-control" value="{{ old('default_hsn_sac', $settings->default_hsn_sac) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Default Terms') }}</label>
                                <input type="text" name="default_terms" class="form-control" value="{{ old('default_terms', $settings->default_terms) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Product Name') }}</label>
                                <input type="text" name="product_name" class="form-control" value="{{ old('product_name', $settings->product_name) }}">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="use_fy_numbering" value="1" id="useFy"
                                        @checked(old('use_fy_numbering', $settings->use_fy_numbering ?? true))>
                                    <label class="form-check-label" for="useFy">{{ __('Use Indian FY numbering (INV/26-27/######)') }}</label>
                                </div>
                            </div>
                        </div>

                        <h6 class="text-muted text-uppercase small mb-2">{{ __('Seller (TAX INVOICE Header)') }}</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Seller Name') }}</label>
                                <input type="text" name="seller_name" class="form-control" value="{{ old('seller_name', $settings->seller_name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Seller GSTIN') }}</label>
                                <input type="text" name="seller_gstin" class="form-control" value="{{ old('seller_gstin', $settings->seller_gstin) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Seller State') }}</label>
                                <input type="text" name="seller_state" class="form-control" value="{{ old('seller_state', $settings->seller_state) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('State Code') }}</label>
                                <input type="text" name="seller_state_code" class="form-control" value="{{ old('seller_state_code', $settings->seller_state_code) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Seller Phone') }}</label>
                                <input type="text" name="seller_phone" class="form-control" value="{{ old('seller_phone', $settings->seller_phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Seller Email') }}</label>
                                <input type="email" name="seller_email" class="form-control" value="{{ old('seller_email', $settings->seller_email) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Logo') }}</label>
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                @if($settings->logo_path)
                                    <small class="text-muted d-block mt-1">{{ $settings->logo_path }}</small>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Authorized Signature Image') }}</label>
                                <input type="file" name="signature" class="form-control" accept="image/*">
                                @if($settings->signature_path)
                                    <small class="text-muted d-block mt-1">{{ $settings->signature_path }}</small>
                                @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('Seller Address') }}</label>
                                <textarea name="seller_address" class="form-control" rows="3">{{ old('seller_address', $settings->seller_address) }}</textarea>
                            </div>
                        </div>

                        <h6 class="text-muted text-uppercase small mb-2">{{ __('Bank Details') }}</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Bank Name') }}</label>
                                <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $settings->bank_name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Account No') }}</label>
                                <input type="text" name="bank_account_no" class="form-control" value="{{ old('bank_account_no', $settings->bank_account_no) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('IFSC') }}</label>
                                <input type="text" name="bank_ifsc" class="form-control" value="{{ old('bank_ifsc', $settings->bank_ifsc) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('SWIFT') }}</label>
                                <input type="text" name="bank_swift" class="form-control" value="{{ old('bank_swift', $settings->bank_swift) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Default Notes') }}</label>
                                <textarea name="default_notes" class="form-control" rows="2">{{ old('default_notes', $settings->default_notes) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Extra Payment Instructions') }}</label>
                                <textarea name="payment_instructions" class="form-control" rows="2">{{ old('payment_instructions', $settings->payment_instructions) }}</textarea>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="auto_email_on_generate" value="1" id="autoEmail"
                                        @checked(old('auto_email_on_generate', $settings->auto_email_on_generate))>
                                    <label class="form-check-label" for="autoEmail">{{ __('Auto-email on monthly generate') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="skip_zero_employee" value="1" id="skipZero"
                                        @checked(old('skip_zero_employee', $settings->skip_zero_employee))>
                                    <label class="form-check-label" for="skipZero">{{ __('Skip companies with 0 active employees') }}</label>
                                </div>
                            </div>
                            <div class="col-12 d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary">{{ __('Save Settings') }}</button>
                                <a href="{{ route('crm.invoices.index') }}" class="btn btn-light">{{ __('Back') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
