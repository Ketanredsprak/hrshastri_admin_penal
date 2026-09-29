@php
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_favicon = \App\Models\Utility::getValByName('company_favicon');
    $setting = \App\Models\Utility::colorset();
    $color = !empty($setting['theme_color']) ? $setting['theme_color'] : 'theme-3';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('page-title') - HRMS Subscription Sync</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .sync-navbar {
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            padding: 12px 24px;
        }
        .sync-brand {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a2e;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    {{-- Standalone Header --}}
    <header class="sync-navbar d-flex justify-content-between align-items-center mb-4 sticky-top">
        <div class="sync-brand">
            <span class="badge bg-primary p-2"><i class="ti ti-refresh fs-4"></i></span>
            <div>
                <span class="fw-bold text-dark">HRMS</span>
                <span class="text-primary fs-7 ms-1">| Subscription Limits & Sync Hub</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if(\Auth::check())
                <span class="badge bg-light-primary text-primary px-3 py-2">
                    <i class="ti ti-user me-1"></i> {{ \Auth::user()->name }} ({{ \Auth::user()->type }})
                </span>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-dashboard me-1"></i> {{ __('Admin Panel') }}
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-login me-1"></i> {{ __('Login to HRMS') }}
                </a>
            @endif
        </div>
    </header>

    {{-- Main Content Container --}}
    <main class="container-fluid px-4 pb-5">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h4 class="mb-1 fw-bold text-dark">@yield('page-title')</h4>
                <nav aria-label="breadcrumb" class="mt-1">
                    <ol class="breadcrumb mb-0 fs-7 py-1">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center flex-wrap gap-1">
                @yield('action-button')
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-check-circle fs-4 me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ti ti-alert-triangle fs-4 me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-center text-muted py-3 fs-8 border-top bg-white">
        HRMS Subscription Management Engine &copy; {{ date('Y') }}
    </footer>

    <!-- Bootstrap & Vendor Scripts -->
    <script src="{{ asset('assets/js/vendor-all.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script>
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>
</body>
</html>
