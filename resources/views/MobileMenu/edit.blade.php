{{ Form::open(['url' => 'update_mobile_menu', 'method' => 'post', 'id' => 'categoriesModel', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-lg-12">
            {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }} <span class="text-danger"> *</span>
            <input type="text" id="name" value="{{ $data->name }}" name="name" class="form-control" required
                placeholder="Please enter name">
        </div>

        <div class="form-group col-lg-12">
            {{ Form::label('title', __('Title'), ['class' => 'col-form-label']) }} <span class="text-danger"> *</span>
            <input type="text" id="title" value="{{ $data->title }}" name="title" class="form-control"
                required placeholder="Please enter title">
            <input type="hidden" value="{{ $data->id }}" name="id">
        </div>

        <div class="form-group">
            <img src="{{ Storage::url('/public/mobileIcon/' . $data->icon) }}" alt="" width="50"
                height="50">



        </div>
        <div class="form-group col-lg-12">
            {{ Form::label('icon', __('Icon'), ['class' => 'col-form-label']) }} <span class="text-danger">
                *</span>
            <input type="file" id="icon" name="icon" class="form-control">
        </div>

        <div class="form-groupcol-lg-12">
            {{ Form::label('sequence_number', __('Sequence Number'), ['class' => 'col-form-label']) }} <span
                class="text-danger"> *</span>
            <input type="text" id="sequence_number" name="sequence_number" class="form-control" id="sequence_number"
                required value="{{ $data->sequence_number }}">
        </div>

        <div class="form-groupcol-lg-12">
            {{ Form::label('parent_id', __('Parent Menu'), ['class' => 'col-form-label']) }}
            {!! Form::select('parent_id', $parentMenusList, $data->parent_id, [
                'id' => 'parent_id',
                'class' => 'form-control select2',
            ]) !!}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
