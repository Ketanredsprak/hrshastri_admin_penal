{{ Form::open(['url' => 'earlyandlate_leave/changeaction', 'method' => 'post']) }}

<div class="modal-body">

    <div class="row">

        <div class="col-12">

            <table class="table modal-table" id="pc-dt-simple">



                <tr>
                    <th>{{ __('Employee Name') }}</th>
                    <td>
                        {{ $data->employee->name }}
                    </td>
                </tr>
                <tr>

                    <th>{{ __('Leave Type ') }}</th>

                    <td>

                        @if ($data->name == 1)
                            Early Leave
                        @else
                            Late Cooming
                        @endif

                    </td>

                </tr>

                <tr>

                    <th>{{ __('date') }}</th>

                    <td>{{ \Auth::user()->dateFormat($data->date) }}</td>

                </tr>

                <!-- <tr>

                    <th>{{ __('In Time') }}</th>

                    <td>{{ $data->in_time }}</td>

                </tr>

                <tr>

                    <th>{{ __('Out Time') }}</th>

                    <td>{{ $data->out_time }}</td>

                </tr> -->

                <tr>

                    <th>{{ __('Reason') }}</th>

                    <td>{{ !empty($data->resone) ? $data->resone : '' }}</td>

                </tr>
                <tr>

                    <th>{{ __('Status') }}</th>

                    @if ($data->status == 0)
                        <td>Pending</td>
                    @elseif($data->status == 1)
                        <td>Approved</td>
                    @elseif($data->status == 2)
                        <td>Rejected</td>
                    @endif


                </tr>

                <input type="hidden" value="{{ $data->id }}" name="leave_id">

            </table>

        </div>

    </div>

</div>

<div class="modal-footer">

    <!-- 0 = pending, 1 = Approved, 2 = Rejected	 -->
    @if ($data->status == 0)
        <input type="hidden" value="{{ __('Approved') }}" class="btn btn-success rounded" name="status">

        <input type="submit" value="{{ __('Approve') }}" class="btn btn-success rounded" name="visit_statusData">
    
        <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger rounded" name="status">
    @endif

</div>

{{ Form::close() }}
