{{ Form::open(['url' => 'grade_type']) }}
<div class="modal-body">

    <div class="row">

        
        <div class="form-group col-12">
            {{ Form::label('name', __('Grade Type'), ['class' => 'col-form-label']) }}  <span class="text-danger">*</span>
            <select name="assign_grade_id" id="assign_grade_id"  class="select2">
                <option value="">Please Select Grade</option>
                @foreach ($gradetypes as $row)
                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                @endforeach
                
            </select>
        </div>
        <div class="form-group col-12">

            {{ Form::label('role_id', __('Role '), ['class' => 'form-label']) }}  <span class="text-danger">*</span>
            <div class="form-icon-user">
                <select name="role_id" id="role_id"  class="select2">
                    <option value="">Please Select Role</option>
                    @foreach ($roles as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>


       

        <div class="form-group col-12">
            {{ Form::label('name', __('Visit Type'), ['class' => 'form-label']) }} 
        </div>

        <div class="form-group col-12">
            {{ Form::label('name', __('In City'), ['class' => 'form-label text-danger']) }} 
        </div>
        <div class="form-group col-6">
            {{ Form::label('name', __('Two wheeler'), ['class' => 'form-label']) }}  <span class="text-danger">*</span>
        </div>
        <div class="form-group col-6">
            <input type="text"name="in_city_two_wheeler" class="form-control" placeholder="Enter Amount">
        </div>

        <div class="form-group col-6">
            {{ Form::label('name', __('Four wheeler'), ['class' => 'form-label']) }}  <span class="text-danger">*</span>
        </div>
        <div class="form-group col-6">
            <input type="text"name="in_city_four_wheeler" class="form-control" placeholder="Enter Amount">
        </div>

        <div class="form-group col-12">
            {{ Form::label('name', __('Out City'), ['class' => 'form-label text-danger']) }} 
        </div>
        
        {{-- //Teir forech --}}

        @foreach ($cityTierData as $key=>$row)
            
            <div class="form-group col-12">
                {{ Form::label('name', $row->name, ['class' => 'form-label text-primary']) }} 
            </div>

            <div class="col-sm-10 text-end"></div>
            <div class="col-sm-2 text-end">
                <a id="add_expenses_{{$key}}" href="#" expensesDiv="{{$key}}" tierId="{{$row->id}}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
                    data-bs-placement="top" title="{{ __('Create grade') }}"><i class="ti ti-plus text-white"></i></a>
            </div>
            <div class="row expenses_id">
                <div class="col-sm-12">
                    <div class="form-group ">
                        {{ Form::label('name', __('Select Reimbursement Expenses '), ['class' => 'form-label']) }}
                        <div class="form-icon-user">
                            <select name="tierData[{{$key}}][{{$row->id}}][select_expensive]" class="form-control" id="expenses_id_{{$key}}" >
                                <option value="">Reimbursement Expenses</option>
                                @foreach ($expenses as $ex)
                                    <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row amount_defualt_{{$key}}">
                <div class="col-6">
                    <div class="form-group " id="amount_defualt_{{$key}}">
                        {{ Form::label('name', __('Amount'), ['class' => 'form-label']) }}
                        {{ Form::text('tierData['.$key.']['.$row->id.'][amount_exp]', '', ['class' => 'form-control', 'placeholder' => 'Amount', 'id' => 'defualt_amount']) }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        {!! Form::label('is_actual', __('Amount Type'), ['class' => 'form-label']) !!}
                        <div class="d-flex radio-check">
                            <div class="custom-control custom-radio custom-control-inline is_actual_defualt_{{$key}}">

                                <input type="checkbox" value="1" name="tierData[{{ $key }}][{{$row->id}}][is_actual]" class="form-check-input" id="is_actual" onchange='add_checkbox(this);'>
                                <label class="form-check-label " for="is_actual">{{ __('Actual') }}</label>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <hr>
        <div id="add_experses_div_{{$key}}"></div>
    
    @endforeach
        <div class="modal-footer">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn  btn-primary">{{ __('Submit') }}</button>
        </div>
    
    
    <script>
        var i = {{ $citytierCount }};
        
        $(document).ready(function()
        {   
            for(k=0; k<=i; k++)
            {

                $("#add_expenses_"+k+"").click(function(e) {
                    
                    attrbuteclick = $(this).attr('expensesDiv');
                    tierIdclick = $(this).attr('tierId');
                    
                    $("#add_experses_div_"+attrbuteclick+"").append(`<div id="jquery_expenses">
                        <div class="row">
                        <div class="col-sm-10">
                            <div class="form-group ">
                                {{ Form::label('name', __('Reimbursement Expenses'), ['class' => 'form-label']) }}
                                    <div class="form-icon-user">
                                        <select name="tierData[` + i + `][`+tierIdclick+`][select_expensive]" class="form-control" id="expenses_id_`+i+`">
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
                            <div class="form-group" id="amount` + i + `">
                                {{ Form::label('name', __('Amount*'), ['class' => 'form-label']) }}
                                {{ Form::text('tierData[` + i + `][`+tierIdclick+`][amount_exp]', '', ['class' => 'form-control', 'placeholder' => 'Amount', 'id' => 'amount_text` + i + `']) }}
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                {!! Form::label('is_actual', __('Amount Type'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                <div class="d-flex radio-check">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="checkbox" id="is_actual" value="1" name="tierData[` + i +
                        `][`+tierIdclick+`][is_actual]" class="form-check-input remove-expenses-amount-card" attr_cnt = ` +
                        i + `  onchange='add_checkbox(this);' >
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
            })

            };

            // hide a amount text box
            $(document).ready(function() {
                $('#is_actual_defualt').change(function() {
                    if (this.checked) {
                        $('#amount_defualt').prop("readonly", true);
                    }else {
                        $('#amount_defualt').show();
                    }
                });
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

                }else {
                    $('#' + make_id).show();

                }
            });
            
           
        });

      
        function add_checkbox(checkbox){
            console.log(checkbox);
            if(checkbox.checked){
                checkbox.value = 1;
            }else{
                checkbox.value = 2;
            }
        }
    </script>

    {{ Form::close() }}
