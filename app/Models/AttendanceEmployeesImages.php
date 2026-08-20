<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AttendanceEmployeesImages extends Model
{

     protected $table = 'attendance_employees_images';

     protected $fillable = [
          "id",
          "attendance_employees_id",
          "image_name",
          "clock_type",
          "created_at",
          "updated_at"
     ];

     public function attendance()
     {
          return $this->hasOne(AttendanceEmployee::class, "attendance_employees_id");
     }
}
