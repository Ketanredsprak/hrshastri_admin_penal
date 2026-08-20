<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Leave Request')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Leave ')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>
    <a href="#" data-url="<?php echo e(route('leave_request_create')); ?>" data-ajax-popup="true"
        data-title="<?php echo e(__('Create New Leave')); ?>" data-size="lg" data-bs-toggle="tooltip" title=""
        class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Create')); ?>">
        <i class="ti ti-plus"></i>
    </a>
<?php $__env->stopSection(); ?>




<?php $__env->startSection('content'); ?>
    <div class="row">
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
                            <h4 class="m-0 text-warning"><?php echo e(re_leave_pending()); ?></h4>
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
                            <h4 class="m-0 text-success"><?php echo e(re_leave_approved()); ?></h4>
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
                            <h4 class="m-0 text-danger"><?php echo e(re_leave_rejected()); ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table leaverequest-datatable" id="pc-dt-simple12">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Employee Id')); ?></th>
                                    <th><?php echo e(__('Employee')); ?></th>
                                    <th><?php echo e(__('Leave Type')); ?></th>
                                    <th><?php echo e(__('Applied On')); ?></th>
                                    <th><?php echo e(__('Start Date')); ?></th>
                                    <th><?php echo e(__('End Date')); ?></th>
                                    <th><?php echo e(__('Total Days')); ?></th>
                                    <th><?php echo e(__('status')); ?></th>
                                    <th width="200px"><?php echo e(__('Action')); ?></th>
                                </tr>
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
    <script type="text/javascript">
        $(function() {
            var table = $('.leaverequest-datatable').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    processing: '<i></i><span class="text-primary spinner-border"></span> '
                },
                ajax: "<?php echo e(route('leaverequest')); ?>",
                order: [
                    [4, 'desc']
                ],
                columns: [{
                        data: 'employee_id',
                        name: 'employee_id',
                        className: 'text-center',
                        orderable: true,
                        searchable: true
                    },

                    {
                        data: 'employee',
                        name: 'employee'
                    },

                    {
                        data: 'leave_type',
                        name: 'leave_type'
                    },

                    {
                        data: 'applied_on',
                        name: 'applied_on'
                    },

                    {
                        data: 'start_date',
                        name: 'start_date'
                    },

                    {
                        data: 'end_date',
                        name: 'end_date'
                    },

                    {
                        data: 'total_days',
                        name: 'total_days',
                        className: 'text-center'
                    },

                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },

                ]
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/leave/leaverequest.blade.php ENDPATH**/ ?>