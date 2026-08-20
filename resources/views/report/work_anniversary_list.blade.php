@extends('layouts.admin')

@section('page-title')
    {{ __('Work Anniversary List') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Work Anniversary List Report') }}</li>
@endsection


@push('script-page')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class=" mt-2 " id="" style="">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['report.work_anniversary_list'], 'method' => 'get', 'id' => 'report_work_anniversary_list']) }}

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
                                <button name="btn_trigger" value="export" class="btn btn-sm btn-primary"
                                    data-bs-toggle="tooltip" data-bs-original-title="{{ __('Export') }}">
                                    <i class="ti ti-file-export"></i>
                                </button>
                            </div>
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>

        <div id="printableArea">
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
                                            value="{{ $data['branch'] . ' ' . __('Branch') . ' ' . $data['curMonth'] . ' ' . $data['department'] . ' ' . 'Department' }}"
                                            id="filename">
                                        <h5 class="mb-0">{{ __('Report') }}</h5>
                                        <div>
                                            <p class="text-muted text-sm mb-0">{{ __('Work Anniversary List') }}</p>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                <div class="col">
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
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive work-anniversary-list-table-responsive">
                            {{-- py-5 --}}
                            <table class="table ">
                                <thead>
                                    <tr>
                                        <th>{{ __('Employee Id') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Branch') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Joining Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td>{{ $employee['employee_id'] }}</td>
                                            <td>{{ $employee['name'] }}</td>
                                            <td>{{ $employee['branch'] ? $employee['branch']->name : '' }}</td>
                                            <td>{{ $employee['email'] }}</td>
                                            <td>{{ \Auth::user()->dateFormat($employee['company_doj']) }}</td>
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
@endpush
