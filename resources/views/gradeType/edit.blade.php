
{{ Form::model($data, ['route' => ['grade_type.update', $data->id], 'method' => 'PUT']) }}
<div class="modal-body">

    <div class="row">


        <div class="form-group col-12">
            {{ Form::label('name', __('Grade Type'), ['class' => 'col-form-label']) }} <span class="text-danger">*</span>
            <select name="assign_grade_id" id="assign_grade_id" class="select2">
                <option value="">Please Select Grade</option>
                @foreach ($gradetypes as $row)
                    <option value="{{ $row->id }}" @if ($row->id == $data->assign_grade_id) selected @endif>
                        {{ $row->name }}</option>
                @endforeach

            </select>
        </div>

        <div class="form-group col-12">
            <div class="form-group">
                {{ Form::label('role_id', __('Role '), ['class' => 'form-label']) }} <span class="text-danger">*</span>
                <div class="form-icon-user">
                    <select name="role_id" id="role_id" class="form-control">
                        <option value="">Please Select Role</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->id }}" @if ($data->role_id == $r->id) selected @endif>
                                {{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="form-group col-12">
            {{ Form::label('name', __('Visit Type'), ['class' => 'form-label']) }}
        </div>

        <div class="form-group col-12">
            {{ Form::label('name', __('In City'), ['class' => 'form-label text-danger']) }}
        </div>
        <div class="form-group col-6">
            {{ Form::label('name', __('Two wheeler'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
        </div>
        <div class="form-group col-6">
            <input type="text"name="in_city_two_wheeler" class="form-control" placeholder="Enter Amount"
                value="{{ $data->in_city_two_wheeler }}">
        </div>

        <div class="form-group col-6">
            {{ Form::label('name', __('Four wheeler'), ['class' => 'form-label']) }} <span class="text-danger">*</span>
        </div>
        <div class="form-group col-6">
            <input type="text"name="in_city_four_wheeler" class="form-control" placeholder="Enter Amount"
                value="{{ $data->in_city_four_wheeler }}">
        </div>

        <div class="form-group col-12">
            {{ Form::label('name', __('Out City'), ['class' => 'form-label text-danger']) }}
        </div>

        @php
            $i = 1;
            $max_arr = [];
        @endphp

        @foreach ($cityTierData as $firstkey => $row)
            @php
                $citytiersubdata = App\Models\GradeTierData::where('grade_id', $data->id)
                    ->where('tier_id', $row->id)
                    ->orderBy('id', 'asc')
                    ->get();
            @endphp

            <div class="form-group col-12">
                {{ Form::label('name', $row->name, ['class' => 'form-label text-primary']) }}
            </div>
            @php
                array_push($max_arr, count($citytiersubdata));
            @endphp

            <div class="col-sm-10 text-end"></div>
            <div class="col-sm-2 text-end">
                <a onclick="add_time_slot({{ $i }},'{{ $row->id }}','{{ count($citytiersubdata) }}','{{ count($cityTierData) }}','{{ $firstkey }}');"
                    href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="{{ __('Create grade') }}"><i class="ti ti-plus text-white"></i></a>
            </div>

            <input type="hidden" name='tier_id' value="{{ $row->id }}" />

            @if (!empty($citytiersubdata))
                @foreach ($citytiersubdata as $key => $secoundrow)
                    @if ($secoundrow->tier_id == $row->id)
                        <div class="row expenses_id_{{ $secoundrow->id }}">
                            <div class="col-sm-10">
                                <div class="form-group ">
                                    {{ Form::label('name', __('Select Reimbursement Expenses '), ['class' => 'form-label']) }}
                                    <div class="form-icon-user">
                                        <select
                                            name="tierData[{{ $key }}][{{ $row->id }}][select_expensive]"
                                            class="form-control" id="expenses_id_{{ $secoundrow->id }}">
                                            <option value="">Reimbursement Expenses</option>
                                            @foreach ($expenses as $ex)
                                                <option @if ($secoundrow->expense_id == $ex->id) selected @endif
                                                    value="{{ $ex->id }}">{{ $ex->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 text-right">
                                <a onclick="removetier({{ $secoundrow->id }});" href="#"
                                    class="btn btn-sm btn-danger btn-icon" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="{{ __('Create grade') }}"><i
                                        class="ti ti-minus text-white"></i></a>
                            </div>
                        </div>
                        <div class="row amount_defualt_{{ $secoundrow->id }}">
                            <div class="col-6">
                                <div class="form-group " id="amount_defualt_{{ $key }}">
                                    {{ Form::label('name', __('Amount'), ['class' => 'form-label']) }}
                                    {{ Form::text('tierData[' . $key . '][' . $row->id . '][amount_exp]', $secoundrow->amount, ['class' => 'form-control', 'placeholder' => 'Amount', 'id' => 'defualt_amount_' . $secoundrow->id . '']) }}
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    {!! Form::label('is_actual', __('Amount Type'), ['class' => 'form-label']) !!}
                                    <div class="d-flex radio-check">
                                        <div
                                            class="custom-control custom-radio custom-control-inline is_actual_defualt_{{ $key }}">
                                            <input type="checkbox"
                                                @if ($secoundrow->is_actual == 1) checked value="1" @else value ="2" @endif
                                                name="tierData[{{ $key }}][{{ $row->id }}][is_actual]"
                                                class="form-check-input" onchange='add_checkbox(this);'>
                                            <label class="form-check-label "
                                                for="is_actual">{{ __('Actual') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
            <div class="add_slot mt-3">
                <div class="panel panel-default">
                    <div class="row align-items-center mb-3">
                        <div class="col-sm-12 border-right">
                            <div id="add_experses_div_{{ $i }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php $i++; @endphp
        @endforeach
        {{-- @dd(max($max_arr)); --}}
        <div class="modal-footer">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
        </div>
    </div>
</div>
{{ Form::close() }}
<script>
    
    @if (!empty($max_arr) && count($max_arr) >= 1)
          var z = "{{ (max($max_arr) ?? 0) }}";
     @else
          var z = "0";
     @endif

    function add_checkbox(checkbox) {
        console.log(checkbox);
        if (checkbox.checked) {
            checkbox.value = 1;
        } else {
            checkbox.value = 2;
        }
    }

    function add_time_slot(data, date_tier_id, count, tier_count, tier_number) {
        $("#add_experses_div_" + data + "").append(`<div id="jquery_expenses">
                        <div class="row">
                        <div class="col-sm-10">
                            <div class="form-group ">
                                {{ Form::label('name', __('Reimbursement Expenses'), ['class' => 'form-label']) }}
                                    <div class="form-icon-user">
                                        <select name="tierData[` + z + `][` + date_tier_id +
            `][select_expensive]" class="form-control" id="expenses_id_` + data + `">
                                            <option value="">Reimbursement Expenses</option>
                                            @foreach ($expenses as $ex)
                                                <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                            </div>
                        </div>
                        <div class="col-sm-2 text-right">
                            <a href="#" class="btn btn-sm btn-danger btn-icon remove-expenses-card"><i class="ti ti-minus  text-white"></i></a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group" id="amount` + date_tier_id +
            `">
                                {{ Form::label('name', __('Amount*'), ['class' => 'form-label']) }}
                                {{ Form::text('tierData[`+z+`][` + date_tier_id + `][amount_exp]', '', ['class' => 'form-control', 'placeholder' => 'Amount', 'id' => 'amount_text` + data + `']) }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('is_actual', __('Amount Type'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                <div class="d-flex radio-check">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="checkbox" onchange="add_checkbox(this);" id="is_actual" value="0" name="tierData[` +
            z + `][` + date_tier_id +
            `][is_actual]" class="form-check-input remove-expenses-amount-card" attr_cnt = ` + date_tier_id + `>
                                        <label class="form-check-label " for="is_actual">{{ __('Actual') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`);
        z++;


        $(document).on('click', '.remove-expenses-card', function() {
            $(this).closest('div#jquery_expenses').remove();
        })
    }


    function removetier(id) {


        $.ajax({
            type: "GET",
            url: "{{ url('delete_tierData') }}",
            data: {
                id: id
            },
            cache: true,
            success: function(data) {
                if (data.status == 1) {

                    // $('.is_actual_defualt_'+id).val('');
                    // $('.defualt_amount_'+id).val('');
                    // $('.expenses_id_'+id).val('');
                    $('.expenses_id_' + id).addClass('d-none');
                    $('.amount_defualt_' + id).addClass('d-none');

                    location.reload();
                }
            }
        });
    }
</script>