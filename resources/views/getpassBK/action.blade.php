{{ Form::open(['url' => 'getpass/changeactionupdate','method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th>{{ __('Employee') }}</th>
                    <td>{{ !empty($employee->name) ? $employee->name : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Gate pass Type ') }}</th>
                    <td>
                        @if(!empty($data->name) == 1)
                            Personal work
                        @else
                            Office Work
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ __('Date') }}</th>
                    <td>{{ \Auth::user()->dateFormat($data->date) }}</td>
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
                    <th>{{ __('Leave Reason') }}</th>
                    <td>{{ !empty($data->resone) ? $data->resone : '' }}</td>
                </tr>
                
                <input type="hidden" value="{{ $data->id }}" name="leave_id">
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    @if($data->status != 'Approved')
        <input type="submit" value="{{ __('Approved') }}" class="btn btn-success rounded" name="status">
    @endif
        <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger rounded" name="status">
</div>
{{ Form::close() }}
