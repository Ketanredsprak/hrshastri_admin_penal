<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EXPTable extends Model
{
     use HasFactory;
     protected $table = 'exp_table';

     protected $fillable = [
          'id',
          'employee_id',
          'previous_company_name',
          'previous_job_title',
          'previous_from_date',
          'previous_to_date',
          'previous_company_designation_id',
          'experiance_Job_description',
          'created_at',
          'updated_at',
     ];

     public function getPreviousFromDateAttribute($value)
     {
          return Auth::user()->dateFormat($value);
     }


     public function getPreviousToDateAttribute($value)
     {
          return Auth::user()->dateFormat($value);
     }
}
