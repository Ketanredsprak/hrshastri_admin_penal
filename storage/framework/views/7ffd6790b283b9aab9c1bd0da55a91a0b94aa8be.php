<?php

$setting = App\Models\Utility::settings();

?>

<?php echo e(Form::open(['route' => ['getpass.update', $data->id], 'method' => 'Put'])); ?>


<div class="modal-body">

     <div class="row">

          <div class="col-md-12">

               <div class="form-group">

                    <?php echo e(Form::label('leave_type_id', __('Type'), ['class' => 'col-form-label'])); ?> <span class="text-danger pl-1">*</span>

                    <select name="leave_type_id" id="leave_type_id" class="form-control select" required>

                         <option value=''>Select Get pass Type</option>

                         <option <?php if($data->name == 1): ?> selected <?php endif; ?> value="1">Personal work</option>

                         <option <?php if($data->name == 2): ?> selected <?php endif; ?> value="2">Office Work</option>

                    </select>

               </div>

          </div>

     </div>



     <div class="row">

          <div class="col-md-4">

               <div class="form-group start_date_calender_date">

                    <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'col-form-label'])); ?> <span class="text-danger pl-1">*</span>
                    <?php echo e(Form::date('start_date', $data->getRawOriginal('date'), [ 'id' => 'start_date_calender','required' => 'required','class' => 'form-control',])); ?>


               </div>

          </div>

          <div class="col-md-4">

               <div class="form-group start_date_calender_date">

                    <?php echo e(Form::label('out_time', __('Out Time'), ['class' => 'col-form-label'])); ?> <span class="text-danger pl-1">*</span>
                    <input type="time" name="out_time" id="out_time" class="form-control"  value="<?php echo e(date("H:i:s",strtotime($data->getRawOriginal('out_time')))); ?>" step="1" required>

               </div>

          </div>

          <div class="col-md-4">

               <div class="form-group start_date_calender_date">
                    <?php echo e(Form::label('in_time', __('In Time'), ['class' => 'col-form-label'])); ?> <span class="text-danger pl-1">*</span>
                    <input type="time" name="in_time" id="in_time" class="form-control"  value="<?php echo e(date("H:i:s",strtotime($data->getRawOriginal('in_time')))); ?>" step="1" required>
               </div>

          </div>

     </div>

     <div class="row">

          <div class="col-md-12">

               <div class="form-group">

                    <?php echo e(Form::label('leave_reason', __('Reason'), ['class' => 'col-form-label'])); ?> <span class="text-danger pl-1">*</span>

                    <?php echo e(Form::textarea('leave_reason', $data->resone, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Reason'), 'rows' => '3'])); ?>


               </div>

          </div>

     </div>

</div>

<div class="modal-footer">

     <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>

     <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">

</div>

<?php echo e(Form::close()); ?>




<script>

</script><?php /**PATH /var/www/html/resources/views/getpass/edit.blade.php ENDPATH**/ ?>