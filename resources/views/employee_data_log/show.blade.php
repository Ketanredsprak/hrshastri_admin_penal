@extends('layouts.admin')

@section('page-title')
    {{ __('Employee Data Change Detail') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employee-data-log.index') }}">{{ __('Employee Data Log') }}</a></li>
    <li class="breadcrumb-item">{{ __('Detail') }} #{{ $log->id }}</li>
@endsection

@section('action-button')
    <a href="{{ route('employee-data-log.index', ['employee_id' => $log->employee_id]) }}" class="btn btn-sm btn-primary">
        <i class="ti ti-arrow-left"></i> {{ __('Back to list') }}
    </a>
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-muted small">{{ __('Employee') }}</div>
                            <strong>
                                @if ($log->employee)
                                    {{ $log->employee->name }} ({{ $log->employee->employee_id }})
                                @else
                                    #{{ $log->employee_id }}
                                @endif
                            </strong>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">{{ __('Changed By') }}</div>
                            <strong>{{ optional($log->changedBy)->name ?? '—' }}</strong>
                            <div class="text-muted small">{{ $log->actor_type }}</div>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small">{{ __('Source') }}</div>
                            <strong>{{ $log->source_label }}</strong>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small">{{ __('When') }}</div>
                            <strong>{{ optional($log->created_at)->format('d M Y H:i:s') }}</strong>
                        </div>
                        <div class="col-md-2">
                            <div class="text-muted small">{{ __('Modules') }}</div>
                            <strong>{{ count($log->modules_touched ?? []) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="accordion" id="moduleAccordion">
                @foreach ($modules as $key => $module)
                    <div class="accordion-item mb-2 border {{ $module['changed'] ? 'border-primary' : '' }}">
                        <h2 class="accordion-header" id="heading-{{ $key }}">
                            <button class="accordion-button {{ $module['changed'] ? '' : 'collapsed' }}" type="button"
                                data-bs-toggle="collapse" data-bs-target="#collapse-{{ $key }}"
                                aria-expanded="{{ $module['changed'] ? 'true' : 'false' }}"
                                aria-controls="collapse-{{ $key }}">
                                {{ $module['label'] }}
                                @if ($module['changed'])
                                    <span class="badge bg-warning text-dark ms-2">{{ __('Changed') }}</span>
                                @else
                                    <span class="badge bg-secondary ms-2">{{ __('Unchanged') }}</span>
                                @endif
                            </button>
                        </h2>
                        <div id="collapse-{{ $key }}" class="accordion-collapse collapse {{ $module['changed'] ? 'show' : '' }}"
                            aria-labelledby="heading-{{ $key }}" data-bs-parent="#moduleAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-muted">{{ __('Before') }}</h6>
                                        <pre class="bg-light p-3 rounded small" style="white-space: pre-wrap; max-height: 420px; overflow: auto;">{{ json_encode($module['before'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted">{{ __('After') }}</h6>
                                        <pre class="bg-light p-3 rounded small" style="white-space: pre-wrap; max-height: 420px; overflow: auto;">{{ json_encode($module['after'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
