<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = "district";
    protected $fillable = [
        'districtid',
        'district_title',
        'state_id',
        'district_description',
        'district_status',
    ];

    public $timestamps = false;

}
