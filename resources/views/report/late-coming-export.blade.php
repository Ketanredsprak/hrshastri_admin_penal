<table>
    <tbody>
        <tr>
            <td>Employee Id</td>
            <td>Name</td>
            <td>Branch</td>
            <td>Designation</td>
            <td>Late Coming Count</td>
        </tr>
        @foreach ($data as $key => $employee)
            <tr>
                <td>{{ $employee->employee_id ?? '' }}</td>
                <td>{{ $employee->name ?? '' }}</td>
                <td>{{ $employee->branch->name ?? '' }}</td>
                <td>{{ $employee->designation->name ?? '' }}</td>
                <td>{{ $employeeLateDataFinal[$employee->id]['count'] ?? 0 }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
