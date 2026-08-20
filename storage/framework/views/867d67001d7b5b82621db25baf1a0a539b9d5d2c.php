<?php $__env->startSection('page-title'); ?>
<?php echo e(__('Manage Resignation')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
<li class="breadcrumb-item"><?php echo e(__('Resignation')); ?></li>
<?php $__env->stopSection(); ?>

<?php if(\Auth::user()->type != 'company'): ?>
<?php $__env->startSection('action-button'); ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Resignation')): ?>
<a href="#" data-url="<?php echo e(route('resignation.create')); ?>" data-ajax-popup="true" data-size="lg" data-title="<?php echo e(__('Create New Resignation')); ?>" data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Create')); ?>">
     <i class="ti ti-plus"></i>
</a>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php endif; ?>

<?php $__env->startSection('content'); ?>
<div class="row">

     <div class="col-xl-12">
          <div class="card">
               <div class="card-header card-body table-border-style">
                    
                    <div class="table-responsive">
                         <table class="table" id="pc-dt-simple">
                              <thead>
                                   <tr>
                                        <?php if(\Spatie\Permission\PermissionServiceProvider::bladeMethodWrapper('hasRole', 'company')): ?>
                                        <th><?php echo e(__('Employee Name')); ?></th>
                                        <?php endif; ?>
                                        <th><?php echo e(__('Resignation Date')); ?></th>
                                        <th><?php echo e(__('Last Working Day')); ?></th>
                                        <th><?php echo e(__('Reason')); ?></th>
                                        <th><?php echo e(__('Notes')); ?></th>
                                        <th><?php echo e(__('status')); ?></th>


                                        <?php if(Gate::check('Edit Resignation') || Gate::check('Delete Resignation')): ?>
                                        <th width="200px"><?php echo e(__('Action')); ?></th>
                                        <?php endif; ?>
                                   </tr>
                              </thead>
                              <tbody>


                                   <?php $__currentLoopData = $resignations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resignation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                   <tr>
                                        <?php if(\Spatie\Permission\PermissionServiceProvider::bladeMethodWrapper('hasRole', 'company')): ?>
                                        <td><?php echo e(!empty($resignation->employee()) ? $resignation->employee()->name : ''); ?></td>
                                        <?php endif; ?>
                                        <td><?php echo e(\Auth::user()->dateFormat($resignation->resignation_date)); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($resignation->notice_date)); ?></td>

                                        <td>
                                             
                                             <p title="<?php echo e($resignation->description); ?>">
                                                  <?php echo e(substr($resignation->description, 0, 60) . '..'); ?>

                                             </p>
                                        </td>

                                        <td>
                                             <p title="<?php echo e($resignation->notes); ?>">
                                                  <?php echo e(substr($resignation->notes, 0, 60) . '..'); ?>

                                             </p>
                                        </td>

                                        <td>
                                             <?php if($resignation->status == 0): ?>
                                                  <div class="badge bg-warning p-2 px-3 rounded status-badge5">pending</div>
                                             <?php elseif($resignation->status == 1): ?>
                                                  <div class="badge bg-success p-2 px-3 rounded status-badge5">Approve</div>
                                             <?php elseif($resignation->status == 2): ?>
                                                  <div class="badge bg-danger p-2 px-3 rounded status-badge5">Rejected</div>
                                             <?php else: ?>
                                                  <div class="badge bg-danger p-2 px-3 rounded status-badge5"></div>
                                             <?php endif; ?>

                                        </td>

                                        <?php if(Gate::check('Edit Resignation') || Gate::check('Delete Resignation')): ?>

                                        <td class="Action">
                                             <span>
                                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Resignation')): ?>
                                                  <?php if($resignation->status == 0): ?>
                                                  <div class="action-btn bg-info ms-2">
                                                       <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg" data-url="<?php echo e(URL::to('resignation/' . $resignation->id . '/edit')); ?>" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="<?php echo e(__('Edit Resignation')); ?>" data-bs-original-title="<?php echo e(__('Edit')); ?>">
                                                            <i class="ti ti-pencil text-white"></i>
                                                       </a>
                                                  </div>
                                                  <?php endif; ?>
                                                  <?php endif; ?>

                                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Resignation')): ?>
                                                  <?php if($resignation->status == 0): ?>
                                                  <div class="action-btn bg-danger ms-2">
                                                       <?php echo Form::open([
                                                       'method' => 'DELETE',
                                                       'route' => ['resignation.destroy', $resignation->id],
                                                       'id' => 'delete-form-' . $resignation->id,
                                                       ]); ?>

                                                       <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>
                                                       </form>
                                                  </div>
                                                  <?php endif; ?>
                                                  <?php endif; ?>
                                             </span>
                                        </td>
                                        <?php endif; ?>
                                   </tr>
                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                              </tbody>
                         </table>
                    </div>
               </div>
          </div>
     </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/resignation/index.blade.php ENDPATH**/ ?>