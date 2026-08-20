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

@can('Create User')

@if (\Auth::user()->type == 'super admin')

<a href="#" data-url="{{ route('user.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Company') }}" data-size="md" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">

     <i class="ti ti-plus"></i>

</a>

@else

<a href="#" data-url="{{ route('user.create') }}" data-ajax-popup="true" data-title="{{ __('Create New User') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">

     <i class="ti ti-plus"></i>

</a>

@endif

@endcan



@endsection





@php

$logo=\App\Models\Utility::get_file('uploads/avatar/');

// $profile = asset(Storage::url('uploads/avatar/'));



$profile=\App\Models\Utility::get_file('uploads/avatar/');

@endphp

@section('content')

<div class="row">

     <div class="col-xl-12">
          <div class="card">

               <div class="card-header card-body table-border-style">



@if (\Auth::user()->type == 'super admin')
                    <div class="table-responsive">
                         <table class="table" id="pc-dt-simple">

                              <thead>
                                   <tr>
                                        <th> {{ __('SR.NO') }}</th>
                                        <th> {{ __('Image') }}</th>
                                        <th> {{ __('Name') }}</th>
                                        <th> {{ __('Email') }}</th>
                                        <!-- <th> {{ __('Role') }}</th> -->
                                        <th> {{ __('Current plan name') }}</th>
                                        <th> {{ __('Plan Start Date') }}</th>


                                        <th width="200px"> {{ __('Plan Expire Date') }}</th>
                                        <th width="200px"> {{ __('Upgrade Plan') }}</th>
                                        <th width="200px"> {{ __('Action') }}</th>
                                   </tr>
                              </thead>

                              <tbody>
                                   @foreach ($users as $key => $user)
                                   <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>
                                             <div class="avatar">

                                                  <a href="{{ !empty($user->avatar) ? ($profile . $user->avatar) :  $logo."avatar.png" }}" target="_blank">

                                                       <img src="{{ !empty($user->avatar) ? ($profile . $user->avatar) :  $logo."avatar.png" }}" class="rounded-circle" style="width: 5%">

                                                  </a>

                                             </div>
                                        </td>

                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ !empty($user->currentPlan) ? $user->currentPlan->name : '' }}</td>
                                        <td>{{ \Auth::user()->dateFormat($user->plan_start_date) }}</td>
                                        <td>{{ \Auth::user()->dateFormat($user->plan_expire_date) }}</td>
                                        <!-- <td>{{ \Auth::user()->countUsers() }}</td>
                                    <td>{{ \Auth::user()->countEmployees() }}</td> -->
                                        <td>
                                             <div class="col-7 text-end">

                                                  <a href="#" data-url="{{ route('plan.upgrade', $user->id) }}" class="btn btn-sm btn-primary btn-icon" data-ajax-popup="true" data-title="{{ __('Upgrade Plan') }}">{{ __('Upgrade Plan') }}

                                                  </a>

                                             </div>
                                        </td>

                                        <td class="Action">

                                             <div class="action-btn bg-success ms-2">
                                                  <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('mobile_assign_menu',$user->id) }}" data-ajax-popup="true" data-title="{{ __('Mobile Menu') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Mobile Menu') }}">
                                                       <i class="ti ti-key"></i>
                                                  </a>
                                             </div>

                                             <div class="action-btn bg-warning ms-2">
                                                  <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}" data-ajax-popup="true" data-title="{{ __('Change Password') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Change Password') }}">
                                                       <i class="ti ti-key"></i>
                                                  </a>
                                             </div>
                                             @can('Edit User')
                                             <div class="action-btn bg-info ms-2">
                                                  <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('user.edit', $user->id) }}" data-ajax-popup="true" data-title="{{ __('Edit Company') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}">
                                                       <i class="ti ti-pencil text-white"></i>
                                                  </a>
                                             </div>
                                             @endcan
                                             @can('Delete User')
                                             <div class="action-btn bg-danger ms-2">
                                                  {!! Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id], 'id' => 'delete-form-' . $user->id]) !!}
                                                  <a href="#!" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('Delete') }}">
                                                       <i class="ti ti-trash text-white"></i></a>
                                                  {!! Form::close() !!}
                                             </div>
                                             @endcan
                    </div>


                    </td>
                    </tr>
                    @endforeach
                    </tbody>
                    </table>
               </div>
          </div>
     </div>
</div>




@else

<div class="table-responsive">
     <table class="table" id="pc-dt-simple">

          <thead>
               <tr>
                    <th> {{ __('SR.NO') }}</th>
                    <th> {{ __('Image') }}</th>
                    <th> {{ __('Name') }}</th>
                    <th> {{ __('Email') }}</th>
                    <th> {{ __('Role') }}</th>
                    <th> {{ __('Status') }}</th>
                    <th width="200px"> {{ __('Action') }}</th>
               </tr>
          </thead>

          <tbody>
               @foreach ($users as $key => $user)
               <tr>
                    <td>{{ $key+1 }}</td>
                    <td>
                         <div class="avatar">

                              <a href="{{ !empty($user->avatar) ? ($profile . $user->avatar) :  $logo."avatar.png" }}" target="_blank">

                                   <img src="{{ !empty($user->avatar) ? ($profile . $user->avatar) :  $logo."avatar.png" }}" class="rounded-circle" style="width: 5%">

                              </a>

                         </div>
                    </td>

                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                         <?php $data = App\Models\RolesManagment::find($user->type); ?>
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
                                   {!! Form::open(['method' => 'get','route' => ['active_user', $user->id], 'id' => 'delete-form-' . $user->id]) !!}
                                   <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Active" aria-label="Active"><i class="ti ti-check  text-white text-white"></i></a>
                                   </form>
                              </div>
                              @else

                              <div class="action-btn bg-success ms-2">
                                   {!! Form::open(['method' => 'get','route' => ['inactive_user', $user->id], 'id' => 'delete-form-' . $user->id]) !!}
                                   <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="InActive" aria-label="InActive"><i class="ti ti-check  text-white text-white"></i></a>
                                   </form>
                              </div>
                              @endif
                         @endif
                         
                    </td>
                    <td class="Action">
                         
                         @if ($user->id != $logged_in_user_id)
                         
                              @can('Edit User')
                                   <div class="action-btn bg-info ms-2">
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('user.edit', $user->id) }}" data-ajax-popup="true" data-title="{{ __('Edit User') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Edit') }}">
                                             <i class="ti ti-pencil text-white"></i>
                                        </a>
                                   </div>
                              @endcan

                              @can('Delete User')
                                   <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id], 'id' => 'delete-form-' . $user->id]) !!}
                                        <a href="#!" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('Delete') }}">
                                             <i class="ti ti-trash text-white"></i></a>
                                        {!! Form::close() !!}
                                   </div>
                              @endcan
                         @endif
                         </div>
                    </td>
</tr>
@endforeach
</tbody>
</table>
</div>




{{--@foreach ($users as $user)

            <div class="col-xl-3">

                <div class="card  text-center">

                    <div class="card-header border-0 pb-0">

                        @if (Gate::check('Edit User') || Gate::check('Delete User'))

                            <div class="card-header-right">

                                <div class="btn-group card-option">

                                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"

                                        aria-haspopup="true" aria-expanded="false">

                                        <i class="feather icon-more-vertical"></i>

                                    </button>

                                    <div class="dropdown-menu dropdown-menu-end">

                                        @can('Edit User')

                                            <a href="#" class="dropdown-item" data-url="{{ route('user.edit', $user->id) }}"

data-size="md" data-ajax-popup="true" data-title="{{ __('Update User') }}"><i class="ti ti-edit "></i><span class="ms-2">{{ __('Edit') }}</span></a>

@endcan







<a href="#" class="dropdown-item" data-ajax-popup="true" data-size="md" data-title="{{ __('Change Password') }}" data-url="{{ route('user.reset', \Crypt::encrypt($user->id)) }}"><i class="ti ti-key"></i>

     <span class="ms-1">{{ __('Reset Password') }}</span></a>



@can('Delete User')

{!! Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id], 'id' => 'delete-form-' . $user->id]) !!}

<a href="#" class="bs-pass-para dropdown-item" data-confirm="{{ __('Are You Sure?') }}" data-text="{{ __('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="delete-form-{{ $user->id }}" title="{{ __('Delete') }}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-trash"></i><span class="ms-2">{{ __('Delete') }}</span></a>

{!! Form::close() !!}

@endcan

</div>

</div>

</div>

@endif

</div>

<div class="card-body">

     <div class="avatar">

          <a href="{{ !empty($user->avatar) ?($profile . $user->avatar) :  $logo."avatar.png" }}" target="_blank">

               <img src="{{ !empty($user->avatar) ? ($profile . $user->avatar) :  $logo."avatar.png" }}" class="rounded-circle" style="width: 30%">

          </a>



     </div>

     <h4 class="mt-2 text-primary">{{ $user->name }}</h4>

     <small class="">{{ $user->email }}</small>



</div>

</div>

</div>

@endforeach--}}



@endif

</div>

</div>

@endsection