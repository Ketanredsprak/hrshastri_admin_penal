<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Resignation Request')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Resignation')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table resignation-datatable" id="pc-dt-simple12">
                            <thead>
                                <th><?php echo e(__('Employee Id')); ?></th>
                                <th><?php echo e(__('Employee Name')); ?></th>
                                <th><?php echo e(__('Resignation Date')); ?></th>
                                <th><?php echo e(__('Last Working Day')); ?></th>
                                <th><?php echo e(__('Reason')); ?></th>
                                <th><?php echo e(__('Notes')); ?></th>
                                <th class="text-end"><?php echo e(__('Status')); ?></th>
                                <?php if(Auth::user()->type != 'employee'): ?>
                                    <th width="200px"><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        $(document).ready(function() {
            var table = $('.resignation-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "<?php echo e(route('resignation_request')); ?>",
                order: [
                    [2, 'desc']
                ],
                columns: [{
                        data: 'employee_id',
                        name: 'employee_id'
                    },
                    {
                        data: 'employee',
                        name: 'employee'
                    },
                    {
                        data: 'resignation_date',
                        name: 'resignation_date'
                    },
                    {
                        data: 'last_working_day',
                        name: 'last_working_day'
                    },
                    {
                        data: 'reason',
                        name: 'reason'
                    },
                    {
                        data: 'notes',
                        name: 'notes'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/resignation/resignation_request.blade.php ENDPATH**/ ?>