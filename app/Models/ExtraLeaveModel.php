<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraLeaveModel extends Model
{
    use HasFactory;
    public $table = 'extra_leave';
    protected $fillable = [
        'leave_id',
        'start_date',
        'end_date',
        'total_day',
        'leave_type_day',
        'leave_type',
        'used_leave',
        'leave_balance',
        'created_at',
        'updated_at',
        'created_by',
    ];

}
