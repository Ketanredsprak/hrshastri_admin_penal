<?php

$setting = App\Models\Utility::settings();

?>

<?php echo e(Form::open(['url' => 'earlyandlate_leave', 'method' => 'post'])); ?>


<div class="modal-body">

     <?php if(\Auth::user()->type != 'employee'): ?>


     <div class="row staff_option d-none">

          <div class="col-md-12">

               <div class="form-group">

                    <?php echo e(Form::label('employee_id', __('Users'), ['class' => 'col-form-label'])); ?>


                    <?php echo e(Form::select('employee_id', $users, null, ['class' => 'form-control select2', 'id' => 'employee_id', 'placeholder' => __('Select Staff')])); ?>


               </div>

          </div>

     </div>



     <?php endif; ?>

     <div class="row">

          <div class="col-md-12">

               <div class="form-group">

                    <?php echo e(Form::label('leave_type_id', __('Leave Type'), ['class' => 'col-form-label'])); ?> <span class="text-danger pl-1">*</span>

                    <select name="leave_type_id" id="leave_type_id" class="form-control select" required>

                         <option value=''>Select Leave Day</option>

                         <option value="1">Early Leave</option>

                         <option value="2">Late Comming</option>

                    </select>

               </div>

          </div>

     </div>

     

     <div class="row">

          <div class="col-md-4">

               <div class="form-group start_date_calender_date">

                    <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'col-form-label'])); ?> <span class="text-danger pl-1">*</span>
                    <?php echo e(Form::text('start_date', date("Y-m-d"), [ 'id' => 'start_date_calender','required' => 'required','class' => 'form-control d_week current_date start_date_calender', 'autocomplete' => 'off'])); ?>


               </div>

          </div>

          

     </div>

     <div class="row">

          <div class="col-md-12">

               <div class="form-group">

                    <?php echo e(Form::label('leave_reason', __('Reason'), ['class' => 'col-form-label'])); ?> <span class="text-danger pl-1">*</span>

                    <?php echo e(Form::textarea('leave_reason', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Leave Reason'), 'rows' => '3'])); ?>


               </div>

          </div>

     </div>





</div>

<div class="modal-footer">

     <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>

     <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">

</div>

<?php echo e(Form::close()); ?>








<script>
     $(document).ready(function() {


          // $(".in_time").timepicker({

          //      timeFormat: "h:mm p",

          //      interval: 30,

          //      minTime: "06",

          //      maxTime: "23:55pm",

          //      defaultTime: "06",

          //      startTime: "01:00",

          //      dynamic: false,

          //      dropdown: false,

          //      scrollbar: false

          // });

          // $(".out_time").timepicker({

          //      timeFormat: "h:mm p",

          //      interval: 30,

          //      minTime: "06",

          //      maxTime: "23:55pm",

          //      defaultTime: "06",

          //      startTime: "01:00",

          //      dynamic: false,

          //      dropdown: false,

          //      scrollbar: false

          // });





          $('#option_select').on('change', function() {



               if (this.value == 1) {

                    $('.staff_option').removeClass('d-none');

                    $('.employee_option').addClass('d-none');

               } else {

                    $('.employee_option').removeClass('d-none');

                    $('.staff_option').addClass('d-none');

               }

          });



          var now = new Date();

          var month = (now.getMonth() + 1);

          var day = now.getDate();

          if (month < 10) month = "0" + month;

          if (day < 10) day = "0" + day;

          var today = now.getFullYear() + '-' + month + '-' + day;

          $('.current_date').val(today);

          $('#leave_type_day').on('change', function() {

               if (this.value == 0.5) {

                    $('.end_date_calender_date').addClass('d-none')

               } else {

                    $('.end_date_calender_date').removeClass('d-none')

               }

               var start_date_value = $('.start_date_calender').val();

               $('.end_date_calender').val(start_date_value);

          });





     });
</script><?php /**PATH /var/www/html/resources/views/EarlyLateComming/create.blade.php ENDPATH**/ ?>