@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Leave') }}
@endsection

@if (\Auth::user()->type != 'company')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item">{{ __('Leave ') }}</li>
    @endsection

    @section('action-button')
        <a href="#" data-url="{{ route('leave.file.export_import_file') }}" data-ajax-popup="true"
            data-title="{{ __('Import Leave') }}" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-secondary"
            data-bs-original-title="{{ __('Import Leave') }}">
            <i class="ti ti-file"></i>
        </a>
        <a href="{{ route('leave.export') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
            data-bs-original-title="{{ __('Export') }}">
            <i class="ti ti-file-export"></i>
        </a>

        <a href="{{ route('leave.calender') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
            data-bs-original-title="{{ __('Calendar View') }}">
            <i class="ti ti-calendar"></i>
        </a>

        @can('Create Leave')
            <a href="#" data-url="{{ route('leave.create') }}" data-ajax-popup="true"
                data-title="{{ __('Create New Leave') }}" data-size="lg" data-bs-toggle="tooltip" title=""
                class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endcan
    @endsection
@endif

@section('content')
    <div class="row">

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
                            <h4 class="m-0 text-warning">{{ leave_pending() }}</h4>
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
                            <h4 class="m-0 text-success">{{ leave_approved() }}</h4>
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
                            <h4 class="m-0 text-danger">{{ leave_rejected() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="card">
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    @if (\Auth::user()->type != 'employee')
                                        <th>{{ __('Employee') }}</th>
                                    @endif
                                    <th>{{ __('Leave Type') }}</th>
                                    <th>{{ __('Applied On') }}</th>
                                    <th>{{ __('Start Date') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Total Days') }}</th>
                                    <th>{{ __('status') }}</th>
                                    {{-- <th>{{ __('Allow to edit') }}</th> --}}
                                    <th width="200px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <?php $total_leave_data = 0; ?>
                            <tbody>
                                @foreach ($leaves as $leave)
                                    <tr>
                                        @if (\Auth::user()->type != 'employee')
                                            <td>
                                                @php
                                                    $data = App\Models\User::find($leave->employee_id);

                                                @endphp
                                                {{ !empty(\Auth::user()->getEmployee($leave->employee_id)) ? \Auth::user()->getEmployee($leave->employee_id)->name : '' }}
                                            </td>
                                        @endif
                                        <td>{{ !empty(\Auth::user()->getLeaveType($leave->leave_type_id)) ? \Auth::user()->getLeaveType($leave->leave_type_id)->title : '' }}
                                        </td>
                                        <td>{{ \Auth::user()->dateFormat($leave->applied_on) }}</td>
                                        <td>{{ \Auth::user()->dateFormat($leave->start_date) }}</td>
                                        <td>{{ \Auth::user()->dateFormat($leave->end_date) }}</td>
                                        <td>{{ $leave->total_leave_days }}</td>
                                        <td id="leave_status_{{ $leave->id }}">
                                            @if ($leave->status == 'Pending')
                                                <div class="badge bg-warning p-2 px-3 rounded status-badge5">
                                                    {{ $leave->status }}
                                                </div>
                                            @elseif($leave->status == 'Approved')
                                                <div class="badge bg-success p-2 px-3 rounded status-badge5">
                                                    {{ $leave->status }}
                                                </div>
                                            @elseif($leave->status == 'Reject')
                                                <div class="badge bg-danger p-2 px-3 rounded status-badge5">
                                                    {{ $leave->status }}
                                                </div>
                                            @endif
                                        </td>

                                        {{-- <td>
                                            @if ($leave->status == 'Approved')
                                                <div class="col switch-width" id="allow_to_edit_leave_{{ $leave->id }}">
                                        <div class="custom-control custom-switch">
                                             <input type="checkbox" class="form-check-input allow_to_edit_leave" name="is_allowed_{{ $leave->id }}" data-toggle="switchbutton" data-onstyle="primary" id="is_allowed_{{ $leave->id }}" leaveId="{{ $leave->id }}">
                                             <label class="custom-control-label form-label" for="is_allowed_{{ $leave->id }}"></label>
                                        </div>
                    </div>
                    @endif
                    </td> --}}


                                        <td class="Action">

                                            <span>

                                                <div class="action-btn bg-success">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                        data-size="lg"
                                                        data-url="{{ URL::to('leave/' . $leave->id . '/action') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="{{ __('Leave Details') }}"
                                                        data-bs-original-title="{{ __('Leave Details') }}">
                                                        <i class="ti ti-caret-right text-white"></i>
                                                    </a>
                                                </div>

                                                @can('Edit Leave')
                                                    @if ($leave->status == 'Pending')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                                data-size="lg"
                                                                data-url="{{ URL::to('leave/' . $leave->id . '/edit') }}"
                                                                data-ajax-popup="true" data-size="md"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-title="{{ __('Edit Leave') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endcan


                                                @if ($leave->status == 'Pending')
                                                    @can('Delete Leave')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['leave.destroy', $leave->id],
                                                                'id' => 'delete-form-' . $leave->id,
                                                            ]) !!}
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            </form>
                                                        </div>
                                                    @endcan
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
    </div>
@endsection
@push('script-page')
    <script>
        $(document).on('change', '#employee_id', function() {
            var employee_id = $(this).val();

            $.ajax({
                url: "{{ route('leave.jsoncount') }}",
                type: 'POST',
                data: {
                    "employee_id": employee_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    console.log("testt", data);
                    $('#leave_type_id').empty();
                    $('#leave_type_id').append('<option value="">Select Leave Type</option>');

                    $.each(data, function(key, value) {

                        if (value.total_leave == value.days) {
                            $('#leave_type_id').append('<option value="' + value.id +
                                '" disabled>' + value.title + '&nbsp(' + value.total_leave +
                                '/' + value.days + ')</option>');
                        } else {
                            $('#leave_type_id').append('<option value="' + value.id + '">' +
                                value.title + '&nbsp(' + value.total_leave + '/' + value
                                .days + ')</option>');
                        }
                    });
                }
            });
        });

        $(".allow_to_edit_leave").on("change", function() {

            if ($(this).prop("checked")) {

                //  $(this).prop("disabled", true);
                var leaveId = $(this).attr("leaveId");
                console.log("Checkbox checked , Leave Id = " + leaveId);



                try {
                    $.ajax({
                        url: "{{ route('leave.allow-to-edit-leave') }}",
                        method: 'POST',
                        data: {
                            leaveId: leaveId,
                        },
                        success: function(response) {
                            // Handle the success response

                            if (response.status == 1) {
                                $("#allow_to_edit_leave_" + leaveId).hide();
                                alert(response.message);

                                //   location.reload();

                                $("#leave_status_" + leaveId).html(
                                    '<div class="badge bg-warning p-2 px-3 rounded status-badge5">' +
                                    response.leaveStatus + '</div>'
                                );
                            } else {
                                $(this).prop("disabled", false);
                                alert(response.message);

                                $("#allow_to_edit_leave_" + leaveId).show();
                            }
                        },
                        error: function(xhr, status, error) {

                            $("#allow_to_edit_leave_" + leaveId).show();
                            $(this).prop("disabled", false);
                        }
                    });

                } catch (error) {
                    console.log(error);
                    $("#allow_to_edit_leave_" + leaveId).show();
                    $(this).prop("disabled", false);
                }
            } else {
                console.log("Checkbox unchecked");
            }
        });
    </script>
@endpush
