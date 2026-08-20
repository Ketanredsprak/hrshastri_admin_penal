<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommuteType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_fixed',
        'amount',
        'grade_id',
        'status',
        'created_at',
        'updated_at',
        'created_by',
    ];

    public function grade()
    {
        return $this->hasOne(GradeType::class,'id', 'grade_id');
    }

}
