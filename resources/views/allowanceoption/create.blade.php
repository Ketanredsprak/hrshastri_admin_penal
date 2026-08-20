{{ Form::open(['url' => 'allowanceoption', 'method' => 'post']) }}

<div class="modal-body">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="form-group">
            {{ Form::label('days', __('Pay Slab'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
            <div class="form-icon-user">
                <select class="form-control" name="play_slab" id="play_slab" required>
                    <option value="">Select Pay Slab</option>
                    @foreach ($data as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach

                </select>
                <span class="play_slab_error text-danger"></span>
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
                {{ Form::label('short_name', __('Short Name'), ['class' => 'form-label']) }} <span class="text-danger">
                    * </span>
                <div class="form-icon-user">
                    {{ Form::text('short_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Allowance Short Name')]) }}
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

    <div class="row hra_types_hidden d-none">

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Percentage of Payslab'), ['class' => 'form-label']) }} <span
                    class="text-danger"> * </span>
                <div class="form-icon-user">
                    <select class="form-control" name="hra_types" id="hra_types">
                        <option>Select a Allowance</option>

                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row HRA_enter_percentage d-none">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('hra_types_percentage', __('Percentage'), ['class' => 'form-label']) }} <span
                    class="text-danger"> * </span>
                <div class="form-icon-user">
                    {{ Form::number('hra_types_percentage', !empty(old('hra_types_percentage')) ? old('hra_types_percentage') : '0.00', ['class' => 'form-control', 'id' => 'hra_types_percentage', 'placeholder' => __('0 %')]) }}
                </div>
            </div>
        </div>
    </div>

    <div class="row amount_hidden">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Amount'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
                <div class="form-icon-user">

                    {{ Form::number('amount', !empty(old('amount')) ? old('amount') : '0.00', ['class' => 'form-control ', 'id' => 'amount', 'step' => '0.01', 'placeholder' => 'Enter Amount', 'autocomplete' => 'off']) }}
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
                <input type="radio" id="g_male" value="Yes" name="is_display" class="form-check-input" checked>
                <label class="form-check-label " for="g_male">Yes</label>
            </div>
            <div class="custom-control custom-radio ms-2 custom-control-inline">

                <input type="radio" id="g_female" value="No" name="is_display" class="form-check-input">
                <label class="form-check-label " for="g_female">No</label>
            </div>
        </div>
    </div>
    {{-- //depended if 0 --}}
    <div class="form-group is_display_slary_slip">

        <label for="gender" class="form-label">Show if amount = 0</label>
        <div class="d-flex radio-check">
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="g_male" value="1" name="is_display_slary_slip"
                    class="form-check-input">

                <label class="form-check-label " for="g_male">Yes</label>
            </div>
            <div class="custom-control custom-radio ms-2 custom-control-inline">
                <input type="radio" id="g_female" value="0" name="is_display_slary_slip"
                    class="form-check-input" checked>
                <label class="form-check-label " for="g_female">No</label>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
<script>
    $(document).ready(function() {

        $("#amount").keyup(function() {
            if ($('#amount').val() != 0 || $('#amount').val() == '') {
                $('.is_display_slary_slip').addClass('d-none');
            } else {
                if ($("input[name=is_display]:checked").val() == 'Yes') {

                    $('.is_display_slary_slip').removeClass('d-none');
                }
            }
        });

        $('input[name="is_display"]:radio').change(function() {

            if ($("input[name=is_display]:checked").val() == 'Yes') {
                if ($('#amount').val() == 0 || $('#amount').val() == '') {
                    $('.is_display_slary_slip').removeClass('d-none');
                }
            } else {
                $('.is_display_slary_slip').addClass('d-none');
            }
        });


        $('#play_slab').change(function() {
            var playslabData = $(this).val();
            $.ajax({
                type: 'get',
                url: "{{ url('getAllowance') }}" + '/' + playslabData,
                data: {},
                success: function(state) {

                    $('.play_slab_error').html('');
                    $('#hra_types').html('');
                    $('#hra_types').append('<option value="">Select a Allowance</option>');
                    $("#hra_types").append('<option value="0" selected>Basic</option>');
                    $.each(state, function(key, value) {
                        $("#hra_types").append('<option value="' + value.id + '">' +
                            value.name + '</option>');
                    });
                }
            });
        })


        $('#type').change(function() {
            if ($(this).val() == 'percentage') {
                var playslabData = $('#play_slab').val();
                $('#amount').val('');
                if (playslabData != '') {
                    $('#hra_types_percentage').val('');
                    $('#hra_types').val('0');

                    // $('.hra_types_hidden').removeClass('d-none');
                    $('.HRA_enter_percentage').removeClass('d-none');
                    $('.amount_hidden').addClass('d-none');

                } else {
                    $('.play_slab_error').html('Please select play slab');
                }

            } else {

                $('#hra_types_percentage').val('');
                $('#hra_types').val('');

                $('.amount_hidden').removeClass('d-none');
                $('.HRA_enter_percentage').addClass('d-none');
                $('.hra_types_hidden').addClass('d-none');
            }
        })

    });
</script>



{{ Form::close() }}
