<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitType extends Model
{
    use HasFactory;

    protected $table = 'visit_type';

    protected $fillable = [
        'name',
        'status',
        'created_by',
        'created_at',
        'updated_at',
    ];
}
