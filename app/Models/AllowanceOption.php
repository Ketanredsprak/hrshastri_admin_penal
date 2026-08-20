<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PayslipType;

class AllowanceOption extends Model
{
    protected $fillable = [
        'name',
        'play_slab_id',
        'type',
        'amount',
        'short_name',
        'percentage',
        'allowance_types',
        'created_by',
        'is_display',
        'is_display_slary_slip',
    ];

    public function playslab() {
        return $this->belongsTo(PayslipType::class,'play_slab_id');
    }

}