@php

    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_logo = \App\Models\Utility::GetLogo();
    $users = \Auth::user();
    $profile = \App\Models\Utility::get_file('uploads/avatar/');
    $currantLang = $users->currentLanguage();
    $emailTemplate = App\Models\EmailTemplate::first();

    $navSegment = Request::segment(1);
    $navOpen = function (array $segments) use ($navSegment) {
        return in_array($navSegment, $segments, true) ? 'dash-trigger' : '';
    };
    $payrollNavOpen = $navOpen([
        'setsalary', 'allowanceoption', 'commission_option', 'loans', 'otherpaymentotion',
        'deductionoption', 'statutory-deductions', 'payslip', 'my-payslip',
    ]);
    $attendanceNavOpen = $navOpen([
        'timesheet', 'leave', 'applywfm', 'earlyandlate_leave', 'getpass',
        'attendanceemployee', 'company-weekoff',
    ]);
    $attendanceSubNavOpen = $navOpen(['attendanceemployee', 'company-weekoff']);
    $visitClaimNavOpen = $navOpen(['my_visit', 'my_claim']);
    $announcementNavOpen = $navOpen(['announcement', 'holiday', 'event']);
    $grievanceNavOpen = $navOpen(['complaint', 'ticket']);
    $performanceNavOpen = $navOpen(['indicator', 'appraisal', 'goaltracking']);
    $recruitmentNavOpen = $navOpen([
        'job', 'job-application', 'candidates-job-applications', 'custom-question', 'interview-schedule',
    ]);
    $financeNavOpen = $navOpen([
        'accountlist', 'accountbalance', 'payees', 'payer', 'deposit', 'expense', 'transferbalance', 'approved-claims',
    ]);
    $employeeExitNavOpen = $navOpen(['resignation', 'termination']);
    $staffRequestNavOpen = $navOpen([
        'timesheet_request', 'leaverequest', 'applywfm_request', 'earlyandlate_leave_request',
        'getpass_request', 'resignation_request', 'my_visit_request', 'my_claim_request',
    ]);
    $reportNavOpen = $navOpen(['report', 'employee-attendance-geofencing-report', 'esic_register_reports', 'wage_reports_index', 'late-coming-report', 'new-joinee-report', 'resigned-employee-report']);
    $systemSetupNavOpen = $navOpen([
        'settings', 'branch', 'department', 'designation', 'leavetype', 'document', 'paysliptype',
        'goaltype', 'trainingtype', 'awardtype', 'loanoption', 'terminationtype', 'job-category', 'job-stage',
        'performanceType', 'competencies', 'expensetype', 'incometype', 'paymenttype', 'contract_type',
        'commuting_type', 'city_tier', 'category_type', 'visit_type', 'grade_type', 'letter_setting', 'plans', 'order',
    ]);
    $hrSetupNavOpen = $navOpen([
        'branch', 'department', 'designation', 'leavetype', 'document', 'paysliptype', 'goaltype', 'trainingtype',
        'awardtype', 'loanoption', 'terminationtype', 'job-category', 'job-stage', 'performanceType', 'competencies',
        'expensetype', 'incometype', 'paymenttype', 'contract_type', 'commuting_type', 'city_tier', 'category_type',
        'visit_type', 'grade_type',
    ]);
    $staffNavOpen = $navOpen(['user', 'roles']);
    $settingsSubNavOpen = $navOpen(['settings']);
    $letterNavOpen = $navOpen(['letter_setting']);
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

            <li class="dash-caption">
                <label>{{ __('Overview') }}</label>
            </li>

            <!-- dashboard-->
            <li class="dash-item">
                <a href="{{ route('home') }}" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-home"></i></span><span class="dash-mtext">{{ __('Dashboard') }} </span></a>
            </li>
            <!--dashboard-->

            @if (\Auth::user()->type != 'company')
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
            @endif

            <li class="dash-caption">
                <label>{{ __('People') }}</label>
            </li>

            <!-- user-->
            @if (Gate::check('Manage User') || Gate::check('Manage Role'))

                <li class="dash-item dash-hasmenu {{ $staffNavOpen }}">
                    <a href="#!" class="dash-link"><span class="dash-micon"><i class="ti ti-users"></i></span><span
                            class="dash-mtext">{{ __('Staff') }}</span><span class="dash-arrow"><i
                                data-feather="chevron-right"></i></span></a>
                    <ul class="dash-submenu">
                        @can('Manage User')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('user.index') }}">{{ __('User') }}</a>
                            </li>
                        @endcan
                        @can('Manage Role')
                            <li class="dash-item">
                                <a class="dash-link" href="{{ route('roles.index') }}">{{ __('Role') }}</a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif
            <!-- user-->

            <!-- employee List-->

            @if (Gate::check('Manage Employee') && \Auth::user()->type != 'employee')
                <li class="dash-item {{ Request::segment(1) == 'employee' ? 'active' : '' }}">
                    <a href="{{ route('employee.index') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-user"></i></span><span class="dash-mtext">{{ __('Employees') }}</span></a>
                </li>
            @endif

            @if ((Gate::check('View Employee Data Log') || \Auth::user()->type == 'company') && \Auth::user()->type != 'employee')
                <li class="dash-item {{ Request::segment(1) == 'employee-data-log' ? 'active' : '' }}">
                    <a href="{{ route('employee-data-log.index') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-history"></i></span><span
                            class="dash-mtext">{{ __('Employee Data Log') }}</span></a>
                </li>
            @endif

            @if (config('form16.enabled') && Gate::check('Manage Employee') && \Auth::user()->type != 'employee')
                <li class="dash-item {{ Request::segment(1) == 'form16' ? 'active' : '' }}">
                    <a href="{{ route('form16.index') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-file-certificate"></i></span><span
                            class="dash-mtext">{{ __('Form 16') }}</span></a>
                </li>
            @endif

            <!-- employee List -->

            <li class="dash-caption">
                <label>{{ __('Operations') }}</label>
            </li>

            <!-- payroll-->
            @if (Gate::check('Manage Set Salary') || Gate::check('Manage Pay Slip'))
                <li class="dash-item dash-hasmenu {{ $payrollNavOpen }}">
                    <a href="#!" class="dash-link">
                        <span class="dash-micon">
                            <i class="ti ti-receipt">
                            </i>
                        </span>
                        <span class="dash-mtext">
                            {{ __('Payroll') }}
                        </span>
                        <span class="dash-arrow"><i data-feather="chevron-right">
                            </i>
                        </span>
                    </a>
                    <ul class="dash-submenu ">
                        {{-- <li class="dash-item {{ Request::segment(1) == 'setsalary' ? 'active' : '-' }}">
                                   <a class="dash-link" href="{{ route('setsalary.index') }}">{{ __('Set Salary') }}</a>
                         </li> --}}
                        <li class="dash-item {{ Request::segment(1) == 'allowanceoption' ? 'active' : '-' }}">
                            <a class="dash-link" href="{{ route('allowanceoption.index') }}">{{ __('Allowance') }}</a>
                        </li>

                        <li class="dash-item {{ Request::segment(1) == 'commission_option' ? 'active' : '-' }}">
                            <a class="dash-link"
                                href="{{ route('commission_option.index') }}">{{ __('Commission ') }}</a>
                        </li>

                        <li class="dash-item {{ Request::segment(1) == 'loans' ? 'active' : '-' }}">
                            <a class="dash-link" href="{{ route('loans') }}">{{ __('Loan ') }}</a>
                        </li>

                        <li class="dash-item {{ Request::segment(1) == 'otherpaymentotion' ? 'active' : '-' }}">
                            <a class="dash-link"
                                href="{{ route('otherpaymentotion.index') }}">{{ __('Other Payment') }}</a>
                        </li>


                        <li class="dash-item {{ Request::segment(1) == 'deductionoption' ? 'active' : '-' }}">
                            <a class="dash-link"
                                href="{{ route('deductionoption.index') }}">{{ __('Deduction ') }}</a>
                        </li>

                        <li class="dash-item {{ Request::segment(1) == 'statutory-deductions' ? 'active' : '-' }}">
                            <a class="dash-link"
                                href="{{ route('statutory-deductions') }}">{{ __('Statutory Deductions') }}</a>
                        </li>

                        <li class="dash-item {{ Request::segment(1) == 'payslip' ? 'active' : '-' }}">
                            <a class="dash-link" href="{{ route('payslip.index') }}">{{ __('Generate Payroll') }}</a>
                        </li>

                        @if (Gate::check('My Pay Slip'))
                            <li class="dash-item {{ Request::segment(1) == 'my-payslip' ? 'active' : '-' }}">
                                <a class="dash-link" href="{{ route('my-payslip.index') }}">{{ __('My Payslip') }}</a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif
            <!-- payroll-->

            <!-- timesheet-->
            @if (Gate::check('Manage Attendance') ||
                    Gate::check('Manage Leave') ||
                    Gate::check('Manage TimeSheet') ||
                    Gate::check('Manage Gate Pass') ||
                    Gate::check('Manage Apply WFH') ||
                    Gate::check('Manage Early & Late Comming'))
                <li class="dash-item dash-hasmenu {{ $attendanceNavOpen }}">
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
                        @endif


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
                                <a class="dash-link"
                                    href="{{ route('getpass.index') }}">{{ __('Gate Pass') }}</a>
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
                            <li class="dash-item dash-hasmenu {{ $attendanceSubNavOpen }}">
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

                                    @can('Company Weekoff')
                                        <li class="dash-item">
                                            <a class="dash-link"
                                                href="{{ route('company-weekoff.show') }}">{{ __('Week-off') }}</a>
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
        @if (Gate::check('Manage Visit Request') || Gate::check('Manage Claim Request'))
            <li class="dash-item dash-hasmenu {{ $visitClaimNavOpen }}">
                <a href="#!" class="dash-link">
                    <span class="dash-micon"><i class="ti ti-file"></i></span>
                    <span class="dash-mtext">{{ __('My Visit & Claim') }}</span>
                    <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                </a>
                <ul class="dash-submenu ">

                    <li class="dash-item {{ Request::segment(1) == 'my_visit' ? 'active' : '-' }}">
                        <a class="dash-link" href="{{ route('my_visit.index') }}">{{ __('Visit List') }}</a>
                    </li>
                    <li class="dash-item {{ Request::segment(1) == 'my_claim' ? 'active' : '-' }}">
                        <a class="dash-link" href="{{ route('my_claim.index') }}">{{ __('Claim List') }}</a>
                    </li>
                </ul>
            </li>
        @endif
        <!-- My Visit & Claim-->


        {{-- Announcement --}}


        @if (Gate::check('Manage Holiday') || Gate::check('Manage Announcement') || Gate::check('Manage Event'))
            <li class="dash-item dash-hasmenu {{ $announcementNavOpen }}">
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
        @can('Manage Company Policy')
            <li class="dash-item">
                <a href="{{ route('company-policy.index') }}" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-book"></i></span><span class="dash-mtext">{{ __('Company Policy') }}
                    </span></a>
            </li>
        @endcan
        <!-- Company Policy-->


        <!-- Grievance Desk-->
        @if (Gate::check('Manage Complaint') || Gate::check('Manage Ticket'))
            <li class="dash-item dash-hasmenu {{ $grievanceNavOpen }}">
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


        <!-- performance-->
        @if (Gate::check('Manage Indicator') || Gate::check('Manage Appraisal') || Gate::check('Manage Goal Tracking'))
            <li class="dash-item dash-hasmenu {{ $performanceNavOpen }}">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-3d-cube-sphere"></i></span><span
                        class="dash-mtext">{{ __('Performance') }}</span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                    @can('Manage Indicator')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('indicator.index') }}">{{ __('Indicator') }}</a>
                        </li>
                    @endcan
                    @can('Manage Appraisal')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('appraisal.index') }}">{{ __('Appraisal') }}</a>
                        </li>
                    @endcan
                    @can('Manage Goal Tracking')
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('goaltracking.index') }}">{{ __('Goal Tracking') }}</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif
        <!--performance-->

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
                            <a class="dash-link"
                                href="{{ route('training.index') }}">{{ __('Training List') }}</a>
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
                class="dash-item dash-hasmenu {{ $recruitmentNavOpen }}">
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
                                href="{{ route('job.application.candidate') }}">{{ __('Archive Application') }}</a>
                        </li>
                    @endcan

                    @can('Manage Job OnBoard')
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('job.on.board') }}">{{ __('Job On-Boarding') }}</a>
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

        <!--fianance-->
        @if (Gate::check('Manage Account List') ||
                Gate::check('Manage Payee') ||
                Gate::check('Manage Payer') ||
                Gate::check('Manage Deposit') ||
                Gate::check('Manage Expense') ||
                Gate::check('Manage Transfer Balance'))
            <li class="dash-item dash-hasmenu {{ $financeNavOpen }}">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-wallet"></i></span><span
                        class="dash-mtext">{{ __('Finance') }}</span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                    @can('Manage Account List')
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('accountlist.index') }}">{{ __('Account List') }}</a>
                        </li>
                    @endcan
                    @can('View Balance Account List')
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('accountbalance') }}">{{ __('Account Balance') }}</a>
                        </li>
                    @endcan
                    @can('Manage Payee')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('payees.index') }}">{{ __('Payees') }}</a>
                        </li>
                    @endcan

                    @can('Manage Payer')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('payer.index') }}">{{ __('Payers') }}</a>
                        </li>
                    @endcan

                    @can('Manage Deposit')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('deposit.index') }}">{{ __('Deposit') }}</a>
                        </li>
                    @endcan

                    @can('Manage Expense')
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('expense.index') }}">{{ __('Expense') }}</a>
                        </li>
                    @endcan

                    @can('Manage Transfer Balance')
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('transferbalance.index') }}">{{ __('Transfer Balance') }}</a>
                        </li>
                    @endcan

                    @can('Approved Claims')
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('approved-claims') }}">{{ __('Approved Claims') }}</a>
                        </li>
                    @endcan

                </ul>
            </li>
        @endif
        <!-- fianance-->

        {{-- assets --}}
        @can('Manage Assets')

            <li class="dash-item">
                <a href="{{ route('account-assets.index') }}" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-book"></i></span><span class="dash-mtext">{{ __('Assets') }}
                    </span></a>
            </li>
        @endcan
        {{-- assets --}}


        <!-- Employee Exit-->
        @if (Gate::check('Manage Resignation') || Gate::check('Manage Termination'))
            <li class="dash-item dash-hasmenu {{ $employeeExitNavOpen }}">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-arrow-ramp-right"></i></span><span
                        class="dash-mtext">{{ __('Employee Exit') }}</span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span>
                </a>
                <ul class="dash-submenu">

                    @can('Manage Resignation')
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('resignation.index') }}">{{ __('Resignation') }}</a>
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



































































        <!--timesheet-->

        <!-- staff request-->
        @if (Gate::check('Manage Timesheet Request') ||
                Gate::check('Manage Leave Request') ||
                Gate::check('Manage Apply WFH Request') ||
                Gate::check('Manage Early & Late Leave Request') ||
                Gate::check('Manage Gate Pass Request') ||
                Gate::check('Manage Resignation Request'))

            @if (\Auth::user()->type != 'employee')
                <li class="dash-item dash-hasmenu {{ $staffRequestNavOpen }}">
                    <a href="#!" class="dash-link"><span class="dash-micon">
                            <i class="ti ti-user-check"></i></span><span
                            class="dash-mtext">{{ __('Staff Request') }}</span><span class="dash-arrow"><i
                                data-feather="chevron-right"></i></span></a>
                    <ul class="dash-submenu">
                        @can('Manage Timesheet Request')
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="{{ route('timesheet_request') }}">{{ __('Timesheet Request') }}</a>
                            </li>
                        @endcan

                        @can('Manage Leave Request')
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="{{ route('leaverequest') }}">{{ __('Leave Request') }}</a>
                            </li>
                        @endcan


                        @can('Manage Apply WFH Request')
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="{{ route('applywfm_request') }}">{{ __('WFH Request') }}</a>
                            </li>
                        @endcan

                        @can('Manage Early & Late Leave Request')
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="{{ route('earlyandlate_leave_request') }}">{{ __('Short Leave Request') }}</a>
                            </li>
                        @endcan

                        @can('Manage Gate Pass Request')
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="{{ route('getpass_request') }}">{{ __('Gate Pass Request') }}</a>
                            </li>
                        @endcan
                        @can('Manage Resignation Request')
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="{{ route('resignation_request') }}">{{ __('Resignation Request') }}</a>
                            </li>
                        @endcan
                        @can('Manage Resignation Request')
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="{{ route('my_visit_request') }}">{{ __('Visit Request') }}</a>
                            </li>
                        @endcan

                        @can('Manage Resignation Request')
                            <li class="dash-item">
                                <a class="dash-link"
                                    href="{{ route('my_claim_request') }}">{{ __('Claim Request') }}</a>
                            </li>
                        @endcan

                    </ul>
                </li>
            @endif
        @endif

















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








        <!--report-->

        <li class="dash-caption">
            <label>{{ __('Insights') }}</label>
        </li>

        @if (Gate::check('Manage Report'))
            <li class="dash-item dash-hasmenu {{ $reportNavOpen }}">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-list"></i></span><span
                        class="dash-mtext">{{ __('Report') }}</span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">
                    @can('Manage Report')
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('report.income-expense') }}">{{ __('Income Vs Expense') }}</a>
                        </li>

                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('report.monthly.attendance') }}">{{ __('Monthly Attendance') }}</a>
                        </li>

                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('employee-attendance-geofencing-report') }}">{{ __('Advance Monthly Attendance') }}</a>
                        </li>

                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('report.leave') }}">{{ __('Leave') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('report.account.statement') }}">{{ __('Account Statement') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('report.timesheet') }}">{{ __('Timesheet') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link" href="{{ route('report.claim') }}">{{ __('Claim') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('esic_register_reports') }}">{{ __('Esic Register Reports') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('wage_reports_index') }}">{{ __('Wage Reports') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('report.birthday_list') }}">{{ __('Birthday List') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('report.work_anniversary_list') }}">{{ __('Work Anniversary List') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('report.marriage_anniversary_list') }}">{{ __('Marriage Anniversary List') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('late-coming-report') }}">{{ __('Late Coming') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('new-joinee-report') }}">{{ __('New Joinee') }}</a>
                        </li>
                        <li class="dash-item">
                            <a class="dash-link"
                                href="{{ route('resigned-employee-report') }}">{{ __('Resigned Employee') }}</a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endif

        @if (Gate::check('Manage System Settings'))
            <li class="dash-item ">
                <a href="{{ route('settings.index') }}" class="dash-link"><span class="dash-micon"><i
                            class="ti ti-settings"></i></span><span
                        class="dash-mtext">{{ __('Settings') }}</span></a>

            </li>
        @endif


        <!--------------------- Start System Setup ----------------------------------->

        @if (Gate::check('Manage Plan') || Gate::check('Manage Order') || Gate::check('Manage Company Settings'))
            <li class="dash-item dash-hasmenu {{ $systemSetupNavOpen }}">
                <a href="#!" class="dash-link ">
                    <span class="dash-micon"><i class="ti ti-settings"></i></span><span
                        class="dash-mtext">{{ __('System Setup') }}</span><span class="dash-arrow">
                        <i data-feather="chevron-right"></i></span>
                </a>

                <ul class="dash-submenu">
                    @if (Gate::check('Manage Company Settings'))
                        <li class="dash-item dash-hasmenu {{ $settingsSubNavOpen }}">
                            <a href="#!" class="dash-link patch_link">
                                <span class="dash-mtext">{{ __('Settings') }}</span>
                                <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul class="dash-submenu patch_submenu">
                                <li class="dash-item">
                                    <a class="dash-link"
                                        href="{{ route('settings.index') }}#business-settings">{{ __('Business Settings') }}</a>
                                </li>
                                <li class="dash-item">
                                    <a class="dash-link"
                                        href="{{ route('settings.index') }}#system-settings">{{ __('System Settings') }}</a>
                                </li>
                                <li class="dash-item">
                                    <a class="dash-link"
                                        href="{{ route('settings.index') }}#company-settings">{{ __('Company Settings') }}</a>
                                </li>
                                <li class="dash-item">
                                    <a class="dash-link"
                                        href="{{ route('settings.index') }}#email-notification-settings">{{ __('Email Notification Settings') }}</a>
                                </li>
                                <li class="dash-item">
                                    <a class="dash-link"
                                        href="{{ route('settings.index') }}#google-calender">{{ __('Google Calendar Settings') }}</a>
                                </li>
                                <li class="dash-item">
                                    <a class="dash-link"
                                        href="{{ route('settings.index') }}#Leave-setting">{{ __('Leave setting') }}</a>
                                </li>
                            </ul>
                        </li>


                        <li class="dash-item dash-hasmenu {{ $hrSetupNavOpen }}">
                            <a href="#!" class="dash-link patch_link2"><span
                                    class="dash-mtext">{{ __('HR Setup') }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu patch_submenu2">
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('branch.index') }}">{{ __('Branch') }}</a></li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('department.index') }}">{{ __('Department') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('designation.index') }}">{{ __('Designation') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('leavetype.index') }}">{{ __('Leave Type') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('document.index') }}">{{ __('Document Type') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('paysliptype.index') }}">{{ __('Pay Slab') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('goaltype.index') }}">{{ __('Goal Type') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('trainingtype.index') }}">{{ __('Training Type') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('awardtype.index') }}">{{ __('Award Type') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('loanoption.index') }}">{{ __('Loan Type') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('terminationtype.index') }}">{{ __('Termination Type') }}</a>
                                </li>

                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('job-category.index') }}">{{ __('Job Category') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('job-stage.index') }}">{{ __('Job Stage') }}</a>
                                </li>

                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('performanceType.index') }}">{{ __('Performance Type') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('competencies.index') }}">{{ __('Competencies') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('expensetype.index') }}">{{ __('Expense Type') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('incometype.index') }}">{{ __('Income Type') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('paymenttype.index') }}">{{ __('Payment Type') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('contract_type.index') }}">{{ __('Contract Type') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('commuting_type.index') }}">{{ __('Commutting Type') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('city_tier.index') }}">{{ __('City Tier') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('category_type.index') }}">{{ __('Reimbursement Expenses') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('visit_type.index') }}">{{ __('Grade Type') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('grade_type.index') }}">{{ __('Assign Grade') }}</a>
                                </li>
                            </ul>
                        </li>


                        <li class="dash-item dash-hasmenu {{ $letterNavOpen }}">
                            <a href="#!" class="dash-link patch_link3"><span
                                    class="dash-mtext">{{ __('Letter setting') }}</span><span
                                    class="dash-arrow"><i data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu patch_submenu3">
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('letter_setting') }}#offer-letter-settings">{{ __('Offer Letter Settings') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('letter_setting') }}#joining-letter-settings">{{ __('Joining Letter Settings') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('letter_setting') }}#increment-letter-settings">{{ __('Increment Letter Settings') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('letter_setting') }}#Relieving-letter-settings">{{ __('Relieving Letter') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('letter_setting') }}#experience-certificate-settings">{{ __('Certificate of Experience Settings') }}</a>
                                </li>
                                <li class="dash-item"><a class="dash-link"
                                        href="{{ route('letter_setting') }}#noc-settings">{{ __('No Objection Certificate Settings') }}</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if (Gate::check('Manage Plan'))
                        <li
                            class="dash-item{{ (Request::route() && (Request::route()->getName() == 'plans.index' || Request::route()->getName() == 'stripe')) ? ' active' : '' }}">
                            <a href="{{ route('plans.index') }}"
                                class="dash-link">{{ __('Setup Subscription Plan') }}</a>
                        </li>
                    @endif

                    <li class="dash-item {{ Request::segment(1) == 'order' ? 'active' : '' }}">
                        <a href="{{ route('order.index') }}" class="dash-link">{{ __('Invoice') }}</a>
                    </li>

                </ul>
            </li>
        @endif

        <!--------------------- End System Setup ----------------------------------->

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
