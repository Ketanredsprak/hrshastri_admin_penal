@extends('layouts.admin')

@section('page-title')
    {{ __('Form 16') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Form 16') }}</li>
@endsection

@section('content')
<div class="form16-module">
    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h5 class="mb-0">{{ __('Form 16 Records') }}</h5>
                <small class="text-muted">{{ __('Part A = TRACES upload · Part B = Employer annexure from payroll') }}</small>
            </div>
            <a href="{{ route('employee.index') }}" class="btn btn-sm btn-primary">
                <i class="ti ti-user"></i> {{ __('Open from Employee List') }}
            </a>
        </div>
        <div class="card-body">
            <form method="get" class="row g-2 mb-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small mb-1">{{ __('Search') }}</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="{{ __('Employee / ID / PAN') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">{{ __('Financial Year') }}</label>
                    <select name="financial_year" class="form-control">
                        <option value="">{{ __('All FY') }}</option>
                        @foreach ($years as $y)
                            <option value="{{ $y['financial_year'] }}"
                                {{ request('financial_year') == $y['financial_year'] ? 'selected' : '' }}>
                                {{ $y['financial_year'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">{{ __('Status') }}</label>
                    <select name="status" class="form-control">
                        <option value="">{{ __('All Status') }}</option>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ __($label) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">{{ __('Employee') }}</label>
                    <select name="employee_id" class="form-control">
                        <option value="">{{ __('All Employees') }}</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}"
                                {{ (string) request('employee_id') === (string) $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }} ({{ $emp->employee_id }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">{{ __('Filter') }}</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('Employee') }}</th>
                            <th>{{ __('Employee ID') }}</th>
                            <th>{{ __('FY') }}</th>
                            <th>{{ __('AY') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Part A') }}</th>
                            <th>{{ __('Part B') }}</th>
                            <th>{{ __('Generated') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $row)
                            <tr>
                                <td class="fw-semibold">{{ $row->employee_name }}</td>
                                <td>{{ $row->employee_code }}</td>
                                <td>{{ $row->financial_year }}</td>
                                <td>{{ $row->assessment_year }}</td>
                                <td><span class="badge bg-secondary">{{ $row->status_label }}</span></td>
                                <td>{{ $row->hasPartA() ? __('Uploaded') : '—' }}</td>
                                <td>{{ $row->hasPartBPdf() ? __('Yes') : '—' }}</td>
                                <td>{{ $row->generated_at ? $row->generated_at->format('d M Y') : '—' }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $row->email_status)) }}</td>
                                <td>
                                    <a href="{{ route('form16.show', $row->id) }}" class="btn btn-sm btn-primary">
                                        {{ __('Open') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    {{ __('No Form 16 records yet. Go to Employee List and click Form 16.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $records->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
