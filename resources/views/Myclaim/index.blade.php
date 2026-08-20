@extends('layouts.admin')

@section('page-title')
    {{ __('Manage My Claim') }}
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('My Claim') }}</li>
@endsection

@section('action-button')
    <a href="#" data-url="{{ route('my_claim.create') }}" data-ajax-popup="true" data-title="{{ __('Create New claim') }}"
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
            $pendingCount = $myclaimsdata->where('status', 'Pending')->count() ?? 0;
            $approvedCount = $myclaimsdata->where('status', 'Approved')->count() ?? 0;
            $rejectedCount = $myclaimsdata->where('status', 'Rejected')->count() ?? 0;
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

        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Id') }}</th>
                                    {{-- <th>{{ __('Employee Name') }}</th> --}}
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
                                @foreach ($myclaimsdata as $key => $row)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        {{-- <td>{{ $row->employee->name }}</td> --}}
                                        <td>
                                            @if ($row->visit_type == 1)
                                                Other Claim
                                            @else
                                                @if (!empty($row->myvisitData))
                                                    @if ($row->myvisitData->visit_type == 1)
                                                        Local visit
                                                    @else
                                                        Out of City visit
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($row->myvisitData))
                                                {{ $row->myvisitData->visit_name }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $row->name }}
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
                                        <td>{{ Auth::user()->priceFormat((float) $row->total_amount) }}</td>

                                        <td>
                                            @php
                                                if (!empty($row->myvisitData)) {
                                                    if ($row->myvisitData->visit_type == 2) {
                                                        $tier = App\Models\CityTier::find($row->myvisitData->tier_id);
                                                        echo $tier->name ?? '';
                                                    }
                                                }
                                            @endphp
                                        </td>

                                        <td>
                                            @php
                                                if (!empty($row->myvisitData)) {
                                                    if ($row->myvisitData->visit_type == 2) {
                                                        $city = App\Models\CityModel::find($row->myvisitData->city_id);
                                                        if ($row->id == 115) {
                                                            // dd($row, $row->myvisitData, $row->myvisitData->city_id, $city);
                                                        }
                                                        echo $city->name ?? '';
                                                    }
                                                }
                                            @endphp
                                        </td>
                                        <td>
                                            @if ($row->visit_type == 1)
                                                {{ \Auth::user()->dateFormat($row->created_at) }}
                                            @else
                                                @if (!empty($row->myvisitData))
                                                    {{ \Auth::user()->dateFormat($row->myvisitData->form_date) }}
                                                @endif
                                            @endif

                                        </td>

                                        <td>
                                            {{ \Auth::user()->dateFormat($row->created_at) }}
                                        </td>

                                        <td>
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

                                                {{-- @if (\Auth::user()->type == 1 || \Auth::user()->type == 'company') --}}
                                                {{-- @if (Gate::check('Edit Claim Request'))
                                        <div class="action-btn bg-success ms-2">
                                            <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg" data-url="{{ URL::to('my_claim/' . $row->id . '/action_hr') }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="{{ __('Action') }}" data-bs-original-title="{{ __('Manage Claim') }}">
                                                  <i class="ti ti-caret-right text-white"></i>
                                                  </a>
                    </div>
                    @endif --}}

                                                {{-- @endif --}}
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-size="lg" data-url="{{ route('my_claim.show', $row->id) }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="{{ __('Claim Details') }}"
                                                        data-bs-original-title="{{ __('Claim Details') }}">
                                                        <i class="ti ti-eye text-white"></i>
                                                    </a>
                                                </div>
                                                @if ((Gate::check('Edit Claim Request') || \Auth::user()->type == 'employee') && $row->status != 'Approved')
                                                    <div class="action-btn btn-info ms-2">
                                                        <a href="#" data-size="md"
                                                            data-url="{{ route('my_claim.edit', $row->id) }}"
                                                            data-ajax-popup="true" data-title="{{ __('Edit claim') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('Edit claim') }}"><i
                                                                class="ti ti-pencil text-white"></i></a>
                                                    </div>
                                                @endif
                                                @if ((Gate::check('Delete Claim Request') || Auth::user()->type == 'employee') && $row->status != 'Approved')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['my_claim.destroy', $row->id]]) !!}
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
    <script>
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
