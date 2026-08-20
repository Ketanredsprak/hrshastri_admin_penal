<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
     use HasFactory;

     protected $table = 'notification';

     protected $fillable = [
          'id',
          'user_id',
          'employee_id',
          'title',
          'details',
          'status',
          'date',
          'created_by',
          'created_at',
          'updated_at',
          'redirect',
     ];
}
