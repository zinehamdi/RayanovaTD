<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>International Sales Contract {{ $contract->contract_number }}</title>
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
            margin-bottom: 18px;
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
            font-size: 14px;
            font-weight: bold;
            color: #D4AF37;
            text-transform: uppercase;
        }
        .parties-table {
            width: 100%;
            margin-bottom: 18px;
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
            margin-bottom: 18px;
        }
        .items-table th {
            background-color: #1B3B2B;
            color: #ffffff;
            font-size: 9px;
            text-transform: uppercase;
            padding: 6px 8px;
            text-align: left;
        }
        .items-table td {
            border-bottom: 1px solid #e2e8f0;
            padding: 7px 8px;
            font-size: 10px;
        }
        .total-box {
            text-align: right;
            margin-bottom: 18px;
        }
        .total-amount {
            font-size: 14px;
            font-weight: bold;
            color: #1B3B2B;
        }
        .clause-title {
            font-size: 11px;
            font-weight: bold;
            color: #1B3B2B;
            text-transform: uppercase;
            margin-top: 10px;
            margin-bottom: 4px;
        }
        .clause-text {
            font-size: 9.5px;
            color: #4a5568;
            margin-bottom: 8px;
            text-align: justify;
        }
        .bank-box {
            border: 1px dashed #D4AF37;
            background-color: #fbf8f2;
            padding: 8px 12px;
            margin-bottom: 15px;
            font-size: 9.5px;
        }
        .signatures-table {
            width: 100%;
            margin-top: 25px;
        }
        .sig-box {
            width: 48%;
            border-top: 1px solid #718096;
            padding-top: 6px;
            text-align: center;
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
                <div class="doc-badge-title">International Sales Contract</div>
                <div style="font-size: 10px; font-weight: bold; color: #1a202c; font-family: monospace;">
                    N°: {{ $contract->contract_number }}
                </div>
                <div style="font-size: 9px; color: #718096;">
                    Date: {{ $contract->created_at->format('d/m/Y') }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Parties Identification Table -->
    <table class="parties-table">
        <tr>
            <td class="party-box">
                <div class="party-title">THE SELLER (Vendeur / Exportateur)</div>
                <strong>RAYANOVA TRADING & DISTRIBUTION SARL</strong><br>
                Rue Omar Kaddah, Imm. Montplaisir, 2ème étage B25<br>
                Av. Khaireddine Pacha, Montplaisir, 1073 Tunis, Republic of Tunisia<br>
                <strong>Matricule Fiscal :</strong> 1975095/B/A/M/000 &nbsp;|&nbsp; <strong>RNE :</strong> 1975095B<br>
                Email: export@rayanovatd.com • Tel: +216 71 890 120
            </td>
            <td style="width: 4%;"></td>
            <td class="party-box">
                <div class="party-title">THE BUYER (Acheteur / Importateur)</div>
                <strong>{{ $quotation->company_name }}</strong><br>
                Attn: {{ $quotation->contact_name }}<br>
                Destination: {{ $quotation->destination_port }}, {{ $quotation->country }}<br>
                Tax / VAT ID: {{ $quotation->tax_number ?? 'N/A' }}<br>
                Email: {{ $quotation->email }}
            </td>
        </tr>
    </table>

    <!-- Article 1: Object & Line Items -->
    <div class="clause-title">Article 1 : Subject of the Contract & Goods Specifications</div>
    <div class="clause-text">
        The Seller undertakes to sell and supply, and the Buyer undertakes to purchase and receive the authentic Tunisian agricultural commodities detailed hereunder, subject to standard International Olive Council (COI) and Codex Alimentarius export tolerances:
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Description of Goods</th>
                <th>Packaging & Specification</th>
                <th>Quantity</th>
                <th>Unit Price ({{ $quotation->currency }})</th>
                <th>Total ({{ $quotation->currency }})</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product ? ($item->product->title['en'] ?? $item->product->localized_title ?? 'Export Commodity') : 'Export Commodity' }}</strong>
                        @if($item->is_private_label)
                            <br><span style="font-size: 8px; color: #D4AF37;">[Private Label OEM Customer Branding]</span>
                        @endif
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
                    <td style="font-weight: bold;">
                        {{ number_format($item->total_price, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        <span style="font-size: 11px; text-transform: uppercase;">Total Contract Value ({{ $quotation->incoterm }} {{ $quotation->destination_port }}):</span>
        <span class="total-amount">{{ number_format($quotation->total_amount, 2) }} {{ $quotation->currency }}</span>
    </div>

    <!-- Article 2 & 3: Delivery Terms and Payment -->
    <table style="width: 100%;">
        <tr>
            <td style="width: 48%; vertical-align: top;">
                <div class="clause-title">Article 2 : Incoterms & Shipping</div>
                <div class="clause-text">
                    • <strong>Delivery Terms:</strong> {{ $quotation->incoterm }} (Incoterms 2020 rules).<br>
                    • <strong>Port of Loading:</strong> Port de Radès / Sousse Commercial Harbor, Tunisia.<br>
                    • <strong>Port of Discharge:</strong> {{ $quotation->destination_port }} ({{ $quotation->country }}).<br>
                    • <strong>Shipping Method:</strong> {{ ucfirst(str_replace('_', ' ', $quotation->shipping_method)) }}.
                </div>
            </td>
            <td style="width: 4%;"></td>
            <td style="width: 48%; vertical-align: top;">
                <div class="clause-title">Article 3 : Payment Terms</div>
                <div class="clause-text">
                    {{ $contract->payment_terms }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Bank Details Box & Official RNE Verification Badge -->
    <table style="width: 100%; margin-bottom: 12px; border-collapse: collapse;">
        <tr>
            <td style="width: 58%; vertical-align: top;">
                <div class="bank-box" style="margin-bottom: 0;">
                    <strong>OFFICIAL SELLER BANKING PARTICULARS (T/T & L/C):</strong><br>
                    Beneficiary: <strong>{{ $contract->bank_details['beneficiary'] ?? 'RAYANOVA TRADING & DISTRIBUTION SARL' }}</strong><br>
                    Bank: {{ $contract->bank_details['bank_name'] ?? 'Banque Internationale Arabe de Tunisie (BIAT)' }}<br>
                    IBAN: <strong>{{ $contract->bank_details['iban'] ?? 'TN59 0800 1234 5678 9012 3456' }}</strong> • SWIFT: <strong>{{ $contract->bank_details['swift_bic'] ?? 'BIATTNTT' }}</strong>
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
                                <strong style="color: #1B3B2B; font-size: 8.5px; text-transform: uppercase;">RNE Legal Verification</strong><br>
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

    <!-- Article 4: Export Documents -->
    <div class="clause-title">Article 4 : Official Shipping Documents</div>
    <div class="clause-text">
        The Seller shall furnish the Buyer via courier (DHL) the following verified trade documents:
        @if(is_array($contract->required_documents))
            {{ implode(' • ', $contract->required_documents) }}
        @endif
    </div>

    <!-- Signatures -->
    <table class="signatures-table">
        <tr>
            <td class="sig-box">
                <strong>FOR THE SELLER / RAYANOVA T.D</strong><br>
                Export Trade Desk Director<br>
                <div style="height: 40px; margin-top: 10px;">
                    <span style="font-size: 14px; font-weight: bold; color: #1B3B2B; font-family: 'Times New Roman', serif;">[ RAYANOVA T.D SEAL & SIGNATURE ]</span>
                </div>
                Date: {{ date('d/m/Y') }}
            </td>
            <td style="width: 4%;"></td>
            <td class="sig-box">
                <strong>FOR THE BUYER / {{ strtoupper($quotation->company_name) }}</strong><br>
                Authorized Procurement Officer: {{ $quotation->contact_name }}<br>
                <div style="height: 40px; margin-top: 10px;">
                    <span style="font-size: 14px; color: #718096; font-style: italic;">[ Accepted & Countersigned ]</span>
                </div>
                Date: ________________________
            </td>
        </tr>
    </table>

</body>
</html>
