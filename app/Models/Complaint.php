<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Complaint extends Model
{
    protected $fillable = [
        'complaint_from',
        'complaint_against',
        'title',
        'complaint_date',
        'description',
        'created_by',
        'main_created_by',
    ];


    public function complaintFromR()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'complaint_from');
    }

    public function complaintAgainstR()
    {
        return $this->hasOne('App\Models\Employee', 'id', 'complaint_against');
    }


    public function getComplaintDateAttribute($value)
    {
        return Auth::user()->dateFormat($value);
    }
}
