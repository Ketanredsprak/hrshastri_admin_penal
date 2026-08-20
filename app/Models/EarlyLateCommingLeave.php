<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EarlyLateCommingLeave extends Model
{
    use HasFactory;

    protected $table = 'cearly_late_comming_leave';

    protected $fillable = [
        'id',
        'name',
        'employee_id',
        'date',
        'veryfy_status',
        'resone',
        'out_time',
        'in_time',
        'superior_user',
        'late_time',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
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
}
