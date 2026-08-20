@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Timesheet Request') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Timesheet') }}</li>
@endsection



@section('content')
    <div class="row">

        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class=" mt-2 " id="multiCollapseExample1" style="">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['timesheet_request'], 'method' => 'get', 'id' => 'timesheet_filter']) }}
                        
                        
                        <div class="row align-items-center">
                <div class="col-sm-8">
                   <div class="row ">
                       <div class="col-sm-4 col-lg-4"> 
                <div class="btn-box">
                                    {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                    {{ Form::text('start_date', isset($_GET['start_date']) ? $_GET['start_date'] : '', ['class' => 'month-btn form-control d_week current_date', 'autocomplete' => 'off', 'id' => 'current_date']) }}
                                </div>
                        </div>  
                             <div class="col-sm-4 col-lg-4"> 
                <div class="btn-box">
                                    {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                    {{ Form::text('end_date', isset($_GET['end_date']) ? $_GET['end_date'] : '', ['class' => 'month-btn form-control d_week current_date', 'autocomplete' => 'off', 'id' => 'end_date']) }}
                                </div>
                        </div>
                            
                            <div class="col-sm-4 col-lg-4"> 
                                <div class="btn-box">
                                    {{ Form::label('employee', __('Employee'), ['class' => 'form-label']) }}
                                    {{ Form::select('employee', $employeesList, isset($_GET['employee']) ? $_GET['employee'] : '', ['class' => 'form-control select ', 'id' => 'employee_id' ,'placeholder' => 'select']) }}
                                </div>
                        </div>
                                        
                        </div>
                        
                </div>
                 <div class="col-sm-4 text-right pt-4">
                  <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('timesheet_filter').submit(); return false;"
                                    data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('timesheet_request') }}" class="btn btn-sm btn-danger"
                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Reset">
                                    <span class="btn-inner--icon"><i class="ti ti-refresh text-white-off "></i></span>
                                </a>
                </div>
                </div>
                        
                        
                       
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    {{-- <h5> </h5> --}}
                    <div class="">

                        <div class="table-responsive">
                            <table class="table" id="pc-dt-simple">
                                <thead>
                                    <tr>
                                        @if (\Auth::user()->type != 'employee')
                                            <th>{{ __('Employee') }}</th>
                                        @endif
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Hours') }}</th>
                                        <th>{{ __('Description') }}</th>
                                        <th width="200ox">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    @foreach ($timeSheets as $timeSheet)
                                        <tr>
                                            @if (\Auth::user()->type != 'employee')
                                                <td>{{ !empty($timeSheet->employee) ? $timeSheet->employee->name : '' }}
                                                </td>
                                            @endif
                                            <td>{{ \Auth::user()->dateFormat($timeSheet->date) }}</td>
                                            <td>{{ $timeSheet->hours }}</td>
                                            <td>{{ $timeSheet->remark }}</td>
                                            <td class="Action">

                                                <span>
                                                   
                                                    @can('Delete TimeSheet')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['timesheet.destroy', $timeSheet->id],
                                                                'id' => 'delete-form-' . $timeSheet->id,
                                                            ]) !!}
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            </form>
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

