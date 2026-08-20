<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplates extends Model
{
     use HasFactory;

     protected $table = 'email_template';

     protected $fillable = [
          'id ',
          'title',
          'sbjects',
          'content',
          'created_by',
          'created_at',
          'updated_at',
     ];
}
