<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Esic Register Reports')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>

    <li class="breadcrumb-item"><?php echo e(__('Esic Register Reports')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Promotion')): ?>
        <?php echo e(Form::open(['route' => ['esic_register_reports_export'], 'method' => 'get', 'id' => 'report_income_expense'])); ?>


        <input type="hidden" value="<?php echo e(isset($_GET['month']) ? $_GET['month'] : date('Y-m')); ?>" name="month">
        <button type="submit" class="btn btn-success btn-sm">Export</button>

        <?php echo e(Form::close()); ?>

    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">

        <div class="col-xl-12">

            <div class="card">

                <div class="card-body">

                    <?php echo e(Form::open(['route' => ['esic_register_reports'], 'method' => 'get', 'id' => 'report_leave'])); ?>


                    <div class="row align-items-center">

                        <div class="col-sm-10">

                            <div class="row ">

                                <div class="col-sm-6 col-lg-3">

                                    <?php echo e(Form::label('type', __('Type'), ['class' => 'form-label'])); ?><br>

                                    <div class="form-check form-check-inline form-group">

                                        <input type="radio" id="monthly" value="monthly" name="type"
                                            class="form-check-input"
                                            <?php echo e(isset($_GET['type']) && $_GET['type'] == 'monthly' ? 'checked' : 'checked'); ?>>

                                        <?php echo e(Form::label('monthly', __('Monthly'), ['class' => 'form-label'])); ?>


                                    </div>

                                </div>

                                <div class="col-sm-6 col-lg-3 month">

                                    <div class="btn-box">

                                        <?php echo e(Form::label('month', __('Month'), ['class' => 'form-label'])); ?>


                                        <?php echo e(Form::text('month', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), ['class' => 'month-btn form-control d_filter'])); ?>


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

                            <a href="<?php echo e(route('esic_register_reports')); ?>" class="btn btn-sm btn-danger "
                                data-bs-toggle="tooltip" title="<?php echo e(__('Reset')); ?>"
                                data-original-title="<?php echo e(__('Reset')); ?>">

                                <span class="btn-inner--icon"><i class="ti ti-refresh text-white-off "></i></span>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

            <?php echo e(Form::close()); ?>


            <div class="card">

                <div class="card-header card-body table-border-style">

                    <div class="table-responsive">

                        <table class="table">

                            <thead>

                                <tr>

                                    <th><?php echo e(__('Sr.no')); ?></th>

                                    <th><?php echo e(__('EMP Code')); ?></th>

                                    <th><?php echo e(__('Name')); ?></th>

                                    <th><?php echo e(__('ESI No')); ?></th>

                                    <th><?php echo e(__('Present Days')); ?></th>

                                    <th><?php echo e(__('Gross Salary')); ?></th>

                                </tr>

                            </thead>

                            <tbody>



                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>

                                        <td><?php echo e($row->id); ?></td>

                                        <td><?php echo e(\Auth::user()->employeeIdFormat($row->id)); ?></td>

                                        <td><?php echo e($row->empName); ?></td>

                                        <td><?php echo e($row->bank_identifier_code); ?></td>

                                        <td><?php echo e($row->attdebdance); ?></td>

                                        <td><?php echo e(\Auth::user()->priceFormat($row->basic_salary)); ?></td>

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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/ReportsManage/esic_register_reports.blade.php ENDPATH**/ ?>