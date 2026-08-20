<?php echo e(Form::model($leave, ['route' => ['leave.update', $leave->id], 'method' => 'PUT'])); ?>

<div class="modal-body">
    <?php if(\Auth::user()->type != 'employee'): ?>
        <?php echo e(Form::hidden('employee_id', $leave->employee_id)); ?>

        <?php echo e(Form::hidden('redirect', $redirect)); ?>

    <?php endif; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('leave_type_id', __('Leave Type'), ['class' => 'col-form-label'])); ?> <span
                    class="text-danger pl-1">*</span>
                <?php echo e(Form::select('leave_type_id', $leavetypes, null, ['class' => 'form-control select', 'placeholder' => __('Select Leave Type')])); ?>

            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('leave_type_id', __('Leave Day'), ['class' => 'col-form-label'])); ?> <span
                    class="text-danger pl-1">*</span>
                <select name="leave_type_day" id="leave_type_day" class="form-control select">
                    <option value="">Select Leave Day</option>
                    <option <?php if($leave->leave_day == '0.5'): ?> selected <?php endif; ?> value="0.5">Half-Day</option>
                    <option <?php if($leave->leave_day == '1'): ?> selected <?php endif; ?> value="1">Full-Day</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'col-form-label'])); ?> <span
                    class="text-danger pl-1">*</span>
                <?php echo e(Form::text('start_date', null, ['class' => 'form-control d_week', 'autocomplete' => 'off'])); ?>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group end_date_calender_date <?php echo e($leave->leave_day == '0.5' ? 'd-none' : ''); ?>">
                <?php echo e(Form::label('end_date', __('End Date'), ['class' => 'col-form-label'])); ?> <span
                    class="text-danger pl-1">*</span>
                <?php echo e(Form::text('end_date', null, ['class' => 'form-control d_week', 'autocomplete' => 'off'])); ?>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('leave_reason', __('Leave Reason'), ['class' => 'col-form-label'])); ?> <span
                    class="text-danger pl-1">*</span>
                <?php echo e(Form::textarea('leave_reason', null, ['class' => 'form-control', 'placeholder' => __('Leave Reason'), 'rows' => '3'])); ?>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('remark', __('Remark'), ['class' => 'col-form-label'])); ?> <span
                    class="text-danger pl-1">*</span>
                <?php echo e(Form::textarea('remark', null, ['class' => 'form-control', 'placeholder' => __('Leave Remark'), 'rows' => '3'])); ?>

            </div>
        </div>
    </div>
    <?php if(\Auth::user()->type != 'employee'): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <?php echo e(Form::label('status', __('Status'), ['class' => 'col-form-label'])); ?>

                    <select name="status" id="status" class="form-control select2">
                        
                        <option value="Pending" <?php if($leave->status == 'Pending'): ?> selected="" <?php endif; ?>>
                            <?php echo e(__('Pending')); ?>

                        </option>
                        <option value="Approved" <?php if($leave->status == 'Approved'): ?> selected="" <?php endif; ?>>
                            <?php echo e(__('Approve')); ?>

                        </option>
                        <option value="Reject" <?php if($leave->status == 'Reject'): ?> selected="" <?php endif; ?>><?php echo e(__('Reject')); ?>

                        </option>
                    </select>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">

</div>
<?php echo e(Form::close()); ?>


<script>
    $(document).ready(function() {
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
</script>
<?php /**PATH /var/www/html/resources/views/leave/edit.blade.php ENDPATH**/ ?>