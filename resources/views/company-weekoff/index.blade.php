<label for=""></label>@extends('layouts.admin')

@section('page-title')
    {{ __('Week Off') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Week Off') }}</li>
@endsection

@section('header-section')
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            {{-- {{ Form::open(['url' => route('company-weekoff.store'), 'method' => 'post', 'id' => 'company-weekoff_form', 'enctype' => 'multipart/form-data']) }} --}}
            <div class="card">
                <div class="card-body">

                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-8">
                                    <h5>{{ __('To set up week off for multiple employees at a time. Please click "Add company week off" button.') }}
                                    </h5>
                                </div>

                                <div class="col-4 float-end">
                                    <a href="#"
                                        data-url='{{ route('company-weekoff.create') . '?calenderType=0&employeeId=0' }}'
                                        data-size="xl" data-ajax-popup="true" class="btn-sm btn btn-info"
                                        data-title="Company Weekoff">Add Company Week off</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


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
                                    {{-- <th>{{ __('Designation') }}</th> --}}
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
    </div>
@endsection

<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />

@push('script-page')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js"></script>



    <script>
        // render table data

        function loadData() {

            // Destroy the existing DataTable if it exists
            if ($.fn.DataTable.isDataTable('.data-table')) {
                $('.data-table').DataTable().destroy();
            }

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    processing: '<i></i><span class="text-primary spinner-border"></span> '
                },
                ajax: "{{ route('company-weekoff-employees-list') }}",
                columns: [{
                        data: 'employee_id',
                        name: 'employee_id',
                        //     orderable: false,
                        //     searchable: false
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
                    // {
                    //     data: 'designation_id',
                    //     name: 'designation_id'
                    // },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });
        }

        $(document).ready(function() {
            loadData();
        });
        // render table data
    </script>
@endpush
