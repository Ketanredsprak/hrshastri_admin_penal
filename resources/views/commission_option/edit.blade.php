{{ Form::model($Commission_option, ['route' => ['commission_option.update', $Commission_option->id], 'method' => 'PUT']) }}
<div class="modal-body">

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('days', __('Pay Slab'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
                <div class="form-icon-user">
                    <select class="form-control" name="play_slab" id="play_slab" required>
                        <option value="">Select pay slab</option>
                        @foreach ($data as $row)
                            <option @if ($Commission_option->play_slab_id == $row->id) selected @endif value="{{ $row->id }}">
                                {{ $row->name }}</option>
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
                        </select>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
                <div class="form-icon-user">
                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Allowance option Name')]) }}
                </div>
                @error('name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('short_name', __('Short Name'), ['class' => 'form-label']) }} <span class="text-danger">
                    * </span>
                <div class="form-icon-user">
                    {{ Form::text('short_name', null, ['class' => 'form-control', 'placeholder' => __('Enter commission short Name')]) }}
                </div>
                @error('short_name')
                    <span class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="form-group">
                    {{ Form::label('name', __('Type'), ['class' => 'form-label']) }} <span class="text-danger"> *
                    </span>
                    <div class="form-icon-user">
                        <select class="form-control" name="type" id="type">
                            @if ($Commission_option->type == 'fixed')
                                <option selected value="fixed">Fixed</option>
                                <option value="percentage">Percentage</option>
                            @else
                                <option value="fixed">Fixed</option>
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

        <div class="row HRA_enter_percentage d-none">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="form-group">
                    {{ Form::label('short_name', __('Percentage'), ['class' => 'form-label']) }} <span
                        class="text-danger"> * </span>
                    <div class="form-icon-user">
                        {{ Form::number('hra_types_percentage', $Commission_option->amount, ['class' => 'form-control', 'placeholder' => __('0 %')]) }}
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
                    {{ Form::label('name', __('Amount'), ['class' => 'form-label']) }} <span class="text-danger"> *
                    </span>
                    <div class="form-icon-user">
                        {{ Form::number('amount', $Commission_option->amount, ['class' => 'form-control ', 'required' => 'required', 'step' => '0.01', 'placeholder' => 'Enter Amount', 'autocomplete' => 'off']) }}
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
</div>

<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>

{{ Form::close() }}

<script>
    $(document).ready(function() {

        var play_slab = $('#play_slab').val();

        var selct1 = ''

        var emp_id = "{{ $Commission_option->employees_id }}";

        $.ajax({
            type: 'get',
            url: '{{ url('getEmployees') }}' + '/' + play_slab,
            data: {},
            success: function(state) {
                $('.play_slab_error').html('');
                $('.employees_hidden').removeClass('d-none');
                $('#employees_id').html('');
                if (emp_id == '0') {
                    $("#employees_id").append('<option selected value="0">All</option>');
                } else {
                    $('#employees_id').append('<option value="0">All</option>');
                }
                $.each(state, function(key, value) {
                    if (value.id == emp_id) {
                        selct1 = 'selected';
                    } else {
                        selct1 = '';
                    }
                    $("#employees_id").append('<option ' + selct1 + ' value="' + value.id +
                        '">' + value.name + '</option>');
                });
            }
        });

        var playslabData = $('#play_slab').val();
        var selct = ''
        var state_id = "{{ $Commission_option->commision_types }}";
        $.ajax({
            type: 'get',
            url: '{{ url('getCommission') }}' + '/' + playslabData,
            data: {},
            success: function(state) {
                $('.play_slab_error').html('');
                $('#hra_types').html('');
                $('#hra_types').append('<option value="">Select a Commision</option>');
                if (state_id == '0') {
                    $("#hra_types").append('<option selected value="0">Basic</option>');
                } else {
                    $("#hra_types").append('<option value="0">Basic</option>');
                }
                $.each(state, function(key, value) {
                    if (value.id == state_id) {
                        selct = 'selected';
                    } else {
                        selct = '';
                    }

                    $("#hra_types").append('<option ' + selct + ' value="' + value.id +
                        '">' + value.name + '</option>');
                });
            }
        });

        var type = $('#type').val();
        if (type == 'percentage') {
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
            $('.HRA_enter_percentage').removeClass('');
        }

        $('#play_slab').change(function() {
            var playslabData = $(this).val();
            $.ajax({
                type: 'get',
                url: '{{ url('getEmployees') }}' + '/' + playslabData,
                data: {},
                success: function(state) {
                    $('.play_slab_error').html('');
                    $('.employees_hidden').removeClass('d-none');
                    $('#employees_id').html('');
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
