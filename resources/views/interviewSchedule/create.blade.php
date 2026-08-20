@php

    $setting = App\Models\Utility::settings();

@endphp

{{ Form::open(['url' => 'interview-schedule', 'method' => 'post']) }}

<div class="modal-body">

    <div class="row">

        <div class="form-group col-md-6">

            {{ Form::label('candidate_job_application_ids', __('Candidate'), ['class' => 'col-form-label']) }} <span
                class="text-danger"> *
            </span>

            {{ Form::select('candidate_job_application_ids', $candidates, $selectJobApplication?->id ?? 0, ['class' => 'form-control select2', 'multiple' => 'multiple', 'name' => 'candidate_job_application_ids[]', 'required' => 'required', 'disabled' => ($selectJobApplication?->id ?? 0) > 0 ? true : false]) }}

            @if (($selectJobApplication?->id ?? 0) > 0)
                <input type="hidden" name="candidate_job_application_ids[]" value="{{ $selectJobApplication?->id ?? 0 }}">
            @endif

        </div>

        <div class="form-group col-md-6">

            {{ Form::label('interviewer_ids', __('Interviewer'), ['class' => 'col-form-label']) }} <span
                class="text-danger">*</span>

            {{ Form::select('interviewer_ids', $employees, null, ['class' => 'form-control select2', 'multiple' => 'multiple', 'name' => 'interviewer_ids[]', 'required' => 'required']) }}

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

        <div class="form-group ">

            {{ Form::label('comment', __('Comment'), ['class' => 'col-form-label']) }}

            {{ Form::textarea('comment', null, ['class' => 'form-control']) }}

        </div>

        @if (isset($setting['is_enabled']) && $setting['is_enabled'] == 'on')
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

</div>

<div class="modal-footer">

    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>

    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">



</div>

{{ Form::close() }}



{{-- @if ($candidate != 0)
    <script>
        //    $('select#candidate').val({{ $candidate }}).trigger('change');
    </script>
@endif --}}



<script>
    //     const getTwoDigits = (value) => value < 10 ? `0${value}` : value;



    //     const formatDate = (date) => {

    //         const day = getTwoDigits(date.getDate());

    //         const month = getTwoDigits(date.getMonth() + 1); // add 1 since getMonth returns 0-11 for the months

    //         const year = date.getFullYear();



    //         return `${year}-${month}-${day}`;

    //     }



    //     const formatTime = (date) => {

    //         const hours = getTwoDigits(date.getHours());

    //         const mins = getTwoDigits(date.getMinutes());



    //         return `${hours}:${mins}`;

    //     }



    //     const date = new Date();

    //     document.getElementById('currentDate').value = formatDate(date);

    //     document.getElementById('currentTime').value = formatTime(date);
</script>
