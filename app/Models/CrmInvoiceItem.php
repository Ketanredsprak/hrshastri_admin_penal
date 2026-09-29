<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrmInvoiceItem extends Model
{
    protected $table = 'crm_invoice_items';

    protected $fillable = [
        'invoice_id',
        'description',
        'hsn_sac',
        'unit',
        'quantity',
        'unit_price',
        'line_total',
        'cgst_percent',
        'cgst_amount',
        'sgst_percent',
        'sgst_amount',
        'sort_order',
    ];

    protected $casts = [
        'quantity' => 'float',
        'unit_price' => 'float',
        'line_total' => 'float',
        'cgst_percent' => 'float',
        'cgst_amount' => 'float',
        'sgst_percent' => 'float',
        'sgst_amount' => 'float',
        'sort_order' => 'integer',
    ];

    public function invoice()
    {
        return $this->belongsTo(CrmInvoice::class, 'invoice_id');
    }
}
