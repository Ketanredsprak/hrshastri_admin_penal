<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PlansModule extends Model
{
    protected $table = "plans_modules";
    public $timestamps = false;

    protected $fillable = [
        'plans_id',
        'modules_id',
    ];

}
