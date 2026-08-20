<div class="card-body">
    <div class="row">
        <div class="col-md-12">
            @php 
                $user_device_configurations = json_decode($user->user_device_configurations, true); 
            @endphp
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th>{{ __('Device Name') }}</th>
                    <td>{{ $user_device_configurations['device_name'] ?? '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('System Version') }}</th>
                    <td>{{ $user_device_configurations['system_version'] ?? '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('System Name') }}</th>
                    <td>{{ $user_device_configurations['system_name'] ?? '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Device Model') }}</th>
                    <td>{{ $user_device_configurations['device_model'] ?? '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Operating System Version') }}</th>
                    <td>{{ $user_device_configurations['operating_system_version'] ?? '' }}</td>
                </tr>
            </table>
        </div>   
    </div>
</div>