{{ Form::open(['url' => 'getpass/changeactionupdate', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th>{{ __('Employee') }}</th>
                    <td>{{ $data->employee->name }}</td>
                </tr>
                <tr>
                    <th>{{ __('Type') }}</th>
                    <td>
                        @if (!empty($data->name) == 1)
                            {{ __('Personal work') }}
                        @else
                            {{ __('Office Work') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ __('Created At') }}</th>
                    <td>{{ \Auth::user()->dateFormat($data->getRawOriginal('created_at')) }}</td>
                </tr>
                <tr>
                    <th>{{ __('Entry Date') }}</th>
                    <td>{{ $data->date }}</td>
                </tr>
                <tr>
                    <th>{{ __('Authorized Person Name') }}</th>
                    <td>{{ $data->approvedBy->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>
                        @if ($data->status == 1)
                            {{ __('Approved Date & Time') }}
                        @elseif($data->status == 2)
                            {{ __('Rejected Date & Time') }}
                        @else
                            {{ __('Approved / Rejected Date & Time') }}
                        @endif
                    </th>
                    <td>{{ !empty($data->approved_at) ? $data->approved_at : '-' }}</td>
                </tr>
                <tr>
                    <th>{{ __('In Time') }}</th>
                    <td>{{ $data->in_time }}</td>
                </tr>
                <tr>
                    <th>{{ __('Out Time') }}</th>
                    <td>{{ $data->out_time }}</td>
                </tr>
                <tr>
                    <th>{{ __('Status') }}</th>
                    <td>
                        @if ($data->status == 0)
                            {{ __('Pending') }}
                        @elseif($data->status == 1)
                            {{ __('Approved') }}
                        @elseif($data->status == 2)
                            {{ __('Rejected') }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ __('Reason') }}</th>
                    <td>{{ !empty($data->resone) ? $data->resone : '' }}</td>
                </tr>
                <input type="hidden" value="{{ $data->id }}" name="leave_id">
            </table>
        </div>
    </div>
</div>

<div class="modal-footer">
    @if ($data->status == 0)
        <input type="hidden" value="{{ __('Approved') }}" class="btn btn-success rounded" name="status">
        <input type="submit" value="{{ __('Approve') }}" class="btn btn-success rounded" name="visit_statusData">
        <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger rounded" name="status">
    @endif
</div>
{{ Form::close() }}
