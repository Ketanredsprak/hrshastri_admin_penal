<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Holiday extends Model
{
     protected $fillable = [
          'end_date',
          'start_date',
          'occasion',
          'branch_id',
          'created_by',
          'status',
          'department_id',
     ];

     public function getStartDateAttribute($value)
     {
          return Auth::user()->dateFormat($value);
     }

     public function getEndDateAttribute($value)
     {
          return Auth::user()->dateFormat($value);
     }
}
