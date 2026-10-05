@extends('layouts.admin')

@section('page-title')
    {{ __('Form 16') }} — {{ $employee->name }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employee.index') }}">{{ __('Employee') }}</a></li>
    <li class="breadcrumb-item">{{ __('Form 16') }}</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Select Financial Year') }}</h5>
                    <p class="mb-0 text-muted">{{ $employee->name }} · {{ $employee->employee_id }}</p>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        {{ __('Form 16 Part A must be the official PDF from TRACES. This HRMS prepares the employer Part B / Annexure from payroll and does not claim Income Tax Department or TRACES authentication.') }}
                    </div>
                    <div class="mb-3 p-3 border rounded bg-light">
                        <div><strong>{{ $employee->name }}</strong> ({{ $employee->employee_id }})</div>
                        <div class="small text-muted">
                            {{ __('Joining date') }}:
                            {{ $employee->company_doj ? \Carbon\Carbon::parse($employee->company_doj)->format('d M Y') : __('Not set') }}
                            @if (!empty($employee->date_of_exit) && $employee->date_of_exit !== '0000-00-00')
                                · {{ __('Exit') }}: {{ \Carbon\Carbon::parse($employee->date_of_exit)->format('d M Y') }}
                            @endif
                        </div>
                        <div class="small text-muted">
                            {{ __('Monthly salary (master)') }}: ₹{{ number_format((float) ($employee->salary ?? 0), 2) }}
                        </div>
                        <div class="small text-warning mb-0 mt-1">
                            {{ __('Select a Financial Year that overlaps the employee joining/exit period. Salary is taken from payslips if available, otherwise estimated from salary structure.') }}
                        </div>
                    </div>
                    {{ Form::open(['route' => 'form16.init', 'method' => 'post']) }}
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <div class="form-group mb-3">
                        <label class="form-label">{{ __('Financial Year') }}</label>
                        <select name="financial_year" class="form-control" required id="fy_select">
                            @foreach ($years as $y)
                                <option value="{{ $y['financial_year'] }}" data-ay="{{ $y['assessment_year'] }}">
                                    FY {{ $y['financial_year'] }} → AY {{ $y['assessment_year'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">{{ __('Assessment Year') }}</label>
                        <input type="text" class="form-control" id="ay_display" readonly
                            value="{{ $years[0]['assessment_year'] ?? '' }}">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label">{{ __('Tax Regime (for this FY)') }}</label>
                        <select name="tax_regime" class="form-control">
                            <option value="unknown">{{ __('Not specified / confirm later') }}</option>
                            <option value="old">{{ __('Old Tax Regime') }}</option>
                            <option value="new">{{ __('New Tax Regime') }}</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('Continue') }}</button>
                    <a href="{{ route('employee.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        document.getElementById('fy_select')?.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            document.getElementById('ay_display').value = opt.getAttribute('data-ay') || '';
        });
    </script>
@endpush
