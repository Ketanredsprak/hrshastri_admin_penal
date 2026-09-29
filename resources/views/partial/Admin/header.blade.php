@php
    use App\Models\Utility;
    $users = \Auth::user();
    $currantLang = $users->currentLanguage();
    $languages = \App\Models\Utility::languages();
    $profile = \App\Models\Utility::get_file('uploads/avatar/');
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_logo = \App\Models\Utility::GetLogo();
    $data_branches = \App\Models\Branch::where('created_by', '=', \Auth::user()->creatorId())->get();
    $isCompanyWithoutSub = isset($isCompanyWithoutSub) ? $isCompanyWithoutSub : (\Auth::check() && \Auth::user()->type == 'company' && !\Auth::user()->hasActiveSubscription());
    $isImpersonating = session()->has('admin_impersonator_id') || session()->has('impersonated_by');
@endphp

<style>
    select#branch {
        width: 169px;
    }

    .text-left {
        text-align: left;
    }

    .text-center {
        text-align: center;
    }

    .text-right {
        text-align: right;
    }
</style>

@if ($isImpersonating)
    <!-- Sleek Black Top Impersonation Banner -->
    <div class="impersonate-top-black-banner">
        <div class="impersonate-banner-container">
            <div class="impersonate-banner-left">
                <span class="impersonate-live-badge">
                    <span class="header-pulse-dot"></span>
                    <i class="ti ti-shield-lock"></i> {{ __('IMPERSONATING') }}
                </span>
                <span class="impersonate-banner-text">
                    {{ __('Currently logged in as:') }} <strong class="text-white">{{ Auth::user()->name }}</strong>
                    <span class="impersonate-banner-type-badge">{{ ucfirst(Auth::user()->type) }}</span>
                    <span class="impersonate-banner-email">({{ Auth::user()->email }})</span>
                </span>
            </div>
            <div class="impersonate-banner-right">
                <button type="button" class="btn-banner-switch" data-bs-toggle="modal" data-bs-target="#impersonateModal" title="{{ __('Switch to another user') }}">
                    <i class="ti ti-switch-horizontal"></i>
                    <span>{{ __('Switch User') }}</span>
                </button>
                <a href="{{ route('impersonate.exit') }}" class="btn-banner-back-admin" title="{{ __('Back to Super Admin Account') }}">
                    <i class="ti ti-arrow-back-up"></i>
                    <span>{{ __('Back to Super Admin') }}</span>
                </a>
            </div>
        </div>
    </div>
@endif

@if (isset($setting['cust_theme_bg']) && $setting['cust_theme_bg'] == 'on')
    <header class="dash-header transprent-bg">
@else
    <header class="dash-header">
@endif
        <div class="header-wrapper">
            <div class="me-auto dash-mob-drp">
                <ul class="list-unstyled">
                    @if ($isCompanyWithoutSub)
                        <li class="dash-h-item d-flex align-items-center">
                            <a href="{{ route('plans.index') }}" class="b-brand d-flex align-items-center me-3">
                                <img src="{{ $logo . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}"
                                    alt="{{ config('app.name', 'HRMGo') }}" class="logo logo-lg"
                                    style="height: 38px;">
                            </a>
                        </li>
                    @else
                        <li class="dash-h-item mob-hamburger">
                            <a href="#!" class="dash-head-link" id="mobile-collapse">
                                <div class="hamburger hamburger--arrowturn">
                                    <div class="hamburger-box">
                                        <div class="hamburger-inner"></div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="ms-auto">
                <ul class="list-unstyled header-toolbar-list d-flex align-items-center mb-0 gap-2">

                    @if (\Auth::user()->type == 'company' && (\Auth::user()->isSubscriptionExpiringSoon(5) || !\Auth::user()->hasActiveSubscription()) && !\Auth::user()->isExemptFromPlanExpiry())
                        <li class="dash-h-item">
                            <button type="button" class="btn btn-sm btn-warning text-dark fw-semibold"
                                data-bs-toggle="modal" data-bs-target="#subscriptionExpiryModal">
                                <i class="ti ti-refresh"></i>
                                <span class="hide-mob">{{ __('Renew Plan') }}</span>
                            </button>
                        </li>
                    @endif

                    @php
                        $unreadCounter = App\Models\ChMessage::where('to_id', Auth::user()->id)
                            ->where('seen', 0)
                            ->count();
                    @endphp

                    @if (\Auth::user()->type != 'super admin')
                        <li class="dropdown dash-h-item drp-notification">
                            <a class="dash-head-link dropdown-toggle arrow-none me-0 header-btn-icon" data-bs-toggle="dropdown"
                                href="#" role="button" aria-haspopup="false" aria-expanded="false" id="msg-btn">
                                <i class="ti ti-bell"></i>
                                @if (!empty(bdaynotification()))
                                    <span class="bg-danger dash-h-badge message-counter custom_messanger_counter">
                                        {{ bdaynotification() }}
                                    </span>
                                @endif
                            </a>
                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                                <div class="noti-header">
                                    <h5 class="m-0">{{ __('Notifications') }}</h5>
                                    <a href="#"
                                        class="dash-head-link mark_all_as_read_message">{{ __('Clear All') }}</a>
                                </div>

                                <div class="noti-body dropdown-list-message-msg">
                                    <div style="display: flex;">
                                        <div class="notifications-wrapper">
                                            @php
                                                $employee = employeeBdayGet();
                                            @endphp
                                            @if (count($employee) > 0)
                                                @foreach ($employee as $row)
                                                    <a class="content" href="{{ redirectUrl($row) }}"
                                                        data-id="{{ $row->id }}" id="notification-redirect-url">
                                                        <div class="notification-item">
                                                            <h4>{{ $row->title }}</h4>
                                                            <span>{{ $row->details }}</span>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            @else
                                                <span>{{ __('You have no unread notifications') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif

                    @php
                        $currantLang = basename(\App::getLocale());
                    @endphp

                    <li class="dropdown dash-h-item drp-language">
                        <a class="dash-head-link dropdown-toggle arrow-none header-nav-btn" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false"
                            id="dropdownLanguage">
                            <i class="ti ti-world"></i>
                            <span class="drp-text hide-mob">{{ Str::upper($currantLang) }}</span>
                            <i class="ti ti-chevron-down drp-arrow"></i>
                        </a>
                        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                            aria-labelledby="dropdownLanguage">
                            <h6 class="dropdown-header text-muted font-weight-bold py-1" style="font-size: 11px;">
                                <i class="ti ti-language me-1"></i>{{ __('Select Language') }}
                            </h6>
                            <div class="dropdown-divider my-1"></div>
                            @foreach (App\Models\Utility::languages() as $lang)
                                <a href="{{ route('change.language', $lang) }}"
                                    class="dropdown-item {{ basename(App::getLocale()) == $lang ? 'active font-weight-bold' : '' }}">
                                    <i class="ti ti-check {{ basename(App::getLocale()) == $lang ? '' : 'invisible' }}" style="font-size: 13px;"></i>
                                    <span>{{ Str::upper($lang) }}</span>
                                </a>
                            @endforeach
                            @if (\Auth::user()->type == 'super admin')
                                <div class="dropdown-divider my-1"></div>
                                <a href="{{ route('manage.language', [basename(App::getLocale())]) }}"
                                    class="dropdown-item text-primary font-weight-bold">
                                    <i class="ti ti-settings"></i>
                                    <span>{{ __('Manage Language') }}</span>
                                </a>
                            @endif
                        </div>
                    </li>

                    @if (\Auth::user()->type == 'super admin' || $isImpersonating)
                        @if ($isImpersonating)
                            <!-- Black Color "Back to Super Admin" Button -->
                            <li class="dash-h-item header-impersonate-item">
                                <a href="{{ route('impersonate.exit') }}"
                                    class="header-back-superadmin-btn"
                                    title="{{ __('Back to Super Admin Account') }}">
                                    <span class="header-pulse-dot"></span>
                                    <i class="ti ti-arrow-back-up"></i>
                                    <span class="hide-mob">{{ __('Back to Super Admin') }}</span>
                                </a>
                            </li>
                            <li class="dash-h-item">
                                <button type="button"
                                    class="header-switch-user-btn"
                                    data-bs-toggle="modal" data-bs-target="#impersonateModal"
                                    title="{{ __('Switch Impersonated Account') }}">
                                    <i class="ti ti-switch-horizontal"></i>
                                    <span class="hide-mob">{{ __('Switch') }}</span>
                                </button>
                            </li>
                        @else
                            <li class="dash-h-item header-impersonate-item">
                                <button type="button"
                                    class="header-impersonate-btn"
                                    data-bs-toggle="modal" data-bs-target="#impersonateModal"
                                    title="{{ __('Impersonate User') }}">
                                    <i class="ti ti-user-check"></i>
                                    <span class="hide-mob">{{ __('Impersonate') }}</span>
                                </button>
                            </li>
                        @endif
                    @endif

                    @php
                        $employee = App\Models\Employee::select('*', 'employee_profile as employee_profile_image')
                            ->where('user_id', \Auth::user()->id)
                            ->first();

                        $imagePath = '';
                        if (Auth::user()->type == 'company') {
                            $imagePath = !empty($users->avatar)
                                ? $profile . $users->avatar
                                : $profile . 'avatar.png';
                        } else {
                            if (empty($employee->employee_profile)) {
                                $imagePath = $profile . 'avatar.png';
                            } else {
                                $imagePath = $employee->employee_profile_image;
                            }
                        }
                    @endphp

                    <li class="dropdown dash-h-item drp-company">
                        <a class="dash-head-link header-profile-btn dropdown-toggle arrow-none" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="theme-avtar header-profile-avatar">
                                <img alt="{{ Auth::user()->name }}" src="{{ $imagePath }}" class="header-avtar">
                            </span>
                            <span class="hide-mob header-profile-name">{{ Auth::user()->name }}</span>
                            <i class="ti ti-chevron-down drp-arrow hide-mob"></i>
                        </a>
                        <div class="dropdown-menu dash-h-dropdown dropdown-menu-end" style="min-width: 230px;">
                            @if ($isImpersonating)
                                <div class="dropdown-user-header" style="background: #09090b; border: 1px solid #27272a;">
                                    <img src="{{ $imagePath }}" class="dropdown-user-avatar" alt="" style="border-color: #3f3f46;">
                                    <div class="dropdown-user-info" style="min-width: 0;">
                                        <div class="d-flex align-items-center gap-1">
                                            <span class="badge bg-danger" style="font-size: 9.5px; padding: 1px 5px;"><i class="ti ti-shield-lock me-1"></i>Active</span>
                                            <span class="badge bg-dark text-white border border-secondary" style="font-size: 9.5px; padding: 1px 5px;">{{ ucfirst(Auth::user()->type) }}</span>
                                        </div>
                                        <div class="dropdown-user-name text-truncate text-white mt-1">{{ Auth::user()->name }}</div>
                                        <div class="text-white-50 text-truncate" style="font-size: 11px;">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('impersonate.exit') }}" class="dropdown-item text-danger font-weight-bold" style="background: #fef2f2;">
                                    <i class="ti ti-arrow-back-up"></i>
                                    <span>{{ __('Back to Super Admin') }}</span>
                                </a>
                                <div class="dropdown-divider my-1"></div>
                            @else
                                <div class="dropdown-user-header">
                                    <img src="{{ $imagePath }}" class="dropdown-user-avatar" alt="">
                                    <div class="dropdown-user-info" style="min-width: 0;">
                                        <div class="dropdown-user-name text-truncate">{{ Auth::user()->name }}</div>
                                        <span class="dropdown-user-badge">{{ Auth::user()->type }}</span>
                                    </div>
                                </div>
                                <div class="dropdown-divider my-1"></div>
                            @endif

                            @if (Auth::user()->type == 'company')
                                <a href="{{ route('profile') }}" class="dropdown-item">
                                    <i class="ti ti-user"></i>
                                    <span>{{ __('My Profile') }}</span>
                                </a>
                            @else
                                <a href="{{ route('employeeProfileShow', \Illuminate\Support\Facades\Crypt::encrypt($employee->id ?? 0)) }}"
                                    class="dropdown-item">
                                    <i class="ti ti-user"></i>
                                    <span>{{ __('My Profile') }}</span>
                                </a>

                                <a href="{{ route('profile') }}" class="dropdown-item">
                                    <i class="ti ti-key"></i>
                                    <span>{{ __('Change Password') }}</span>
                                </a>
                            @endif

                            <div class="dropdown-divider my-1"></div>
                            <a href="{{ route('logout') }}" class="dropdown-item text-danger"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ti ti-power"></i>
                                <span>{{ __('Logout') }}</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
    </header>

@push('scripts')
    {{-- @include('Chatify::layouts.modals') --}}
    <script>
        $("#notification-redirect-url").click(function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "{{ route('notification.status') }}",
                data: {
                    id: id,
                },
                dataType: "json",
                success: function (response) {
                    if (response.status == '1') {
                        console.log(url)
                        window.location.href = url;
                    } else {
                        console.log('Notification redirection errror');
                    }
                }
            });

        });
        $(".mark_all_as_read_message").click(function (e) {
            e.preventDefault();
            $.ajax({
                type: "get",
                url: "{{ route('clear-notification') }}",
                dataType: "json",
                success: function (response) {
                    if (response.status == '1') {
                        console.log(url)
                        window.location.reload();
                    } else {
                        console.log('Notification clearing error');
                    }
                },
                error: (error) => {
                    console.error(error);
                },
            });

        });
        $('#msg-btn').click(function () {
            let contactsPage = 1;
            let contactsLoading = false;
            let noMoreContacts = false;
            $.ajax({
                url: url + "/getContacts",
                method: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    page: contactsPage,
                    type: 'custom',
                },
                dataType: "JSON",
                success: (data) => {

                    if (contactsPage < 2) {
                        $(".count-listOfContacts").html(data.contacts);

                    } else {
                        $(".count-listOfContacts").append(data.contacts);
                    }
                    $('.count-listOfContacts').find('.messenger-list-item').each(function (e) {
                        $('.noti-body .activeStatus').remove()
                        $('.noti-body .avatar').remove()
                        $(this).find('span').remove()
                        $(this).find('p').addClass("d-inline")
                        // $(this).find('b').addClass('position-absolute')
                        // $(this).find('b').css({position: "absolute"});
                        $(this).find('b').css({
                            "position": "absolute",
                            "right": "50px"
                        });
                        $(this).find('tr').remove('td')

                    })
                },
                error: (error) => {
                    setContactsLoading(false);
                    console.error(error);
                },
            });
        })
    </script>
@endpush

@if (\Auth::user()->type == 'super admin' || $isImpersonating)
    @include('impersonate.modal')
@endif