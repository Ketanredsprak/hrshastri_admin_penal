<table>
    <tbody>
        <tr>
            <td>Employee Email</td>

            @foreach ($monthDates as $monthDateRow)
                <td colspan="2" style="text-align: center;">{{ date('d-m-Y', strtotime($monthDateRow)) }}</td>
            @endforeach
        </tr>
        <tr>
            <td>#</td>

            @foreach ($monthDates as $monthDateRow)
                <td>In</td>
                <td>Out</td>
            @endforeach
        </tr>

    </tbody>
</table>
