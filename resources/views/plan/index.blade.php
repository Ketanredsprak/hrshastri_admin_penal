@extends('layouts.admin')

@section('page-title')
    {{ __('Subscription Plans') }}
@endsection

@section('breadcrumb')
    @if(\Auth::user()->hasActiveSubscription())
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    @endif
    <li class="breadcrumb-item">{{ __('Subscription Plans') }}</li>
@endsection

@section('action-button')
    <div class="d-flex align-items-center gap-2">
        {{-- Super Admin ONLY: Sync Hub Link & Create Plan Button --}}
        @if(\Auth::user()->type == 'super admin')
            <a href="{{ route('subscription.sync') }}" class="btn btn-sm btn-outline-primary shadow-sm" data-bs-toggle="tooltip" title="{{ __('Manage company capacity limits & sync subscription data') }}">
                <i class="ti ti-refresh me-1"></i> {{ __('Sync & Limits Hub') }}
            </a>

            @if (!empty($admin_payment_setting) &&
                (($admin_payment_setting['is_stripe_enabled'] == 'on' && !empty($admin_payment_setting['stripe_key']) && !empty($admin_payment_setting['stripe_secret'])) ||
                ($admin_payment_setting['is_razorpay_enabled'] == 'on' && !empty($admin_payment_setting['razorpay_public_key']) && !empty($admin_payment_setting['razorpay_secret_key'])) ||
                ($admin_payment_setting['is_paypal_enabled'] == 'on' && !empty($admin_payment_setting['paypal_client_id']) && !empty($admin_payment_setting['paypal_secret_key']))))
                <a href="#" data-url="{{ route('plans.create') }}" data-size="md" data-ajax-popup="true"
                    data-title="{{ __('Create New Plan') }}" data-bs-toggle="tooltip" title="{{ __('Create New Plan') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="ti ti-plus me-1"></i> {{ __('Create Plan') }}
                </a>
            @else
                <a href="#" data-url="{{ route('plans.create') }}" data-size="md" data-ajax-popup="true"
                    data-title="{{ __('Create New Plan') }}" data-bs-toggle="tooltip" title="{{ __('Create New Plan') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="ti ti-plus me-1"></i> {{ __('Create Plan') }}
                </a>
            @endif
        @endif
    </div>
@endsection

@section('content')
<style>
    /* Modern Pricing Card Styles */
    .plan-card-wrapper {
        display: flex;
    }
    .modern-plan-card {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        width: 100%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }
    .modern-plan-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }
    .modern-plan-card.is-suggested {
        border: 2px solid #0d6efd !important;
        box-shadow: 0 12px 28px rgba(13, 110, 253, 0.15) !important;
    }
    .modern-plan-card.is-active-plan {
        border: 2px solid #198754 !important;
        box-shadow: 0 12px 28px rgba(25, 135, 84, 0.14) !important;
    }
    .modern-plan-card.is-ineligible {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
    }
    .plan-header {
        padding: 22px 22px 16px 22px;
        border-bottom: 1px solid #f1f5f9;
        position: relative;
    }
    .plan-badge-pill {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        padding: 5px 12px;
        border-radius: 20px;
        display: inline-block;
        line-height: 1.2;
    }
    .plan-status-badge {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        text-transform: uppercase;
        line-height: 1.2;
    }
    .plan-status-badge-recommended {
        background: #eef2ff;
        color: #0d6efd;
        border: 1px solid #bfdbfe;
    }
    .plan-status-badge-active {
        background: #ecfdf5;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .plan-price-box {
        margin-top: 14px;
        display: flex;
        align-items: baseline;
        gap: 3px;
    }
    .plan-price-currency {
        font-size: 20px;
        font-weight: 600;
        color: #1e293b;
    }
    .plan-price-amount {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
    }
    .plan-price-duration {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        margin-left: 2px;
    }
    .plan-body {
        padding: 18px 22px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .plan-description {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        min-height: 38px;
        margin-bottom: 16px;
    }
    .plan-features-list {
        list-style: none;
        padding: 0;
        margin: 0 0 16px 0;
    }
    .plan-feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        font-size: 13.5px;
        color: #334155;
    }
    .plan-feature-item:last-child {
        margin-bottom: 0;
    }
    .plan-feature-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        flex-shrink: 0;
    }
    .plan-footer {
        padding: 16px 22px 20px 22px;
        background: #fbfcfd;
        border-top: 1px solid #f1f5f9;
        border-bottom-left-radius: 16px;
        border-bottom-right-radius: 16px;
    }
    .capacity-overview-card {
        background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
        border: 1px solid #e0e7ff;
        border-radius: 16px;
        padding: 20px 24px;
        margin-bottom: 28px;
    }
    .pulse-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background-color: #198754;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
        animation: pulse-green 1.8s infinite;
    }
    @keyframes pulse-green {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 5px rgba(25, 135, 84, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
    }
    .edit-plan-btn {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #f1f5f9;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
    }
    .edit-plan-btn:hover {
        background: #0d6efd;
        color: #ffffff;
    }
</style>

{{-- Inactive Subscription Full-Page Notification for Companies --}}
@if(\Auth::user()->type == 'company' && !\Auth::user()->hasActiveSubscription())
    <div class="alert alert-warning border-0 shadow-sm rounded-4 p-3.5 mb-4 text-dark" style="background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-left: 5px solid #f59e0b !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle p-2 bg-warning text-white flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                <i class="ti ti-lock fs-3"></i>
            </div>
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-1 text-dark">{{ __('Active Subscription Required') }}</h5>
                <p class="mb-0 text-muted fs-7">
                    {{ __('Your company does not currently have an active subscription. Please renew your current package or upgrade to a higher plan. Downgrade is not allowed.') }}
                </p>
            </div>
            @if(\Auth::user()->plan)
                <a href="{{ route('stripe', \Illuminate\Support\Facades\Crypt::encrypt(\Auth::user()->plan)) }}"
                    class="btn btn-warning text-dark fw-semibold flex-shrink-0">
                    <i class="ti ti-refresh me-1"></i>{{ __('Renew Current Plan') }}
                </a>
            @endif
        </div>
    </div>
@elseif(\Auth::user()->type == 'company' && \Auth::user()->isSubscriptionExpiringSoon(5))
    <div class="alert alert-warning border-0 shadow-sm rounded-4 p-3.5 mb-4 text-dark" style="background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); border-left: 5px solid #f97316 !important;">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle p-2 bg-warning text-dark flex-shrink-0 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                <i class="ti ti-calendar-due fs-3"></i>
            </div>
            <div class="flex-grow-1">
                <h5 class="fw-bold mb-1 text-dark">{{ __('Subscription Expiring Soon') }}</h5>
                <p class="mb-0 text-muted fs-7">
                    {{ __('Your plan expires on') }}
                    <strong>{{ \Auth::user()->dateFormat(\Auth::user()->plan_expire_date) }}</strong>
                    ({{ (int) \Auth::user()->daysUntilPlanExpiry() }} {{ __('day(s) left') }}).
                    {{ __('Renew now to keep the same expiry day next cycle, or upgrade to a higher plan.') }}
                </p>
            </div>
            <a href="{{ route('stripe', \Illuminate\Support\Facades\Crypt::encrypt(\Auth::user()->plan)) }}"
                class="btn btn-warning text-dark fw-semibold flex-shrink-0">
                <i class="ti ti-refresh me-1"></i>{{ __('Renew Current Plan') }}
            </a>
        </div>
    </div>
@endif

{{-- Organization Usage & Recommendation Banner --}}
@if(\Auth::user()->type == 'company' || \Auth::user()->type != 'super admin')
    <div class="capacity-overview-card shadow-sm">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-3 mb-lg-0">
                <div class="d-flex align-items-center">
                    <div class="rounded-3 p-3 bg-white text-primary shadow-sm me-3 border">
                        <i class="ti ti-building-community fs-2"></i>
                    </div>
                    <div>
                        <h5 class="mb-1 fw-bold text-dark">{{ __('Your Organization Capacity & Usage') }}</h5>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <span class="badge bg-white text-dark border px-2.5 py-1.5 fs-7 shadow-xs">
                                <i class="ti ti-users text-primary me-1"></i> <strong>{{ $activeEmployees ?? 0 }}</strong> {{ __('Active Employees') }}
                            </span>
                            <span class="badge bg-white text-dark border px-2.5 py-1.5 fs-7 shadow-xs">
                                <i class="ti ti-user-check text-info me-1"></i> <strong>{{ $activeUsers ?? 0 }}</strong> {{ __('Active Sub-Users') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                @if(!empty($suggestedPlan))
                    <div class="bg-white p-3 rounded-3 border border-primary shadow-sm d-flex align-items-center justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-primary text-white"><i class="ti ti-star me-1"></i>{{ __('Recommended For You') }}</span>
                                <strong class="text-dark fs-6">{{ $suggestedPlan->name }}</strong>
                            </div>
                            <small class="text-muted fs-8">
                                {{ __('Optimal capacity for up to ') }}
                                <strong>{{ $suggestedPlan->max_employees == -1 ? __('Unlimited') : $suggestedPlan->max_employees }}</strong> {{ __('employees') }} & 
                                <strong>{{ $suggestedPlan->max_users == -1 ? __('Unlimited') : $suggestedPlan->max_users }}</strong> {{ __('users') }}
                            </small>
                        </div>
                        <div class="text-end ps-3">
                            <span class="fw-bold text-primary fs-5">{{ env('CURRENCY_SYMBOL', '$') }}{{ number_format($suggestedPlan->price) }}</span>
                            <small class="text-muted d-block fs-8">/ {{ $suggestedPlan->duration }}</small>
                        </div>
                    </div>
                @else
                    <div class="bg-white p-3 rounded-3 border text-muted fs-7 d-flex align-items-center">
                        <i class="ti ti-circle-check text-success fs-3 me-2"></i>
                        <div>
                            <strong class="text-dark d-block">{{ __('Plan Capacity Healthy') }}</strong>
                            <small class="text-muted">{{ __('All current active employee and user limits are fully accommodated.') }}</small>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

{{-- Subscription Plan Cards Grid --}}
@php
    $needsRenew = \Auth::user()->type == 'company'
        && !\Auth::user()->isExemptFromPlanExpiry()
        && (\Auth::user()->isSubscriptionExpiringSoon(5) || !\Auth::user()->hasActiveSubscription());
@endphp
<div class="row g-4">
    @foreach ($plans as $plan)
        @php
            $isCompany = (\Auth::user()->type == 'company' || \Auth::user()->type != 'super admin');
            $isEligible = $plan->isEligibleForCompany(\Auth::user());
            $ineligibilityReason = $plan->getIneligibilityReason(\Auth::user());
            $isSuggested = (!empty($suggestedPlan) && $suggestedPlan->id == $plan->id && $isEligible);
            $isCurrentPlan = (\Auth::user()->type == 'company' && \Auth::user()->plan == $plan->id);
            $planExpireDate = \Auth::user()->plan_expire_date;
            $isDowngrade = (\Auth::user()->type == 'company' && \Auth::user()->isPlanDowngrade($plan));
            $canBuy = $isEligible && !$isDowngrade;
            $showRenewOnCard = $isCurrentPlan && $needsRenew && $plan->price > 0;
            $actionLabel = $showRenewOnCard
                ? __('Renew Now')
                : ($isCurrentPlan
                    ? __('Current Plan')
                    : ($isDowngrade ? __('Downgrade Locked') : __('Upgrade')));
            $nextExpirePreview = (\Auth::user()->type == 'company')
                ? \Auth::user()->calculateNextPlanExpireDate($plan)
                : null;
        @endphp

        <div class="col-xl-3 col-lg-4 col-md-6 plan-card-wrapper">
            <div class="modern-plan-card {{ $isSuggested ? 'is-suggested' : '' }} {{ $isCurrentPlan ? 'is-active-plan' : '' }} {{ (!$isEligible && $isCompany) ? 'is-ineligible' : '' }} {{ $isDowngrade ? 'is-downgrade-locked' : '' }}">
                
                {{-- Plan Header --}}
                <div class="plan-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="plan-badge-pill {{ $isSuggested ? 'bg-primary text-white' : ($isCurrentPlan ? 'bg-success text-white' : 'bg-light-primary text-primary') }}">
                            {{ $plan->name }}
                        </span>

                        <div class="d-flex align-items-center gap-2">
                            @if($isSuggested)
                                <span class="plan-status-badge plan-status-badge-recommended">
                                    <i class="ti ti-star me-1"></i>{{ __('Recommended') }}
                                </span>
                            @elseif($isCurrentPlan && $needsRenew)
                                <span class="plan-status-badge bg-warning text-dark">
                                    <i class="ti ti-alert-triangle me-1"></i>{{ \Auth::user()->hasActiveSubscription() ? __('Expiring Soon') : __('Expired') }}
                                </span>
                            @elseif($isCurrentPlan)
                                <span class="plan-status-badge plan-status-badge-active">
                                    <span class="pulse-dot me-1"></span>{{ __('Active Plan') }}
                                </span>
                            @endif

                            {{-- Super Admin ONLY: Edit Plan Button --}}
                            @if(\Auth::user()->type == 'super admin')
                                @can('Edit Plan')
                                    <a href="#" class="edit-plan-btn"
                                        data-ajax-popup="true" data-title="{{ __('Edit Plan') }}"
                                        data-url="{{ route('plans.edit', $plan->id) }}" data-size="lg" data-bs-toggle="tooltip"
                                        title="{{ __('Edit Plan Details') }}">
                                        <i class="ti ti-pencil fs-6"></i>
                                    </a>
                                @endcan
                            @endif
                        </div>
                    </div>

                    <div class="plan-price-box">
                        <span class="plan-price-currency">{{ env('CURRENCY_SYMBOL', '$') }}</span>
                        <span class="plan-price-amount">{{ number_format($plan->price) }}</span>
                        <span class="plan-price-duration">/ {{ $plan->duration }}</span>
                    </div>
                </div>

                {{-- Plan Body & Features --}}
                <div class="plan-body">
                    <div>
                        <p class="plan-description">
                            {{ $plan->description ?: __('Standard subscription package with full feature access.') }}
                        </p>

                        <ul class="plan-features-list">
                            <li class="plan-feature-item">
                                <span class="plan-feature-icon bg-light-primary text-primary">
                                    <i class="ti ti-users fs-5"></i>
                                </span>
                                <span>
                                    <strong>{{ $plan->max_users == -1 ? __('Unlimited') : number_format($plan->max_users) }}</strong> {{ __('Users Capacity') }}
                                </span>
                            </li>
                            <li class="plan-feature-item">
                                <span class="plan-feature-icon bg-light-info text-info">
                                    <i class="ti ti-user-check fs-5"></i>
                                </span>
                                <span>
                                    <strong>{{ $plan->max_employees == -1 ? __('Unlimited') : number_format($plan->max_employees) }}</strong> {{ __('Employees Capacity') }}
                                </span>
                            </li>
                            <li class="plan-feature-item">
                                <span class="plan-feature-icon bg-light-success text-success">
                                    <i class="ti ti-calendar-time fs-5"></i>
                                </span>
                                <span>
                                    {{ __('Billing Cycle:') }} <strong>{{ ucfirst($plan->duration) }}</strong>
                                </span>
                            </li>
                        </ul>
                    </div>

                    {{-- Capacity Exceeded Warning Box --}}
                    @if(!$isEligible && $isCompany)
                        <div class="alert alert-danger py-2 px-3 mb-0 fs-8 rounded-3 d-flex align-items-center mt-3" data-bs-toggle="tooltip" title="{{ $ineligibilityReason }}">
                            <i class="ti ti-alert-triangle text-danger fs-5 me-2 flex-shrink-0"></i>
                            <div>
                                <strong class="d-block">{{ __('Capacity Exceeded') }}</strong>
                                <span class="text-truncate d-block" style="max-width: 200px;">{{ $ineligibilityReason }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Plan Footer & Action Buttons --}}
                <div class="plan-footer">
                    <div class="row g-2 align-items-center">
                        {{-- Current plan: Renew only when expired or within 5 days --}}
                        @if($showRenewOnCard)
                            <div class="col-12">
                                <a href="{{ route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                    class="btn btn-warning text-dark btn-sm w-100 d-flex justify-content-center align-items-center py-2 fw-semibold shadow-xs"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="{{ __('Renew current package') }}">
                                    <i class="ti ti-refresh me-1"></i>
                                    {{ __('Renew Now') }}
                                </a>
                            </div>
                        @endif

                        {{-- Payment Gateway Subscribe / Upgrade (not used for current-plan renew) --}}
                        @if (!$isCurrentPlan && ((!empty($admin_payment_setting) &&
                            ($admin_payment_setting['is_stripe_enabled'] == 'on' ||
                                $admin_payment_setting['is_paypal_enabled'] == 'on' ||
                                $admin_payment_setting['is_paystack_enabled'] == 'on' ||
                                $admin_payment_setting['is_flutterwave_enabled'] == 'on' ||
                                $admin_payment_setting['is_razorpay_enabled'] == 'on' ||
                                $admin_payment_setting['is_mercado_enabled'] == 'on' ||
                                $admin_payment_setting['is_paytm_enabled'] == 'on' ||
                                $admin_payment_setting['is_mollie_enabled'] == 'on' ||
                                $admin_payment_setting['is_skrill_enabled'] == 'on' ||
                                $admin_payment_setting['is_coingate_enabled'] == 'on')) ||
                            (!empty($admin_payment_setting) && $admin_payment_setting['is_paymentwall_enabled'] == 'on')))
                            @if(\Auth::user()->can('Buy Plan') || \Auth::user()->type == 'company')
                                @if (\Auth::user()->type != 'super admin' && $plan->price > 0)
                                    <div class="col">
                                        @if($canBuy)
                                            <a href="{{ route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($plan->id)) }}"
                                                class="btn {{ $isSuggested ? 'btn-primary' : 'btn-outline-primary' }} btn-sm w-100 d-flex justify-content-center align-items-center py-2 shadow-xs"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Upgrade to ') . $plan->name }}">
                                                {{ __('Upgrade') }}
                                                <i class="ti ti-arrow-narrow-right ms-1"></i>
                                            </a>
                                        @elseif($isDowngrade)
                                            <button type="button" class="btn btn-secondary btn-sm w-100 d-flex justify-content-center align-items-center py-2 opacity-60"
                                                disabled data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Downgrade is not allowed. Renew your current plan or upgrade to a higher plan.') }}">
                                                <i class="ti ti-lock me-1"></i> {{ __('Downgrade Locked') }}
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-secondary btn-sm w-100 d-flex justify-content-center align-items-center py-2 opacity-60"
                                                disabled data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ $ineligibilityReason }}">
                                                <i class="ti ti-ban me-1"></i> {{ __('Unavailable') }}
                                            </button>
                                        @endif
                                    </div>
                                @elseif(\Auth::user()->type != 'super admin' && $plan->price <= 0)
                                    <div class="col">
                                        @if($isDowngrade)
                                            <button type="button" class="btn btn-secondary btn-sm w-100 opacity-60" disabled>
                                                <i class="ti ti-lock me-1"></i> {{ __('Downgrade Locked') }}
                                            </button>
                                        @else
                                            <span class="badge bg-light-secondary text-secondary w-100 py-2">{{ __('Free Tier') }}</span>
                                        @endif
                                    </div>
                                @endif
                            @endif
                        @endif

                        {{-- Manual Request to Super Admin Button --}}
                        @if (\Auth::user()->type == 'company' && \Auth::user()->plan != $plan->id)
                            @if ($plan->id != 1)
                                <div class="col-auto">
                                    @if (\Auth::user()->requested_plan != $plan->id)
                                        @if($canBuy)
                                            <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}"
                                                class="btn btn-outline-secondary btn-icon btn-sm py-2 px-2.5 shadow-xs"
                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ __('Send upgrade request to Super Admin') }}">
                                                <span class="btn-inner--icon"><i class="ti ti-send fs-6"></i></span>
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-secondary btn-icon btn-sm py-2 px-2.5 opacity-60"
                                                disabled data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ $isDowngrade ? __('Downgrade is not allowed') : $ineligibilityReason }}">
                                                <span class="btn-inner--icon"><i class="ti ti-ban fs-6"></i></span>
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('request.cancel', \Auth::user()->id) }}"
                                            class="btn btn-danger btn-icon btn-sm py-2 px-2.5 shadow-xs"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="{{ __('Cancel pending request') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-x fs-6"></i></span>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        @endif

                        {{-- Current Plan Status Info --}}
                        @if ($isCurrentPlan)
                            <div class="col-12">
                                <div class="{{ $needsRenew ? 'bg-warning text-dark' : 'bg-light-success text-success' }} p-2 rounded-3 fs-8 text-center fw-semibold">
                                    <i class="ti ti-calendar me-1"></i>
                                    {{ $needsRenew && !\Auth::user()->hasActiveSubscription() ? __('Expired:') : __('Expires:') }}
                                    {{ !empty($planExpireDate) ? \Auth::user()->dateFormat($planExpireDate) : __('Unlimited') }}
                                    @if($needsRenew && \Auth::user()->hasActiveSubscription())
                                        · {{ (int) \Auth::user()->daysUntilPlanExpiry() }} {{ __('day(s) left') }}
                                    @endif
                                </div>
                                @if($showRenewOnCard && $nextExpirePreview)
                                    <div class="bg-light-primary text-primary p-2 rounded-3 fs-8 text-center fw-semibold mt-1">
                                        <i class="ti ti-refresh me-1"></i>
                                        {{ __('After renew:') }} {{ \Auth::user()->dateFormat($nextExpirePreview) }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    @endforeach
</div>
@endsection
