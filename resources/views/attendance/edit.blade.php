@php

    //     $clockInDateTime = null;
    //     $clockoutDateTime = null;

    //     $clockInTime = date('H:i:s', strtotime($attendanceEmployee->clock_in_date_time)) ?? '';
    //     $clockOutTime = date('H:i:s', strtotime($attendanceEmployee->clock_out_date_time)) ?? '';

    //     if (empty($clockInTime) || $clockInTime == '00:00:00') {
    //         $clockInTime = $attendanceEmployee->clock_in ?? '';
    //     }

    //     if (empty($clockOutTime) || $clockOutTime == '00:00:00') {
    //         $clockOutTime = $attendanceEmployee->clock_out ?? '';
    //     }

    //     $clockInDateTime = date('Y-m-d', strtotime($attendanceEmployee->clock_in_date_time)) . ' ' . $clockInTime;
    //     if ($attendanceEmployee->clock_out_date_time != null) {
    //         $clockoutDateTime = date('Y-m-d', strtotime($attendanceEmployee->clock_out_date_time)) . ' ' . $clockOutTime;
    //     }

    //     ================

    $clockInDateTime = '';
    $clockOutDateTime = '';

    if (
        empty($attendanceEmployee->clock_in_date_time) ||
        $attendanceEmployee->clock_in_date_time == '0000-00-00 00:00:00'
    ) {
        $clockInDateTime = $attendanceEmployee->getRawOriginal('date') . ' ' . $attendanceEmployee->clock_in;
    } else {
        $clockInDateTime = $attendanceEmployee->clock_in_date_time;
    }

    if (
        empty($attendanceEmployee->clock_out_date_time) ||
        $attendanceEmployee->clock_out_date_time == '0000-00-00 00:00:00'
    ) {
        $clockOutDateTime = $attendanceEmployee->getRawOriginal('date') . ' ' . $attendanceEmployee->clock_out;
    } else {
        $clockOutDateTime = $attendanceEmployee->clock_out_date_time;
    }

    if ($attendanceEmployee->clock_in == '' || $attendanceEmployee->clock_in == '00:00:00') {
        $clockInDateTime = '';
    }

    if ($attendanceEmployee->clock_out == '' || $attendanceEmployee->clock_out == '00:00:00') {
        $clockOutDateTime = '';
    }

    //     dd($clockInDateTime, $clockOutDateTime);

@endphp

{{ Form::model($attendanceEmployee, ['route' => ['attendanceemployee.update', $attendanceEmployee->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-lg-6 col-md-6 ">
            {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
            <label style="color: red">*</label>
            {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'disabled' => true]) }}
        </div>
        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('date', __('Date'), ['class' => 'col-form-label']) }}
            {{ Form::text('date', date('Y-m-d', strtotime($clockInDateTime)), ['class' => 'form-control d_week', 'autocomplete' => 'off', 'readonly']) }}
        </div>

        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('clock_in', __('Clock In'), ['class' => 'col-form-label']) }}
            <label style="color: red">*</label>
            <input type="datetime-local" name="clock_in" id="clock_in_request" value="{{ $clockInDateTime }}"
                class="form-control" step="1">
        </div>

        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('clock_out', __('Clock Out'), ['class' => 'col-form-label']) }}
            <input type="datetime-local" name="clock_out" id="clock_out_request" value="{{ $clockOutDateTime }}"
                class="form-control" step="1">

        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
