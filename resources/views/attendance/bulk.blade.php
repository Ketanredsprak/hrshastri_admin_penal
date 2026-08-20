@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Bulk Attendance') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Bulk Attendance') }}</li>
@endsection


@push('script-page')
    <script>
        $('#present_all').click(function(event) {
            // alert('hiii');
            if (this.checked) {
                $('.present').each(function() {
                    this.checked = true;
                });

                $('.present_check_in').removeClass('d-none');
                $('.present_check_in').addClass('d-block');

            } else {
                $('.present').each(function() {
                    this.checked = false;
                });
                $('.present_check_in').removeClass('d-block');
                $('.present_check_in').addClass('d-none');

            }
        });

        $('.present').click(function(event) {
            var div = $(this).parent().parent().parent().parent().find('.present_check_in');

            if (this.checked) {
                div.removeClass('d-none');
                div.addClass('d-block');

            } else {
                div.removeClass('d-block');
                div.addClass('d-none');
            }

        });
    </script>
@endpush

@section('action-button')
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class=" mt-2" id="" style="">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['attendanceemployee.bulkattendance'], 'method' => 'get', 'id' => 'bulkattendance_filter']) }}


                        <div class="row align-items-center">
                            <div class="col-sm-9">
                                <div class="row ">
                                    <div class="col-sm-4 col-lg-4">
                                        <div class="btn-box">
                                            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                                            {{ Form::text('date', isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'), ['class' => 'month-btn form-control d_week ', 'autocomplete' => 'off']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-lg-4">
                                        <div class="btn-box">
                                            {{ Form::label('branch', __('Branch'), ['class' => 'form-label']) }}
                                            {{ Form::select('branch', $branch, isset($_GET['branch']) ? $_GET['branch'] : '', ['class' => 'form-control select ', 'id' => 'branch_id']) }}
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-lg-4">
                                        <div class="btn-box">
                                            {{ Form::label('department', __('Department'), ['class' => 'form-label']) }}
                                            {{-- {{ Form::select('department', $department, isset($_GET['department']) ? $_GET['department'] : '', ['class' => 'form-control select ', 'id' => 'department_id']) }} --}}

                                            {{ Form::select('department', [], isset($_GET['department']) ? $_GET['department'] : '', ['id' => 'department_id', 'class' => 'form-control']) }}
                                            {{ Form::hidden('requestDepartmentId', $requestDepartmentId ?? '', ['id' => 'requestDepartmentId', 'class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-3 text-right pt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('bulkattendance_filter').submit(); return false;"
                                    data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('attendanceemployee.bulkattendance') }}" class="btn btn-sm btn-danger"
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
                    {{ Form::open(['route' => ['attendanceemployee.bulkattendance'], 'method' => 'post']) }}
                    <div class="table-responsive">
                        <table class="table" id="">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">{{ __('Employee Id') }}</th>
                                    <th style="width: 25%;">{{ __('Employee') }}</th>
                                    <th style="width: 10%;">{{ __('Branch') }}</th>
                                    <th style="width: 10%;">{{ __('Department') }}</th>
                                    <th style="width: 40%;" class="text-center">
                                        <label class="custom-control-label" for="present_all">
                                            <input class="form-check-input me-2" type="checkbox" name="present_all" id="present_all" {{ old('remember') ? 'checked' : '' }}>{{ __('Attendance') }}
                                        </label> 
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    @php
                                        $attendance = $employee->present_status(
                                            $employee->id,
                                            isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'),
                                        );
                                    @endphp
                                    <tr>
                                        <td class="Id" style="width: 5%;">
                                            <input type="hidden" value="{{ $employee->id }}" name="employee_id[]">
                                            <a href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}"
                                                class="btn btn-outline-primary">{{ $employee->employee_id }}</a>
                                        </td>
                                        <td style="width: 25%;">{{ $employee->name }}</td>
                                        <td style="width: 10%;">{{ !empty($employee->branch) ? $employee->branch->name : '' }}</td>
                                        <td style="width: 10%;">{{ !empty($employee->department) ? $employee->department->name : '' }}</td>
                                        <td style="width: 40%;">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <label class="custom-control-label" for="present{{ $employee->id }}">
                                                    <input class="form-check-input present me-2" type="checkbox"
                                                    name="present-{{ $employee->id }}"
                                                    id="present{{ $employee->id }}"
                                                    {{ !empty($attendance) && $attendance->status == 'Present' ? 'checked' : '' }}>
                                                </label>
                                               
                                           
                                            
                                                

                                                <?php
                                                
                                                //    $clockIn = '';
                                                //    $clockOut = '';
                                                //    if (!empty($attendance)) {
                                                //        if ($attendance->clock_in != '00:00:00' && !empty($attendance->clock_in)) {
                                                //            $clockIn = $attendance->clock_in;
                                                //        }
                                                
                                                //        if ($attendance->clock_out != '00:00:00' && !empty($attendance->clock_out)) {
                                                //            $clockOut = $attendance->clock_out;
                                                //        }
                                                //    }
                                                
                                                //    ---------------
                                                
                                                $clockInDateTime = null;
                                                $clockoutDateTime = null;
                                                if (!empty($attendance)) {
                                                    $clockInTime = date('H:i:s', strtotime($attendance->clock_in_date_time)) ?? '';
                                                    $clockOutTime = date('H:i:s', strtotime($attendance->clock_out_date_time)) ?? '';
                                                
                                                    if (empty($clockInTime) || $clockInTime == '00:00:00') {
                                                        $clockInTime = $attendance->clock_in ?? '';
                                                    }
                                                
                                                    if (empty($clockOutTime) || $clockOutTime == '00:00:00') {
                                                        $clockOutTime = $attendance->clock_out ?? '';
                                                    }
                                                
                                                    $clockInDateTime = date('Y-m-d', strtotime($attendance->clock_in_date_time)) . ' ' . $clockInTime;
                                                    if ($attendance->clock_out_date_time != null) {
                                                        $clockoutDateTime = date('Y-m-d', strtotime($attendance->clock_out_date_time)) . ' ' . $clockOutTime;
                                                    }
                                                }
                                                ?>
                                                <div
                                                    class="present_check_in {{ empty($attendance) ? 'd-none' : '' }} ">
                                                    <div class="row">
                                                        
                                                        <div class="col-sm-6">
                                                            <div class="d-flex align-items-center">
                                                            <label class="control-label me-2">{{ __('In') }}</label>
                                                            <input type="datetime-local" class="form-control"
                                                                name="in-{{ $employee->id }}"
                                                                value="{{ $clockInDateTime }}">
                                                            </div>
                                                        </div>

                                                        
                                                        <div class="col-sm-6">
                                                            <div class="d-flex align-items-center">
                                                            <label for="inputValue"
                                                            class="control-label me-2">{{ __('Out') }}</label>
                                                            <input type="datetime-local" class="form-control"
                                                                name="out-{{ $employee->id }}"
                                                                value="{{ $clockoutDateTime }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>    
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="attendance-btn float-end pt-4">
                        <input type="hidden" value="{{ isset($_GET['date']) ? $_GET['date'] : date('Y-m-d') }}"
                            name="date">
                        <input type="hidden" value="{{ isset($_GET['branch']) ? $_GET['branch'] : '' }}" name="branch">
                        <input type="hidden" value="{{ isset($_GET['department']) ? $_GET['department'] : '' }}"
                            name="department">
                        {{ Form::submit(__('Update'), ['class' => 'btn btn-primary']) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
    <script>
        $(document).ready(function() {
            if ($('.daterangepicker').length > 0) {
                $('.daterangepicker').daterangepicker({
                    format: 'yyyy-mm-dd',
                    locale: {
                        format: 'YYYY-MM-DD'
                    },
                });
            }
        });
    </script>
@endpush



@push('script-page')
    <script type="text/javascript">
        $(document).ready(function() {

            getDepartments();

            $('#branch_id').on('change', function() {

                $('#department_id').empty();
                getDepartments();
            });

        });

        function getDepartments() {

            try {
                $.ajax({
                    url: "{{ route('get-departments') }}", // Route to fetch departments
                    type: 'POST',
                    data: {
                        branch_id: ($('#branch_id').val() ?? 0),
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {

                        try {
                            if (data.length > 1) {
                                var newOption = new Option("All", 0, false, false);
                                $('#department_id').append(newOption);
                            }

                            var branch_id = ($('#branch_id').val() ?? 0);

                            var requestDepartmentId = $("#requestDepartmentId").val();

                            console.log("requestDepartmentId", requestDepartmentId);

                            $("#requestDepartmentId").val("");


                            data.forEach(function(department) {

                                var isSelected = false;

                                if (data.length == 1 || department.id == requestDepartmentId) {
                                    var isSelected = true;
                                }
                                var newOption = new Option(department.name, department.id, isSelected,
                                    isSelected);
                                $('#department_id').append(newOption);
                            });



                        } catch (error) {

                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching departments:', error);
                    }
                });

            } catch (error) {
                console.error('Error fetching departments:', error);
            }
        }
    </script>
@endpush
