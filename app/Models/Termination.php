<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Termination extends Model
{
    protected $fillable = [
        'employee_id',
        'notice_date',
        'termination_date',
        'termination_type',
        'description',
        'created_by',
        'main_created_by',
    ];

    public function terminationType()
    {
        return $this->hasOne('App\Models\TerminationType', 'id', 'termination_type')->first();
    }

    public function employee()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'employee_id')->first();
    }

    public function getNoticeDateAttribute($value)
    {
        return Auth::user()->dateFormat($value);
    }

    public function getTerminationDateAttribute($value)
    {
        return Auth::user()->dateFormat($value);
    }
}
