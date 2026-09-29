@extends('layouts.admin')

@section('page-title')
    {{ __('Company Invoices') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Company Invoices') }}</li>
@endsection

@section('action-button')
    <div class="crm-invoice-actions d-flex flex-wrap gap-1 justify-content-end">
        <a href="{{ route('crm.invoices.settings') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-settings"></i> {{ __('Settings') }}
        </a>
        <a href="{{ route('crm.invoices.create') }}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i> {{ __('Create Invoice') }}
        </a>
    </div>
@endsection

@section('content')
<div class="crm-invoice-module">
    <div class="card mb-3">
        <div class="card-body">
            <form method="get" class="row g-3 align-items-end">
                <div class="col-lg-3 col-md-6">
                    <label class="form-label">{{ __('Company') }}</label>
                    <select name="company_id" class="form-control">
                        <option value="">{{ __('All') }}</option>
                        @foreach($companies as $c)
                            <option value="{{ $c->id }}" @selected(request('company_id') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label class="form-label">{{ __('Status') }}</label>
                    <select name="status" class="form-control">
                        <option value="">{{ __('All') }}</option>
                        @foreach(['draft','generated','sent','paid','cancelled'] as $st)
                            <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 col-md-3">
                    <label class="form-label">{{ __('Year') }}</label>
                    <input type="number" name="year" class="form-control" value="{{ request('year') }}" placeholder="{{ date('Y') }}">
                </div>
                <div class="col-lg-2 col-md-3">
                    <label class="form-label">{{ __('Month') }}</label>
                    <input type="number" name="month" min="1" max="12" class="form-control" value="{{ request('month') }}" placeholder="1-12">
                </div>
                <div class="col-lg-3 col-md-6">
                    <button class="btn btn-primary" type="submit"><i class="ti ti-filter"></i> {{ __('Filter') }}</button>
                    <a href="{{ route('crm.invoices.index') }}" class="btn btn-light">{{ __('Reset') }}</a>
                </div>
            </form>
            <div class="mt-3 text-muted small">
                {{ __('Default price') }}: <strong>₹{{ number_format($settings->price_per_employee, 2) }}</strong>
                / {{ __('employee') }} / {{ __('month') }}
                · {{ __('Showing') }} {{ $invoices->total() }} {{ __('invoice(s)') }}
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <table class="table table-hover crm-invoice-list mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('Invoice #') }}</th>
                            <th>{{ __('Company') }}</th>
                            <th>{{ __('Period') }}</th>
                            <th class="text-end">{{ __('Emp.') }}</th>
                            <th class="text-end">{{ __('Total') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $inv)
                            <tr>
                                <td>
                                    <a href="{{ route('crm.invoices.show', $inv->id) }}" class="fw-semibold text-decoration-none">
                                        {{ $inv->invoice_number }}
                                    </a>
                                    <div class="text-muted small">{{ ucfirst($inv->source) }}</div>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ \Illuminate\Support\Str::limit($inv->company_name, 28) }}</div>
                                    @if($inv->company_email)
                                        <div class="text-muted small">{{ \Illuminate\Support\Str::limit($inv->company_email, 32) }}</div>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <div>{{ $inv->periodLabel() }}</div>
                                    <div class="text-muted small">{{ __('Due') }} {{ optional($inv->due_date)->format('d M Y') }}</div>
                                </td>
                                <td class="text-end">{{ number_format($inv->active_employees_count) }}</td>
                                <td class="text-end text-nowrap fw-semibold">₹{{ number_format($inv->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge {{ $inv->statusBadgeClass() }}">{{ ucfirst($inv->status) }}</span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('crm.invoices.show', $inv->id) }}" class="btn btn-outline-primary" title="{{ __('View') }}">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        @if($inv->isEditable())
                                            <a href="{{ route('crm.invoices.edit', $inv->id) }}" class="btn btn-outline-warning" title="{{ __('Edit') }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('crm.invoices.pdf', $inv->id) }}" class="btn btn-outline-secondary" title="{{ __('PDF') }}">
                                            <i class="ti ti-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <div class="mb-2"><i class="ti ti-file-invoice" style="font-size:1.6rem"></i></div>
                                    {{ __('No invoices found.') }}
                                    <div class="mt-2">
                                        <a href="{{ route('crm.invoices.create') }}" class="btn btn-sm btn-primary">{{ __('Create Invoice') }}</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
