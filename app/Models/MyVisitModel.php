<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyVisitModel extends Model
{
    use HasFactory;

    
    protected $table = 'my_visit';
    protected $fillable = [
        'id',
        'visit_name',
        'visit_type',
        'form_date',
        'to_date',
        'status',
        'claim_status',
        'userid',
        'description',
        'tier_id',
        'city_id',
        'status_changed_by',
        'reject_reason',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function tierData() {
        return $this->belongsTo(CityTier::class,'tier_id');
    }

    public function cityData() {
        return $this->belongsTo(CityModel::class,'city_id');
    }
    
    public function employee() {
        return $this->hasOne(Employee::class,'id','userid');
    }

    
}
