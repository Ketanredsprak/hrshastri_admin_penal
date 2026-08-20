<?php echo e(Form::open(['url' => 'leave/changeaction', 'method' => 'post'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th><?php echo e(__('Employee')); ?></th>
                    <td><?php echo e(!empty($leave->employees->name) ? $leave->employees->name : ''); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Leave Type')); ?></th>
                    <td><?php echo e(!empty($leave->leaveType->title) ? $leave->leaveType->title : ''); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Leave Day')); ?></th>
                    <td><?php echo e($leave->total_leave_days > '0.5' ? __('Full Day') : __('Half Day')); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Appplied On')); ?></th>
                    <td><?php echo e(\Auth::user()->dateFormat($leave->applied_on)); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Start Date')); ?></th>
                    <td><?php echo e(\Auth::user()->dateFormat($leave->start_date)); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('End Date')); ?></th>
                    <td><?php echo e(\Auth::user()->dateFormat($leave->end_date)); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Total Days')); ?></th>
                    <td><?php echo e($leave->total_leave_days); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Leave Reason')); ?></th>
                    <td><?php echo e(!empty($leave->leave_reason) ? $leave->leave_reason : ''); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Status')); ?></th>
                    <td><?php echo e(!empty($leave->status) ? $leave->status : ''); ?></td>
                </tr>

                <input type="hidden" value="<?php echo e($leave->id); ?>" name="leave_id">
            </table>

            <?php if(!empty($logsData)): ?>

                <h6>Leave Update Logs</h6>
                <table class="table modal-table" id="pc-dt-simple">
                    <thead>
                        <th>Updated By</th>
                        <th>Updated At</th>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $logsData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $logRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($logRow['name']); ?></td>
                                <td><?php echo e($logRow['dateTime']); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endif; ?>

        </div>
    </div>
</div>
<?php if(Auth::user()->type != 'employee'): ?>

    <div class="modal-footer">

        <!-- 0 = pending, 1 = Approved, 2 = Rejected	 -->
        <?php if(in_array($leave->status, ['Pending', 'Reject'])): ?>
            <input type="hidden" value="<?php echo e(__('Approved')); ?>" class="btn btn-success rounded" name="status">

            <input type="submit" value="<?php echo e(__('Approve')); ?>" class="btn btn-success rounded" name="visit_statusData">
        <?php endif; ?>

        <?php if(in_array($leave->status, ['Pending', 'Approved'])): ?>
            <input type="submit" value="<?php echo e(__('Reject')); ?>" class="btn btn-danger rounded" name="status">
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php echo e(Form::close()); ?>

<?php /**PATH /var/www/html/resources/views/leave/action.blade.php ENDPATH**/ ?>