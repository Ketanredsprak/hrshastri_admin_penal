@extends('layouts.admin')

@section('page-title')
    {{ __('Manage My Visit') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('My Visit') }}</li>
@endsection

@section('action-button')
    <a href="#" data-url="{{ route('my_visit.create') }}" data-ajax-popup="true" data-title="{{ __('Create visit') }}"
        data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
        data-bs-original-title="{{ __('Create') }}">
        <i class="ti ti-plus"></i>
    </a>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Category Type') }}</li>
@endsection
@section('content')
    <div class="row">
        @php
            $pendingCount = $data->where('status', 'Pending')->count() ?? 0;
            $approvedCount = $data->where('status', 'Approved')->count() ?? 0;
            $rejectedCount = $data->where('status', 'Reject')->count() ?? 0;
        @endphp

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
                                    <small class="text-muted">{{ __('Total') }}</small>
                                    <h6 class="m-0">{{ __('Pending') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0 text-warning">{{ $pendingCount }}</h4>
                            {{-- <h4 class="m-0 text-warning">{{ $data->where('status', 'Pending')->count() ?? 0 }}</h4> --}}
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
                                    <small class="text-muted">{{ __('Total') }}</small>
                                    <h6 class="m-0">{{ __('Approved') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0 text-success">{{ $approvedCount }}</h4>
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
                                    <small class="text-muted">{{ __('Total') }}</small>
                                    <h6 class="m-0">{{ __('Rejected') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto text-end">
                            <h4 class="m-0 text-danger">{{ $rejectedCount }}</h4>
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
                                    <th>{{ __('Id') }}</th>
                                    <!-- <th>{{ __('Employee Name') }}</th> -->
                                    <th>{{ __('Visit type') }}</th>
                                    <th>{{ __('Visit name') }}</th>
                                    <th>{{ __('From date') }}</th>
                                    <th>{{ __('To date') }}</th>
                                    <th>{{ __('Tier') }}</th>
                                    <th>{{ __('City') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Approved / Rejected By') }}</th>
                                    <th width="250px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $row)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        {{-- <td>{{ $row->employee->name }}</td>  --}}
                                        <td>
                                            @if ($row->visit_type == 1)
                                                Local Visit
                                            @else
                                                Out of City Visit
                                            @endif
                                        </td>
                                        <td>{{ $row->visit_name }}</td>
                                        <td>{{ \Auth::user()->dateFormat($row->form_date) }}</td>
                                        <td>{{ \Auth::user()->dateFormat($row->to_date) }}</td>
                                        <td>
                                            @if (!empty($row->tierData))
                                                {{ $row->tierData->name }}
                                            @endif
                                        </td>

                                        <td>
                                            @if (!empty($row->cityData))
                                                {{ $row->cityData->name }}
                                            @endif
                                        </td>

                                        <td>{{ substr($row->description, 0, 80) . '..' }}</td>
                                        <td>
                                            @if ($row->status == 'Pending')
                                                <div class="badge bg-warning p-2 px-3 rounded status-badge5">Pending </div>
                                            @endif
                                            @if ($row->status == 'Reject')
                                                <div class="badge bg-danger p-2 px-3 rounded status-badge5">Rejected </div>
                                            @endif
                                            @if ($row->status == 'Approved')
                                                <div class="badge bg-success p-2 px-3 rounded status-badge5">Approved</div>
                                            @endif
                                        </td>
                                        <td>{{ findEmployeeByUser($row->status_changed_by) }}</td>
                                        <td class="Action">
                                            <span>
                                                @if ((Gate::check('Edit Visit Request') || \Auth::user()->type == 'employee') && $row->status != 'Approved')
                                                    <div class="action-btn btn-info ms-2">
                                                        <a href="#" data-size="md"
                                                            data-url="{{ URL::to('my_visit/' . $row->id . '/edit') }}"
                                                            data-ajax-popup="true" data-title="{{ __('Edit Visit') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Edit visit') }}"><i
                                                                class="ti ti-pencil text-white"></i></a>
                                                    </div>
                                                @endif
                                                @if ((Gate::check('Delete Visit Request') || Auth::user()->type == 'employee') && $row->status != 'Approved')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['my_visit.destroy', $row->id]]) !!}
                                                        <a href="#!"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Delete') }}">
                                                            <span class="text-white"> <i class="ti ti-trash"></i></span></a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endif
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-page')
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
                url: "{{ url('get_commutingData') }}",
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
                url: "{{ url('get_getCitiesData') }}",
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
                url: "{{ url('get_getExpensesdData') }}",
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
@endpush
