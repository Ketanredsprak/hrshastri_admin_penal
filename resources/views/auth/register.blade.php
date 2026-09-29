@extends('layouts.auth')

@section('page-title')
    {{ __('Register') }}
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
            <option @if (($lang ?? 'en') == $language) selected @endif value="{{ route('register', $language) }}">
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
                    {{ __('Create your workspace and start managing people with clarity.') }}
                </p>
            </div>
        </section>

        <section class="hs-login-panel">
            <div class="hs-login-panel-card">
                <h2>{{ __('Create account') }}</h2>
                <p class="hs-sub">{{ __('Register to get started with HR Shastri.') }}</p>

                @if (session('status'))
                    <div class="hs-alert hs-alert-danger">
                        {{ __('Email SMTP settings does not configured so please contact to your site admin.') }}
                    </div>
                @endif

                {{ Form::open(['route' => 'register', 'method' => 'post', 'id' => 'loginForm']) }}

                <div class="hs-field">
                    <label for="name">{{ __('Full Name') }}</label>
                    {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => __('Enter your full name')]) }}
                    @error('name')
                        <span class="hs-field-error" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="hs-field">
                    <label for="email">{{ __('Email') }}</label>
                    {{ Form::text('email', null, ['id' => 'email', 'class' => 'form-control', 'placeholder' => __('Enter your email')]) }}
                    @error('email')
                        <span class="hs-field-error" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="hs-field">
                    <label for="input-password">{{ __('Password') }}</label>
                    {{ Form::password('password', ['class' => 'form-control', 'id' => 'input-password', 'placeholder' => __('Create a password')]) }}
                    @error('password')
                        <span class="hs-field-error" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="hs-field">
                    <label for="confirm-input-password">{{ __('Confirm password') }}</label>
                    {{ Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'confirm-input-password', 'placeholder' => __('Confirm your password')]) }}
                    @error('password_confirmation')
                        <span class="hs-field-error" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                @if (env('RECAPTCHA_MODULE') == 'yes')
                    <div class="hs-field">
                        {!! NoCaptcha::display() !!}
                        @error('g-recaptcha-response')
                            <span class="hs-field-error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                <button type="submit" class="hs-btn-login">{{ __('Register') }}</button>

                <p class="hs-auth-meta">
                    {{ __('Already have an account?') }}
                    <a href="{{ route('login', $lang ?? 'en') }}">{{ __('Login') }}</a>
                </p>

                {{ Form::close() }}
            </div>
        </section>
    </main>
@endsection
