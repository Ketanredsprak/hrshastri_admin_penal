@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Employee') }}
@endsection



@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>

    <li class="breadcrumb-item">{{ __('Employee') }}</li>
@endsection



@section('action-button')
    <a href="{{ route('employee.export') }}" data-bs-toggle="tooltip" data-bs-placement="top"
        data-bs-original-title="{{ __('Export') }}" class="btn btn-sm btn-primary">

        <i class="ti ti-file-export"></i>

    </a>



    <a href="#" data-url="{{ route('employee.file.import') }}" data-ajax-popup="true"
        data-title="{{ __('Import  Employee CSV File') }}" data-bs-toggle="tooltip" title=""
        class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Import') }}">

        <i class="ti ti-file"></i>

    </a>

    @can('Create Employee')
        <a href="{{ route('employee.create') }}" data-title="{{ __('Create New Employee') }}" data-bs-toggle="tooltip"
            title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">

            <i class="ti ti-plus"></i>

        </a>
    @endcan
@endsection



@section('content')
    <div class="row">

        <div class="col-xl-12">

            <div class="card">

                <div class="card-header card-body table-border-style">

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

                                    @if (Gate::check('Edit Employee') || Gate::check('Delete Employee'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif

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
        $(function() {



            var table = $('.data-table').DataTable({

                processing: true,

                serverSide: true,

                language: {

                    processing: '<i></i><span class="text-primary spinner-border"></span> '
                },



                ajax: "{{ route('employee.index') }}",



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



        });
    </script>
@endsection
