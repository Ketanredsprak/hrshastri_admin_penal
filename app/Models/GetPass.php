<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class GetPass extends Model
{
     use HasFactory;
     protected $table = 'get_pass';

     protected $fillable = [
          'id',
          'name',
          'employee_id',
          'date',
          'status',
          'veryfy_status',
          'resone',
          'out_time',
          'in_time',
          'superior_user',
          'late_time',
          'approved_by',
          'approved_at',
          'created_by',
          'created_at',
          'updated_at',
     ];

     public function employee()
     {
          return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
     }

     public function approvedBy()
     {
          return $this->hasOne('App\Models\User', 'id', 'approved_by');
     }

     public function getDateAttribute($value)
     {
          return Auth::user()->dateFormat($value);
     }

     public function getOutTimeAttribute($value)
     {
          return Auth::user()->timeFormat($value);
     }

     public function getInTimeAttribute($value)
     {
          return Auth::user()->timeFormat($value);
     }

     public function getApprovedAtAttribute($value)
     {
          if (empty($value) || $value == '0000-00-00 00:00:00')
          {
               return '';
          }

          return Auth::user()->dateFormat($value) . ' ' . Auth::user()->timeFormat($value);
     }
}
