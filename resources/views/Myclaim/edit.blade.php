{{ Form::open(['route' => ['my_claim.update', $data->id], 'method' => 'PUT', 'id' => 'categoriesModel', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">

        {{-- <div class="form-group col-lg-12 col-md-12">
            <div class="form-check">
                <input class="form-check-input visit_type" type="radio" name="visit_type" id="adioVisit" value="0"
                    required <?php //echo $data->visit_type == 0 ? 'checked' : '';
                    ?>>
                <label class="form-check-label" for="adioVisit">
                    Visit Claim
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input visit_type" type="radio" name="visit_type" id="ratioOtherVisit"
                    value="1" required <?php //echo $data->visit_type == 1 ? 'checked' : '';
                    ?>>
                <label class="form-check-label" for="ratioOtherVisit">
                    Other Claim
                </label>
            </div>
        </div> --}}

        <div class="form-group col-lg-12">
            {{ Form::label('visit_type_lable', $data->visit_type == 0 ? 'Visit Claim' : 'Other Claim', ['class' => 'col-form-label']) }}
        </div>

        <div class="form-group col-lg-6" id="claim_name_div" style="display: {{ $data->visit_type == 1 ? '' : 'none' }}">
            {{ Form::label('claim_name', __('Claim Name'), ['class' => 'col-form-label']) }}
            <span class="text-danger">*</span>
            {{ Form::text('claim_name', $data->name, ['class' => 'form-control claim_name', 'placeholder' => __('Enter Claim Name'), 'id' => 'claim_name']) }}
        </div>

        <div class="form-group col-lg-6 col-md-6 normal_visit_class"
            style="display: {{ $data->visit_type == 0 ? '' : 'none' }}">
            {{ Form::label('grade_type', __('My Visit '), ['class' => 'col-form-label']) }}<span
                class="text-danger">*</span>
            <input type="hidden" value="{{ $data->my_visit_id ?? 0 }}" id="my_visit_id" name="my_visit_id"
                class="form-control">
            <input type="text" value="{{ $myvisit->visit_name ?? '' }}" readonly class="form-control">
        </div>

        <div class="row normal_visit_class" id="expenses_div"></div>

        <input type="hidden" name="twowheeleramount" id="twowheeleramount" class="normal_visit_class">
        <input type="hidden" name="fourwheeleramount" id="fourwheeleramount" class="normal_visit_class">

        @php
            $count = 1;
        @endphp
        <div class="form-group col-lg-6 col-md-6 select_commuting_type normal_visit_class"
            style="display: {{ $data->visit_type == 0 && ($data->myvisitData->visit_type ?? 0) == 2 ? '' : 'none' }}">
            {{ Form::label('select_commuting_type', __('Vehicle Type'), ['class' => 'col-form-label']) }}<span
                class="text-danger"> *</span>
            <select id="select_commuting_type" name="commuting_type" class="form-control"
                onchange="selectCommutingType(this.value)">
                <option value="">Select vehicle type</option>
                <option @if ($data->commuting_type == 1) selected @endif value="1">Two-wheeler</option>
                <option @if ($data->commuting_type == 2) selected @endif value="2">Four-wheeler </option>
            </select>
        </div>

        <div class="form-group col-lg-6 kilomiter normal_visit_class"
            style="display: {{ $data->visit_type == 0 ? '' : 'none' }}">
            {{ Form::label('amount', __('kilometre'), ['class' => 'col-form-label']) }} <span class="text-danger">
                *</span>
            <input type="number" name="kilomiter" id="amountinput" class="form-control" placeholder="Enter kilometre"
                value="{{ $data->kilomiter }}">
        </div>


        <div class="form-group col-lg-6 misc">
            {{ Form::label('amount', __('Misc'), ['class' => 'col-form-label']) }}
            <input type="number" name="misc" id="misc_amount" class="form-control" placeholder="Enter misc"
                value="{{ $data->misc }}">
        </div>


        @php
            $count = count(json_decode($data->expens_name));
        @endphp

        <div class="row normal_visit_class" id="expenses_div"
            style="display: {{ $data->visit_type == 0 && ($data->myvisitData->visit_type ?? 0) == 2 ? '' : 'none' }}">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2 text-right">
                        <a href="#" class="btn btn-sm btn-primary btn-icon add-expeses"
                            onclick="addExpeses($(this))"><i class="ti ti-plus text-white"></i></a>
                    </div>
                </div>
                @foreach (json_decode($data->expens_name) as $key => $item)
                    @if ($item != null)
                        <div class="row" id="remove-for-div">
                            <div class="col-sm-4">
                                <div class="form-group expens_name">
                                    <label for="" class="form-label">Expense Name</label>
                                    <div class="form-icon-user">
                                        <select name="expens_name[]" class="form-control"
                                            id="expens_name{{ $key }}">
                                            <option value="">{{ __('Expenses Name') }}</option>
                                            @foreach ($datatier as $value)
                                                <option value="{{ $value->id }}"
                                                    @if ($value->expenseData->name == $item) selected @endif
                                                    data-id="{{ $value->id }}">
                                                    {{ $value->expenseData->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                @php
                                    $amount = json_decode($data->expens_amount);
                                    $is_actual = json_decode($data->hidden_expense_is_actual);
                                @endphp
                                <div class="form-group expense_amount">
                                    <label for="" class="form-label">Expense Amount</label>
                                    <input type="text" name="expens_amount[]" id="expens_amount" class="form-control"
                                        value="{{ $amount[$key] }}">
                                </div>
                            </div>
                            <div class="col-sm-2"></div>
                            <div class="col-sm-2 text-right">
                                <input type="hidden" name="hidden_expense_is_actual[]" id="hidden_expense_is_actual"
                                    value="{{ $is_actual[$key] }}">
                                @if ($key != '0')
                                    <a href="#" class="btn btn-sm btn-danger btn-icon remove-for-expeses"><i
                                            class="ti ti-minus text-white"></i></a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('total_amount', __('Total Amount'), ['class' => 'col-form-label']) }}
            <input type="text" value="{{ $data->total_amount }}" name="total_amount" id="total_amount"
                class="form-control" readonly>

            <input type="hidden" value="0" name="fix_total_amount" id="fix_total_amount" class="form-control"
                readonly>
            <input type="hidden" value="0" name="fix_not_Actual_total_amount" id="fix_not_Actual_total_amount"
                class="form-control" readonly>
        </div>

        <input type="hidden" name="fix_amount" id="fix_amount" class="form-control" readonly>

        @php
            $fileupload = explode('.', $data->fileupload);
        @endphp

        <div class="form-group col-lg-6 col-md-6">
            {{ Form::label('amount', __('Upload Image'), ['class' => 'col-form-label']) }}
            <input type="file" name="uploadfile" id="uploadfile" class="form-control"
                accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
            text/plain, application/pdf, image/*">
        </div>
        <div class="form-group col-lg-6 col-md-6">
            @if (count($fileupload) > 1)
                @if ($fileupload['1'] == 'jpg' || $fileupload['1'] == 'jpeg' || $fileupload['1'] == 'png' || $fileupload['1'] == 'webp')
                    <img src="{{ asset('fileupload/' . $data->fileupload) }}" height="100" width="100">
                @else
                    <a href="{{ route('file.download', $data->id) }}" class="btn btn-info"><i
                            class="ti ti-arrow-down text-white"></i></a>
                @endif
            @else
                No file uploaded
            @endif
        </div>
        <div class="form-group col-lg-12">
            {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }} <span
                class="text-danger"> *</span>
            {{ Form::textarea('description', $data->description, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'rows' => '3']) }}
        </div>


    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>

{{ Form::close() }}
<script>
    var options = '';

    $(document).ready(function() {
        var select_commuting_type = "{{ $data->commuting_type }}";
        if (select_commuting_type != "") {
            selectCommutingType(select_commuting_type);
        }
        options = $('#expens_name0 option').clone();

        $("#commonModal").on('keyup', '#amountinput', function() {
            commutingdata = $('#select_commuting_type').val();
            var misc_amount = $('#misc_amount').val();
            var amountinput = $(this).val();
            var twowheeleramount = $('#twowheeleramount').val();
            var fourwheeleramount = $('#fourwheeleramount').val();

            amount(twowheeleramount, fourwheeleramount, amountinput, misc_amount)
        });
        $("#commonModal").on('keyup', '#misc_amount', function() {
            commutingdata = $('#select_commuting_type').val();
            var misc_amount = $(this).val();
            var amountinput = $('#amountinput').val();
            var twowheeleramount = $('#twowheeleramount').val();
            var fourwheeleramount = $('#fourwheeleramount').val();

            amount(twowheeleramount, fourwheeleramount, amountinput, misc_amount)
        });
        $("#commonModal").on('change', '#amountinput', function() {
            commutingdata = $('#select_commuting_type').val();
            var misc_amount = $('#misc_amount').val();
            var amountinput = $(this).val();
            var twowheeleramount = $('#twowheeleramount').val();
            var fourwheeleramount = $('#fourwheeleramount').val();

            amount(twowheeleramount, fourwheeleramount, amountinput, misc_amount)
        });
        $("#commonModal").on('change', '#misc_amount', function() {
            commutingdata = $('#select_commuting_type').val();
            var misc_amount = $(this).val();
            var amountinput = $('#amountinput').val();
            var twowheeleramount = $('#twowheeleramount').val();
            var fourwheeleramount = $('#fourwheeleramount').val();

            amount(twowheeleramount, fourwheeleramount, amountinput, misc_amount)
        });

        $("#commonModal").on('keyup', 'input[name="expens_amount[]"]', function() {
            total_amount();
        });
        // $(document).on('change', '[name="expens_name[]"]', function() {
        //     var expense_name = $(this).val();
        //     var get_visit_type = $('#my_visit_id').val();

        //     var parent = $(this).parent().parent().parent().parent();
        //     $.ajax({
        //         type: "GET",
        //         url: "{{ route('compare.actual') }}",
        //         data: {
        //             expense_name: expense_name,
        //             get_visit_type: get_visit_type,
        //         },
        //         success: function(data) {
        //             parent.find('.expense_amount').removeClass('d-none');
        //             parent.find('#expens_amount').val(data.grade_tier_data.amount);
        //             parent.find('#hidden_expense_is_actual').val(data.grade_tier_data
        //                 .is_actual);
        //             if (data.grade_tier_data.is_actual == '1') {
        //                 parent.find('#expens_amount').attr('readonly', false);
        //             } else {
        //                 parent.find('#expens_amount').attr('readonly', true);
        //             }
        //             total_amount()
        //         }
        //     });
        // });

        $(document).on('keyup', '[name="expens_amount[]"]', function() {
            var thisAttr = $(this);
            var expense_id = $(this).parent().parent().parent().find('select :selected').data('id');
            var expense_amount = $(this).val();

            var parent = $(this).parent().parent().parent();
            $.ajax({
                type: "GET",
                url: "{{ route('compare.actual') }}",
                data: {
                    expense_id: expense_id,
                    expense_amount: expense_amount,
                },
                success: function(response) {
                    $(thisAttr).parent().find('.unique_error').remove();
                    console.log('ss')
                    if (response.status == '1') {
                        console.log('1')
                        // parent.find('#expens_amount').val(response.grade_tier_data.amount);
                        parent.find('#hidden_expense_is_actual').val(response
                            .grade_tier_data.is_actual);
                    } else {
                        console.log('0')
                        $('<label class="unique_error text-danger">' + response.message +
                                '</label>')
                            .insertAfter(thisAttr);
                    }
                    total_amount()
                }
            });
        });

        $(document).on('click', '.remove-expeses', function() {
            $(this).closest('div#remove-add-div').remove();
            total_amount();
        });
        $(document).on('click', '.remove-for-expeses', function() {
            $(this).closest('div#remove-for-div').remove();
            total_amount();
        });

    });

    function amount(twowheeleramount, fourwheeleramount, amountinput, misc_amount) {


        // var visit_type = $("input[name='visit_type']:checked").val(); // 0 = normal visit, 1 = other visit
        var visit_type = "{{ $data->visit_type }}"; // 0 = normal visit, 1 = other visit

        twowheeleramount = ((twowheeleramount == "" || twowheeleramount < 0 || !$.isNumeric(twowheeleramount)) ? 0 :
            twowheeleramount);
        fourwheeleramount = ((fourwheeleramount == "" || fourwheeleramount < 0 || !$.isNumeric(fourwheeleramount)) ? 0 :
            fourwheeleramount);
        amountinput = ((amountinput == "" || amountinput < 0 || !$.isNumeric(amountinput)) ? 0 : amountinput);
        misc_amount = ((misc_amount == "" || misc_amount < 0 || !$.isNumeric(misc_amount)) ? 0 : misc_amount);

        if (visit_type == 0) {
            var amountData = 0;
            if (commutingdata == 1) {
                amountData = (parseFloat(twowheeleramount) * parseFloat(amountinput)) + parseFloat(misc_amount);
            }
            if (commutingdata == 2) {
                amountData = (parseFloat(fourwheeleramount) * parseFloat(amountinput)) + parseFloat(misc_amount);
            }

            $('#total_amount').val(amountData);
            $('#fix_amount').val(amountData);

        } else {
            $('#total_amount').val(parseFloat(misc_amount));
            $('#fix_amount').val(parseFloat(misc_amount));
        }
    }

    function ajax_local(commutingdata, amountinput, misc_amount) {

        if (commutingdata == '') {
            alert('Please first select commuting type');
            $('#amountinput').val(0);
            $('#misc_amount').val(0);
        } else {
            //ajax call to 
            $.ajax({
                type: "GET",
                url: "{{ url('get_getAmount') }}",
                data: {
                    commutingtype: commutingdata,
                    kilometer: amountinput,
                    misc_amount: misc_amount,
                },
                cache: true,
                success: function(data) {
                    $('#total_amount').val(data);
                    $('#fix_amount').val(data);
                }
            });
        }
    }

    function total_amount() {
        var total = $('input[name="expens_amount[]"]').map(function() {
            return $(this).val();
        }).get();

        var sum = 0;

        $.each(total, function() {
            sum += parseFloat(this);
        });

        $('#total_amount').val(sum);
    }

    var i = "{{ $count }}";

    function addExpeses(obj) {
        var id = obj.parent().parent().parent();
        console.log(id)

        var select = id.find("#expens_name0 option").clone();
        // var select = options;
        console.log(select)
        id.append(`<div class="row" id="remove-add-div">
                    <div class="col-sm-4">
                        <div class="form-group expens_name">
                            <label for="" class="form-label">Expense Name</label>
                            <div class="form-icon-user">
                                <select name="expens_name[]" class="form-control" id="expens_name` + i + `">
                                    <option value="">Expenses</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group expense_amount">
                            <label for="" class="form-label">Expense Amount</label>
                            <input type="text" name="expens_amount[]" id="expens_amount" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <input type="hidden" name="hidden_expense_is_actual[]" id="hidden_expense_is_actual">
                    </div>
                    <div class="col-sm-2 text-right">
                        <a href="#" class="btn btn-sm btn-danger btn-icon remove-expeses"><i
                                class="ti ti-minus text-white"></i></a>
                    </div>
                </div>`);
        $("#expens_name" + i + "").html('');
        $("#expens_name" + i + "").append(select);
        i++;
    }


    function selectCommutingType(commutingtype) {
        if (commutingtype == '') {
            alert('Please first select commuting type');
            $('#amountinput').val(0);
            $('#misc_amount').val(0);
        } else {
            //ajax call to 
            $.ajax({
                type: "GET",
                url: "{{ url('get_getAmount') }}",
                cache: true,
                success: function(response) {
                    $('#twowheeleramount').val(response.data.in_city_two_wheeler);
                    $('#fourwheeleramount').val(response.data.in_city_four_wheeler);
                }
            });
        }
        // $('#amountinput').val(0);
        // $('#misc_amount').val(0);
        // $('#total_amount').val(0);
    }

    $(".visit_type").change(function() {

        var visit_type = $(this).val() ?? 0; // 0 = normal visit, 1 = other visit

        if (visit_type == 1) {
            $("#claim_name_div").show();
            $(".normal_visit_class").hide();
        } else {
            $("#claim_name_div").hide();
            $(".normal_visit_class").show();
        }
    });

    @if (count($datatier) > 0)
        $('.select_commuting_type').addClass('d-none');
        $('.kilomiter').addClass('d-none');
        $('.misc').addClass('d-none');
    @endif
</script>
