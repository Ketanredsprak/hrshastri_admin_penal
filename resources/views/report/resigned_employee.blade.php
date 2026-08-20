@extends('layouts.admin')
@section('page-title')
    {{ __('Resigned Employee Report') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Resinged Employee Report') }}</li>
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
    <style>
        select#branch {
            width: 100% !important;
        }
    </style>
@endpush

@section('action-button')
    <!-- <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()" data-bs-toggle="tooltip" title="{{ __('Download') }}"
                data-original-title="{{ __('Download') }}" style="margin-right: 5px">
                <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
            </a>
            <a href="{{ route('timesheet.report.export') }}" class="btn btn-sm btn-primary float-end" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Export') }}">
                <i class="ti ti-file-export"></i>
            </a> -->
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class=" mt-2 ">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['resigned-employee-report'], 'method' => 'get', 'id' => 'report_resigned']) }}

                        <div class="row align-items-center">
                            <div class="col-sm-9">
                                <div class="row ">
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            {{ Form::label('resigned_from_date', __('Resigned From Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('resigned_from_date', isset($_GET['resigned_from_date']) ? $_GET['resigned_from_date'] : date('Y-m-01'), ['class' => 'month-btn form-control current_date']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            {{ Form::label('resigned_to_date', __('Resigned To Date'), ['class' => 'form-label']) }}
                                            {{ Form::date('resigned_to_date', isset($_GET['resigned_to_date']) ? $_GET['resigned_to_date'] : date('Y-m-t'), ['class' => 'month-btn form-control current_date']) }}
                                        </div>
                                    </div>
                                    <!-- <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            {{ Form::label('branch', __('Branch'), ['class' => 'form-label']) }}
                                            {{ Form::select('branch', $branch, isset($_GET['branch']) ? $_GET['branch'] : '', ['class' => 'month-btn form-control select']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            {{ Form::label('department', __('Department'), ['class' => 'form-label']) }}
                                            {{ Form::select('department', $department, isset($_GET['department']) ? $_GET['department'] : '', ['class' => 'month-btn form-control select']) }}
                                        </div>
                                    </div> -->
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box" id="employee_div">
                                            {{ Form::label('employee', __('Employee'), ['class' => 'form-label']) }}
                                            <select class="form-control select" name="employee_id[]" id="employee_id"
                                                placeholder="Select Employee">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 text-right pt-4">

                                <button name="btn_trigger" value="search" class="btn btn-sm btn-primary"
                                    data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                    data-original-title="{{ __('apply') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </button>

                                <button name="btn_trigger" value="reset" class="btn btn-sm btn-danger "
                                    data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                    data-original-title="{{ __('Reset') }}">
                                    <span class="btn-inner--icon"><i class="ti ti-refresh text-white-off "></i></span>
                                </button>

                                <button name="btn_trigger" value="export" class="btn btn-sm btn-primary float-end"
                                    data-bs-toggle="tooltip" data-bs-original-title="{{ __('Export') }}">
                                    <i class="ti ti-file-export"></i>
                                </button>

                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        
        <div id="printableArea">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive ">
                            <table class="table" id="pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th>{{ __('Employee ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Branch') }}</th>
                                        <th>{{ __('Department') }}</th>
                                        <th>{{ __('Designation') }}</th>
                                        <th>{{ __('Joining Date') }}</th>
                                        <th>{{ __('Resignation Date') }}</th>
                                        <th>{{ __('Last Working Date') }}</th>
                                        <th>{{ __('Reason for Resignation') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resigned_employee as $resignation)
                                        <tr>
                                            <td>{{ $resignation->employee()->employee_id }}</td>
                                            <td>{{ !empty($resignation->employee()) ? $resignation->employee()->name : '' }}</td>
                                            <td>{{ $resignation->employee()->branch->name ?? '' }}</td>
                                            <td>{{ $resignation->employee()->department->name ?? '' }}</td>
                                            <td>{{ $resignation->employee()->designation->name ?? '' }}</td>
                                            <td>{{ \Auth::user()->dateFormat($resignation->employee()->company_doj) }}</td>
                                            <td>{{ \Auth::user()->dateFormat($resignation->resignation_date) }}</td>
                                            <td>{{ \Auth::user()->dateFormat($resignation->notice_date) }}</td>
                                            <td>
                                                <p title="{{$resignation->description}}">
                                                    {{ substr($resignation->description, 0, 60) . '..' }}
                                                </p>
                                            </td>
                                            <td>
                                                @if ($resignation->status == 0)
                                                    <div class="badge bg-warning p-2 px-3 rounded status-badge5">Pending</div>
                                                @elseif ($resignation->status == 1)
                                                    <div class="badge bg-success p-2 px-3 rounded status-badge5">Approve</div>
                                                @elseif ($resignation->status == 2)
                                                    <div class="badge bg-danger p-2 px-3 rounded status-badge5">Rejected</div>
                                                @else
                                                    <div class="badge bg-danger p-2 px-3 rounded status-badge5"></div>
                                                @endif
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

    </div>
@endsection

@push('script-page')
    <script>
        $(document).ready(function() {
            var b_id = $('#branch_id').val();
            // getDepartment(b_id);
        });
        $(document).on('change', 'select[name=branch_id]', function() {
            var branch_id = $(this).val();

            getDepartment(branch_id);
        });

        @if (!empty($requestBranchId))
            getDepartment({
                {
                    $requestBranchId
                }
            }, {
                {
                    $requestDepartmentId ?? 0
                }
            });
        @endif


        var selectedEmployeesIds = [];
        var requestDepartmentId = "0";
        @if (!empty($requestEmployeeId))
            selectedEmployeesIds = <?php echo json_encode($requestEmployeeId); ?>;
        @endif

        @if (!empty($requestDepartmentId))
            requestDepartmentId = <?php echo json_encode($requestEmployeeId); ?>;
        @endif

        getEmployee(requestDepartmentId, selectedEmployeesIds);

        function getDepartment(bid, selectDepartmentId = 0) {

            $.ajax({
                url: "{{ route('monthly.getdepartment') }}",
                type: 'POST',
                data: {
                    "branch_id": bid,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {

                    $('.department_id').empty();
                    var emp_selct = `<select class="department_id form-control multi-select" id="choices-multiple" multiple="" required="required" name="department_id[]">
                </select>`;
                    $('.department_div').html(emp_selct);

                    $('.department_id').append('<option value="0"> {{ __('All ') }} </option>');
                    $.each(data, function(key, value) {
                        var selected = "";
                        if (selectDepartmentId = key) {
                            selected = "selected";
                        }
                        $('.department_id').append('<option value="' + key + '" ' + selected + '>' +
                            value +
                            '</option>');
                    });
                    new Choices('#choices-multiple', {
                        removeItemButton: true,
                    });
                }
            });
        }

        $(document).on('change', '.department_id', function() {
            var department_id = $(this).val();
            getEmployee(department_id);
        });

        function getEmployee(did, selectedEmployeesIds = []) {
            //    function getEmployee(did) {

            $.ajax({
                url: "{{ route('monthly.getemployee') }}",
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {

                    $('#employee_id').empty();

                    $("#employee_div").html('');
                    // $('#employee_div').append('<select class="form-control" id="employee_id" name="employee_id[]"  multiĒple></select>');
                    $('#employee_div').append(
                        '<label for="employee" class="form-label">{{ __('Employee ') }}</label><select class="form-control" id="employee_id" name="employee_id[]"  multiple></select>'
                    );

                    var result = jQuery.inArray(0, selectedEmployeesIds);

                    $('#employee_id').append('<option value="">{{ __('Select Employee ') }}</option>');

                    if (result !== -1) {
                        $('#employee_id').append(
                            '<option value="0" selected> {{ __('All Employee ') }} </option>');
                    } else {
                        $('#employee_id').append('<option value="0"> {{ __('All Employee ') }} </option>');
                    }

                    $.each(data, function(key, value) {
                        var selected = "";

                        var result = jQuery.inArray(key, selectedEmployeesIds);

                        if (result !== -1) {
                            selected = "selected";
                        }
                        $('#employee_id').append('<option value="' + key + '" ' + selected + '>' +
                            value + '</option>');
                    });

                    var multipleCancelButton = new Choices('#employee_id', {
                        removeItemButton: true,
                    });
                }
            });

            //   selectedEmployeesIds = [];
        }
    </script>
@endpush
