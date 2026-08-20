<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsOptionModel extends Model
{
    use HasFactory;
    
    protected $table = 'is_select_option';
    protected $fillable = [
        'id',
        'role_id',
        'name',
        'status',
        'created_at',
        'updated_at',
    ];
    
}
