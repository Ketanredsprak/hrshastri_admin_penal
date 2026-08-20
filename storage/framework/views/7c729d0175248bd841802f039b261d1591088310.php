<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Attendance List')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Attendance List')); ?></li>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('action-button'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php if(session('status')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo session('status'); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="row">
        




        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <?php if(\Auth::user()->type != 'employee'): ?>
                                        <th><?php echo e(__('Employee')); ?></th>
                                    <?php endif; ?>
                                    <th><?php echo e(__('Date')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Clock In')); ?></th>
                                    <th><?php echo e(__('Clock Out')); ?></th>
                                    <th><?php echo e(__('Late')); ?></th>
                                    <th><?php echo e(__('Early Leaving')); ?></th>
                                    <th><?php echo e(__('Overtime')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $attendanceEmployee; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <?php if(\Auth::user()->type != 'employee'): ?>
                                            <td><?php echo e(!empty($attendance->employee) ? $attendance->employee->name : ''); ?></td>
                                        <?php endif; ?>
                                        <td><?php echo e(\Auth::user()->dateFormat($attendance->date)); ?></td>
                                        <td><?php echo e($attendance->status); ?></td>

                                        <td><?php echo e($attendance->clock_in != '00:00:00' ? \Auth::user()->dateFormat($attendance->clock_in_date_time) . ' ' . \Auth::user()->timeFormat($attendance->clock_in) : ''); ?>

                                        </td>
                                        <td><?php echo e($attendance->clock_out != '00:00:00' ? \Auth::user()->dateFormat($attendance->clock_out_date_time) . ' ' . \Auth::user()->timeFormat($attendance->clock_out) : ''); ?>

                                        </td>

                                        


                                        <td><?php echo e($attendance->late); ?></td>
                                        <td><?php echo e($attendance->early_leaving); ?></td>
                                        <td><?php echo e($attendance->overtime); ?></td>

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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/attendance/my_attendance.blade.php ENDPATH**/ ?>