{{ Form::model($data, ['route' => ['grade_type.update', $data->id], 'method' => 'PUT']) }}
<div class="modal-body">

    <div class="row">

        <div class="form-group col-12">
            {{ Form::label('assign_grade_id', __('Grade Type Name '), ['class' => 'col-form-label']) }}  <span class="text-danger">*</span>
            <select name="assign_grade_id" id="assign_grade_id"  class="select2">
                <option value="">Please Select Grade</option>
                @foreach ($gradetypes as $row)
                    <option @if($data->assign_grade_id == $row->id) selected @endif value="{{ $row->id }}">{{ $row->name }}</option>
                @endforeach
                
            </select>
        </div>
        <div class="form-group col-12">
            <div class="form-group">
                {{ Form::label('role_id', __('Role *'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    <select name="role_id" id="role_id" class="form-control">
                        <option value="">Please Select Role</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r->name }}"
                                @if ($data->role_id == $r->name) selected @endif>
                                {{ $r->roleData->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('role', __('Commuting Type'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                <select name="commuting_id[]" class="select2" id="commuting_id" multiple >
                    @foreach ($commuteType as $ct)
                        <option @if (in_array($ct->id, $data->commuting_id)) selected @endif value="{{ $ct->id }}">
                            {{ $ct->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group col-12">
            {{ Form::label('visit_type', __('Select Visit Type'), ['class' => 'form-label']) }}  <span class="text-danger">*</span>
            <div class="form-icon-user">
                <select name="visit_type" class="select2" id="visit_type" >
                    <option value="">Select Visit Type</option>
                    <option value="1" @if($data->visit_type == 1) selected @endif>In City</option>
                    <option value="2" @if($data->visit_type == 2) selected @endif>Out City</option>
                </select>
            </div>
        </div>
        

        

        <div class="form-group col-6">
            {{ Form::label('name', __('Commuting Amount '), ['class' => 'col-form-label']) }}  <span class="text-danger">*</span>
            {{ Form::text('commuting_amount', $data->commuting_amount, ['class' => 'form-control', 'placeholder' => 'Commuting Amount']) }}
        </div>
        <div class="form-group col-6 depend_amount">
            {{ Form::label('name', __('Per-KM '), ['class' => 'col-form-label']) }}
                <input type="radio" id="html" name="commuting_amount_status" value="per_km" required @if($data->commuting_amount_status == 'per_km') checked @endif>
            {{ Form::label('name', __('Fixed '), ['class' => 'col-form-label']) }}
                <input type="radio" id="css" name="commuting_amount_status" value="fixed" required @if($data->commuting_amount_status == 'fixed') checked @endif>
        </div>

        <div class="form-group city_tier_id">
            {{ Form::label('city_tier_id', __('Select City Tier'), ['class' => 'form-label']) }}
            <div class="form-icon-user">
                <select name="city_tier_id" class="select2" id="city_tier_id" >
                    <option value="">Select City Tier</option>
                    @foreach ($city_tier as $ct)
                        <option @if ($data->city_tier_id == $ct->id) selected @endif value="{{ $ct->id }}">
                            {{ $ct->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        
        <div class="row expenses_id">
            <div class="col-sm-10">
                <div class="form-group ">
                    {{ Form::label('name', __('Select Reimbursement Expenses'), ['class' => 'form-label']) }}
                    <div class="form-icon-user">
                    </div>
                </div>
            </div>
            <div class="col-sm-2 text-right">
                <a id="add_expenses" href="#" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
                data-bs-placement="top" title="{{ __('Create grade') }}"><i class="ti ti-plus text-white"></i></a>
            </div>
        </div>
        <br></br>
        <div class="expenses_id" id="add_experses_div"></div>
        @php $expenses_array = 0;@endphp
        @if (!empty($data->city_tier_id))
        
        @php $expenses_array = count($data->expenses_data); @endphp
        @foreach ($data->expenses_data as $key => $ex)
        
                <div id="remove_expenses_div expenses_id">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="form-group ">
                                {{ Form::label('name', __('Select Reimbursement Expenses'), ['class' => 'form-label']) }}
                                <div class="form-icon-user">
                                    <select name="expenses_data[{{$key}}][expenses_id]" class="form-control" id="expenses_id"
                                        >
                                        <option value="">Select Reimbursement Expenses</option>
                                            @foreach ($expenses as $exp)
                                                <option @if ($ex['expenses_id'] == $exp->id) selected @endif
                                                    value="{{ $exp->id }}">{{ $exp->name }}</option>
                                            @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="col-sm-2 text-right">
                            <a href="#" class="btn btn-sm btn-danger btn-icon remove_expenses"><i
                                    class="ti ti-minus  text-white" title="{{ __('Remove Expenses') }}"></i></a>
                            </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group ">
                                    @if(!empty($ex['amount']))
                                    @if ($ex['amount'] != null)
                                        {{ Form::label('name', __('Amount '), ['class' => 'form-label']) }}
                                        {{ Form::text('expenses_data[0][amount]', $ex['amount'], ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Amount']) }}
                                    @endif
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    {!! Form::label('is_actual', __('Amount Type'), ['class' => 'form-label']) !!}
                                    <div class="d-flex radio-check">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="checkbox" id="is_actual" value="1"
                                                name="expenses_data[0][is_actual]" class="form-check-input"
                                                @if ($ex['amount'] == null) checked @endif>
                                            <label class="form-check-label "
                                                for="is_actual">{{ __('Actual') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif


        
       
    </div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
</div>
<script>
    var i = "{{ $expenses_array }}";
    
    $(document).ready(function() {

        var visittype = {{ $data->visit_type }};
        if(visittype == 1){
               $('.city_tier_id').addClass('d-none');
               $('.expenses_id').addClass('d-none');
               $('.amount_defualt').addClass('d-none');
               $('#city_tier_id').val(null);

            }else{
                $('#city_tier_id').val(null);
                $('.city_tier_id').removeClass('d-none');
                $('.expenses_id').removeClass('d-none');
                $('.amount_defualt').removeClass('d-none');
            }

        $("#add_expenses").click(function(e) {

            $("#add_experses_div").append(`
                <div id="jquery_expenses">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="form-group ">
                                {{ Form::label('name', __('Select Reimbursement Expenses'), ['class' => 'form-label']) }}
                                <div class="form-icon-user">
                                    <select name="expenses_data[` + i + `][expenses_id]" class="form-control" id="expenses_id">
                                        <option value="">Select Reimbursement Expenses</option>
                                        @foreach ($expenses as $ex)
                                            <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2 text-right">
                            <a href="#" class="btn btn-sm btn-danger btn-icon remove-expenses-card"><i
                                    class="ti ti-minus  text-white"></i></a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group" id="amount` + i + `">
                                {{ Form::label('name', __('Amount '), ['class' => 'form-label']) }}
                                {{ Form::text('expenses_data[` + i + `][amount]', '', ['class' => 'form-control', 'placeholder' => 'Amount', 'id' => 'amount_text` + i + `']) }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('is_actual', __('Amount Type'), ['class' => 'form-label']) !!}
                                <div class="d-flex radio-check">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="checkbox" id="is_actual" value="1" name="expenses_data[` + i +
                        `][is_actual]" class="form-check-input remove-expenses-amount-card" attr_cnt = ` +
                        i + `>
                                        <label class="form-check-label " for="is_actual">{{ __('Actual') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`);
            i++;
        });
        $(document).on('click', '.remove-expenses-card', function() {
            $(this).closest('div#jquery_expenses').remove();
        });

        $(document).on('click', '.remove_expenses', function() {
            $(this).closest('div#remove_expenses_div').remove();
        });



        //hide amount text box
        $(document).on('click', '.remove-expenses-amount-card', function(e) {
            var cnt = $(this).attr('attr_cnt');
            var divid = "amount";
            var input_id = "amount_text";
            var make_id = divid.concat(cnt);
            var amount_text = input_id.concat(cnt);
            if ($(this).prop('checked') == true) {
                // alert('hide');
                $('#' + make_id).hide();
                $('#' + amount_text).val('');

            } else {
                $('#' + make_id).show();

            }
        });


        // hide a amount text box
        $(document).ready(function() {
            $('#is_actual_defualt').change(function() {
                if (this.checked) {
                    $('#amount_defualt').hide();
                    $('#defualt_amount').val('');
                    $('#defualt_amount').val('0');
                    
                    
                } else {
                    $('#amount_defualt').show();
                    
                }
            });
        });


    });
</script>


{{ Form::close() }}
