<table>
    <tbody>
        <tr>
            <td>Employee Id</td>
            <td>Name</td>
            <td>Branch</td>
            <td>Designation</td>

            @foreach ($monthDates as $monthDateRow)
                @php
                    $checkData = $data->where('attendance_date', $monthDateRow);

                    $columnsCount = 0;
                    if ($checkData != null) {
                        if ($checkData->groupBY('employee_id')->max() != null) {
                            $columnsCount = $checkData->groupBY('employee_id')->max()->count();
                        }
                    }

                @endphp

                @if ($columnsCount > 0)
                    <td colspan="{{ $columnsCount * 2 + 3 }}" style="text-align: center;">
                        {{ Auth::user()->dateFormat($monthDateRow) }}</td>
                @else
                    <td colspan="5" style="text-align: center;">{{ Auth::user()->dateFormat($monthDateRow) }}</td>
                @endif
            @endforeach
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

            @foreach ($monthDates as $monthDateRow)
                @php
                    $checkData = $data->where('attendance_date', $monthDateRow);

                    $columnsCount = 0;
                    if ($checkData != null) {
                        if ($checkData->groupBY('employee_id')->max() != null) {
                            $columnsCount = $checkData->groupBY('employee_id')->max()->count();
                        }
                    }
                @endphp

                @if ($columnsCount > 0)
                    @for ($i = 1; $i <= $columnsCount; $i++)
                        <td>In</td>
                        <td>Out</td>
                    @endfor
                @else
                    <td>In</td>
                    <td>Out</td>
                @endif
                <td>WH</td>
                <td>LC</td>
                <td>EL</td>
            @endforeach
        </tr>

        @foreach ($data->groupBy('employee_id') as $key => $row)
            @php
                $employeeData = getEmployeeDetails($key);
            @endphp
            <tr>
                <td style="border: 20px; border-color: black;">{{ $employeeData->employee_id ?? '' }}</td>
                <td style="border: 20px; border-color: black;">{{ $employeeData->name ?? '' }}</td>
                <td style="border: 20px; border-color: black;">{{ $employeeData->branch->name ?? '' }}</td>
                <td style="border: 20px; border-color: black;">{{ $employeeData->designation->name ?? '' }}
                </td>

                @foreach ($monthDates as $monthDateRow)
                    @php

                        $workingHours = 0;
                        $late = '00:00';
                        $earlyLeaving = '00:00';
                        $checkData = $data->where('attendance_date', $monthDateRow);

                        $columnsCount = 0;
                        if ($checkData != null) {
                            if ($checkData->groupBY('employee_id')->max() != null) {
                                $columnsCount = $checkData->groupBY('employee_id')->max()->count();
                            }
                        }
                    @endphp

                    @if ($columnsCount > 0)
                        @foreach ($checkData->where('employee_id', $key) as $childRow)
                            @php
                                $clockInDateTime = '';
                                $clockOutDateTime = '';

                                if (
                                    empty($childRow->clock_in_date_time) ||
                                    $childRow->clock_in_date_time == '0000-00-00 00:00:00'
                                ) {
                                    $clockInDateTime = $childRow->getRawOriginal('date') . ' ' . $childRow->clock_in;
                                } else {
                                    $clockInDateTime = $childRow->clock_in_date_time;
                                }

                                if (
                                    empty($childRow->clock_out_date_time) ||
                                    $childRow->clock_out_date_time == '0000-00-00 00:00:00'
                                ) {
                                    $clockOutDateTime = $childRow->getRawOriginal('date') . ' ' . $childRow->clock_out;
                                } else {
                                    $clockOutDateTime = $childRow->clock_out_date_time;
                                }
                            @endphp

                            {{-- New --}}
                            <td class="clockIn">
                                {{-- {{ \Auth::user()->dateFormat($clockInDateTime) . ' ' . \Auth::user()->timeFormat($clockInDateTime) }} --}}
                                @php
                                    if ($childRow->clock_in != '' && $childRow->clock_in != '00:00:00') {
                                        echo \Auth::user()->dateFormat($clockInDateTime) .
                                            ' ' .
                                            \Auth::user()->timeFormat($clockInDateTime);
                                    }
                                @endphp
                            </td>
                            <td class="clockOut">
                                {{-- {{ \Auth::user()->dateFormat($clockOutDateTime) . ' ' . \Auth::user()->timeFormat($clockOutDateTime) }} --}}
                                @php
                                    if ($childRow->clock_out != '' && $childRow->clock_out != '00:00:00') {
                                        echo \Auth::user()->dateFormat($clockOutDateTime) .
                                            ' ' .
                                            \Auth::user()->timeFormat($clockOutDateTime);
                                    }
                                @endphp
                            </td>
                            {{-- New --}}

                            @php

                                if ($childRow->clock_in != '00:00:00' && $childRow->clock_out != '00:00:00') {
                                    try {
                                        //    dd($clockInDateTime, $clockOutDateTime, $childRow);
                                        if ($late == '00:00' && $childRow->late != '00:00:00') {
                                            $late = date('H:i', strtotime($childRow->late));
                                        }
                                        $earlyLeaving = date('H:i', strtotime($childRow->early_leaving));
                                        $from = new DateTime($clockInDateTime);
                                        $to = new DateTime($clockOutDateTime);

                                        $diff = $from->diff($to);

                                        // Total working minutes
                                        $totalMinutes = $diff->i + $diff->h * 60 + $diff->days * 24 * 60;

                                        // Convert total minutes to hours with decimal representation
                                        $workingHours += $totalMinutes;
                                    } catch (Exception | Throwable $th) {
                                    }
                                }

                            @endphp
                        @endforeach

                        @for ($i = 1; $i <= $columnsCount - $checkData->where('employee_id', $key)->count(); $i++)
                            <td></td>
                            <td></td>
                        @endfor
                    @else
                        <td></td>
                        <td></td>
                    @endif
                    @php
                        $working_hours = floor($workingHours / 60);
                        $working_minutes = $workingHours % 60;
                        $workingHours = $working_hours . ':' . $working_minutes;
                    @endphp
                    <td>{{ $workingHours }}</td>
                    <td>{{ $late }}</td>
                    <td>{{ $earlyLeaving }}</td>
                @endforeach

            </tr>
        @endforeach

    </tbody>
</table>
