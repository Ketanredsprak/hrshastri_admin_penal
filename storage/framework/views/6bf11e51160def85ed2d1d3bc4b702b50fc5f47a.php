<!-- Include Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<!-- Include Bootstrap Timepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">


<?php $__env->startSection('page-title'); ?>
<?php echo e(__('Short Leave')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>

<li class="breadcrumb-item"><?php echo e(__('Short Leave')); ?></li>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('action-button'); ?>
<?php if(\Auth::user()->type != 'employee'): ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Early & Late Comming')): ?>
<a href="#" data-url="<?php echo e(route('earlyandlate_leave.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create Short Leave')); ?>" data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Short Leave')); ?>">

     <i class="ti ti-plus"></i>

</a>
<?php endif; ?>
<?php else: ?>
<a href="#" data-url="<?php echo e(route('earlyandlate_leave.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create Short Leave')); ?>" data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Short Leave')); ?>">

     <i class="ti ti-plus"></i>

</a>
<?php endif; ?>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
<div class="row">

     <?php
     $pendingCount = $leaves->where('status', 0)->count();
     $approvedCount = $leaves->where('status', 1)->count();
     $rejectedCount = $leaves->where('status', 2)->count();
     ?>

     <div class="col-xxl-4 col-md-4 col-sm-6">

          <div class="card">

               <div class="card-body">

                    <div class="row align-items-center justify-content-between">

                         <div class="col-auto mb-3 mb-sm-0">

                              <div class="d-flex align-items-center">

                                   <div class="theme-avtar bg-warning">

                                        <i class="ti ti-circle-minus"></i>

                                        <i class="fa-solid fa-lights-holiday"></i>

                                   </div>

                                   <div class="ms-3">

                                        <small class="text-muted"><?php echo e(__('Total')); ?></small>

                                        <h6 class="m-0"><?php echo e(__('Pending')); ?></h6>

                                   </div>

                              </div>

                         </div>

                         <div class="col-auto text-end">

                              <h4 class="m-0 text-warning"><?php echo e($pendingCount); ?></h4>

                         </div>

                    </div>

               </div>

          </div>

     </div>



     <div class="col-xxl-4 col-md-4 col-sm-6">

          <div class="card">

               <div class="card-body">

                    <div class="row align-items-center justify-content-between">

                         <div class="col-auto mb-3 mb-sm-0">

                              <div class="d-flex align-items-center">

                                   <div class="theme-avtar bg-success">

                                        <i class="ti ti-circle-check"></i>

                                   </div>

                                   <div class="ms-3">

                                        <small class="text-muted"><?php echo e(__('Total')); ?></small>

                                        <h6 class="m-0"><?php echo e(__('Approved')); ?></h6>

                                   </div>

                              </div>

                         </div>

                         <div class="col-auto text-end">

                              <h4 class="m-0 text-success"><?php echo e($approvedCount); ?></h4>

                         </div>

                    </div>

               </div>

          </div>

     </div>





     <div class="col-xxl-4 col-md-4 col-sm-6">

          <div class="card">

               <div class="card-body">

                    <div class="row align-items-center justify-content-between">

                         <div class="col-auto mb-3 mb-sm-0">

                              <div class="d-flex align-items-center">

                                   <div class="theme-avtar bg-danger">

                                        <i class="ti ti-circle-x"></i>

                                   </div>

                                   <div class="ms-3">

                                        <small class="text-muted"><?php echo e(__('Total')); ?></small>

                                        <h6 class="m-0"><?php echo e(__('Rejected')); ?></h6>

                                   </div>

                              </div>

                         </div>

                         <div class="col-auto text-end">

                              <h4 class="m-0 text-danger"><?php echo e($rejectedCount); ?></h4>

                         </div>

                    </div>

               </div>

          </div>

     </div>



     <div class="col-xl-12">

          <div class="card">

               <div class="card-header card-body table-border-style">

                    <div class="table-responsive">

                         <table class="table" id="pc-dt-simple">

                              <thead>

                                   <tr>

                                        <th><?php echo e(__('Employee')); ?></th>

                                        <th><?php echo e(__('Date')); ?></th>

                                        <th><?php echo e(__('title')); ?></th>

                                        <!-- <th><?php echo e(__('In Time')); ?></th>

                                        <th><?php echo e(__('Out Time')); ?></th> -->

                                        <th><?php echo e(__('Reason')); ?></th>

                                        <th><?php echo e(__('Status')); ?></th>



                                        <th width="200px"><?php echo e(__('Action')); ?></th>

                                   </tr>

                              </thead>

                              <?php $total_leave_data = 0; ?>

                              <tbody>

                                   <?php $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                   <tr>

                                        <td>

                                             <?php echo e($leave->employee->name); ?>


                                        </td>

                                        <td>

                                             <?php echo e(\Auth::user()->dateFormat($leave->date)); ?>


                                        </td>

                                        <td>

                                             <?php if($leave->name == 1): ?>
                                             Early Leave
                                             <?php else: ?>
                                             Late coming
                                             <?php endif; ?>

                                        </td>

                                        <!-- <td><?php echo e($leave->in_time); ?></td>

                                        <td><?php echo e($leave->out_time); ?></td> -->

                                        <td><?php echo e(substr($leave->resone, 0, 80) . '..'); ?></td>










                                        <td>

                                             <?php if($leave->status == 0): ?>
                                             <div class="badge bg-warning p-2 px-3 rounded status-badge5">

                                                  Pending</div>
                                             <?php elseif($leave->status == 1): ?>
                                             <div class="badge bg-success p-2 px-3 rounded status-badge5">

                                                  Approved</div>
                                             <?php elseif($leave->status == 2): ?>
                                             <div class="badge bg-danger p-2 px-3 rounded status-badge5">

                                                  Reject</div>
                                             <?php endif; ?>

                                        </td>

                                        <td class="Action">

                                             <span>





                                                  <?php if(\Auth::user()->type != 'employee'): ?>
                                                  <?php if($leave->status == 0): ?>
                                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Early & Late Comming')): ?>
                                                  <div class="action-btn bg-info ms-2">

                                                       <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg" data-url="<?php echo e(URL::to('earlyandlate_leave/' . $leave->id . '/edit')); ?>" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="<?php echo e(__('Edit Short Leave')); ?>" data-bs-original-title="<?php echo e(__('Edit Short Leave')); ?>">

                                                            <i class="ti ti-pencil text-white"></i>

                                                       </a>

                                                  </div>

                                                  <?php endif; ?>

                                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Early & Late Comming')): ?>
                                                  

                                                       <?php echo Form::open([
                                                       'method' => 'DELETE',

                                                       'route' => ['earlyandlate_leave.destroy', $leave->id],

                                                       'id' => 'delete-form-' . $leave->id,
                                                       ]); ?>


                                                            <div class="action-btn bg-danger ms-2">

                                                       <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete Short Leave" aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>

                                                       </div>
                                                  </form>

                                                  <?php endif; ?>
                                                  <?php endif; ?>
                                                  <?php else: ?>
                                                  <?php if($leave->status == 0): ?>
                                                  <div class="action-btn bg-info ms-2">

                                                       <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg" data-url="<?php echo e(URL::to('earlyandlate_leave/' . $leave->id . '/edit')); ?>" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="<?php echo e(__('Edit Short Leave')); ?>" data-bs-original-title="<?php echo e(__('Edit Short Leave')); ?>">

                                                            <i class="ti ti-pencil text-white"></i>

                                                       </a>

                                                  </div>


                                                  <div class="action-btn bg-danger ms-2">

                                                       <?php echo Form::open([
                                                       'method' => 'DELETE',

                                                       'route' => ['earlyandlate_leave.destroy', $leave->id],

                                                       'id' => 'delete-form-' . $leave->id,
                                                       ]); ?>


                                                       <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete Short Leave" aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>

                                                       </form>

                                                  </div>
                                                  <?php endif; ?>
                                                  <?php endif; ?>





                                             </span>

                                        </td>

                                   </tr>
                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                              </tbody>

                         </table>

                    </div>

               </div>

          </div>

     </div>

</div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/EarlyLateComming/index.blade.php ENDPATH**/ ?>