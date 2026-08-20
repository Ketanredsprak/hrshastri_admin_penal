@extends('layouts.admin')

@section('page-title')
    {{ __('Esic Register Reports') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>

    <li class="breadcrumb-item">{{ __('Esic Register Reports') }}</li>
@endsection

@section('action-button')
    @can('Create Promotion')
        {{ Form::open(['route' => ['esic_register_reports_export'], 'method' => 'get', 'id' => 'report_income_expense']) }}

        <input type="hidden" value="{{ isset($_GET['month']) ? $_GET['month'] : date('Y-m') }}" name="month">
        <button type="submit" class="btn btn-success btn-sm">Export</button>

        {{ Form::close() }}
    @endcan
@endsection

@section('content')
    <div class="row">

        <div class="col-xl-12">

            <div class="card">

                <div class="card-body">

                    {{ Form::open(['route' => ['esic_register_reports'], 'method' => 'get', 'id' => 'report_leave']) }}

                    <div class="row align-items-center">

                        <div class="col-sm-10">

                            <div class="row ">

                                <div class="col-sm-6 col-lg-3">

                                    {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}<br>

                                    <div class="form-check form-check-inline form-group">

                                        <input type="radio" id="monthly" value="monthly" name="type"
                                            class="form-check-input"
                                            {{ isset($_GET['type']) && $_GET['type'] == 'monthly' ? 'checked' : 'checked' }}>

                                        {{ Form::label('monthly', __('Monthly'), ['class' => 'form-label']) }}

                                    </div>

                                </div>

                                <div class="col-sm-6 col-lg-3 month">

                                    <div class="btn-box">

                                        {{ Form::label('month', __('Month'), ['class' => 'form-label']) }}

                                        {{ Form::text('month', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), ['class' => 'month-btn form-control d_filter']) }}

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-sm-2 text-right pt-4">

                            <a href="#" class="btn btn-sm btn-primary"
                                onclick="document.getElementById('report_leave').submit(); return false;"
                                data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                data-original-title="{{ __('apply') }}">

                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>

                            </a>

                            <a href="{{ route('esic_register_reports') }}" class="btn btn-sm btn-danger "
                                data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                data-original-title="{{ __('Reset') }}">

                                <span class="btn-inner--icon"><i class="ti ti-refresh text-white-off "></i></span>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

            {{ Form::close() }}

            <div class="card">

                <div class="card-header card-body table-border-style">

                    <div class="table-responsive">

                        <table class="table">

                            <thead>

                                <tr>

                                    <th>{{ __('Sr.no') }}</th>

                                    <th>{{ __('EMP Code') }}</th>

                                    <th>{{ __('Name') }}</th>

                                    <th>{{ __('ESI No') }}</th>

                                    <th>{{ __('Present Days') }}</th>

                                    <th>{{ __('Gross Salary') }}</th>

                                </tr>

                            </thead>

                            <tbody>



                                @foreach ($data as $row)
                                    <tr>

                                        <td>{{ $row->id }}</td>

                                        <td>{{ \Auth::user()->employeeIdFormat($row->id) }}</td>

                                        <td>{{ $row->empName }}</td>

                                        <td>{{ $row->bank_identifier_code }}</td>

                                        <td>{{ $row->attdebdance }}</td>

                                        <td>{{ \Auth::user()->priceFormat($row->basic_salary) }}</td>

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
