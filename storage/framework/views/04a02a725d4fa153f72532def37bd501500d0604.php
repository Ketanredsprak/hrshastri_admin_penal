<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Attendance List')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Attendance List')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-section'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('script-page'); ?>
    <script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript"
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBZFQKMMsd28jQDnGIHsDL6fJYhnWmeeCE"></script>
    
    


    <script>
        $('input[name="type"]:radio').on('change', function(e) {
            var type = $(this).val();

            if (type == 'monthly') {
                $('.month').addClass('d-block');
                $('.month').removeClass('d-none');
                $('.date').addClass('d-none');
                $('.date').removeClass('d-block');
            } else {
                $('.date').addClass('d-block');
                $('.date').removeClass('d-none');
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
<?php $__env->startSection('action-button'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php if(session('status')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo session('status'); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-sm-12">
            <div class=" mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        <?php echo e(Form::open(['route' => ['attendanceemployee.index'], 'method' => 'get', 'id' => 'attendanceemployee_filter'])); ?>


                        <div class="row align-items-center">
                            <div class="col-sm-9">
                                <div class="row ">
                                    <div class="col-sm-6 col-lg-3">
                                        <label class="form-label"><?php echo e(__('Type')); ?></label> <br>

                                        <div class="form-check form-check-inline form-group">
                                            <input type="radio" id="monthly" value="monthly" name="type"
                                                class="form-check-input" <?php echo e($type == 'monthly' ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="monthly"><?php echo e(__('Monthly')); ?></label>
                                        </div>
                                        <div class="form-check form-check-inline form-group">
                                            <input type="radio" id="daily" value="daily" name="type"
                                                class="form-check-input" <?php echo e($type == 'daily' ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="daily"><?php echo e(__('Daily')); ?></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3 month">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('month', __('Month'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::month('month', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), ['class' => 'month-btn form-control month-btn'])); ?>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3 date">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('date', __('Date'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::date('date', $date, ['class' => 'form-control month-btn'])); ?>

                                        </div>
                                    </div>
                                    <?php if(\Auth::user()->type != 'employee'): ?>
                                        <div class="col-sm-6 col-lg-3">

                                            <div class="btn-box">
                                                <?php echo e(Form::label('branch', __('Branch'), ['class' => 'form-label'])); ?>

                                                <?php echo e(Form::select('branch', $branch, isset($_GET['branch']) ? $_GET['branch'] : '', ['id' => 'branch_id', 'class' => 'form-control'])); ?>

                                            </div>


                                        </div>
                                        <div class="col-sm-6 col-lg-3">

                                            <div class="btn-box">
                                                <?php echo e(Form::label('department', __('Department'), ['class' => 'form-label'])); ?>

                                                <?php echo e(Form::select('department', [], isset($_GET['department']) ? $_GET['department'] : '', ['id' => 'department_id', 'class' => 'form-control'])); ?>

                                                <?php echo e(Form::hidden('requestDepartmentId', $requestDepartmentId ?? '', ['id' => 'requestDepartmentId', 'class' => 'form-control'])); ?>

                                            </div>
                                        </div>
                                    <?php endif; ?>

                                </div>

                            </div>
                            <div class="col-sm-3 text-right pt-4">

                                <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('attendanceemployee_filter').submit(); return false;"
                                    data-bs-toggle="tooltip" title="<?php echo e(__('Apply')); ?>"
                                    data-original-title="<?php echo e(__('apply')); ?>">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>

                                <a href="<?php echo e(route('attendanceemployee.index')); ?>" class="btn btn-sm btn-danger "
                                    data-bs-toggle="tooltip" title="<?php echo e(__('Reset')); ?>"
                                    data-original-title="<?php echo e(__('Reset')); ?>">
                                    <span class="btn-inner--icon"><i class="ti ti-refresh  text-white-off "></i></span>
                                </a>

                                <a href="#" data-url="<?php echo e(route('attendance.file.import')); ?>" data-ajax-popup="true"
                                    data-title="<?php echo e(__('Import Attendance File')); ?>" data-bs-toggle="tooltip" title=""
                                    class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Import')); ?>">
                                    <i class="ti ti-file"></i>
                                </a>

                                <?php
                                    $date =
                                        $type == 'monthly'
                                            ? (isset($_GET['month'])
                                                ? $_GET['month']
                                                : date('Y-m'))
                                            : $date;
                                    $branch = isset($_GET['branch']) && !empty($_GET['branch']) ? $_GET['branch'] : 0;
                                    $department =
                                        isset($_GET['department']) && !empty($_GET['department'])
                                            ? $_GET['department']
                                            : 0;
                                ?>

                                <a href="<?php echo e(route('attendance.export', [$type, $date, $branch, $department])); ?>"
                                    data-bs-toggle="tooltip" title="<?php echo e(__('Export')); ?>" class="btn btn-sm btn-danger"
                                    data-bs-original-title="<?php echo e(__('Export')); ?>">
                                    <i class="ti ti-file"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>




        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <?php if(\Auth::user()->type != 'employee'): ?>
                                        <th><?php echo e(__('Employee Id')); ?></th>
                                        <th><?php echo e(__('Employee')); ?></th>
                                    <?php endif; ?>
                                    <th><?php echo e(__('Date')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Clock In')); ?></th>
                                    <th><?php echo e(__('Clock In Ip Address')); ?></th>
                                    <th><?php echo e(__('Clock Out')); ?></th>
                                    <th><?php echo e(__('Clock Out Ip Address')); ?></th>
                                    <th><?php echo e(__('Late')); ?></th>
                                    <th><?php echo e(__('Early Leaving')); ?></th>
                                    <th><?php echo e(__('Overtime')); ?></th>
                                    <?php if(Gate::check('Edit Attendance') || Gate::check('Delete Attendance')): ?>
                                        <th width="200px"><?php echo e(__('Action')); ?></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>



                                <?php $__currentLoopData = $attendanceEmployee; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <?php if(\Auth::user()->type != 'employee'): ?>
                                            <td><?php echo e(!empty($attendance->employee) ? $attendance->employee->employee_id : ''); ?>

                                            </td>
                                            <td><?php echo e(!empty($attendance->employee) ? $attendance->employee->name : ''); ?></td>
                                        <?php endif; ?>
                                        <td><?php echo e(\Auth::user()->dateFormat($attendance->date)); ?></td>
                                        <td><?php echo e($attendance->status); ?></td>


                                        <?php

                                            $clockInDateTime = '';
                                            $clockOutDateTime = '';

                                            if (
                                                empty($attendance->clock_in_date_time) ||
                                                $attendance->clock_in_date_time == '0000-00-00 00:00:00'
                                            ) {
                                                $clockInDateTime =
                                                    $attendance->getRawOriginal('date') . ' ' . $attendance->clock_in;
                                            } else {
                                                $clockInDateTime = $attendance->clock_in_date_time;
                                            }

                                            if (
                                                empty($attendance->clock_out_date_time) ||
                                                $attendance->clock_out_date_time == '0000-00-00 00:00:00'
                                            ) {
                                                $clockOutDateTime =
                                                    $attendance->getRawOriginal('date') . ' ' . $attendance->clock_out;
                                            } else {
                                                $clockOutDateTime = $attendance->clock_out_date_time;
                                            }
                                        ?>

                                        <td>
                                            <?php
                                                if (
                                                    $attendance->clock_in != '' &&
                                                    $attendance->clock_in != '00:00:00'
                                                ) {
                                                    echo \Auth::user()->dateFormat($clockInDateTime) .
                                                        ' ' .
                                                        \Auth::user()->timeFormat($clockInDateTime);
                                                }
                                            ?>
                                        </td>

                                        <td><?php echo e($attendance->clock_in_ip_address ?? ''); ?></td>

                                        <td>
                                            <?php
                                                if (
                                                    $attendance->clock_out != '' &&
                                                    $attendance->clock_out != '00:00:00'
                                                ) {
                                                    echo \Auth::user()->dateFormat($clockOutDateTime) .
                                                        ' ' .
                                                        \Auth::user()->timeFormat($clockOutDateTime);
                                                }
                                            ?>
                                        </td>

                                        <td><?php echo e($attendance->clock_out_ip_address ?? ''); ?></td>

                                        <td><?php echo e($attendance->late == '00:00:00' ? '00:00:00' : date('H:i:s', strtotime($attendance->late))); ?>

                                        <td><?php echo e($attendance->early_leaving == '00:00:00' ? '00:00:00' : date('H:i:s', strtotime($attendance->early_leaving))); ?>

                                        <td><?php echo e($attendance->overtime == '00:00:00' ? '00:00:00' : date('H:i:s', strtotime($attendance->overtime))); ?>


                                        <td class="Action">
                                            <?php if(Gate::check('Edit Attendance') || Gate::check('Delete Attendance')): ?>
                                                <span>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Attendance')): ?>
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                data-size="lg"
                                                                data-url="<?php echo e(URL::to('attendanceemployee/' . $attendance->id . '/edit')); ?>"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="<?php echo e(__('Edit Attendance')); ?>"
                                                                data-bs-original-title="<?php echo e(__('Edit')); ?>">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>

                                                        <?php if(!empty($attendance->clock_in_latitude)): ?>
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                    data-size="lg"
                                                                    data-url="<?php echo e(URL::to('google_map/' . $attendance->id)); ?>"
                                                                    data-ajax-popup="true" data-size="md"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-title="<?php echo e(__('Clock In / Clock Out Map')); ?>"
                                                                    data-bs-original-title="<?php echo e(__('Map')); ?>">
                                                                    <i class="fas fa-map-marker text-white"></i>
                                                                </a>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>

                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Attendance')): ?>
                                                        <div class="action-btn bg-danger ms-2">
                                                            <?php echo Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['attendanceemployee.destroy', $attendance->id],
                                                                'id' => 'delete-form-' . $attendance->id,
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
                                            <?php endif; ?>
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
    <script type="text/javascript">
        $(document).ready(function() {

            getDepartments();

            $('#branch_id').on('change', function() {

                $('#department_id').empty();
                getDepartments();
            });

        });

        function getDepartments() {

            try {
                $.ajax({
                    url: "<?php echo e(route('get-departments')); ?>", // Route to fetch departments
                    type: 'POST',
                    data: {
                        branch_id: ($('#branch_id').val() ?? 0),
                        _token: "<?php echo e(csrf_token()); ?>"
                    },
                    success: function(data) {

                        try {
                            if (data.length > 1) {
                                var newOption = new Option("All", 0, false, false);
                                $('#department_id').append(newOption);
                            }

                            var branch_id = ($('#branch_id').val() ?? 0);

                            var requestDepartmentId = $("#requestDepartmentId").val();

                            console.log("requestDepartmentId", requestDepartmentId);

                            $("#requestDepartmentId").val("");


                            data.forEach(function(department) {

                                var isSelected = false;

                                if (data.length == 1 || department.id == requestDepartmentId) {
                                    var isSelected = true;
                                }
                                var newOption = new Option(department.name, department.id, isSelected,
                                    isSelected);
                                $('#department_id').append(newOption);
                            });



                        } catch (error) {

                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching departments:', error);
                    }
                });

            } catch (error) {
                console.error('Error fetching departments:', error);
            }
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/attendance/index.blade.php ENDPATH**/ ?>