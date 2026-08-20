{{ Form::open(['route' => ['resignation.exit_inerviewupdate', $id], 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">

        <div class="form-group col-lg-12">
            {{ Form::label('description', __('Exit Interview Notes '), ['class' => 'col-form-label']) }} <span
                class="text-danger">*</span>
            {{ Form::textarea('description', $data->exit_notes, ['class' => 'form-control', 'placeholder' => __('Enter exit interview notes'), 'rows' => '3', 'required' => 'required']) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>

{{ Form::close() }}
