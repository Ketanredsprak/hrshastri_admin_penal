<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Monthly Attendance')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Manage Monthly Attendance Report')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('action-button'); ?>
    <a href="#" class="btn btn-sm btn-primary" onclick="saveAsPDF()" data-bs-toggle="tooltip" title="<?php echo e(__('Download')); ?>"
        data-original-title="<?php echo e(__('Download')); ?>">
        <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
    </a>

    <?php
        $emp = isset($_GET['employee_id']) && !empty($_GET['employee_id']) ? $_GET['employee_id'] : [0];
        $employees = implode(', ', $emp);
        $requestStartDate = $requestStartDate ?? date('Y-m-01', strtotime('first day of this month'));
    ?>

    <a href="<?php echo e(route('report.attendance', [$requestStartDate, $requestEndDate ?? date('Y-m-d', strtotime('last day of this month', strtotime($requestStartDate))), isset($_GET['branch_id']) && !empty($_GET['branch_id']) ? $_GET['branch_id'] : 0, isset($_GET['department']) && !empty($_GET['department']) ? $_GET['department'] : 0, implode(', ', $requestEmployeeId)])); ?>"
        class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="" data-bs-original-title="Export">
        <span class="btn-inner--icon"><i class="ti ti-file-export "></i></span>
    </a>
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
                    format: 'A2'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class=" mt-2 " id="" style="">
                <div class="card">
                    <div class="card-body">
                        <?php echo e(Form::open(['route' => ['report.monthly.attendance'], 'method' => 'get', 'id' => 'report_monthly_attendance'])); ?>


                        <div class="row align-items-center">
                            <div class="col-sm-10">
                                <div class="row ">
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('start_date', __('Start Date'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::date('start_date', $requestStartDate, ['class' => 'month-btn form-control current_date'])); ?>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('end_date', __('End Date'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::date('end_date', $requestEndDate ?? date('Y-m-d', strtotime('last day of this month', strtotime($requestStartDate))), ['class' => 'month-btn form-control current_date'])); ?>

                                        </div>
                                    </div>
                                    <!-- <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('month', __(' Month'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::month('month', $requestMonth ?? '', ['class' => 'month-btn form-control current_date', 'autocomplete' => 'off', 'placeholder' => 'Select month'])); ?>

                                        </div>
                                    </div> -->
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            <?php echo e(Form::label('branch', __('Branch'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::select('branch_id', $branch, $requestBranchId ?? '', ['class' => 'form-control select branch_id', 'id' => 'branch_id'])); ?>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box">
                                            <div class="btn-box" id="department_id">
                                                <?php echo e(Form::label('department', __('Department'), ['class' => 'form-label'])); ?>

                                                <?php echo e(Form::select('department', $department, $requestDepartmentId ?? '', ['class' => 'form-control select department_id', 'id' => 'department'])); ?>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="btn-box" id="employee_div">
                                            <?php echo e(Form::label('employee', __('Employee'), ['class' => 'form-label'])); ?>

                                            <select class="form-control select" name="employee_id[]" id="employee_id"
                                                placeholder="Select Employee">
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-2 text-right pt-4">
                                <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('report_monthly_attendance').submit(); return false;"
                                    data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="<?php echo e(route('report.monthly.attendance')); ?>" class="btn btn-sm btn-danger"
                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Reset">
                                    <span class="btn-inner--icon"><i class="ti ti-refresh  text-white-off "></i></span>
                                </a>
                            </div>
                        </div>

                        <?php echo e(Form::close()); ?>

                    </div>
                </div>
            </div>
        </div>

        <div id="printableArea">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-2"><span class="text-success"><i
                                            class="badge bg-success p-2  rounded"><?php echo e(__('P')); ?></i>
                                        <?php echo e(__('Present')); ?></span></div>
                                <div class="col-2"><span class="text-danger"><i
                                            class="badge bg-danger p-2  rounded"><?php echo e(__('A')); ?></i>
                                        <?php echo e(__('Absent')); ?></span></div>
                                <div class="col-2"><span class="text-info"><i
                                            class="badge bg-info p-2  rounded"><?php echo e(__('L')); ?></i>
                                        <?php echo e(__('Leave')); ?></span></div>
                                <div class="col-2"><span class="text-secondary"><i
                                            class="badge bg-secondary p-2  rounded"><?php echo e(__('HD')); ?></i>
                                        <?php echo e(__('Half Day')); ?></span></div>
                                <div class="col-2"><span class="text-warning"><i
                                            class="badge bg-warning p-2  rounded"><?php echo e(__('H')); ?></i>
                                        <?php echo e(__('Holiday')); ?></span></div>
                                <div class="col-2"><span class="text-dark"><i
                                            class="badge bg-dark p-2  rounded"><?php echo e(__('WO')); ?></i>
                                        <?php echo e(__('Week Off')); ?></span></div>
                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive attendance-table-responsive">
                                
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th class="active"><?php echo e(__('Employee Id')); ?></th>
                                            <th class="active"><?php echo e(__('Name')); ?></th>
                                            <th class="active"><?php echo e(__('Branch')); ?></th>
                                            <th class="active"><?php echo e(__('Designation')); ?></th>
                                            <?php $__currentLoopData = $monthDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <th><?php echo e(date('d', strtotime($date))); ?></th>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tr>
                                    </thead>
                                    <tbody id="employee-attendance-tbody">
                                        <?php echo $__env->make('report.attendance_rows', ['employeesAttendance' => $employeesAttendance], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    </tbody>
                                    
                                </table>
                                <div class="page-load-status">
                                    <div class="loading" style="display:none;">Loading...</div>
                                    <p class="no-data" style="display:none;">End of content</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
<script>
    let loadCount = 1; 
    let isLoading = false; 
    let noMoreData = false;
    $(window).on('scroll', function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            if (isLoading || noMoreData) return;

            isLoading = true; 
            $('.loading').show();
            var reportUrl = "<?php echo e(route('report.monthly.attendance')); ?>"; // Base URL
            var start_date = $('#report_monthly_attendance input[name="start_date"]').val() || '';
            var end_date = $('#report_monthly_attendance input[name="end_date"]').val() || '';
            var branchId = $('#branch_id').val() || '';
            var departmentId = $('#department').val() || '';
            var employeeIds = $('#employee_id').val() || [];

            var nextPageUrl = reportUrl + '?page=' + (++loadCount) + '&start_date=' + start_date + '&end_date=' + end_date +
                '&branch_id=' + branchId + '&department_id=' + departmentId + 
                '&employee_id[]=' + employeeIds.join(',');

            $.ajax({
                url: nextPageUrl,
                method: 'GET',
                success: function(response) {
                    $('.loading').hide();
                    if (response.trim() != '') {
                        $('#employee-attendance-tbody').append(response);
                    } else {
                        noMoreData = true;
                        $('.no-data').show();

                    }
                },
                error: function() {
                    $('.loading').hide();
                    console.log('Manual AJAX request failed.');
                },
                complete: function() {
                    $('.loading').hide();
                    isLoading = false; 
                }
            });
        }
    });
    $(document).ready(function() {
        var b_id = $('#branch_id').val();
        $('#start_date').on('change', function() {
            var startDate = $(this).val();

            if (startDate) {
                var startDateObj = new Date(startDate);
                startDateObj.setMonth(startDateObj.getMonth() + 1);
                var endDate = startDateObj.getFullYear() + '-' + 
                            ('0' + (startDateObj.getMonth() + 1)).slice(-2) + '-' + 
                            ('0' + startDateObj.getDate()).slice(-2);
                $('#end_date').val(endDate);
            }
        });
        $('#end_date').on('change', function() {
            var startDate = $('#start_date').val();
            var endDate = $(this).val();

            if (startDate && endDate) {
                var startDateObj = new Date(startDate);
                var endDateObj = new Date(endDate);
                var timeDifference = endDateObj - startDateObj;
                var dayDifference = timeDifference / (1000 * 3600 * 24);
                if (dayDifference > 31) {
                    alert('The difference between start and end date is more than 31 days.');
                    startDateObj.setMonth(startDateObj.getMonth() + 1);
                    var adjustedEndDate = startDateObj.getFullYear() + '-' + 
                                        ('0' + (startDateObj.getMonth() + 1)).slice(-2) + '-' + 
                                        ('0' + startDateObj.getDate()).slice(-2);
                    $('#end_date').val(adjustedEndDate);
                }
            }
        });
        // getDepartment(b_id);
    });
    $(document).on('change', 'select[name=branch_id]', function() {
        var branch_id = $(this).val();

        getDepartment(branch_id);
    });

    <?php if(!empty($requestBranchId)): ?>
        getDepartment({
            {
                $requestBranchId
            }
        }, {
            {
                $requestDepartmentId ?? 0
            }
        });
    <?php endif; ?>


    var selectedEmployeesIds = [];
    var requestDepartmentId = "0";
    <?php if(!empty($requestEmployeeId)): ?>
        selectedEmployeesIds = <?php echo json_encode($requestEmployeeId); ?>;
    <?php endif; ?>

    <?php if(!empty($requestDepartmentId)): ?>
        requestDepartmentId = <?php echo json_encode($requestEmployeeId); ?>;
    <?php endif; ?>

    getEmployee(requestDepartmentId, selectedEmployeesIds);

    function getDepartment(bid, selectDepartmentId = 0) {

        $.ajax({
            url: "<?php echo e(route('monthly.getdepartment')); ?>",
            type: 'POST',
            data: {
                "branch_id": bid,
                "_token": "<?php echo e(csrf_token()); ?>",
            },
            success: function(data) {

                $('.department_id').empty();
                var emp_selct = `<select class="department_id form-control multi-select" id="choices-multiple" multiple="" required="required" name="department_id[]">
            </select>`;
                $('.department_div').html(emp_selct);

                $('.department_id').append('<option value="0"> <?php echo e(__('All ')); ?> </option>');
                $.each(data, function(key, value) {
                    var selected = "";
                    if (selectDepartmentId = key) {
                        selected = "selected";
                    }
                    $('.department_id').append('<option value="' + key + '" ' + selected + '>' +
                        value +
                        '</option>');
                });
                new Choices('#choices-multiple', {
                    removeItemButton: true,
                });
            }
        });
    }

    $(document).on('change', '.department_id', function() {
        var department_id = $(this).val();
        getEmployee(department_id);
    });

    function getEmployee(did, selectedEmployeesIds = []) {
        //    function getEmployee(did) {

        $.ajax({
            url: "<?php echo e(route('monthly.getemployee')); ?>",
            type: 'POST',
            data: {
                "department_id": did,
                "_token": "<?php echo e(csrf_token()); ?>",
            },
            success: function(data) {

                $('#employee_id').empty();

                $("#employee_div").html('');
                // $('#employee_div').append('<select class="form-control" id="employee_id" name="employee_id[]"  multiĒple></select>');
                $('#employee_div').append(
                    '<label for="employee" class="form-label"><?php echo e(__('Employee ')); ?></label><select class="form-control" id="employee_id" name="employee_id[]"  multiple></select>'
                );

                var result = jQuery.inArray(0, selectedEmployeesIds);

                $('#employee_id').append('<option value=""><?php echo e(__('Select Employee ')); ?></option>');

                if (result !== -1) {
                    $('#employee_id').append(
                        '<option value="0" selected> <?php echo e(__('All Employee ')); ?> </option>');
                } else {
                    $('#employee_id').append('<option value="0"> <?php echo e(__('All Employee ')); ?> </option>');
                }

                $.each(data, function(key, value) {
                    var selected = "";

                    var result = jQuery.inArray(key, selectedEmployeesIds);

                    if (result !== -1) {
                        selected = "selected";
                    }
                    $('#employee_id').append('<option value="' + key + '" ' + selected + '>' +
                        value + '</option>');
                });

                var multipleCancelButton = new Choices('#employee_id', {
                    removeItemButton: true,
                });
            }
        });

        //   selectedEmployeesIds = [];
    }
    
</script>

<script>
    $(document).ready(function() {
        //   var now = new Date();
        //   var month = (now.getMonth() + 1);
        //   var day = now.getDate();
        //   if (month < 10) month = "0" + month;
        //   if (day < 10) day = "0" + day;
        //   var today = now.getFullYear() + '-' + month + '-' + day;
        //   $('.current_date').val(today);
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/report/monthlyAttendance.blade.php ENDPATH**/ ?>