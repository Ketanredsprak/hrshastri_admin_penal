<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Gate pass')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Gate pass')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>
    <?php if(\Auth::user()->type != 'employee'): ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Gate Pass')): ?>
            <a href="#" data-url="<?php echo e(route('getpass.create')); ?>" data-ajax-popup="true"
                data-title="<?php echo e(__('Create Gate pass')); ?>" data-size="lg" data-bs-toggle="tooltip" title=""
                class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Gate pass')); ?>">
                <i class="ti ti-plus"></i>
            </a>
        <?php endif; ?>
    <?php else: ?>
        <a href="#" data-url="<?php echo e(route('getpass.create')); ?>" data-ajax-popup="true"
            data-title="<?php echo e(__('Create Gate pass')); ?>" data-size="lg" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Gate pass')); ?>">
            <i class="ti ti-plus"></i>
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php
            $pendingCount = $getpass->where('status', 0)->count();
            $approvedCount = $getpass->where('status', 1)->count();
            $rejectedCount = $getpass->where('status', 2)->count();
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
                                    <th><?php echo e(__('Created At')); ?></th>
                                    <th><?php echo e(__('Entry Date')); ?></th>
                                    <th><?php echo e(__('Authorized Person Name')); ?></th>
                                    <th><?php echo e(__('Approved / Rejected Date & Time')); ?></th>
                                    <th><?php echo e(__('Type')); ?></th>
                                    <th><?php echo e(__('Out Time')); ?></th>
                                    <th><?php echo e(__('In Time')); ?></th>
                                    <th><?php echo e(__('Reason')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th width="200px"><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <?php $total_leave_data = 0; ?>
                            <tbody>
                                <?php $__currentLoopData = $getpass; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pass): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e(\Auth::user()->dateFormat($pass->getRawOriginal('created_at'))); ?></td>
                                        <td><?php echo e($pass->date); ?></td>
                                        <td><?php echo e($pass->approvedBy->name ?? '-'); ?></td>
                                        <td><?php echo e(!empty($pass->approved_at) ? $pass->approved_at : '-'); ?></td>
                                        <td>
                                            <?php if($pass->name == 1): ?>
                                                <?php echo e(__('Personal work')); ?>

                                            <?php else: ?>
                                                <?php echo e(__('Office Work')); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($pass->out_time); ?></td>
                                        <td><?php echo e($pass->in_time); ?></td>
                                        <td><?php echo e(substr($pass->resone, 0, 80) . '..'); ?></td>
                                        <td>
                                            <?php if($pass->status == 0): ?>
                                                <div class="badge bg-warning p-2 px-3 rounded status-badge5">
                                                    <?php echo e(__('Pending')); ?></div>
                                            <?php elseif($pass->status == 1): ?>
                                                <div class="badge bg-success p-2 px-3 rounded status-badge5">
                                                    <?php echo e(__('Approved')); ?></div>
                                            <?php elseif($pass->status == 2): ?>
                                                <div class="badge bg-danger p-2 px-3 rounded status-badge5">
                                                    <?php echo e(__('Rejected')); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="Action">
                                            <span>
                                                <?php if($pass->status == 0): ?>
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-size="lg"
                                                            data-url="<?php echo e(URL::to('getpass/' . $pass->id . '/edit')); ?>"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                            title="" data-title="<?php echo e(__('Edit Gate Pass')); ?>"
                                                            data-bs-original-title="<?php echo e(__('Edit')); ?>">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>

                                                    <div class="action-btn bg-danger ms-2">
                                                        <?php echo Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['getpass.destroy', $pass->id],
                                                            'id' => 'delete-form-' . $pass->id,
                                                        ]); ?>

                                                        <a href="#"
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        </form>
                                                    </div>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/getpass/index.blade.php ENDPATH**/ ?>