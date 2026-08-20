{{ Form::open(['url' => 'warning', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        @if (\Auth::user()->type != 'employee')
            <div class="form-group col-md-6 col-lg-6">
                {{ Form::label('warning_by', __('Warning By'), ['class' => 'col-form-label']) }} <span class="text-danger">*</span>
                {{ Form::select('warning_by', $employees, null, ['class' => 'form-control select2','placeholder' => __('Select a Warning By')]) }}
            </div>
        @endif
        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('warning_to', __('Warning To'), ['class' => 'col-form-label']) }} <span class="text-danger">*</span>
            {{ Form::select('warning_to', $employees, null, ['class' => 'form-control select2','placeholder' => __('Select a Warning To') ]) }}
        </div>
        <div class="form-group col-md-6 col-lg-6">
            {{ Form::label('subject', __('Subject'), ['class' => 'col-form-label']) }} <span class="text-danger">*</span>
            {{ Form::text('subject', null, ['class' => 'form-control']) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }} <span class="text-danger">*</span>
            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description') ,'rows' => '3' ]) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>

{{ Form::close() }}
