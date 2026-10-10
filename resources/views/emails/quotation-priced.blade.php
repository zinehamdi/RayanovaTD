<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Official Export Quotation {{ $quotation->reference_number }} — Rayanova T.D</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0B0E0D; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #E2E8F0;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #0B0E0D; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width: 600px; width: 100%; background-color: #121A15; border: 1px solid rgba(212, 175, 55, 0.35); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.6);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="padding: 35px 40px; background: linear-gradient(135deg, #1B3B2B 0%, #102219 100%); border-bottom: 1px solid rgba(212, 175, 55, 0.4); text-align: center;">
                            @php
                                $logoSrc = isset($message) && method_exists($message, 'embed') && file_exists(public_path('images/brand/logo-rayanova-td.jpg'))
                                    ? $message->embed(public_path('images/brand/logo-rayanova-td.jpg'))
                                    : (app()->environment('production') ? 'https://rayanovatd.com/images/brand/logo-rayanova-td.jpg' : asset('images/brand/logo-rayanova-td.jpg'));
                            @endphp
                            <div style="text-align: center; margin-bottom: 12px;">
                                <img src="{{ $logoSrc }}" alt="Rayanova T.D" width="130" style="display: inline-block; width: 130px; max-width: 130px; height: auto; border-radius: 12px; border: 1px solid rgba(212, 175, 55, 0.4); box-shadow: 0 8px 20px rgba(0,0,0,0.4);" />
                            </div>
                            <h1 style="margin: 10px 0 2px; font-size: 24px; font-weight: bold; letter-spacing: 2px; color: #FFFFFF;">RAYANOVA <span style="color: #D4AF37; font-size: 14px;">T.D</span></h1>
                            <p style="margin: 0; font-size: 11px; letter-spacing: 3px; color: #94A3B8; text-transform: uppercase;">Official Export Quotation & Pro-Forma Offer</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 35px 40px;">
                            <span style="display: inline-block; padding: 4px 12px; background-color: rgba(212, 175, 55, 0.15); border: 1px solid rgba(212, 175, 55, 0.4); border-radius: 50px; font-size: 11px; font-weight: bold; color: #D4AF37; text-transform: uppercase; margin-bottom: 15px;">
                                Offer Quoted • Valid for {{ $quotation->valid_until ? $quotation->valid_until->format('d M Y') : '14 Days' }}
                            </span>

                            <h2 style="margin: 0 0 15px; font-size: 20px; color: #FFFFFF; font-weight: 600;">Dear {{ $quotation->contact_name }},</h2>
                            
                            <p style="margin: 0 0 20px; font-size: 14px; line-height: 1.6; color: #CBD5E1;">
                                We are pleased to present our official quotation for <strong>{{ $quotation->company_name }}</strong> regarding your requested Tunisian commodities order.
                            </p>

                            <!-- Financial Summary Highlight Card -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background: linear-gradient(135deg, rgba(27,59,43,0.6) 0%, rgba(16,34,25,0.8) 100%); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 16px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 22px 25px; text-align: center;">
                                        <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #94A3B8;">Total Quotation Amount ({{ $quotation->incoterm }})</span>
                                        <div style="font-size: 32px; font-weight: bold; color: #D4AF37; margin: 8px 0;">
                                            {{ number_format($quotation->total_amount, 2) }} {{ $quotation->currency }}
                                        </div>
                                        <span style="font-size: 12px; color: #E2E8F0;">Destination Port : <strong>{{ $quotation->destination_port }}</strong></span>
                                    </td>
                                </tr>
                            </table>

                            <!-- Items Breakdown -->
                            <h3 style="margin: 0 0 12px; font-size: 13px; color: #FFFFFF; text-transform: uppercase; letter-spacing: 1px;">Pricing Breakdown</h3>
                            
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 25px; border-collapse: collapse;">
                                @foreach($quotation->items as $item)
                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                                        <td style="padding: 10px 0; font-size: 13px; color: #F1F5F9;">
                                            <strong>{{ $item->product->localized_title ?? 'Product' }}</strong><br>
                                            <span style="font-size: 11px; color: #94A3B8;">{{ ucwords(str_replace('_', ' ', $item->packaging_type)) }} • {{ $item->size }}</span>
                                        </td>
                                        <td align="right" style="padding: 10px 0; font-size: 13px; color: #E2E8F0;">
                                            {{ number_format($item->quantity, 0) }} {{ $item->unit }} @ {{ number_format($item->offered_unit_price, 2) }} {{ $quotation->currency }}<br>
                                            <strong style="color: #D4AF37;">{{ number_format($item->subtotal, 2) }} {{ $quotation->currency }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <!-- MAGIC LINK CALL TO ACTION (Main Feature) -->
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ $magicLink }}" style="display: inline-block; padding: 16px 36px; background: linear-gradient(135deg, #D4AF37 0%, #C29B38 100%); color: #0B0E0D; font-size: 15px; font-weight: bold; text-decoration: none; border-radius: 12px; box-shadow: 0 10px 25px rgba(212, 175, 55, 0.35);">
                                    👉 Open Quotation & Confirm Deal Online ➔
                                </a>
                                <div style="margin-top: 14px;">
                                    @php
                                        $pdfDownloadUrl = route('quote.download_pdf', [
                                            'locale' => 'en',
                                            'reference' => $quotation->reference_number,
                                            'token' => $quotation->signed_token,
                                        ]);
                                    @endphp
                                    <a href="{{ $pdfDownloadUrl }}" target="_blank" style="display: inline-block; padding: 11px 24px; background-color: rgba(255,255,255,0.08); border: 1px solid rgba(212,175,55,0.4); color: #E2E8F0; font-size: 12px; font-weight: bold; text-decoration: none; border-radius: 10px;">
                                        📄 Download Official Quotation (PDF)
                                    </a>
                                </div>
                                <p style="margin: 12px 0 0; font-size: 11px; color: #64748B;">
                                    Secure encrypted link • No password required • Modify quantities or approve in 1 click
                                </p>
                            </div>

                            <p style="margin: 0 0 10px; font-size: 12px; line-height: 1.6; color: #94A3B8;">
                                💡 <em>Note : You can also simply reply to this email to communicate directly with our trade desk.</em>
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 25px 40px; background-color: #0E1410; border-top: 1px solid rgba(255,255,255,0.08); text-align: center; font-size: 11px; color: #64748B;">
                            <p style="margin: 0 0 5px;">Rayanova T.D • Export Gateway : Port of Rades & Sfax, Tunisia</p>
                            <p style="margin: 0;">Certified EU Organic • US FDA Registered • COI Accredited</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
