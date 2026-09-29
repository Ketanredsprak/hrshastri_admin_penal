@php
    $subUser = \Auth::user();
    $showExpiryAlert = $subUser
        && $subUser->type === 'company'
        && !$subUser->isExemptFromPlanExpiry()
        && ($subUser->isSubscriptionExpiringSoon(5) || !$subUser->hasActiveSubscription());
    $daysLeft = $subUser ? $subUser->daysUntilPlanExpiry() : null;
    $currentPlan = ($subUser && $subUser->plan) ? \App\Models\Plan::find($subUser->plan) : null;
    $expireLabel = !empty($subUser->plan_expire_date) ? $subUser->dateFormat($subUser->plan_expire_date) : null;
    $renewUrl = ($currentPlan && (float) $currentPlan->price > 0)
        ? route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($currentPlan->id))
        : route('plans.index');
    $isExpired = $subUser && !$subUser->hasActiveSubscription();
    $autoShowModal = $showExpiryAlert;
@endphp

@if($showExpiryAlert)
    <div class="subscription-expiry-bar {{ $isExpired ? 'is-expired' : 'is-warning' }}">
        <div class="subscription-expiry-bar-inner">
            <div class="subscription-expiry-bar-text">
                <i class="ti ti-alert-triangle"></i>
                @if($isExpired)
                    <strong>{{ __('Your Subscription Has Expired') }}</strong>
                    <span>
                        {{ __('Expired on') }} {{ $expireLabel }}.
                        {{ __('Renew your package to restore full access.') }}
                    </span>
                @else
                    <strong>{{ __('Your Subscription Is Expiring Soon') }}</strong>
                    <span>
                        {{ __('Expires on') }} {{ $expireLabel }}
                        ({{ (int) $daysLeft }} {{ __('day(s) left') }}).
                        {{ __('Renew now to keep the same expiry day next cycle.') }}
                    </span>
                @endif
            </div>
            <div class="subscription-expiry-bar-actions">
                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#subscriptionExpiryModal">
                    {{ __('Details') }}
                </button>
                <a href="{{ $renewUrl }}" class="btn btn-sm btn-warning text-dark fw-semibold">
                    <i class="ti ti-refresh"></i> {{ __('Renew Subscription') }}
                </a>
                <a href="{{ route('plans.index') }}" class="btn btn-sm btn-primary">
                    {{ __('See Plans / Upgrade') }}
                </a>
            </div>
        </div>
    </div>

    <div class="modal fade" id="subscriptionExpiryModal" tabindex="-1" aria-labelledby="subscriptionExpiryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header {{ $isExpired ? 'bg-danger text-white' : 'bg-warning' }}">
                    <h5 class="modal-title" id="subscriptionExpiryModalLabel">
                        <i class="ti ti-calendar-due me-1"></i>
                        {{ $isExpired ? __('Your Subscription Has Expired') : __('Your Subscription Is Expiring Soon') }}
                    </h5>
                    <button type="button" class="btn-close {{ $isExpired ? 'btn-close-white' : '' }}" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 {{ $isExpired ? 'bg-danger text-white' : 'bg-warning text-dark' }}"
                             style="width:48px;height:48px;">
                            <i class="ti ti-alert-triangle fs-3"></i>
                        </div>
                        <div>
                            <p class="mb-1">
                                <strong>{{ __('Current Plan') }}:</strong>
                                {{ $currentPlan->name ?? __('N/A') }}
                            </p>
                            <p class="mb-0">
                                <strong>{{ $isExpired ? __('Expired On') : __('Expires On') }}:</strong>
                                {{ $expireLabel ?? __('N/A') }}
                                @if(!$isExpired && $daysLeft !== null)
                                    <span class="badge bg-warning text-dark ms-1">{{ (int) $daysLeft }} {{ __('day(s) left') }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($isExpired)
                        <p class="text-muted mb-3">
                            {{ __('Your company subscription has expired. Please renew your current package or upgrade to a higher plan to continue using HR modules. Downgrade is not allowed.') }}
                        </p>
                    @else
                        <p class="text-muted mb-3">
                            {{ __('Your subscription will expire soon. Renew before the expiry date to keep the same billing day next cycle (example: if it ends on the 29th, the next cycle also ends on the 29th).') }}
                        </p>
                    @endif
                    <div class="alert alert-info mb-0 py-2">
                        <i class="ti ti-info-circle me-1"></i>
                        {{ __('You can renew the same package or upgrade. Downgrade is not allowed.') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Later') }}</button>
                    <a href="{{ route('plans.index') }}" class="btn btn-outline-primary">{{ __('See Subscription Plans') }}</a>
                    <a href="{{ $renewUrl }}" class="btn btn-warning text-dark fw-semibold">
                        <i class="ti ti-refresh me-1"></i>{{ __('Renew Now') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .subscription-expiry-bar {
            position: sticky;
            top: 0;
            z-index: 1040;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid rgba(0,0,0,.08);
        }
        .subscription-expiry-bar.is-warning {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            color: #9a3412;
        }
        .subscription-expiry-bar.is-expired {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            color: #991b1b;
        }
        .subscription-expiry-bar-inner {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        .subscription-expiry-bar-text {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.4rem 0.65rem;
            font-size: 0.92rem;
        }
        .subscription-expiry-bar-text i {
            font-size: 1.2rem;
        }
        .subscription-expiry-bar-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
        }
        body.no-sidebar .subscription-expiry-bar {
            margin-left: 0;
        }
        .crm-sub-dash-alert {
            border: 0;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.08);
        }
        .crm-sub-dash-alert.is-expired {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-left: 5px solid #dc2626 !important;
        }
        .crm-sub-dash-alert.is-warning {
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            border-left: 5px solid #f97316 !important;
        }
    </style>

    @if($autoShowModal)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                try {
                    var key = 'sub_expiry_modal_{{ $subUser->id }}_{{ $isExpired ? 'expired' : 'soon' }}_{{ $subUser->plan_expire_date }}';
                    if (!sessionStorage.getItem(key)) {
                        var el = document.getElementById('subscriptionExpiryModal');
                        if (el && window.bootstrap) {
                            var modal = new bootstrap.Modal(el);
                            modal.show();
                            sessionStorage.setItem(key, '1');
                        } else if (el && typeof $ !== 'undefined' && $.fn.modal) {
                            $(el).modal('show');
                            sessionStorage.setItem(key, '1');
                        }
                    }
                } catch (e) {}
            });
        </script>
    @endif
@endif
