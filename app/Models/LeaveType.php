<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PayslipType;
use App\Models\UserLeaveBalanceYear;

class LeaveType extends Model
{
     protected $fillable = [
          'title',
          'days',
          'no_leave',
          'play_slab_id',
          'per_month_leave',
          'created_by',
     ];

     public function playslab()
     {
          return $this->belongsTo(PayslipType::class, 'play_slab_id');
     }

     public function leaves()
     {
          return $this->belongsTo(Leave::class, 'leave_type_id');
     }

     public function UserLeaveBalanceYear()
     {
          return $this->belongsTo(UserLeaveBalanceYear::class, 'leave_type_id');
     }

     public function getTitleWithPaySlabNameAttribute()
     {
          $payslipType = PayslipType::where("id", $this->play_slab_id)->first();
          if ($payslipType != null)
          {
               return $this->title . " - " . $payslipType->name;
          }

          return $this->title;
     }
}
