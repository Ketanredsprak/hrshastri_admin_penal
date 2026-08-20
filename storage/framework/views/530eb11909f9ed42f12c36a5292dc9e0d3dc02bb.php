<?php echo e(Form::open(['url' => 'earlyandlate_leave/changeaction', 'method' => 'post'])); ?>


<div class="modal-body">

    <div class="row">

        <div class="col-12">

            <table class="table modal-table" id="pc-dt-simple">



                <tr>
                    <th><?php echo e(__('Employee Name')); ?></th>
                    <td>
                        <?php echo e($data->employee->name); ?>

                    </td>
                </tr>
                <tr>

                    <th><?php echo e(__('Leave Type ')); ?></th>

                    <td>

                        <?php if($data->name == 1): ?>
                            Early Leave
                        <?php else: ?>
                            Late Cooming
                        <?php endif; ?>

                    </td>

                </tr>

                <tr>

                    <th><?php echo e(__('date')); ?></th>

                    <td><?php echo e(\Auth::user()->dateFormat($data->date)); ?></td>

                </tr>

                <!-- <tr>

                    <th><?php echo e(__('In Time')); ?></th>

                    <td><?php echo e($data->in_time); ?></td>

                </tr>

                <tr>

                    <th><?php echo e(__('Out Time')); ?></th>

                    <td><?php echo e($data->out_time); ?></td>

                </tr> -->

                <tr>

                    <th><?php echo e(__('Reason')); ?></th>

                    <td><?php echo e(!empty($data->resone) ? $data->resone : ''); ?></td>

                </tr>
                <tr>

                    <th><?php echo e(__('Status')); ?></th>

                    <?php if($data->status == 0): ?>
                        <td>Pending</td>
                    <?php elseif($data->status == 1): ?>
                        <td>Approved</td>
                    <?php elseif($data->status == 2): ?>
                        <td>Rejected</td>
                    <?php endif; ?>


                </tr>

                <input type="hidden" value="<?php echo e($data->id); ?>" name="leave_id">

            </table>

        </div>

    </div>

</div>

<div class="modal-footer">

    <!-- 0 = pending, 1 = Approved, 2 = Rejected	 -->
    <?php if($data->status == 0): ?>
        <input type="hidden" value="<?php echo e(__('Approved')); ?>" class="btn btn-success rounded" name="status">

        <input type="submit" value="<?php echo e(__('Approve')); ?>" class="btn btn-success rounded" name="visit_statusData">
    
        <input type="submit" value="<?php echo e(__('Reject')); ?>" class="btn btn-danger rounded" name="status">
    <?php endif; ?>

</div>

<?php echo e(Form::close()); ?>

<?php /**PATH /var/www/html/resources/views/EarlyLateComming/action.blade.php ENDPATH**/ ?>