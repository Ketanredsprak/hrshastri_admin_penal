<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Dashboard')); ?>

<?php $__env->stopSection(); ?>
<?php
    $setting = App\Models\Utility::settings();
    $total_birthday = DB::table('employees')
        ->where('created_by', '=', \Auth::user()->creatorId())
        ->whereDate('dob', date('y-m-d'))
        ->count();
    $total_anniversary = DB::table('employees')
        ->where('created_by', '=', \Auth::user()->creatorId())
        ->whereMonth('company_doj', date('m'))
        ->whereDay('company_doj', date('d'))
        ->count();

    //holidaycount
    $total_holidays = DB::table('holidays')->where('created_by', '=', \Auth::user()->creatorId());
    $total_holidays = $total_holidays
        ->whereMonth('end_date', date('m'))
        ->whereYear('end_date', date('Y'))
        ->whereMonth('start_date', date('m'))
        ->whereYear('start_date', date('Y'))
        ->count();

?>

<?php $__env->startSection('header-section'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php if(session('status')): ?>
            <div class="alert alert-success" role="alert">
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>


        
        <?php if(\Auth::user()->type != 'employee'): ?>
            
            <div class="col-xxl-12">
                <div class="row">

                    <div class="col-xxl-3 col-md-4 col-sm-6">

                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti ti-users"></i>
                                            </div>
                                            <div class="ms-3">
                                                <small class="text-muted"><?php echo e(__('Total')); ?></small>
                                                <h6 class="m-0"><?php echo e(__('Staff')); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <h4 class="m-0 text-primary"><?php echo e($countUser); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-4 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti ti-ticket"></i>
                                            </div>
                                            <div class="ms-3">
                                                <small class="text-muted"><?php echo e(__('Total')); ?></small>
                                                <h6 class="m-0"><?php echo e(__('Ticket')); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <h4 class="m-0 text-info"> <?php echo e($countTicket); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-4 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti ti-wallet"></i>
                                            </div>
                                            <div class="ms-3">
                                                <small class="text-muted"><?php echo e(__('Total')); ?></small>
                                                <h6 class="m-0"><?php echo e(__('Employee')); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <h4 class="m-0 text-warning"><?php echo e($employeees); ?>

                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-4 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-avtar bg-primary">
                                                <i class="ti ti-cast"></i>
                                            </div>
                                            <div class="ms-3">
                                                <small class="text-muted"><?php echo e(__('Total')); ?></small>
                                                <h6 class="m-0"><?php echo e(__('Jobs')); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <h4 class="m-0 text-primary"><?php echo e($activeJob + $inActiveJOb); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-4 col-sm-6">

                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-avtar bg-info">
                                                <i class="ti ti-cast"></i>
                                            </div>
                                            <div class="ms-3">
                                                <small class="text-muted"><?php echo e(__('Total')); ?></small>
                                                <h6 class="m-0"><?php echo e(__('Active Jobs')); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <h4 class="m-0 text-info"> <?php echo e($activeJob); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-4 col-sm-6">

                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center justify-content-between">
                                    <div class="col-auto mb-3 mb-sm-0">
                                        <div class="d-flex align-items-center">
                                            <div class="theme-avtar bg-warning">
                                                <i class="ti ti-cast"></i>
                                            </div>
                                            <div class="ms-3">
                                                <small class="text-muted"><?php echo e(__('Total')); ?></small>
                                                <h6 class="m-0"><?php echo e(__('Inactive Jobs')); ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto text-end">
                                        <h4 class="m-0 text-warning"><?php echo e($inActiveJOb); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
        <?php endif; ?>
        


        
        <?php if(\Auth::user()->type != 'company'): ?>
            <div class="col-xxl-12">
                <div class="card" style="height: 230px;">
                    <div class="card-header">
                        <h5><?php echo e(__('Mark Attandance')); ?></h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted pb-0-5">
                            <?php echo e(__('My ' . $officeTime['text'] . ' Time: ' . date('h:i A', strtotime($officeTime['startTime'])) . ' to ' . date('h:i A', strtotime($officeTime['endTime'])))); ?>

                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span><?php echo e(($officeTime['flexiHours'] ? 'Company Flexi Time: ' . $officeTime['flexiHours'] : '') . ($officeTime['flexiDays'] && $officeTime['usedFlexiHoursDays'] ? ' , ' . ' Company Flexi Days: ' . $officeTime['usedFlexiHoursDays'] . '  /  ' . $officeTime['flexiDays'] : '')); ?></span>
                        </p>

                        <div class="row">

                            <?php
                                $clickInOutUrl = '';

                                if ($employeeDetails->geofencing_status == 0) {
                                    $clickInOutUrl = 'attendanceemployee/attendance';
                                } else {
                                    $clickInOutUrl = 'sync-clock-in-clock-out';
                                }
                                $clickInOutUrl = 'sync-clock-in-clock-out';
                            ?>

                            <div class="col-md-6 float-right border-right">

                                
                                <?php echo e(Form::open(['url' => $clickInOutUrl, 'method' => 'post', 'class' => 'd-inline'])); ?>

                                <?php echo Form::hidden('clock_type', 0); ?>


                                <?php
                                    $clockInDisabled = '';
                                ?>

                                <?php echo Form::hidden('webClockInOut', 1); ?>


                                <?php if($checkLastAttendance == null): ?>
                                    <?php
                                        $clockInDisabled = '';
                                    ?>
                                <?php else: ?>
                                    <?php if($checkLastAttendance->clock_out != '00:00:00'): ?>
                                        <?php
                                            $clockInDisabled = '';
                                        ?>
                                    <?php else: ?>
                                        <?php
                                            $clockInDisabled = 'disabled';
                                        ?>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <button type="submit" value="0" name="in" id="clock_in" class="btn btn-primary"
                                    <?php echo e($clockInDisabled); ?>><?php echo e(__('CLOCK IN')); ?></button>

                                <?php echo e(Form::close()); ?>

                                



                                

                                <?php echo e(Form::open(['url' => $clickInOutUrl, 'method' => 'post', 'class' => 'd-inline'])); ?>


                                <?php echo Form::hidden('clock_type', 1); ?>


                                <?php
                                    $clockOutDisabled = '';
                                ?>

                                <?php echo Form::hidden('webClockInOut', 1); ?>

                                <?php if($checkLastAttendance != null && $checkLastAttendance->clock_out == '00:00:00'): ?>
                                    <?php
                                        $clockOutDisabled = '';
                                    ?>
                                <?php else: ?>
                                    <?php
                                        $clockOutDisabled = 'disabled';
                                    ?>
                                <?php endif; ?>

                                <button type="submit" value="1" name="out" id="clock_out" class="btn btn-danger"
                                    <?php echo e($clockOutDisabled); ?>><?php echo e(__('CLOCK OUT')); ?></button>

                                <?php echo e(Form::close()); ?>


                                

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        <?php endif; ?>
        


        
        <?php if(\Auth::user()->type != 'employee'): ?>

            <div class="col-xxl-12">
                <div class="row">
                    <div class="col-xl-5">

                        <div class="card">
                            <div class="card-header card-body table-border-style">
                                <h5><?php echo e(__("Today's Not Clock In")); ?></h5>
                            </div>
                            <div class="card-body" style="height: 324px; overflow:auto">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th><?php echo e(__('Name')); ?></th>
                                                <th><?php echo e(__('Status')); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            <?php $__currentLoopData = $notClockIns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notClockIn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($notClockIn->name); ?></td>
                                                    <td><span class="absent-btn"><?php echo e(__('Absent')); ?></span></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xl-7">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-9">
                                        <h5><?php echo e(__('Calendar')); ?></h5>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group mb-0">
                                            <label for=""></label>
                                            <?php if(isset($setting['is_enabled']) && $setting['is_enabled'] == 'on'): ?>
                                                <select class="form-control" name="calender_type" id="calender_type"
                                                    onchange="get_data()">
                                                    <option value="google_calender"><?php echo e(__('Google Calender')); ?></option>
                                                    <option value="local_calender" selected="true">
                                                        <?php echo e(__('Local Calender')); ?></option>
                                                </select>
                                            <?php endif; ?>
                                            <input type="hidden" id="path_admin" value="<?php echo e(url('/')); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body card-635 ">
                                <div id='calendar' class='calendar'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if(!empty($employeesData)): ?>
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header card-body table-border-style">
                            <h5><?php echo e(__('Employee List')); ?></h5>
                        </div>
                        <div class="card-body" style="height: 270px; overflow:auto">
                            <div class="table-responsive">
                                <table class="table" id="pc-dt-simple">
                                    <thead>
                                        <tr>
                                            <th><?php echo e(__('Name')); ?></th>
                                            <th><?php echo e(__('Email')); ?></th>
                                            <th><?php echo e(__('Branch')); ?></th>
                                            <th><?php echo e(__('Department')); ?></th>
                                            <th><?php echo e(__('Designation')); ?>

                                            </th>
                                            <th><?php echo e(__('Date Of Joining')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $employeesData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>

                                                <td><?php echo e($employee->name); ?></td>
                                                <td><?php echo e($employee->email); ?></td>
                                                <td>
                                                    <?php echo e(!empty(\Auth::user()->getBranch($employee->branch_id)) ? \Auth::user()->getBranch($employee->branch_id)->name : ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e(!empty(\Auth::user()->getDepartment($employee->department_id)) ? \Auth::user()->getDepartment($employee->department_id)->name : ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e(!empty(\Auth::user()->getDesignation($employee->designation_id)) ? \Auth::user()->getDesignation($employee->designation_id)->name : ''); ?>

                                                </td>
                                                <td>
                                                    <?php echo e(\Auth::user()->dateFormat($employee->company_doj)); ?>

                                                </td>


                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        


        
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <h5><?php echo e(__('Announcement List')); ?></h5>
                </div>
                <div class="card-body" style="height: 270px; overflow:auto">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Title')); ?></th>
                                    <th><?php echo e(__('Start Date')); ?></th>
                                    <th><?php echo e(__('End Date')); ?></th>
                                    <th><?php echo e(__('Description')); ?></th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($announcement->title); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($announcement->start_date)); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($announcement->end_date)); ?></td>
                                        <td><?php echo e($announcement->description); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        


        
        <?php if(!empty($employeesBirthdayList)): ?>
            <div class="col-xxl-4">
                <div class="card">
                    <div class="card-header card-body table-border-style">
                        <h5><?php echo e(__('Upcomming Birthdays List')); ?></h5>
                    </div>
                    <div class="card-body" style="height: 400px; overflow:auto">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-left"><?php echo e(__('Name')); ?></th>
                                        <th class="text-left"><?php echo e(__('Date')); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    <?php $__currentLoopData = $employeesBirthdayList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employeesBirthdayListRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="text-left">
                                                <img src="<?php echo e(!empty($employeesBirthdayListRow->employee_profile_image) ? $employeesBirthdayListRow->employee_profile_image : asset(Storage::url('uploads/avatar')) . '/avatar.png'); ?>"
                                                    class="rounded-circle" style="height: 40px; width: 40px;"
                                                    onerror="this.onerror=null; this.src='<?php echo e(asset(Storage::url('uploads/avatar'))); ?>/avatar.png';">
                                                &nbsp; <?php echo e($employeesBirthdayListRow->name); ?>

                                            </td>

                                            <td class="text-left">
                                                <?php echo e($employeesBirthdayListRow->getRaworiginal('dob') == date('Y-m-d') ? 'Today' : date('d-M', strtotime($employeesBirthdayListRow->getRaworiginal('dob')))); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        

        
        <?php if(!empty($workAnniversaryList)): ?>
            <div class="col-xxl-4">
                <div class="card">
                    <div class="card-header card-body table-border-style">
                        <h5><?php echo e(__("Today's Work Anniversary List")); ?></h5>
                    </div>
                    <div class="card-body" style="height: 400px; overflow:auto">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: left"><?php echo e(__('Name')); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    <?php $__currentLoopData = $workAnniversaryList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $workAnniversaryListRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td style="text-align: left">
                                                <img src="<?php echo e(!empty($workAnniversaryListRow->employee_profile_image) ? $workAnniversaryListRow->employee_profile_image : asset(Storage::url('uploads/avatar')) . '/avatar.png'); ?>"
                                                    class="rounded-circle" style="height: 40px; width: 40px;"
                                                    onerror="this.onerror=null; this.src='<?php echo e(asset(Storage::url('uploads/avatar'))); ?>/avatar.png';">
                                                &nbsp; <?php echo e($workAnniversaryListRow->name); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        


        
        <?php if(!empty($marriageAnniversaryList)): ?>
            <div class="col-xxl-4">
                <div class="card">
                    <div class="card-header card-body table-border-style">
                        <h5><?php echo e(__("Today's Marriage Anniversary List")); ?></h5>
                    </div>
                    <div class="card-body" style="height: 400px; overflow:auto">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th style="text-align: left"><?php echo e(__('Name')); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    <?php $__currentLoopData = $marriageAnniversaryList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $marriageAnniversaryListRow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td style="text-align: left">
                                                <img src="<?php echo e(!empty($marriageAnniversaryListRow->employee_profile_image) ? $marriageAnniversaryListRow->employee_profile_image : asset(Storage::url('uploads/avatar')) . '/avatar.png'); ?>"
                                                    class="rounded-circle" style="height: 40px; width: 40px;"
                                                    onerror="this.onerror=null; this.src='<?php echo e(asset(Storage::url('uploads/avatar'))); ?>/avatar.png';">
                                                &nbsp; <?php echo e($marriageAnniversaryListRow->name); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        

    </div>
<?php $__env->stopSection(); ?>



<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('assets/js/plugins/main.min.js')); ?>"></script>

    
    <script type="text/javascript">
        $(document).ready(function() {
            get_data();
        });

        function get_data() {
            var calender_type = $('#calender_type :selected').val();

            $('#calendar').removeClass('local_calender');
            $('#calendar').removeClass('google_calender');
            if (calender_type == undefined) {
                calender_type = 'local_calender';
            }
            $('#calendar').addClass(calender_type);

            $.ajax({
                url: $("#path_admin").val() + "/event/get_event_data",
                method: "POST",
                data: {
                    "_token": "<?php echo e(csrf_token()); ?>",
                    'calender_type': calender_type
                },
                success: function(data) {

                    var etitle;
                    var etype;
                    var etypeclass;
                    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        buttonText: {
                            timeGridDay: "<?php echo e(__('Day')); ?>",
                            timeGridWeek: "<?php echo e(__('Week')); ?>",
                            dayGridMonth: "<?php echo e(__('Month')); ?>"
                        },
                        themeSystem: 'bootstrap',
                        slotDuration: '00:10:00',
                        navLinks: true,
                        droppable: true,
                        selectable: true,
                        selectMirror: true,
                        editable: true,
                        dayMaxEvents: true,
                        handleWindowResize: true,
                        events: data,
                    });
                    calendar.render();
                }
            });
        };
    </script>
    
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dashboard/dashboard.blade.php ENDPATH**/ ?>