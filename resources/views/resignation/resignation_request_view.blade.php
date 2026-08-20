<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th>{{ __('Employee') }}</th>
                    <td>{{ !empty($data->employee()) ? $data->employee()->name : '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Resignation Date') }}</th>
                    <td>{{ \Auth::user()->dateFormat($data->resignation_date) }}</td>
                </tr>
                <tr>
                    <th>{{ __('Last Working Date') }}</th>
                    <td>{{ \Auth::user()->dateFormat($data->notice_date) }}</td>
                </tr>
                <tr>
                    <th>{{ __('Description') }}</th>
                    <td><p>{!! nl2br(e($data->description ?? '')) !!}</p></td>
                </tr>
                <tr>
                    <th>{{ __('Notes') }}</th>
                    <td>{{ $data->notes ?? '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Status') }}</th>
                    <td>
                        @if ($data->status == 0)
                             <div class="badge bg-warning p-2 px-3 rounded status-badge5">pending</div>
                        @elseif ($data->status == 1)
                             <div class="badge bg-success p-2 px-3 rounded status-badge5">Approve</div>
                        @elseif ($data->status == 2)
                             <div class="badge bg-danger p-2 px-3 rounded status-badge5">Rejected</div>
                        @else
                             <div class="badge bg-danger p-2 px-3 rounded status-badge5"></div>
                        @endif
                    </td>
                </tr>
            </table>
    </div>
</div>