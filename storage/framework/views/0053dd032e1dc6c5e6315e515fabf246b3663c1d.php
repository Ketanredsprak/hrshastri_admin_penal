<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Employee')); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>

    <li class="breadcrumb-item"><?php echo e(__('Employee')); ?></li>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('action-button'); ?>
    <a href="#" id="export_employees_btn" data-bs-toggle="tooltip" data-bs-placement="top"
        data-bs-original-title="<?php echo e(__('Export')); ?>" class="btn btn-sm btn-primary">

        <i class="ti ti-file-export"></i>

    </a>



    <a href="#" data-url="<?php echo e(route('employee.file.import')); ?>" data-ajax-popup="true"
        data-title="<?php echo e(__('Import  Employee CSV File')); ?>" data-bs-toggle="tooltip" title=""
        class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Import')); ?>">

        <i class="ti ti-file"></i>

    </a>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Employee')): ?>
        <a href="<?php echo e(route('employee.create')); ?>" data-title="<?php echo e(__('Create New Employee')); ?>" data-bs-toggle="tooltip"
            title="" class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Create')); ?>">

            <i class="ti ti-plus"></i>

        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
    <div class="row">

        <div class="col-xl-12">

            <div class="card">

                <div class="card-header card-body table-border-style">

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="status_filter" class="form-label"><?php echo e(__('Filter by Status')); ?></label>
                            <select name="status_filter" id="status_filter" class="form-control">
                                <option value="all" selected><?php echo e(__('All')); ?></option>
                                <option value="Active"><?php echo e(__('Active')); ?></option>
                                <option value="Terminated"><?php echo e(__('Terminated')); ?></option>
                                

                                <option value="Resigned"><?php echo e(__('Resigned')); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">

                        <table class="table data-table" id="pc-dt-simple12">

                            <thead>

                                <tr>

                                    <th><?php echo e(__('Employee ID')); ?></th>

                                    <th><?php echo e(__('Name')); ?></th>

                                    <th><?php echo e(__('Email')); ?></th>

                                    <th><?php echo e(__('Branch')); ?></th>

                                    <th><?php echo e(__('Department')); ?></th>

                                    <th><?php echo e(__('Designation')); ?></th>

                                    <th><?php echo e(__('Pay Slab')); ?></th>

                                    <th><?php echo e(__('Salary')); ?></th>

                                    <th><?php echo e(__('Date Of Joining')); ?></th>





                                    <th><?php echo e(__('Status')); ?></th>

                                    <?php if(Gate::check('Edit Employee') || Gate::check('Delete Employee')): ?>
                                        <th width="200px"><?php echo e(__('Action')); ?></th>
                                    <?php endif; ?>

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



    <script type="text/javascript">
        $(document).ready(function() {

            // Initialize select2 for status filter (same pattern as create.blade.php)
            //   $('#status_filter').select2({
            //       placeholder: '<?php echo e(__('Select Status')); ?>',
            //       allowClear: false
            //   });

            // Set default selection to "all"
            $('#status_filter').val('all').trigger('change');

            var table = $('.data-table').DataTable({

                processing: true,

                serverSide: true,

                language: {

                    processing: '<i></i><span class="text-primary spinner-border"></span> '
                },

                ajax: {
                    url: "<?php echo e(route('employee.index')); ?>",
                    type: 'GET',
                    data: function(d) {
                        // Get the current filter value (single selection now)
                        var statusFilter = $('#status_filter').val() || 'all';

                        // Always send status_filter parameter as array for backend compatibility
                        if (statusFilter) {
                            d.status_filter = [statusFilter];
                        } else {
                            // Default to "all" if nothing is selected
                            d.status_filter = ['all'];
                        }
                    }
                },



                columns: [

                    {
                        data: 'employee_id',
                        name: 'employee_id',
                        orderable: true,
                        searchable: true
                    },

                    {
                        data: 'name',
                        name: 'name'
                    },

                    {
                        data: 'email',
                        name: 'email'
                    },

                    {
                        data: 'branch_id',
                        name: 'branch_id'
                    },

                    {
                        data: 'department_id',
                        name: 'department_id'
                    },

                    {
                        data: 'designation_id',
                        name: 'designation_id'
                    },

                    {
                        data: 'playslab',
                        name: 'playslab'
                    },

                    {
                        data: 'salary',
                        name: 'salary',
                        className: 'text-right'
                    },

                    {
                        data: 'company_doj',
                        name: 'company_doj'
                    },

                    {
                        data: 'delete_employe',
                        name: 'delete_employe'
                    },

                    {
                        data: 'edit_employe',
                        name: 'edit_employe'
                    },

                ]

            });

            // Handle status filter change (single selection)
            $('#status_filter').on('change', function() {
                var selectedValue = $(this).val() || 'all';

                // If nothing is selected, default to "all"
                if (!selectedValue) {
                    $(this).val('all').trigger('change');
                    table.ajax.reload(); // Reset pagination to first page
                    return;
                }

                // Reload datatable with new filter and reset pagination to first page
                table.ajax.reload(); // Reset pagination when filter changes
            });

            // Handle export button click - export with current filter
            $('#export_employees_btn').on('click', function(e) {
                e.preventDefault();

                // Check if datatable has any data
                var tableInfo = table.page.info();
                var totalRecords = tableInfo.recordsTotal;

                if (totalRecords === 0) {
                    // Show error message if no data
                    if (typeof show_toastr !== 'undefined') {
                        show_toastr('Error', 'No data to export', 'error');
                    } else if (typeof toastr !== 'undefined') {
                        toastr.error('No data to export');
                    } else {
                        alert('No data to export');
                    }
                    return;
                }

                var statusFilter = $('#status_filter').val() || 'all';
                var exportUrl = "<?php echo e(route('employee.export')); ?>?status_filter=" + encodeURIComponent(
                    statusFilter);
                window.location.href = exportUrl;
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/employee/index.blade.php ENDPATH**/ ?>