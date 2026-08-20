<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Leave extends Model
{
     protected $fillable = [
          'employee_id',
          'leave_type_id',
          'applied_on',
          'start_date',
          'end_date',
          'total_leave_days',
          'leave_reason',
          'secound_leave_status',
          'remark',
          'status',
          'leave_day',
          'superior_user',
          'created_by',
          'update_logs',
          "deducted_leave_balance",
          "extra_leave_days"
     ];

     public function leaveType()
     {
          return $this->hasOne('App\Models\LeaveType', 'id', 'leave_type_id');
     }

     public function employees()
     {
          return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
     }

     // applied_on as applied_on_formatted
     public function getAppliedOnFormattedAttribute($value)
     {
          return Auth::user()->dateFormat($this->applied_on);
     }

     // start_date as start_date_formatted
     public function getStartDateFormattedAttribute($value)
     {
          return Auth::user()->dateFormat($this->start_date);
     }

     // end_date as end_date_formatted
     public function getEndDateFormattedAttribute($value)
     {
          return Auth::user()->dateFormat($this->end_date);
     }
}
