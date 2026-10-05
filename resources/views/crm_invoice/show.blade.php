@extends('layouts.admin')

@section('page-title')
    {{ __('Invoice') }} {{ $invoice->invoice_number }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('crm.invoices.index') }}">{{ __('Company Invoices') }}</a></li>
    <li class="breadcrumb-item">{{ $invoice->invoice_number }}</li>
@endsection

@section('action-button')
    <div class="crm-invoice-actions d-flex flex-wrap gap-1 justify-content-end">
        <a href="{{ route('crm.invoices.pdf', $invoice->id) }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-download"></i> {{ __('PDF') }}
        </a>
        <a href="{{ route('crm.invoices.regenerate-pdf', $invoice->id) }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-refresh"></i> {{ __('Regen PDF') }}
        </a>
        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#crmSendInvoiceModal">
            <i class="ti ti-mail"></i> {{ __('Send Invoice') }}
        </button>
        @if($invoice->isEditable())
            <a href="{{ route('crm.invoices.edit', $invoice->id) }}" class="btn btn-sm btn-warning">
                <i class="ti ti-pencil"></i> {{ __('Edit') }}
            </a>
            <form action="{{ route('crm.invoices.mark-paid', $invoice->id) }}" method="post" class="d-inline"
                  onsubmit="return confirm('{{ __('Mark as Payment Complete? Invoice will become locked.') }}');">
                @csrf
                <button type="submit" class="btn btn-sm btn-success">
                    <i class="ti ti-lock"></i> {{ __('Payment Complete') }}
                </button>
            </form>
        @endif
    </div>
@endsection

@section('content')
<div class="crm-invoice-module">
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="card crm-stat-card h-100 mb-0">
                <div class="card-body py-3">
                    <div class="text-muted small">{{ __('Status') }}</div>
                    <div class="mt-1">
                        <span class="badge {{ $invoice->statusBadgeClass() }}">{{ ucfirst($invoice->status) }}</span>
                        @if($invoice->isLocked())
                            <span class="badge bg-dark ms-1">{{ __('Locked') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card crm-stat-card h-100 mb-0">
                <div class="card-body py-3">
                    <div class="text-muted small">{{ __('Period') }}</div>
                    <div class="fw-semibold mt-1">{{ $invoice->periodLabel() }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card crm-stat-card h-100 mb-0">
                <div class="card-body py-3">
                    <div class="text-muted small">{{ __('Employees') }}</div>
                    <div class="fw-semibold mt-1">{{ number_format($invoice->active_employees_count) }} × ₹{{ number_format($invoice->price_per_employee, 2) }}</div>
                    <div class="text-muted small mt-2">{{ __('Users') }}</div>
                    @if($invoice->usersNotBilled())
                        <div class="fw-semibold">
                            0
                            <span class="badge bg-warning text-dark ms-1">{{ __('Not billed') }}</span>
                        </div>
                    @else
                        <div class="fw-semibold">{{ number_format((int) $invoice->active_users_count) }} × ₹{{ number_format((float) $invoice->price_per_user, 2) }}</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card crm-stat-card h-100 mb-0">
                <div class="card-body py-3">
                    <div class="text-muted small">{{ __('Total') }}</div>
                    <div class="fw-bold mt-1 text-primary">₹{{ number_format($invoice->total_amount, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0">{{ $invoice->invoice_number }}</h5>
            <div class="text-muted small">
                {{ __('Source') }}: <strong>{{ ucfirst($invoice->source) }}</strong>
                @if($invoice->paid_at)
                    · <span class="text-success">{{ __('Paid At') }}: {{ $invoice->paid_at->format('d M Y H:i') }}</span>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4 mb-4">
                <div class="col-lg-4">
                    <h6 class="text-muted text-uppercase small mb-2">{{ __('Bill To') }}</h6>
                    <div class="fw-semibold fs-6">{{ $invoice->company_name }}</div>
                    @if($invoice->company_email)
                        <div><a href="mailto:{{ $invoice->company_email }}">{{ $invoice->company_email }}</a></div>
                    @endif
                    @if($invoice->company_phone)
                        <div>{{ $invoice->company_phone }}</div>
                    @endif
                    @if($invoice->company_address)
                        <div class="text-muted mt-1" style="white-space:pre-line;">{{ $invoice->company_address }}</div>
                    @endif
                    @if($invoice->company_gstin)
                        <div class="mt-1"><strong>{{ __('GSTIN') }}</strong> {{ $invoice->company_gstin }}</div>
                    @endif
                </div>
                <div class="col-lg-4">
                    <h6 class="text-muted text-uppercase small mb-2">{{ __('Ship To') }}</h6>
                    <div class="fw-semibold">{{ $invoice->ship_to_name ?: $invoice->company_name }}</div>
                    <div class="text-muted mt-1" style="white-space:pre-line;">{{ $invoice->ship_to_address ?: $invoice->company_address }}</div>
                </div>
                <div class="col-lg-4">
                    <h6 class="text-muted text-uppercase small mb-2">{{ __('TAX Invoice Details') }}</h6>
                    <div class="crm-detail-grid">
                        <div class="crm-detail-row">
                            <span>{{ __('Invoice Date') }}</span>
                            <strong>{{ optional($invoice->invoice_date)->format('d/m/Y') }}</strong>
                        </div>
                        <div class="crm-detail-row">
                            <span>{{ __('Due Date') }}</span>
                            <strong>{{ optional($invoice->due_date)->format('d/m/Y') }}</strong>
                        </div>
                        <div class="crm-detail-row">
                            <span>{{ __('Terms') }}</span>
                            <strong>{{ $invoice->terms ?: 'Due on Receipt' }}</strong>
                        </div>
                        <div class="crm-detail-row">
                            <span>{{ __('Place Of Supply') }}</span>
                            <strong>
                                {{ $invoice->place_of_supply ?: '-' }}
                                @if($invoice->place_of_supply_code)
                                    ({{ $invoice->place_of_supply_code }})
                                @endif
                            </strong>
                        </div>
                        <div class="crm-detail-row">
                            <span>{{ __('Seller GSTIN') }}</span>
                            <strong>{{ $invoice->seller_gstin ?: '-' }}</strong>
                        </div>
                        <div class="crm-detail-row">
                            <span>{{ __('Emails Sent') }}</span>
                            <strong>{{ $invoice->emailLogs->count() }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered crm-invoice-lines mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:4%">#</th>
                            <th class="crm-col-desc" style="width:28%">{{ __('Item & Description') }}</th>
                            <th class="text-center">{{ __('HSN/SAC') }}</th>
                            <th class="text-end">{{ __('Qty') }}</th>
                            <th class="text-end">{{ __('Rate') }}</th>
                            <th class="text-end">{{ __('CGST %') }}</th>
                            <th class="text-end">{{ __('CGST Amt') }}</th>
                            <th class="text-end">{{ __('SGST %') }}</th>
                            <th class="text-end">{{ __('SGST Amt') }}</th>
                            <th class="text-end">{{ __('Amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="crm-col-desc">{{ $item->description }}</td>
                                <td class="text-center">{{ $item->hsn_sac ?: '-' }}</td>
                                <td class="text-end">{{ number_format((float) $item->quantity, 2) }} {{ $item->unit ?: 'Nos' }}</td>
                                <td class="text-end text-nowrap">₹{{ number_format($item->unit_price, 2) }}</td>
                                <td class="text-end">{{ number_format((float) ($item->cgst_percent ?? 0), 2) }}%</td>
                                <td class="text-end text-nowrap">₹{{ number_format((float) ($item->cgst_amount ?? 0), 2) }}</td>
                                <td class="text-end">{{ number_format((float) ($item->sgst_percent ?? 0), 2) }}%</td>
                                <td class="text-end text-nowrap">₹{{ number_format((float) ($item->sgst_amount ?? 0), 2) }}</td>
                                <td class="text-end text-nowrap">₹{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="9" class="text-end">{{ __('Sub Total') }}</th>
                            <th class="text-end text-nowrap">₹{{ number_format($invoice->subtotal, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="9" class="text-end">{{ __('CGST') }} ({{ number_format((float) ($invoice->cgst_percent ?? 0), 2) }}%)</th>
                            <th class="text-end text-nowrap">₹{{ number_format((float) ($invoice->cgst_amount ?? 0), 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="9" class="text-end">{{ __('SGST') }} ({{ number_format((float) ($invoice->sgst_percent ?? 0), 2) }}%)</th>
                            <th class="text-end text-nowrap">₹{{ number_format((float) ($invoice->sgst_amount ?? 0), 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="9" class="text-end">{{ __('Total') }}</th>
                            <th class="text-end text-nowrap">₹{{ number_format($invoice->total_amount, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if($invoice->total_in_words)
                <div class="crm-note-box mt-3">
                    <div class="fw-semibold mb-1">{{ __('Total In Words') }}</div>
                    <div>{{ $invoice->total_in_words }}</div>
                </div>
            @endif

            @if($invoice->notes || $invoice->payment_instructions)
                <div class="row g-3 mt-3">
                    @if($invoice->notes)
                        <div class="col-lg-6">
                            <div class="crm-note-box">
                                <div class="fw-semibold mb-1">{{ __('Notes') }}</div>
                                <div class="text-muted">{{ $invoice->notes }}</div>
                            </div>
                        </div>
                    @endif
                    @if($invoice->payment_instructions)
                        <div class="col-lg-6">
                            <div class="crm-note-box">
                                <div class="fw-semibold mb-1">{{ __('Payment Instructions') }}</div>
                                <div class="text-muted">{{ $invoice->payment_instructions }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h5 class="mb-0">{{ __('Email Log') }}</h5>
            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#crmSendInvoiceModal">
                <i class="ti ti-mail"></i> {{ __('Send Again') }}
            </button>
        </div>
        <div class="card-body">
            @forelse($invoice->emailLogs as $log)
                <div class="crm-email-log-item {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="badge {{ $log->statusBadgeClass() }}">{{ ucfirst($log->status) }}</span>
                            <span class="small text-muted text-nowrap">
                                <i class="ti ti-clock"></i>
                                {{ optional($log->sent_at ?: $log->created_at)->format('d M Y H:i') }}
                            </span>
                        </div>
                        <div class="small text-muted">
                            {{ __('Sent By') }}: <strong>{{ optional($log->sentBy)->name ?: '—' }}</strong>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-lg-4">
                            <div class="text-muted small text-uppercase mb-1">{{ __('Recipients') }}</div>
                            @foreach(array_filter(array_map('trim', explode(',', (string) $log->recipients))) as $email)
                                <div class="fw-medium text-break">{{ $email }}</div>
                            @endforeach
                        </div>
                        <div class="col-lg-8">
                            <div class="text-muted small text-uppercase mb-1">{{ __('Subject') }}</div>
                            <div class="text-break">{{ $log->subject ?: '—' }}</div>
                        </div>
                        @if($log->error_message)
                            <div class="col-12">
                                <div class="alert alert-danger py-2 px-3 mb-0 small">
                                    <strong>{{ __('Error') }}:</strong> {{ $log->error_message }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    <div class="mb-1"><i class="ti ti-mail-off" style="font-size:1.4rem"></i></div>
                    {{ __('No emails sent yet. Use “Send Invoice” to email this PDF.') }}
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Send Invoice Modal --}}
<div class="modal fade" id="crmSendInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('crm.invoices.send', $invoice->id) }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Send Invoice') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-light border small mb-3">
                    {{ __('Invoice') }}: <strong>{{ $invoice->invoice_number }}</strong>
                    · {{ __('Total') }}: <strong>₹{{ number_format($invoice->total_amount, 2) }}</strong>
                </div>
                <label class="form-label">{{ __('Recipient emails') }}</label>
                <textarea name="recipients" class="form-control" rows="3" required placeholder="email1@example.com, email2@example.com">{{ $invoice->company_email }}</textarea>
                <small class="text-muted">{{ __('Separate multiple emails with commas. PDF will be attached.') }}</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-send"></i> {{ __('Send') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
