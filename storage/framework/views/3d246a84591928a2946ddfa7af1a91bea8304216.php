<?php echo e(Form::model($resignation, ['route' => ['resignation.update', $resignation->id], 'method' => 'PUT'])); ?>

<div class="modal-body">
    <div class="row">
        <?php if(\Auth::user()->type != 'employee'): ?>
            <div class="form-group col-lg-12">
                <?php echo e(Form::label('employee_id', __('Employee'), ['class' => 'col-form-label'])); ?>

                <?php echo e(Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'required' => 'required'])); ?>

            </div>
        <?php endif; ?>
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('resignation_date', __('Resignation Date'), ['class' => 'col-form-label'])); ?>

            <input type="date" name="resignation_date" value="<?php echo e($resignation->getRawOriginal('resignation_date')); ?>" class='form-control' min="<?php echo e(date('Y-m-d')); ?>" readonly>
            
        </div>
        
        <div class="form-group col-lg-12"> <span class="text-danger"> * </span>
            <?php echo e(Form::label('description', __('Reason'), ['class' => 'col-form-label'])); ?>

            <?php echo e(Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Reason'),'rows'=>'3' , 'required' => 'required'])); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn btn-primary">
</div>

<?php echo e(Form::close()); ?>

<?php /**PATH /var/www/html/resources/views/resignation/edit.blade.php ENDPATH**/ ?>