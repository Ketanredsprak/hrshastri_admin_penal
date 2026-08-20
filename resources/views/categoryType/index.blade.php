@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Reimbursement Expenses') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Reimbursement Expenses') }}</li>
@endsection

@section('action-button')
    <div class="row align-items-center m-1">

        <div class="col-auto pe-0">
            <a href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Create Reimbursement Expense') }}" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Create Reimbursement Expense') }}" data-url="{{ route('category_type.create') }}"><i
                    class="ti ti-plus text-white"></i></a>
        </div>

    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Reimbursement Expenses') }}</li>
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
                                    <th>{{ __('Reimbursement Expenses') }}</th>
                                    <th width="250px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categoriesTypes as $categoriesType)
                                    <tr>
                                        <td>{{ $categoriesType->name }}</td>
                                        <td class="Action">
                                            <span>
                                                @can('Edit Contract Type')
                                                    <div class="action-btn btn-info ms-2">
                                                        <a href="#" data-size="md"
                                                            data-url="{{ URL::to('category_type/' . $categoriesType->id . '/edit') }}"
                                                            data-ajax-popup="true"
                                                            data-title="{{ __('Edit Reimbursement Expense') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Edit Reimbursement Expense') }}"><i
                                                                class="ti ti-pencil text-white"></i></a>
                                                    </div>
                                                @endcan
                                                @can('Delete Contract Type')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['category_type.destroy', $categoriesType->id]]) !!}
                                                        <a href="#!"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Delete Reimbursement Expense') }}">
                                                            <span class="text-white"> <i class="ti ti-trash"></i></span></a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endcan
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
