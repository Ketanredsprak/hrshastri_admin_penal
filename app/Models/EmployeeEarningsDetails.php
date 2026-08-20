<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PayslipType;

class EmployeeEarningsDetails extends Model
{
     protected $table = 'employee_earnings_details';

     protected $fillable = [
          "employees_id",
          "earning_type",
          "earning_id",
          "amount",
          "amount_type",
          "status",
          "created_by",
          "created_at",
          "updated_at",
     ];
}
