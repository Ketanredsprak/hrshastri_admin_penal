@php
    $company_favicon = \App\Models\Utility::getValByName('company_favicon');
    $logo = \App\Models\Utility::get_file('uploads/logo');
    $company_logo = \App\Models\Utility::GetLogo();
    $SITE_RTL = \App\Models\Utility::getValByName('SITE_RTL');
    $setting = \App\Models\Utility::colorset();
    $color = !empty($setting['theme_color']) ? $setting['theme_color'] : 'theme-3';
    $isModernAuth = request()->routeIs('login', 'register', 'password.request');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">

<head>
    <title>
        {{ \App\Models\Utility::getValByName('title_text') ? \App\Models\Utility::getValByName('title_text') : config('app.name', 'HR Shastri') }}
        - @yield('page-title')
    </title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description"
        content="Secure login to your HR Shastri management portal. Manage employees, attendance, payroll, and more in one place." />
    <meta name="keywords"
        content="HR management login, employee portal, HR system, HR software, attendance system, payroll login" />
    <meta name="author" content="Hrshastri" />
    <meta name="robots" content="index, follow" />
    <meta property="og:title" content="Login | HR Shastri" />
    <meta property="og:description" content="Access your HR Shastri dashboard." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://app.hrshastri.com/login" />

    <link rel="icon" href="{{ $logo . '/favicon.png' }}" type="image/x-icon" />

    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/stylesheet.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">

    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif
    @if (($setting['cust_darklayout'] ?? '') == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif

    <link rel="stylesheet" href="{{ asset('css/auth-login.css') }}?v={{ @filemtime(public_path('css/auth-login.css')) }}">
</head>

<body class="{{ $color }} hs-auth-body {{ $isModernAuth ? 'hs-auth-login' : '' }}">
    <div class="hs-auth-shell">
        <header class="hs-auth-top">
            <div></div>
            <ul class="hs-auth-top-links">
                <li>
                    <a href="https://hrshastri.com/term-and-conditions/" target="_blank"
                        rel="noopener">{{ __('Terms') }}</a>
                </li>
                <li>
                    <a href="https://hrshastri.com/privacy-policy/" target="_blank"
                        rel="noopener">{{ __('Privacy') }}</a>
                </li>
                <li>
                    @yield('language-bar')
                </li>
            </ul>
        </header>

        @if ($isModernAuth)
            @yield('content')
        @else
            <div class="hs-auth-legacy">
                @yield('content')
            </div>
        @endif

        <footer class="hs-auth-footer">
            {{ __('Copyright') }} &copy;
            {{ \App\Models\Utility::getValByName('footer_text') ? \App\Models\Utility::getValByName('footer_text') : 'HR Shastri' }}
            {{ date('Y') }}
        </footer>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor-all.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script>
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>

    <input type="checkbox" class="d-none" id="cust-theme-bg"
        {{ \App\Models\Utility::getValByName('cust_theme_bg') == 'on' ? 'checked' : '' }} />
    <input type="checkbox" class="d-none" id="cust-darklayout"
        {{ \App\Models\Utility::getValByName('cust_darklayout') == 'on' ? 'checked' : '' }} />

    <script src="{{ asset('js/custom.js') }}"></script>
    @stack('script')
    @stack('custom-scripts')

    @if ($message = Session::get('success'))
        <script>
            show_toastr('Success', '{!! $message !!}', 'success');
        </script>
    @endif
    @if ($message = Session::get('error'))
        <script>
            show_toastr('Error', '{!! $message !!}', 'error');
        </script>
    @endif
</body>

</html>
