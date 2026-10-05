<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form16AuditLog extends Model
{
    protected $table = 'form16_audit_logs';

    protected $fillable = [
        'form16_id',
        'company_id',
        'employee_id',
        'user_id',
        'action',
        'metadata',
        'ip_address',
        'user_agent',
    ];

    public function form16()
    {
        return $this->belongsTo(Form16Record::class, 'form16_id');
    }
}
