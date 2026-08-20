<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th>{{ __('Branch') }}</th>
                    <td>{{ $brances->name ?? "" }}</td>
                </tr>

                <tr>
                    <th>{{ __('Goal Type') }}</th>
                    <td>{{ $goalTypes->name ?? "" }}</td>
                </tr>

                <tr>
                    <th>{{ __('Start Date') }}</th>
                    <td>{{ $goalTracking->start_date }}</td>
                </tr>
                
                <tr>
                    <th>{{ __('End Date') }}</th>
                    <td>{{ $goalTracking->end_date }}</td>
                </tr>
                
                <tr>
                    <th>{{ __('Subject') }}</th>
                    <td>{{ $goalTracking->subject }}</td>
                </tr>
                
                <tr>
                    <th>{{ __('Target Achievement') }}</th>
                    <td>{{ $goalTracking->target_achievement }}</td>
                </tr>
                
                <tr>
                    <th>{{ __('Description') }}</th>
                    <td>{{ $goalTracking->description }}</td>
                </tr>
                
                <tr>
                    <th>{{ __('Status') }}</th>
                    <td>{{ $status[$goalTracking->status] }}</td>
                </tr>

                
                
                <tr>
                    <th>{{ __('Progress') }}</th>
                    <td>
                         @php
                              $progressPercentasge = $goalTracking->progress;
                         @endphp
                         <div class="progress-bar" role="progressbar" style="width: {{$progressPercentasge}}%;" aria-valuenow="{{$progressPercentasge}}" aria-valuemin="0" aria-valuemax="100">
                              {{$progressPercentasge}}%
                         </div>
                    </td>
                </tr>

            </table>
        </div>
    </div>
</div>
