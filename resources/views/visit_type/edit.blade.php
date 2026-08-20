{{ Form::model($data, array('route' => array('visit_type.update', $data->id), 'method' => 'PUT')) }}
<div class="modal-body">
    
    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('name', __('Grade Name '),['class'=>'col-form-label']) }} <span class="text-danger"> * </span>
            {{ Form::text('name', $data->name, array('class' => 'form-control','required'=>'required','placeholder' => 'Grade Name' )) }}
        </div>
    </div>             
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Update')}}</button>
</div>
    
    {{ Form::close() }}

