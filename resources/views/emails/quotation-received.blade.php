<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Quotation Request Received — Rayanova T.D</title>
</head>
<body style="margin: 0; padding: 0; background-color: #0B0E0D; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #E2E8F0;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #0B0E0D; padding: 40px 10px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="max-width: 600px; width: 100%; background-color: #121A15; border: 1px solid rgba(212, 175, 55, 0.25); border-radius: 20px; overflow: hidden; box-shadow: 0 16px 40px rgba(0,0,0,0.5);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="padding: 35px 40px; background: linear-gradient(135deg, #1B3B2B 0%, #102219 100%); border-bottom: 1px solid rgba(212, 175, 55, 0.3); text-align: center;">
                            <div style="font-size: 32px; line-height: 1;">🌿</div>
                            <h1 style="margin: 10px 0 2px; font-size: 24px; font-weight: bold; letter-spacing: 2px; color: #FFFFFF;">RAYANOVA <span style="color: #D4AF37; font-size: 14px;">T.D</span></h1>
                            <p style="margin: 0; font-size: 11px; letter-spacing: 3px; color: #94A3B8; text-transform: uppercase;">Trading & Global Distribution • Tunisia</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 35px 40px;">
                            <span style="display: inline-block; padding: 4px 12px; background-color: rgba(212, 175, 55, 0.12); border: 1px solid rgba(212, 175, 55, 0.3); border-radius: 50px; font-size: 11px; font-weight: bold; color: #D4AF37; text-transform: uppercase; margin-bottom: 15px;">
                                Dossier Registered • {{ $quotation->reference_number }}
                            </span>

                            <h2 style="margin: 0 0 15px; font-size: 20px; color: #FFFFFF; font-weight: 600;">Dear {{ $quotation->contact_name }},</h2>
                            
                            <p style="margin: 0 0 20px; font-size: 14px; line-height: 1.6; color: #CBD5E1;">
                                Thank you for your inquiry with <strong>Rayanova T.D</strong>. We have received your export quotation request on behalf of <strong>{{ $quotation->company_name }}</strong>.
                            </p>

                            <!-- Trade Specifications Box -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 15px 20px;">
                                        <p style="margin: 0 0 8px; font-size: 12px; color: #94A3B8;"><strong>Reference :</strong> <span style="color: #FFFFFF;">{{ $quotation->reference_number }}</span></p>
                                        <p style="margin: 0 0 8px; font-size: 12px; color: #94A3B8;"><strong>Incoterm :</strong> <span style="color: #D4AF37; font-weight: bold;">{{ $quotation->incoterm }} — {{ $quotation->destination_port }}</span></p>
                                        <p style="margin: 0 0 8px; font-size: 12px; color: #94A3B8;"><strong>Shipping Mode :</strong> <span style="color: #FFFFFF;">{{ strtoupper(str_replace('_', ' ', $quotation->shipping_method)) }}</span></p>
                                        <p style="margin: 0; font-size: 12px; color: #94A3B8;"><strong>Dossier Type :</strong> <span style="color: #FFFFFF;">{{ strtoupper($quotation->request_type) }}</span></p>
                                    </td>
                                </tr>
                            </table>

                            <h3 style="margin: 0 0 12px; font-size: 14px; color: #FFFFFF; text-transform: uppercase; letter-spacing: 1px;">Selected Items & Configurations</h3>
                            
                            <!-- Items List -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 25px; border-collapse: collapse;">
                                @foreach($quotation->items as $item)
                                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                                        <td style="padding: 10px 0; font-size: 13px; color: #F1F5F9;">
                                            <strong>{{ $item->product->localized_title ?? 'Product' }}</strong><br>
                                            <span style="font-size: 11px; color: #94A3B8;">{{ ucwords(str_replace('_', ' ', $item->packaging_type)) }} • {{ $item->size }}</span>
                                        </td>
                                        <td align="right" style="padding: 10px 0; font-size: 13px; color: #D4AF37; font-weight: bold;">
                                            {{ number_format($item->quantity, 0) }} {{ $item->unit }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                            <p style="margin: 0 0 20px; font-size: 13px; line-height: 1.6; color: #94A3B8;">
                                Our export desk is reviewing current market pricing, container loading schedules, and freight conditions for your requested destination. You will receive an official pro-forma quotation with our signed interactive review link shortly.
                            </p>

                            <!-- Direct WhatsApp Assistance -->
                            <div style="text-align: center; padding: 20px 0;">
                                <a href="https://wa.me/21698000000" style="display: inline-block; padding: 12px 24px; background-color: #25D366; color: #FFFFFF; font-size: 13px; font-weight: bold; text-decoration: none; border-radius: 10px;">
                                    💬 Chat with our Export Manager on WhatsApp
                                </a>
                            </div>

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
