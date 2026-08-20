<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EdcutionTable extends Model
{
     use HasFactory;
     protected $table = 'eduction_table';

     protected $fillable = [
          'employee_id',
          'institute_name',
          'education',
          'specialization',
          'date_of_completion',
          'notes',
          'created_at',
          'updated_at',
     ];

     public function getDateOfCompletionAttribute($value)
     {
          return Auth::user()->dateFormat($value);
     }
}
