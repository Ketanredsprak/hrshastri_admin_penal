{{ Form::open(['url' => 'commission_option', 'method' => 'post']) }}
<div class="modal-body">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="form-group">
            {{ Form::label('days', __('Pay Slab'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
            <div class="form-icon-user">
                <select class="form-control" name="play_slab" id="play_slab" required>
                    <option value="">Select pay slab</option>
                    @foreach ($data as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>


    <div class="row employees_hidden d-none">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Employees'), ['class' => 'form-label']) }} <span class="text-danger"> *
                </span>
                <div class="form-icon-user">
                    <select class="form-control" name="employees_id" id="employees_id">
                        <option>Select a Employee</option>
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
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter commission option Name')]) }}
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
                    {{ Form::text('short_name', null, ['class' => 'form-control', 'placeholder' => __('Enter commission short name')]) }}
                </div>
                @error('short_name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

    <div class="row types_hidden d-none">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Type'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
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

    <div class="row HRA_enter_percentage d-none">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('short_name', __('Percentage'), ['class' => 'form-label']) }} <span class="text-danger">
                    * </span>
                <div class="form-icon-user">
                    {{ Form::number('hra_types_percentage', null, ['class' => 'form-control', 'placeholder' => __('0 %')]) }}
                </div>
            </div>
        </div>
    </div>

    <div class="row hra_types_hidden d-none">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">

                {{ Form::label('name', __('Commision of Payslab'), ['class' => 'form-label']) }} <span
                    class="text-danger"> * </span>
                <div class="form-icon-user">
                    <select class="form-control" name="hra_types" id="hra_types">
                        <option>Select a Commision</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row amount_hidden">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Amount'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
                <div class="form-icon-user">
                    {{ Form::number('amount', null, ['class' => 'form-control ', 'step' => '0.01', 'placeholder' => 'Enter Amount', 'autocomplete' => 'off']) }}
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
    $(document).ready(function() {

        $('#play_slab').change(function() {
            var playslabData = $(this).val();
            $.ajax({
                type: 'get',
                url: '{{ url('getEmployees') }}' + '/' + playslabData,
                data: {},
                success: function(state) {
                    $('.play_slab_error').html('');
                    $('.employees_hidden').removeClass('d-none');
                    $('.types_hidden').removeClass('d-none');
                    $('#employees_id').html('');
                    $('#employees_id').append(
                    '<option value="">Select a Employee</option>');
                    $("#employees_id").append('<option value="0">All</option>');
                    $.each(state, function(key, value) {
                        $("#employees_id").append('<option value="' + value.id +
                            '">' + value.name + '</option>');
                    });
                }
            });

            $.ajax({
                type: 'get',
                url: '{{ url('getCommission') }}' + '/' + playslabData,
                data: {},
                success: function(state) {
                    $('.play_slab_error').html('');
                    $('#hra_types').html('');
                    $('#hra_types').append('<option value="">Select a Allowance</option>');
                    $("#hra_types").append('<option value="0">Basic</option>');
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
                if (playslabData != '') {
                    $('.hra_types_hidden').removeClass('d-none');
                    $('.HRA_enter_percentage').removeClass('d-none');
                    $('.amount_hidden').addClass('d-none');
                } else {
                    $('.play_slab_error').html('Please select play slab');
                }
            } else {
                $('.amount_hidden').removeClass('d-none');
                $('.HRA_enter_percentage').addClass('d-none');
                $('.hra_types_hidden').addClass('d-none');
            }
        })
    });
</script>