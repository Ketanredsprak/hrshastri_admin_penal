<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PayslipType;

class EmployeeDeductionsDetails extends Model
{
     protected $table = 'employee_deductions_details';

     protected $fillable = [
          "employees_id",
          "deduction_type",
          "deduction_id",
          "amount",
          "amount_type",
          "status",
          "created_by",
          "created_at",
          "updated_at",
     ];
}
