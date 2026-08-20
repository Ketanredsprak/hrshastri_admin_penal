<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEmailTemplate extends Model
{

    

    protected $table = "user_email_templates";

    protected $fillable = [
        'template_id',
        'user_id',
        'is_active',
    ];
}
