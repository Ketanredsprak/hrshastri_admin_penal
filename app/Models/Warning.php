<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Warning extends Model
{
    protected $fillable = [
        'warning_to',
        'warning_by',
        'subject',
        'warning_date',
        'description',
        'created_by',
        'main_created_by',
    ];

    public function warningTo()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'warning_to');
    }

    public function warningBy()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'warning_by');
    }

    public function getComplaintDateAttribute($value)
    {
        return Auth::user()->dateFormat($value);
    }
}
