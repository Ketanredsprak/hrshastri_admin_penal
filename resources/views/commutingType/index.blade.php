@extends('layouts.admin')

@section('page-title')
   {{ __("Manage Commuting Type") }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __("Home") }}</a></li>
    <li class="breadcrumb-item">{{ __("Commuting Type") }}</li>
@endsection

@section('action-button')
    
    <div class="row align-items-center m-1">
        {{-- @can('Create Commuting Type') --}}
            <div class="col-auto pe-0">
                <a href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Commuting Type')}}" data-ajax-popup="true" data-size="md" data-title="{{__('Create Commuting Type')}}" data-url="{{route('commuting_type.create')}}"><i class="ti ti-plus text-white"></i></a>
            </div>
        {{-- @endcan --}}
    </div>
 
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{__('Commuting Type')}}</li>
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
                                    <th>{{__('No')}}</th>
                                    <th>{{__('Commuting Name')}}</th>
                                    <th>{{__('Status')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($datas as $key=>$data)
                                <tr>
                                    <td>{{ $no; }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>

                                        @if($data->status == '1')
                                            <div class="action-btn bg-success ms-2">
                                                {!! Form::open(['method' => 'get','route' => ['commuting_in_active', $data->id], 'id' => 'delete-form-' . $data->id]) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                    data-bs-toggle="tooltip" title=""
                                                    data-bs-original-title="InActive" aria-label="InActive"><i
                                                        class="ti ti-check  text-white text-white"></i></a>
                                                </form>
                                            </div>
                                        @else

                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'get','route' => ['commuting_active', $data->id], 'id' => 'delete-form-' . $data->id]) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                    data-bs-toggle="tooltip" title=""
                                                    data-bs-original-title="Active" aria-label="Active"><i
                                                        class="ti ti-check  text-white text-white"></i></a>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="Action">
                                        <span>
                                        @can('Edit Contract Type')
                                                <div class="action-btn btn-info ms-2">
                                                    <a href="#" data-size="md" data-url="{{ URL::to('commuting_type/'.$data->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Commuting Type')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Commuting Type')}}" ><i class="ti ti-pencil text-white"></i></a>
                                                </div>
                                            @endcan
                                            @can('Delete Contract Type')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['commuting_type.destroy', $data->id]]) !!}
                                                        <a href="#!" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                           <span class="text-white"> <i class="ti ti-trash"></i></span></a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                <?php $no++; ?>
                            @endforeach
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection