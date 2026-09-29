@php
    $employeeLogin = $loginAnalytics['employees'] ?? null;
    $companyLogin = $loginAnalytics['companies'] ?? null;
    $isSuperAdmin = \Auth::user()->type == 'super admin';
@endphp

@if (!empty($employeeLogin))
    <div class="col-xxl-12 mt-2">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <h5 class="mb-0 fw-semibold">
                <i class="ti ti-login text-primary me-1"></i>
                {{ $isSuperAdmin ? __('Login Analytics') : __('Employee Login Analytics') }}
            </h5>
            @if (!$isSuperAdmin && \Illuminate\Support\Facades\Route::has('lastlogin'))
                <a href="{{ route('lastlogin') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-list me-1"></i>{{ __('View All Last Logins') }}
                </a>
            @endif
        </div>

        @if ($isSuperAdmin && !empty($companyLogin))
            <div class="row mb-2">
                <div class="col-12 mb-2">
                    <small class="text-muted text-uppercase fw-bold">{{ __('Company Web Logins') }}</small>
                </div>
                <div class="col-xxl-2 col-md-4 col-sm-6">
                    <div class="card dashboard-stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrapper stat-icon-primary">
                                        <i class="ti ti-building"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="stat-label-caption">{{ __('Total') }}</div>
                                        <h6 class="stat-title">{{ __('Companies') }}</h6>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h4 class="stat-number text-primary">{{ $companyLogin['total'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-2 col-md-4 col-sm-6">
                    <div class="card dashboard-stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrapper stat-icon-success">
                                        <i class="ti ti-calendar-event"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="stat-label-caption">{{ __('Logged In') }}</div>
                                        <h6 class="stat-title">{{ __('Today') }}</h6>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h4 class="stat-number text-success">{{ $companyLogin['today'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-2 col-md-4 col-sm-6">
                    <div class="card dashboard-stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrapper stat-icon-info">
                                        <i class="ti ti-calendar-stats"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="stat-label-caption">{{ __('Last 7 Days') }}</div>
                                        <h6 class="stat-title">{{ __('Active') }}</h6>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h4 class="stat-number text-info">{{ $companyLogin['week'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-4 col-sm-6">
                    <div class="card dashboard-stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrapper stat-icon-warning">
                                        <i class="ti ti-user-off"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="stat-label-caption">{{ __('Never') }}</div>
                                        <h6 class="stat-title">{{ __('Logged In') }}</h6>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h4 class="stat-number text-warning">{{ $companyLogin['never'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-4 col-sm-6">
                    <div class="card dashboard-stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon-wrapper stat-icon-warning">
                                        <i class="ti ti-clock-off"></i>
                                    </div>
                                    <div class="ms-3">
                                        <div class="stat-label-caption">{{ __('Inactive') }}</div>
                                        <h6 class="stat-title">{{ __('30+ Days') }}</h6>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <h4 class="stat-number text-warning">{{ $companyLogin['inactive_30'] }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            @if ($isSuperAdmin)
                <div class="col-12 mb-2">
                    <small class="text-muted text-uppercase fw-bold">{{ __('Employee Web Logins') }}</small>
                </div>
            @endif
            <div class="col-xxl-2 col-md-4 col-sm-6">
                <div class="card dashboard-stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon-wrapper stat-icon-primary">
                                    <i class="ti ti-users"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="stat-label-caption">{{ __('Total') }}</div>
                                    <h6 class="stat-title">{{ __('Employees') }}</h6>
                                </div>
                            </div>
                            <div class="text-end">
                                <h4 class="stat-number text-primary">{{ $employeeLogin['total'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-4 col-sm-6">
                <div class="card dashboard-stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon-wrapper stat-icon-success">
                                    <i class="ti ti-login"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="stat-label-caption">{{ __('Logged In') }}</div>
                                    <h6 class="stat-title">{{ __('Today') }}</h6>
                                </div>
                            </div>
                            <div class="text-end">
                                <h4 class="stat-number text-success">{{ $employeeLogin['today'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-2 col-md-4 col-sm-6">
                <div class="card dashboard-stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon-wrapper stat-icon-info">
                                    <i class="ti ti-calendar-stats"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="stat-label-caption">{{ __('Last 7 Days') }}</div>
                                    <h6 class="stat-title">{{ __('Active') }}</h6>
                                </div>
                            </div>
                            <div class="text-end">
                                <h4 class="stat-number text-info">{{ $employeeLogin['week'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-4 col-sm-6">
                <div class="card dashboard-stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon-wrapper stat-icon-warning">
                                    <i class="ti ti-user-off"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="stat-label-caption">{{ __('Never') }}</div>
                                    <h6 class="stat-title">{{ __('Logged In') }}</h6>
                                </div>
                            </div>
                            <div class="text-end">
                                <h4 class="stat-number text-warning">{{ $employeeLogin['never'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-md-4 col-sm-6">
                <div class="card dashboard-stat-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon-wrapper stat-icon-warning">
                                    <i class="ti ti-clock-off"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="stat-label-caption">{{ __('Inactive') }}</div>
                                    <h6 class="stat-title">{{ __('30+ Days') }}</h6>
                                </div>
                            </div>
                            <div class="text-end">
                                <h4 class="stat-number text-warning">{{ $employeeLogin['inactive_30'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2">
            @if ($isSuperAdmin && !empty($companyLogin))
                <div class="col-lg-6">
                    <div class="card dashboard-table-card">
                        <div class="card-header">
                            <h5><i class="ti ti-building text-primary"></i> {{ __('Recent Company Logins') }}</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Company') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Last Login') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($companyLogin['recent'] as $row)
                                            <tr>
                                                <td>{{ $row->name }}</td>
                                                <td>{{ $row->email }}</td>
                                                <td>{{ $row->last_login }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">{{ __('No recent company logins') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="{{ $isSuperAdmin ? 'col-lg-6' : 'col-12' }}">
                <div class="card dashboard-table-card">
                    <div class="card-header">
                        <h5><i class="ti ti-user-check text-primary"></i> {{ __('Recent Employee Logins') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Last Login') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($employeeLogin['recent'] as $row)
                                        <tr>
                                            <td>{{ $row->name }}</td>
                                            <td>{{ $row->email }}</td>
                                            <td>{{ $row->last_login }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-3">{{ __('No recent employee logins') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
