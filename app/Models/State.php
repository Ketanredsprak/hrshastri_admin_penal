<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = "state";
    protected $fillable = [
        'state_id',
        'country_id',
        'state_title',
        'state_description',
        'status',
    ];

    public $timestamps = false;
}
