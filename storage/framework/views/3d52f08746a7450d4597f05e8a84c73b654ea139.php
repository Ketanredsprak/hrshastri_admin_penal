<?php
    $setting = App\Models\Utility::settings();
?>
<?php echo e(Form::open(['url' => 'leave', 'method' => 'post'])); ?>

<div class="modal-body">
    <?php if(\Auth::user()->type != 'employee'): ?>
        
        
        
    <?php endif; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php echo e(Form::label('leave_type_id', __('Leave Type'), ['class' => 'col-form-label'])); ?> <span
                    class="text-danger pl-1">*</span>
                <select name="leave_type_id" id="leave_type_id" class="form-control select">
                    <?php $__currentLoopData = $leavetypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($leave->id); ?>"><?php echo e($leave->title); ?> (<p class="float-right pr-5">
                                <?php echo e($leave->used_leaved); ?>

                            </p>)</option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
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
                    <option value="0.5">Half-Day</option>
                    <option value="1">Full-Day</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group start_date_calender_date">
                <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'col-form-label'])); ?> <span
                    class="text-danger pl-1">*</span>
                <?php echo e(Form::text('start_date', null, ['id' => 'start_date_calender', 'class' => 'form-control d_week current_date start_date_calender', 'autocomplete' => 'off'])); ?>

            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group end_date_calender_date">
                <?php echo e(Form::label('end_date', __('End Date'), ['class' => 'col-form-label'])); ?> <span
                    class="text-danger pl-1">*</span>
                <?php echo e(Form::text('end_date', null, ['class' => 'form-control d_week current_date end_date_calender', 'autocomplete' => 'off'])); ?>

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
    <?php if(isset($setting['is_enabled']) && $setting['is_enabled'] == 'on'): ?>
        <div class="form-group col-md-6">
            <?php echo e(Form::label('synchronize_type', __('Synchroniz in Google Calendar ?'), ['class' => 'form-label'])); ?>

            <div class=" form-switch">
                <input type="checkbox" class="form-check-input mt-2" name="synchronize_type" id="switch-shadow"
                    value="google_calender">
                <label class="form-check-label" for="switch-shadow"></label>
            </div>
        </div>
    <?php endif; ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn  btn-primary">
</div>
<?php echo e(Form::close()); ?>


<script>
    $(document).ready(function() {
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
</script>
<?php /**PATH /var/www/html/resources/views/leave/create.blade.php ENDPATH**/ ?>