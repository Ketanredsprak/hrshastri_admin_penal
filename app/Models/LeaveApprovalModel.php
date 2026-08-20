<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApprovalModel extends Model
{
    use HasFactory;

    protected $table = 'leave_approval';

    protected $fillable = [
        'id',
        'leave_status',
        'created_at',
        'updated_at',
        'status',
        'created_by',
    ];

}
