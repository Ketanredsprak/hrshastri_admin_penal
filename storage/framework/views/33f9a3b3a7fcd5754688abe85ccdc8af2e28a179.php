<?php $__env->startSection('page-title'); ?>

<?php if(\Auth::user()->type == 'super admin'): ?>

<?php echo e(__('Manage Companies')); ?>


<?php else: ?>

<?php echo e(__('Manage Users')); ?>


<?php endif; ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('breadcrumb'); ?>

<li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>

<?php if(\Auth::user()->type == 'super admin'): ?>

<li class="breadcrumb-item"><?php echo e(__('Companies')); ?></li>

<?php else: ?>

<li class="breadcrumb-item"><?php echo e(__('Users')); ?></li>

<?php endif; ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('action-button'); ?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create User')): ?>

<?php if(\Auth::user()->type == 'super admin'): ?>

<a href="#" data-url="<?php echo e(route('user.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create New Company')); ?>" data-size="md" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Create')); ?>">

     <i class="ti ti-plus"></i>

</a>

<?php else: ?>

<a href="#" data-url="<?php echo e(route('user.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create New User')); ?>" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Create')); ?>">

     <i class="ti ti-plus"></i>

</a>

<?php endif; ?>

<?php endif; ?>



<?php $__env->stopSection(); ?>





<?php

$logo=\App\Models\Utility::get_file('uploads/avatar/');

// $profile = asset(Storage::url('uploads/avatar/'));



$profile=\App\Models\Utility::get_file('uploads/avatar/');

?>

<?php $__env->startSection('content'); ?>

<div class="row">

     <div class="col-xl-12">
          <div class="card">

               <div class="card-header card-body table-border-style">



<?php if(\Auth::user()->type == 'super admin'): ?>
                    <div class="table-responsive">
                         <table class="table" id="pc-dt-simple">

                              <thead>
                                   <tr>
                                        <th> <?php echo e(__('SR.NO')); ?></th>
                                        <th> <?php echo e(__('Image')); ?></th>
                                        <th> <?php echo e(__('Name')); ?></th>
                                        <th> <?php echo e(__('Email')); ?></th>
                                        <!-- <th> <?php echo e(__('Role')); ?></th> -->
                                        <th> <?php echo e(__('Current plan name')); ?></th>
                                        <th> <?php echo e(__('Plan Start Date')); ?></th>


                                        <th width="200px"> <?php echo e(__('Plan Expire Date')); ?></th>
                                        <th width="200px"> <?php echo e(__('Upgrade Plan')); ?></th>
                                        <th width="200px"> <?php echo e(__('Action')); ?></th>
                                   </tr>
                              </thead>

                              <tbody>
                                   <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                   <tr>
                                        <td><?php echo e($key+1); ?></td>
                                        <td>
                                             <div class="avatar">

                                                  <a href="<?php echo e(!empty($user->avatar) ? ($profile . $user->avatar) :  $logo."avatar.png"); ?>" target="_blank">

                                                       <img src="<?php echo e(!empty($user->avatar) ? ($profile . $user->avatar) :  $logo."avatar.png"); ?>" class="rounded-circle" style="width: 5%">

                                                  </a>

                                             </div>
                                        </td>

                                        <td><?php echo e($user->name); ?></td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td><?php echo e(!empty($user->currentPlan) ? $user->currentPlan->name : ''); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($user->plan_start_date)); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($user->plan_expire_date)); ?></td>
                                        <!-- <td><?php echo e(\Auth::user()->countUsers()); ?></td>
                                    <td><?php echo e(\Auth::user()->countEmployees()); ?></td> -->
                                        <td>
                                             <div class="col-7 text-end">

                                                  <a href="#" data-url="<?php echo e(route('plan.upgrade', $user->id)); ?>" class="btn btn-sm btn-primary btn-icon" data-ajax-popup="true" data-title="<?php echo e(__('Upgrade Plan')); ?>"><?php echo e(__('Upgrade Plan')); ?>


                                                  </a>

                                             </div>
                                        </td>

                                        <td class="Action">

                                             <div class="action-btn bg-success ms-2">
                                                  <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="<?php echo e(route('mobile_assign_menu',$user->id)); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Mobile Menu')); ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Mobile Menu')); ?>">
                                                       <i class="ti ti-key"></i>
                                                  </a>
                                             </div>

                                             <div class="action-btn bg-warning ms-2">
                                                  <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="<?php echo e(route('user.reset', \Crypt::encrypt($user->id))); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Change Password')); ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Change Password')); ?>">
                                                       <i class="ti ti-key"></i>
                                                  </a>
                                             </div>
                                             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit User')): ?>
                                             <div class="action-btn bg-info ms-2">
                                                  <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="<?php echo e(route('user.edit', $user->id)); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Edit Company')); ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Edit')); ?>">
                                                       <i class="ti ti-pencil text-white"></i>
                                                  </a>
                                             </div>
                                             <?php endif; ?>
                                             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete User')): ?>
                                             <div class="action-btn bg-danger ms-2">
                                                  <?php echo Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id], 'id' => 'delete-form-' . $user->id]); ?>

                                                  <a href="#!" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo e(__('Delete')); ?>">
                                                       <i class="ti ti-trash text-white"></i></a>
                                                  <?php echo Form::close(); ?>

                                             </div>
                                             <?php endif; ?>
                    </div>


                    </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    </table>
               </div>
          </div>
     </div>
</div>




<?php else: ?>

<div class="table-responsive">
     <table class="table" id="pc-dt-simple">

          <thead>
               <tr>
                    <th> <?php echo e(__('SR.NO')); ?></th>
                    <th> <?php echo e(__('Image')); ?></th>
                    <th> <?php echo e(__('Name')); ?></th>
                    <th> <?php echo e(__('Email')); ?></th>
                    <th> <?php echo e(__('Role')); ?></th>
                    <th> <?php echo e(__('Status')); ?></th>
                    <th width="200px"> <?php echo e(__('Action')); ?></th>
               </tr>
          </thead>

          <tbody>
               <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               <tr>
                    <td><?php echo e($key+1); ?></td>
                    <td>
                         <div class="avatar">

                              <a href="<?php echo e(!empty($user->avatar) ? ($profile . $user->avatar) :  $logo."avatar.png"); ?>" target="_blank">

                                   <img src="<?php echo e(!empty($user->avatar) ? ($profile . $user->avatar) :  $logo."avatar.png"); ?>" class="rounded-circle" style="width: 5%">

                              </a>

                         </div>
                    </td>

                    <td><?php echo e($user->name); ?></td>
                    <td><?php echo e($user->email); ?></td>
                    <td>
                         <?php $data = App\Models\RolesManagment::find($user->type); ?>
                         <?php if(!empty($data)): ?>
                         <?php echo e($data->name); ?>

                         <?php else: ?>
                         <?php echo e($user->type); ?>

                         <?php endif; ?>
                    </td>
                    <td>
                         <?php if($user->id != $logged_in_user_id): ?>
                              <?php if($user->login_status == 0): ?>

                              <div class="action-btn bg-danger ms-2">
                                   <?php echo Form::open(['method' => 'get','route' => ['active_user', $user->id], 'id' => 'delete-form-' . $user->id]); ?>

                                   <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Active" aria-label="Active"><i class="ti ti-check  text-white text-white"></i></a>
                                   </form>
                              </div>
                              <?php else: ?>

                              <div class="action-btn bg-success ms-2">
                                   <?php echo Form::open(['method' => 'get','route' => ['inactive_user', $user->id], 'id' => 'delete-form-' . $user->id]); ?>

                                   <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="InActive" aria-label="InActive"><i class="ti ti-check  text-white text-white"></i></a>
                                   </form>
                              </div>
                              <?php endif; ?>
                         <?php endif; ?>
                         
                    </td>
                    <td class="Action">
                         
                         <?php if($user->id != $logged_in_user_id): ?>
                         
                              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit User')): ?>
                                   <div class="action-btn bg-info ms-2">
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center" data-url="<?php echo e(route('user.edit', $user->id)); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Edit User')); ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Edit')); ?>">
                                             <i class="ti ti-pencil text-white"></i>
                                        </a>
                                   </div>
                              <?php endif; ?>

                              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete User')): ?>
                                   <div class="action-btn bg-danger ms-2">
                                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id], 'id' => 'delete-form-' . $user->id]); ?>

                                        <a href="#!" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo e(__('Delete')); ?>">
                                             <i class="ti ti-trash text-white"></i></a>
                                        <?php echo Form::close(); ?>

                                   </div>
                              <?php endif; ?>
                         <?php endif; ?>
                         </div>
                    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
</table>
</div>








<?php endif; ?>

</div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/user/index.blade.php ENDPATH**/ ?>