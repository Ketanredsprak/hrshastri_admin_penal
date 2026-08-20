@extends('layouts.admin')

@section('page-title')
    {{ __('Approved Claims') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Approved Claims') }}</li>
@endsection

{{-- @section('action-button')
<a href="#" data-url="{{ route('my_claim.create') }}" data-ajax-popup="true" data-title="{{ __('Create New claim') }}" data-size="lg" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
<i class="ti ti-plus"></i>
</a>
@endsection --}}

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Category Type') }}</li>
@endsection
@section('content')
    <div class="row">

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
                            <h4 class="m-0 text-success">{{ claim_approved() }}</h4>
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
                                    <th>{{ __('Employee Name') }}</th>
                                    <th>{{ __('Visit Type') }}</th>
                                    <th>{{ __('Visit Name') }}</th>
                                    <th>{{ __('Vehicle type') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Tier') }}</th>
                                    <th>{{ __('City') }}</th>
                                    <th>{{ __('Visit Date') }}</th>
                                    <th style="display: none">{{ __('Status') }}</th>
                                    <th>{{ __('Approved / Rejected By') }}</th>
                                    <th width="250px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($myclaimsdata as $key => $row)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $row->employee->name }}</td>
                                        <td>
                                            @if (!empty($row->myvisitData))
                                                @if ($row->myvisitData->visit_type == 1)
                                                    Local visit
                                                @else
                                                    Out of City visit
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($row->myvisitData))
                                                {{ $row->myvisitData->visit_name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($row->commuting_type))
                                                @if ($row->commuting_type == 1)
                                                    Two wheeler
                                                @else
                                                    Four wheeler
                                                @endif
                                            @endif
                                        </td>
                                        <td>&#8377; {{ $row->total_amount }}</td>

                                        <td>
                                            @php
                                                if (!empty($row->myvisitData)) {
                                                    if ($row->myvisitData->visit_type == 2) {
                                                        $tier = App\Models\CityTier::find($row->myvisitData->tier_id);
                                                        echo $tier->name;
                                                    }
                                                }
                                            @endphp
                                        </td>

                                        <td>
                                            @php
                                                if (!empty($row->myvisitData)) {
                                                    if ($row->myvisitData->visit_type == 2) {
                                                        $city = App\Models\CityModel::find($row->myvisitData->city_id);
                                                        echo $city->name;
                                                    }
                                                }
                                            @endphp
                                        </td>
                                        <td>
                                            @if (!empty($row->myvisitData))
                                                {{ $row->myvisitData->form_date }}
                                            @endif
                                        </td>

                                        <td style="display: none">
                                            @if ($row->status == 'Pending')
                                                <div class="badge bg-warning p-2 px-3 rounded status-badge5">Pending </div>
                                            @endif
                                            @if ($row->status == 'Rejected')
                                                <div class="badge bg-danger p-2 px-3 rounded status-badge5">Rejected </div>
                                            @endif
                                            @if ($row->status == 'Approved')
                                                <div class="badge bg-success p-2 px-3 rounded status-badge5">Approved</div>
                                            @endif
                                        </td>
                                        <td>{{ findEmployeeByUser($row->status_changed_by) }}</td>
                                        <td class="Action">
                                            <span>
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-size="lg" data-url="{{ route('my_claim.show', $row->id) }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="{{ __('Claim Details') }}"
                                                        data-bs-original-title="{{ __('Claim Details') }}">
                                                        <i class="ti ti-eye text-white"></i>
                                                    </a>
                                                </div>
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
