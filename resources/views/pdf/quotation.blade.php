<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Official Quotation {{ $quotation->reference_number }} — Rayanova T.D</title>
    <style>
        @page {
            margin: 28px 36px;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.45;
            color: #0f172a;
            background-color: #ffffff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .brand-title {
            font-size: 18px;
            font-weight: bold;
            color: #1B3B2B;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .brand-sub {
            font-size: 8.5px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: bold;
        }
        .doc-badge {
            text-align: right;
            vertical-align: top;
        }
        .doc-badge-title {
            font-size: 14px;
            font-weight: bold;
            color: #1B3B2B;
            text-transform: uppercase;
        }
        .parties-table {
            width: 100%;
            margin-bottom: 14px;
        }
        .party-box {
            width: 48%;
            border: 1px solid #cbd5e1;
            padding: 9px 12px;
            background-color: #f8fafc;
            border-radius: 4px;
            vertical-align: top;
            font-size: 9.5px;
        }
        .party-title {
            font-size: 9.5px;
            font-weight: bold;
            color: #1B3B2B;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }
        .logistics-bar {
            width: 100%;
            border: 1px solid #e2e8f0;
            background-color: #fbf8f2;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 14px;
            font-size: 9px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .items-table th {
            background-color: #1B3B2B;
            color: #ffffff;
            font-size: 8.5px;
            text-transform: uppercase;
            padding: 7px 9px;
            text-align: left;
            letter-spacing: 0.5px;
        }
        .items-table td {
            border-bottom: 1px solid #e2e8f0;
            padding: 7px 9px;
            font-size: 9.5px;
        }
        .items-table tr:nth-child(even) td {
            background-color: #fafbfc;
        }
        .totals-table {
            width: 48%;
            margin-left: auto;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .totals-table td {
            padding: 4px 8px;
            font-size: 9.5px;
        }
        .grand-total {
            font-size: 12px;
            font-weight: bold;
            color: #1B3B2B;
            border-top: 2px solid #D4AF37;
            background-color: #fbf8f2;
        }
        .compliance-box {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            border-radius: 4px;
            padding: 8px 12px;
            margin-bottom: 14px;
            font-size: 8.5px;
            line-height: 1.4;
            color: #334155;
        }
        .stamp-box {
            text-align: right;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('images/brand/logo-rayanova-td.jpg');
        $logoData = file_exists($logoPath) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath)) : null;
        $qrPath = public_path('images/brand/rne-verification-qr.png');
        $qrData = file_exists($qrPath) ? 'data:image/png;base64,' . base64_encode(file_get_contents($qrPath)) : null;
    @endphp

    <!-- Header Table -->
    <table class="header-table">
        <tr>
            @if($logoData)
                <td style="width: 60px; vertical-align: top;">
                    <img src="{{ $logoData }}" style="width: 52px; height: 52px; border-radius: 6px;" alt="Rayanova T.D">
                </td>
            @endif
            <td style="vertical-align: top; padding-left: 8px;">
                <div class="brand-title">RAYANOVA T.D</div>
                <div class="brand-sub">Rayanova Trading & Distribution SARL — International Agro-Export Division</div>
                <div style="font-size: 8px; color: #475569; margin-top: 3px; line-height: 1.4;">
                    Rue Omar Kaddah, Imm. Montplaisir, 2ème étage B25, Av. Khaireddine Pacha, 1073 Tunis, Republic of Tunisia<br>
                    <strong>Matricule Fiscal :</strong> 1975095/B/A/M/000 &nbsp;|&nbsp; <strong>RNE (Identifiant Unique) :</strong> 1975095B &nbsp;|&nbsp; <strong>Registre N° :</strong> B50148372026<br>
                    <strong>Extrait RNE :</strong> ER20260645525 &nbsp;|&nbsp; <strong>Capital :</strong> 20 000 TND &nbsp;|&nbsp; <strong>Gérance :</strong> M. Khaled Ben Ammar<br>
                    Email: export@rayanovatd.com &nbsp;|&nbsp; Web: www.rayanovatd.com &nbsp;|&nbsp; Tel: +216 71 890 120
                </div>
            </td>
            <td class="doc-badge">
                <div class="doc-badge-title">OFFICIAL PRO-FORMA QUOTATION</div>
                <div style="font-size: 11px; font-weight: bold; color: #D4AF37; font-family: monospace; margin-top: 2px;">
                    REF : {{ $quotation->reference_number }}
                </div>
                <div style="font-size: 8.5px; color: #64748b; margin-top: 3px;">
                    Date : {{ $quotation->created_at->format('d/m/Y') }}<br>
                    @if($quotation->valid_until)
                        Offer Valid Until : <strong style="color: #0f172a;">{{ $quotation->valid_until->format('d/m/Y') }}</strong><br>
                    @else
                        Offer Validity : 14 calendar days<br>
                    @endif
                    Status : <strong style="color: #1B3B2B; text-transform: uppercase;">{{ str_replace('_', ' ', $quotation->status) }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <!-- Parties Table -->
    <table class="parties-table">
        <tr>
            <td class="party-box">
                <div class="party-title">EXPORTER & BENEFICIARY</div>
                <strong style="color: #1B3B2B; font-size: 10px;">RAYANOVA TRADING & DISTRIBUTION SARL</strong><br>
                Rue Omar Kaddah, Imm. Montplaisir, 2ème étage B25<br>
                Av. Khaireddine Pacha, Montplaisir, 1073 Tunis, Republic of Tunisia<br>
                <strong>Matricule Fiscal :</strong> 1975095/B/A/M/000 &nbsp;|&nbsp; <strong>RNE :</strong> 1975095B (Reg. B50148372026)<br>
                <strong>Extrait RNE :</strong> ER20260645525 &nbsp;|&nbsp; <strong>Code Activité :</strong> 51700<br>
                <strong>Export Gateway :</strong> Port of Rades & Sfax, Tunisia<br>
                Email: export@rayanovatd.com &nbsp;|&nbsp; Tel: +216 71 890 120
            </td>
            <td style="width: 4%;"></td>
            <td class="party-box">
                <div class="party-title">BUYER / PROSPECTIVE IMPORTER</div>
                <strong style="color: #1B3B2B; font-size: 10px;">{{ $quotation->company_name }}</strong><br>
                Attn: <strong>{{ $quotation->contact_name }}</strong><br>
                Country of Import: <strong>{{ $quotation->country }}</strong><br>
                Destination Port / Hub: <strong>{{ $quotation->destination_port }}</strong><br>
                Tax / VAT Registration: {{ $quotation->tax_number ?? 'Not Specified' }}<br>
                Email: {{ $quotation->email }} &nbsp;|&nbsp; Tel / WA: {{ $quotation->whatsapp ?? $quotation->phone ?? 'On file' }}
            </td>
        </tr>
    </table>

    <!-- Maritime Shipping Terms & Trade Specifications -->
    <div class="logistics-bar">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 25%;">
                    <strong style="color: #64748b; font-size: 8px; text-transform: uppercase; display: block;">INCOTERM 2020 :</strong>
                    <span style="font-weight: bold; color: #1B3B2B; font-size: 11px;">{{ $quotation->incoterm }}</span>
                </td>
                <td style="width: 30%;">
                    <strong style="color: #64748b; font-size: 8px; text-transform: uppercase; display: block;">DESTINATION PORT :</strong>
                    <span style="font-weight: bold; color: #0f172a; font-size: 10px;">{{ $quotation->destination_port }}</span>
                </td>
                <td style="width: 25%;">
                    <strong style="color: #64748b; font-size: 8px; text-transform: uppercase; display: block;">LOGISTICS MODE :</strong>
                    <span style="font-weight: bold; color: #0f172a; font-size: 10px;">{{ strtoupper(str_replace('_', ' ', $quotation->shipping_method)) }}</span>
                </td>
                <td style="width: 20%; text-align: right;">
                    <strong style="color: #64748b; font-size: 8px; text-transform: uppercase; display: block;">CURRENCY :</strong>
                    <span style="font-weight: bold; color: #D4AF37; font-size: 11px;">{{ $quotation->currency ?? 'EUR' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">#</th>
                <th style="width: 40%;">COMMODITY & BOTANICAL DESCRIPTION</th>
                <th style="width: 20%;">PACKAGING & SPECS</th>
                <th style="width: 12%; text-align: center;">QUANTITY</th>
                <th style="width: 11%; text-align: right;">UNIT PRICE</th>
                <th style="width: 12%; text-align: right;">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quotation->items as $index => $item)
                <tr>
                    <td style="text-align: center; color: #64748b;">{{ $index + 1 }}</td>
                    <td>
                        <strong style="color: #1B3B2B;">{{ $item->product->localized_title ?? 'Tunisian Commodity' }}</strong>
                        @if($item->is_private_label)
                            <span style="color: #047857; font-size: 8px; font-weight: bold;">(Private Label OEM)</span>
                        @endif
                        <div style="font-size: 8px; color: #64748b;">
                            Origin: Republic of Tunisia • Category: {{ $item->product?->category?->localized_name ?? 'Agro-Export' }}
                        </div>
                    </td>
                    <td>
                        <strong>{{ ucwords(str_replace('_', ' ', $item->packaging_type)) }}</strong><br>
                        <span style="color: #64748b; font-size: 8.5px;">Unit Size: {{ $item->size }}</span>
                    </td>
                    <td style="text-align: center; font-weight: bold;">
                        {{ number_format($item->quantity, 0) }} {{ $item->unit }}
                    </td>
                    <td style="text-align: right; font-family: monospace;">
                        @if($item->offered_unit_price)
                            {{ number_format($item->offered_unit_price, 2) }}
                        @else
                            <span style="color: #94a3b8; font-size: 8px;">Pending</span>
                        @endif
                    </td>
                    <td style="text-align: right; font-family: monospace; font-weight: bold;">
                        @if($item->subtotal)
                            {{ number_format($item->subtotal, 2) }}
                        @else
                            <span style="color: #94a3b8; font-size: 8px;">Pending</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Financial Totals -->
    <table class="totals-table">
        @if($quotation->total_amount)
            <tr>
                <td style="color: #64748b;">Commodities Subtotal:</td>
                <td style="text-align: right; font-weight: bold; font-family: monospace;">
                    {{ number_format($quotation->items->sum('subtotal'), 2) }} {{ $quotation->currency }}
                </td>
            </tr>
            <tr>
                <td style="color: #64748b;">Freight & Maritime Handling ({{ $quotation->incoterm }}):</td>
                <td style="text-align: right; font-family: monospace;">
                    Included in quote
                </td>
            </tr>
            <tr class="grand-total">
                <td style="padding: 7px;">TOTAL PRO-FORMA OFFER :</td>
                <td style="text-align: right; padding: 7px; font-size: 13px; font-family: monospace;">
                    {{ number_format($quotation->total_amount, 2) }} {{ $quotation->currency }}
                </td>
            </tr>
        @else
            <tr class="grand-total">
                <td style="padding: 7px;">STATUS :</td>
                <td style="text-align: right; padding: 7px; font-size: 11px;">
                    Preliminary Quotation Dossier (Awaiting Vessel Schedule)
                </td>
            </tr>
        @endif
    </table>

    <!-- Commercial Terms & Compliance -->
    <div class="compliance-box">
        <strong style="color: #1B3B2B; font-size: 9px; text-transform: uppercase;">STANDARD EXPORT TERMS & COMPLIANCE GUARANTEE :</strong><br>
        1. <strong>Quality Certification :</strong> Delivered lots are certified 100% Tunisian Extra Virgin Olive Oil (acidity &le; 0.3% upon request) or Deglet Nour Premium Standard. Accompanying documents: EUR.1 Certificate of Origin, Phytosanitary Certificate, and COI Accredited Lab Certificate of Analysis (COA).<br>
        2. <strong>Payment Terms :</strong> 30% advance deposit upon contract signature, 70% against confirmed Bill of Lading (B/L) copy, or Irrevocable Letter of Credit (L/C) at sight opened with prime international bank.<br>
        3. <strong>Vessel & Lead Time :</strong> Container loading completed within 14 calendar days from final sales confirmation and vessel booking confirmation.
    </div>

    <!-- Official Stamp & Legal Verification -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <tr>
            <td style="width: 55%; vertical-align: bottom;">
                @if($qrData)
                    <table style="border: 1px solid #cbd5e1; background-color: #f8fafc; border-radius: 4px; padding: 6px 8px; width: 100%;">
                        <tr>
                            <td style="width: 44px; vertical-align: middle;">
                                <img src="{{ $qrData }}" style="width: 42px; height: 42px; border-radius: 2px;" alt="QR Verification">
                            </td>
                            <td style="padding-left: 8px; font-size: 7.5px; line-height: 1.35; color: #334155;">
                                <strong style="color: #1B3B2B; font-size: 8px; text-transform: uppercase;">Official Trade Entity Verification (RNE)</strong><br>
                                Extrait RNE : <strong>ER20260645525</strong> &nbsp;|&nbsp; Registre : <strong>B50148372026</strong> &nbsp;|&nbsp; Code : <strong>Y7XYX27QV3UUEO</strong><br>
                                <span style="color: #64748b;">Vérification : registre-entreprises.tn/rne-public/#/qr-code/validation</span>
                            </td>
                        </tr>
                    </table>
                @endif
            </td>
            <td style="width: 5%;"></td>
            <td style="width: 40%; vertical-align: top; text-align: right;">
                <div class="stamp-box">
                    <strong style="color: #1B3B2B; font-size: 9.5px;">RAYANOVA TRADING & DISTRIBUTION SARL</strong><br>
                    <span style="font-size: 8px; color: #64748b;">Authorized Export Directorate & Operations Desk</span><br>
                    <div style="font-size: 11px; font-weight: bold; color: #1B3B2B; margin-top: 8px; font-family: 'Times New Roman', serif; border: 1px dashed #D4AF37; display: inline-block; padding: 6px 12px; border-radius: 4px; background-color: #fbf8f2;">
                        [ OFFICIAL CORPORATE EXPORT SEAL ]
                    </div>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
