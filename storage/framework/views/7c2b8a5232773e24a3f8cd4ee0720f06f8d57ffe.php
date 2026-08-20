<?php $__currentLoopData = $employeesAttendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($attendance['employee_id']); ?></td>
        <td><?php echo e($attendance['name']); ?></td>
        <td><?php echo e($attendance['branch']); ?></td>
        <td><?php echo e($attendance['designation']); ?></td>
        <?php $__currentLoopData = $attendance['status']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <td>
                <?php if($status == 'P'): ?>
                    <i class="badge bg-success p-2 rounded"><?php echo e(__('P')); ?></i>
                <?php elseif($status == 'A'): ?>
                    <i class="badge bg-danger p-2 rounded"><?php echo e(__('A')); ?></i>
                <?php elseif($status == 'L'): ?>
                    <i class="badge bg-info p-2 rounded"><?php echo e(__('L')); ?></i>
                <?php elseif($status == 'HD'): ?>
                    <i class="badge bg-secondary p-2 rounded"><?php echo e(__('HD')); ?></i>
                <?php elseif($status == 'H'): ?>
                    <i class="badge bg-warning p-2 rounded"><?php echo e(__('H')); ?></i>
                <?php elseif($status == 'WO'): ?>
                    <i class="badge bg-dark p-2 rounded"><?php echo e(__('WO')); ?></i>
                <?php endif; ?>
            </td>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH /var/www/html/resources/views/report/attendance_rows.blade.php ENDPATH**/ ?>