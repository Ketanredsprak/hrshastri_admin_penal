<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmInvoice extends Model
{
    protected $table = 'crm_invoices';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_GENERATED = 'generated';
    public const STATUS_SENT = 'sent';
    public const STATUS_PAID = 'paid';
    public const STATUS_CANCELLED = 'cancelled';

    public const SOURCE_AUTO = 'auto';
    public const SOURCE_MANUAL = 'manual';

    protected $fillable = [
        'invoice_number',
        'company_id',
        'company_name',
        'company_email',
        'company_address',
        'company_phone',
        'company_gstin',
        'ship_to_name',
        'ship_to_address',
        'place_of_supply',
        'place_of_supply_code',
        'terms',
        'billing_year',
        'billing_month',
        'invoice_date',
        'due_date',
        'source',
        'status',
        'auto_period_key',
        'active_employees_count',
        'active_users_count',
        'price_per_employee',
        'price_per_user',
        'subtotal',
        'cgst_percent',
        'sgst_percent',
        'cgst_amount',
        'sgst_amount',
        'tax_amount',
        'total_amount',
        'total_in_words',
        'seller_gstin',
        'seller_name_snapshot',
        'seller_address_snapshot',
        'currency',
        'notes',
        'payment_instructions',
        'pdf_path',
        'paid_at',
        'paid_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'billing_year' => 'integer',
        'billing_month' => 'integer',
        'active_employees_count' => 'integer',
        'active_users_count' => 'integer',
        'price_per_employee' => 'float',
        'price_per_user' => 'float',
        'subtotal' => 'float',
        'cgst_percent' => 'float',
        'sgst_percent' => 'float',
        'cgst_amount' => 'float',
        'sgst_amount' => 'float',
        'tax_amount' => 'float',
        'total_amount' => 'float',
    ];

    public function items()
    {
        return $this->hasMany(CrmInvoiceItem::class, 'invoice_id')->orderBy('sort_order');
    }

    public function emailLogs()
    {
        return $this->hasMany(CrmInvoiceEmailLog::class, 'invoice_id')->orderByDesc('id');
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function isLocked(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    public function isEditable(): bool
    {
        return !$this->isLocked() && $this->status !== self::STATUS_CANCELLED;
    }

    public function periodLabel(): string
    {
        return sprintf('%04d-%02d', $this->billing_year, $this->billing_month);
    }

    /**
     * True when this invoice did not charge for user seats (qty = 0).
     */
    public function usersNotBilled(): bool
    {
        return (int) ($this->active_users_count ?? 0) === 0;
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_PAID => 'bg-success',
            self::STATUS_SENT => 'bg-info',
            self::STATUS_GENERATED => 'bg-primary',
            self::STATUS_CANCELLED => 'bg-danger',
            self::STATUS_DRAFT => 'bg-secondary',
            default => 'bg-secondary',
        };
    }
}
