@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Employee') }}
@endsection



@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>

    <li class="breadcrumb-item">{{ __('Employee') }}</li>
@endsection



@section('action-button')
    <a href="#" id="export_employees_btn" data-bs-toggle="tooltip" data-bs-placement="top"
        data-bs-original-title="{{ __('Export') }}" class="btn btn-sm btn-primary">

        <i class="ti ti-file-export"></i>

    </a>



    <a href="#" data-url="{{ route('employee.file.import') }}" data-ajax-popup="true"
        data-title="{{ __('Import  Employee CSV File') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
        data-bs-original-title="{{ __('Import') }}">

        <i class="ti ti-file"></i>

    </a>

    @can('Create Employee')
        <a href="{{ route('employee.create') }}" data-title="{{ __('Create New Employee') }}" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">

            <i class="ti ti-plus"></i>

        </a>
    @endcan
@endsection



@section('content')
    <div class="row">

        <div class="col-xl-12">

            <div class="card">

                <div class="card-header card-body table-border-style">

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="status_filter" class="form-label">{{ __('Filter by Status') }}</label>
                            <select name="status_filter" id="status_filter" class="form-control">
                                <option value="all" selected>{{ __('All') }}</option>
                                <option value="Active">{{ __('Active') }}</option>
                                <option value="Terminated">{{ __('Terminated') }}</option>
                                {{-- <option value="Deceased">{{ __('Deceased') }}</option> --}}

                                <option value="Resigned">{{ __('Resigned') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">

                        <table class="table data-table" id="pc-dt-simple12">

                            <thead>

                                <tr>

                                    <th>{{ __('Employee ID') }}</th>

                                    <th>{{ __('Name') }}</th>

                                    <th>{{ __('Email') }}</th>

                                    <th>{{ __('Branch') }}</th>

                                    <th>{{ __('Department') }}</th>

                                    <th>{{ __('Designation') }}</th>

                                    <th>{{ __('Pay Slab') }}</th>

                                    <th>{{ __('Salary') }}</th>

                                    <th>{{ __('Date Of Joining') }}</th>

                                    <th>{{ __('Status') }}</th>

                                    <th width="200px">{{ __('Action') }}</th>

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
@endsection

@push('script-page')
    <script type="text/javascript">
        $(document).ready(function () {

            var table = $('.data-table').DataTable({

                processing: true,

                serverSide: true,

                language: {

                    processing: '<i></i><span class="text-primary spinner-border"></span> '
                },

                ajax: {
                    url: "{{ route('employee.index') }}",
                    type: 'GET',
                    data: function (d) {
                        var statusFilter = $('#status_filter').val() || 'all';

                        if (statusFilter) {
                            d.status_filter = [statusFilter];
                        } else {
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
                        name: 'branch_id',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'department_id',
                        name: 'department_id',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'designation_id',
                        name: 'designation_id',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'playslab',
                        name: 'playslab',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'salary',
                        name: 'salary',
                        className: 'text-right',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'company_doj',
                        name: 'company_doj',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'delete_employe',
                        name: 'delete_employe',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'edit_employe',
                        name: 'edit_employe',
                        orderable: false,
                        searchable: false
                    },

                ]

            });

            // Handle status filter change (single selection)
            $('#status_filter').on('change', function () {
                var selectedValue = $(this).val() || 'all';

                if (!selectedValue) {
                    $(this).val('all').trigger('change');
                    table.ajax.reload();
                    return;
                }

                table.ajax.reload();
            });

            // Handle export button click - export with current filter
            $('#export_employees_btn').on('click', function (e) {
                e.preventDefault();

                var tableInfo = table.page.info();
                var totalRecords = tableInfo.recordsTotal;

                if (totalRecords === 0) {
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
                var exportUrl = "{{ route('employee.export') }}?status_filter=" + encodeURIComponent(
                    statusFilter);
                window.location.href = exportUrl;
            });

        });
    </script>
@endpush