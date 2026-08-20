{{ Form::model($otherdata, ['route' => ['otherpaymentotion.update', $otherdata->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                {{ Form::label('days', __('Pay Slab'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
                <div class="form-icon-user">
                    <select class="form-control" name="play_slab" id="play_slab" required>
                        <option value="">Select Pay Slab</option>
                        @foreach ($data as $row)
                            <option @if ($otherdata->play_slab_id == $row->id) selected @endif value="{{ $row->id }}">
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
                        <select class="form-control" name="employees_id" id="employees_id" required>
                        </select>
                    </div>
                </div>
            </div>
        </div>

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

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="form-group">
                    {{ Form::label('name', __('Type'), ['class' => 'form-label']) }}
                    <div class="form-icon-user">
                        <select class="form-control" name="type" id="type">
                            @if ($otherdata->type == 'fixed')
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

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="form-group">
                    {{ Form::label('name', __('Amount'), ['class' => 'form-label']) }} <span class="text-danger"> *
                    </span>
                    <div class="form-icon-user">
                        {{ Form::number('amount', $otherdata->amount, ['class' => 'form-control ', 'required' => 'required', 'step' => '0.01', 'placeholder' => 'Enter Amount', 'autocomplete' => 'off']) }}
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
        var selct = ''
        var state_id = "{{ $otherdata->employees_id }}";
        $.ajax({
            type: 'get',
            url: '{{ url('getEmployees') }}' + '/' + play_slab,
            data: {},
            success: function(state) {
                $('.play_slab_error').html('');
                $('.employees_hidden').removeClass('d-none');
                $('#employees_id').html('');
                if (state_id == '0') {
                    $("#employees_id").append('<option selected value="0">All</option>');
                } else {
                    $("#employees_id").append('<option selected value="0">All</option>');
                }
                $.each(state, function(key, value) {
                    if (value.id == state_id) {
                        selct = 'selected';
                    } else {
                        selct = '';
                    }
                    $("#employees_id").append('<option ' + selct + ' value="' + value.id +
                        '">' + value.name + '</option>');
                });
            }
        });

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
        })
    });
</script>
