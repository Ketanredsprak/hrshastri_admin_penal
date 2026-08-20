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
            <!-- ========   change your logo hear   ============ -->
            <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}"
                alt="{{ config('app.name', 'HRMGo') }}" class="logo logo-lg" style="height: 40px;">
        </a>
    </div>
    <div class="navbar-content">
        <ul class="dash-navbar">

            <!-- dashboard-->
            <li class="dash-item">
                <a href="{{ route('home') }}" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-home"></i></span><span class="dash-mtext">{{ __('Dashboard') }} </span></a>
            </li>
            <!--dashboard-->

            {{-- Employee Details --}}
            <li
                class="dash-item {{ Request::segment(1) == 'employee-profile-edit' || Request::segment(1) == 'employee-profile-show' ? 'active' : '' }}">

                @php
                    $employee = App\Models\Employee::where('user_id', \Auth::user()->id)->first();
                @endphp
                <a href="{{ route('employeeProfileShow', \Illuminate\Support\Facades\Crypt::encrypt($employee->id ?? 0)) }}"
                    class="dash-link"><span class="dash-micon"><i class="ti ti-user"></i></span><span
                        class="dash-mtext">{{ __('Employee Details') }}</span></a>
            </li>
            {{-- Employee Details --}}

            <li class="dash-item dash-hasmenu {{ Request::segment(1) == 'setsalary' ? 'dash-trigger active' : '' }}">
                <a href="{{ route('my-payslip.index') }}" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-receipt"></i></span><span
                        class="dash-mtext">{{ __('My Payslip') }}</span></span></a>
            </li>

            <!-- timesheet-->
            @if (Gate::check('Manage Attendance') ||
                    Gate::check('Manage Leave') ||
                    Gate::check('Manage TimeSheet') ||
                    Gate::check('Manage Gate Pass') ||
                    Gate::check('Manage Apply WFH') ||
                    Gate::check('Manage Early & Late Comming'))
                <li class="dash-item dash-hasmenu">
                    <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-clock"></i></span><span
                            class="dash-mtext">{{ __('Attendance & Leaves') }}</span><span class="dash-arrow"><i
                                data-feather="chevron-right"></i></span></a>
                    <ul class="dash-submenu">

                        @can('Manage TimeSheet')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('timesheet.index') }}">{{ __('Timesheet') }}</a>
                            </li>
                        @endcan

                        @can('Manage Leave')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('leave.index') }}">{{ __('Leave') }}</a>
                            </li>
                        @endcan

                        @if (\Auth::user()->type != 'company')
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="{{ route('leave.my_leave_balance') }}">{{ __('My Leave Balance') }}</a>
                            </li>
                        @endcan


                        @if (\Auth::user()->type == 'employee')
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="{{ route('applywfm.index') }}">{{ __('Apply WFH') }}</a>
                            </li>
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="{{ route('earlyandlate_leave.index') }}">{{ __('Short Leave') }}</a>
                            </li>
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('getpass.index') }}">{{ __('Gate Pass') }}</a>
                            </li>
                        @endif

                        @can('Manage Apply WFH')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('applywfm.index') }}">{{ __('Apply WFH') }}</a>
                            </li>
                        @endcan
                        @can('Manage Early & Late Comming')
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="{{ route('earlyandlate_leave.index') }}">{{ __('Short Leave') }}</a>
                            </li>
                        @endcan
                        @can('Manage Gate Pass')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('getpass.index') }}">{{ __('Gate Pass') }}</a>
                            </li>
                        @endcan

                        @can('Manage Attendance')
                            <li class="dash-item dash-hasmenu">
                                <a href="#!" class="dash-link"><span
                                        class="dash-mtext">{{ __('Attendance') }}</span><span class="dash-arrow"><i
                                            data-feather="chevron-right"></i></span></a>
                                <ul class="dash-submenu">
                                    <li class="dash-item">
                                        <a class="dash-link"
                                            href="{{ route('attendanceemployee.index') }}">{{ __('Marked Attendance') }}</a>
                                    </li>
                                    @if (\Auth::user()->type != 'company' && \Auth::user()->type != 'employee')
                                        <li class="dash-item">
                                            <a class="dash-link"
                                                href="{{ route('attendanceemployee.my-attendance') }}">{{ __('My Attendance') }}</a>
                                        </li>
                                    @endif
                                    @can('Create Attendance')
                                        <li class="dash-item">
                                            <a class="dash-link"
                                                href="{{ route('attendanceemployee.bulkattendance') }}">{{ __('Bulk Attendance') }}</a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                </ul>
            </li>
        @endif
        <!--timesheet-->

        <!-- My Visit & Claim-->

        <li class="dash-item dash-hasmenu  {{ Request::segment(1) == 'setsalary' ? 'dash-trigger active' : '' }}">
            <a href="#!" class="dash-link">
                <span class="dash-micon"><i class="ti ti-file"></i></span>
                <span class="dash-mtext">{{ __('My Visit & Claim') }}</span>
                <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
            </a>
            <ul class="dash-submenu ">

                <li class="dash-item {{ Request::segment(1) == 'my_visit' ? 'active' : '-' }}">
                    <a class="dash-link" href="{{ route('my_visit.index') }}">
                        {{ __('My Visit') }}
                    </a>
                </li>
                <li class="dash-item {{ Request::segment(1) == 'my_claim' ? 'active' : '-' }}">
                    <a class="dash-link" href="{{ route('my_claim.index') }}">
                        {{ __('My Claim') }}
                    </a>
                </li>

            </ul>
        </li>
        <!-- My Visit & Claim-->


        {{-- Announcement --}}

        @if (Gate::check('Manage Holiday') || Gate::check('Manage Announcement') || Gate::check('Manage Event'))
            <li class="dash-item dash-hasmenu">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-book"></i></span><span
                        class="dash-mtext">{{ __('Announcement') }}</span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span>
                </a>
                <ul class="dash-submenu">

                    @can('Manage Announcement')
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('announcement.index') }}">{{ __('Office Announcement') }}</a>
                        </li>
                    @endcan

                    @can('Manage Holiday')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('holiday.index') }}">{{ __('Holidays') }}</a>
                        </li>
                    @endcan

                    @can('Manage Event')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('event.index') }}">{{ __('Event') }}</a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endif
        {{-- Announcement --}}

        <!-- Company Policy-->
        {{-- @can('Manage Company Policy') --}}
        <li class="dash-item">
            <a href="{{ route('company-policy.index') }}" class="dash-link"><span class="dash-micon"><i
                        class="ti ti-book"></i></span><span class="dash-mtext">{{ __('Company Policy') }}
                </span></a>
        </li>
        {{-- @endcan --}}
        <!-- Company Policy-->


        <!-- Grievance Desk-->
        @if (Gate::check('Manage Complaint') || Gate::check('Manage Ticket'))
            <li class="dash-item dash-hasmenu">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-border-all"></i></span><span
                        class="dash-mtext">{{ __('Grievance Desk') }}</span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span>
                </a>
                <ul class="dash-submenu">

                    @can('Manage Complaint')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('complaint.index') }}">{{ __('Complaints') }}</a>
                        </li>
                    @endcan

                    @can('Manage Ticket')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('ticket.index') }}">{{ __('Ticket') }}</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif
        <!-- Grievance Desk-->


        {{-- Promotion --}}
        @if (Gate::check('Manage Promotion'))
            <li class="dash-item">
                <a class="dash-link" href="{{ route('promotion.index') }}"><span class="dash-micon"><i
                            class="ti ti-tag"></i></span><span
                        class="dash-mtext">{{ __('Promotion') }}</span></a>
            </li>
        @endif
        {{-- Promotion --}}


        {{-- Transfer --}}
        @can('Manage Transfer')

            <li class="dash-item">
                <a href="{{ route('transfer.index') }}" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-book"></i></span><span class="dash-mtext">{{ __('Transfer') }}
                    </span></a>
            </li>
        @endcan
        {{-- Transfer --}}


        <!--trainning-->
        @if (Gate::check('Manage Trainer') || Gate::check('Manage Training') || \Auth::user()->type == 'employee')
            <!-- user type if employee than show list of traning st en -->
            <li
                class="dash-item dash-hasmenu {{ Request::segment(1) == 'training' ? 'dash-trigger active' : '' }}">
                <a href="#!" class="dash-link "><span class="dash-micon"><i
                            class="ti ti-school"></i></span><span
                        class="dash-mtext">{{ __('Training') }}</span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                    <!-- st -->
                    @if (\Auth::user()->type == 'employee')
                        <li class="dash-item {{ Request::segment(1) == 'training' ? ' active' : '' }}">
                            <a class="dash-link"
                                href="{{ route('training.index') }}">{{ __('Training List') }}</a>
                        </li>
                    @endif
                    <!-- en -->
                    @can('Manage Training')
                        <li class="dash-item {{ Request::segment(1) == 'training' ? ' active' : '' }}">
                            <a class="dash-link" href="{{ route('training.index') }}">{{ __('Training List') }}</a>
                        </li>
                    @endcan

                    @can('Manage Trainer')
                        <li class="dash-item ">
                            <a class="dash-link" href="{{ route('trainer.index') }}">{{ __('Trainer') }}</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif
        <!-- tranning-->

        <!-- recruitment-->
        @if (Gate::check('Manage Job') ||
                Gate::check('Manage Job Application') ||
                Gate::check('Manage Job OnBoard') ||
                Gate::check('Manage Custom Question') ||
                Gate::check('Manage Interview Schedule') ||
                Gate::check('Manage Career'))
            <li
                class="dash-item dash-hasmenu  {{ Request::segment(1) == 'job' || Request::segment(1) == 'job-application' ? 'dash-trigger active' : '' }} ">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-users"></i></span><span
                        class="dash-mtext">{{ __('Recruitment') }}</span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                    @can('Manage Job')
                        <li class="dash-item {{ Request::segment(1) == 'job' ? 'active' : '-' }}">
                            <a class="dash-link" href="{{ route('job.index') }}">{{ __('Jobs') }}</a>
                        </li>
                    @endcan
                    @can('Manage Job')
                        <li class="dash-item {{ Request::segment(1) == 'job' ? 'active' : '-' }}">
                            <a class="dash-link" href="{{ route('job.create') }}">{{ __('Job Create ') }}</a>
                        </li>
                    @endcan
                    @can('Manage Job Application')
                        <li class="dash-item {{ request()->is('job-application*') ? 'active' : '' }}">
                            <a class="dash-link"
                                href="{{ route('job-application.index') }}">{{ __('Job Application') }}</a>
                        </li>
                    @endcan
                    @can('Manage Job Application')
                        <li class="dash-item {{ request()->is('candidates-job-applications') ? 'active' : '' }}">
                            <a class="dash-link"
                                href="{{ route('job.application.candidate') }}">{{ __('Job Candidate') }}</a>
                        </li>
                    @endcan

                    @can('Manage Job OnBoard')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('job.on.board') }}">{{ __('Job On-Boarding') }}</a>
                        </li>
                    @endcan

                    @can('Manage Custom Question')
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('custom-question.index') }}">{{ __('Custom Question') }}</a>
                        </li>
                    @endcan

                    @can('Manage Interview Schedule')
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('interview-schedule.index') }}">{{ __('Interview Schedule') }}</a>
                        </li>
                    @endcan

                    @can('Manage Career')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('career', [\Auth::user()->creatorId(), 'en']) }}"
                                target="_blank">{{ __('Career') }}</a>
                        </li>
                    @endcan

                    @if (\Auth::user()->type == 1 || \Auth::user()->type == 'company')
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('job_referencelist') }}">{{ __('Reference List') }}</a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        <!-- recruitment-->


        <!-- Employee Exit-->
        @if (Gate::check('Manage Resignation') || Gate::check('Manage Termination'))
            <li class="dash-item dash-hasmenu">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-arrow-ramp-right"></i></span><span
                        class="dash-mtext">{{ __('Employee Exit') }}</span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span>
                </a>
                <ul class="dash-submenu">

                    @can('Manage Resignation')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('resignation.index') }}">{{ __('Resignation') }}</a>
                        </li>
                    @endcan

                    @can('Manage Termination')
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('termination.index') }}">{{ __('Termination') }}</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif
        <!-- Employee Exit-->


        <!--meeting-->
        {{-- @can('Manage Meeting') --}}
        {{-- <li class="dash-item {{ Request::segment(1) == 'meeting' ? 'active' : '' }}">
                    <a href="{{ route('meeting.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-calendar-time"></i></span><span class="dash-mtext">{{ __('Meeting') }}</span></a>
                    </li> --}}
        {{-- @endcan --}}


        <!-- Zoom meeting-->
        {{-- @if (\Auth::user()->type != 'super admin') --}}
        {{-- <li class="dash-item {{ Request::segment(1) == 'zoommeeting' ? 'active' : '' }}">
                    <a href="{{ route('zoom-meeting.index') }}" class="dash-link"><span class="dash-micon"><i class="ti ti-video"></i></span><span class="dash-mtext">{{ __('Zoom Meeting') }}</span></a>
                    </li> --}}
        {{-- @endif --}}

    </ul>

</div>
</div>
</nav>
<script type="text/javascript">
    $('.patch_link').on("click", function() {
        setTimeout(function() {
            $('.patch_submenu').removeAttr("style");
            $('.patch_submenu').css('display', 'block');
        }, 200);
    });
    // $('.patch_link2').on("click", function(){ setTimeout(function(){ $('.patch_submenu2').removeAttr("style"); $('.patch_submenu2').css('display', 'block'); }, 200);  });
    // $('.patch_link3').on("click", function(){ setTimeout(function(){ $('.patch_submenu3').removeAttr("style"); $('.patch_submenu3').css('display', 'block'); }, 200);  });
</script>
