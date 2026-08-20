<?php echo e(Form::open(['url' => 'getpass/changeactionupdate', 'method' => 'post'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th><?php echo e(__('Employee')); ?></th>
                    <td><?php echo e($data->employee->name); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Type')); ?></th>
                    <td>
                        <?php if(!empty($data->name) == 1): ?>
                            <?php echo e(__('Personal work')); ?>

                        <?php else: ?>
                            <?php echo e(__('Office Work')); ?>

                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo e(__('Created At')); ?></th>
                    <td><?php echo e(\Auth::user()->dateFormat($data->getRawOriginal('created_at'))); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Entry Date')); ?></th>
                    <td><?php echo e($data->date); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Authorized Person Name')); ?></th>
                    <td><?php echo e($data->approvedBy->name ?? '-'); ?></td>
                </tr>
                <tr>
                    <th>
                        <?php if($data->status == 1): ?>
                            <?php echo e(__('Approved Date & Time')); ?>

                        <?php elseif($data->status == 2): ?>
                            <?php echo e(__('Rejected Date & Time')); ?>

                        <?php else: ?>
                            <?php echo e(__('Approved / Rejected Date & Time')); ?>

                        <?php endif; ?>
                    </th>
                    <td><?php echo e(!empty($data->approved_at) ? $data->approved_at : '-'); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('In Time')); ?></th>
                    <td><?php echo e($data->in_time); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Out Time')); ?></th>
                    <td><?php echo e($data->out_time); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Status')); ?></th>
                    <td>
                        <?php if($data->status == 0): ?>
                            <?php echo e(__('Pending')); ?>

                        <?php elseif($data->status == 1): ?>
                            <?php echo e(__('Approved')); ?>

                        <?php elseif($data->status == 2): ?>
                            <?php echo e(__('Rejected')); ?>

                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo e(__('Reason')); ?></th>
                    <td><?php echo e(!empty($data->resone) ? $data->resone : ''); ?></td>
                </tr>
                <input type="hidden" value="<?php echo e($data->id); ?>" name="leave_id">
            </table>
        </div>
    </div>
</div>

<div class="modal-footer">
    <?php if($data->status == 0): ?>
        <input type="hidden" value="<?php echo e(__('Approved')); ?>" class="btn btn-success rounded" name="status">
        <input type="submit" value="<?php echo e(__('Approve')); ?>" class="btn btn-success rounded" name="visit_statusData">
        <input type="submit" value="<?php echo e(__('Reject')); ?>" class="btn btn-danger rounded" name="status">
    <?php endif; ?>
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH /var/www/html/resources/views/getpass/action.blade.php ENDPATH**/ ?>