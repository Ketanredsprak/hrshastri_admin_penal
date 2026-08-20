<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    protected $table = 'categories';

    protected $fillable = [
        'id',
        'visit_type_id',
        'grade_type_id',
        'category_id',
        'amount',
        'amount_status',
        'created_at',
        'updated_at',
        'created_by',
        'description',
        'status',
    ];

    public function visittypeData(){
        return $this->belongsTo(VisitType::class,'visit_type_id');
    }

    public function gradetypeData(){
        return $this->belongsTo(GradeType::class,'grade_type_id');
    }

    public function CategorytypeData(){
        return $this->belongsTo(CategoriesType::class,'category_id');
    }

    

}
