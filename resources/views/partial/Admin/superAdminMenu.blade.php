@php

    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_logo = \App\Models\Utility::GetLogo();
    $users = \Auth::user();
    $profile = \App\Models\Utility::get_file('uploads/avatar/');
    $currantLang = $users->currentLanguage();
    $emailTemplate = App\Models\EmailTemplate::first();
@endphp


@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <nav class="dash-sidebar light-sidebar transprent-bg">
@else
    <nav class="dash-sidebar light-sidebar">
@endif

    <div class="navbar-wrapper">
        <div class="m-header main-logo">
            <a href="{{ route('home') }}" class="b-brand">
                <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}"
                    alt="{{ config('app.name', 'HRMGo') }}" class="logo logo-lg" style="height: 40px;">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="dash-navbar">

                <li class="dash-caption">
                    <label>{{ __('Overview') }}</label>
                </li>

                <li class="dash-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-home"></i></span><span class="dash-mtext">{{ __('Dashboard') }}
                        </span></a>
                </li>

                <li class="dash-caption">
                    <label>{{ __('Companies') }}</label>
                </li>

                <li class="dash-item {{ request()->is('user*') || request()->is('users*') ? 'active' : '' }}">
                    <a href="{{ route('user.index') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-building"></i></span><span
                            class="dash-mtext">{{ __('Company') }}</span></a>
                </li>

                <li class="dash-item {{ request()->is('mobile_menu*') || request()->routeIs('mobile_menu') ? 'active' : '' }}">
                    <a href="{{ route('mobile_menu') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-device-mobile"></i></span><span
                            class="dash-mtext">{{ __('Mobile Menu') }}</span></a>
                </li>

                <li class="dash-caption">
                    <label>{{ __('Billing') }}</label>
                </li>

                <li class="dash-item {{ request()->is('plans*') ? 'active' : '' }}">
                    <a href="{{ route('plans.index') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-trophy"></i></span><span
                            class="dash-mtext">{{ __('Plan') }}</span></a>
                </li>

                <li class="dash-item {{ request()->is('plan_request*') ? 'active' : '' }}">
                    <a href="{{ route('plan_request.index') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-arrow-down-right-circle"></i></span><span
                            class="dash-mtext">{{ __('Plan Request') }}</span></a>
                </li>

                @if (Gate::check('manage coupon'))
                    <li class="dash-item {{ request()->is('coupons*') ? 'active' : '' }}">
                        <a href="{{ route('coupons.index') }}" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-gift"></i></span><span class="dash-mtext">{{ __('Coupon') }}</span></a>
                    </li>
                @endif

                <li class="dash-item {{ request()->is('orders*') ? 'active' : '' }}">
                    <a href="{{ route('order.index') }}"
                        class="dash-link {{ request()->is('orders*') ? 'active' : '' }}"><span class="dash-micon"><i
                                class="ti ti-shopping-cart"></i></span><span
                            class="dash-mtext">{{ __('Order') }}</span></a>
                </li>

                <li class="dash-item {{ request()->routeIs('crm.invoices.*') || request()->is('crm/invoices*') ? 'active' : '' }}">
                    <a href="{{ route('crm.invoices.index') }}"
                        class="dash-link {{ request()->routeIs('crm.invoices.*') || request()->is('crm/invoices*') ? 'active' : '' }}"><span class="dash-micon"><i
                                class="ti ti-file-invoice"></i></span><span
                            class="dash-mtext">{{ __('Company Invoices') }}</span></a>
                </li>

                <li class="dash-caption">
                    <label>{{ __('System') }}</label>
                </li>

                <li class="dash-item {{ request()->is('roles*') ? 'active' : '' }}">
                    <a href="{{ route('role.index') }}"
                        class="dash-link {{ request()->is('roles*') ? 'active' : '' }}"><span class="dash-micon"><i
                                class="ti ti-shield-lock"></i></span><span
                            class="dash-mtext">{{ __('Role') }}</span></a>
                </li>

                <li
                    class="dash-item {{ (Request::route() && (Request::route()->getName() == 'email_template.show' || Request::route()->getName() == 'manageemail.lang')) || Request::segment(1) == 'email_template_lang' ? 'active' : '' }}">
                    <a href="{{ route('manage.email.language', [$emailTemplate->id, \Auth::user()->lang]) }}"
                        class="dash-link"><span class="dash-micon"><i class="ti ti-mail"></i></span><span
                            class="dash-mtext">{{ __('Email Templates') }}</span></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script type="text/javascript">
    $('.patch_link').on("click", function () {
        setTimeout(function () {
            $('.patch_submenu').removeAttr("style");
            $('.patch_submenu').css('display', 'block');
        }, 200);
    });
</script>
