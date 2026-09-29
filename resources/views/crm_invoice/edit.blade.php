@extends('layouts.admin')

@section('page-title')
    {{ __('Edit Invoice') }} — {{ $invoice->invoice_number }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('crm.invoices.index') }}">{{ __('Company Invoices') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit') }}</li>
@endsection

@section('action-button')
    <a href="{{ route('crm.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-secondary">
        <i class="ti ti-arrow-left"></i> {{ __('Back to Invoice') }}
    </a>
@endsection

@section('content')
<div class="crm-invoice-module">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h5 class="mb-0">{{ __('Edit Invoice') }} — {{ $invoice->invoice_number }}</h5>
                        <small class="text-muted">{{ __('Period') }}: {{ $invoice->periodLabel() }} · {{ __('Status') }}: {{ ucfirst($invoice->status) }}</small>
                    </div>
                    <span class="badge {{ $invoice->statusBadgeClass() }}">{{ ucfirst($invoice->status) }}</span>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('crm.invoices.update', $invoice->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Company Name') }}</label>
                                <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $invoice->company_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Company Email') }}</label>
                                <input type="email" name="company_email" class="form-control" value="{{ old('company_email', $invoice->company_email) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ __('Company Address') }}</label>
                                <textarea name="company_address" class="form-control" rows="2">{{ old('company_address', $invoice->company_address) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Company Phone') }}</label>
                                <input type="text" name="company_phone" class="form-control" value="{{ old('company_phone', $invoice->company_phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Buyer GSTIN') }}</label>
                                <input type="text" name="company_gstin" class="form-control" value="{{ old('company_gstin', $invoice->company_gstin) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Place Of Supply') }}</label>
                                <input type="text" name="place_of_supply" class="form-control" value="{{ old('place_of_supply', $invoice->place_of_supply) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('State Code') }}</label>
                                <input type="text" name="place_of_supply_code" class="form-control" value="{{ old('place_of_supply_code', $invoice->place_of_supply_code) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('Terms') }}</label>
                                <input type="text" name="terms" class="form-control" value="{{ old('terms', $invoice->terms) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Ship To Name') }}</label>
                                <input type="text" name="ship_to_name" class="form-control" value="{{ old('ship_to_name', $invoice->ship_to_name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Ship To Address') }}</label>
                                <textarea name="ship_to_address" class="form-control" rows="2">{{ old('ship_to_address', $invoice->ship_to_address) }}</textarea>
                            </div>
                            <div class="col-md-3 col-lg-2">
                                <label class="form-label">{{ __('Billing Year') }}</label>
                                <input type="number" name="billing_year" class="form-control" value="{{ old('billing_year', $invoice->billing_year) }}" required>
                            </div>
                            <div class="col-md-3 col-lg-2">
                                <label class="form-label">{{ __('Billing Month') }}</label>
                                <input type="number" name="billing_month" min="1" max="12" class="form-control" value="{{ old('billing_month', $invoice->billing_month) }}" required>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label">{{ __('Active Employees') }}</label>
                                <input type="number" name="active_employees_count" class="form-control" min="0" value="{{ old('active_employees_count', $invoice->active_employees_count) }}" required>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label">{{ __('Price per Employee') }}</label>
                                <input type="number" step="0.01" name="price_per_employee" class="form-control" value="{{ old('price_per_employee', $invoice->price_per_employee) }}" required>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label">{{ __('Active Users') }}</label>
                                <input type="number" name="active_users_count" class="form-control" min="0" value="{{ old('active_users_count', $invoice->active_users_count) }}" required>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label">{{ __('Price per User') }}</label>
                                <input type="number" step="0.01" name="price_per_user" class="form-control" value="{{ old('price_per_user', $invoice->price_per_user) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Invoice Date') }}</label>
                                <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', optional($invoice->invoice_date)->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Due Date') }}</label>
                                <input type="date" name="due_date" class="form-control" value="{{ old('due_date', optional($invoice->due_date)->format('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Employees Line Description') }}</label>
                                <input type="text" name="employee_description" class="form-control"
                                    value="{{ old('employee_description', optional($invoice->items->get(0))->description) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Users Line Description') }}</label>
                                <input type="text" name="user_description" class="form-control"
                                    value="{{ old('user_description', optional($invoice->items->get(1))->description) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Notes') }}</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('Payment Instructions') }}</label>
                                <textarea name="payment_instructions" class="form-control" rows="3">{{ old('payment_instructions', $invoice->payment_instructions) }}</textarea>
                            </div>
                            <div class="col-12 d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                                <a href="{{ route('crm.invoices.show', $invoice->id) }}" class="btn btn-light">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
