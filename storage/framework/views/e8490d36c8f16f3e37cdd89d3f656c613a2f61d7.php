<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('My Leave Balance')); ?>

<?php $__env->stopSection(); ?>

<?php if(\Auth::user()->type != 'company'): ?>
    <?php $__env->startSection('breadcrumb'); ?>
        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
        <li class="breadcrumb-item"><?php echo e(__('Leave ')); ?></li>
    <?php $__env->stopSection(); ?>
<?php endif; ?>
<?php $__env->startPush('script-page'); ?>
    <script type="text/javascript" src="<?php echo e(asset('js/html2pdf.bundle.min.js')); ?>"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A4'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
    <script>
        $('input[name="type"]:radio').on('change', function(e) {
            var type = $(this).val();
            if (type == 'monthly') {
                $('.month').addClass('d-block');
                $('.month').removeClass('d-none');
                $('.year').addClass('d-none');
                $('.year').removeClass('d-block');
            } else {
                $('.year').addClass('d-block');
                $('.year').removeClass('d-none');
                $('.month').addClass('d-none');
                $('.month').removeClass('d-block');
            }
        });

        $('input[name="type"]:radio:checked').trigger('change');
    </script>
    <style>
        select#branch {
            width: 100% !important;
        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <?php echo e(Form::open(['route' => ['leave.my_leave_balance'], 'method' => 'get', 'id' => 'report_leave'])); ?>


                        <div class="row align-items-center">
                            <div class="col-sm-10">
                                <div class="row">
                                    <div class="col-sm-6 col-lg-3 Month">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('monthAndYear', __('Month'), ['class' => 'form-label'])); ?>

                                            <input type="month" class="form-control" id="monthAndYear" name="monthAndYear"
                                                value="<?php echo e(@$monthAndYear ?? date('Y-m')); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2 text-right pt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('report_leave').submit(); return false;"
                                    data-bs-toggle="tooltip" title="<?php echo e(__('Apply')); ?>"
                                    data-original-title="<?php echo e(__('apply')); ?>">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="<?php echo e(route('leave.my_leave_balance')); ?>" class="btn btn-sm btn-danger "
                                    data-bs-toggle="tooltip" title="<?php echo e(__('Reset')); ?>"
                                    data-original-title="<?php echo e(__('Reset')); ?>">
                                    <span class="btn-inner--icon"><i class="ti ti-refresh text-white-off "></i></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table" id="pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th><?php echo e(__('Leave Type')); ?></th>
                                        
                                        
                                        <th><?php echo e(__('Approved Leaves')); ?></th>
                                        <th><?php echo e(__('Balance Leave')); ?></th>
                                    </tr>
                                </thead>
                                <?php $total_leave_data = 0; ?>
                                <tbody>
                                    <?php $__currentLoopData = $leave_counts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($leave->title); ?> </td>
                                            
                                            
                                            </span></td>
                                            <td> <span class="badge bg-danger p-2 px-3 rounded status-badge5">
                                                    <?php echo e(formatNumber($leave->applied_leaves)); ?>

                                                </span></td>
                                            <td> <span class="badge bg-success p-2 px-3 rounded status-badge5">
                                                    <?php echo e(formatNumber($leave->used_leaved)); ?>

                                                </span></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/leave/my_leave_balance.blade.php ENDPATH**/ ?>