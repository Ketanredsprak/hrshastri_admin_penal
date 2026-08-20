@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Claim Report') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Claim Report') }}</li>
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
        $('input[name="type"]:radio').on('change', function(e) {
            var type = $(this).val();
            if (type == 'monthly') {
                $('.month').addClass('d-block');
                $('.month').removeClass('d-none');
                $('.year').addClass('d-none');
                $('.year').removeClass('d-block');
                $('.date').addClass('d-none');
                $('.date').removeClass('d-block');
            } else if (type == 'daily') {
                $('.year').addClass('d-none');
                $('.year').removeClass('d-block');
                $('.month').addClass('d-none');
                $('.month').removeClass('d-block');
                $('.date').removeClass('d-none');
                $('.date').addClass('d-block');
            } else {
                $('.year').addClass('d-block');
                $('.year').removeClass('d-none');
                $('.month').addClass('d-none');
                $('.month').removeClass('d-block');
                $('.date').addClass('d-none');
                $('.date').removeClass('d-block');
            }
        });

        $('input[name="type"]:radio:checked').trigger('change');
    </script>
    <style>
        select#branch {
            width: 100% !important;
        }
    </style>
@endpush
@section('action-button')
    {{-- <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()" data-bs-toggle="tooltip" title="{{ __('Download') }}" data-original-title="{{ __('Download') }}" style="margin-right: 5px;">
     <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
</a> --}}


    <button onclick="exportData()" class="btn btn-sm btn-primary float-end" data-bs-toggle="tooltip"
        data-bs-original-title="{{ __('Export') }}"><i class="ti ti-file-export"></i></button>
    {{-- <a href="#" onclick="exportData()" class="btn btn-sm btn-primary float-end" data-bs-toggle="tooltip"
        data-bs-original-title="{{ __('Export') }}">
<i class="ti ti-file-export"></i>
</a> --}}
@endsection




@section('content')
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['report.claim'], 'method' => 'get', 'id' => 'report_claim']) }}

                        <div class="row align-items-center">
                            <div class="col-sm-10">
                                <div class="row ">
                                    <div class="col-sm-6 col-lg-4">
                                        {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}<br>
                                        <div class="form-check form-check-inline form-group">
                                            <input type="radio" id="daily" value="daily" name="type"
                                                class="form-check-input"
                                                {{ isset($_GET['type']) && $_GET['type'] == 'daily' ? 'checked' : 'checked' }}>
                                            {{ Form::label('daily', __('Daily'), ['class' => 'form-label']) }}
                                        </div>
                                        <div class="form-check form-check-inline form-group">
                                            <input type="radio" id="monthly" value="monthly" name="type"
                                                class="form-check-input"
                                                {{ isset($_GET['type']) && $_GET['type'] == 'monthly' ? 'checked' : '' }}>
                                            {{ Form::label('monthly', __('Monthly'), ['class' => 'form-label']) }}
                                        </div>
                                        <div class="form-check form-check-inline form-group">
                                            <input type="radio" id="yearly" value="yearly" name="type"
                                                class="form-check-input yearly"
                                                {{ isset($_GET['type']) && $_GET['type'] == 'yearly' ? 'checked' : '' }}>
                                            {{ Form::label('yearly', __('Yearly'), ['class' => 'form-label']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3 month">
                                        <div class="btn-box">
                                            {{ Form::label('month', __('Month'), ['class' => 'form-label']) }}
                                            {{ Form::month('month', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3 date">
                                        <div class="btn-box">
                                            {{ Form::label('date', __('Start Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('start_date', isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d'), ['class' => 'month-btn form-control current_date']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3 date">
                                        <div class="btn-box">
                                            {{ Form::label('date', __('End Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('end_date', isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d'), ['class' => 'month-btn form-control current_date']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3 year d-none">
                                        <div class="btn-box">
                                            {{ Form::label('year', __('Year'), ['class' => 'form-label']) }}
                                            <select class="form-control select" id="year" name="year" tabindex="-1"
                                                aria-hidden="true">
                                                @for ($filterYear['starting_year']; $filterYear['starting_year'] <= $filterYear['ending_year']; $filterYear['starting_year']++)
                                                    <option
                                                        {{ isset($_GET['year']) && $_GET['year'] == $filterYear['starting_year'] ? 'selected' : '' }}
                                                        {{ !isset($_GET['year']) && date('Y') == $filterYear['starting_year'] ? 'selected' : '' }}
                                                        value="{{ $filterYear['starting_year'] }}">
                                                        {{ $filterYear['starting_year'] }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-2">
                                        <div class="btn-box">
                                            {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                                            {{ Form::select('status', $status, isset($_GET['status']) ? $_GET['status'] : '', ['class' => 'form-control select', 'id' => 'status']) }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-2 text-right pt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('report_claim').submit(); return false;"
                                    data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                    data-original-title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>

                                <a href="{{ route('report.claim') }}" class="btn btn-sm btn-danger "
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
                                            value="{{ ' ' . __('All Claim') . ' ' . $filterYear['dateYearRange'] . ' ' . $filterYear['type'] . ' ' . __('Report of Claim') . ' ' }}"
                                            id="filename">
                                        <h5 class="mb-0">{{ __('Report') }}</h5>
                                        <div>
                                            <p class="text-muted text-sm mb-0">
                                                {{ $filterYear['type'] . ' ' . __('Claim Summary') }}
                                            </p>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                                        <p class="text-muted text-sm mb-0">{{ $filterYear['dateYearRange'] }}
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
                                        <h5 class="mb-0">{{ __('Approved Claim') }} </h5>
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
                                        <h5 class="mb-0">{{ __('Rejected Claim') }}</h5>
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
                                        <h5 class="mb-0">{{ __('Pending Claim') }}</h5>
                                        <p class="text-muted text-sm mb-0">{{ $filter['totalPending'] }}</p>
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
                                    <th>{{ __('Employee Id') }}</th>
                                    <th>{{ __('Employee') }}</th>
                                    <th>{{ __('Visit Type') }}</th>
                                    <th>{{ __('Visit Name') }}</th>
                                    <th>{{ __('Other Claim') }}</th>
                                    <th>{{ __('Visit Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Status changed by') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($claims as $claim)
                                    <tr>
                                        <td>
                                            {{ $claim->employee->employee_id }}
                                        </td>
                                        <td>
                                            {{ $claim->employee->name }}
                                        </td>
                                        <td>
                                            @if ($claim->visit_type == 1)
                                                Other Claim
                                            @else
                                                @if (!empty($claim->myvisitData))
                                                    @if ($claim->myvisitData->visit_type == 1)
                                                        Local visit
                                                    @else
                                                        Out of City visit
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($claim->myvisitData))
                                                {{ $claim->myvisitData->visit_name ?? '' }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $claim->name }}
                                        </td>
                                        <td>
                                            @if ($claim->visit_type == 1)
                                                {{ \Auth::user()->dateFormat($claim->created_at) }}
                                            @else
                                                @if (!empty($claim->myvisitData))
                                                    {{ \Auth::user()->dateFormat($claim->myvisitData->form_date) }}
                                                @endif
                                            @endif

                                        </td>

                                        <td>
                                            @if ($claim->status == 'Pending')
                                                <p class="badge bg-warning p-2 px-3 rounded status-badge5">Pending </p>
                                            @endif
                                            @if ($claim->status == 'Rejected')
                                                <p class="badge bg-danger p-2 px-3 rounded status-badge5">Rejected </p>
                                            @endif
                                            @if ($claim->status == 'Approved')
                                                <p class="badge bg-success p-2 px-3 rounded status-badge5">Approved</p>
                                            @endif
                                        </td>
                                        <td>{{ findEmployeeByUser($claim->status_changed_by) }}</td>
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
        var formData = $('#report_claim').serialize();

        var currentUrl = window.location.href;

        var searchString = 'type';

        var url = "";
        if (currentUrl.indexOf(searchString) !== -1) {
            url = "{{ route('report.claim') . '?export=csv' }}&" + formData;
        } else {
            url = "{{ route('report.claim') . '?export=csv' }}";
        }

        window.location.href = url;
    }
</script>
