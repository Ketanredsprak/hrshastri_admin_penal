{{-- {{ Form::open(['route' => 'role.update',$data->id 'method' => 'get']) }} --}}
<form action="{{ route('role.update',$data->id)}}" method="POST">
    @csrf
<div class="modal-body">
    <div class="row">
        <div class="form-group">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                {{ Form::text('name', $data->name, ['class' => 'form-control', 'required'=>'required', 'placeholder' => __('Enter Role Name')]) }}
            </div>
            @error('name')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{-- {{ Form::close() }} --}}
</form>
