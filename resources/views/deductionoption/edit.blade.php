
{{ Form::model($deductionoption, ['route' => ['deductionoption.update', $deductionoption->id],'method' => 'PUT']) }}
<div class="modal-body">


        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('days', __('Pay Slab'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
                <div class="form-icon-user">
                    <select class="form-control" name="play_slab" id="play_slab" required>
                        <option value="">Select  Pay Slab</option>
                        @foreach ($data as $row)
                            <option @if($deductionoption->play_slab_id ==$row->id ) selected @endif value="{{$row->id}}">{{ $row->name }}</option>
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
                    {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Deduction Option Name')]) }}
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
                {{ Form::label('short_name', __('Short Name'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
                <div class="form-icon-user">
                    {{ Form::text('short_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Deduction Short Name')]) }}
                </div>
                @error('short_name')
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
                    {{ Form::label('name', __('Type'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
                    <div class="form-icon-user">
                        <select class="form-control" name="type" id="type" required>
                            @if($deductionoption->type == 'fixed')

                                <option selected value="fixed">Fixed</option>
                                <option value="percentage">Percentage</option>
                            @else
                                <option  value="fixed">Fixed</option>
                                <option selected value="percentage">Percentage</option>
                            @endif
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
                        {{ Form::number('amount', $deductionoption->amount, ['class' => 'form-control ', 'id' => 'amount', 'required' => 'required', 'step' => '0.01','placeholder'=>'Enter Amount','autocomplete'=>'off']) }}
                    </div>
                    @error('name')
                        <span class="invalid-name" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="gender" class="form-label">Display</label>
            <div class="d-flex radio-check">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="g_male" @if($deductionoption->is_display == 'Yes') checked @endif value="Yes" name="is_display" class="form-check-input">
                    <label class="form-check-label " for="g_male">Yes</label>
                </div>
                <div class="custom-control custom-radio ms-2 custom-control-inline">
                    <input type="radio" id="g_female" @if($deductionoption->is_display == 'No') checked @endif value="No" name="is_display" class="form-check-input">
                    <label class="form-check-label " for="g_female">No</label>
                </div>
            </div>
        </div>
        {{-- //depended if 0 --}}
        <div class="form-group is_display_slary_slip d-none">
            <label for="gender" class="form-label">Show if amount = 0</label>
            <div class="d-flex radio-check">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="g_male" @if($deductionoption->is_display_slary_slip == '1') checked @endif value="1" name="is_display_slary_slip" class="form-check-input">
                    <label class="form-check-label " for="g_male">Yes</label>
                </div>
                <div class="custom-control custom-radio ms-2 custom-control-inline">
                    <input type="radio" id="g_female" value="0" @if($deductionoption->is_display_slary_slip == '0') checked @endif name="is_display_slary_slip" class="form-check-input">
                    <label class="form-check-label " for="g_female">No</label>
                </div>
            </div>
        </div>

    </div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}

<script>
    $( document ).ready(function() {

        if($("input[name=is_display_slary_slip]:checked").val() == 1){
            if($('#amount').val() == 0){
                $('.is_display_slary_slip').removeClass('d-none');
            }
        }else{
            if($("input[name=is_display]:checked").val() == 'Yes')
            {
                if($("input[name=is_display_slary_slip]:checked").val() == 0){
                    $('.is_display_slary_slip').removeClass('d-none');
                }
            }
        }
        $("#amount").keyup(function()
        {
            if($('#amount').val() != 0 || $('#amount').val() == '')
            {
               $('.is_display_slary_slip').addClass('d-none');
            }
            else
            {
                if($("input[name=is_display]:checked").val() == 'Yes'){

                    $('.is_display_slary_slip').removeClass('d-none');
                }
            }
        });

        $('input[name="is_display"]:radio').change(function () {
            
            if($("input[name=is_display]:checked").val() == 'Yes')
            {
                if($('#amount').val() == 0 || $('#amount').val() == '' ){
                    $('.is_display_slary_slip').removeClass('d-none');
                }
            }
            else
            {
                $('.is_display_slary_slip').addClass('d-none');
            }
        });

        var play_slab = $('#play_slab').val();
        var selctData = ''
        var state_id = {{ $deductionoption->employees_id }}
        
        $.ajax({
            type:'get',
            url: '{{ url("getEmployees") }}' + '/' + play_slab,
            data:{},
                success:function(state){
                    $('.play_slab_error').html('');
                    $('.employees_hidden').removeClass('d-none');
                    $('#employees_id').html('');
                    if(state_id == 0){
                        $("#employees_id").append('<option selected value="0">All</option>');
                    }else{
                        $('#employees_id').append('<option value="0">All</option>');
                    }
                $.each(state, function (key, value) {
                    if(value.id == state_id){
                        selctData  = 'selected';
                    }else{
                        selctData  = '';
                    }
                    $("#employees_id").append('<option ' +selctData+ ' value="' + value.id + '">' + value.name + '</option>');
                });
            }
        });

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

