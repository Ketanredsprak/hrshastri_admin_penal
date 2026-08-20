<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Timesheet')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>

    <li class="breadcrumb-item"><?php echo e(__('Timesheet')); ?></li>
<?php $__env->stopSection(); ?>



<?php if(\Auth::user()->type != 'company'): ?>

    <?php $__env->startSection('action-button'); ?>
        <a href="<?php echo e(route('timesheet.export')); ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
            data-bs-original-title="<?php echo e(__('Export')); ?>">

            <i class="ti ti-file-export"></i>

        </a>

        

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create TimeSheet')): ?>
            <a href="#" data-url="<?php echo e(route('timesheet.create')); ?>" data-ajax-popup="true" data-size="md"
                data-title="<?php echo e(__('Create New Timesheet')); ?>" data-bs-toggle="tooltip" title=""
                class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Create')); ?>">

                <i class="ti ti-plus"></i>

            </a>
        <?php endif; ?>
    <?php $__env->stopSection(); ?>

<?php endif; ?>



<?php $__env->startSection('content'); ?>
    <div class="row">



        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">

            <div class=" mt-2 " id="multiCollapseExample1" style="">

                <div class="card">

                    <div class="card-body">

                        <?php echo e(Form::open(['route' => ['timesheet.index'], 'method' => 'get', 'id' => 'timesheet_filter'])); ?>






                        <div class="row align-items-center">

                            <div class="col-sm-8">

                                <div class="row ">

                                    <div class="col-sm-4 col-lg-4">

                                        <div class="btn-box">

                                            <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'form-label'])); ?>


                                            <input type="date" name="start_date"
                                                value="<?php echo e(isset($_GET['start_date']) ? $_GET['start_date'] : ''); ?>"
                                                class="month-btn form-control d_week">

                                        </div>

                                    </div>

                                    <div class="col-sm-4 col-lg-4">

                                        <div class="btn-box">

                                            <?php echo e(Form::label('end_date', __('End Date'), ['class' => 'form-label'])); ?>


                                            <input type="date" name="end_date"
                                                value="<?php echo e(isset($_GET['end_date']) ? $_GET['end_date'] : ''); ?>"
                                                class="month-btn form-control d_week">

                                        </div>

                                    </div>



                                </div>



                            </div>

                            <div class="col-sm-4 text-right pt-4">

                                <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('timesheet_filter').submit(); return false;"
                                    data-bs-toggle="tooltip" title="" data-bs-original-title="apply">

                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>

                                </a>

                                <a href="<?php echo e(route('timesheet.index')); ?>" class="btn btn-sm btn-danger"
                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Reset">

                                    <span class="btn-inner--icon"><i class="ti ti-refresh text-white-off "></i></span>

                                </a>

                            </div>

                        </div>





                        <!--<div class="d-flex align-items-center justify-content-end">

                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">

                                                <div class="btn-box">

                                                    <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'form-label'])); ?>


                                                    <?php echo e(Form::text('start_date', isset($_GET['start_date']) ? $_GET['start_date'] : '', ['class' => 'month-btn form-control d_week current_date', 'autocomplete' => 'off', 'id' => 'current_date'])); ?>


                                                </div>

                                            </div>

                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">

                                                <div class="btn-box">

                                                    <?php echo e(Form::label('end_date', __('End Date'), ['class' => 'form-label'])); ?>


                                                    <?php echo e(Form::text('end_date', isset($_GET['end_date']) ? $_GET['end_date'] : '', ['class' => 'month-btn form-control d_week current_date', 'autocomplete' => 'off', 'id' => 'current_date'])); ?>


                                                </div>

                                            </div>

                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">

                                                <div class="btn-box">

                                                    <?php echo e(Form::label('employee', __('Employee'), ['class' => 'form-label'])); ?>


                                                    <?php echo e(Form::select('employee', $employeesList, isset($_GET['employee']) ? $_GET['employee'] : '', ['class' => 'form-control select ', 'id' => 'employee_id'])); ?>


                                                </div>

                                            </div>

                                            <div class="col-auto float-end ms-2 mt-4">

                                                <a href="#" class="btn btn-sm btn-primary"

                                                    onclick="document.getElementById('timesheet_filter').submit(); return false;"

                                                    data-bs-toggle="tooltip" title="" data-bs-original-title="apply">

                                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>

                                                </a>

                                                <a href="<?php echo e(route('timesheet.index')); ?>" class="btn btn-sm btn-danger"

                                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Reset">

                                                    <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>

                                                </a>

                                            </div>

                                        </div>-->

                        <?php echo e(Form::close()); ?>


                    </div>

                </div>

            </div>

        </div>





        <div class="col-xl-12">

            <div class="card">

                <div class="card-header card-body table-border-style">

                    

                    <div class="">



                        <div class="table-responsive">

                            <table class="table" id="pc-dt-simple">

                                <thead>

                                    <tr>

                                        

                                        <th><?php echo e(__('Date')); ?></th>

                                        <th><?php echo e(__('Hours')); ?></th>

                                        <th><?php echo e(__('Remark')); ?></th>

                                        <th width="200ox"><?php echo e(__('Action')); ?></th>

                                    </tr>

                                </thead>

                                <tbody>





                                    <?php $__currentLoopData = $timeSheets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timeSheet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>

                                            

                                            <td><?php echo e(\Auth::user()->dateFormat($timeSheet->date)); ?></td>

                                            <td><?php echo e($timeSheet->hours); ?></td>

                                            <td><?php echo e(substr($timeSheet->remark, 0, 60) . '..'); ?></td>

                                            <td class="Action">



                                                <span>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit TimeSheet')): ?>
                                                        <div class="action-btn bg-info ms-2">

                                                            <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="<?php echo e(route('timesheet.edit', $timeSheet->id)); ?>"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="<?php echo e(__('Edit Timesheet')); ?>"
                                                                data-bs-original-title="<?php echo e(__('Edit')); ?>">

                                                                <i class="ti ti-pencil text-white"></i>

                                                            </a>

                                                        </div>
                                                    <?php endif; ?>



                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete TimeSheet')): ?>
                                                        <div class="action-btn bg-danger ms-2">

                                                            <?php echo Form::open([
                                                                'method' => 'DELETE',
                                                            
                                                                'route' => ['timesheet.destroy', $timeSheet->id],
                                                            
                                                                'id' => 'delete-form-' . $timeSheet->id,
                                                            ]); ?>


                                                            <a href="#"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>

                                                            </form>

                                                        </div>
                                                    <?php endif; ?>

                                                </span>

                                            </td>

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
            $(document).ready(function() {

                var now = new Date();

                var month = (now.getMonth() + 1);

                var day = now.getDate();

                if (month < 10) month = "0" + month;

                if (day < 10) day = "0" + day;

                var today = now.getFullYear() + '-' + month + '-' + day;

                $('.current_date').val(today);

            });
        </script>
    <?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/timeSheet/index.blade.php ENDPATH**/ ?>