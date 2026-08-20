{{ Form::open(['url' => 'leave/changeaction_hr', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th>{{ __('Employee') }}</th>
                    <td>{{ !empty($leave->employees->name) ? $leave->employees->name : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Leave Type ') }}</th>
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
                    <td>{{ !empty($leave->secound_leave_status) ? $leave->secound_leave_status : '' }}</td>
                </tr>
                <input type="hidden" value="{{ $leave->id }}" name="leave_id">
            </table>
        </div>
    </div>
</div>


<div class="modal-footer">
    @if ($leave->secound_leave_status != 'Approved')
        <input type="submit" value="{{ __('Approved') }}" class="btn btn-success rounded" name="secound_leave_status">
    @endif
    <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger rounded" name="secound_leave_status">
</div>


{{ Form::close() }}
