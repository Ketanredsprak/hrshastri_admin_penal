@foreach ($employees as $employee)
    <tr>
        <td>{{ $employee->employee_id ?? '' }}</td>
        <td>{{ $employee->name ?? '' }}</td>
        <td>{{ $employee->branch->name ?? '' }}</td>
        <td>{{ $employee->designation->name ?? '' }}</td>

        @foreach ($monthDates as $monthDate)
            @php
                $attendance = $attendanceData[$monthDate] ?? null;
                $workingHours = 0;
                $late = '00:00';
                $earlyLeaving = '00:00';
            @endphp

            @if (!empty($employeesWithMaxAttendance[$monthDate]) && $employeesWithMaxAttendance[$monthDate] > 0)
                @php
                    $checkData = $attendance['attendanceForDate']->where('employee_id', $employee->id);
                @endphp

                @foreach ($checkData as $childRow)
                    @php
                        $clockInDateTime = '';
                        $clockOutDateTime = '';
                        
                        if (empty($childRow->clock_in_date_time) || $childRow->clock_in_date_time == '0000-00-00 00:00:00') {
                            $clockInDateTime = $childRow->getRawOriginal('date') . ' ' . $childRow->clock_in;
                        } else {
                            $clockInDateTime = $childRow->clock_in_date_time;
                        }
                        
                        if (empty($childRow->clock_out_date_time) || $childRow->clock_out_date_time == '0000-00-00 00:00:00') {
                            $clockOutDateTime = $childRow->getRawOriginal('date') . ' ' . $childRow->clock_out;
                        } else {
                            $clockOutDateTime = $childRow->clock_out_date_time;
                        }
                        
                        if ($childRow->clock_in != '00:00:00' || $childRow->clock_out != '00:00:00') {
                            if ($late == '00:00' && $childRow->late != '00:00:00') {
                                $late = date('H:i', strtotime($childRow->late));
                            }
                            $earlyLeaving = date('H:i', strtotime($childRow->early_leaving));
                            $from = new DateTime($clockInDateTime);
                            $to = new DateTime($clockOutDateTime);
                            $diff = $from->diff($to);
                            $totalMinutes =
                                    $diff->i +
                                    $diff->h * 60 +
                                    $diff->days * 24 * 60;

                            
                            $workingHours += $totalMinutes;
                        }
                    @endphp

                    <td class="clockIn">
                    @php
                        if ($childRow->clock_in != '' && $childRow->clock_in != '00:00:00') {
                            echo \Auth::user()->dateFormat($clockInDateTime) . ' ' . \Auth::user()->timeFormat($clockInDateTime);
                        }
                    @endphp
                    </td>
                    <td class="clockOut">
                    @php
                        if ($childRow->clock_out != '' && $childRow->clock_out != '00:00:00') {
                            echo \Auth::user()->dateFormat($clockOutDateTime) . ' ' . \Auth::user()->timeFormat($clockOutDateTime);
                        }
                    @endphp</td>
                    
                @endforeach

                @for ($i = 1; $i <= $employeesWithMaxAttendance[$monthDate] - $checkData->count(); $i++)
                    <td class="clockIn"></td>
                    <td class="clockOut"></td>
                @endfor
            @else
                <td class="clockIn"></td>
                <td class="clockOut"></td>
            @endif

            @php
                $working_hours = floor($workingHours / 60);
                $working_minutes = $workingHours % 60;
                $workingHours = $working_hours . ':' . $working_minutes;
            @endphp
            <td class="workingHours">{{ $workingHours }}</td>

            <td class="workingHours">{{ $late }}</td>

            <td class="workingHours">{{ $earlyLeaving }}</td>
        @endforeach
    </tr>
@endforeach
