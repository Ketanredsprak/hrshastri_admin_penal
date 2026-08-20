{{ Form::open(['url' => 'otherpaymentotion', 'method' => 'post']) }}
<div class="modal-body">


    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="form-group">
            {{ Form::label('days', __('Pay Slab'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
            <div class="form-icon-user">
                <select class="form-control" name="play_slab" id="play_slab" required>
                    <option value="">Select Pay Slab</option>
                    @foreach ($data as $row)
                        <option value="{{$row->id}}">{{ $row->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row employees_hidden d-none">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Employees'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
                <div class="form-icon-user">
                    <select class="form-control" name="employees_id" id="employees_id" required>
                        <option >Select a Employee</option>
                    </select>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
                <div class="form-icon-user">
                    {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Allowance option Name')]) }}
                </div>
                @error('name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    
    

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Type'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    <select class="form-control" name="type" id="type">
                        <option value="fixed">Fixed</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>
                @error('name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Amount'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
                <div class="form-icon-user">

                    {{ Form::number('amount', null, ['class' => 'form-control ', 'required' => 'required', 'step' => '0.01','placeholder'=>'Enter Amount','autocomplete'=>'off']) }}
                </div>
                @error('name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    $( document ).ready(function() {

        $('#play_slab').change(function(){
            var playslabData =  $(this).val();
            $.ajax({
                type: 'get',
                url: '{{ url("getEmployees") }}' + '/' + playslabData,
                data: {},
                success: function(state) {
                    $('.play_slab_error').html('');
                    $('.employees_hidden').removeClass('d-none');
                    $('#employees_id').html('');
                    $('#employees_id').append('<option value="">Select a Employee</option>');
                    $("#employees_id").append('<option value="0">All</option>');
                    $.each(state, function(key, value) {
                        $("#employees_id").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        })
    });
</script>