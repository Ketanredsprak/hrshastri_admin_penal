{{ Form::model($loan, ['route' => ['loan.update', $loan->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">

        <div class="form-group col-md-12">
            {{ Form::label('days', __('Employee'), ['class' => 'form-label']) }} <span class="text-danger"> * </span>
            <div class="form-icon-user">
                <select class="form-control select2" name="employee_id" id="employee_id"
                    {{ $checkLoadStarted ? 'disabled' : '' }}>
                    <option value="">Select Employee</option>

                    @foreach ($employee as $row)
                        <?php
                        $selected = $row->id == $loan->employee_id ? 'selected' : '';
                        ?>
                        <option value="{{ $row->id }}" {{ $selected }}>{{ $row->name }}</option>
                    @endforeach

                </select>
                <span class="play_slab_error text-danger"></span>
            </div>
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('title', __('Title'), ['class' => 'col-form-label']) }} <span class="text-danger"> * </span>
            {{ Form::text('title', $loan->title, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Enter Title']) }}
        </div>

        <div class="form-group col-md-6">
            {{ Form::label('loan_option', __('Loan Options*'), ['class' => 'col-form-label']) }} <span
                class="text-danger"> * </span>
            {{ Form::select('loan_option', $loan_options, $loan->loan_option, ['class' => 'form-control select2', 'required' => 'required']) }}
        </div>

        <div class="form-group col-md-4">
            {{ Form::label('amount', __('Loan Amount'), ['class' => 'col-form-label amount_label']) }} <span
                class="text-danger"> * </span>
            {{ Form::text('amount', $loan->amount, ['class' => 'form-control installments_amount_calculate', 'required' => 'required', 'step' => '0.01', 'placeholder' => 'Enter Amount', 'onpaste' => 'return false;', 'ondrop' => 'return false;', $checkLoadStarted ? 'readonly' : '']) }}
        </div>

        <div class="form-group col-md-4">
            {{ Form::label('installments', __('Loan Installments'), ['class' => 'col-form-label installments_label']) }}
            <span class="text-danger"> * </span>
            {{ Form::text('installments', $loan->loan_installments, ['class' => 'form-control installments_amount_calculate', 'required' => 'required', 'id' => 'installments', 'placeholder' => 'Enter Installments', 'onpaste' => 'return false;', 'ondrop' => 'return false;', 'maxlength' => '2', $checkLoadStarted ? 'readonly' : '']) }}
        </div>

        <div class="form-group col-md-4">
            {{ Form::label('installment_amount_per_month', __('Per Month'), ['class' => 'col-form-label installments_label']) }}
            <span class="text-danger"> * </span>

            @php
                $installment_amount_per_month = 0;
                if ($loan->amount > 0 && $loan->loan_installments) {
                    $installment_amount_per_month = $loan->amount / $loan->loan_installments;
                }
            @endphp
            {{ Form::text('installment_amount_per_month', $installment_amount_per_month, ['class' => 'form-control text-right', 'required' => 'required', 'id' => 'installment_amount_per_month', 'readonly']) }}
        </div>

        <div class="form-group col-md-12">
            {{ Form::label('start_date', __('Loan Start Date'), ['class' => 'col-form-label']) }} <span
                class="text-danger"> * </span>
            {{ Form::date('start_date', $loan->start_date, ['class' => 'form-control', 'required' => 'required', 'autocomplete' => 'off', 'min' => date('Y-m-d'), $checkLoadStarted ? 'disabled' : '']) }}
        </div>

        <div class="form-group">
            {{ Form::label('reason', __('Reason'), ['class' => 'col-form-label']) }} <span class="text-danger"> *
            </span>
            {{ Form::textarea('reason', $loan->reason, ['class' => 'form-control', 'rows' => 3, 'required' => 'required']) }}
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>

{{ Form::close() }}

<script>
    $(".installments_amount_calculate").on("input", function() {
        try {
            var installments = $("#installments").val() ?? 0;
            var amount = $("#amount").val() ?? 0;

            if (installments > 0 && amount > 0) {
                var total = amount / installments;
                $("#installment_amount_per_month").val(parseFloat(total));
            } else {
                $("#installment_amount_per_month").val(0);
            }
        } catch (error) {
            @if (config('app.debug'))
                console.log("Error", error);
            @else
                console.log("Error");
            @endif
        }

    });

    $('#installments').keypress(function(event) {
        if (!numberValidation(this, event)) {
            event.preventDefault();
        }
    });

    $('#amount').keypress(function(event) {
        if (!isNumberDecimalKey(this, event)) {
            event.preventDefault();
        }
    });


    function numberValidation(el, evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        var number = el.value.split('.');
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }


    function isNumberDecimalKey(el, evt) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        var number = el.value.split('.');
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        //just one dot
        if (number.length > 1 && charCode == 46) {
            return false;
        }
        //get the carat position
        var caratPos = getSelectionStart(el);
        var dotPos = el.value.indexOf(".");
        if (caratPos > dotPos && dotPos > -1 && (number[1].length > 1)) {
            return false;
        }
        return true;
    }

    function getSelectionStart(o) {
        if (o.createTextRange) {
            var r = document.selection.createRange().duplicate()
            r.moveEnd('character', o.value.length)
            if (r.text == '') return o.value.length
            return o.value.lastIndexOf(r.text)
        } else return o.selectionStart
    }
</script>
