<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage My Visit Request')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('My visit')); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Category Type')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">

        <?php
            $pendingCount = $data->where('status', 'Pending')->count();
            $approvedCount = $data->where('status', 'Approved')->count();
            $rejectedCount = $data->where('status', 'Reject')->count();
        ?>
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
                                <h4 class="m-0 text-warning"><?php echo e($pendingCount); ?></h4>
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
                                <h4 class="m-0 text-success"><?php echo e($approvedCount); ?></h4>
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
                                <h4 class="m-0 text-danger"><?php echo e($rejectedCount); ?></h4>
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
                            <table class="table myvisit-datatable" id="pc-dt-simple12">
                                <thead>
                                    <tr>
                                        <th><?php echo e(__('Employee Id')); ?></th>
                                        <th><?php echo e(__('Employee Name')); ?></th>
                                        <th><?php echo e(__('Visit type')); ?></th>
                                        <th><?php echo e(__('Visit name')); ?></th>
                                        <th><?php echo e(__('Form date')); ?></th>
                                        <th><?php echo e(__('To date')); ?></th>
                                        <th><?php echo e(__('Tier')); ?></th>
                                        <th><?php echo e(__('City')); ?></th>
                                        <th><?php echo e(__('Descraption')); ?></th>
                                        <th><?php echo e(__('Status')); ?></th>
                                        <th><?php echo e(__('Approved / Rejected By')); ?></th>
                                        <th width="250px"><?php echo e(__('Action')); ?></th>
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
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('script-page'); ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script>
        // Datatable
        $(document).ready(function() {
            $('.myvisit-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "<?php echo e(route('my_visit_request')); ?>",
                columns: [{
                        data: 'employee_id',
                        name: 'employee_id'
                    },
                    {
                        data: 'employee',
                        name: 'employee'
                    },
                    {
                        data: 'visit_type',
                        name: 'visit_type'
                    },
                    {
                        data: 'visit_name',
                        name: 'visit_name'
                    },
                    {
                        data: 'from_date',
                        name: 'from_date'
                    },
                    {
                        data: 'to_date',
                        name: 'to_date'
                    },
                    {
                        data: 'tier',
                        name: 'tier'
                    },
                    {
                        data: 'city',
                        name: 'city'
                    },
                    {
                        data: 'descraption',
                        name: 'descraption'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'approved_rejected_by',
                        name: 'approved_rejected_by'
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
        // End

        $(document).ready(function() {
            $("#commonModal").on("click", 'form input[type=submit]', function(event) {
                $("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
                $(this).attr("clicked", "true");
            });

            $("#commonModal").on("submit", 'form', function(event) {
                event.preventDefault();
                var val = $("input[type=submit][clicked=true]").val();
                var form_cust = this;
                var formData = new FormData(form_cust);
                var url = $(this).attr('action');
                var type = "POST";
                formData.append('visit_status', val);
                $.ajax({
                    context: 'commonModal',
                    type: type,
                    url: url,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status == 1) {
                            show_toastr('Success', response.message, 'success');
                            location.reload();
                        }
                        if (response.status == 0) {
                            $.each(response.message, function(key, value) {
                                show_toastr('Error', value, 'error');
                            });
                        }
                    }
                })
                return false;
            });
        });

        function getGradeType(selectObjectValue) {
            $('#commuting_type').html('');
            $('#commuting_type').append('<option value="">Select commuting type</option>');

            getCities(selectObjectValue)

            getExpensesdData(selectObjectValue)
            $.ajax({
                type: "GET",
                url: "<?php echo e(url('get_commutingData')); ?>",
                data: {
                    gradetype: selectObjectValue
                },
                cache: true,
                success: function(data) {
                    $.each(data, function(key, value) {
                        $('#commuting_type').append($("<option value=" + value.id + ">" + value.name +
                            "</option>"));
                    });
                }
            });
        }

        //function cities
        function getCities(CittData) {
            $('#city_id').html('');
            $('#city_id').append('<option value="">Select City</option>');

            $.ajax({
                type: "GET",
                url: "<?php echo e(url('get_getCitiesData')); ?>",
                data: {
                    getvisit_data: CittData
                },
                cache: true,
                success: function(data) {
                    console.log(data);
                    $.each(data, function(key, value) {
                        $('#city_id').append($("<option value=" + value.id + ">" + value.name +
                            "</option>"));
                    });
                }
            });
        }

        function getExpensesdData(ExpensesdData) {

            $('#getExpensesdData').html('');
            $('#getExpensesdData').append('<option value="">Select City</option>');

            $.ajax({
                type: "GET",
                url: "<?php echo e(url('get_getExpensesdData')); ?>",
                data: {
                    getvisit_data: ExpensesdData
                },
                cache: true,
                success: function(data) {
                    console.log(data);
                    $.each(data, function(key, value) {
                        $('#getExpensesdData').append($("<option value=" + value.id + ">" + value.name +
                            "</option>"));
                    });
                }
            });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/Myvisit/my_visit_request.blade.php ENDPATH**/ ?>