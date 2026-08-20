<!DOCTYPE html>

<html xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" lang="en">



<body style="font-family:Arial Unicode MS, Helvetica , Sans-Serif;">





    <div style="padding-top: 1cm; padding-bottom: 1cm;">

        <table style="table-layout: fixed; width: 100%;" cellspacing="0" cellpadding="0">

            <tbody>

                <tr>

                    <td>

                        <div style="font-size: 13px;text-align:left;">

                            {{ $company->name }}

                        </div>

                    </td>

                    <td width="10%">

                    </td>

                    <td>

                        <div style="font-size: 13px;text-align:center;">Form 7</div>

                    </td>

                    <td width="10%"></td>

                    <td>

                        <div style="font-size: 13px;text-align:right;">

                            Page :1

                        </div>

                    </td>

                </tr>

                <tr>

                    <td>

                        <div>Location : </div>

                    </td>

                    <td width="10%"></td>

                    <td>

                        <div style="font-size: 13px;text-align:center;">

                            REGISTER OF EMPLOYEE

                        </div>

                    </td>

                    <td width="10%">

                    </td>

                    <td>

                        <div style="font-size: 13px;text-align:right;"> {{ date('F-Y') }} </div>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

    <div>

        <table style="table-layout: fixed; width: 100%;" cellspacing="0" cellpadding="0">

            <thead>

                <tr>

                    <th width="20%" align="left" style="border-top: 1px solid #000; padding: 5px; font-size:13px">

                        Sr No

                    </th>

                    <th align="center" style="border-top: 1px solid #000; padding: 5px; font-size:13px">

                        Emp Code

                    </th>

                    <th align="center" style="border-top: 1px solid #000; padding: 5px; font-size:13px">

                        Name

                    </th>

                    <th align="right" style="border-top: 1px solid #000; padding: 5px; font-size:13px">

                        ESI No

                    </th>

                    <th align="center" style="border-top: 1px solid #000; padding: 5px; font-size:13px">

                        Present Days

                    </th>

                    <th align="center" style="border-top: 1px solid #000; padding: 5px; font-size:13px">

                        Gross Salary

                    </th>

                    <th align="right" style="border-top: 1px solid #000; padding: 5px; font-size:13px">

                        Tot ESI

                    </th>

                </tr>

            </thead>

            <tbody>

                <?php $totalsalary = 0; ?>

                <?php $Esictotalsalary = 0; ?>

                @foreach ($data as $row)

                    {{ $totalsalary += $row->net_payble }}



                    <?php $employee = App\Models\Employee::find($row->employee_id);
                    
                    $employeeAttendance = App\Models\AttendanceEmployee::where('employee_id', $row->employee_id)->count();

                    ?>

                    <tr>

                        <td style="border-top: 1px solid #000; padding: 5px;font-size:13px">

                            {{ $row->id }}

                        </td>

                        <td align="center" style="border-top: 1px solid #000; padding: 5px;font-size:13px">

                            {{ \Auth::user()->employeeIdFormat($row->employee_id) }}

                        </td>

                        <td align="center" style="border-top: 1px solid #000; padding: 5px;font-size:13px">



                            {{ $employee->name ?? '' }}

                        </td>

                        <td align="right" style="border-top: 1px solid #000; padding: 5px;font-size:13px">

                            {{ $employee->bank_identifier_code ?? '' }}

                        </td>

                        <td align="center" style="border-top: 1px solid #000; padding: 5px;font-size:13px">

                            {{ $employeeAttendance }}

                        </td>

                        <td align="center" style="border-top: 1px solid #000; padding: 5px;font-size:13px">

                            {{ $row->net_payble }}

                        </td>

                        <td align="right" style="border-top: 1px solid #000; padding: 5px;font-size:13px">

                            @if (!empty($row->Esic_tex))

                                <?php

                                echo ($row->net_payble * 4) / 100;

                                $Esictotalsalary += ($row->net_payble * 4) / 100;

                                ?>

                            @endif

                        </td>

                    </tr>

                @endforeach

                <tr>

                    <td colspan="5" align="left" style="border-top: 1px solid #000; padding: 5px;font-size:13px">

                        Total

                    </td>

                    <td colspan="2" align="right" style="border-top: 1px solid #000; padding: 5px;font-size:13px">

                        {{ $Esictotalsalary }}

                    </td>

                </tr>

            </tbody>

        </table>

    </div>



    <div style="border-top: 1px solid #000;">

        <table style="table-layout: fixed; width: 80%; border-collapse: collapse;">

            <tbody>

                <tr>

                    <td>

                        &nbsp;

                    </td>

                </tr>

                <tr>

                    <td align="left" width="50%"

                        style="padding: 5px; border-top:1px solid #000;border-bottom:1px solid #000;font-size:13px">

                        Total of Salary & OT:

                    </td>

                    <td align="left" width="20%"

                        style="padding: 5px; border-top:1px solid #000;border-bottom:1px solid #000;font-size:13px">

                        {{ $totalsalary }}

                    </td>

                </tr>

                <tr>

                    <td align="left" width="45%" style="padding: 5px;font-size:13px">

                        Employee`s Contribution (0.75 %):

                    </td>

                    <td align="left" width="20%" style="padding: 5px;font-size:13px">

                        00

                    </td>

                </tr>

                <tr>

                    <td align="left" width="45%" style="padding: 5px;font-size:13px">

                        Employer`s Contribution (3.25 %):

                    </td>

                    <td align="left" width="20%" style="padding: 5px;font-size:13px">

                        00

                    </td>

                </tr>

                <tr>

                    <td align="left" width="45%" style="padding: 5px;border-top:1px solid #000;font-size:13px">

                        Cheque Amount:

                    </td>

                    <td align="left" width="20%" style="padding: 5px;border-top:1px solid #000;font-size:13px">

                        00

                    </td>

                </tr>



            </tbody>

        </table>

    </div>



</body>



</html>

