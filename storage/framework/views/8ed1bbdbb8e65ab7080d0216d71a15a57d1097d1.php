<?php echo e(Form::open(['url' => 'resignation', 'method' => 'post'])); ?>

<div class="modal-body">
    <div class="row">
        
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('resignation_date', __('Resignation Date'), ['class' => 'col-form-label'])); ?>


            <input type="date" name="resignation_date" class='form-control' min="<?php echo e(date('Y-m-d')); ?>" value="<?php echo e(date('Y-m-d')); ?>" readonly>

        </div>
        
        <div class="form-group col-lg-12"><span class="text-danger"> * </span>
            <?php echo e(Form::label('description', __('Reason'), ['class' => 'col-form-label'])); ?>

            <?php echo e(Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Reason'),'rows'=>'3' ,])); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn btn-primary">
</div>

<?php echo e(Form::close()); ?>


<script>
    $(document).ready(function() {
        var now = new Date();
        var month = (now.getMonth() + 1);
        var day = now.getDate();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var today = now.getFullYear() + '-' + month + '-' + day;
        $('.current_date').val(today);
    });
</script><?php /**PATH /var/www/html/resources/views/resignation/create.blade.php ENDPATH**/ ?>