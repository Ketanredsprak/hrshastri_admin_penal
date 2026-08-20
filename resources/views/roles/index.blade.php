@extends('layouts.admin')



@section('page-title')
    {{ __('Manage Roles') }}
@endsection



@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>

    <li class="breadcrumb-item">{{ __('Role') }}</li>
@endsection



@section('action-button')
    @can('Create Role')
        <a href="#" data-url="{{ route('role.create') }}" data-ajax-popup="true" data-title="{{ __('Create New Role') }}"
            data-bs-toggle="tooltip" title="" data-size="lg" class="btn btn-sm btn-primary"
            data-bs-original-title="{{ __('Create') }}">

            <i class="ti ti-plus"></i>

        </a>
    @endcan
@endsection



@section('content')
    <div class="row">



        <div class="col-xl-12">

            <div class="card">

                <div class="card-header card-body table-border-style">

                    {{-- <h5></h5> --}}

                    <div class="table-responsive">

                        <table class="table" id="pc-dt-simple">

                            <thead>
                                <tr>
                                    <th>{{ __('Id') }}</th>
                                    <th>{{ __('Role name') }}</th>
                                    <th width="200px">{{ __('Action') }}</th>

                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($data as $key => $row)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $row->name }}</td>
                                        <td class="Action">

                                            <span>
                                                @can('Edit Role')
                                                    <div class="action-btn bg-info ms-2">

                                                        <a class="mx-3 btn btn-sm  align-items-center"
                                                            data-url="{{ route('role.edit', $row->id) }}" data-ajax-popup="true"
                                                            data-size="lg" data-bs-toggle="tooltip" title=""
                                                            data-title="{{ __('Edit Role') }}"
                                                            data-bs-original-title="{{ __('Edit 324234') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endcan



                                                {{-- @can('Delete Role')

                                            <div class="action-btn bg-danger ms-2">

                                                {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id], 'id' => 'delete-form-' . $role->id]) !!}

                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"

                                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"

                                                    aria-label="Delete"><i

                                                        class="ti ti-trash text-white text-white"></i></a>

                                                </form>

                                            </div>

                                        @endcan --}}

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
