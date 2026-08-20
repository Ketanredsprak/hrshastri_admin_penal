@extends('layouts.admin')
@section('page-title')
    {{ __('Settings') }}
@endsection
@php
    // $logo = asset(Storage::url('uploads/logo/'));
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_logo = \App\Models\Utility::getValByName('company_logo');
    $company_logo_light = \App\Models\Utility::getValByName('company_logo_light');
    $company_favicon = \App\Models\Utility::getValByName('company_favicon');
    $color = isset($settings['theme_color']) ? $settings['theme_color'] : 'theme-4';

    $settings = App\Models\Utility::settings();

    $currantLang = \App\Models\Utility::languages();
    $SITE_RTL = \App\Models\Utility::getValByName('SITE_RTL');
@endphp



@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection

@push('script-page')
    <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>

    <script>
        if ($(".pc-tinymce-2").length) {
            tinymce.init({
                selector: '.pc-tinymce-2',
                height: "400",
                content_style: 'body { font-family: "Inter", sans-serif; }'
            });
        }
    </script>
    <script>
        if ($(".pc-tinymce-3").length) {
            tinymce.init({
                selector: '.pc-tinymce-3',
                height: "400",
                content_style: 'body { font-family: "Inter", sans-serif; }'
            });
        }
    </script>
    <script>
        if ($(".pc-tinymce-4").length) {
            tinymce.init({
                selector: '.pc-tinymce-4',
                height: "400",
                content_style: 'body { font-family: "Inter", sans-serif; }'
            });
        }
    </script>
    <script>
        if ($(".pc-tinymce-5").length) {
            tinymce.init({
                selector: '.pc-tinymce-5',
                height: "400",
                content_style: 'body { font-family: "Inter", sans-serif; }'
            });
        }
    </script>
    <script>
        $(document).on('change', '.email-template-checkbox', function() {
            var url = $(this).data('url');
            var chbox = $(this);

            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    status: chbox.val()
                },
                success: function(data) {

                },
            });
        });
    </script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })

        $('.themes-color-change').on('click', function() {
            var color_val = $(this).data('value');
            $('.theme-color').prop('checked', false);
            $('.themes-color-change').removeClass('active_color');
            $(this).addClass('active_color');
            $(`input[value=${color_val}]`).prop('checked', true);

        });
    </script>
    <script>
        document.getElementById('company_logo').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image').src = src
        }
    </script>
    <script>
        document.getElementById('company_logo_light').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image1').src = src
        }
    </script>
    <script>
        document.getElementById('company_favicon').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('image2').src = src
        }
    </script>
@endpush
@section('content')
    <div class="row">

        <div class="col-sm-12">
            <div class="row">
                {{--<div class="col-xl-3">
                    <div class="card sticky-top">
                        <div class="list-group list-group-flush" id="useradd-sidenav">





                            <a href="#offer-letter-settings" id="offer-letter-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Offer Letter Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#joining-letter-settings" id="joining-letter-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Joining Letter Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#increment-letter-settings" id="joining-letter-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Increment Letter Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#Relieving-letter-settings" id="joining-letter-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Relieving Lette') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>





                            <a href="#experience-certificate-settings" id="experience-certificate-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('Certificate of Experience Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>

                            <a href="#noc-settings" id="noc-tab"
                                class="list-group-item list-group-item-action border-0">{{ __('No Objection Certificate Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>


                        </div>

                    </div>
                </div>--}}

                <div class="col-xl-12">
                    <div class="" id="business-settings">
                        {{ Form::model($settings, ['route' => 'business.setting', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-md-12">

                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>

                    <div class="" id="system-settings">

                    </div>

                    <div class="" id="company-settings">

                    </div>

                                                                    <div class="" id="offer-letter-settings">
                                                                <div class="card">
                                                                    <div
                                                                        class="card-header d-flex justify-content-between">
                                                                        <h5>{{ __('Offer Letter Settings') }}</h5>
                                                                        <div
                                                                            class="d-flex justify-content-end drp-languages">
                                                                            <ul class="list-unstyled mb-0 m-2">
                                                                                <li class="dropdown dash-h-item drp-language"
                                                                                    style="margin-top: -19px;">
                                                                                    <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                                                                        data-bs-toggle="dropdown"
                                                                                        href="#" role="button"
                                                                                        aria-haspopup="false"
                                                                                        aria-expanded="false"
                                                                                        id="dropdownLanguage">
                                                                                        <span
                                                                                            class="drp-text hide-mob text-primary">
                                                                                            {{ Str::upper($offerlang) }}
                                                                                        </span>
                                                                                        <i
                                                                                            class="ti ti-chevron-down drp-arrow nocolor"></i>
                                                                                    </a>
                                                                                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                                                                        aria-labelledby="dropdownLanguage">
                                                                                        @foreach ($currantLang as $offerlangs)
                                                                                            <a href="{{ route('get.offerlatter.language', ['noclangs' => $noclang, 'explangs' => $explang, 'offerlangs' => $offerlangs, 'joininglangs' => $joininglang]) }}"
                                                                                                class="dropdown-item ms-1 {{ $offerlangs == $offerlang ? 'text-primary' : '' }}">{{ Str::upper($offerlangs) }}</a>

                                                                                        @endforeach
                                                                                    </div>
                                                                                </li>
                                                                            </ul>

                                                                        </div>
                                                                    </div>
                                                                    <div class="card-body ">
                                                                        <h5 class="font-weight-bold pb-3">
                                                                            {{ __('Placeholders') }}</h5>

                                                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                                                            <div class="card">
                                                                                <div class="card-header card-body">
                                                                                    <div class="row text-xs">
                                                                                        <div class="row">
                                                                                            <p class="col-4">
                                                                                                {{ __('Applicant Name') }}
                                                                                                : <span
                                                                                                    class="pull-end text-primary">{applicant_name}</span>
                                                                                            </p>
                                                                                            <p class="col-4">
                                                                                                {{ __('Company Name') }} :
                                                                                                <span
                                                                                                    class="pull-right text-primary">{app_name}</span>
                                                                                            </p>
                                                                                            <p class="col-4">
                                                                                                {{ __('Job title') }} :
                                                                                                <span
                                                                                                    class="pull-right text-primary">{job_title}</span>
                                                                                            </p>
                                                                                            <p class="col-4">
                                                                                                {{ __('Job type') }} :
                                                                                                <span
                                                                                                    class="pull-right text-primary">{job_type}</span>
                                                                                            </p>
                                                                                            <p class="col-4">
                                                                                                {{ __('Proposed Start Date') }}
                                                                                                : <span
                                                                                                    class="pull-right text-primary">{start_date}</span>
                                                                                            </p>
                                                                                            <p class="col-4">
                                                                                                {{ __('Working Location') }}
                                                                                                : <span
                                                                                                    class="pull-right text-primary">{workplace_location}</span>
                                                                                            </p>
                                                                                            <p class="col-4">
                                                                                                {{ __('Days Of Week') }} :
                                                                                                <span
                                                                                                    class="pull-right text-primary">{days_of_week}</span>
                                                                                            </p>
                                                                                            <p class="col-4">
                                                                                                {{ __('Salary') }} :
                                                                                                <span
                                                                                                    class="pull-right text-primary">{salary}</span>
                                                                                            </p>
                                                                                            <p class="col-4">
                                                                                                {{ __('Salary Type') }} :
                                                                                                <span
                                                                                                    class="pull-right text-primary">{salary_type}</span>
                                                                                            </p>
                                                                                            <p class="col-4">
                                                                                                {{ __('Salary Duration') }}
                                                                                                : <span
                                                                                                    class="pull-end text-primary">{salary_duration}</span>
                                                                                            </p>
                                                                                            <p class="col-4">
                                                                                                {{ __('Offer Expiration Date') }}
                                                                                                : <span
                                                                                                    class="pull-right text-primary">{offer_expiration_date}</span>
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-body table-border-style ">

                                                                        {{ Form::open(['route' => ['offerlatter.update', $offerlang], 'method' => 'post']) }}
                                                                        <div class="form-group col-12">
                                                                            {{ Form::label('content', __(' Format'), ['class' => 'form-label text-dark']) }}
                                                                            <textarea name="content" class="pc-tinymce-2">{!! isset($currOfferletterLang->content) ? $currOfferletterLang->content : '' !!}</textarea>
                                                                        </div>
                                                                        <div class="card-footer text-end">

                                                                            {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                                                                        </div>

                                                                        {{ Form::close() }}
                                                                    </div>
                                                                </div>
                                                        </div>

                                                        <div class="" id="joining-letter-settings">
                                                            <div class="card">
                                                                <div class="card-header d-flex justify-content-between">
                                                                    <h5>{{ __('Joining Letter Settings') }}</h5>
                                                                    <div class="d-flex justify-content-end drp-languages">
                                                                        <ul class="list-unstyled mb-0 m-2">
                                                                            <li class="dropdown dash-h-item drp-language"
                                                                                style="margin-top: -19px;">
                                                                                <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                                                                    data-bs-toggle="dropdown"
                                                                                    href="#" role="button"
                                                                                    aria-haspopup="false"
                                                                                    aria-expanded="false"
                                                                                    id="dropdownLanguage1">
                                                                                    <span
                                                                                        class="drp-text hide-mob text-primary">

                                                                                        {{ Str::upper($joininglang) }}
                                                                                    </span>
                                                                                    <i
                                                                                        class="ti ti-chevron-down drp-arrow nocolor"></i>
                                                                                </a>
                                                                                <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                                                                    aria-labelledby="dropdownLanguage1">
                                                                                    @foreach ($currantLang as $joininglangs)
                                                                                        <a href="{{ route('get.joiningletter.language', ['noclangs' => $noclang, 'explangs' => $explang, 'offerlangs' => $offerlang, 'joininglangs' => $joininglangs]) }}"
                                                                                            class="dropdown-item {{ $joininglangs == $joininglang ? 'text-primary' : '' }}">{{ Str::upper($joininglangs) }}</a>
                                                                                    @endforeach
                                                                                </div>
                                                                            </li>

                                                                        </ul>
                                                                    </div>

                                                                </div>
                                                                <div class="card-body ">
                                                                    <h5 class="font-weight-bold pb-3">
                                                                        {{ __('Placeholders') }}</h5>

                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <div class="card">
                                                                            <div class="card-header card-body">
                                                                                <div class="row text-xs">
                                                                                    <div class="row">
                                                                                        <p class="col-4">
                                                                                            {{ __('Applicant Name') }} :
                                                                                            <span
                                                                                                class="pull-end text-primary">{date}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Company Name') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{app_name}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Employee Name') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{employee_name}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Address') }} : <span
                                                                                                class="pull-right text-primary">{address}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Designation') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{designation}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Start Date') }} : <span
                                                                                                class="pull-right text-primary">{start_date}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Branch') }} : <span
                                                                                                class="pull-right text-primary">{branch}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Start Time') }} : <span
                                                                                                class="pull-end text-primary">{start_time}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('End Time') }} : <span
                                                                                                class="pull-right text-primary">{end_time}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Number of Hours') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{total_hours}</span>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body table-border-style ">

                                                                    {{ Form::open(['route' => ['joiningletter.update', $joininglang], 'method' => 'post']) }}
                                                                    <div class="form-group col-12">
                                                                        {{ Form::label('content', __(' Format'), ['class' => 'form-label text-dark']) }}
                                                                        <textarea name="content" class="pc-tinymce-3">{!! isset($currjoiningletterLang->content) ? $currjoiningletterLang->content : '' !!}</textarea>



                                                                    </div>

                                                                    <div class="card-footer text-end">

                                                                        {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                                                                    </div>

                                                                    {{ Form::close() }}
                                                                </div>



                                                            </div>
                                                        </div>
                                                        {{-- //Increment Letter --}}

                                                        <div class="" id="increment-letter-settings">
                                                            <div class="card">
                                                                <div
                                                                    class="card-header d-flex justify-content-between">
                                                                    <h5>{{ __('Increment Letter Settings') }}</h5>
                                                                    <div
                                                                        class="d-flex justify-content-end drp-languages">
                                                                        <ul class="list-unstyled mb-0 m-2">
                                                                            <li class="dropdown dash-h-item drp-language"
                                                                                style="margin-top: -19px;">
                                                                                <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                                                                    data-bs-toggle="dropdown"
                                                                                    href="#" role="button"
                                                                                    aria-haspopup="false"
                                                                                    aria-expanded="false"
                                                                                    id="dropdownLanguage">
                                                                                    <span
                                                                                        class="drp-text hide-mob text-primary">
                                                                                        {{ Str::upper($offerlang) }}
                                                                                    </span>
                                                                                    <i
                                                                                        class="ti ti-chevron-down drp-arrow nocolor"></i>
                                                                                </a>
                                                                                <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                                                                    aria-labelledby="dropdownLanguage">
                                                                                    @foreach ($currantLang as $offerlangs)
                                                                                        <a href="{{ route('get.offerlatter.language', ['noclangs' => $noclang, 'explangs' => $explang, 'offerlangs' => $offerlangs, 'joininglangs' => $joininglang]) }}"
                                                                                            class="dropdown-item ms-1 {{ $offerlangs == $offerlang ? 'text-primary' : '' }}">{{ Str::upper($offerlangs) }}</a>

                                                                                    @endforeach
                                                                                </div>
                                                                            </li>
                                                                        </ul>

                                                                    </div>
                                                                </div>
                                                                <div class="card-body ">
                                                                    <h5 class="font-weight-bold pb-3">
                                                                        {{ __('Placeholders') }}</h5>

                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <div class="card">
                                                                            <div class="card-header card-body">
                                                                                <div class="row text-xs">
                                                                                    <div class="row">
                                                                                        <p class="col-4">
                                                                                            {{ __('Applicant Name') }}
                                                                                            : <span
                                                                                                class="pull-end text-primary">{applicant_name}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Company Name') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{app_name}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Job title') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{job_title}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Job type') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{job_type}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Proposed Start Date') }}
                                                                                            : <span
                                                                                                class="pull-right text-primary">{start_date}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Working Location') }}
                                                                                            : <span
                                                                                                class="pull-right text-primary">{workplace_location}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Days Of Week') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{days_of_week}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Salary') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{salary}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Salary Type') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{salary_type}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Salary Duration') }}
                                                                                            : <span
                                                                                                class="pull-end text-primary">{salary_duration}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Offer Expiration Date') }}
                                                                                            : <span
                                                                                                class="pull-right text-primary">{offer_expiration_date}</span>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body table-border-style ">

                                                                    {{ Form::open(['route' => ['incrementlatter.update', $offerlang], 'method' => 'post']) }}
                                                                    <div class="form-group col-12">
                                                                        {{ Form::label('content', __(' Format'), ['class' => 'form-label text-dark']) }}
                                                                        <textarea name="content" class="pc-tinymce-2">{!! isset($IncrementLetterLang->content) ? $IncrementLetterLang->content : '' !!}</textarea>
                                                                    </div>
                                                                    <div class="card-footer text-end">

                                                                        {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                                                                    </div>

                                                                    {{ Form::close() }}
                                                                </div>
                                                            </div>
                                                    </div>

                                                    {{-- //Relieving-letter-settings --}}
                                                    <div class="" id="Relieving-letter-settings">
                                                        <div class="card">
                                                            <div
                                                                class="card-header d-flex justify-content-between">
                                                                <h5>{{ __('Relieving letter Settings') }}</h5>
                                                                <div
                                                                    class="d-flex justify-content-end drp-languages">
                                                                    <ul class="list-unstyled mb-0 m-2">
                                                                        <li class="dropdown dash-h-item drp-language"
                                                                            style="margin-top: -19px;">
                                                                            <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                                                                data-bs-toggle="dropdown"
                                                                                href="#" role="button"
                                                                                aria-haspopup="false"
                                                                                aria-expanded="false"
                                                                                id="dropdownLanguage">
                                                                                <span
                                                                                    class="drp-text hide-mob text-primary">
                                                                                    {{ Str::upper($offerlang) }}
                                                                                </span>
                                                                                <i
                                                                                    class="ti ti-chevron-down drp-arrow nocolor"></i>
                                                                            </a>
                                                                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                                                                aria-labelledby="dropdownLanguage">
                                                                                @foreach ($currantLang as $offerlangs)
                                                                                    <a href="{{ route('get.offerlatter.language', ['noclangs' => $noclang, 'explangs' => $explang, 'offerlangs' => $offerlangs, 'joininglangs' => $joininglang]) }}"
                                                                                        class="dropdown-item ms-1 {{ $offerlangs == $offerlang ? 'text-primary' : '' }}">{{ Str::upper($offerlangs) }}</a>

                                                                                @endforeach
                                                                            </div>
                                                                        </li>
                                                                    </ul>

                                                                </div>
                                                            </div>
                                                            <div class="card-body ">
                                                                <h5 class="font-weight-bold pb-3">
                                                                    {{ __('Placeholders') }}</h5>

                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="card">
                                                                        <div class="card-header card-body">
                                                                            <div class="row text-xs">
                                                                                <div class="row">
                                                                                    <p class="col-4">
                                                                                        {{ __('Applicant Name') }}
                                                                                        : <span
                                                                                            class="pull-end text-primary">{applicant_name}</span>
                                                                                    </p>
                                                                                    <p class="col-4">
                                                                                        {{ __('Company Name') }} :
                                                                                        <span
                                                                                            class="pull-right text-primary">{app_name}</span>
                                                                                    </p>
                                                                                    <p class="col-4">
                                                                                        {{ __('Job title') }} :
                                                                                        <span
                                                                                            class="pull-right text-primary">{job_title}</span>
                                                                                    </p>
                                                                                    <p class="col-4">
                                                                                        {{ __('Job type') }} :
                                                                                        <span
                                                                                            class="pull-right text-primary">{job_type}</span>
                                                                                    </p>
                                                                                    <p class="col-4">
                                                                                        {{ __('Proposed Start Date') }}
                                                                                        : <span
                                                                                            class="pull-right text-primary">{start_date}</span>
                                                                                    </p>
                                                                                    <p class="col-4">
                                                                                        {{ __('Working Location') }}
                                                                                        : <span
                                                                                            class="pull-right text-primary">{workplace_location}</span>
                                                                                    </p>
                                                                                    <p class="col-4">
                                                                                        {{ __('Days Of Week') }} :
                                                                                        <span
                                                                                            class="pull-right text-primary">{days_of_week}</span>
                                                                                    </p>
                                                                                    <p class="col-4">
                                                                                        {{ __('Salary') }} :
                                                                                        <span
                                                                                            class="pull-right text-primary">{salary}</span>
                                                                                    </p>
                                                                                    <p class="col-4">
                                                                                        {{ __('Salary Type') }} :
                                                                                        <span
                                                                                            class="pull-right text-primary">{salary_type}</span>
                                                                                    </p>
                                                                                    <p class="col-4">
                                                                                        {{ __('Salary Duration') }}
                                                                                        : <span
                                                                                            class="pull-end text-primary">{salary_duration}</span>
                                                                                    </p>
                                                                                    <p class="col-4">
                                                                                        {{ __('Offer Expiration Date') }}
                                                                                        : <span
                                                                                            class="pull-right text-primary">{offer_expiration_date}</span>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-body table-border-style ">

                                                                {{ Form::open(['route' => ['relievingtlatter.update', $offerlang], 'method' => 'post']) }}
                                                                <div class="form-group col-12">
                                                                    {{ Form::label('content', __(' Format'), ['class' => 'form-label text-dark']) }}
                                                                    <textarea name="content" class="pc-tinymce-2">{!! isset($RelievingLetterLang->content) ? $RelievingLetterLang->content : '' !!}</textarea>
                                                                </div>
                                                                <div class="card-footer text-end">

                                                                    {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                                                                </div>

                                                                {{ Form::close() }}
                                                            </div>
                                                        </div>
                                                </div>


                                                        <div class="" id="experience-certificate-settings">
                                                            <div class="card">
                                                                <div class="card-header d-flex justify-content-between">
                                                                    <h5>{{ __('Certificate of Experience Settings') }}
                                                                    </h5>
                                                                    <div class="d-flex justify-content-end drp-languages">
                                                                        <ul class="list-unstyled mb-0 m-2">
                                                                            <li class="dropdown dash-h-item drp-language"
                                                                                style="margin-top: -19px;">
                                                                                <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                                                                    data-bs-toggle="dropdown"
                                                                                    href="#" role="button"
                                                                                    aria-haspopup="false"
                                                                                    aria-expanded="false"
                                                                                    id="dropdownLanguage1">
                                                                                    <span
                                                                                        class="drp-text hide-mob text-primary">

                                                                                        {{ Str::upper($explang) }}
                                                                                    </span>
                                                                                    <i
                                                                                        class="ti ti-chevron-down drp-arrow nocolor"></i>
                                                                                </a>
                                                                                <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                                                                    aria-labelledby="dropdownLanguage1">
                                                                                    @foreach ($currantLang as $explangs)
                                                                                        <a href="{{ route('get.experiencecertificate.language', ['noclangs' => $noclang, 'explangs' => $explangs, 'offerlangs' => $offerlang, 'joininglangs' => $joininglang]) }}"
                                                                                            class="dropdown-item {{ $explangs == $explang ? 'text-primary' : '' }}">{{ Str::upper($explangs) }}</a>
                                                                                    @endforeach
                                                                                </div>
                                                                            </li>

                                                                        </ul>
                                                                    </div>

                                                                </div>
                                                                <div class="card-body ">
                                                                    <h5 class="font-weight-bold pb-3">
                                                                        {{ __('Placeholders') }}</h5>

                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <div class="card">
                                                                            <div class="card-header card-body">
                                                                                <div class="row text-xs">
                                                                                    <div class="row">
                                                                                        <p class="col-4">
                                                                                            {{ __('Company Name') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{app_name}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Employee Name') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{employee_name}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Date of Issuance') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{date}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Designation') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{designation}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Start Date') }} : <span
                                                                                                class="pull-right text-primary">{start_date}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Branch') }} : <span
                                                                                                class="pull-right text-primary">{branch}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Start Time') }} : <span
                                                                                                class="pull-end text-primary">{start_time}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('End Time') }} : <span
                                                                                                class="pull-right text-primary">{end_time}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Number of Hours') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{total_hours}</span>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body table-border-style ">

                                                                    {{ Form::open(['route' => ['experiencecertificate.update', $explang], 'method' => 'post']) }}
                                                                    <div class="form-group col-12">
                                                                        {{ Form::label('content', __(' Format'), ['class' => 'form-label text-dark']) }}
                                                                        <textarea name="content" class="pc-tinymce-4">{!! isset($curr_exp_cetificate_Lang->content) ? $curr_exp_cetificate_Lang->content : '' !!}</textarea>



                                                                    </div>

                                                                    <div class="card-footer text-end">

                                                                        {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                                                                    </div>

                                                                    {{ Form::close() }}
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="" id="noc-settings">
                                                            <div class="card">
                                                                <div class="card-header d-flex justify-content-between">
                                                                    <h5>{{ __('No Objection Certificate Settings') }}</h5>
                                                                    <div class="d-flex justify-content-end drp-languages">
                                                                        <ul class="list-unstyled mb-0 m-2">
                                                                            <li class="dropdown dash-h-item drp-language"
                                                                                style="margin-top: -19px;">
                                                                                <a class="dash-head-link dropdown-toggle arrow-none me-0"
                                                                                    data-bs-toggle="dropdown"
                                                                                    href="#" role="button"
                                                                                    aria-haspopup="false"
                                                                                    aria-expanded="false"
                                                                                    id="dropdownLanguage1">
                                                                                    <span
                                                                                        class="drp-text hide-mob text-primary">

                                                                                        {{ Str::upper($noclang) }}
                                                                                    </span>
                                                                                    <i
                                                                                        class="ti ti-chevron-down drp-arrow nocolor"></i>
                                                                                </a>
                                                                                <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                                                                    aria-labelledby="dropdownLanguage1">
                                                                                    @foreach ($currantLang as $noclangs)
                                                                                        <a href="{{ route('get.noc.language', ['noclangs' => $noclangs, 'explangs' => $explang, 'offerlangs' => $offerlang, 'joininglangs' => $joininglang]) }}"
                                                                                            class="dropdown-item {{ $noclangs == $noclang ? 'text-primary' : '' }}">{{ Str::upper($noclangs) }}</a>
                                                                                    @endforeach
                                                                                </div>
                                                                            </li>

                                                                        </ul>
                                                                    </div>

                                                                </div>
                                                                <div class="card-body ">
                                                                    <h5 class="font-weight-bold pb-3">
                                                                        {{ __('Placeholders') }}</h5>

                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                        <div class="card">
                                                                            <div class="card-header card-body">
                                                                                <div class="row text-xs">
                                                                                    <div class="row">
                                                                                        <p class="col-4">
                                                                                            {{ __('Date') }} : <span
                                                                                                class="pull-end text-primary">{date}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Company Name') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{app_name}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Employee Name') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{employee_name}</span>
                                                                                        </p>
                                                                                        <p class="col-4">
                                                                                            {{ __('Designation') }} :
                                                                                            <span
                                                                                                class="pull-right text-primary">{designation}</span>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="card-body table-border-style">
                                                                    {{ Form::open(['route' => ['noc.update', $noclang], 'method' => 'post']) }}
                                                                    <div class="form-group col-12">
                                                                        {{ Form::label('content', __(' Format'), ['class' => 'form-label text-dark']) }}
                                                                        <textarea name="content" class="pc-tinymce-5">{!! isset($currnocLang->content) ? $currnocLang->content : '' !!}</textarea>

                                                                    </div>

                                                                    <div class="card-footer text-end">

                                                                        {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                                                                    </div>

                                                                    {{ Form::close() }}
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endsection
