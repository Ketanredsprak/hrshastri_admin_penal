{{ Form::model($interviewSchedule, ['route' => ['interview-schedule.update', $interviewSchedule->id], 'method' => 'PUT']) }}

<div class="modal-body">

    <div class="row">

        <div class="form-group col-md-6">

            {{ Form::label('candidate_job_application_ids', __('Candidate'), ['class' => 'col-form-label']) }} <span
                class="text-danger"> * </span>

            {{ Form::select('candidate_job_application_ids', $candidates, json_decode($interviewSchedule->candidate_job_application_ids), ['class' => 'form-control select2', 'multiple' => 'multiple', 'name' => 'candidate_job_application_ids[]', 'required' => 'required']) }}

        </div>

        <div class="form-group col-md-6">

            {{ Form::label('interviewer_ids', __('Interviewer'), ['class' => 'col-form-label']) }} <span
                class="text-danger"> *
            </span>

            {{ Form::select('interviewer_ids', $employees, json_decode($interviewSchedule->interviewer_ids), ['class' => 'form-control select2', 'multiple' => 'multiple', 'name' => 'interviewer_ids[]', 'required' => 'required']) }}

        </div>

        <div class="form-group col-md-6">

            {{ Form::label('date', __('Interview Date'), ['class' => 'col-form-label']) }} <span class="text-danger"> *
            </span>

            {{ Form::date('date', null, ['class' => 'form-control', 'required' => 'required', 'autocomplete' => 'off', 'id' => 'currentDate']) }}


        </div>

        <div class="form-group col-md-6">

            {{ Form::label('time', __('Interview Time'), ['class' => 'col-form-label']) }} <span class="text-danger"> *
            </span>
            {{ Form::time('time', null, ['class' => 'form-control ', 'id' => 'currentTime', 'required' => 'required']) }}


        </div>

        <div class="form-group">

            {{ Form::label('comment', __('Comment'), ['class' => 'col-form-label']) }}

            {{ Form::textarea('comment', null, ['class' => 'form-control']) }}

        </div>

    </div>

</div>

<div class="modal-footer">

    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>

    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">



</div>

{{ Form::close() }}
