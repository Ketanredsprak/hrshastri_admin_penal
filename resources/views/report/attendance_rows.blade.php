@foreach ($employeesAttendance as $attendance)
    <tr>
        <td>{{ $attendance['employee_id'] }}</td>
        <td>{{ $attendance['name'] }}</td>
        <td>{{ $attendance['branch'] }}</td>
        <td>{{ $attendance['designation'] }}</td>
        @foreach ($attendance['status'] as $status)
            <td>
                @if ($status == 'P')
                    <i class="badge bg-success p-2 rounded">{{ __('P') }}</i>
                @elseif($status == 'A')
                    <i class="badge bg-danger p-2 rounded">{{ __('A') }}</i>
                @elseif($status == 'L')
                    <i class="badge bg-info p-2 rounded">{{ __('L') }}</i>
                @elseif($status == 'HD')
                    <i class="badge bg-secondary p-2 rounded">{{ __('HD') }}</i>
                @elseif($status == 'H')
                    <i class="badge bg-warning p-2 rounded">{{ __('H') }}</i>
                @elseif($status == 'WO')
                    <i class="badge bg-dark p-2 rounded">{{ __('WO') }}</i>
                @endif
            </td>
        @endforeach
    </tr>
@endforeach