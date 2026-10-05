<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form16EmailLog extends Model
{
    protected $table = 'form16_email_logs';

    protected $fillable = [
        'form16_id',
        'company_id',
        'employee_id',
        'email',
        'subject',
        'status',
        'error_message',
        'attempt',
        'sent_by',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function form16()
    {
        return $this->belongsTo(Form16Record::class, 'form16_id');
    }
}
