<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmInvoiceEmailLog extends Model
{
    protected $table = 'crm_invoice_email_logs';

    protected $fillable = [
        'invoice_id',
        'recipients',
        'subject',
        'status',
        'error_message',
        'sent_by',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(CrmInvoice::class, 'invoice_id');
    }

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'sent' => 'bg-success',
            'failed' => 'bg-danger',
            'queued' => 'bg-warning text-dark',
            default => 'bg-secondary',
        };
    }
}
