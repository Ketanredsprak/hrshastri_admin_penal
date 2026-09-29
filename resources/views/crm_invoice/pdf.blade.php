<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        @page { margin: 22px 24px 28px 24px; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1f2937;
            line-height: 1.35;
            margin: 0;
            padding: 0;
        }
        table { width: 100%; border-collapse: collapse; }
        .w-100 { width: 100%; }
        .seller-name {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 3px 0;
        }
        .seller-address {
            color: #4b5563;
            white-space: pre-line;
            font-size: 9.5px;
            margin: 0;
        }
        .gstin-line { margin-top: 4px; font-size: 10px; }
        .tax-title {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.4px;
            margin: 0 0 8px 0;
            text-align: right;
        }
        .meta { width: 100%; }
        .meta td { padding: 1px 0; font-size: 9.5px; vertical-align: top; }
        .meta .k { color: #6b7280; width: 48%; text-align: right; padding-right: 6px; }
        .meta .v { width: 52%; text-align: left; font-weight: bold; }
        .section-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin: 0 0 3px 0;
            font-weight: bold;
        }
        .party-box {
            border: 1px solid #9ca3af;
            padding: 7px 8px;
            min-height: 78px;
        }
        .party-box .name { font-weight: bold; font-size: 10.5px; margin-bottom: 2px; }
        .party-box .addr { color: #374151; white-space: pre-line; font-size: 9.5px; }
        .items { margin-top: 10px; table-layout: fixed; }
        .items th {
            background: #f3f4f6;
            border: 1px solid #9ca3af;
            padding: 4px 3px;
            font-size: 8.5px;
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
        }
        .items td {
            border: 1px solid #9ca3af;
            padding: 5px 3px;
            vertical-align: top;
            font-size: 9px;
            word-wrap: break-word;
        }
        .items .desc { text-align: left; padding-left: 5px; }
        .items .desc .title { font-weight: bold; display: block; margin-bottom: 2px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .muted { color: #6b7280; }
        .totals-wrap { margin-top: 0; }
        .totals {
            width: 100%;
            border: 1px solid #9ca3af;
        }
        .totals td {
            border-bottom: 1px solid #d1d5db;
            padding: 5px 7px;
            font-size: 9.5px;
        }
        .totals tr:last-child td { border-bottom: none; }
        .totals .label { text-align: left; }
        .totals .amt { text-align: right; width: 38%; }
        .totals .grand td {
            font-size: 11px;
            font-weight: bold;
            background: #f9fafb;
        }
        .words-box {
            margin-top: 8px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            padding: 7px 8px;
        }
        .words-box .lbl { font-weight: bold; font-size: 9px; color: #4b5563; margin-bottom: 2px; }
        .footer td { vertical-align: top; padding-top: 12px; }
        .bank-block {
            margin-top: 8px;
            white-space: pre-line;
            font-size: 9.5px;
            line-height: 1.4;
        }
        .sign-wrap { text-align: center; padding-top: 28px; }
        .sign-img { max-height: 48px; margin-bottom: 4px; }
        .sign-line {
            border-top: 1px solid #6b7280;
            display: inline-block;
            min-width: 150px;
            padding-top: 4px;
            font-size: 9px;
            color: #374151;
        }
        .logo { max-height: 42px; margin-bottom: 4px; }
        .page-no { text-align: center; color: #9ca3af; font-size: 8px; margin-top: 10px; }
    </style>
</head>
<body>
@php
    $sellerName = $invoice->seller_name_snapshot ?: ($settings->seller_name ?: 'HR SHASTRI TECH LLP');
    $sellerAddress = $invoice->seller_address_snapshot ?: ($settings->seller_address ?: '');
    $sellerGstin = $invoice->seller_gstin ?: ($settings->seller_gstin ?: '');
    $place = trim(($invoice->place_of_supply ?: ($settings->seller_state ?: 'Gujarat'))
        . (!empty($invoice->place_of_supply_code ?: $settings->seller_state_code)
            ? ' (' . ($invoice->place_of_supply_code ?: $settings->seller_state_code) . ')'
            : ''));
    $shipName = $invoice->ship_to_name ?: $invoice->company_name;
    $shipAddress = $invoice->ship_to_address ?: $invoice->company_address;
    $ifsc = $settings->bank_ifsc ?: '';
    $ifscSpoken = '';
    if ($ifsc !== '' && preg_match('/^([A-Za-z]+)(0+)(\d+)$/', $ifsc, $m)) {
        $zeroCount = strlen($m[2]);
        $zeroWord = match ($zeroCount) {
            1 => 'ZERO',
            2 => 'TWO TIMES ZERO',
            3 => 'THREE TIMES ZERO',
            default => $zeroCount . ' ZEROS',
        };
        $ifscSpoken = strtoupper($m[1]) . ' ' . $zeroWord . ' ' . $m[3];
    }
    $bankLines = array_filter([
        $settings->seller_name ?: $sellerName,
        $settings->bank_name,
        $settings->bank_account_no ? ('ACCOUNT NO -' . $settings->bank_account_no) : null,
        $ifsc ? ('IFS CODE - ' . $ifsc . ($ifscSpoken ? ' (' . $ifscSpoken . ')' : '')) : null,
        $settings->bank_swift ? ('SWIFT CODE - ' . $settings->bank_swift) : null,
    ]);
    $cgstPctLabel = rtrim(rtrim(number_format((float) ($invoice->cgst_percent ?? 0), 2, '.', ''), '0'), '.');
    $sgstPctLabel = rtrim(rtrim(number_format((float) ($invoice->sgst_percent ?? 0), 2, '.', ''), '0'), '.');
@endphp

{{-- Header: Seller + TAX INVOICE meta --}}
<table class="w-100">
    <tr>
        <td style="width:56%; vertical-align:top;">
            @if(!empty($settings->logo_path) && file_exists(storage_path('app/public/' . $settings->logo_path)))
                <img class="logo" src="{{ storage_path('app/public/' . $settings->logo_path) }}" alt="">
            @endif
            <div class="seller-name">{{ $sellerName }}</div>
            <div class="seller-address">{{ $sellerAddress }}</div>
            @if($sellerGstin)
                <div class="gstin-line"><strong>GSTIN</strong> {{ $sellerGstin }}</div>
            @endif
        </td>
        <td style="width:44%; vertical-align:top;">
            <div class="tax-title">TAX INVOICE</div>
            <table class="meta">
                <tr>
                    <td class="k"># :</td>
                    <td class="v">{{ $invoice->invoice_number }}</td>
                </tr>
                <tr>
                    <td class="k">Invoice Date :</td>
                    <td class="v">{{ optional($invoice->invoice_date)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="k">Terms :</td>
                    <td class="v">{{ $invoice->terms ?: 'Due on Receipt' }}</td>
                </tr>
                <tr>
                    <td class="k">Due Date :</td>
                    <td class="v">{{ optional($invoice->due_date)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td class="k">Place Of Supply :</td>
                    <td class="v">{{ $place }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

{{-- Bill To / Ship To --}}
<table class="w-100" style="margin-top:10px;">
    <tr>
        <td style="width:50%; padding-right:5px; vertical-align:top;">
            <div class="section-label">Bill To</div>
            <div class="party-box">
                <div class="name">{{ $invoice->company_name }}</div>
                <div class="addr">{{ $invoice->company_address }}</div>
                @if($invoice->company_gstin)
                    <div style="margin-top:5px;"><strong>GSTIN</strong> {{ $invoice->company_gstin }}</div>
                @endif
            </div>
        </td>
        <td style="width:50%; padding-left:5px; vertical-align:top;">
            <div class="section-label">Ship To</div>
            <div class="party-box">
                <div class="name">{{ $shipName }}</div>
                <div class="addr">{{ $shipAddress }}</div>
            </div>
        </td>
    </tr>
</table>

{{-- Items table --}}
<table class="items">
    <thead>
        <tr>
            <th style="width:4%;">#</th>
            <th style="width:26%;" class="text-left">Item &amp; Description</th>
            <th style="width:8%;">HSN/SAC</th>
            <th style="width:8%;">Qty</th>
            <th style="width:10%;">Rate</th>
            <th style="width:7%;">CGST %</th>
            <th style="width:10%;">CGST Amt</th>
            <th style="width:7%;">SGST %</th>
            <th style="width:10%;">SGST Amt</th>
            <th style="width:10%;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoice->items as $index => $item)
            @php
                $descLines = preg_split("/\r\n|\n|\r/", (string) $item->description) ?: [(string) $item->description];
            @endphp
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="desc">
                    @foreach($descLines as $dIndex => $line)
                        @if(trim($line) === '')
                            @continue
                        @endif
                        @if($dIndex === 0)
                            <span class="title">{{ $line }}</span>
                        @else
                            <div class="muted">{{ $line }}</div>
                        @endif
                    @endforeach
                </td>
                <td class="text-center">{{ $item->hsn_sac ?: ($settings->default_hsn_sac ?: '-') }}</td>
                <td class="text-center">
                    {{ number_format((float) $item->quantity, 2) }}
                    <div class="muted">{{ $item->unit ?: 'Nos' }}</div>
                </td>
                <td class="text-right">{{ number_format((float) $item->unit_price, 2) }}</td>
                <td class="text-center">{{ number_format((float) ($item->cgst_percent ?? $invoice->cgst_percent), 0) }}%</td>
                <td class="text-right">{{ number_format((float) ($item->cgst_amount ?? 0), 2) }}</td>
                <td class="text-center">{{ number_format((float) ($item->sgst_percent ?? $invoice->sgst_percent), 0) }}%</td>
                <td class="text-right">{{ number_format((float) ($item->sgst_amount ?? 0), 2) }}</td>
                <td class="text-right">{{ number_format((float) $item->line_total, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Totals --}}
<table class="w-100 totals-wrap" style="margin-top:6px;">
    <tr>
        <td style="width:52%;"></td>
        <td style="width:48%;">
            <table class="totals">
                <tr>
                    <td class="label">Sub Total</td>
                    <td class="amt">{{ number_format((float) $invoice->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td class="label">CGST{{ $cgstPctLabel }} ({{ $cgstPctLabel }}%)</td>
                    <td class="amt">{{ number_format((float) ($invoice->cgst_amount ?? 0), 2) }}</td>
                </tr>
                <tr>
                    <td class="label">SGST{{ $sgstPctLabel }} ({{ $sgstPctLabel }}%)</td>
                    <td class="amt">{{ number_format((float) ($invoice->sgst_amount ?? 0), 2) }}</td>
                </tr>
                <tr class="grand">
                    <td class="label">Total</td>
                    <td class="amt">Rs.{{ number_format((float) $invoice->total_amount, 2) }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="words-box">
    <div class="lbl">Total In Words</div>
    {{ $invoice->total_in_words ?: \App\Support\IndianCurrency::toWords((float) $invoice->total_amount) }}
</div>

<table class="w-100 footer">
    <tr>
        <td style="width:58%;">
            <strong>Notes</strong>
            <div class="muted" style="margin-top:2px;">{{ $invoice->notes ?: 'Thanks for your business.' }}</div>
            @if(!empty($bankLines))
                <div class="bank-block">
                    <strong>{{ $bankLines[0] ?? '' }}</strong>
@foreach(array_slice($bankLines, 1) as $line)
{{ $line }}
@endforeach
                </div>
            @elseif($invoice->payment_instructions)
                <div class="bank-block">{{ $invoice->payment_instructions }}</div>
            @endif
        </td>
        <td style="width:42%;">
            <div class="sign-wrap">
                @if(!empty($settings->signature_path) && file_exists(storage_path('app/public/' . $settings->signature_path)))
                    <img class="sign-img" src="{{ storage_path('app/public/' . $settings->signature_path) }}" alt=""><br>
                @endif
                <div class="sign-line">Authorized Signature</div>
            </div>
        </td>
    </tr>
</table>

<div class="page-no">1</div>
</body>
</html>
