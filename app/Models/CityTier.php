<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CityTier extends Model
{
    use HasFactory;

    protected $table = "city_tiers";
    protected $fillable = [
        'name',
        'citysid',
        'created_by',
    ];

    protected function Citysid(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }


}
