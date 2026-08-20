<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesType extends Model
{
    use HasFactory;
    protected $table = 'category_type';

    protected $fillable = [
        'id',
        'name',
        'status',
        'created_by',
        'created_at',
        'updated_at',
    ];
}
