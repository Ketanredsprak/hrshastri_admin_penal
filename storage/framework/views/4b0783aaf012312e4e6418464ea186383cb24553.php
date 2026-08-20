<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Timesheet Report')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Manage Timesheet Report')); ?></li>
<?php $__env->stopSection(); ?>

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
    <style>
        select#branch {
            width: 100% !important;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('action-button'); ?>
    <!-- <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()" data-bs-toggle="tooltip" title="<?php echo e(__('Download')); ?>"
                data-original-title="<?php echo e(__('Download')); ?>" style="margin-right: 5px">
                <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
            </a>
            <a href="<?php echo e(route('timesheet.report.export')); ?>" class="btn btn-sm btn-primary float-end" data-bs-toggle="tooltip"
                data-bs-original-title="<?php echo e(__('Export')); ?>">
                <i class="ti ti-file-export"></i>
            </a> -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class=" mt-2 ">
                <div class="card">
                    <div class="card-body">
                        <?php echo e(Form::open(['route' => ['report.timesheet'], 'method' => 'get', 'id' => 'report_timesheet'])); ?>


                        <div class="row align-items-center">
                            <div class="col-sm-9">
                                <div class="row ">
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::date('start_date', isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d'), ['class' => 'month-btn form-control current_date'])); ?>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('end_date', __('End Date'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::date('end_date', isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d'), ['class' => 'month-btn form-control current_date'])); ?>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('branch', __('Branch'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::select('branch', $branch, isset($_GET['branch']) ? $_GET['branch'] : '', ['class' => 'month-btn form-control select'])); ?>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('department', __('Department'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::select('department', $department, isset($_GET['department']) ? $_GET['department'] : '', ['class' => 'month-btn form-control select'])); ?>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-3 text-right pt-4">

                                <button name="btn_trigger" value="search" class="btn btn-sm btn-primary"
                                    data-bs-toggle="tooltip" title="<?php echo e(__('Apply')); ?>"
                                    data-original-title="<?php echo e(__('apply')); ?>">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </button>

                                <button name="btn_trigger" value="reset" class="btn btn-sm btn-danger "
                                    data-bs-toggle="tooltip" title="<?php echo e(__('Reset')); ?>"
                                    data-original-title="<?php echo e(__('Reset')); ?>">
                                    <span class="btn-inner--icon"><i class="ti ti-refresh text-white-off "></i></span>
                                </button>

                                <button name="btn_trigger" value="export" class="btn btn-sm btn-primary float-end"
                                    data-bs-toggle="tooltip" data-bs-original-title="<?php echo e(__('Export')); ?>">
                                    <i class="ti ti-file-export"></i>
                                </button>

                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>


        <div id="printableArea">

            <div class="row mt-3">
                <div class="col">
                    <input type="hidden"
                        value="<?php echo e($filterYear['branch'] . ' ' . __('Branch') . ' ' . __('Timesheet Report') . ' '); ?><?php echo e($filterYear['start_date'] . ' to ' . $filterYear['end_date'] . ' ' . __('of') . ' ' . $filterYear['department'] . ' ' . 'Department'); ?>"
                        id="filename">
                    <div class="card p-4 mb-4">
                        <h6 class="report-text gray-text mb-0"><?php echo e(__('Title')); ?> :</h6>
                        <small class="text-muted"><?php echo e(__('Timesheet Report')); ?></small>
                    </div>
                </div>
                <?php if($filterYear['branch'] != 'All'): ?>
                    <div class="col">
                        <div class="card p-4 mb-4">
                            <h6 class="report-text gray-text mb-0"><?php echo e(__('Branch')); ?> :</h6>
                            <small class="text-muted"><?php echo e($filterYear['branch']); ?></small>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if($filterYear['department'] != 'All'): ?>
                    <div class="col">
                        <div class="card p-4 mb-4">
                            <h6 class="report-text gray-text mb-0"><?php echo e(__('Department')); ?> :</h6>
                            <small class="text-muted"><?php echo e($filterYear['department']); ?></small>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h6 class="report-text gray-text mb-0"><?php echo e(__('Duration')); ?> :</h6>
                        <small
                            class="text-muted"><?php echo e($filterYear['start_date'] . ' to ' . $filterYear['end_date']); ?></small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="card p-4 mb-4">
                        <small class="text-muted"><?php echo e(__('Total Working Employee')); ?> :</small>
                        <h5 class="report-text mb-0"><?php echo e($filterYear['totalEmployee']); ?></h5>
                    </div>
                </div>
                <div class="col">
                    <div class="card p-4 mb-4 ">
                        <small class="text-muted"><?php echo e(__('Total Working Hours')); ?> :</small>
                        <h6 class="report-text mb-0"><?php echo e($filterYear['totalHours']); ?></h6>
                    </div>
                </div>
            </div>
            <div class=" row ">
                <?php $__currentLoopData = $timesheetFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timesheetFilter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-3">
                        <div class="card p-4 mb-4 timesheet-card">

                            <h6 class="report-text mb-0"><?php echo e($timesheetFilter->name); ?> </h6>
                            <small class="report-text text-muted "><?php echo e(__('Total Working Hours')); ?> :
                                <?php echo e($timesheetFilter->total); ?>

                            </small>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

        </div>


        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive ">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Employee ID')); ?></th>
                                    <th><?php echo e(__('Employee')); ?></th>
                                    <th><?php echo e(__('Date')); ?></th>
                                    <th><?php echo e(__('Hours')); ?></th>
                                    <th><?php echo e(__('Description')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $timesheets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timesheet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        
                                        <td><?php echo e($timesheet->employee_id); ?></td>
                                        <td><?php echo e(!empty($timesheet->employees) ? $timesheet->employees->name : ''); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($timesheet->date)); ?></td>
                                        <td><?php echo e($timesheet->hours); ?></td>
                                        <td><?php echo e($timesheet->remark); ?></td>
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

<?php $__env->startPush('script-page'); ?>
    <script>
        // $(document).ready(function() {
        //     var now = new Date();
        //     var month = (now.getMonth() + 1);
        //     var day = now.getDate();
        //     if (month < 10) month = "0" + month;
        //     if (day < 10) day = "0" + day;
        //     var today = now.getFullYear() + '-' + month + '-' + day;
        //     $('.current_date').val(today);
        // });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/report/timesheet.blade.php ENDPATH**/ ?>