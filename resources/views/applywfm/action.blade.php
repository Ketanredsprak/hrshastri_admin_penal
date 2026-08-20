{{ Form::open(['url' => 'applywfm_update_action', 'method' => 'post']) }}

<div class="modal-body">

     <div class="row">

          <div class="col-12">

               <table class="table modal-table" id="pc-dt-simple">

                    <tr>

                         <th>{{ __('Employee Name') }}</th>

                         <td>{{ $data->employee->name }}</td>

                    </tr>
                    <tr>

                         <th>{{ __('Start Date') }}</th>

                         <td>{{ \Auth::user()->dateFormat($data->start_date) }}</td>

                    </tr>

                    <tr>

                         <th>{{ __('End Date') }}</th>

                         <td>{{ \Auth::user()->dateFormat($data->end_date) }}</td>

                    </tr>



                    <tr>

                         <th>{{ __('Note') }}</th>

                         <td>{{ $data->note }}</td>

                    </tr>
                    <tr>

                         <th>{{ __('Status') }}</th>
                         @if ($data->status == 0)
                         <td>{{ _('Pending') }}</td>
                         @elseif($data->status == 1)
                         <td>{{ _('Approved') }}</td>
                         @elseif($data->status == 2)
                         <td>{{ _('Rejected') }}</td>
                         @endif

                    </tr>



                    <input type="hidden" value="{{ $data->id }}" name="leave_id">

               </table>

          </div>

     </div>

</div>

<div class="modal-footer">

     <!-- 0 = pending, 1 = Approved, 2 = Rejected	 -->
     @if (in_array($data->status, [0]))
     <input type="hidden" value="{{ __('Approved') }}" class="btn btn-success rounded" name="status">
     <input type="submit" value="{{ __('Approve') }}" class="btn btn-success rounded" name="visit_statusData">
     @endif

     @if (in_array($data->status, [0]))
     <input type="submit" value="{{ __('Reject') }}" class="btn btn-danger rounded" name="status">
     @endif


</div>

{{ Form::close() }}