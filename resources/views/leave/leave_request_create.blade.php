
@php
    $setting = App\Models\Utility::settings();
@endphp
    {{ Form::open(['url' => 'leave', 'method' => 'post']) }}
<div class="modal-body">

        
        <div class="row employee_option">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }} <span class="text-danger">*</span>
                    <select name="employee_id" id="employee_id" class="form-control select2">
                        <option value="">Select employee</option>
                            @foreach ($employees as $row)
                                <option value="{{ $row->id}}">{{ $row->name}}</option>
                            @endforeach
                    </select>
                </div>
            </div>
        </div>
        
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('leave_type_id', __('Leave Type'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                <select name="leave_type_id" id="leave_type_id" class="form-control select">
                    @foreach ($leavetypes as $leave)
                        <option value="{{ $leave->id }}">{{ $leave->title }} (<p class="float-right pr-5">
                                {{ $leave->days }}</p>)</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    {{-- //creae leave balance --}}
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('leave_type_id', __('Leave Day'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                <select name="leave_type_day" id="leave_type_day" class="form-control select">
                    <option value="">Select Leave Day</option>
                    <option value="0.5">Half-Day</option>
                    <option value="1">Full-Day</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group start_date_calender_date">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                {{ Form::text('start_date', null, [ 'id' => 'start_date_calender','class' => 'form-control d_week current_date start_date_calender', 'autocomplete' => 'off']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group end_date_calender_date">
                {{ Form::label('end_date', __('End Date'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                {{ Form::text('end_date', null, ['class' => 'form-control d_week current_date end_date_calender',  'autocomplete' => 'off']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('leave_reason', __('Leave Reason'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                {{ Form::textarea('leave_reason', null, ['class' => 'form-control', 'placeholder' => __('Leave Reason'), 'rows' => '3']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('remark', __('Remark'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                {{ Form::textarea('remark', null, ['class' => 'form-control', 'placeholder' => __('Leave Remark'), 'rows' => '3']) }}
            </div>
        </div>
    </div>
    @if(isset($setting['is_enabled']) && $setting['is_enabled'] =='on')
    <div class="form-group col-md-6">
        {{ Form::label('synchronize_type', __('Synchroniz in Google Calendar ?'), ['class' => 'form-label']) }}
        <div class=" form-switch">
            <input type="checkbox" class="form-check-input mt-2" name="synchronize_type" id="switch-shadow"
                value="google_calender">
            <label class="form-check-label" for="switch-shadow"></label>
        </div>
    </div>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {


        $(document).on('change', '#employee_id', function() {
            var employee_id = $(this).val();

            $.ajax({
                url: '{{ route('leave.jsoncount') }}',
                type: 'POST',
                data: {
                    "employee_id": employee_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    console.log("testt",data);
                    $('#leave_type_id').empty();
                    $('#leave_type_id').append(
                        '<option value="">{{ __('Select Leave Type') }}</option>');

                    // $.each(data, function(key, value) {

                    //     if (value.total_leave == value.days) {
                    //         $('#leave_type_id').append('<option value="' + value.id +
                    //             '" disabled>' + value.title + '&nbsp(' + value.total_leave +
                    //             '/' + value.days + ')</option>');
                    //     } else {
                    //         $('#leave_type_id').append('<option value="' + value.id + '">' +
                    //             value.title + '&nbsp(' + value.total_leave + '/' + value
                    //             .days + ')</option>');
                    //     }
                    // });
                    $.each(data, function(key, value) {

                        if (value.total_leave == value.days) {
                            $('#leave_type_id').append('<option value="' + value.id +
                                '" disabled>' + value.title + '&nbsp</option>');
                        } else {
                            $('#leave_type_id').append('<option value="' + value.id + '">' +
                                value.title + '&nbsp</option>');
                        }
                    });
                }
            });
        });

        $('#option_select').on('change', function() {
            
            if(this.value == 1){
                $('.staff_option').removeClass('d-none');
                $('.employee_option').addClass('d-none');
            }else{
                $('.employee_option').removeClass('d-none');
                $('.staff_option').addClass('d-none');
            }
        });

        var now = new Date();
        var month = (now.getMonth() + 1);
        var day = now.getDate();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var today = now.getFullYear() + '-' + month + '-' + day;
        $('.current_date').val(today);
        $('#leave_type_day').on('change', function() {
            if(this.value == 0.5){
                $('.end_date_calender_date').addClass('d-none')
            }else{
                $('.end_date_calender_date').removeClass('d-none')
            }
            var start_date_value = $('.start_date_calender').val();
            $('.end_date_calender').val(start_date_value);
        });


    });



</script>
