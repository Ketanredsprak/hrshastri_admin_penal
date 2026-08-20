<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AttendanceEmployee extends Model
{

     protected $table = 'attendance_employees';

     protected $fillable = [
          "employee_id",
          "date",
          "status",
          "clock_in",
          "clock_out",
          "late",
          "early_leaving",
          "overtime",
          "total_rest",
          "created_by",
          "created_at",
          "updated_at",
          "clock_in_latitude",
          "clock_in_longitude",
          "clock_out_latitude",
          "clock_out_longitude",
          "image",
          "is_flexi_hours_used",
          "geofencing_status",

          "clock_in_date_time",
          "clock_out_date_time",

          "clock_in_ip_address",
          "clock_out_ip_address",

          "clock_out_image",

          "clock_in_meta_data",
          "clock_out_meta_data",
     ];

     public function employees()
     {
          return $this->hasOne('App\Models\Employee', 'user_id', 'employee_id');
     }

     public function employee()
     {
          return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
     }

     public function getDateAttribute($value)
     {
          return Auth::user()->dateFormat($value);
     }


     public function getClockedInImageAttribute($value)
     {
          if (!empty(trim($this->image)) && $this->image != null)
          {
               return getFilePathPrefix() . "images/clock_in_out/" . $this->employee_id . "/" . $this->image;
          }
          return "";
     }

     public function getClockedOutImageAttribute($value)
     {
          if (!empty(trim($this->clock_out_image)) && $this->clock_out_image != null)
          {
               return getFilePathPrefix() . "images/clock_in_out/" . $this->employee_id . "/" . $this->clock_out_image;
          }
          return "";
     }
}
