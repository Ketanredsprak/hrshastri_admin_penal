<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th><?php echo e(__('Employee')); ?></th>
                    <td><?php echo e(!empty($data->employee()) ? $data->employee()->name : ''); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Resignation Date')); ?></th>
                    <td><?php echo e(\Auth::user()->dateFormat($data->resignation_date)); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Last Working Date')); ?></th>
                    <td><?php echo e(\Auth::user()->dateFormat($data->notice_date)); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Description')); ?></th>
                    <td><p><?php echo nl2br(e($data->description ?? '')); ?></p></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Notes')); ?></th>
                    <td><?php echo e($data->notes ?? ''); ?></td>
                </tr>
                <tr>
                    <th><?php echo e(__('Status')); ?></th>
                    <td>
                        <?php if($data->status == 0): ?>
                             <div class="badge bg-warning p-2 px-3 rounded status-badge5">pending</div>
                        <?php elseif($data->status == 1): ?>
                             <div class="badge bg-success p-2 px-3 rounded status-badge5">Approve</div>
                        <?php elseif($data->status == 2): ?>
                             <div class="badge bg-danger p-2 px-3 rounded status-badge5">Rejected</div>
                        <?php else: ?>
                             <div class="badge bg-danger p-2 px-3 rounded status-badge5"></div>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
    </div>
</div><?php /**PATH /var/www/html/resources/views/resignation/resignation_request_view.blade.php ENDPATH**/ ?>