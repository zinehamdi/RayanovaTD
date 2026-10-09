<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Pro-Forma Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 30px 40px;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #1a202c;
            background-color: #ffffff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .brand-title {
            font-size: 20px;
            font-weight: bold;
            color: #1B3B2B;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .brand-sub {
            font-size: 9px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .doc-badge {
            text-align: right;
        }
        .doc-badge-title {
            font-size: 16px;
            font-weight: bold;
            color: #1B3B2B;
            text-transform: uppercase;
        }
        .parties-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .party-box {
            width: 48%;
            border: 1px solid #e2e8f0;
            padding: 10px;
            background-color: #f8fafc;
            border-radius: 4px;
            vertical-align: top;
        }
        .party-title {
            font-size: 10px;
            font-weight: bold;
            color: #1B3B2B;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #1B3B2B;
            color: #ffffff;
            font-size: 9px;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
        }
        .items-table td {
            border-bottom: 1px solid #e2e8f0;
            padding: 8px 10px;
            font-size: 10px;
        }
        .totals-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .totals-table td {
            padding: 5px 8px;
            font-size: 10px;
        }
        .grand-total {
            font-size: 13px;
            font-weight: bold;
            color: #1B3B2B;
            border-top: 2px solid #D4AF37;
            background-color: #fbf8f2;
        }
        .bank-box {
            border: 1px dashed #D4AF37;
            background-color: #fbf8f2;
            padding: 10px 14px;
            margin-bottom: 20px;
            font-size: 10px;
        }
        .stamp-box {
            text-align: right;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <!-- Header Table -->
    <table class="header-table">
        <tr>
            <td>
                <div class="brand-title">RAYANOVA T.D</div>
                <div class="brand-sub">Rayanova Trading & Distribution SARL — International Agro-Food Division</div>
                <div style="font-size: 8.5px; color: #475569; margin-top: 4px; line-height: 1.45;">
                    Rue Omar Kaddah, Imm. Montplaisir, 2ème étage B25, Av. Khaireddine Pacha, 1073 Tunis, Tunisie<br>
                    <strong>Matricule Fiscal :</strong> 1975095/B/A/M/000 &nbsp;|&nbsp; <strong>RNE (Identifiant Unique) :</strong> 1975095B &nbsp;|&nbsp; <strong>Capital :</strong> 20 000 TND
                </div>
            </td>
            <td class="doc-badge">
                <div class="doc-badge-title">PRO-FORMA INVOICE</div>
                <div style="font-size: 11px; font-weight: bold; color: #D4AF37; font-family: monospace;">
                    N°: {{ $invoice->invoice_number }}
                </div>
                <div style="font-size: 9px; color: #718096;">
                    Date: {{ $invoice->created_at->format('d/m/Y') }}<br>
                    Payment Due: {{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : '15 days' }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Parties Table -->
    <table class="parties-table">
        <tr>
            <td class="party-box">
                <div class="party-title">EXPORTER & BENEFICIARY</div>
                <strong>RAYANOVA TRADING & DISTRIBUTION SARL</strong><br>
                Rue Omar Kaddah, Imm. Montplaisir, 2ème étage B25<br>
                Av. Khaireddine Pacha, Montplaisir, 1073 Tunis, Republic of Tunisia<br>
                <strong>Matricule Fiscal :</strong> 1975095/B/A/M/000 &nbsp;|&nbsp; <strong>RNE :</strong> 1975095B<br>
                Email: export@rayanovatd.com • Tel: +216 71 890 120
            </td>
            <td style="width: 4%;"></td>
            <td class="party-box">
                <div class="party-title">CONSIGNEE / BUYER</div>
                <strong>{{ $quotation->company_name }}</strong><br>
                Attn: {{ $quotation->contact_name }}<br>
                {{ $quotation->destination_port }}, {{ $quotation->country }}<br>
                VAT / Tax ID: {{ $quotation->tax_number ?? 'N/A' }}<br>
                Contract Ref: {{ $contract ? $contract->contract_number : $quotation->reference_number }}
            </td>
        </tr>
    </table>

    <!-- Shipment Metadata -->
    <div style="background-color: #f1f5f9; padding: 6px 10px; margin-bottom: 15px; font-size: 9.5px; border-radius: 4px;">
        <strong>Port of Loading:</strong> Port de Radès / Sousse, Tunisia &nbsp;|&nbsp;
        <strong>Discharge Port:</strong> {{ $quotation->destination_port }} &nbsp;|&nbsp;
        <strong>Incoterm:</strong> {{ $quotation->incoterm }} &nbsp;|&nbsp;
        <strong>Terms of Payment:</strong> {{ $contract ? $contract->payment_terms : 'T/T & L/C' }}
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Item & Origin</th>
                <th>Packaging & Size</th>
                <th>Quantity</th>
                <th>Unit Price ({{ $invoice->currency }})</th>
                <th style="text-align: right;">Total Amount ({{ $invoice->currency }})</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product ? ($item->product->title['en'] ?? $item->product->localized_title ?? 'Export Commodity') : 'Export Commodity' }}</strong><br>
                        <span style="font-size: 8.5px; color: #718096;">Origin: Tunisia (Mediterranean Terroirs)</span>
                    </td>
                    <td>
                        {{ ucfirst(str_replace('_', ' ', $item->packaging_type)) }} ({{ $item->size }})
                    </td>
                    <td>
                        {{ number_format($item->quantity) }} {{ $item->unit }}
                    </td>
                    <td>
                        {{ number_format($item->unit_price, 2) }}
                    </td>
                    <td style="text-align: right; font-weight: bold;">
                        {{ number_format($item->total_price, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals Table -->
    <table class="totals-table">
        <tr>
            <td>Subtotal Commodity:</td>
            <td style="text-align: right; font-weight: bold;">{{ number_format($invoice->subtotal, 2) }} {{ $invoice->currency }}</td>
        </tr>
        @if($invoice->freight_cost > 0)
            <tr>
                <td>Ocean Freight ({{ $quotation->incoterm }}):</td>
                <td style="text-align: right;">{{ number_format($invoice->freight_cost, 2) }} {{ $invoice->currency }}</td>
            </tr>
        @endif
        @if($invoice->insurance_cost > 0)
            <tr>
                <td>Marine Insurance:</td>
                <td style="text-align: right;">{{ number_format($invoice->insurance_cost, 2) }} {{ $invoice->currency }}</td>
            </tr>
        @endif
        <tr class="grand-total">
            <td style="padding: 8px;">TOTAL TO PAY:</td>
            <td style="text-align: right; padding: 8px; font-size: 13px;">{{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency }}</td>
        </tr>
    </table>

    <!-- Official Bank Details & Official RNE Verification Badge -->
    <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse;">
        <tr>
            <td style="width: 58%; vertical-align: top;">
                <div class="bank-box" style="margin-bottom: 0;">
                    <strong style="color: #1B3B2B; font-size: 10px;">BANK DETAILS FOR WIRE REMITTANCE (SWIFT / T/T):</strong><br>
                    Beneficiary: <strong>RAYANOVA TRADING & DISTRIBUTION SARL</strong><br>
                    Bank: <strong>Banque Internationale Arabe de Tunisie (BIAT)</strong> — Head Office Tunis, Tunisia<br>
                    IBAN: <strong>TN59 0800 1234 5678 9012 3456</strong><br>
                    SWIFT / BIC: <strong>BIATTNTT</strong>
                </div>
            </td>
            <td style="width: 3%;"></td>
            <td style="width: 39%; vertical-align: top;">
                <div style="border: 1px solid #cbd5e1; background-color: #f8fafc; border-radius: 4px; padding: 7px 9px;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="width: 48px; vertical-align: middle;">
                                @php
                                    $qrPath = public_path('images/brand/rne-verification-qr.png');
                                    $qrData = file_exists($qrPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($qrPath)) : null;
                                @endphp
                                @if($qrData)
                                    <img src="{{ $qrData }}" style="width: 46px; height: 46px; border: 1px solid #cbd5e1; border-radius: 3px;" alt="RNE QR Code">
                                @endif
                            </td>
                            <td style="vertical-align: middle; padding-left: 8px; font-size: 8px; line-height: 1.35; color: #334155;">
                                <strong style="color: #1B3B2B; font-size: 8.5px; text-transform: uppercase;">RNE Document Verification</strong><br>
                                Extrait RNE : <strong>ER20260645525</strong><br>
                                Réf. Vérif : <strong style="font-family: monospace;">Y7XYX27QV3UUE0</strong><br>
                                <span style="color: #64748b; font-size: 7.5px;">Scan QR to verify legal standing on official RNE portal</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Official Seal -->
    <div class="stamp-box">
        <strong>RAYANOVA TRADING & DISTRIBUTION SARL</strong><br>
        <span style="font-size: 9px; color: #718096;">Authorized Signatory & Export Desk Operations</span><br>
        <div style="font-size: 13px; font-weight: bold; color: #1B3B2B; margin-top: 8px; font-family: 'Times New Roman', serif;">
            [ OFFICIAL CORPORATE EXPORT SEAL ]
        </div>
    </div>

</body>
</html>
