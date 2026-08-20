<div class="col-form-label" width="100" style="width: 100%">

    @php
        $interviewScheduleData = [];

        $interviewScheduleData = $interviewSchedule
            ->candidateJobApplicationData()
            ->where('id', $job_application_id)
            ->first();
    @endphp

    <div class="card-body">
        <h6 class="mb-4">{{ __('Schedule Detail') }}</h6>
        <dl class="row mb-0 align-items-center">
            <dt class="col-sm-3 h6 text-sm">{{ __('Job') }}</dt>
            <dd class="col-sm-9 text-sm">
                {{ $interviewScheduleData->jobs->title }}
            </dd>
            <dt class="col-sm-3 h6 text-sm">{{ __('Interview On') }}</dt>
            <dd class="col-sm-9 text-sm">
                {{ \Auth::user()->dateFormat($interviewSchedule->date) . ' ' . \Auth::user()->timeFormat($interviewSchedule->time) }}
            </dd>
            <dt class="col-sm-3 h6 text-sm">{{ __('Interviewer') }}</dt>

            <dd class="col-sm-9 text-sm">
                {{ implode(', ', $interviewSchedule->interviewerData()->pluck('name')->toArray()) }}
            </dd>

        </dl>

    </div>
    <div class="card-body">
        <h6 class="mb-4">{{ __('Candidate Detail') }}</h6>
        <dl class="row mb-0 align-items-center">
            <dt class="col-sm-3 h6 text-sm">{{ __('Name') }}</dt>
            <dd class="col-sm-9 text-sm">
                {{ $interviewScheduleData->name }}</dd>
            <dt class="col-sm-3 h6 text-sm">{{ __('Email') }}</dt>
            <dd class="col-sm-9 text-sm">
                {{ $interviewScheduleData->email }}</dd>
            <dt class="col-sm-3 h6 text-sm">{{ __('Phone') }}</dt>
            <dd class="col-sm-9 text-sm">
                {{ $interviewScheduleData->phone }}</dd>
        </dl>
    </div>


    <div class="card-body">
        <h6 class="mb-4">{{ __('Candidate Status') }}</h6>
        @foreach ($stages as $stage)
            <div class="custom-control custom-radio">
                <input type="radio" id="stage_{{ $stage->id }}" name="stage"
                    data-scheduleid="{{ $interviewScheduleData->id }}" value="{{ $stage->id }}"
                    class="form-check-input stages" {{ $interviewScheduleData->stage == $stage->id ? 'checked' : '' }}>
                <label class="custom-control-label" for="stage_{{ $stage->id }}">{{ $stage->title }}</label>
            </div>
        @endforeach
    </div>
    <div class="card-footer">


        <a href="#"
            data-url="{{ route('job.on.board.create', $interviewScheduleData->id) . '?job_application_id=' . $job_application_id }}"
            data-ajax-popup="true" class="btn btn-primary"> {{ __('Add to Job OnBoard') }}</a>
    </div>
</div>
