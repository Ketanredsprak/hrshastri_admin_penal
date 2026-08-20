{{ Form::open(['url' => 'my_claim/changeaction_hr', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <table class="table modal-table" id="pc-dt-simple">
                <tr role="row">
                    <th>{{ __('Employee') }}</th>
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

                <tr>
                    <th>{{ __('Amount') }}</th>
                    <td>{{ $data->total_amount }}</td>
                </tr>

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

                @php
                    $fileupload = explode('.', $data->fileupload);
                @endphp
                @if (count($fileupload) > 1)
                    <tr>
                        <th>{{ __('Image') }}</th>
                        <td>
                            @if (count($fileupload) > 1)
                                @if ($fileupload['1'] == 'jpg' || $fileupload['1'] == 'jpeg' || $fileupload['1'] == 'png' || $fileupload['1'] == 'webp')
                                    <img src="{{ asset('fileupload/' . $data->fileupload) }}" height="100"
                                        width="100">
                                @else
                                    <a href="{{ route('file.download', $data->id) }}" class="btn btn-info"><i
                                            class="ti ti-arrow-down text-white"></i></a>
                                @endif
                            @else
                                No file uploaded
                            @endif
                        </td>
                    </tr>
                @endif
                <input type="hidden" value="{{ $data->id }}" name="claim_id">
            </table>

            @if ($data->status != 'Approved')
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="">Reason</label>
                            <textarea name="reject_reason" class="form-control" id="" cols="40" rows="6"> {{ $data->reject_reason }} </textarea>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>


<div class="modal-footer">
    @if ($data->status != 'Approved')
        <input type="submit" value="{{ __('Approve') }}" class="btn btn-success rounded" name="claim_status">
    @endif
    @if ($data->status != 'Rejected' && $data->status != 'Approved')
        <input type="submit" value="{{ __('Reject') }}" id="reject" class="btn btn-danger rounded"
            name="claim_status">
    @endif

</div>


{{ Form::close() }}
