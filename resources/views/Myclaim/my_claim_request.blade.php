@extends('layouts.admin')

@section('page-title')
    {{ __('Manage My Claim Request') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('My Claim') }}</li>
@endsection

{{-- @section('action-button')
<a href="#" data-url="{{ route('my_claim.create') }}" data-ajax-popup="true" data-title="{{ __('Create New claim') }}"
    data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
    data-bs-original-title="{{ __('Create') }}">
    <i class="ti ti-plus"></i>
</a>
@endsection --}}

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Category Type') }}</li>
@endsection
@section('content')
    <div class="row">
        @php
            $pendingCount = $myclaimsdata->where('status', 'Pending')->count();
            $approvedCount = $myclaimsdata->where('status', 'Approved')->count();
            $rejectedCount = $myclaimsdata->where('status', 'Rejected')->count();
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
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table claim-datatable" id="pc-dt-simple12">
                            <thead>
                                <tr>
                                    <th>{{ __('Employee Id') }}</th>
                                    <th>{{ __('Employee Name') }}</th>
                                    <th>{{ __('Visit Type') }}</th>
                                    <th>{{ __('Visit Name') }}</th>
                                    <th>{{ __('Other Claim') }}</th>
                                    <th>{{ __('Vehicle type') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Tier') }}</th>
                                    <th>{{ __('City') }}</th>
                                    <th>{{ __('Visit Date') }}</th>
                                    <th>{{ __('Claimed On') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Approved / Rejected By') }}</th>
                                    <th width="250px">{{ __('Action') }}</th>
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
@push('script-page')
    <script>
        // Datatable
        $(document).ready(function() {
            var table = $('.claim-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('my_claim_request') }}",
                order: [
                    [11, 'desc']
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
                        data: 'visit_type',
                        name: 'visit_type'
                    },
                    {
                        data: 'visit_name',
                        name: 'visit_name'
                    },
                    {
                        data: 'other_claim',
                        name: 'other_claim'
                    },
                    {
                        data: 'vehicle_type',
                        name: 'vehicle_type'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
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
                        data: 'visit_date',
                        name: 'visit_date'
                    },
                    {
                        data: 'claimed_on',
                        name: 'claimed_on'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'status_changed_by',
                        name: 'status_changed_by'
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
        // END

        $(document).ready(function() {
            $("#commonModal").on("click", 'form input[type=submit]', function(event) {
                $("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
                $(this).attr("clicked", "true");
            });
            // $("#commonModal").on("click", '#reject', function(event) {
            //     $("#reject_reason").removeClass('d-none')
            //     $(this).attr('type','submit');
            //     event.preventDefault();
            // });
            $("#commonModal").on("submit", 'form', function(event) {
                event.preventDefault();
                var val = $("input[type=submit][clicked=true]").val();
                var form_cust = this;
                var formData = new FormData(form_cust);
                var url = $(this).attr('action');
                var type = "POST";
                formData.append('claim_status', val);
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
    </script>
@endpush
