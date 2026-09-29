@extends('layouts.auth')

@section('page-title')
    {{ __('Login') }}
@endsection

@php
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_logo = \App\Models\Utility::GetLogo();
    $logoFile = isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png';
@endphp

@push('custom-scripts')
    @if (env('RECAPTCHA_MODULE') == 'yes')
        {!! NoCaptcha::renderJs() !!}
    @endif
@endpush

@section('language-bar')
    <select name="language" id="language" class="hs-lang-select"
        onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
        @foreach (\App\Models\Utility::languages() as $language)
            <option @if ($lang == $language) selected @endif value="{{ route('login', $language) }}">
                {{ Str::upper($language) }}
            </option>
        @endforeach
    </select>
@endsection

@section('content')
    <main class="hs-login">
        <section class="hs-login-hero" aria-label="{{ __('HR Shastri') }}">
            <div class="hs-login-orb hs-login-orb--a" aria-hidden="true"></div>
            <div class="hs-login-orb hs-login-orb--b" aria-hidden="true"></div>

            <div class="hs-login-hero-inner">
                <div class="hs-brand-mark">
                    <img src="{{ $logo . '/' . $logoFile }}" alt="HR Shastri"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <span class="hs-brand-fallback" style="display:none;">HR <span>Shastri</span></span>
                </div>
                <p class="hs-lead">
                    {{ __('People, payroll, and attendance — one calm workplace for your whole team.') }}
                </p>
            </div>
        </section>

        <section class="hs-login-panel">
            <div class="hs-login-panel-card">
                <h2>{{ __('Welcome back') }}</h2>
                <p class="hs-sub">{{ __('Sign in to continue to your dashboard.') }}</p>

                @if (session()->has('message'))
                    <div class="hs-alert hs-alert-danger">{{ session()->get('message') }}</div>
                @endif
                @if (session()->has('error'))
                    <div class="hs-alert hs-alert-danger">{{ session()->get('error') }}</div>
                @endif
                @if (session()->has('success'))
                    <div class="hs-alert hs-alert-success">{{ session()->get('success') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate id="form_data">
                    @csrf

                    <div class="hs-field">
                        <label for="email">{{ __('Email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            autocomplete="email" autofocus placeholder="{{ __('Enter your email') }}"
                            class="@error('email') is-invalid @enderror">
                        @error('email')
                            <span class="hs-field-error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="hs-field">
                        <label for="password">{{ __('Password') }}</label>
                        <div class="hs-password-wrap">
                            <input id="password" type="password" name="password" required
                                autocomplete="current-password" placeholder="{{ __('Enter your password') }}"
                                class="enterpassword @error('password') is-invalid @enderror">
                            <button type="button" class="hs-toggle-pass icon_password" aria-label="{{ __('Show password') }}">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="hs-field-error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>

                    @if (Route::has('password.request'))
                        <div class="hs-field-row">
                            <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                        </div>
                    @endif

                    @if (env('RECAPTCHA_MODULE') == 'yes')
                        <div class="hs-field">
                            {!! NoCaptcha::display() !!}
                            @error('g-recaptcha-response')
                                <span class="hs-field-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <button type="submit" class="hs-btn-login login-do-btn" id="login_button" tabindex="4">
                        {{ __('Login') }}
                    </button>

                    @if (\App\Models\Utility::getValByName('disable_signup_button') == 'on')
                        <p class="hs-auth-meta">
                            {{ __("Don't have an account?") }}
                            <a href="{{ route('register', $lang) }}">{{ __('Register') }}</a>
                        </p>
                    @endif
                </form>

                @if (app()->environment('local'))
                    <div class="hs-dev-box">
                        <div class="hs-dev-box-head">
                            <span class="hs-dev-badge"><i class="ti ti-terminal"></i> Local only</span>
                            <span class="hs-dev-badge" style="background:#ede9fe;color:#51459d;">1-click login</span>
                        </div>
                        <p>{{ __('Switch instantly to a testing role:') }}</p>
                        <div class="hs-dev-grid">
                            <form method="POST" action="{{ route('local.quick.login') }}">
                                @csrf
                                <input type="hidden" name="role" value="Super Admin">
                                <button type="submit" class="hs-dev-btn" title="superadmin@redspark.com">
                                    <strong>Super Admin</strong>
                                    <small>superadmin@redspark.com</small>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('local.quick.login') }}">
                                @csrf
                                <input type="hidden" name="role" value="Company">
                                <button type="submit" class="hs-dev-btn" title="company@redspark.com">
                                    <strong>Company</strong>
                                    <small>company@redspark.com</small>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('local.quick.login') }}">
                                @csrf
                                <input type="hidden" name="role" value="Employee">
                                <button type="submit" class="hs-dev-btn" title="viral@redsparkinfo.co.in">
                                    <strong>Employee</strong>
                                    <small>viral@redsparkinfo.co.in</small>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.icon_password').on('click', function() {
                var $input = $('.enterpassword');
                var showing = $input.attr('type') === 'text';
                $input.attr('type', showing ? 'password' : 'text');
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });

            $('#form_data').on('submit', function() {
                $('#login_button').prop('disabled', true);
            });
        });
    </script>
@endpush
