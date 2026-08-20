@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Leave Report') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Leave Report') }}</li>
@endsection
@push('script-page')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A4'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
    <script>
        //    $('input[name="type"]:radio').on('change', function(e) {
        //        var type = $(this).val();
        //        if (type == 'monthly') {
        //            $('.month').addClass('d-block');
        //            $('.month').removeClass('d-none');
        //            $('.year').addClass('d-none');
        //            $('.year').removeClass('d-block');
        //        } else {
        //            $('.year').addClass('d-block');
        //            $('.year').removeClass('d-none');
        //            $('.month').addClass('d-none');
        //            $('.month').removeClass('d-block');
        //        }
        //    });

        //    $('input[name="type"]:radio:checked').trigger('change');
    </script>
    <style>
        select#branch {
            width: 100% !important;
        }
    </style>
@endpush
@section('action-button')
    {{-- <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()" data-bs-toggle="tooltip" title="{{ __('Download') }}"
        data-original-title="{{ __('Download') }}" style="margin-right: 5px;">
        <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
    </a> --}}
    <button onclick="exportData()" class="btn btn-sm btn-primary float-end" data-bs-toggle="tooltip"
        data-bs-original-title="{{ __('Export') }}">
        <i class="ti ti-file-export"></i>
    </button>
@endsection
@php
    $requestStartDate = $requestStartDate ?? date('Y-m-01', strtotime('first day of this month'));
@endphp
@section('content')
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['report.leave'], 'method' => 'get', 'id' => 'report_leave']) }}

                        <div class="row align-items-center">
                            <div class="col-sm-10">
                                <div class="row ">
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('start_date', $requestStartDate, ['class' => 'month-btn form-control current_date', 'id' => "requestStartDate"]) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('end_date', $requestEndDate ?? date('Y-m-d', strtotime('last day of this month', strtotime($requestStartDate))), ['class' => 'month-btn form-control current_date', 'id' => "requestEndDate"]) }}
                                        </div>
                                    </div>
                                    {{-- <div class="col-sm-6 col-lg-3 year">
                                        <div class="btn-box">
                                            {{ Form::label('year', __('Year'), ['class' => 'form-label']) }}
                                            <select class="form-control select" id="year" name="year" tabindex="-1"
                                                aria-hidden="true">
                                                @for ($i = 0; $i <= $yearsCount; $i++)
                                                    <?php
                                                    $selected = '';
                                                    $value = date('Y', strtotime("-$i year"));
                                                    
                                                    if ($value == $year) {
                                                        $selected = 'selected';
                                                    }
                                                    ?> <option value="{{ $value }}"
                                                        {{ $selected }}>{{ $value }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            {{ Form::label('branch', __('Branch'), ['class' => 'form-label']) }}
                                            {{ Form::select('branch', $branch, isset($_GET['branch']) ? $_GET['branch'] : '', ['class' => 'form-control select', 'id' => 'branch']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            {{ Form::label('department', __('Department'), ['class' => 'form-label']) }}
                                            {{ Form::select('department', $department, isset($_GET['department']) ? $_GET['department'] : '', ['class' => 'form-control select', 'id' => 'department']) }}
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

                                <a href="{{ route('report.leave') }}" class="btn btn-sm btn-danger "
                                    data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                    data-original-title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-refresh text-white-off "></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

        <div id="printableArea" class="">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-primary">
                                        <i class="ti ti-report"></i>
                                    </div>
                                    <div class="ms-3">
                                        <input type="hidden"
                                            value="{{ $filterYear['branch'] . ' ' . __('Branch') . ' ' . $requestStartDate . ' ' . $requestEndDate . ' ' . $filterYear['type'] . ' ' . __('Leave Report of') . ' ' . $filterYear['department'] . ' ' . 'Department' }}"
                                            id="filename">
                                        <h5 class="mb-0">{{ __('Report') }}</h5>
                                        <div>
                                            <p class="text-muted text-sm mb-0">
                                                {{ $filterYear['type'] . ' ' . __('Leave Summary') }}
                                            </p>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($filterYear['branch'] != 'All')
                    <div class="col">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="theme-avtar bg-secondary">
                                            <i class="ti ti-sitemap"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-0">{{ __('Branch') }}</h5>
                                            <p class="text-muted text-sm mb-0">
                                                {{ $filterYear['branch'] }}
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($filterYear['department'] != 'All')
                    <div class="col">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-template"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-0">{{ __('Department') }}</h5>
                                            <p class="text-muted text-sm mb-0">{{ $filterYear['department'] }}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-secondary">
                                        <i class="ti ti-calendar"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-0">{{ __('Duration') }}</h5>
                                        <p class="text-muted text-sm mb-0">{{ $requestStartDate . ' - ' . $requestEndDate }}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-primary">
                                        <i class="ti ti-circle-check"></i>
                                    </div>

                                    <div class="ms-3">
                                        <h5 class="mb-0">{{ __('Approved Leaves') }} </h5>
                                        <p class="text-muted text-sm mb-0">{{ $filter['totalApproved'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-secondary">
                                        <i class="ti ti-circle-x"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-0">{{ __('Rejected Leave') }}</h5>
                                        <p class="text-muted text-sm mb-0">
                                            {{ $filter['totalReject'] }}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-primary">
                                        <i class="ti ti-circle-minus"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-0">{{ __('Leave Balance') }}</h5>
                                        <p class="text-muted text-sm mb-0">{{ $filter['totalLeaveBalance'] }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


            </div>


        </div>

        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    {{-- <h5></h5> --}}

                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th>{{ __('Employee ID') }}</th>
                                    <th>{{ __('Employee') }}</th>

                                    @foreach ($leaveTypes as $leaveType)
                                        <th>{{ $leaveType->title }}</th>
                                    @endforeach

                                    <th>{{ __('Approved Leaves') }}</th>
                                    <th>{{ __('Rejected Leaves') }}</th>
                                    <th>{{ __('Leave Balance') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaves as $leave)
                                    <tr>
                                        <td>
                                            @if ($leave['employee_id'] == 1)
                                                <a href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($leave['id'])) }}"
                                                    class="btn btn-sm btn-outline-primary ">{{ $leave['employee_id'] }}</a>
                                            @else
                                                <a href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($leave['employee_id'])) }}"
                                                    class="btn btn-sm btn-outline-primary ">{{ $leave['employee_id'] }}</a>
                                            @endif
                                        </td>
                                        <td>{{ $leave['employee'] }}</td>

                                        @foreach ($leaveTypes as $leaveType)
                                            <th>{{ $leave[$leaveType->title . "_" . $leaveType->id] }}</th>
                                        @endforeach

                                        <td>
                                            <div class="btn btn-sm btn-success box">{{ $leave['approved'] }}
                                                <a href="#" class="text-white"
                                                    data-url="{{ route('report.employee.leave', [$leave['id'], 'Approved', isset($_GET['type']) ? $_GET['type'] : 'no', $requestStartDate, $requestEndDate]) }}"
                                                    data-ajax-popup="true" data-size="lg"
                                                    data-title="{{ __('Approved Leave Detail') }}"
                                                    data-bs-toggle="tooltip" title="{{ __('View') }}"
                                                    data-original-title="{{ __('View') }}">{{ __('View') }}</a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn btn-sm btn-danger box">{{ $leave['reject'] }}
                                                {{-- <a href="#" class="text-white"
                                                    data-url="{{ route('report.employee.leave', [$leave['id'], 'Reject', isset($_GET['type']) ? $_GET['type'] : 'no', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), isset($_GET['year']) ? $_GET['year'] : date('Y')]) }}"
                                                  class="table-action table-action-delete" data-size="lg"
                                                  data-ajax-popup="true" data-title="{{ __('Rejected Leave Detail') }}"
                                                  data-bs-toggle="tooltip" title="{{ __('View') }}"
                                                  data-original-title="{{ __('View') }}">{{ __('View') }}</a> --}}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn btn-sm btn-info box">
                                                {{ formatNumber($leave['leaveBalance']) }}
                                                {{-- <a href="#" class="text-white"
                                                    data-url="{{ route('report.employee.leave', [$leave['id'], 'Pending', isset($_GET['type']) ? $_GET['type'] : 'no', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), isset($_GET['year']) ? $_GET['year'] : date('Y')]) }}"
                                                  class="table-action table-action-delete" data-size="lg"
                                                  data-ajax-popup="true" data-title="{{ __('Pending Leave Detail') }}"
                                                  data-bs-toggle="tooltip" title="{{ __('View') }}"
                                                  data-original-title="{{ __('View') }}">{{ __('View') }}</a> --}}
                                            </div>
                                        </td>
                                        {{--
                                        <td>
                                            <div class="btn btn-sm btn-info box">
                                                {{ get_employee_flaxihourescount($leave['employee_id_cus']) }}
                    </div>
                    </td>
                    <td>
                         <div class="btn btn-sm btn-primary box">{{ company_flexi_days() }}</div>
                    </td> --}}
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

<script>
    function exportData() {


        var requestStartDate = $("#requestStartDate").val() ?? "";
        var requestEndDate = $("#requestEndDate").val() ?? "";
        var branch = $("#branch").val() ?? "";
        var department = $("#department").val() ?? "";

        var url = "{{ route('leave.report.export') }}?start_date=" + requestStartDate + "&end_date=" + requestEndDate + "&branch=" + branch + "&department=" +
            department;

        location.href = url;
    }
</script>
