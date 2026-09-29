@extends($layout)

@section('page-title')
    {{ __('Subscription Limit Management & Synchronization') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('plans.index') }}">{{ __('Plan') }}</a></li>
    <li class="breadcrumb-item">{{ __('Subscription Sync & Limits') }}</li>
@endsection

@section('action-button')
    <a href="{{ route('subscription.sync', ['action' => 'sync_all']) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Audit and assign default plans to unassigned companies') }}">
        <i class="ti ti-refresh"></i> {{ __('Run Full Sync') }}
    </a>
    <a href="{{ route('subscription.sync', ['action' => 'auto_adjust']) }}" class="btn btn-sm btn-warning ms-1" data-bs-toggle="tooltip" title="{{ __('Automatically adjust custom limits for companies exceeding plan capacity') }}" onclick="return confirm('Auto-adjust will raise custom limits for any companies currently exceeding capacity to match their active count. Proceed?');">
        <i class="ti ti-bolt"></i> {{ __('Auto-Adjust Limits') }}
    </a>
    <a href="{{ route('subscription.sync', ['format' => 'json']) }}" target="_blank" class="btn btn-sm btn-dark ms-1" data-bs-toggle="tooltip" title="{{ __('View raw JSON API response') }}">
        <i class="ti ti-code"></i> {{ __('JSON View') }}
    </a>
@endsection

@section('content')
<style>
    .sync-stat-card {
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .sync-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }
    .progress-bar-custom {
        height: 7px;
        border-radius: 4px;
        background-color: #eaedf1;
        overflow: hidden;
        margin-top: 4px;
    }
    .progress-bar-fill {
        height: 100%;
        transition: width 0.4s ease-in-out;
    }
    .badge-soft-success { background-color: #d1e7dd; color: #0f5132; }
    .badge-soft-danger { background-color: #f8d7da; color: #842029; }
    .badge-soft-warning { background-color: #fff3cd; color: #664d03; }
    .badge-soft-info { background-color: #cff4fc; color: #055160; }
    .badge-soft-secondary { background-color: #e2e3e5; color: #41464b; }
    .custom-limit-badge {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 4px;
        background: #e0e7ff;
        color: #3730a3;
        font-weight: 600;
        display: inline-block;
        margin-left: 4px;
    }
    .sync-json-box {
        background: #1e1e2f;
        color: #a5d6a7;
        font-family: 'Consolas', 'Monaco', monospace;
        font-size: 12px;
        max-height: 400px;
        overflow-y: auto;
        border-radius: 8px;
        padding: 15px;
    }
</style>

{{-- Action Notification Banner --}}
@if($action !== 'audit')
    <div class="alert alert-info alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
        <i class="ti ti-info-circle fs-3 me-2"></i>
        <div>
            <strong>{{ __('Sync Action Executed:') }}</strong> <code>{{ $action }}</code>. 
            {{ __('Total actions/updates applied:') }} <strong>{{ $stats['synced_actions_count'] }}</strong>.
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Stats Metric Cards --}}
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card sync-stat-card bg-white shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle p-3 bg-light-primary text-primary me-3">
                    <i class="ti ti-building fs-2"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase fs-7">{{ __('Total Companies') }}</h6>
                    <h3 class="mb-0 fw-bold">{{ $stats['total_companies'] }}</h3>
                    <small class="text-muted">{{ $stats['companies_with_custom_limits'] }} {{ __('with custom limits') }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card sync-stat-card bg-white shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle p-3 bg-light-success text-success me-3">
                    <i class="ti ti-circle-check fs-2"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase fs-7">{{ __('Active Subscriptions') }}</h6>
                    <h3 class="mb-0 fw-bold text-success">{{ $stats['active_subscriptions'] }}</h3>
                    <small class="text-muted">{{ $stats['expired_subscriptions'] }} {{ __('expired') }} | {{ $stats['no_expiry_set'] }} {{ __('no expiry') }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card sync-stat-card bg-white shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle p-3 bg-light-info text-info me-3">
                    <i class="ti ti-users fs-2"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase fs-7">{{ __('Active Employees') }}</h6>
                    <h3 class="mb-0 fw-bold text-info">{{ $stats['total_active_employees'] }}</h3>
                    <small class="text-muted">{{ $stats['total_terminated_employees'] }} {{ __('terminated/exited') }}</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card sync-stat-card bg-white shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle p-3 {{ ($stats['companies_over_user_limit'] > 0 || $stats['companies_over_emp_limit'] > 0) ? 'bg-light-danger text-danger' : 'bg-light-success text-success' }} me-3">
                    <i class="ti {{ ($stats['companies_over_user_limit'] > 0 || $stats['companies_over_emp_limit'] > 0) ? 'ti-alert-triangle' : 'ti-shield-check' }} fs-2"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 text-uppercase fs-7">{{ __('Capacity Over-Limit') }}</h6>
                    <h3 class="mb-0 fw-bold {{ ($stats['companies_over_user_limit'] > 0 || $stats['companies_over_emp_limit'] > 0) ? 'text-danger' : 'text-success' }}">
                        {{ $stats['companies_over_user_limit'] + $stats['companies_over_emp_limit'] }}
                    </h3>
                    <small class="text-muted">{{ $stats['companies_over_emp_limit'] }} {{ __('emp') }}, {{ $stats['companies_over_user_limit'] }} {{ __('user over') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Main Company Synchronization Table --}}
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <div class="row align-items-center">
            <div class="col-md-4 mb-2 mb-md-0">
                <h5 class="mb-0 fw-bold text-dark"><i class="ti ti-adjustments-horizontal text-primary me-1"></i> {{ __('Company Limits & Subscription Status') }}</h5>
                <small class="text-muted">{{ __('Live synchronization status and custom capacity control') }}</small>
            </div>
            <div class="col-md-8">
                <div class="d-flex flex-wrap justify-content-md-end gap-2">
                    <input type="text" id="syncTableSearch" class="form-control form-control-sm w-auto" placeholder="{{ __('Search company name, email, ID...') }}">
                    <select id="filterStatus" class="form-select form-select-sm w-auto">
                        <option value="all">{{ __('All Subscriptions') }}</option>
                        <option value="active">{{ __('Active Only') }}</option>
                        <option value="expired">{{ __('Expired Only') }}</option>
                        <option value="custom">{{ __('Custom Limits Only') }}</option>
                        <option value="overlimit">{{ __('Over Capacity Only') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="syncCompaniesTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">{{ __('Company ID & Name') }}</th>
                        <th>{{ __('Plan & Duration') }}</th>
                        <th>{{ __('Subscription Status') }}</th>
                        <th>{{ __('Sub-Users (Used / Max)') }}</th>
                        <th>{{ __('Employees (Active / Max)') }}</th>
                        <th class="text-center">{{ __('Quick Adjust Limits') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $c)
                    @php
                        $userOver = $c['users']['is_over_limit'];
                        $empOver = $c['employees']['is_over_limit'];
                        $hasCustom = $c['users']['is_custom_applied'] || $c['employees']['is_custom_applied'];
                        $statusClass = 'all';
                        if ($c['is_subscription_active']) $statusClass .= ' active';
                        if ($c['subscription_status'] === 'Expired') $statusClass .= ' expired';
                        if ($hasCustom) $statusClass .= ' custom';
                        if ($userOver || $empOver) $statusClass .= ' overlimit';
                    @endphp
                    <tr class="company-row {{ $statusClass }}" id="company-row-{{ $c['company_id'] }}">
                        {{-- Company Info --}}
                        <td class="ps-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle me-2 fw-bold text-center" style="width: 36px; height: 36px; line-height: 36px;">
                                    #{{ $c['company_id'] }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark company-name-text">{{ $c['name'] }}</div>
                                    <small class="text-muted company-email-text">{{ $c['email'] }}</small>
                                </div>
                            </div>
                        </td>

                        {{-- Plan Info --}}
                        <td>
                            <div class="fw-semibold text-dark">{{ $c['plan_name'] }}</div>
                            <small class="text-muted">
                                {{ $c['plan_duration'] }} (₹{{ number_format($c['plan_price']) }})
                            </small>
                        </td>

                        {{-- Subscription Status --}}
                        <td>
                            @if($c['subscription_status'] === 'Active')
                                <span class="badge badge-soft-success px-2 py-1"><i class="ti ti-check me-1"></i>{{ __('Active') }}</span>
                            @elseif($c['subscription_status'] === 'Expired')
                                <span class="badge badge-soft-danger px-2 py-1"><i class="ti ti-calendar me-1"></i>{{ __('Expired') }}</span>
                            @else
                                <span class="badge badge-soft-secondary px-2 py-1">{{ __('No Date') }}</span>
                            @endif
                            <div class="text-muted fs-7 mt-1">
                                {{ $c['plan_expire_date'] ? date('d M Y', strtotime($c['plan_expire_date'])) : __('No expiry set') }}
                            </div>
                        </td>

                        {{-- Sub Users Limit --}}
                        <td>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold {{ $userOver ? 'text-danger' : 'text-dark' }}">
                                    {{ $c['users']['active_count'] }} / {{ $c['users']['effective_max'] }}
                                </span>
                                @if($c['users']['is_custom_applied'])
                                    <span class="custom-limit-badge" title="{{ __('Custom override set by admin') }}">Custom</span>
                                @endif
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-bar-fill {{ $userOver ? 'bg-danger' : ($c['users']['usage_percent'] > 80 ? 'bg-warning' : 'bg-primary') }}" style="width: {{ $c['users']['usage_percent'] }}%;"></div>
                            </div>
                            <small class="text-muted fs-8">
                                {{ $userOver ? __('Over limit by ') . ($c['users']['active_count'] - $c['users']['effective_max']) : $c['users']['remaining'] . __(' spots left') }}
                            </small>
                        </td>

                        {{-- Employees Limit --}}
                        <td>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold {{ $empOver ? 'text-danger' : 'text-dark' }}">
                                    {{ $c['employees']['active_count'] }} / {{ $c['employees']['effective_max'] }}
                                </span>
                                @if($c['employees']['is_custom_applied'])
                                    <span class="custom-limit-badge" title="{{ __('Custom override set by admin') }}">Custom</span>
                                @endif
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-bar-fill {{ $empOver ? 'bg-danger' : ($c['employees']['usage_percent'] > 80 ? 'bg-warning' : 'bg-success') }}" style="width: {{ $c['employees']['usage_percent'] }}%;"></div>
                            </div>
                            <small class="text-muted fs-8">
                                {{ $empOver ? __('Over limit by ') . ($c['employees']['active_count'] - $c['employees']['effective_max']) : $c['employees']['remaining'] . __(' spots left') }}
                                ({{ $c['employees']['terminated_count'] }} {{ __('exited') }})
                            </small>
                        </td>

                        {{-- Quick Adjust Actions --}}
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-adjust-limit"
                                data-company-id="{{ $c['company_id'] }}"
                                data-company-name="{{ $c['name'] }}"
                                data-plan-id="{{ $c['plan_id'] }}"
                                data-users-active="{{ $c['users']['active_count'] }}"
                                data-users-plan-max="{{ $c['users']['plan_max'] }}"
                                data-users-custom="{{ $c['users']['custom_max'] ?? '' }}"
                                data-emps-active="{{ $c['employees']['active_count'] }}"
                                data-emps-plan-max="{{ $c['employees']['plan_max'] }}"
                                data-emps-custom="{{ $c['employees']['custom_max'] ?? '' }}"
                                data-plan-expire="{{ $c['plan_expire_date'] ?? '' }}">
                                <i class="ti ti-pencil me-1"></i> {{ __('Adjust Limits') }}
                            </button>
                            <a href="{{ route('subscription.sync', ['action' => 'auto_adjust', 'company_id' => $c['company_id']]) }}" class="btn btn-sm btn-outline-warning ms-1" data-bs-toggle="tooltip" title="{{ __('Auto adjust limits for this company') }}">
                                <i class="ti ti-bolt"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="ti ti-folder-off fs-1 d-block mb-2"></i>
                            {{ __('No company records found.') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Single Global Adjust Limits Modal (Rendered Outside Table for Perfect Positioning) --}}
<div class="modal fade" id="adjustLimitsModal" tabindex="-1" aria-labelledby="adjustLimitsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content text-start shadow-lg border-0 rounded-4">
            <form action="{{ route('subscription.sync.update') }}" method="POST" id="adjustLimitsForm">
                @csrf
                <input type="hidden" name="company_id" id="modal_company_id" value="">
                
                <div class="modal-header bg-light px-4 py-3 border-bottom">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center mb-0" id="adjustLimitsModalLabel">
                        <i class="ti ti-adjustments text-primary fs-4 me-2"></i>
                        <span>{{ __('Adjust Capacity Limits') }} — <span id="modal_company_name" class="text-primary"></span></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    {{-- Usage Overview Box --}}
                    <div class="p-3 bg-light rounded-3 border mb-4">
                        <h6 class="text-muted text-uppercase fs-8 fw-bold mb-2">{{ __('Current Live Usage') }}</h6>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="bg-white p-3 rounded-3 border">
                                    <small class="text-muted d-block fs-7 mb-1">{{ __('Active Employees') }}</small>
                                    <div class="d-flex align-items-baseline gap-2">
                                        <span class="fs-4 fw-bold text-dark" id="modal_display_active_emps">0</span>
                                        <span class="text-muted fs-7">/ <span id="modal_display_plan_emps">0</span> (Plan default)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="bg-white p-3 rounded-3 border">
                                    <small class="text-muted d-block fs-7 mb-1">{{ __('Active Sub-Users') }}</small>
                                    <div class="d-flex align-items-baseline gap-2">
                                        <span class="fs-4 fw-bold text-dark" id="modal_display_active_users">0</span>
                                        <span class="text-muted fs-7">/ <span id="modal_display_plan_users">0</span> (Plan default)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Fields --}}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">{{ __('Subscription Plan') }} <span class="text-danger">*</span></label>
                            <select name="plan_id" id="modal_plan_id" class="form-select">
                                @foreach($allPlans as $pl)
                                    <option value="{{ $pl->id }}">
                                        {{ $pl->name }} (Users: {{ $pl->max_users == -1 ? 'Unlimited' : $pl->max_users }}, Emps: {{ $pl->max_employees == -1 ? 'Unlimited' : $pl->max_employees }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">{{ __('Plan Expiry Date') }}</label>
                            <input type="date" name="plan_expire_date" id="modal_plan_expire_date" class="form-control">
                        </div>

                        <div class="col-12 mt-3 mb-1">
                            <div class="d-flex align-items-center gap-1">
                                <i class="ti ti-adjustments text-warning fs-5"></i>
                                <h6 class="fw-bold text-dark mb-0">{{ __('Custom Capacity Limits (Admin Override)') }}</h6>
                            </div>
                            <small class="text-muted fs-8">{{ __('Leave blank or empty to use standard plan defaults. Enter -1 for unlimited.') }}</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">{{ __('Custom Max Employees Limit') }}</label>
                            <input type="number" name="custom_max_employees" id="modal_custom_max_employees" class="form-control" placeholder="{{ __('Leave blank to inherit plan default') }}" min="-1">
                            <small class="text-muted fs-8">{{ __('Plan default:') }} <span id="modal_help_plan_emps">0</span></small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark">{{ __('Custom Max Users Limit') }}</label>
                            <input type="number" name="custom_max_users" id="modal_custom_max_users" class="form-control" placeholder="{{ __('Leave blank to inherit plan default') }}" min="-1">
                            <small class="text-muted fs-8">{{ __('Plan default:') }} <span id="modal_help_plan_users">0</span></small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light px-4 py-3 border-top">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="ti ti-check me-1"></i> {{ __('Save Limit Changes') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Table Search & Filter
    const searchInput = document.getElementById('syncTableSearch');
    const filterSelect = document.getElementById('filterStatus');
    const rows = document.querySelectorAll('.company-row');

    function applyFilters() {
        const query = searchInput.value.toLowerCase().trim();
        const selectedFilter = filterSelect.value;

        rows.forEach(row => {
            const nameEl = row.querySelector('.company-name-text');
            const emailEl = row.querySelector('.company-email-text');
            const name = nameEl ? nameEl.textContent.toLowerCase() : '';
            const email = emailEl ? emailEl.textContent.toLowerCase() : '';
            const textMatch = !query || name.includes(query) || email.includes(query) || row.id.includes(query);
            
            let filterMatch = true;
            if (selectedFilter !== 'all') {
                filterMatch = row.classList.contains(selectedFilter);
            }

            if (textMatch && filterMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (filterSelect) filterSelect.addEventListener('change', applyFilters);

    // 2. Dynamic Modal Population
    const adjustModalEl = document.getElementById('adjustLimitsModal');
    let adjustModalInstance = null;

    document.querySelectorAll('.btn-adjust-limit').forEach(btn => {
        btn.addEventListener('click', function() {
            const d = this.dataset;
            document.getElementById('modal_company_id').value = d.companyId || '';
            document.getElementById('modal_company_name').textContent = d.companyName || '';
            document.getElementById('modal_display_active_emps').textContent = d.empsActive || '0';
            document.getElementById('modal_display_plan_emps').textContent = d.empsPlanMax == '-1' ? 'Unlimited' : d.empsPlanMax;
            document.getElementById('modal_help_plan_emps').textContent = d.empsPlanMax == '-1' ? 'Unlimited' : d.empsPlanMax;
            document.getElementById('modal_display_active_users').textContent = d.usersActive || '0';
            document.getElementById('modal_display_plan_users').textContent = d.usersPlanMax == '-1' ? 'Unlimited' : d.usersPlanMax;
            document.getElementById('modal_help_plan_users').textContent = d.usersPlanMax == '-1' ? 'Unlimited' : d.usersPlanMax;
            
            document.getElementById('modal_plan_id').value = d.planId || '';
            document.getElementById('modal_custom_max_employees').value = d.empsCustom || '';
            document.getElementById('modal_custom_max_users').value = d.usersCustom || '';
            document.getElementById('modal_plan_expire_date').value = d.planExpire || '';

            if (!adjustModalInstance && window.bootstrap && window.bootstrap.Modal) {
                adjustModalInstance = new bootstrap.Modal(adjustModalEl);
            }
            if (adjustModalInstance) {
                adjustModalInstance.show();
            } else {
                // Fallback using jQuery if available
                if (window.$ && $.fn.modal) {
                    $('#adjustLimitsModal').modal('show');
                }
            }
        });
    });
});
</script>
@endsection
