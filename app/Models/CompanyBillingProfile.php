<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyBillingProfile extends Model
{
    public const MODE_GLOBAL = 'global';
    public const MODE_CUSTOM = 'custom';

    /** Plan id used for custom employee/user pricing (Unlimited + invoice). */
    public const CUSTOM_PLAN_ID = 9;

    protected $fillable = [
        'company_id',
        'billing_mode',
        'price_per_employee',
        'price_per_user',
        'invoice_plan_id',
        'manual_invoice_only',
    ];

    protected $casts = [
        'price_per_employee' => 'float',
        'price_per_user' => 'float',
        'manual_invoice_only' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function isCustom(): bool
    {
        return $this->billing_mode === self::MODE_CUSTOM;
    }

    public function isGlobal(): bool
    {
        return $this->billing_mode === self::MODE_GLOBAL;
    }

    public static function forCompany(int $companyId): ?self
    {
        return static::where('company_id', $companyId)->first();
    }
}
