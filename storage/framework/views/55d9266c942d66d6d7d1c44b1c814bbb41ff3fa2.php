<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage My Visit')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('My Visit')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>
    <a href="#" data-url="<?php echo e(route('my_visit.create')); ?>" data-ajax-popup="true" data-title="<?php echo e(__('Create visit')); ?>"
        data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
        data-bs-original-title="<?php echo e(__('Create')); ?>">
        <i class="ti ti-plus"></i>
    </a>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Category Type')); ?></li>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php
            $pendingCount = $data->where('status', 'Pending')->count() ?? 0;
            $approvedCount = $data->where('status', 'Approved')->count() ?? 0;
            $rejectedCount = $data->where('status', 'Reject')->count() ?? 0;
        ?>

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
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">

                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Id')); ?></th>
                                    <!-- <th><?php echo e(__('Employee Name')); ?></th> -->
                                    <th><?php echo e(__('Visit type')); ?></th>
                                    <th><?php echo e(__('Visit name')); ?></th>
                                    <th><?php echo e(__('From date')); ?></th>
                                    <th><?php echo e(__('To date')); ?></th>
                                    <th><?php echo e(__('Tier')); ?></th>
                                    <th><?php echo e(__('City')); ?></th>
                                    <th><?php echo e(__('Description')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Approved / Rejected By')); ?></th>
                                    <th width="250px"><?php echo e(__('Action')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        
                                        <td>
                                            <?php if($row->visit_type == 1): ?>
                                                Local Visit
                                            <?php else: ?>
                                                Out of City Visit
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($row->visit_name); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($row->form_date)); ?></td>
                                        <td><?php echo e(\Auth::user()->dateFormat($row->to_date)); ?></td>
                                        <td>
                                            <?php if(!empty($row->tierData)): ?>
                                                <?php echo e($row->tierData->name); ?>

                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if(!empty($row->cityData)): ?>
                                                <?php echo e($row->cityData->name); ?>

                                            <?php endif; ?>
                                        </td>

                                        <td><?php echo e(substr($row->description, 0, 80) . '..'); ?></td>
                                        <td>
                                            <?php if($row->status == 'Pending'): ?>
                                                <div class="badge bg-warning p-2 px-3 rounded status-badge5">Pending </div>
                                            <?php endif; ?>
                                            <?php if($row->status == 'Reject'): ?>
                                                <div class="badge bg-danger p-2 px-3 rounded status-badge5">Rejected </div>
                                            <?php endif; ?>
                                            <?php if($row->status == 'Approved'): ?>
                                                <div class="badge bg-success p-2 px-3 rounded status-badge5">Approved</div>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e(findEmployeeByUser($row->status_changed_by)); ?></td>
                                        <td class="Action">
                                            <span>
                                                <?php if((Gate::check('Edit Visit Request') || \Auth::user()->type == 'employee') && $row->status != 'Approved'): ?>
                                                    <div class="action-btn btn-info ms-2">
                                                        <a href="#" data-size="md"
                                                            data-url="<?php echo e(URL::to('my_visit/' . $row->id . '/edit')); ?>"
                                                            data-ajax-popup="true" data-title="<?php echo e(__('Edit Visit')); ?>"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="<?php echo e(__('Edit visit')); ?>"><i
                                                                class="ti ti-pencil text-white"></i></a>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if((Gate::check('Delete Visit Request') || Auth::user()->type == 'employee') && $row->status != 'Approved'): ?>
                                                    <div class="action-btn bg-danger ms-2">
                                                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['my_visit.destroy', $row->id]]); ?>

                                                        <a href="#!"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="<?php echo e(__('Delete')); ?>">
                                                            <span class="text-white"> <i class="ti ti-trash"></i></span></a>
                                                        <?php echo Form::close(); ?>

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/Myvisit/index.blade.php ENDPATH**/ ?>