@extends('layouts.admin')

@section('page-title')
    {{ __('Form 16') }} — {{ $record->employee_name }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('form16.index') }}">{{ __('Form 16') }}</a></li>
    <li class="breadcrumb-item">{{ $record->employee_name }}</li>
@endsection

@section('action-button')
    <div class="d-flex flex-wrap gap-1 justify-content-end">
        <a href="{{ route('form16.refresh', $record->id) }}" class="btn btn-sm btn-outline-primary">
            <i class="ti ti-refresh"></i> {{ __('Refresh Data') }}
        </a>
        <a href="{{ route('form16.preview', $record->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
            <i class="ti ti-eye"></i> {{ __('Preview') }}
        </a>
        @if ($record->hasFinalPdf() || $record->hasPartBPdf())
            <a href="{{ route('form16.download', ['id' => $record->id, 'which' => 'final']) }}"
                class="btn btn-sm btn-primary">
                <i class="ti ti-download"></i> {{ __('Download') }}
            </a>
        @endif
    </div>
@endsection

@section('content')
@php
    $hasMissing = false;
    $hasWarnings = false;
    foreach (($missingInfo ?? []) as $err) {
        if (($err['level'] ?? 'error') === 'warning') {
            $hasWarnings = true;
        } else {
            $hasMissing = true;
        }
    }
    $partBMonths = $partB['payroll_aggregation']['months'] ?? [];
    $payrollTotals = $partB['payroll_aggregation']['totals'] ?? [];
    $dataSource = $payrollTotals['data_source'] ?? 'none';
    $periodFrom = $record->employment_period_from ? $record->employment_period_from->format('d M Y') : '—';
    $periodTo = $record->employment_period_to ? $record->employment_period_to->format('d M Y') : '—';
    $statusClass = [
        'data_pending' => 'bg-warning text-dark',
        'draft' => 'bg-secondary',
        'verified' => 'bg-info',
        'part_a_uploaded' => 'bg-info',
        'part_b_generated' => 'bg-primary',
        'generated' => 'bg-primary',
        'issued' => 'bg-success',
        'sent' => 'bg-success',
        'failed' => 'bg-danger',
    ][$record->status] ?? 'bg-secondary';
@endphp

<div class="form16-module">
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="card h-100 mb-0">
                <div class="card-body py-3">
                    <div class="text-muted small">{{ __('Status') }}</div>
                    <div class="mt-1">
                        <span class="badge {{ $statusClass }}">{{ $record->status_label }}</span>
                        <span class="badge bg-light text-dark">v{{ $record->version }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 mb-0">
                <div class="card-body py-3">
                    <div class="text-muted small">{{ __('Financial Year') }}</div>
                    <div class="fw-semibold mt-1">{{ $record->financial_year }}</div>
                    <div class="text-muted small">AY {{ $record->assessment_year }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 mb-0">
                <div class="card-body py-3">
                    <div class="text-muted small">{{ __('Employment Period') }}</div>
                    <div class="fw-semibold mt-1" style="font-size: 0.9rem;">{{ $periodFrom }} – {{ $periodTo }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 mb-0">
                <div class="card-body py-3">
                    <div class="text-muted small">{{ __('TDS (Payroll)') }}</div>
                    <div class="fw-bold mt-1 text-primary">₹{{ number_format((float) $record->tds_deducted, 2) }}</div>
                    <div class="text-muted small">{{ __('Gross') }}: ₹{{ number_format((float) $record->gross_salary, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    @if ($hasMissing)
        <div class="alert alert-warning border-0 shadow-sm mb-3">
            <div class="d-flex align-items-start">
                <i class="ti ti-alert-triangle me-2 mt-1 fs-5"></i>
                <div class="flex-grow-1">
                    <strong>{{ __('Missing Information') }}</strong>
                    <p class="mb-2 small">{{ __('Complete the items below before verifying / issuing Form 16.') }}</p>
                    <ul class="mb-0 ps-3">
                        @foreach ($missingInfo as $err)
                            @if (($err['level'] ?? 'error') === 'warning')
                                @continue
                            @endif
                            <li class="mb-1">
                                {{ $err['message'] ?? '' }}
                                @if (!empty($err['fix']))
                                    — <a href="{{ $err['fix'] }}" class="fw-semibold">{{ __('Update') }}</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if ($hasWarnings)
        <div class="alert alert-info border-0 mb-3">
            <strong>{{ __('Salary data notice') }}</strong>
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($missingInfo as $err)
                    @if (($err['level'] ?? 'error') !== 'warning')
                        @continue
                    @endif
                    <li class="mb-1">
                        {{ $err['message'] ?? '' }}
                        @if (!empty($err['fix']))
                            — <a href="{{ $err['fix'] }}">{{ __('Open Payslips') }}</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="alert alert-info border-0 mb-3" style="background: var(--saas-info-bg, #ecfeff);">
        <strong>{{ __('Important') }}:</strong>
        {{ __('Part A must be the official TRACES PDF. This HRMS prepares employer Part B / Annexure only — it does not create Income Tax Department or TRACES certificates.') }}
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card mb-0 h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Employee') }}</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-3" style="width:40%">{{ __('Name') }}</th>
                                <td>{{ $record->employee_name }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3">{{ __('Employee ID') }}</th>
                                <td>{{ $record->employee_code }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3">{{ __('PAN') }}</th>
                                <td>
                                    @if ($record->employee_pan)
                                        {{ $record->employee_pan }}
                                    @else
                                        <span class="text-danger">{{ __('Missing') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-3">{{ __('Email') }}</th>
                                <td>{{ $record->employee_email ?: '—' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3">{{ __('Tax Regime') }}</th>
                                <td>{{ ucfirst($record->tax_regime ?: 'unknown') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-0 h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Employer') }}</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-3" style="width:40%">{{ __('Company') }}</th>
                                <td>{{ $record->employer_name }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3">{{ __('PAN') }}</th>
                                <td>
                                    @if ($record->employer_pan)
                                        {{ $record->employer_pan }}
                                    @else
                                        <span class="text-danger">{{ __('Missing') }}</span>
                                        <a href="{{ route('company.setting') }}" class="small ms-1">{{ __('Update') }}</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-3">{{ __('TAN') }}</th>
                                <td>
                                    @if ($record->employer_tan)
                                        {{ $record->employer_tan }}
                                    @else
                                        <span class="text-danger">{{ __('Missing') }}</span>
                                        <a href="{{ route('company.setting') }}" class="small ms-1">{{ __('Update') }}</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-3">{{ __('Address') }}</th>
                                <td>{{ $record->employer_address ?: '—' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <div class="card mb-0 h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Financial Summary') }}</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th class="ps-3">{{ __('Data source') }}</th>
                                <td class="text-end pe-3">
                                    @if ($dataSource === 'payslips')
                                        <span class="badge bg-success">{{ __('Payslips') }}</span>
                                    @elseif ($dataSource === 'salary_structure_estimate')
                                        <span class="badge bg-warning text-dark">{{ __('Salary structure estimate') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('None') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-3">{{ __('Months included') }}</th>
                                <td class="text-end pe-3">{{ (int) ($payrollTotals['months_present'] ?? 0) }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3">{{ __('Gross salary') }}</th>
                                <td class="text-end pe-3">₹{{ number_format((float) $record->gross_salary, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3">{{ __('Exempt allowances') }}</th>
                                <td class="text-end pe-3">₹{{ number_format((float) $record->exempt_allowances, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3">{{ __('Net salary') }}</th>
                                <td class="text-end pe-3">₹{{ number_format((float) $record->net_salary, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3">{{ __('Income chargeable (Salaries)') }}</th>
                                <td class="text-end pe-3">₹{{ number_format((float) $record->income_chargeable_salaries, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="ps-3">{{ __('TDS deducted (payroll)') }}</th>
                                <td class="text-end pe-3 fw-semibold">₹{{ number_format((float) $record->tds_deducted, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-0 h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Part A — TRACES Upload') }}</h5>
                    @if ($record->hasPartA())
                        <span class="badge bg-success">{{ __('Uploaded') }}</span>
                    @else
                        <span class="badge bg-light text-dark">{{ __('Not uploaded') }}</span>
                    @endif
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        {{ __('Upload the official Part A PDF from TRACES after Form 24Q. Do not upload a fabricated certificate.') }}
                    </p>

                    @if ($record->hasPartA())
                        <div class="border rounded p-3 mb-3 bg-light">
                            <div class="fw-semibold">{{ $record->part_a_original_name }}</div>
                            <div class="small text-muted mb-2">
                                {{ __('Uploaded') }}:
                                {{ $record->part_a_uploaded_at ? $record->part_a_uploaded_at->format('d M Y H:i') : '—' }}
                            </div>
                            <a href="{{ route('form16.download', ['id' => $record->id, 'which' => 'part_a']) }}"
                                class="btn btn-sm btn-outline-primary me-1">
                                <i class="ti ti-download"></i> {{ __('Download') }}
                            </a>
                            @if (!in_array($record->status, ['issued', 'sent'], true))
                                <form action="{{ route('form16.part-a.delete', $record->id) }}" method="post" class="d-inline"
                                    onsubmit="return confirm('{{ __('Delete Part A upload?') }}')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('Delete') }}</button>
                                </form>
                            @endif
                        </div>
                    @endif

                    <form action="{{ route('form16.part-a.upload', $record->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label small">{{ __('Part A PDF') }}</label>
                            <input type="file" name="part_a_pdf" accept="application/pdf,.pdf" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">{{ __('TRACES certificate number (optional)') }}</label>
                            <input type="text" name="part_a_certificate_number" class="form-control"
                                placeholder="{{ __('Copy from TRACES Part A') }}"
                                value="{{ old('part_a_certificate_number', $record->part_a_certificate_number) }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="ti ti-upload"></i> {{ __('Upload Part A') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (!empty($partBMonths))
        <div class="card mt-3 mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Month-wise Salary Details') }}</h5>
                <small class="text-muted">
                    @if ($dataSource === 'payslips')
                        {{ __('From generated payslips') }}
                    @else
                        {{ __('Estimated from employee salary structure (no payslips yet)') }}
                    @endif
                </small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-3">{{ __('Month') }}</th>
                                <th class="text-end">{{ __('Basic') }}</th>
                                <th class="text-end">{{ __('Gross') }}</th>
                                <th class="text-end">{{ __('Net') }}</th>
                                <th class="text-end">{{ __('TDS') }}</th>
                                <th class="text-end pe-3">{{ __('Source') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($partBMonths as $m)
                                <tr>
                                    <td class="ps-3">{{ $m['salary_month'] ?? '' }}</td>
                                    <td class="text-end">₹{{ number_format((float) ($m['basic_salary'] ?? 0), 2) }}</td>
                                    <td class="text-end">₹{{ number_format((float) ($m['gross_earning'] ?? 0), 2) }}</td>
                                    <td class="text-end">₹{{ number_format((float) ($m['net_payable'] ?? 0), 2) }}</td>
                                    <td class="text-end">₹{{ number_format((float) ($m['tds'] ?? 0), 2) }}</td>
                                    <td class="text-end pe-3">
                                        <span class="badge {{ ($m['source'] ?? '') === 'payslip' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ ($m['source'] ?? '') === 'payslip' ? __('Payslip') : __('Estimate') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-semibold">
                                <td class="ps-3">{{ __('Total') }}</td>
                                <td class="text-end">₹{{ number_format((float) ($payrollTotals['basic_salary'] ?? 0), 2) }}</td>
                                <td class="text-end">₹{{ number_format((float) ($payrollTotals['gross_earning'] ?? 0), 2) }}</td>
                                <td class="text-end">₹{{ number_format((float) ($payrollTotals['net_payable'] ?? 0), 2) }}</td>
                                <td class="text-end">₹{{ number_format((float) ($payrollTotals['tds'] ?? 0), 2) }}</td>
                                <td class="pe-3"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="card mt-3 mb-3">
        <div class="card-header">
            <h5 class="mb-0">{{ __('Part B — Employer Annexure') }}</h5>
        </div>
        <div class="card-body">
            <p class="small text-muted mb-3">
                {{ __('Generated by employer from HRMS payslips for the selected Financial Year. Not a TRACES document.') }}
            </p>
            <div class="d-flex flex-wrap align-items-center" style="gap: 0.5rem;">
                <form action="{{ route('form16.generate', $record->id) }}" method="post" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-file-certificate"></i>
                        {{ $record->hasPartBPdf() ? __('Regenerate Part B PDF') : __('Generate Part B PDF') }}
                    </button>
                </form>

                @if ($record->hasPartBPdf())
                    <a href="{{ route('form16.download', ['id' => $record->id, 'which' => 'part_b']) }}"
                        class="btn btn-outline-primary">
                        <i class="ti ti-download"></i> {{ __('Download Part B') }}
                    </a>
                    <a href="{{ route('form16.download', ['id' => $record->id, 'which' => 'final']) }}"
                        class="btn btn-outline-primary">
                        <i class="ti ti-download"></i> {{ __('Download Package') }}
                    </a>
                    <form action="{{ route('form16.verify', $record->id) }}" method="post" class="d-inline">@csrf
                        <button class="btn btn-success" type="submit" @if($hasMissing) disabled @endif>
                            <i class="ti ti-check"></i> {{ __('Verify') }}
                        </button>
                    </form>
                    <form action="{{ route('form16.issue', $record->id) }}" method="post" class="d-inline">@csrf
                        <button class="btn btn-secondary" type="submit" @if($hasMissing) disabled @endif>{{ __('Mark Issued') }}</button>
                    </form>
                    <form action="{{ route('form16.send', $record->id) }}" method="post" class="d-inline"
                        onsubmit="return confirm('{{ __('Send Form 16 to employee email? Sensitive details stay in the PDF only.') }}')">
                        @csrf
                        <button class="btn btn-warning" type="submit" @if($hasMissing) disabled @endif>
                            <i class="ti ti-mail"></i> {{ __('Send Email') }}
                        </button>
                    </form>
                @endif
            </div>
            @if ($hasMissing)
                <p class="small text-warning mb-0 mt-2">
                    {{ __('You can generate a draft Part B PDF now. Verify / Issue / Email stay locked until missing information is fixed.') }}
                </p>
            @elseif (!$record->hasPartBPdf())
                <p class="small text-muted mb-0 mt-2">{{ __('Click Generate Part B PDF to create the downloadable document.') }}</p>
            @endif
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card mb-0 h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Email History') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">{{ __('Email') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Sent At') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($emailLogs as $log)
                                    <tr>
                                        <td class="ps-3">{{ $log->email }}</td>
                                        <td>
                                            <span class="badge {{ $log->status === 'sent' ? 'bg-success' : ($log->status === 'failed' ? 'bg-danger' : 'bg-secondary') }}">
                                                {{ ucfirst($log->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $log->sent_at ? $log->sent_at->format('d M Y H:i') : '—' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">{{ __('No emails yet') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-0 h-100">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Version History') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-3">
                        @foreach ($versions as $v)
                            <li class="mb-2 d-flex align-items-center flex-wrap" style="gap: 0.35rem;">
                                <a href="{{ route('form16.show', $v->id) }}" class="fw-semibold">v{{ $v->version }}</a>
                                <span class="text-muted">— {{ \App\Models\Form16Record::STATUSES[$v->status] ?? $v->status }}</span>
                                @if ($v->is_latest)
                                    <span class="badge bg-primary">{{ __('Latest') }}</span>
                                @endif
                                @if ($v->regeneration_reason)
                                    <small class="text-muted w-100">({{ $v->regeneration_reason }})</small>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    <form action="{{ route('form16.regenerate', $record->id) }}" method="post">
                        @csrf
                        <label class="form-label small">{{ __('Create new version') }}</label>
                        <div class="input-group">
                            <input type="text" name="reason" class="form-control" required
                                placeholder="{{ __('Reason for regeneration') }}">
                            <button class="btn btn-outline-danger" type="submit">{{ __('Regenerate') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
