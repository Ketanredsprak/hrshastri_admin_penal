@extends('layouts.auth')

@section('page-title')
    {{ __('Forgot Password') }}
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
            <option @if (($lang ?? 'en') == $language) selected @endif
                value="{{ route('password.request', $language) }}">
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
                    {{ __('Reset access securely and get back to your workplace in minutes.') }}
                </p>
            </div>
        </section>

        <section class="hs-login-panel">
            <div class="hs-login-panel-card">
                <h2>{{ __('Forgot password') }}</h2>
                <p class="hs-sub">{{ __('Enter your email and we will send a reset link.') }}</p>

                @if (session('status'))
                    <div class="hs-alert hs-alert-success">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" id="form_data">
                    @csrf

                    <div class="hs-field">
                        <label for="email">{{ __('Email') }}</label>
                        <input id="email" type="email"
                            class="@error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}"
                            placeholder="{{ __('Enter your email') }}" autocomplete="email" autofocus required>
                        @foreach ($errors->all() as $error)
                            <span class="hs-field-error" role="alert">{{ $error }}</span>
                        @endforeach
                    </div>

                    @if (env('RECAPTCHA_MODULE') == 'yes')
                        <div class="hs-field">
                            {!! NoCaptcha::display() !!}
                            @error('g-recaptcha-response')
                                <span class="hs-field-error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <button type="submit" class="hs-btn-login btn-submit">
                        {{ __('Send Password Reset Link') }}
                    </button>

                    <p class="hs-auth-meta">
                        {{ __('Back to') }}
                        <a href="{{ route('login', $lang ?? 'en') }}">{{ __('Login') }}</a>
                    </p>
                </form>
            </div>
        </section>
    </main>
@endsection
