{{ Form::open(['url' => 'my_claim', 'method' => 'post', 'id' => 'categoriesModel', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
     <div class="row">

          <div class="form-group col-lg-12 col-md-12">
               <div class="form-check">
                    <input class="form-check-input visit_type" type="radio" name="visit_type" id="adioVisit" checked value="0" required>
                    <label class="form-check-label" for="adioVisit">
                         Visit Claim
                    </label>
               </div>
               <div class="form-check">
                    <input class="form-check-input visit_type" type="radio" name="visit_type" id="ratioOtherVisit" value="1" required>
                    <label class="form-check-label" for="ratioOtherVisit">
                         Other Claim
                    </label>
               </div>
          </div>

          <div class="form-group col-lg-6" id="claim_name_div" style="display: none">
               {{ Form::label('claim_name', __('Claim Name'), ['class' => 'col-form-label']) }}
               <span class="text-danger">*</span>
               {{ Form::text('claim_name', '', ['class' => 'form-control claim_name', 'placeholder' => __('Enter Claim Name'), 'id' => 'claim_name']) }}
          </div>


          <div class="form-group col-lg-6 col-md-6 normal_visit_class">
               {{ Form::label('grade_type', __('My visit '), ['class' => 'col-form-label']) }}<span class="text-danger">
                    *</span>
               <select id="my_visit_id" name="my_visit_id" class="form-control" onchange="getVisitType(this.value)">
                    <option value="">Select visit type</option>
                    @foreach ($myvisit as $row)
                    <option value="{{ $row->id }}">{{ $row->visit_name }}</option>
                    @endforeach
               </select>
          </div>
          <div class="row normal_visit_class" id="expenses_div"></div>

          <div class="form-group col-lg-6 col-md-6 select_commuting_type normal_visit_class">
               {{ Form::label('select_commuting_type', __('Vehicle type'), ['class' => 'col-form-label']) }}<span class="text-danger"> *</span>
               <select id="select_commuting_type" name="commuting_type" class="form-control" onchange="selectCommutingType(this.value)">
                    <option value="">Select vehicle type</option>
                    <option value="1">Two-wheeler</option>
                    <option value="2">Four-wheeler </option>
               </select>
          </div>
          <input type="hidden" name="twowheeleramount" id="twowheeleramount" class="normal_visit_class">
          <input type="hidden" name="fourwheeleramount" id="fourwheeleramount" class="normal_visit_class">

          <div class="form-group col-lg-6 kilomiter normal_visit_class">
               {{ Form::label('amount', __('kilometre'), ['class' => 'col-form-label']) }} <span class="text-danger">
                    *</span>
               <input type="number" name="kilomiter" id="amountinput" class="form-control" placeholder="Enter kilometre" value="0">
          </div>

          <div class="form-group col-lg-6 misc">
               {{ Form::label('amount', __('Misc'), ['class' => 'col-form-label']) }}
               <input type="number" name="misc" id="misc_amount" class="form-control" placeholder="Enter misc" value="0">
          </div>

          <div class="add_expense_dive normal_visit_class"></div>

          <div class="form-group col-lg-6 col-md-6">
               {{ Form::label('total_amount', __('Total amount'), ['class' => 'col-form-label']) }}
               <input type="text" value="0" name="total_amount" id="total_amount" class="form-control" readonly>

               <input type="hidden" value="0" name="fix_total_amount" id="fix_total_amount" class="form-control" readonly>
               <input type="hidden" value="0" name="fix_not_Actual_total_amount" id="fix_not_Actual_total_amount" class="form-control" readonly>
          </div>

          <input type="hidden" name="fix_amount" id="fix_amount" class="form-control normal_visit_class" readonly>

          <div class="form-group col-lg-6 col-md-6">
               {{ Form::label('amount', __('Upload Image'), ['class' => 'col-form-label']) }}
               <input type="file" name="uploadfile" id="uploadfile" class="form-control" accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
            text/plain, application/pdf, image/*">
          </div>

          <div class="form-group col-lg-12">
               {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }} <span class="text-danger"> *</span>
               {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'rows' => '3']) }}
          </div>


     </div>
</div>
<div class="modal-footer">
     <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
     <input type="submit" value="{{ __('Submit') }}" class="btn btn-primary">
</div>

{{ Form::close() }}
<script>
     $(document).ready(function() {

          $("#commonModal").on('keyup', '#amountinput', function() {
               commutingdata = $('#select_commuting_type').val();
               var misc_amount = $('#misc_amount').val() ?? 0;
               var amountinput = $(this).val() ?? 0;
               var twowheeleramount = $('#twowheeleramount').val() ?? 0;
               var fourwheeleramount = $('#fourwheeleramount').val() ?? 0;

               amount(twowheeleramount, fourwheeleramount, amountinput, misc_amount)
          });
          $("#commonModal").on('keyup', '#misc_amount', function() {
               commutingdata = $('#select_commuting_type').val();
               var misc_amount = $(this).val() ?? 0;
               var amountinput = $('#amountinput').val() ?? 0;
               var twowheeleramount = $('#twowheeleramount').val() ?? 0;
               var fourwheeleramount = $('#fourwheeleramount').val() ?? 0;

               amount(twowheeleramount, fourwheeleramount, amountinput, misc_amount)
          });
          $("#commonModal").on('change', '#amountinput', function() {
               commutingdata = $('#select_commuting_type').val();
               var misc_amount = $('#misc_amount').val() ?? 0;
               var amountinput = $(this).val() ?? 0;
               var twowheeleramount = $('#twowheeleramount').val() ?? 0;
               var fourwheeleramount = $('#fourwheeleramount').val() ?? 0;

               amount(twowheeleramount, fourwheeleramount, amountinput, misc_amount)
          });
          $("#commonModal").on('change', '#misc_amount', function() {
               commutingdata = $('#select_commuting_type').val();
               var misc_amount = $(this).val() ?? 0;
               var amountinput = $('#amountinput').val() ?? 0;
               var twowheeleramount = $('#twowheeleramount').val() ?? 0;
               var fourwheeleramount = $('#fourwheeleramount').val() ?? 0;

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

     });

     function amount(twowheeleramount, fourwheeleramount, amountinput, misc_amount) {

          var visit_type = $("input[name='visit_type']:checked").val(); // 0 = normal visit, 1 = other visit

          //    console.log("Inn visit_type", commutingdata);

          twowheeleramount = ((twowheeleramount == "" || twowheeleramount < 0 || !$.isNumeric(twowheeleramount)) ? 0 :
               twowheeleramount);
          fourwheeleramount = ((fourwheeleramount == "" || fourwheeleramount < 0 || !$.isNumeric(fourwheeleramount)) ? 0 :
               fourwheeleramount);
          amountinput = ((amountinput == "" || amountinput < 0 || !$.isNumeric(amountinput)) ? 0 : amountinput);
          misc_amount = ((misc_amount == "" || misc_amount < 0 || !$.isNumeric(misc_amount)) ? 0 : misc_amount);

          console.log("------------------------------------- commutingdata", commutingdata);
          console.log("twowheeleramount", twowheeleramount);
          console.log("fourwheeleramount", fourwheeleramount);
          console.log("amountinput", amountinput);
          console.log("misc_amount", misc_amount);
          console.log("-------------------------------------");

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

          //    console.log("here", visit_type, twowheeleramount, fourwheeleramount, amountinput, misc_amount);
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

     var i = 1;

     function addExpeses(obj) {
          var id = obj.parent().parent().parent();

          var select = obj.parent().parent().find("#expens_name0 option").clone();

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
                            <label for="" class="form-label">Expense amount</label>
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

     function getVisitType(getVisitType) {
          $.ajax({
               type: "GET",
               url: "{{ url('get_getVisitTypeexpense') }}",
               data: {
                    getVisitType: getVisitType
               },
               cache: true,
               success: function(data) {

                    if (data.status == 1) {
                         $('.select_commuting_type').addClass('d-none');
                         $('.kilomiter').addClass('d-none');
                         $('.misc').addClass('d-none');
                         $("#amountinput").attr('required', false);
                         $("#misc_amount").attr('required', false);
                         $('#expenses_div').removeClass('d-none');
                         $('#expenses_div').append(`<div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <div class="form-group expens_name">
                                                                <label for="" class="form-label">Expense Name</label>
                                                                <div class="form-icon-user">
                                                                    <select name="expens_name[]" class="form-control" id="expens_name0">
                                                                        ` + data.options + `
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <div class="form-group expense_amount">
                                                                <label for="" class="form-label">Expense amount</label>
                                                                <input type="text" name="expens_amount[]" id="expens_amount" class="form-control" value="0">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <input type="hidden" name="hidden_expense_is_actual[]" id="hidden_expense_is_actual">
                                                        </div>
                                                        <div class="col-sm-2 text-right">
                                                            <a href="#" class="btn btn-sm btn-primary btn-icon add-expeses" onclick="addExpeses($(this))"><i
                                                                    class="ti ti-plus text-white"></i></a>
                                                        </div>
                                                    </div>
                                                </div>`);
                    } else {
                         $('.select_commuting_type').removeClass('d-none');
                         $('.kilomiter').removeClass('d-none');
                         $('.misc').removeClass('d-none');
                         $("#amountinput").attr('required', true);
                         $("#misc_amount").attr('required', true);
                         $('#expenses_div').html('');
                         $('.add_expense_dive').html('');
                    }

               }
          });

          $('#amountinput').val(0);
          $('#misc_amount').val(0);
          $('#total_amount').val(0);
          $("#select_commuting_type").prop("selectedIndex", 0);
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
                         $('#twowheeleramount').val(response.data?.in_city_two_wheeler ?? 0);
                         $('#fourwheeleramount').val(response.data?.in_city_four_wheeler ?? 0);
                    }
               });
          }
          $('#amountinput').val(0);
          $('#misc_amount').val(0);
          $('#total_amount').val(0);
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
</script>