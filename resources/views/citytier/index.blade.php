@extends('layouts.admin')

@section('page-title')
    {{ __('Manage City Tier') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}">{{ __('Home') }}</a>
    </li>
    <li class="breadcrumb-item">{{ __('City Tier') }}</li>
@endsection

@section('action-button')
    <div class="row align-items-center m-1">
        <div class="col-auto pe-0">
            <a href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Create Contract') }}" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Create City Tier') }}" data-url="{{ route('city_tier.create') }}"><i
                    class="ti ti-plus text-white"></i></a>
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('City Tier') }}</li>
@endsection

@section('content')
    <div class="row">
        {{--<div class="col-3">
            @include('layouts.hrm_setup')
        </div>--}}
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">

                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('No') }}</th>
                                    <th>{{ __('Tier Name') }}</th>
                                    <th>{{ __('City') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($citys as $key => $city)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $city->name }}</td>
                                        <td>
                                            @php
                                                $cities = DB::table('city')
                                                    ->whereIn('id', $city->citysid)
                                                    ->get();
                                            @endphp
                                            @foreach ($cities as $row)
                                                <span>{{ $row->name }}@if ($loop->last)
                                                    @else,
                                                    @endif
                                                </span>
                                            @endforeach
                                        </td>
                                        <td class="Action">
                                            <span>
                                                @can('Edit Contract Type')
                                                    <div class="action-btn btn-info ms-2">
                                                        <a href="#" data-size="md"
                                                            data-url="{{ URL::to('city_tier/' . $city->id . '/edit') }}"
                                                            data-ajax-popup="true" data-title="{{ __('Edit City Tier') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Edit City Tier') }}"><i
                                                                class="ti ti-pencil text-white"></i></a>
                                                    </div>
                                                @endcan
                                                @can('Delete Contract Type')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['city_tier.destroy', $city->id]]) !!}
                                                        <a href="#!"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Delete') }}">
                                                            <span class="text-white"> <i class="ti ti-trash"></i></span></a>
                                                        {!! Form::close() !!}
                                                    </div>
                                    @endif
                                    </span>
                                    </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
