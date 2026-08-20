{{ Form::open(['route' => ['job.on.board.store', $id], 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">

        <div class="form-group col-md-12">
            <h6 class="mb-4">{{ __('Candidate Detail') }}</h6>
            <dl class="row mb-0 align-items-center">
                <dt class="col-sm-3 h6 text-sm">{{ __('Name') }}</dt>
                <dd class="col-sm-9 text-sm">
                    {{ $jobApplicationData->name ?? '' }}</dd>
                <dt class="col-sm-3 h6 text-sm">{{ __('Email') }}</dt>
                <dd class="col-sm-9 text-sm">
                    {{ $jobApplicationData->email ?? '' }}</dd>
                <dt class="col-sm-3 h6 text-sm">{{ __('Phone') }}</dt>
                <dd class="col-sm-9 text-sm">
                    {{ $jobApplicationData->phone ?? '' }}</dd>
            </dl>
        </div>
        @if ($id == 0)
            <div class="form-group col-md-12">
                {{ Form::label('application', __('Candidate'), ['class' => 'col-form-label']) }}
                {{ Form::select('application', $applications, null, ['class' => 'form-control select2', 'required' => 'required']) }}
            </div>
        @endif
        <div class="form-group col-md-12">
            {!! Form::label('joining_date', __('Joining Date'), ['class' => 'col-form-label']) !!}
            {!! Form::date('joining_date', null, ['class' => 'form-control current_date', 'autocomplete' => 'off']) !!}
        </div>

        <div class="form-group col-md-6">
            {!! Form::label('days_of_week', __('Days Of Week'), ['class' => 'col-form-label']) !!}
            {!! Form::number('days_of_week', null, ['class' => 'form-control ', 'autocomplete' => 'off', 'min' => '0']) !!}
        </div>
        <div class="form-group col-md-6">
            {!! Form::label('salary', __('Salary'), ['class' => 'col-form-label']) !!}
            {!! Form::number('salary', null, ['class' => 'form-control ', 'autocomplete' => 'off', 'min' => '0']) !!}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('salary_type', __('Pay Slab'), ['class' => 'col-form-label']) }}
            {{ Form::select('salary_type', $salary_type, null, ['class' => 'form-control select']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('salary_duration', __('Salary Duration'), ['class' => 'col-form-label']) }}
            {{ Form::select('salary_duration', $salary_duration, null, ['class' => 'form-control select']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('job_type', __('Job Type'), ['class' => 'col-form-label']) }}
            {{ Form::select('job_type', $job_type, null, ['class' => 'form-control select']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('status', __('Status'), ['class' => 'col-form-label']) }}
            {{ Form::select('status', $status, null, ['class' => 'form-control select']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        var now = new Date();
        var month = (now.getMonth() + 1);
        var day = now.getDate();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var today = now.getFullYear() + '-' + month + '-' + day;
        $('.current_date').val(today);
    });
</script>
