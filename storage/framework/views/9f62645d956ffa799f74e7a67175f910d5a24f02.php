<?php
$logo=\App\Models\Utility::get_file('uploads/logo/');

?>
<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Forgot Password')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="row align-items-center text-start">
            <div class="col-xl-6">
                <div class="card-body">
                    <div class="">
                        
                        <h2 class="mb-3 f-w-600"><?php echo e(__('Reset Password')); ?></h2>
                        <?php if(session('status')): ?>
                            <div class="alert alert-primary">
                                <?php echo e(session('status')); ?>

                            </div>
                        <?php endif; ?>

                    </div>
                    
                        <?php echo e(Form::open(array('route'=>'password.update','method'=>'post','id'=>'form_data'))); ?>

                        <input type="hidden" name="token" value="<?php echo e($request->route('token')); ?>">

                        <input type="hidden" name="email" value="<?php echo e($request->email); ?>">

                        <div class="">
                            
                            <div class="form-group mb-3">
                                <label for="password" class="form-label"><?php echo e(__('Password')); ?></label>
                                <input id="password" type="password"
                                    class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password"
                                    value="<?php echo e(old('password')); ?>" required autocomplete="password" autofocus>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="error invalid-password text-danger" role="alert">
                                        <small><?php echo e($message); ?></small>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group mb-3">
                                <label for="password-confirm" class="form-label"><?php echo e(__('Confirm Password')); ?></label>
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" value="<?php echo e(old('password')); ?>" required
                                    autocomplete="password" autofocus>
                                <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-password_confirmation text-danger" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="d-grid">
                                <button type="submit"
                                    class="btn btn-primary btn-submit btn-block mt-2"><?php echo e(__('Reset Password')); ?></button>
                            </div>
                           
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-xl-6 img-card-side">
                <div class="auth-img-content">
                    <img src="<?php echo e(asset('assets/images/auth/img-auth-3.svg')); ?>" alt="" class="img-fluid">
                    <h3 class="text-white mb-4 mt-5">“Attention is the new currency”</h3>
                    <p class="text-white">The more effortless the writing looks, the more effort the writer
                        actually put into the process.</p>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?> 


<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/reset-password.blade.php ENDPATH**/ ?>