@extends('layouts.admin')

@section('page-title')
    {{ __('Employee Data Change Log') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Employee Data Log') }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Employee Data Change Log') }}</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('employee-data-log.index') }}" class="mb-4">
                        <div class="row g-2 align-items-end">
                            <div class="col-lg-3 col-md-6">
                                <label class="form-label">{{ __('Employee') }}</label>
                                <select name="employee_id" class="form-control">
                                    <option value="">{{ __('All Employees') }}</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }} ({{ $emp->employee_id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-3">
                                <label class="form-label">{{ __('Date From') }}</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-lg-2 col-md-3">
                                <label class="form-label">{{ __('Date To') }}</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <label class="form-label">{{ __('Source') }}</label>
                                <select name="source" class="form-control">
                                    <option value="">{{ __('All') }}</option>
                                    @foreach ($sources as $key => $label)
                                        <option value="{{ $key }}" {{ request('source') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <label class="form-label">{{ __('Actor Type') }}</label>
                                <select name="actor_type" class="form-control">
                                    <option value="">{{ __('All') }}</option>
                                    @foreach ($actorTypes as $type)
                                        <option value="{{ $type }}" {{ request('actor_type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-1 col-md-4">
                                <button type="submit" class="btn btn-primary w-100 text-nowrap">{{ __('Filter') }}</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Date / Time') }}</th>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Changed By') }}</th>
                                    <th>{{ __('Source') }}</th>
                                    <th>{{ __('Modules Touched') }}</th>
                                    <th width="100">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                    <tr>
                                        <td>{{ optional($log->created_at)->format('d M Y H:i:s') }}</td>
                                        <td>
                                            @if ($log->employee)
                                                {{ $log->employee->name }}
                                                <div class="text-muted small">{{ $log->employee->employee_id }}</div>
                                            @else
                                                #{{ $log->employee_id }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($log->changedBy)
                                                {{ $log->changedBy->name }}
                                                <div class="text-muted small">{{ $log->actor_type }}</div>
                                            @else
                                                {{ $log->actor_type ?? '—' }}
                                            @endif
                                        </td>
                                        <td>{{ $log->source_label }}</td>
                                        <td>
                                            @php $touched = $log->modules_touched ?? []; @endphp
                                            @if (empty($touched))
                                                <span class="text-muted">{{ __('No field changes detected') }}</span>
                                            @else
                                                @foreach ($touched as $mod)
                                                    <span class="badge bg-light-primary text-primary me-1 mb-1">
                                                        {{ \App\Services\EmployeeDataChangeLogService::moduleLabel($mod) }}
                                                    </span>
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('employee-data-log.show', $log->id) }}" class="btn btn-sm btn-primary">
                                                {{ __('View') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">{{ __('No change logs found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
