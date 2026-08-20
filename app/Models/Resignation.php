<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Resignation extends Model
{
    protected $fillable = [
        'employee_id',
        'notice_date',
        'resignation_date',
        'description',
        'status',
        'notes',
        'superior_user',
        'exit_notes',
        'created_by',
    ];

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id')->first();
    }

    public function employee_new()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id');
    }

    public function getNoticeDateAttribute($value)
    {
        return Auth::user()->dateFormat($value);
    }

    public function getResignationDateAttribute($value)
    {
        return Auth::user()->dateFormat($value);
    }
}
