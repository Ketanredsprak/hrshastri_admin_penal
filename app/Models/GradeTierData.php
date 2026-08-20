<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CategoriesType;

class GradeTierData extends Model
{
    use HasFactory;
    protected $table = 'grade_tier_data';

    protected $fillable = [
        'id',
        'grade_id',
        'tier_id',
        'expense_id',
        'amount',
        'is_actual',
        'created_by',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function expenseData() {
        return $this->belongsTo(CategoriesType::class,'expense_id');
    }
}
