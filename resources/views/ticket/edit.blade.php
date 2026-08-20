{{ Form::model($ticket, ['route' => ['ticket.update', $ticket->id], 'method' => 'PUT']) }}

@php
    $isCompanyUser = Auth::user()->type == 'company' ? true : false;
@endphp
<div class="modal-body">

    <div class="row">

        <div class="form-group">

            {{ Form::label('title', __('Subject'), ['class' => 'col-form-label']) }}

            {{ Form::text('title', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Ticket Subject'), 'readonly' => $isCompanyUser]) }}

        </div>

        @if (\Auth::user()->type == 'company')
            <div class="form-group">

                {{ Form::label('employee_id', __('Ticket for Employee'), ['class' => 'col-form-label']) }}

                {{ Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'disabled' => $isCompanyUser]) }}

            </div>
        @endif

        <div class="row">

            <div class="col-md-6 col-sm-12 col-lg-6 col-xl-6">

                <div class="form-group">

                    {{ Form::label('priority', __('Priority'), ['class' => 'col-form-label']) }} <span
                        class="text-danger"> * </span>

                    <select name="priority" class="form-control select2" id="choices-multiple" required>

                        <option value="low" @if ($ticket->priority == 'low') selected @endif>{{ __('Low') }}

                        </option>

                        <option value="medium" @if ($ticket->priority == 'medium') selected @endif>{{ __('Medium') }}

                        </option>

                        <option value="high" @if ($ticket->priority == 'high') selected @endif>{{ __('High') }}

                        </option>

                        <option value="critical" @if ($ticket->priority == 'critical') selected @endif>

                            {{ __('critical') }}

                        </option>

                    </select>

                </div>

            </div>

            <div class="col-md-6 col-sm-12 col-lg-6 col-xl-6" style="display: ;">

                <div class="form-group">

                    {{ Form::label('created_at', __('Created On'), ['class' => 'col-form-label']) }}

                    {{ Form::text('created_at', null, ['class' => 'form-control d_week', 'required' => 'required', 'autocomplete' => 'off', 'readonly' => $isCompanyUser]) }}

                </div>

            </div>

        </div>





        <div class="form-group">

            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Ticket Description'), 'rows' => '5', 'readonly' => $isCompanyUser]) }}

        </div>

        <div class="form-group">

            {{ Form::label('status', __('Status'), ['class' => 'col-form-label']) }}

            <select name="status" class="form-control  select2" id="status">

                <option value="close" @if ($ticket->status == 'close') selected @endif>{{ __('Close') }}</option>

                <option value="open" @if ($ticket->status == 'open') selected @endif>{{ __('Open') }}</option>

                <option value="onhold" @if ($ticket->status == 'onhold') selected @endif>{{ __('On Hold') }}</option>

            </select>

        </div>

    </div>

</div>

<div class="modal-footer">

    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">

    <input type="submit" value="{{ __('Update') }}" class="btn  btn-primary">



</div>

{{ Form::close() }}
