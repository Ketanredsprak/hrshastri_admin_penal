{{ Form::open(['url' => 'leave/changeaction', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th>{{ __('Employee') }}</th>
                    <td>{{ !empty($leave->employees->name) ? $leave->employees->name : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Leave Type') }}</th>
                    <td>{{ !empty($leave->leaveType->title) ? $leave->leaveType->title : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Leave Day') }}</th>
                    <td>{{ $leave->total_leave_days > '0.5' ? __('Full Day') : __('Half Day') }}</td>
                </tr>
                <tr>
                    <th>{{ __('Appplied On') }}</th>
                    <td>{{ \Auth::user()->dateFormat($leave->applied_on) }}</td>
                </tr>
                <tr>
                    <th>{{ __('Start Date') }}</th>
                    <td>{{ \Auth::user()->dateFormat($leave->start_date) }}</td>
                </tr>
                <tr>
                    <th>{{ __('End Date') }}</th>
                    <td>{{ \Auth::user()->dateFormat($leave->end_date) }}</td>
                </tr>
                <tr>
                    <th>{{ __('Total Days') }}</th>
                    <td>{{ $leave->total_leave_days }}</td>
                </tr>
                <tr>
                    <th>{{ __('Leave Reason') }}</th>
                    <td>{{ !empty($leave->leave_reason) ? $leave->leave_reason : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Status') }}</th>
                    <td>{{ !empty($leave->status) ? $leave->status : '' }}</td>
                </tr>

                <input type="hidden" value="{{ $leave->id }}" name="leave_id">
            </table>

            @if (!empty($logsData))

                <h6>Leave Update Logs</h6>
                <table class="table modal-table" id="pc-dt-simple">
                    <thead>
                        <th>Updated By</th>
                        <th>Updated At</th>
                    </thead>
                    <tbody>
                        @foreach ($logsData as $logRow)
                            <tr>
                                <td>{{ $logRow['name'] }}</td>
                                <td>{{ $logRow['dateTime'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>
</div>
@if (Auth::user()->type != 'employee')

    <div class="modal-footer">

        <!-- 0 = pending, 1 = Approved, 2 = Rejected	 -->
        @if (in_array($leave->status, ['Pending', 'Reject']))
            <input type="hidden" value="{{ __('Approved') }}" class="btn btn-success rounded" name="status">

            <input type="submit" value="{{ __('Approve') }}" class="btn btn-success rounded" name="visit_statusData">
        @endif

        @if (in_array($leave->status, ['Pending', 'Approved']))
            <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger rounded" name="status">
        @endif
    </div>
@endif
{{ Form::close() }}
