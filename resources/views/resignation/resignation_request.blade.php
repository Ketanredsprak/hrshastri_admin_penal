@extends('layouts.admin')
@section('page-title')
    {{ __('Manage Resignation Request') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Resignation') }}</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table resignation-datatable" id="pc-dt-simple12">
                            <thead>
                                <th>{{ __('Employee Id') }}</th>
                                <th>{{ __('Employee Name') }}</th>
                                <th>{{ __('Resignation Date') }}</th>
                                <th>{{ __('Last Working Day') }}</th>
                                <th>{{ __('Reason') }}</th>
                                <th>{{ __('Notes') }}</th>
                                <th class="text-end">{{ __('Status') }}</th>
                                @if (Auth::user()->type != 'employee')
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
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

    <script>
        $(document).ready(function() {
            var table = $('.resignation-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('resignation_request') }}",
                order: [
                    [2, 'desc']
                ],
                columns: [{
                        data: 'employee_id',
                        name: 'employee_id'
                    },
                    {
                        data: 'employee',
                        name: 'employee'
                    },
                    {
                        data: 'resignation_date',
                        name: 'resignation_date'
                    },
                    {
                        data: 'last_working_day',
                        name: 'last_working_day'
                    },
                    {
                        data: 'reason',
                        name: 'reason'
                    },
                    {
                        data: 'notes',
                        name: 'notes'
                    },
                    {
                        data: 'status',
                        name: 'status'
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
    </script>
@endsection
