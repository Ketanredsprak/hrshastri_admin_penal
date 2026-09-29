<p>
    Dear {{ $invoice->company_name }},
</p>
<p>
    Greetings from <strong>{{ $settings->product_name ?: 'HR Shastri' }}</strong>.
    Please find attached TAX INVOICE <strong>{{ $invoice->invoice_number }}</strong>.
</p>
<p>
    <strong>Amount due:</strong> ₹{{ number_format($invoice->total_amount, 2) }}<br>
    <strong>In words:</strong> {{ $invoice->total_in_words ?: \App\Support\IndianCurrency::toWords((float) $invoice->total_amount) }}<br>
    <strong>Subtotal:</strong> ₹{{ number_format($invoice->subtotal, 2) }}<br>
    <strong>CGST ({{ number_format((float) ($invoice->cgst_percent ?? 0), 2) }}%):</strong>
    ₹{{ number_format((float) ($invoice->cgst_amount ?? 0), 2) }}<br>
    <strong>SGST ({{ number_format((float) ($invoice->sgst_percent ?? 0), 2) }}%):</strong>
    ₹{{ number_format((float) ($invoice->sgst_amount ?? 0), 2) }}<br>
    <strong>Invoice date:</strong> {{ optional($invoice->invoice_date)->format('d/m/Y') }}<br>
    <strong>Due date:</strong> {{ optional($invoice->due_date)->format('d/m/Y') }}<br>
    <strong>Place of supply:</strong>
    {{ $invoice->place_of_supply }}
    @if($invoice->place_of_supply_code) ({{ $invoice->place_of_supply_code }}) @endif
</p>
@if($invoice->notes)
    <p><strong>Notes:</strong> {{ $invoice->notes }}</p>
@endif
<p>
    Regards,<br>
    {{ $settings->seller_name ?: ($settings->product_name ?: 'HR Shastri') }} Team
</p>
