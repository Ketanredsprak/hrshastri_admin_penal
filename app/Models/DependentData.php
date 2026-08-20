<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DependentData extends Model
{
     use HasFactory;
     protected $table = 'dependent_data';

     protected $fillable = [
          'employee_id',
          'dependent_name',
          'dependent_relation',
          'dependent_dob',
          'emergency_phone_no',
          'created_at',
          'updated_at',
     ];

     public function getDependentDobAttribute($value)
     {
          return Auth::user()->dateFormat($value);
     }
}
