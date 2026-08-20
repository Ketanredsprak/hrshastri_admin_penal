

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__("Manage Plan Order")); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__("Home")); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__("Plan Order")); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    
                    <div class="table-responsive">
                        <table class="table data-table " id="pc-dt-simple12">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Order Id')); ?></th>
                                    <th><?php echo e(__('Name')); ?></th>
                                    <th><?php echo e(__('Plan Name')); ?></th>
                                    <th><?php echo e(__('Price')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Date')); ?></th>
                                    <th><?php echo e(__('Coupon')); ?></th>
                                    <th><?php echo e(__('Type')); ?></th>
                                    <th><?php echo e(__('Invoice')); ?></th>
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
<script type="text/javascript">
  $(function () {
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
                    processing: '<i></i><span class="text-primary spinner-border"></span> '},
            ajax: "<?php echo e(route('order.index')); ?>",
            columns: [
                {data: 'order_id', name: 'order_id'},
                {data: 'user_name', name: 'user_name'},
                {data: 'plan_name', name: 'plan_name'},
                {data: 'price', name: 'price'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'created_at', name: 'created_at'},
                {data: 'total_coupon_used', name: 'total_coupon_used'},
                {data: 'payment_type', name: 'payment_type'},
                {data: 'receipt', name: 'receipt'},
            ]
        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/order/index.blade.php ENDPATH**/ ?>