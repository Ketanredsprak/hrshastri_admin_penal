@extends('layouts.admin')

@section('page-title')
   {{ __("Manage Grade Type") }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
    <li class="breadcrumb-item">{{ __("Grade Type") }}</li>
@endsection

@section('action-button')
    
    <div class="row align-items-center m-1">
        {{-- @can('Create Visit Type') --}}
            <div class="col-auto pe-0">
                <a href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Contract')}}" data-ajax-popup="true" data-size="md" data-title="{{__('Create Visit Type')}}" data-url="{{route('visit_type.create')}}"><i class="ti ti-plus text-white"></i></a>
            </div>
        {{-- @endcan --}}
    </div>
 
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Grade Type')}}</li>
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
                                    <th>{{__('Grade Type')}}</th>
                                    <th width="250px">{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody >
                                @foreach ($visitTypes as $visitTypes)
                                <tr>
                                    <td>{{ $visitTypes->name }}</td>
                                    <td class="Action">
                                        <span>
                                        @can('Edit Contract Type')
                                                <div class="action-btn btn-info ms-2">
                                                    <a href="#" data-size="md" data-url="{{ URL::to('visit_type/'.$visitTypes->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Grade Type')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Visit Type')}}" ><i class="ti ti-pencil text-white"></i></a>
                                                </div>
                                            @endcan
                                            @can('Delete Contract Type')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['visit_type.destroy', $visitTypes->id]]) !!}
                                                        <a href="#!" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
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