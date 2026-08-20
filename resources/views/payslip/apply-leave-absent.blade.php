{{ Form::open(['url' => 'payslip/apply_leave_absent_save/', 'method' => 'post']) }}
<div class="row">
    <div class="modal-body">
        <table>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee['name'] }}</td>
                </tr>
                @foreach ($employee['absentDates'] as $date)
                    <tr>
                        <td>{{ $date }}</td>
                        <td>
                            <select name="leavetype_{{ $employee['id'] }}_{{ $date }}">
                                @foreach ($employee['leavetypes'] as $leave)
                                    <option value="{{ $leave['id'] }}">{{ $leave['title'] }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </table>
        <div class="form-group">
            
        </div>
    </div>
</div>
<div class="modal-footer">
    
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
</div>
{{ Form::close() }}


