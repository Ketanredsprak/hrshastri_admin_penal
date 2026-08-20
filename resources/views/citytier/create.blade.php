{{ Form::open(array('url' => 'city_tier')) }}
<div class="modal-body">
    
    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('name', __('Tier Name '),['class'=>'col-form-label']) }}   <span class="text-danger">*</span>
            {{ Form::text('name', '', array('class' => 'form-control','placeholder' => 'Tier Name', 'required' => 'required' )) }} 
        </div>

        <div class="form-group col-12">
           {{ Form::label('name', __('Select City'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                <select name="citys_id[]" class="select2" id="citys_id" multiple required>
                    <option value="">Select City</option>
                    @foreach ($citys as $ct)
                        <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                    @endforeach
                </select>
        </div>

    </div>             
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Create')}}</button>
</div>
{{ Form::close() }}

