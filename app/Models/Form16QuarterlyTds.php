<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form16QuarterlyTds extends Model
{
    protected $table = 'form16_quarterly_tds';

    protected $fillable = [
        'form16_id',
        'quarter',
        'receipt_number',
        'amount_paid_credited',
        'tax_deducted',
        'tax_deposited',
        'bsr_code',
        'challan_date',
        'challan_serial',
        'notes',
    ];

    protected $casts = [
        'challan_date' => 'date',
        'amount_paid_credited' => 'float',
        'tax_deducted' => 'float',
        'tax_deposited' => 'float',
    ];

    public function form16()
    {
        return $this->belongsTo(Form16Record::class, 'form16_id');
    }
}
