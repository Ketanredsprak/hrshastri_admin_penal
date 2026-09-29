@extends('layouts.admin')

@section('page-title')
    @if (\Auth::user()->type == 'super admin')
        {{ __('Manage Companies') }}
    @else
        {{ __('Manage Users') }}
    @endif
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    @if (\Auth::user()->type == 'super admin')
        <li class="breadcrumb-item">{{ __('Companies') }}</li>
    @else
        <li class="breadcrumb-item">{{ __('Users') }}</li>
    @endif
@endsection

@section('action-button')
    <div class="d-flex align-items-center gap-2">
        @if (\Auth::user()->type == 'super admin')
            <a href="{{ route('subscription.sync') }}" class="btn btn-sm btn-outline-primary shadow-sm" data-bs-toggle="tooltip" title="{{ __('Manage company capacity limits & sync subscription data') }}">
                <i class="ti ti-refresh me-1"></i> {{ __('Sync & Limits Hub') }}
            </a>
            @can('Create User')
                <a href="#" data-url="{{ route('user.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Company') }}" data-size="md" data-bs-toggle="tooltip" title="{{ __('Create New Company') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="ti ti-plus me-1"></i> {{ __('Create Company') }}
                </a>
            @endcan
        @else
            @can('Create User')
                <a href="#" data-url="{{ route('user.create') }}" data-ajax-popup="true" data-title="{{ __('Create New User') }}" data-size="md" data-bs-toggle="tooltip" title="{{ __('Create New User') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="ti ti-plus me-1"></i> {{ __('Create User') }}
                </a>
            @endcan
        @endif
    </div>
@endsection

@php
    $logo = \App\Models\Utility::get_file('uploads/avatar/');
    $profile = \App\Models\Utility::get_file('uploads/avatar/');
@endphp

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">

                @if (\Auth::user()->type == 'super admin')
                    {{-- Super Admin: Companies List with Employee & User Capacity Limits --}}
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('SR.NO') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Company Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Plan') }}</th>
                                    <th>{{ __('Employee Limit & Usage') }}</th>
                                    <th>{{ __('User Limit & Usage') }}</th>
                                    <th>{{ __('Plan Expiry') }}</th>
                                    <th>{{ __('Upgrade Plan') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    @php
                                        $empCount = $user->countEmployees();
                                        $empMax = $user->getMaxEmployees();
                                        $userCount = $user->countUsers();
                                        $userMax = $user->getMaxUsers();
                                        $hasCustomEmp = ($user->custom_max_employees !== null);
                                        $hasCustomUser = ($user->custom_max_users !== null);
                                        $isEmpExceeded = ($empMax != -1 && $empCount > $empMax);
                                        $isUserExceeded = ($userMax != -1 && $userCount > $userMax);
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <div class="avatar">
                                                <a href="{{ !empty($user->avatar) ? ($profile . $user->avatar) : $logo . 'avatar.png' }}" target="_blank">
                                                    <img src="{{ !empty($user->avatar) ? ($profile . $user->avatar) : $logo . 'avatar.png' }}" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $user->name }}</span>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if(!empty($user->currentPlan))
                                                <span class="badge bg-light-primary text-primary border border-primary-subtle fw-semibold">
                                                    {{ $user->currentPlan->name }}
                                                </span>
                                                <small class="text-muted d-block fs-8">{{ ucfirst($user->currentPlan->duration) }}</small>
                                            @else
                                                <span class="badge bg-light-secondary text-secondary">{{ __('No Plan') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                                <span class="badge {{ $isEmpExceeded ? 'bg-light-danger text-danger border border-danger-subtle' : 'bg-light-info text-info border border-info-subtle' }} px-2 py-1 fs-7">
                                                    <i class="ti ti-user-check me-1"></i>
                                                    <strong>{{ $empCount }}</strong> / {{ $empMax == -1 ? __('Unlimited') : $empMax }}
                                                </span>
                                                @if($hasCustomEmp)
                                                    <span class="badge bg-light-warning text-warning border border-warning-subtle" style="font-size: 10px;" data-bs-toggle="tooltip" title="{{ __('Custom limit set by Admin') }}: {{ $user->custom_max_employees }}">
                                                        <i class="ti ti-adjustments"></i> {{ __('Custom') }}
                                                    </span>
                                                @endif
                                                @if($isEmpExceeded)
                                                    <span class="badge bg-danger text-white" style="font-size: 10px;" data-bs-toggle="tooltip" title="{{ __('Exceeding limit by ') . ($empCount - $empMax) }}">
                                                        <i class="ti ti-alert-triangle"></i> {{ __('Over Limit') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                                <span class="badge {{ $isUserExceeded ? 'bg-light-danger text-danger border border-danger-subtle' : 'bg-light-success text-success border border-success-subtle' }} px-2 py-1 fs-7">
                                                    <i class="ti ti-users me-1"></i>
                                                    <strong>{{ $userCount }}</strong> / {{ $userMax == -1 ? __('Unlimited') : $userMax }}
                                                </span>
                                                @if($hasCustomUser)
                                                    <span class="badge bg-light-warning text-warning border border-warning-subtle" style="font-size: 10px;" data-bs-toggle="tooltip" title="{{ __('Custom limit set by Admin') }}: {{ $user->custom_max_users }}">
                                                        <i class="ti ti-adjustments"></i> {{ __('Custom') }}
                                                    </span>
                                                @endif
                                                @if($isUserExceeded)
                                                    <span class="badge bg-danger text-white" style="font-size: 10px;" data-bs-toggle="tooltip" title="{{ __('Exceeding limit by ') . ($userCount - $userMax) }}">
                                                        <i class="ti ti-alert-triangle"></i> {{ __('Over Limit') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if(!empty($user->plan_expire_date))
                                                @php
                                                    $isExpired = $user->plan_expire_date < date('Y-m-d');
                                                @endphp
                                                <span class="badge {{ $isExpired ? 'bg-light-danger text-danger' : 'bg-light-secondary text-secondary' }}">
                                                    {{ \Auth::user()->dateFormat($user->plan_expire_date) }}
                                                </span>
                                                @if($isExpired)
                                                    <small class="text-danger d-block fs-8 fw-semibold">{{ __('Expired') }}</small>
                                                @endif
                                            @else
                                                <span class="badge bg-light-success text-success">{{ __('Unlimited') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#" data-url="{{ route('plan.upgrade', $user->id) }}" class="btn btn-sm btn-outline-primary btn-icon" data-ajax-popup="true" data-title="{{ __('Upgrade Plan') }}" data-bs-toggle="tooltip" title="{{ __('Upgrade Subscription Plan') }}">
                                                <i class="ti ti-arrow-up-circle me-1"></i> {{ __('Upgrade') }}
                                            </a>
                                        </td>
                                        <td class="Action">
                                            <div class="action-btn bg-success ms-2">
                                                <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('mobile_assign_menu', $user->id) }}" data-ajax-popup="true" data-title="{{ __('Mobile Menu') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Mobile Menu') }}">
                                                    <i class="ti ti-device-mobile text-white"></i>
                                                </a>
                                            </div>

                                            <div class="action-btn bg-warning ms-2">
                                                <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}" data-ajax-popup="true" data-title="{{ __('Change Password') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Change Password') }}">
                                                    <i class="ti ti-key text-white"></i>
                                                </a>
                                            </div>

                                            @can('Edit User')
                                            <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('user.edit', $user->id) }}" data-ajax-popup="true" data-size="lg" data-title="{{ __('Edit Company & Capacity Limits') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit Company & Limits') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            @endcan

                                            @can('Delete User')
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id], 'id' => 'delete-form-' . $user->id]) !!}
                                                <a href="#!" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('Delete') }}">
                                                    <i class="ti ti-trash text-white"></i>
                                                </a>
                                                {!! Form::close() !!}
                                            </div>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    {{-- Non-Super Admin: Users List --}}
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('SR.NO') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <div class="avatar">
                                                <a href="{{ !empty($user->avatar) ? ($profile . $user->avatar) : $logo . 'avatar.png' }}" target="_blank">
                                                    <img src="{{ !empty($user->avatar) ? ($profile . $user->avatar) : $logo . 'avatar.png' }}" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @php $data = App\Models\RolesManagment::find($user->type); @endphp
                                            @if(!empty($data))
                                                {{ $data->name }}
                                            @else
                                                {{ $user->type }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->id != $logged_in_user_id)
                                                @if ($user->login_status == 0)
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'get', 'route' => ['active_user', $user->id], 'id' => 'delete-form-' . $user->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{ __('Active') }}">
                                                            <i class="ti ti-check text-white"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @else
                                                    <div class="action-btn bg-success ms-2">
                                                        {!! Form::open(['method' => 'get', 'route' => ['inactive_user', $user->id], 'id' => 'delete-form-' . $user->id]) !!}
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{ __('InActive') }}">
                                                            <i class="ti ti-check text-white"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="Action">
                                            @if ($user->id != $logged_in_user_id)
                                                @can('Edit User')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('user.edit', $user->id) }}" data-ajax-popup="true" data-title="{{ __('Edit User') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan

                                                @can('Delete User')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id], 'id' => 'delete-form-' . $user->id]) !!}
                                                        <a href="#!" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('Delete') }}">
                                                            <i class="ti ti-trash text-white"></i>
                                                        </a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection