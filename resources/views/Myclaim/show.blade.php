<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th>{{ __('Employee Name') }}</th>
                    <td>{{ $employee->name ?? '' }}</td>
                </tr>
                <tr>
                    <th>{{ __('Visit Type ') }}</th>
                    <td>
                        @if ($data->visit_type == 1)
                            Other Claim
                        @else
                            @if (!empty($data->myvisitData))
                                @if ($data->myvisitData->visit_type == 1)
                                    Local visit
                                @else
                                    Out of City visit
                                @endif
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ __('Visit Name') }}</th>
                    <td>
                        @if ($data->visit_type == 1)
                            {{ $data->name }}
                        @else
                            @if (!empty($data->myvisitData))
                                {{ $data->myvisitData->visit_name }}
                            @endif
                        @endif

                    </td>
                </tr>

                @if (($data->myvisitData->visit_type ?? 0) == 1)
                    <tr style="display: {{ $data->visit_type == 1 ? 'none' : '' }}">
                        <th>{{ __('Vehicle type') }}</th>
                        <td>
                            @if (!empty($data->commuting_type))
                                @if ($data->commuting_type == 1)
                                    Two wheeler
                                @else
                                    Four wheeler
                                @endif
                            @endif
                        </td>
                    </tr>
                @endif


                @if (($data->myvisitData->visit_type ?? 0) != 1)
                    <tr style="display: {{ $data->visit_type == 1 ? 'none' : '' }}">
                        <th>{{ __('Tier') }}</th>
                        <td>
                            @php
                                if (!empty($data->myvisitData)) {
                                    if ($data->myvisitData->visit_type == 2) {
                                        $tier = App\Models\CityTier::find($data->myvisitData->tier_id);
                                        echo $tier->name ?? '';
                                    }
                                }
                            @endphp
                        </td>
                    </tr>

                    <tr style="display: {{ $data->visit_type == 1 ? 'none' : '' }}">
                        <th>{{ __('City') }}</th>
                        <td>
                            @php
                                if (!empty($data->myvisitData)) {
                                    if ($data->myvisitData->visit_type == 2) {
                                        $city = App\Models\CityModel::find($data->myvisitData->city_id);
                                        echo $city->name ?? '';
                                    }
                                }
                            @endphp
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
                @if ($data->status == 'Rejected')
                    <tr>
                        <th>{{ __('Reject Reason') }}</th>
                        <td>{{ $data->reject_reason }}</td>
                    </tr>
                @endif
                @if ($data->visit_type == 0 && ($data->myvisitData->visit_type ?? 0) == 2)
                    @foreach (json_decode($data->expens_name) as $key => $item)
                        @if ($item != null)
                            <tr>
                                <th>Expense Name</th>
                                <td>{{ $item }}</td>
                            </tr>
                            <tr>
                                <th>Expense Amount</th>
                                @php
                                    $amount = json_decode($data->expens_amount);
                                @endphp
                                <td>{{ $amount[$key] }}</td>
                            </tr>
                        @endif
                    @endforeach
                @endif
                <tr>
                    <th>{{ __('Amount') }}</th>
                    <td>{{ $data->total_amount }}</td>
                </tr>

                <tr>
                    @php
                        $fileupload = explode('.', $data->fileupload);
                    @endphp
                    <th>{{ __('Uploded Image') }}</th>
                    @if (count($fileupload) > 1)
                        <td>
                            @if ($fileupload['1'] == 'jpg' || $fileupload['1'] == 'jpeg' || $fileupload['1'] == 'png' || $fileupload['1'] == 'webp')
                                <img src="{{ asset('fileupload/' . $data->fileupload) }}" height="100" width="100">
                            @else
                                <a href="{{ route('file.download', $data->id) }}" class="btn btn-info"><i
                                        class="ti ti-arrow-down text-white"></i></a>
                            @endif
                        </td>
                    @else
                        <td>No file uploaded</td>
                    @endif
                </tr>
            </table>
        </div>
    </div>
</div>
