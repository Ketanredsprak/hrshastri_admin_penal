<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PayslipType;

class DeductionOption extends Model
{
    protected $fillable = [
        'name',
        'play_slab_id',
        'type',
        'amount',
        'employees_id',
        'created_by',
        'is_display',
        'is_display_slary_slip',
    ];

    public function playslab() {
        return $this->belongsTo(PayslipType::class,'play_slab_id');
    }

}
