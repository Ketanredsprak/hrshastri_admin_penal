@extends('layouts.admin')
@section('page-title')
    {{ __('Statutory Deductions') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Statutory Deductions') }}</li>
@endsection

@section('header-section')
    <script>
        $(document).ready(function() {
            $(".numberValidation").on("keypress", function(event) {

                if (!isNumberDecimalKey(this, event)) {
                    event.preventDefault();
                }
            });

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


            function numberValidationWithoutDecimalPoints(el, evt) {

                var charCode = (evt.which) ? evt.which : event.keyCode;
                var number = el.value.split('.');

                if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }

                return true;
            }

            $(".numberValidation").on("paste dragover", function(event) {
                event.preventDefault();
            });


            $('#pt_div').on('keypress paste dragover', '.dynamicField', function(e) {
                // Check if the entered value is a valid number
                var inputValue = $(this).val() + String.fromCharCode(e.which);
                if (!$.isNumeric(inputValue)) {
                    // Prevent the input if it's not a number
                    e.preventDefault();
                }
            });

            //   $("#epf_deductions_allowances").select2({
            //       placeholder: "Select option"
            //   })

        });
    </script>
@endsection

@push('script-page')
    <script src="{{ asset('assets/js/plugins/tinymce/tinymce.min.js') }}"></script>

    <script>
        $('.set_epf').change(function() {
            if ($(this).prop('checked')) {
                $("#epf_div").show();
            } else {
                $("#epf_div").hide();
            }
        });

        $('.set_pt').change(function() {
            if ($(this).prop('checked')) {
                $("#pt_div").show();
            } else {
                $("#pt_div").hide();
            }
        });

        $('.set_esi').change(function() {
            if ($(this).prop('checked')) {
                $("#esi_div").show();
            } else {
                $("#esi_div").hide();
            }
        });
    </script>

    <script>
        // Add row
        function addRow() {
            var newRow = $(".pt_row:first").clone();
            newRow.find("input").val(""); // Clear input values in the new row
            newRow.find("input").removeClass("is-invalid");
            $(".pt_row:last").after(newRow);

            var min = 1;
            var max = 100;


            newRow.find("input").each(function() {

                var randomNumber = Math.floor(Math.random() * (max - min + 1)) + min;
                $(this).attr('id', randomNumber);
                var label = $(this).closest('div').find('label');
                var newForAttribute = randomNumber;
                label.attr('for', newForAttribute);
            });

            //   $(".pt_row:not(:last)").find(".add-row").remove();

            //   
        };

        // Remove row
        function removeRow() {
            if ($(".pt_row").length > 1) {
                $(".pt_row:last").remove();
            } else {}
        };


        // Validate number fields on submit

        function validateEpfFields() {
            var isValid = true;

            if ($('.set_epf:checked').length <= 0) {
                return isValid;
            }

            var epf_number = $("#epf_number").val();
            if (epf_number.trim() == "") {
                $("#epf_number").addClass("is-invalid");
                isValid = false;
            } else {
                $("#epf_number").removeClass("is-invalid");
            }

            //   var epf_limit = $("#epf_limit").val();
            //   if (epf_limit == "" || epf_limit <= 0) {
            //       $("#epf_limit").addClass("is-invalid");
            //       isValid = false;
            //   } else {
            //       $("#epf_limit").removeClass("is-invalid");
            //   }

            //   var employee_contribution_rate = $("#employee_contribution_rate").val();
            //   if (employee_contribution_rate == "") {
            //       $("#employee_contribution_rate").addClass("is-invalid");
            //       isValid = false;

            //       //  var errorElement = $('<span class="error-message text-danger pl-1">This Field is required</span>');
            //       //  $("#employee_contribution_rate").after(errorElement);
            //   } else {
            //       $("#employee_contribution_rate").removeClass("is-invalid");
            //       //  $("#employee_contribution_rate").next('.error-message').remove();;
            //   }

            //   var employer_contribution_rate = $("#employer_contribution_rate").val();
            //   if (employer_contribution_rate == "") {
            //       $("#employer_contribution_rate").addClass("is-invalid");
            //       isValid = false;
            //   } else {
            //       $("#employer_contribution_rate").removeClass("is-invalid");
            //   }

            return isValid;
        }

        function validateProfessionalTaxFields() {
            var isValid = true;

            if ($('.set_pt:checked').length <= 0) {
                return isValid;
            }

            var pt_number = $("#pt_number").val();
            if (pt_number.trim() == "") {
                $("#pt_number").addClass("is-invalid");
                isValid = false;
            } else {
                // Remove the invalid class if the input is valid
                $("#pt_number").removeClass("is-invalid");
            }


            // Loop through each row
            $(".pt_row").each(function() {
                // Validate the first three fields in each row
                var startRange = $(this).find("input[name='salary_start_range[]']").val();
                var endRange = $(this).find("input[name='salary_end_range[]']").val();
                var deductionAmount = $(this).find("input[name='deduction_amount[]']").val();

                // Validate numeric input
                if (!$.isNumeric(startRange)) {
                    // Highlight the invalid inputs
                    $(this).find("input[name='salary_start_range[]']").addClass("is-invalid");
                    isValid = false;
                } else {
                    // Remove the invalid class if the input is valid
                    $(this).find("input[name='salary_start_range[]']").removeClass("is-invalid");
                }

                if (!$.isNumeric(endRange)) {
                    // Highlight the invalid inputs
                    $(this).find("input[name='salary_end_range[]']").addClass("is-invalid");
                    isValid = false;
                } else {
                    // Remove the invalid class if the input is valid
                    $(this).find("input[name='salary_end_range[]']").removeClass("is-invalid");
                }

                if (!$.isNumeric(deductionAmount)) {
                    // Highlight the invalid inputs
                    $(this).find("input[name='deduction_amount[]']").addClass("is-invalid");
                    isValid = false;
                } else {
                    // Remove the invalid class if the input is valid
                    $(this).find("input[name='deduction_amount[]']").removeClass("is-invalid");
                }
            });

            return isValid;
        }

        function validateEsiFields() {
            var isValid = true;

            if ($('.set_esi:checked').length <= 0) {
                return isValid;
            }

            var esi_number = $("#esi_number").val();
            if (esi_number.trim() == "") {
                $("#esi_number").addClass("is-invalid");
                isValid = false;
            } else {
                $("#esi_number").removeClass("is-invalid");
            }

            //   var esi_employee_contribution_rate = $("#esi_employee_contribution_rate").val();
            //   if (esi_employee_contribution_rate == "") {
            //       $("#esi_employee_contribution_rate").addClass("is-invalid");
            //       isValid = false;
            //   } else {
            //       $("#esi_employee_contribution_rate").removeClass("is-invalid");
            //   }

            //   var esi_employer_contribution_rate = $("#esi_employer_contribution_rate").val();
            //   if (esi_employer_contribution_rate == "") {
            //       $("#esi_employer_contribution_rate").addClass("is-invalid");
            //       isValid = false;
            //   } else {
            //       $("#esi_employer_contribution_rate").removeClass("is-invalid");
            //   }

            return isValid;
        }
    </script>
@endpush
@section('content')
    <div class="row">

        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-12">

                    {{-- EPF | Employee Provident Fund --}}
                    <div class="card">
                        <div class="col-md-12">
                            {{ Form::open(['url' => route('statutory-deductions.epf')]) }}
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <h5 class="">
                                            {{ __('EPF | Employee Provident Fund') }}
                                        </h5>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4 text-end">
                                        <div class="col switch-width">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="form-check-input set_epf" name="status"
                                                    data-toggle="switchbutton" data-onstyle="primary" id="status"
                                                    {{ ($statutoryDeductionsEpf->status ?? old('status')) == 1 ? 'checked="checked"' : '' }}>
                                                <label class="custom-control-label form-label" for="status"></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body" id="epf_div"
                                style="display: {{ ($statutoryDeductionsEpf->status ?? 0) == 1 ? '' : 'none' }}">
                                <div class="row">

                                    <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                        {{ Form::label('epf_number', __('EPF Number'), ['class' => 'col-form-label']) }}
                                        <span class="text-danger pl-1">*</span>
                                        {{ Form::text('epf_number', $statutoryDeductionsEpf->epf_number ?? old('epf_number'), ['id' => 'epf_number', 'class' => 'form-control', 'placeholder' => 'EPF Number']) }}
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                        {{ Form::label('epf_limit', __('EPF limit'), ['class' => 'col-form-label']) }}
                                        {{-- <span class="text-danger pl-1">*</span> --}}
                                        {{ Form::text('epf_limit', $statutoryDeductionsEpf->epf_limit ?? '', ['id' => 'epf_limit', 'class' => 'form-control numberValidation text-right', 'placeholder' => 'EPF Limit', 'max' => '100']) }}
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                        {{ Form::label('employee_contribution_rate', __('Employee Contribution Rate (%)'), ['class' => 'col-form-label']) }}
                                        {{-- <span class="text-danger pl-1">*</span> --}}
                                        {{ Form::text('employee_contribution_rate', $statutoryDeductionsEpf->employee_contribution_rate ?? '', ['id' => 'employee_contribution_rate', 'class' => 'form-control numberValidation text-right', 'placeholder' => 'Employee Contribution Rate (%)', 'max' => '100']) }}
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-12 form-group">
                                        {{ Form::label('employer_contribution_rate', __('Employer Contribution Rate (%)'), ['class' => 'col-form-label']) }}
                                        {{-- <span class="text-danger pl-1">*</span> --}}
                                        {{ Form::text('employer_contribution_rate', $statutoryDeductionsEpf->employer_contribution_rate ?? '', ['id' => 'employer_contribution_rate', 'class' => 'form-control numberValidation text-right', 'placeholder' => 'Employer Contribution Rate (%)', 'max' => '100']) }}
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                        <div class="form-check form-switch ">
                                            <input type="checkbox" class="form-check-input" id="override_employee_pf"
                                                name="override_employee_pf"
                                                {{ ($statutoryDeductionsEpf->override_employee_pf ?? 0) == 1 ? 'checked="checked"' : '' }}>

                                            <label class="form-check-label f-w-600 pl-1" for="override_employee_pf">Override
                                                Employee PF contribution rate
                                                at employee level (HR can change “Employee PF” by edit the employee)</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                        <div class="form-check form-switch ">
                                            <input type="checkbox" class="form-check-input" id="is_min_max_salary_limit"
                                                name="is_min_max_salary_limit"
                                                {{ ($statutoryDeductionsEpf->is_min_max_salary_limit ?? 0) == 1 ? 'checked="checked"' : '' }}
                                                value="1">

                                            <label class="form-check-label f-w-600 pl-1" for="is_min_max_salary_limit">Is
                                                there any minimum or minimum salary limit to deduct EPF. If yes, share all
                                                possible scenarios.</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                        <label class="form-check-label f-w-600 pl-1"
                                            for="epf_deductions_allowances_ids">Allowances
                                            Deductions from EPF (Employee Provident Fund)</label>
                                        {{ Form::select('epf_deductions_allowances_ids[]', $allowanceOption, json_decode($statutoryDeductionsEpf->epf_deductions_allowances_ids ?? '') ?? [], ['id' => 'epf_deductions_allowances_ids', 'class' => 'form-control select2', 'multiple']) }}
                                    </div>
                                </div>

                            </div>

                            <div class="row">

                                <div class="card-footer text-end">
                                    <button class="btn-submit btn btn-primary" type="submit"
                                        onclick="return validateEpfFields()">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                    {{-- EPF | Employee Provident Fund --}}

                    {{-- PT Deduction Range | Professional Tax Deduction Range --}}
                    <div class="card">
                        <div class="col-md-12">
                            {{ Form::open(['url' => route('statutory-deductions.professional-tax')]) }}
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <h5 class="">
                                            {{ __('PT | Professional Tax Deduction Range') }}
                                        </h5>

                                        <br>

                                        <label>{{ __('Tax Slabs based on Monthly Gorss salary') }}</label>

                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4 text-end">
                                        <div class="col switch-width">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="form-check-input set_pt" name="status"
                                                    data-toggle="switchbutton" data-onstyle="primary" id="status"
                                                    {{ ($professional_tax_status ?? old('status')) == 1 ? 'checked="checked"' : '' }}>
                                                <label class="custom-control-label form-label" for="status"></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body" id="pt_div"
                                style="display: {{ ($professional_tax_status ?? 0) == 1 ? '' : 'none' }}">

                                <div class="row">

                                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                                        {{ Form::label('pt_number', __('Professional Number'), ['class' => 'col-form-label']) }}
                                        <span class="text-danger pl-1">*</span>
                                        {{ Form::text('pt_number', $professional_tax_number ?? old('pt_number'), ['id' => 'pt_number', 'class' => 'form-control', 'placeholder' => 'Professional Number']) }}
                                    </div>
                                </div>

                                <hr>

                                @if ($statutoryDeductionsPt->count() > 0)
                                    @foreach ($statutoryDeductionsPt as $statutoryDeductionsPtRow)
                                        <div class="row pt_row">

                                            <div class="col-lg-2 col-md-2 col-sm-12 form-group">
                                                {{ Form::label('salary_start_range' . $statutoryDeductionsPtRow->id, __('Start Range'), ['class' => 'col-form-label']) }}
                                                <span class="text-danger pl-1">*</span>
                                                {{ Form::text('salary_start_range[]', $statutoryDeductionsPtRow->salary_start_range ?? old('salary_start_range'), ['id' => 'salary_start_range' . $statutoryDeductionsPtRow->id, 'class' => 'form-control dynamicField text-right', 'placeholder' => 'Start Range']) }}
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-12 form-group">
                                                {{ Form::label('salary_end_range' . $statutoryDeductionsPtRow->id, __('End Range'), ['class' => 'col-form-label']) }}
                                                <span class="text-danger pl-1">*</span>
                                                {{ Form::text('salary_end_range[]', $statutoryDeductionsPtRow->salary_end_range ?? old('salary_end_range'), ['id' => 'salary_end_range' . $statutoryDeductionsPtRow->id, 'class' => 'form-control dynamicField text-right', 'placeholder' => 'End Range']) }}
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-12 form-group">
                                                {{ Form::label('deduction_amount' . $statutoryDeductionsPtRow->id, __('Monthly Tax Amount'), ['class' => 'col-form-label']) }}
                                                <span class="text-danger pl-1">*</span>
                                                {{ Form::text('deduction_amount[]', $statutoryDeductionsPtRow->deduction_amount ?? old('deduction_amount'), ['id' => 'deduction_amount' . $statutoryDeductionsPtRow->id, 'class' => 'form-control dynamicField text-right', 'placeholder' => 'Monthly Tax Amount']) }}
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-12 form-group">
                                                {{ Form::label('work_location' . $statutoryDeductionsPtRow->id, __('Work Location'), ['class' => 'col-form-label']) }}
                                                {{ Form::text('work_location[]', $statutoryDeductionsPtRow->work_location ?? old('work_location'), ['id' => 'work_location' . $statutoryDeductionsPtRow->id, 'class' => 'form-control', 'placeholder' => 'Work Location']) }}
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-12 form-group addMoreActions">
                                                <br><br>
                                                <button title="Additional Slab" class="btn btn-sm btn-primary add-row"
                                                    type="button" onclick="addRow()"><i class="ti ti-plus"></i></button>
                                                <button title="Remove" class="btn btn-sm btn-danger remove-row"
                                                    type="button" onclick="removeRow()"><i
                                                        class="ti ti-trash text-white"></i></button>
                                            </div>

                                        </div>
                                    @endforeach
                                @else
                                    <div class="row pt_row">

                                        <div class="col-lg-2 col-md-2 col-sm-12 form-group">
                                            {{ Form::label('salary_start_range', __('Start Range'), ['class' => 'col-form-label']) }}
                                            <span class="text-danger pl-1">*</span>
                                            {{ Form::text('salary_start_range[]', old('salary_start_range'), ['id' => 'salary_start_range', 'class' => 'form-control dynamicField text-right', 'placeholder' => 'Start Range']) }}
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-12 form-group">
                                            {{ Form::label('salary_end_range', __('End Range'), ['class' => 'col-form-label']) }}
                                            <span class="text-danger pl-1">*</span>
                                            {{ Form::text('salary_end_range[]', old('salary_end_range'), ['id' => 'salary_end_range', 'class' => 'form-control dynamicField text-right', 'placeholder' => 'End Range']) }}
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-12 form-group">
                                            {{ Form::label('deduction_amount', __('Monthly Tax Amount'), ['class' => 'col-form-label']) }}
                                            <span class="text-danger pl-1">*</span>
                                            {{ Form::text('deduction_amount[]', old('deduction_amount'), ['id' => 'deduction_amount', 'class' => 'form-control dynamicField text-right', 'placeholder' => 'Monthly Tax Amount']) }}
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-12 form-group">
                                            {{ Form::label('work_location', __('Work Location'), ['class' => 'col-form-label']) }}
                                            {{ Form::text('work_location[]', old('work_location'), ['id' => 'work_location', 'class' => 'form-control', 'placeholder' => 'Work Location']) }}
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-12 form-group addMoreActions">
                                            <br><br>
                                            <button title="Additional Slab" class="btn btn-sm btn-primary add-row"
                                                type="button" onclick="addRow()"><i class="ti ti-plus"></i></button>
                                            <button title="Remove" class="btn btn-sm btn-danger remove-row"
                                                type="button" onclick="removeRow()"><i
                                                    class="ti ti-trash text-white"></i></button>
                                        </div>

                                    </div>
                                @endif


                            </div>

                            <div class="row">

                                <div class="card-footer text-end">
                                    <button class="btn-submit btn btn-primary" type="submit"
                                        onclick="return validateProfessionalTaxFields()">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                    {{-- PT Deduction Range | Professional Tax Deduction Range --}}



                    {{-- ESI | Employees State Insurance --}}
                    <div class="card">
                        <div class="col-md-12">
                            {{ Form::open(['url' => route('statutory-deductions.esi')]) }}
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8">
                                        <h5 class="">
                                            {{ __('ESI | Employees State Insurance') }}
                                        </h5>
                                        <br>

                                        <label>
                                            {{ __('Note: ESI deductions will be made only if the employee’s monthly salary is less than or equal to ₹21,000. If the employee gets a salary revision which increases their monthly salary above ₹21,000, they would have to continue making ESI contributions till the end of the contribution period in which the salary was revised (April-September or October-March).') }}
                                        </label>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4 text-end">
                                        <div class="col switch-width">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="form-check-input set_esi" name="status"
                                                    data-toggle="switchbutton" data-onstyle="primary" id="status"
                                                    {{ ($employeesStateInsurance->status ?? old('status')) == 1 ? 'checked="checked"' : '' }}>
                                                <label class="custom-control-label form-label" for="status"></label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card-body" id="esi_div"
                                style="display: {{ ($employeesStateInsurance->status ?? 0) == 1 ? '' : 'none' }}">
                                <div class="row">

                                    <div class="col-lg-4 col-md-4 col-sm-12 form-group">
                                        {{ Form::label('esi_number', __('Esi Number'), ['class' => 'col-form-label']) }}
                                        <span class="text-danger pl-1">*</span>
                                        {{ Form::text('esi_number', $employeesStateInsurance->esi_number ?? old('esi_number'), ['id' => 'esi_number', 'class' => 'form-control', 'placeholder' => 'Esi Number']) }}
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12 form-group">
                                        {{ Form::label('esi_employee_contribution_rate', __('Employee Contribution Rate (%)'), ['class' => 'col-form-label']) }}
                                        {{-- <span class="text-danger pl-1">*</span> --}}
                                        {{ Form::text('esi_employee_contribution_rate', $employeesStateInsurance->employee_contribution_rate ?? '', ['id' => 'esi_employee_contribution_rate', 'class' => 'form-control numberValidation text-right', 'placeholder' => 'Employee Contribution Rate (%)', 'max' => '100']) }}
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-12 form-group">
                                        {{ Form::label('esi_employer_contribution_rate', __('Employer Contribution Rate (%)'), ['class' => 'col-form-label']) }}
                                        {{-- <span class="text-danger pl-1">*</span> --}}
                                        {{ Form::text('esi_employer_contribution_rate', $employeesStateInsurance->employer_contribution_rate ?? '', ['id' => 'esi_employer_contribution_rate', 'class' => 'form-control numberValidation text-right', 'placeholder' => 'Employer Contribution Rate (%)', 'max' => '100']) }}
                                    </div>

                                </div>
                            </div>

                            <div class="row">

                                <div class="card-footer text-end">
                                    <button class="btn-submit btn btn-primary" type="submit"
                                        onclick="return validateEsiFields()">
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                    {{-- ESI | Employees State Insurance --}}


                </div>
            @endsection
