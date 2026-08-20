<?php echo e(Form::model($user, ['route' => ['user.update', $user->id], 'method' => 'PUT'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="form-group">
            <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?> <span class="text-danger"> * </span>
            <div class="form-icon-user">
                <?php echo Form::text('name', null, [
                    'class' => 'form-control',
                    'required' => 'required',
                    'placeholder' => 'Enter Name',
                ]); ?>

            </div>
        </div>
        <div class="form-group">
            <?php echo e(Form::label('email', __('Email'), ['class' => 'form-label'])); ?>

            <div class="form-icon-user">
                <?php echo Form::text('email', null, [
                    'class' => 'form-control',
                    'readonly' => 'readonly',
                    'required' => 'required',
                    'placeholder' => 'Enter Email',
                ]); ?>

            </div>
        </div>
        <?php if(\Auth::user()->type == 'super admin'): ?>
            <div class="form-group">
                <?php echo e(Form::label('plan_id', __('Plans'), ['class' => 'form-label'])); ?> <span class="text-danger">*</span>
                <div class="form-icon-user">
                    <select name="plan_id" class="form-control select2" id="plan_id">
                        <option value=""><?php echo e(__('Please select as plan')); ?></option>
                        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($key == $user->plan): ?>
                                <option value="<?php echo e($key); ?>" selected><?php echo e($plan); ?></option>
                            <?php else: ?>
                                <option value="<?php echo e($key); ?>"><?php echo e($plan); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span class="dropdown-wrapper" aria-hidden="true"></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if(\Auth::user()->type != 'super admin'): ?>
            <div class="form-group  col-lg-12 col-md-12">
                <?php echo e(Form::label('role', __('User Role'))); ?> <span class="text-danger"> * </span>
                <?php echo Form::select('role', $roles, $user->roles, ['class' => 'form-control font-style select2']); ?>

                <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-role" role="alert">
                        <strong class="text-danger"><?php echo e($message); ?></strong>
                    </span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        <?php endif; ?>
        <?php if(\Auth::user()->type != 'super admin'): ?>
            <div class="form-group">
                <?php echo e(Form::label('role', __('Assigen Employee'), ['class' => 'form-label'])); ?>

                <div class="form-icon-user">

                    <select name="assign_employees[]" class="select2" id="assign_employees" multiple>
                        <option value="">Select employee</option>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(in_array($row->id, $selectemployees ?? [])): ?>
                                <option selected value="<?php echo e($row->id); ?>"><?php echo e($row->name); ?></option>
                            <?php else: ?>
                                <option value="<?php echo e($row->id); ?>"><?php echo e($row->name); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn  btn-primary">
</div>
<?php echo Form::close(); ?>

<?php /**PATH /var/www/html/resources/views/user/edit.blade.php ENDPATH**/ ?>