<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMobileMenu extends Model
{
    use HasFactory;

    use HasFactory;
    protected $table = 'company_mobile_menu';
    protected $fillable = [
        'id',
        'company_id',
        'menu_id',
        'created_by',        
        'created_at',
        'updated_at',
    ];
}
