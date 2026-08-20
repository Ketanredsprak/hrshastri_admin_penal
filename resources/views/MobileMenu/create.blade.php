{{ Form::open(['url' => 'submit_mobile_menu', 'method' => 'post', 'id' => 'categoriesModel', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-lg-12">
            {{ Form::label('name', __('name'), ['class' => 'col-form-label']) }} <span class="text-danger"> *</span>
            <input type="text" id="name" name="name" class="form-control" required
                placeholder="Please enter name">
        </div>

        <div class="form-group col-lg-12">
            {{ Form::label('title', __('Title'), ['class' => 'col-form-label']) }} <span class="text-danger"> *</span>
            <input type="text" id="title" name="title" class="form-control" required
                placeholder="Please enter title">
        </div>

        <div class="form-group col-lg-12">
            {{ Form::label('icon', __('Icon'), ['class' => 'col-form-label']) }} <span class="text-danger"> *</span>
            <input type="file" id="icon" name="icon" class="form-control" required>
        </div>

        <div class="form-groupcol-lg-12">
            {{ Form::label('sequence_number', __('Sequence Number'), ['class' => 'col-form-label']) }} <span
                class="text-danger"> *</span>
            <input type="text" id="sequence_number" name="sequence_number" class="form-control" id="sequence_number"
                required value="1">
        </div>

        <div class="form-groupcol-lg-12">
            {{ Form::label('parent_id', __('Parent Menu'), ['class' => 'col-form-label']) }}
            {!! Form::select('parent_id', $parentMenusList, '', [
                'id' => 'parent_id',
                'class' => 'form-control select2',
            ]) !!}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Submit') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
