@extends('layouts.admin')

@section('page-title')
    {{ __('Test Notification') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Test Notification') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Send Test Push Notification') }}</h5>
                <small class="text-muted">{{ __('No database write. Log is session-only.') }}</small>
            </div>
            <div class="card-body">
                    <form method="get" action="{{ route('test.notification.index') }}" class="row g-2 mb-3">
                        <div class="col-md-7">
                            <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control"
                                placeholder="{{ __('Search name / email / id') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="with_token" class="form-control">
                                <option value="0" {{ empty($onlyWithToken) ? 'selected' : '' }}>{{ __('All users') }}</option>
                                <option value="1" {{ !empty($onlyWithToken) ? 'selected' : '' }}>{{ __('Only with device token') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary w-100" type="submit">{{ __('Filter') }}</button>
                        </div>
                    </form>

                    <form method="post" action="{{ route('test.notification.send') }}" id="testNotificationForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">
                            {{ __('User') }}
                            <small class="text-muted">({{ $users->count() }} {{ __('listed') }}, {{ $usersWithTokenCount ?? 0 }} {{ __('with token') }})</small>
                        </label>
                        <select name="user_id" class="form-control" required>
                            <option value="">{{ __('Select user') }}</option>
                            @foreach ($users as $user)
                                @php $hasToken = !empty($user->device_token); @endphp
                                <option value="{{ $user->id }}"
                                    {{ (string) old('user_id') === (string) $user->id ? 'selected' : '' }}
                                    @if(!$hasToken) data-no-token="1" @endif>
                                    {{ $user->name }} — {{ $user->email }}
                                    [{{ $user->type }}]
                                    @if ($hasToken)
                                        ✓ token
                                        ({{ $user->device_type == 1 ? 'iOS' : ($user->device_type == 2 ? 'Android' : 'Web') }})
                                    @else
                                        ✗ no token
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @if ($users->isEmpty())
                            <small class="text-danger">{{ __('No users found for this filter.') }}</small>
                        @else
                            <small class="text-muted">{{ __('Users without device token can be selected, but send will fail until the app registers a token.') }}</small>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Notification Type') }}</label>
                        <select name="notification_type" id="notification_type" class="form-control" required>
                            @foreach ($templates as $key => $tpl)
                                <option value="{{ $key }}"
                                    data-title="{{ $tpl['title'] }}"
                                    data-body="{{ $tpl['body'] }}"
                                    {{ old('notification_type', 'general_test') === $key ? 'selected' : '' }}>
                                    {{ $tpl['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Title (optional override)') }}</label>
                        <input type="text" name="title" id="notif_title" class="form-control"
                            value="{{ old('title') }}" placeholder="{{ __('Leave blank to use template title') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Body (optional override)') }}</label>
                        <textarea name="body" id="notif_body" class="form-control" rows="3"
                            placeholder="{{ __('Leave blank to use template body') }}">{{ old('body') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" @if($users->isEmpty()) disabled @endif>
                        <i class="ti ti-bell"></i> {{ __('Send Test Notification') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">{{ __('Notification Test Log') }}</h5>
                    <small class="text-muted">{{ __('Latest attempts in this browser session') }}</small>
                </div>
                <form method="post" action="{{ route('test.notification.clear-log') }}"
                    onsubmit="return confirm('{{ __('Clear log?') }}')">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('Clear Log') }}</button>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-3">{{ __('Time') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="pe-3">{{ __('Message') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($logs as $log)
                                <tr>
                                    <td class="ps-3 small">{{ $log['at'] ?? '-' }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $log['user'] ?? '-' }}</div>
                                        <div class="small text-muted">{{ $log['email'] ?? '' }}</div>
                                    </td>
                                    <td>
                                        <div>{{ $log['type'] ?? '-' }}</div>
                                        <div class="small text-muted">{{ $log['title'] ?? '' }}</div>
                                    </td>
                                    <td>
                                        @if (($log['status'] ?? '') === 'sent')
                                            <span class="badge bg-success">{{ __('Sent') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('Failed') }}</span>
                                        @endif
                                    </td>
                                    <td class="pe-3 small">
                                        {{ $log['message'] ?? '-' }}
                                        @if (!empty($log['response']))
                                            <details class="mt-1">
                                                <summary>{{ __('FCM response') }}</summary>
                                                <code class="small">{{ $log['response'] }}</code>
                                            </details>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        {{ __('No test notifications sent yet in this session.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script-page')
<script>
    (function () {
        var select = document.getElementById('notification_type');
        var title = document.getElementById('notif_title');
        var body = document.getElementById('notif_body');
        if (!select) return;

        function fillTemplate() {
            var opt = select.options[select.selectedIndex];
            if (!opt) return;
            if (!title.value) title.placeholder = opt.getAttribute('data-title') || '';
            if (!body.value) body.placeholder = opt.getAttribute('data-body') || '';
        }
        select.addEventListener('change', function () {
            title.value = '';
            body.value = '';
            fillTemplate();
        });
        fillTemplate();
    })();
</script>
@endpush
