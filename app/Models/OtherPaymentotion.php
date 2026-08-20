<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherPaymentotion extends Model
{
    use HasFactory;
    protected $table = 'other_payment_option';

    protected $fillable = [
        'name',
        'type',
        'amount',
        'play_slab_id',
        'created_by',
        'created_at',
        'updated_at',
        'employees_id',
    ];

    public function playslab() {
        return $this->belongsTo(PayslipType::class,'play_slab_id');
    }



}
