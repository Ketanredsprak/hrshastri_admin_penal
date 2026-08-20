{{ Form::open(['url' => 'my_visit/changeaction_hr', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">

                <tr>
                    <th>{{ __('Employee Name') }}</th>
                    <td>{{ $employee->name ?? '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Visit Type') }}</th>
                    <td>
                        @if ($data->visit_type == 1)
                            Local visit
                        @else
                            Out of city visit
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>{{ __('Visit Name') }}</th>
                    <td>{{ $data->visit_name }}</td>
                </tr>
                <tr>
                    <th>{{ __('Form Date') }}</th>
                    <td>{{ \Auth::user()->dateFormat($data->form_date) }}</td>
                </tr>

                <tr>
                    <th>{{ __('To Date') }}</th>
                    <td>{{ \Auth::user()->dateFormat($data->to_date) }}</td>
                </tr>

                @if ($data->visit_type != 1)
                    <tr>
                        <th>{{ __('Tier') }}</th>
                        <td>
                            @if (!empty($data->tierData))
                                {{ $data->tierData->name }}
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>{{ __('City') }}</th>
                        <td>
                            @if (!empty($data->cityData))
                                {{ $data->cityData->name }}
                            @endif
                        </td>
                    </tr>
                @endif


                <tr>
                    <th>{{ __('Description') }}</th>
                    <td>{{ $data->description }}</td>
                </tr>
                <tr>
                    <th>{{ __('Status') }}</th>
                    <td>{{ !empty($data->status) ? $data->status : '' }}</td>
                </tr>
                <input type="hidden" value="{{ $data->id }}" name="visit_id">
            </table>
            @if (in_array($data->status, ['Pennding', 'Pending']))
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="">Reason</label>
                            <textarea name="reject_reason" class="form-control" id="" cols="40" rows="6">{{ $data->reject_reason }}</textarea>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>


<div class="modal-footer">
    @if (in_array($data->status, ['Pennding', 'Pending', 'Reject']))
        <input type="submit" value="{{ __('Approve') }}" class="btn btn-success rounded" name="visit_status">
    @endif

    @if (in_array($data->status, ['Pennding', 'Pending']))
        <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger rounded" name="visit_status">
    @endif
</div>


{{ Form::close() }}
