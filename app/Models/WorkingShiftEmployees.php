<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingShiftEmployees extends Model
{
     protected $table = 'working_shift_employees';

     protected $fillable = [
          "working_shifts_id",
          "employee_id",
     ];

     public $timestamps = false;
}
