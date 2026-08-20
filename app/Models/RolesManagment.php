<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesManagment extends Model
{
    use HasFactory;
    protected $table = 'roles_managment';
    protected $fillable = [
        'id',
        'name',
        'status',
        'created_at',
        'updated_at',
    ];
}
