<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeJoininglater extends Model
{
    use HasFactory;
    protected $table = 'employeejoininglater';
    protected $fillable = [
        'id ',
        'employee_id',
        'content',
        'type',
        'created_by',
        'created_at',
        'updated_at',
    ];

}
