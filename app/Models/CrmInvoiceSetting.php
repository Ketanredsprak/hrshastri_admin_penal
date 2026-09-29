<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmInvoiceSetting extends Model
{
    protected $table = 'crm_invoice_settings';

    protected $fillable = [
        'price_per_employee',
        'cgst_percent',
        'sgst_percent',
        'currency',
        'due_days',
        'invoice_prefix',
        'next_number',
        'use_fy_numbering',
        'seller_name',
        'seller_address',
        'seller_email',
        'seller_phone',
        'seller_gstin',
        'seller_state',
        'seller_state_code',
        'product_name',
        'logo_path',
        'signature_path',
        'payment_instructions',
        'bank_name',
        'bank_account_no',
        'bank_ifsc',
        'bank_swift',
        'default_hsn_sac',
        'default_terms',
        'default_notes',
        'auto_email_on_generate',
        'auto_generate_enabled',
        'skip_zero_employee',
    ];

    protected $casts = [
        'price_per_employee' => 'float',
        'cgst_percent' => 'float',
        'sgst_percent' => 'float',
        'due_days' => 'integer',
        'next_number' => 'integer',
        'use_fy_numbering' => 'boolean',
        'auto_email_on_generate' => 'boolean',
        'auto_generate_enabled' => 'boolean',
        'skip_zero_employee' => 'boolean',
    ];

    public static function current(): self
    {
        $row = static::query()->orderBy('id')->first();
        if ($row) {
            return $row;
        }

        return static::create([
            'price_per_employee' => 200,
            'cgst_percent' => 9,
            'sgst_percent' => 9,
            'currency' => 'INR',
            'due_days' => 15,
            'invoice_prefix' => 'INV',
            'next_number' => 1,
            'use_fy_numbering' => true,
            'seller_name' => 'HR SHASTRI TECH LLP',
            'seller_address' => "5th Floor, Darshanam Oxy Park\nVasna Bhayli Road\nVadodara Gujarat 390015\nIndia",
            'seller_gstin' => '24AAQFH2769H1Z1',
            'seller_state' => 'Gujarat',
            'seller_state_code' => '24',
            'product_name' => 'HR Shastri',
            'bank_name' => 'AXIS BANK',
            'bank_account_no' => '922020049777281',
            'bank_ifsc' => 'UTIB0001491',
            'bank_swift' => 'AXISINBB013',
            'default_hsn_sac' => '997331',
            'default_terms' => 'Due on Receipt',
            'default_notes' => 'Thanks for your business.',
            'payment_instructions' => 'Please pay via bank transfer / UPI and share the UTR with support.',
            'auto_email_on_generate' => true,
            'auto_generate_enabled' => false,
            'skip_zero_employee' => true,
        ]);
    }
}
