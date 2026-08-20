@php
    $setting = App\Models\Utility::settings();
@endphp
    {{ Form::open(['route' => ['getpass.update', $data->id], 'method' => 'Put']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('leave_type_id', __('Get pass Type'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                <select name="leave_type_id" id="leave_type_id" class="form-control select" required>
                    <option value=''>Select Get pass Type</option>
                    <option @if($data->name == 1) selected  @endif value="1">Personal work</option>
                    <option @if($data->name == 2) selected @endif value="2">Office Work</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <div class="form-group start_date_calender_date">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                {{ Form::text('start_date', $data->date, [ 'id' => 'start_date_calender','required' => 'required','class' => 'form-control d_week current_date start_date_calender', 'autocomplete' => 'off']) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group start_date_calender_date">
                {{ Form::label('start_date', __('In Time'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                {{ Form::text('in_time', $data->in_time, ['class' => 'form-control ', 'autocomplete' => 'off','required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group start_date_calender_date">
                {{ Form::label('start_date', __('Out Time'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                {{ Form::text('out_time',  $data->out_time, ['class' => 'form-control ', 'autocomplete' => 'off','required' => 'required']) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('leave_reason', __('Reason'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                {{ Form::textarea('leave_reason', $data->resone, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Reason'), 'rows' => '3']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {

        $(".in_time").timepicker({
            timeFormat: "h:mm p", 
            interval: 30, 
            minTime: "{{ $data->in_time }}",
            maxTime: "23:55pm", 
            defaultTime: "06", 
            startTime: "01:00", 
            dynamic: false, 
            dropdown: false, 
            scrollbar: false 
        });
        $(".out_time").timepicker({
            timeFormat: "h:mm p", 
            interval: 30, 
            minTime: "{{ $data->out_time }}",
            maxTime: "23:55pm", 
            defaultTime: "06", 
            startTime: "01:00", 
            dynamic: false, 
            dropdown: false, 
            scrollbar: false 
        });

});
</script>