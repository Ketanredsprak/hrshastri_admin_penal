@extends('layouts.admin')

@section('page-title')
    {{ __('Advance Monthly Attendance') }}
@endsection

@section('header-section')
    <style>
        .clockIn {
            color: #6FD943
        }

        .clockOut {
            color: #FF3A6E
        }

        .workingHours {
            color: #6C757D
        }
    </style>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Advance Monthly Attendance') }}</li>
@endsection
@section('action-button')
    @php
        $emp = isset($_GET['employee_id']) && !empty($_GET['employee_id']) ? $_GET['employee_id'] : [0];
        $employees = implode(', ', $emp);
    @endphp
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
                    format: 'A2'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class=" mt-2 " id="" style="">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['employee-attendance-geofencing-report'], 'method' => 'get', 'id' => 'report_monthly_attendance']) }}

                        <div class="row align-items-center">
                            <div class="col-sm-10">
                                <div class="row ">
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            {{ Form::label('month', __(' Month'), ['class' => 'form-label']) }}
                                            {{ Form::month('month', $requestMonth ?? '', ['class' => 'month-btn form-control current_date', 'autocomplete' => 'off', 'placeholder' => 'Select month']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            {{ Form::label('branch', __('Branch'), ['class' => 'form-label']) }}
                                            {{ Form::select('branch_id', $branch, $requestBranchId ?? '', ['class' => 'form-control select branch_id', 'id' => 'branch_id']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            <div class="btn-box" id="department_id">
                                                {{ Form::label('department', __('Department'), ['class' => 'form-label']) }}
                                                {{ Form::select('department', $department, $requestDepartmentId ?? '', ['class' => 'form-control select department_id', 'id' => 'department']) }}
                                            </div>
                                        </div>
                                    </div>
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
                            <div class="col-sm-2 text-right pt-4">
                                <button name="search" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                    title="" data-bs-original-title="apply">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </button>

                                <button name="export" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                                    title="" data-bs-original-title="export">
                                    <span class="btn-inner--icon"><i class="ti ti-file-export"></i></span>
                                </button>

                                <a href="{{ route('employee-attendance-geofencing-report') }}"
                                    class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title=""
                                    data-bs-original-title="Reset">
                                    <span class="btn-inner--icon"><i class="ti ti-refresh  text-white-off "></i></span>
                                </a>
                            </div>
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

        <div id="printableArea">
            <div class="row">

                {{-- <div class="col">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-primary">
                                        <i class="ti ti-report"></i>
                                    </div>
                                    <div class="ms-3">
                                        <input type="hidden"
                                            value="{{ $data['branch'] . ' ' . __('Branch') . ' ' . $data['curMonth'] . ' ' . __('Attendance Report of') . ' ' . $data['department'] . ' ' . 'Department' }}"
                                            id="filename">
                                        <h5 class="mb-0">{{ __('Report') }}</h5>
                                        <div>
                                            <p class="text-muted text-sm mb-0">{{ __('Attendance Summary') }}</p>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                @if ($data['branch'] != 'All')
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
                                                {{ $data['branch'] }}
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($data['department'] != 'All')
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
                                            <p class="text-muted text-sm mb-0">{{ $data['department'] }}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- <div class="col">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-secondary">
                                        <i class="ti ti-sum"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-0">{{ __('Duration') }}</h5>
                                        <p class="text-muted text-sm mb-0">{{ $data['curMonth'] }}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <div class="row">

                            <div class="col-2"><span class="text-success"><i
                                        class="badge bg-success p-2  rounded">{{ __('In') }}</i>
                                    {{ __('Clock In') }}</span></div>


                            <div class="col-2"><span class="text-danger"><i
                                        class="badge bg-danger p-2  rounded">{{ __('Out') }}</i>
                                    {{ __('Clock Out') }}</span></div>

                            <div class="col-2"><span class="text-secondary"><i
                                        class="badge bg-secondary p-2  rounded">{{ __('WH') }}</i>
                                    {{ __('Working Hours') }}</span></div>

                            <div class="col-2"><span class="text-secondary"><i
                                        class="badge bg-primary p-2  rounded">{{ __('LC') }}</i>
                                    {{ __('Late Coming') }}</span></div>

                            <div class="col-2"><span class="text-secondary"><i
                                        class="badge bg-info p-2  rounded">{{ __('EL') }}</i>
                                    {{ __('Early Leaving') }}</span></div>

                            {{-- <div class="col-2"><span class="text-info"><i
                                        class="badge bg-info p-2  rounded">{{ __('L') }}</i>
                                    {{ __('Leave') }}</span></div>





                            <div class="col-2"><span class="text-warning"><i
                                        class="badge bg-warning p-2  rounded">{{ __('H') }}</i>
                                    {{ __('Holiday') }}</span></div>



                            <div class="col-2"><span class="text-dark"><i
                                        class="badge bg-dark p-2  rounded">{{ __('WO') }}</i>
                                    {{ __('Week Off') }}</span></div> --}}
                        </div>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive attendance-table-responsive">








                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Employee Id</td>
                                        <td>Name</td>
                                        <td>Branch</td>
                                        <td>Designation</td>
                                        @foreach ($monthDates as $monthDateRow)
                                            @php
                                                $checkData = $employeesAttendance->where(
                                                    'attendance_date',
                                                    $monthDateRow,
                                                );

                                                $columnsCount = 0;
                                                if ($checkData != null) {
                                                    if ($checkData->groupBY('employee_id')->max() != null) {
                                                        $columnsCount = $checkData
                                                            ->groupBY('employee_id')
                                                            ->max()
                                                            ->count();
                                                    }
                                                }

                                            @endphp

                                            @if ($columnsCount > 0)
                                                <td colspan="{{ $columnsCount * 2 + 3 }}" style="text-align: center;">
                                                    {{ Auth::user()->dateFormat($monthDateRow) }}</td>
                                            @else
                                                <td colspan="5" style="text-align: center;">
                                                    {{ Auth::user()->dateFormat($monthDateRow) }}</td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        @foreach ($monthDates as $monthDateRow)
                                            @php
                                                $checkData = $employeesAttendance->where(
                                                    'attendance_date',
                                                    $monthDateRow,
                                                );

                                                $columnsCount = 0;
                                                if ($checkData != null) {
                                                    if ($checkData->groupBY('employee_id')->max() != null) {
                                                        $columnsCount = $checkData
                                                            ->groupBY('employee_id')
                                                            ->max()
                                                            ->count();
                                                    }
                                                }
                                            @endphp

                                            @if ($columnsCount > 0)
                                                @for ($i = 1; $i <= $columnsCount; $i++)
                                                    <td class="clockIn">
                                                        <span class="text-success"><i
                                                                class="badge bg-success p-2  rounded">{{ __('In') }}</i></span>
                                                    </td>
                                                    <td class="clockOut">
                                                        <span class="text-danger"><i
                                                                class="badge bg-danger p-2  rounded">{{ __('Out') }}</i></span>
                                                    </td>
                                                @endfor
                                            @else
                                                <td class="clockIn">
                                                    <span class="text-success"><i
                                                            class="badge bg-success p-2  rounded">{{ __('In') }}</i></span>
                                                </td>
                                                <td class="clockOut">
                                                    <span class="text-danger"><i
                                                            class="badge bg-danger p-2  rounded">{{ __('Out') }}</i></span>
                                                </td>
                                            @endif
                                            <td class="workingHours">
                                                <span class="text-secondary"><i
                                                        class="badge bg-secondary p-2  rounded">{{ __('WH') }}</i></span>
                                            </td>
                                            <td class="workingHours">
                                                <span class="text-secondary"><i
                                                        class="badge bg-primary p-2  rounded">{{ __('LC') }}</i></span>
                                            </td>
                                            <td class="workingHours">
                                                <span class="text-secondary"><i
                                                        class="badge bg-info p-2  rounded">{{ __('EL') }}</i></span>
                                            </td>
                                        @endforeach
                                    </tr>

                                    @foreach ($employeesAttendance->groupBy('employee_id') as $key => $row)
                                        @php
                                            $employeeData = getEmployeeDetails($key);
                                        @endphp
                                        <tr>
                                            <td>{{ $employeeData->employee_id ?? '' }}</td>
                                            <td>{{ $employeeData->name ?? '' }}</td>
                                            <td>{{ $employeeData->branch->name ?? '' }}</td>
                                            <td>{{ $employeeData->designation->name ?? '' }}</td>

                                            @foreach ($monthDates as $monthDateRow)
                                                @php

                                                    $workingHours = 0;
                                                    $late = '00:00';
                                                    $earlyLeaving = '00:00';

                                                    $checkData = $employeesAttendance->where(
                                                        'attendance_date',
                                                        $monthDateRow,
                                                    );

                                                    $columnsCount = 0;
                                                    if ($checkData != null) {
                                                        if ($checkData->groupBY('employee_id')->max() != null) {
                                                            $columnsCount = $checkData
                                                                ->groupBY('employee_id')
                                                                ->max()
                                                                ->count();
                                                        }
                                                    }

                                                @endphp

                                                @if ($columnsCount > 0)
                                                    @foreach ($checkData->where('employee_id', $key) as $childRow)
                                                        @php
                                                            $clockInDateTime = '';
                                                            $clockOutDateTime = '';

                                                            if (
                                                                empty($childRow->clock_in_date_time) ||
                                                                $childRow->clock_in_date_time == '0000-00-00 00:00:00'
                                                            ) {
                                                                $clockInDateTime =
                                                                    $childRow->getRawOriginal('date') .
                                                                    ' ' .
                                                                    $childRow->clock_in;
                                                            } else {
                                                                $clockInDateTime = $childRow->clock_in_date_time;
                                                            }

                                                            if (
                                                                empty($childRow->clock_out_date_time) ||
                                                                $childRow->clock_out_date_time == '0000-00-00 00:00:00'
                                                            ) {
                                                                $clockOutDateTime =
                                                                    $childRow->getRawOriginal('date') .
                                                                    ' ' .
                                                                    $childRow->clock_out;
                                                            } else {
                                                                $clockOutDateTime = $childRow->clock_out_date_time;
                                                            }
                                                        @endphp

                                                        {{-- New --}}
                                                        <td class="clockIn">
                                                            {{-- {{ \Auth::user()->dateFormat($clockInDateTime) . ' ' . \Auth::user()->timeFormat($clockInDateTime) }} --}}

                                                            @php
                                                                if (
                                                                    $childRow->clock_in != '' &&
                                                                    $childRow->clock_in != '00:00:00'
                                                                ) {
                                                                    echo \Auth::user()->dateFormat($clockInDateTime) .
                                                                        ' ' .
                                                                        \Auth::user()->timeFormat($clockInDateTime);
                                                                }
                                                            @endphp

                                                        </td>
                                                        <td class="clockOut">
                                                            {{-- {{ \Auth::user()->dateFormat($clockOutDateTime) . ' ' . \Auth::user()->timeFormat($clockOutDateTime) }} --}}

                                                            @php
                                                                if (
                                                                    $childRow->clock_out != '' &&
                                                                    $childRow->clock_out != '00:00:00'
                                                                ) {
                                                                    echo \Auth::user()->dateFormat($clockOutDateTime) .
                                                                        ' ' .
                                                                        \Auth::user()->timeFormat($clockOutDateTime);
                                                                }
                                                            @endphp
                                                        </td>
                                                        {{-- New --}}

                                                        @php

                                                            if (
                                                                $childRow->clock_in != '00:00:00' &&
                                                                $childRow->clock_out != '00:00:00'
                                                            ) {
                                                                try {
                                                                    if (
                                                                        $late == '00:00' &&
                                                                        $childRow->late != '00:00:00'
                                                                    ) {
                                                                        $late = date('H:i', strtotime($childRow->late));
                                                                    }
                                                                    $earlyLeaving = date(
                                                                        'H:i',
                                                                        strtotime($childRow->early_leaving),
                                                                    );

                                                                    $from = new DateTime($clockInDateTime);
                                                                    $to = new DateTime($clockOutDateTime);

                                                                    $diff = $from->diff($to);

                                                                    // Total working minutes
                                                                    $totalMinutes =
                                                                        $diff->i +
                                                                        $diff->h * 60 +
                                                                        $diff->days * 24 * 60;

                                                                    // Convert total minutes to hours with decimal representation
                                                                    $workingHours += $totalMinutes;
                                                                } catch (Exception | Throwable $th) {
                                                                }
                                                            }

                                                        @endphp
                                                    @endforeach

                                                    @for ($i = 1; $i <= $columnsCount - $checkData->where('employee_id', $key)->count(); $i++)
                                                        <td class="clockIn"></td>
                                                        <td class="clockOut"></td>
                                                    @endfor
                                                @else
                                                    <td class="clockIn"></td>
                                                    <td class="clockOut"></td>
                                                @endif
                                                @php
                                                    $working_hours = floor($workingHours / 60);
                                                    $working_minutes = $workingHours % 60;
                                                    $workingHours = $working_hours . ':' . $working_minutes;
                                                @endphp
                                                <td class="workingHours">{{ $workingHours }}</td>

                                                <td class="workingHours">{{ $late }}</td>

                                                <td class="workingHours">{{ $earlyLeaving }}</td>
                                            @endforeach

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
    </div>
@endsection

@push('script-page')
    <script>
        // Event listener for changes in branch_id
        $(document).on('change', 'select[name=branch_id]', function() {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        // Handle request parameters if they exist
        @if (!empty($requestBranchId))
            getDepartment({{ $requestBranchId }}, {{ $requestDepartmentId ?? 0 }});
        @endif

        var selectedEmployeesIds = [];
        var requestDepartmentId = "0";

        @if (!empty($requestEmployeeId))
            selectedEmployeesIds = <?php echo json_encode($requestEmployeeId); ?>;
        @endif

        @if (!empty($requestDepartmentId))
            requestDepartmentId = <?php echo json_encode($requestDepartmentId); ?>;
        @endif

        getEmployee(requestDepartmentId, selectedEmployeesIds);

        // Function to fetch and populate departments
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
                    var departmentSelect =
                        `<select class="form-control select department_id" id="department" name="department_id"></select>`;
                    $('.department_div').html(departmentSelect);

                    $('.department_id').append('<option value="0">{{ __('All ') }}</option>');
                    $.each(data, function(key, value) {
                        var selected = (selectDepartmentId == key) ? "selected" : "";
                        $('.department_id').append('<option value="' + key + '" ' + selected + '>' +
                            value + '</option>');
                    });
                }
            });
        }

        // Event listener for changes in department_id
        $(document).on('change', '.department_id', function() {
            var department_id = $(this).val();
            getEmployee(department_id);
        });

        // Function to fetch and populate employees
        function getEmployee(did, selectedEmployeesIds = []) {
            $.ajax({
                url: "{{ route('monthly.getemployee') }}",
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#employee_id').empty();
                    $('#employee_div').html(
                        '<label for="employee" class="form-label">{{ __('Employee ') }}</label><select class="form-control" id="employee_id" name="employee_id[]" multiple></select>'
                    );

                    $('#employee_id').append('<option value="">{{ __('Select Employee ') }}</option>');
                    if ($.inArray(0, selectedEmployeesIds) !== -1) {
                        $('#employee_id').append(
                            '<option value="0" selected>{{ __('All Employee ') }}</option>');
                    } else {
                        $('#employee_id').append('<option value="0">{{ __('All Employee ') }}</option>');
                    }

                    $.each(data, function(key, value) {
                        var selected = ($.inArray(key, selectedEmployeesIds) !== -1) ? "selected" : "";
                        $('#employee_id').append('<option value="' + key + '" ' + selected + '>' +
                            value + '</option>');
                    });

                    new Choices('#employee_id', {
                        removeItemButton: true,
                    });
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            //   var now = new Date();
            //   var month = (now.getMonth() + 1);
            //   var day = now.getDate();
            //   if (month < 10) month = "0" + month;
            //   if (day < 10) day = "0" + day;
            //   var today = now.getFullYear() + '-' + month + '-' + day;
            //   $('.current_date').val(today);
        });
    </script>
@endpush
