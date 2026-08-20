<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ApplyWFM extends Model
{
     use HasFactory;
     protected $table = 'apply_wfm';

     protected $fillable = [
          'id',
          'employee_id',
          'start_date',
          'end_date',
          'status',
          'created_by',
          'superior_user',
          'note',
          'created_at',
          'updated_at',
     ];

     public function employee()
     {
          return $this->belongsTo('App\Models\Employee', 'employee_id');
     }

     public function getStartDateAttribute($value)
     {
          return Auth::user()->dateFormat($value);
     }

     public function getEndDateAttribute($value)
     {
          return Auth::user()->dateFormat($value);
     }

     // start_date as start_date_formatted
     public function getStartDateFormattedAttribute($value)
     {
          return $this->getRawOriginal("start_date");
          return Auth::user()->dateFormat($this->start_date);
     }

     // end_date as end_date_formatted
     public function getEndDateFormattedAttribute($value)
     {
          return $this->getRawOriginal("end_date");
          return Auth::user()->dateFormat($this->end_date);
     }
}
