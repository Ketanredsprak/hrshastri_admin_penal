@php
    $setting = App\Models\Utility::settings();
@endphp
    {{ Form::open(['url' => 'applywfm', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group start_date_calender_date">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                {{ Form::text('start_date', null, [ 'id' => 'start_date_calender','required' => 'required','class' => 'form-control d_week current_date start_date_calender', 'autocomplete' => 'off']) }}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group start_date_calender_date">
                {{ Form::label('end_date', __('End Date'), ['class' => 'col-form-label']) }} <span class="text-danger pl-1">*</span>
                {{ Form::text('end_date', null, [ 'id' => 'end_date_calender','required' => 'required','class' => 'form-control d_week current_date start_date_calender', 'autocomplete' => 'off']) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group start_date_calender_date">
                {{ Form::label('note', __('Note'), ['class' => 'col-form-label']) }}
                <textarea type="textarea" class="form-control" name="note"></textarea>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}

