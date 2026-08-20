{{ Form::model($data, array('route' => array('city_tier.update', $data->id), 'method' => 'PUT')) }}
<div class="modal-body">
    
    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('name', __('Tier Name '),['class'=>'col-form-label']) }}  <span class="text-danger">*</span>
            {{ Form::text('name', $data->name, array('class' => 'form-control','required'=>'required','placeholder' => 'Tier Name' )) }}
        </div>
       
        <div class="form-group">
            {{ Form::label('role', __('Select City'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
            <select name="citys_id[]" class="select2"
                id="citys_id" multiple required>
                @foreach ($citys as $ct)
                    <option @if(in_array($ct->id,$data->citysid)) selected @endif value="{{ $ct->id}}">{{ $ct->name }}</option>
                @endforeach
            </select>
            </div>
        </div>
    </div>             
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Update')}}</button>
</div>
{{ Form::close() }}

