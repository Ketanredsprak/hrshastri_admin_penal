<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingShifts extends Model
{
     protected $table = 'working_shifts';

     protected $fillable = [
          "name",
          "start_time",
          "end_time",
          "flexi_hours",
          "flexi_days",
          "status",
          "created_by",
          "created_at",
          "updated_at",
     ];
}
