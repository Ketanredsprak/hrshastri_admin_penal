<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PayslipType;
class LoanOption extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'play_slab_id',
        'type',
        'amount',
        'created_at',
        'updated_at',
        'created_by',
    ];

    public function playslab() {
        return $this->belongsTo(PayslipType::class,'play_slab_id');
    }

}
