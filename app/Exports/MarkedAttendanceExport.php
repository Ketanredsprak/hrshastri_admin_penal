<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\TimeSheet;
use App\Models\AttendanceEmployee;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class MarkedAttendanceExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if (Auth::user()->type == 'employee') {
            $emp = !empty(Auth::user()->employee) ? Auth::user()->employee->id : 0;
            $data = AttendanceEmployee::where('employee_id', $emp)->orderBy('id', 'DESC')->get();
        } else {
            if (Auth::user()->type == 'company') {
                $employee = Employee::select('id')->where('created_by', Auth::user()->creatorId());
            } else {
                // $employee = Employee::where('users_types_id',auth::id())->select('id')->where('created_by', \Auth::user()->creatorId());
                $employee = Employee::whereIn('id', selectedEmployee())->select('id')->where('created_by', Auth::user()->creatorId());
            }
            $data = AttendanceEmployee::whereIn('employee_id', $employee)->orderBy('id', 'DESC')->get();
        }

        foreach ($data as $k => $attendance) {
            $data[$k]["id"] = $k+1;
            $data[$k]["employee_id"] = !empty($attendance->employee) ? $attendance->employee->name : '';
            $data[$k]["date"] = $attendance->date;
            // $data[$k]["status"] = $attendance->status;
            // $data[$k]["clock_in"] = $attendance->clock_in;
            // $data[$k]["clock_out"] = $attendance->clock_out;
            $data[$k]["created_by"] = Employee::login_user($attendance->created_by);
            $data[$k]["clock_in_location"] = (!empty($attendance->clock_in_latitude)) ? "https://www.google.com/maps/search/?api=1&query={$attendance->clock_in_latitude},{$attendance->clock_in_longitude}" : '-';
            $data[$k]["clock_out_location"] = (!empty($attendance->clock_out_latitude)) ? "https://www.google.com/maps/search/?api=1&query={$attendance->clock_out_latitude},{$attendance->clock_out_longitude}" : '-';
            unset($attendance->created_at, $attendance->updated_at, $attendance->late, $attendance->early_leaving, $attendance->overtime, $attendance->total_rest, $attendance->clock_in_latitude, $attendance->clock_in_longitude, $attendance->clock_out_latitude, $attendance->clock_out_longitude, $attendance->image);
        }
        return $data;
    }
    public function headings(): array
    {
        return [
            "ID",
            "Employee Name",
            "Date",
            "Status",
            "Clock In",
            "Clock Out",
            "Created By",
            "Clock In Location",
            "Clock Out Location",
        ];
    }
}
