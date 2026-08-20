{{ Form::open(['route' => ['my_visit.update', $data->id], 'method' => 'PUT', 'id' => 'categoriesModel']) }}
<div class="modal-body">
    <div class="row">

        <div class="form-group col-lg-12 col-md-12">
            {{ Form::label('grade_type', __('Visit Type'), ['class' => 'col-form-label']) }} <span class="text-danger">
                *</span>
            <select id="visit_type" name="visit_type" class="form-control" required>
                <option value="">Select Visit Type</option>
                <option value="1" @if ($data->visit_type == 1) selected @endif>Local Visit</option>
                <option value="2" @if ($data->visit_type == 2) selected @endif>Out of City Visit</option>
            </select>
        </div>

        <div class="form-group col-lg-6 tier_type_class d-none">
            {{ Form::label('grade_type', __('Tier type'), ['class' => 'col-form-label']) }} <span class="text-danger">
                *</span>
            <select id="tier_id" name="tier_id" class="form-control" onchange="getTierType(this.value)">
                <option value="">Select tier type</option>

            </select>
        </div>
        <div class="form-group col-lg-6 city_type_class d-none">
            {{ Form::label('city_id', __('City type'), ['class' => 'col-form-label']) }} <span class="text-danger">
                *</span>
            <select id="city_id" name="city_id" class="form-control">
                <option value="">Select city type</option>

            </select>
        </div>

        <div class="form-group col-lg-12">
            {{ Form::label('amount', __('Visit Name'), ['class' => 'col-form-label']) }} <span class="text-danger">
                *</span>
            <input type="text" name="visit_name" value="{{ $data->visit_name }}" class="form-control" required
                placeholder="Please enter visit name">
        </div>

        <div class="form-group col-lg-6">
            {{ Form::label('amount', __('From Date'), ['class' => 'col-form-label']) }} <span class="text-danger">
                *</span>
            <input type="date" name="form_date" class="form-control" value="{{ $data->form_date }}"
                min="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <div class="form-group col-lg-6">
            {{ Form::label('amount', __('To Date'), ['class' => 'col-form-label']) }} <span class="text-danger">
                *</span>
            <input type="date" name="to_date" class="form-control" value="{{ $data->to_date }}"
                min="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <div class="form-group col-lg-12">
            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }} <span
                class="text-danger"> *</span>
            {{ Form::textarea('description', $data->description, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Description'), 'rows' => '3']) }}
        </div>



    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}


<script>
    $(document).ready(function() {

        //autometically call
        var selectvisit = "{{ $data->visit_type }}";

        if (selectvisit == 2) {

            var selecttier = "{{ $data->tier_id }}";

            $('.tier_type_class').removeClass('d-none');
            $('.city_type_class').removeClass('d-none');
            $.ajax({
                type: "GET",
                url: "{{ url('get_visitCitiesData') }}",
                data: {
                    getvisit_data: selectvisit
                },
                cache: true,
                success: function(data) {
                    var select = '';
                    $.each(data, function(key, value) {

                        if (selecttier == value.id) {
                            select = 'selected';
                        } else {
                            select = '';
                        }
                        $('#tier_id').append($("<option " + select + " value=" + value.id +
                            ">" + value.name + "</option>"));
                    });
                }
            });

            //pass to city 

            var selectcity = "{{ $data->city_id }}";
            $.ajax({
                type: "GET",
                url: "{{ url('get_tierCitiesData') }}",
                data: {
                    getTierid: selecttier
                },
                cache: true,
                success: function(data) {

                    var select = '';
                    $.each(data, function(key, value) {

                        if (selectcity == value.id) {
                            select = 'selected';
                        } else {
                            select = '';
                        }
                        $('#city_id').append($("<option " + select + " value=" + value.id +
                            ">" + value.name + "</option>"));
                    });
                }
            });

        }

    });



    function getTierType(getTierType) {
        if (getTierType == '') {
            $("#city_id").prop("selectedIndex", 0);
            $('#city_id').html('');
            $('#city_id').val('');
            $('#city_id').append('<option value="">Select city type</option>');
        } else {
            $('#city_id').html('');
            $('#city_id').append('<option value="">Select city type</option>');
            $.ajax({
                type: "GET",
                url: "{{ url('get_tierCitiesData') }}",
                data: {
                    getTierid: getTierType
                },
                cache: true,
                success: function(data) {
                    $.each(data, function(key, value) {
                        $('#city_id').append($("<option value=" + value.id + ">" + value.name +
                            "</option>"));
                    });
                }
            });
        }
    }

    function getVisitType(getVisitType) {

        if (getVisitType == '' || getVisitType == 1) {
            $("#tier_id").prop("selectedIndex", 0);

            $('#tier_id').html('');
            $('#city_id').html('');

            $('#tier_id').val('');
            $('#city_id').val('');

            $('#city_id').append('<option value="">Select city type</option>');
            $('#tier_id').append('<option value="">Select tier type</option>');

            $('.tier_type_class').addClass('d-none');
            $('.city_type_class').addClass('d-none');

        } else {

            $('.tier_type_class').removeClass('d-none');
            $('.city_type_class').removeClass('d-none');

            $('#tier_id').html('');
            $('#tier_id').append('<option value="">Select tier type</option>');

            $.ajax({
                type: "GET",
                url: "{{ url('get_visitCitiesData') }}",
                data: {
                    getvisit_data: getVisitType
                },
                cache: true,
                success: function(data) {
                    $.each(data, function(key, value) {
                        $('#tier_id').append($("<option value=" + value.id + ">" + value.name +
                            "</option>"));
                    });
                }
            });
        }
    }

    $("#visit_type").change(function() {
        // Get the selected value
        var selectedValue = $(this).val();

        getVisitType(selectedValue);
    });
</script>
