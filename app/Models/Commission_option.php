<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission_option extends Model
{
    protected $table = "commission_options";
    protected $fillable = [
        'play_slab_id',
        'name',
        'short_name',
        'amount',
        'type',
        'created_by',
        'employees_id',
        'commision_types',
    ];

    public function playslab() {
        return $this->belongsTo(PayslipType::class,'play_slab_id');
    }

    
}
