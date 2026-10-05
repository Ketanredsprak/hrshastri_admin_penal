@extends('layouts.admin')

@section('page-title')
    {{ __('Dashboard') }}
@endsection
@php
    $setting = App\Models\Utility::settings();
    $total_birthday = DB::table('employees')
        ->where('created_by', '=', \Auth::user()->creatorId())
        ->whereDate('dob', date('y-m-d'))
        ->count();
    $total_anniversary = DB::table('employees')
        ->where('created_by', '=', \Auth::user()->creatorId())
        ->whereMonth('company_doj', date('m'))
        ->whereDay('company_doj', date('d'))
        ->count();

    //holidaycount
    $total_holidays = DB::table('holidays')->where('created_by', '=', \Auth::user()->creatorId());
    $total_holidays = $total_holidays
        ->whereMonth('end_date', date('m'))
        ->whereYear('end_date', date('Y'))
        ->whereMonth('start_date', date('m'))
        ->whereYear('start_date', date('Y'))
        ->count();

@endphp

@section('header-section')
@endsection
@section('content')
    <div class="row">
        <div class="col-12 mb-3">
            <div class="d-flex flex-wrap align-items-end justify-content-between gap-2">
                <div>
                    <p class="text-muted small text-uppercase fw-semibold mb-1" style="letter-spacing:.08em;">{{ __('Overview') }}</p>
                    <h4 class="mb-0 fw-bold" style="letter-spacing:-0.02em;">{{ __('Dashboard') }}</h4>
                    <p class="text-muted mb-0 mt-1">{{ __('Workforce, attendance, and today’s HR signals in one place.') }}</p>
                </div>
            </div>
        </div>

        @if (session('status'))
            <div class="col-12 mb-3">
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px; border-left: 4px solid #10b981;">
                    <i class="ti ti-check me-2"></i>{{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @php
            $dashCompany = \Auth::user();
            $showDashSubAlert = $dashCompany
                && $dashCompany->type === 'company'
                && !$dashCompany->isExemptFromPlanExpiry()
                && ($dashCompany->isSubscriptionExpiringSoon(5) || !$dashCompany->hasActiveSubscription());
            $dashExpired = $dashCompany && !$dashCompany->hasActiveSubscription();
            $dashDaysLeft = $dashCompany ? $dashCompany->daysUntilPlanExpiry() : null;
            $dashPlan = ($dashCompany && $dashCompany->plan) ? \App\Models\Plan::find($dashCompany->plan) : null;
            $dashExpireLabel = !empty($dashCompany->plan_expire_date) ? $dashCompany->dateFormat($dashCompany->plan_expire_date) : null;
            $dashRenewUrl = ($dashPlan && (float) $dashPlan->price > 0)
                ? route('stripe', \Illuminate\Support\Facades\Crypt::encrypt($dashPlan->id))
                : route('plans.index');
        @endphp

        @if($showDashSubAlert)
            <div class="col-12 mb-3">
                <div class="alert crm-sub-dash-alert {{ $dashExpired ? 'is-expired' : 'is-warning' }} d-flex flex-wrap align-items-center justify-content-between gap-3 mb-0 p-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 {{ $dashExpired ? 'bg-danger text-white' : 'bg-warning text-dark' }}"
                             style="width:48px;height:48px;">
                            <i class="ti ti-alert-triangle fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1 {{ $dashExpired ? 'text-danger' : 'text-dark' }}">
                                {{ $dashExpired ? __('Your Subscription Has Expired') : __('Your Subscription Is Expiring Soon') }}
                            </h5>
                            <p class="mb-0 text-muted">
                                @if($dashExpired)
                                    {{ __('Plan') }}: <strong>{{ $dashPlan->name ?? __('N/A') }}</strong>
                                    · {{ __('Expired on') }} <strong>{{ $dashExpireLabel }}</strong>.
                                    {{ __('Renew to restore access to all HR modules.') }}
                                @else
                                    {{ __('Plan') }}: <strong>{{ $dashPlan->name ?? __('N/A') }}</strong>
                                    · {{ __('Expires on') }} <strong>{{ $dashExpireLabel }}</strong>
                                    ({{ (int) $dashDaysLeft }} {{ __('day(s) left') }}).
                                    {{ __('Please renew before it expires.') }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#subscriptionExpiryModal">
                            {{ __('View Details') }}
                        </button>
                        <a href="{{ $dashRenewUrl }}" class="btn btn-warning btn-sm text-dark fw-semibold">
                            <i class="ti ti-refresh me-1"></i>{{ __('Renew Subscription') }}
                        </a>
                        <a href="{{ route('plans.index') }}" class="btn btn-primary btn-sm">
                            {{ __('See Subscription Plans') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- statistics --}}
        @if (\Auth::user()->type != 'employee')
            {{-- start --}}
            <div class="col-xxl-12">
                <div class="row">

                    <div class="col-xxl-3 col-md-4 col-sm-6">
                        <div class="card dashboard-stat-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon-wrapper stat-icon-primary">
                                            <i class="ti ti-users"></i>
                                        </div>
                                        <div class="ms-3">
                                            <div class="stat-label-caption">{{ __('Total') }}</div>
                                            <h6 class="stat-title">{{ __('Staff') }}</h6>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <h4 class="stat-number text-primary">{{ $countUser }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-4 col-sm-6">
                        <div class="card dashboard-stat-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon-wrapper stat-icon-info">
                                            <i class="ti ti-ticket"></i>
                                        </div>
                                        <div class="ms-3">
                                            <div class="stat-label-caption">{{ __('Total') }}</div>
                                            <h6 class="stat-title">{{ __('Ticket') }}</h6>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <h4 class="stat-number text-info">{{ $countTicket }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-4 col-sm-6">
                        <div class="card dashboard-stat-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon-wrapper stat-icon-warning">
                                            <i class="ti ti-wallet"></i>
                                        </div>
                                        <div class="ms-3">
                                            <div class="stat-label-caption">{{ __('Total') }}</div>
                                            <h6 class="stat-title">{{ __('Employee') }}</h6>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <h4 class="stat-number text-warning">{{ $employeees }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-4 col-sm-6">
                        <div class="card dashboard-stat-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon-wrapper stat-icon-primary">
                                            <i class="ti ti-briefcase"></i>
                                        </div>
                                        <div class="ms-3">
                                            <div class="stat-label-caption">{{ __('Total') }}</div>
                                            <h6 class="stat-title">{{ __('Jobs') }}</h6>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <h4 class="stat-number text-primary">{{ $activeJob + $inActiveJOb }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-4 col-sm-6">
                        <div class="card dashboard-stat-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon-wrapper stat-icon-success">
                                            <i class="ti ti-circle-check"></i>
                                        </div>
                                        <div class="ms-3">
                                            <div class="stat-label-caption">{{ __('Total') }}</div>
                                            <h6 class="stat-title">{{ __('Active Jobs') }}</h6>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <h4 class="stat-number text-success">{{ $activeJob }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-4 col-sm-6">
                        <div class="card dashboard-stat-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon-wrapper stat-icon-warning">
                                            <i class="ti ti-circle-x"></i>
                                        </div>
                                        <div class="ms-3">
                                            <div class="stat-label-caption">{{ __('Total') }}</div>
                                            <h6 class="stat-title">{{ __('Inactive Jobs') }}</h6>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <h4 class="stat-number text-warning">{{ $inActiveJOb }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{-- end --}}

            @include('dashboard.partials.login_analytics')
        @endif
        {{-- statistics --}}


        {{-- clock in out --}}
        @if (\Auth::user()->type != 'company')
            <div class="col-xxl-12">
                <div class="card dashboard-clock-card">
                    <div class="card-header">
                        <h5><i class="ti ti-clock text-primary"></i> {{ __('Mark Attendance') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="shift-info-bar">
                            <span class="shift-badge shift-badge-primary">
                                <i class="ti ti-sun-moon"></i>
                                {{ __('My') }} {{ $officeTime['text'] }} {{ __('Time') }}:
                                {{ date('h:i A', strtotime($officeTime['startTime'])) }}
                                {{ __('to') }}
                                {{ date('h:i A', strtotime($officeTime['endTime'])) }}
                            </span>
                            @if ($officeTime['flexiHours'] || $officeTime['flexiDays'])
                                <span class="shift-badge shift-badge-subtle">
                                    <i class="ti ti-calendar-time"></i>
                                    @if ($officeTime['flexiHours'])
                                        {{ __('Company Flexi Time') }}: {{ $officeTime['flexiHours'] }}
                                    @endif
                                    @if ($officeTime['flexiDays'] && $officeTime['usedFlexiHoursDays'])
                                        @if ($officeTime['flexiHours']), @endif
                                        {{ __('Company Flexi Days') }}: {{ $officeTime['usedFlexiHoursDays'] }} / {{ $officeTime['flexiDays'] }}
                                    @endif
                                </span>
                            @endif
                        </div>

                        <div class="row">
                            @php
                                $clickInOutUrl = 'sync-clock-in-clock-out';
                                if (!empty($employeeDetails) && $employeeDetails->geofencing_status == 0) {
                                    $clickInOutUrl = 'attendanceemployee/attendance';
                                }
                            @endphp

                            <div class="col-12">
                                <div class="clock-actions-wrapper">
                                    {{-- Clock In Code --}}
                                    {{ Form::open(['url' => $clickInOutUrl, 'method' => 'post', 'class' => 'd-inline']) }}
                                    {!! Form::hidden('clock_type', 0) !!}

                                    @php
                                        $clockInDisabled = '';
                                    @endphp

                                    {!! Form::hidden('webClockInOut', 1) !!}

                                    @if ($checkLastAttendance == null)
                                        @php
                                            $clockInDisabled = '';
                                        @endphp
                                    @else
                                        @if ($checkLastAttendance->clock_out != '00:00:00')
                                            @php
                                                $clockInDisabled = '';
                                            @endphp
                                        @else
                                            @php
                                                $clockInDisabled = 'disabled';
                                            @endphp
                                        @endif
                                    @endif

                                    <button type="submit" value="0" name="in" id="clock_in" class="btn btn-clock-in" {{ $clockInDisabled }}>
                                        <i class="ti ti-player-play"></i> {{ __('CLOCK IN') }}
                                    </button>

                                    {{ Form::close() }}
                                    {{-- Clock In Code --}}


                                    {{-- Clock Out Code --}}
                                    {{ Form::open(['url' => $clickInOutUrl, 'method' => 'post', 'class' => 'd-inline']) }}

                                    {!! Form::hidden('clock_type', 1) !!}

                                    @php
                                        $clockOutDisabled = '';
                                    @endphp

                                    {!! Form::hidden('webClockInOut', 1) !!}
                                    @if ($checkLastAttendance != null && $checkLastAttendance->clock_out == '00:00:00')
                                        @php
                                            $clockOutDisabled = '';
                                        @endphp
                                    @else
                                        @php
                                            $clockOutDisabled = 'disabled';
                                        @endphp
                                    @endif

                                    <button type="submit" value="1" name="out" id="clock_out" class="btn btn-clock-out" {{ $clockOutDisabled }}>
                                        <i class="ti ti-player-pause"></i> {{ __('CLOCK OUT') }}
                                    </button>

                                    {{ Form::close() }}
                                    {{-- Clock Out Code --}}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @endif
        {{-- clock in out --}}


        {{-- todays clock in & calander --}}
        @if (\Auth::user()->type != 'employee')

            <div class="col-xxl-12">
                <div class="row">
                    <div class="col-xl-5">

                        <div class="card dashboard-table-card">
                            <div class="card-header">
                                <h5><i class="ti ti-user-x text-danger"></i> {{ __("Today's Not Clock In") }}</h5>
                            </div>
                            <div class="card-body p-0" style="height: 324px; overflow-y: auto;">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Name') }}</th>
                                                <th class="text-end">{{ __('Status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @foreach ($notClockIns as $notClockIn)
                                                <tr>
                                                    <td class="font-weight-500">{{ $notClockIn->name }}</td>
                                                    <td class="text-end"><span class="absent-btn">{{ __('Absent') }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-7">
                        <div class="card calendar-card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h5><i class="ti ti-calendar-event text-primary"></i> {{ __('Calendar') }}</h5>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group mb-0">
                                            @if (isset($setting['is_enabled']) && $setting['is_enabled'] == 'on')
                                                <select class="form-select form-select-sm" name="calender_type" id="calender_type"
                                                    onchange="get_data()" style="border-radius: 8px;">
                                                    <option value="google_calender">{{ __('Google Calender') }}</option>
                                                    <option value="local_calender" selected="true">
                                                        {{ __('Local Calender') }}
                                                    </option>
                                                </select>
                                            @endif
                                            <input type="hidden" id="path_admin" value="{{ url('/') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body card-635">
                                <div id='calendar' class='calendar'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (!empty($employeesData))
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card dashboard-table-card">
                        <div class="card-header">
                            <h5><i class="ti ti-users text-primary"></i> {{ __('Employee List') }}</h5>
                        </div>
                        <div class="card-body p-0" style="height: 270px; overflow-y: auto;">
                            <div class="table-responsive">
                                <table class="table" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Branch') }}</th>
                                            <th>{{ __('Department') }}</th>
                                            <th>{{ __('Designation') }}</th>
                                            <th>{{ __('Date Of Joining') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employeesData as $employee)
                                            <tr>
                                                <td class="font-weight-500">{{ $employee->name }}</td>
                                                <td>{{ $employee->email }}</td>
                                                <td>
                                                    {{ !empty(\Auth::user()->getBranch($employee->branch_id)) ? \Auth::user()->getBranch($employee->branch_id)->name : '' }}
                                                </td>
                                                <td>
                                                    {{ !empty(\Auth::user()->getDepartment($employee->department_id)) ? \Auth::user()->getDepartment($employee->department_id)->name : '' }}
                                                </td>
                                                <td>
                                                    {{ !empty(\Auth::user()->getDesignation($employee->designation_id)) ? \Auth::user()->getDesignation($employee->designation_id)->name : '' }}
                                                </td>
                                                <td>
                                                    {{ \Auth::user()->dateFormat($employee->company_doj) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
        {{-- todays clock in & calander --}}


        {{-- Announcements --}}
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card dashboard-table-card">
                <div class="card-header">
                    <h5><i class="ti ti-speakerphone text-info"></i> {{ __('Announcement List') }}</h5>
                </div>
                <div class="card-body p-0" style="height: 270px; overflow-y: auto;">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th>{{ __('Start Date') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Description') }}</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                @foreach ($announcements as $announcement)
                                    <tr>
                                        <td class="font-weight-500">{{ $announcement->title }}</td>
                                        <td>{{ \Auth::user()->dateFormat($announcement->start_date) }}</td>
                                        <td>{{ \Auth::user()->dateFormat($announcement->end_date) }}</td>
                                        <td>{{ $announcement->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Announcements --}}


        {{-- Birthday List --}}
        @if (!empty($employeesBirthdayList))
            <div class="col-xxl-4 col-md-6">
                <div class="card celebration-card">
                    <div class="card-header">
                        <h5><i class="ti ti-cake text-warning"></i> {{ __('Upcoming Birthdays List') }}</h5>
                    </div>
                    <div class="card-body p-0" style="height: 380px; overflow-y: auto;">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-left">{{ __('Name') }}</th>
                                        <th class="text-end">{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @foreach ($employeesBirthdayList as $employeesBirthdayListRow)
                                        <tr>
                                            <td class="text-left">
                                                <img src="{{ !empty($employeesBirthdayListRow->employee_profile_image) ? $employeesBirthdayListRow->employee_profile_image : asset(Storage::url('uploads/avatar')) . '/avatar.png' }}"
                                                    class="celebration-avatar"
                                                    onerror="this.onerror=null; this.src='{{ asset(Storage::url('uploads/avatar')) }}/avatar.png';">
                                                <span class="celebration-name">{{ $employeesBirthdayListRow->name }}</span>
                                            </td>

                                            <td class="text-end">
                                                @if ($employeesBirthdayListRow->getRaworiginal('dob') == date('Y-m-d'))
                                                    <span class="celebration-badge-today">{{ __('Today') }}</span>
                                                @else
                                                    <span class="celebration-badge-date">{{ date('d-M', strtotime($employeesBirthdayListRow->getRaworiginal('dob'))) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- Birthday List --}}

        {{-- Work Anniversary List --}}
        @if (!empty($workAnniversaryList))
            <div class="col-xxl-4 col-md-6">
                <div class="card celebration-card">
                    <div class="card-header">
                        <h5><i class="ti ti-award text-success"></i> {{ __("Today's Work Anniversary List") }}</h5>
                    </div>
                    <div class="card-body p-0" style="height: 380px; overflow-y: auto;">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: left">{{ __('Name') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @foreach ($workAnniversaryList as $workAnniversaryListRow)
                                        <tr>
                                            <td style="text-align: left">
                                                <img src="{{ !empty($workAnniversaryListRow->employee_profile_image) ? $workAnniversaryListRow->employee_profile_image : asset(Storage::url('uploads/avatar')) . '/avatar.png' }}"
                                                    class="celebration-avatar"
                                                    onerror="this.onerror=null; this.src='{{ asset(Storage::url('uploads/avatar')) }}/avatar.png';">
                                                <span class="celebration-name">{{ $workAnniversaryListRow->name }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- Work Anniversary List --}}


        {{-- Marriage Anniversary List --}}
        @if (!empty($marriageAnniversaryList))
            <div class="col-xxl-4 col-md-6">
                <div class="card celebration-card">
                    <div class="card-header">
                        <h5><i class="ti ti-heart text-danger"></i> {{ __("Today's Marriage Anniversary List") }}</h5>
                    </div>
                    <div class="card-body p-0" style="height: 380px; overflow-y: auto;">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: left">{{ __('Name') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @foreach ($marriageAnniversaryList as $marriageAnniversaryListRow)
                                        <tr>
                                            <td style="text-align: left">
                                                <img src="{{ !empty($marriageAnniversaryListRow->employee_profile_image) ? $marriageAnniversaryListRow->employee_profile_image : asset(Storage::url('uploads/avatar')) . '/avatar.png' }}"
                                                    class="celebration-avatar"
                                                    onerror="this.onerror=null; this.src='{{ asset(Storage::url('uploads/avatar')) }}/avatar.png';">
                                                <span class="celebration-name">{{ $marriageAnniversaryListRow->name }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        {{-- Marriage Anniversary List --}}

    </div>
@endsection

@push('script-page')
    <script src="{{ asset('assets/js/plugins/main.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            get_data();
        });

        function get_data() {
            var calender_type = $('#calender_type :selected').val();

            $('#calendar').removeClass('local_calender');
            $('#calendar').removeClass('google_calender');
            if (calender_type == undefined) {
                calender_type = 'local_calender';
            }
            $('#calendar').addClass(calender_type);

            $.ajax({
                url: $("#path_admin").val() + "/event/get_event_data",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'calender_type': calender_type
                },
                success: function (data) {
                    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        buttonText: {
                            timeGridDay: "{{ __('Day') }}",
                            timeGridWeek: "{{ __('Week') }}",
                            dayGridMonth: "{{ __('Month') }}"
                        },
                        themeSystem: 'bootstrap',
                        slotDuration: '00:10:00',
                        navLinks: true,
                        droppable: true,
                        selectable: true,
                        selectMirror: true,
                        editable: true,
                        dayMaxEvents: true,
                        handleWindowResize: true,
                        events: data,
                    });
                    calendar.render();
                }
            });
        };
    </script>
@endpush