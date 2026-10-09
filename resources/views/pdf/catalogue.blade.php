<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rayanova T.D — {{ $isRtl ? 'كتالوج التصدير الدولي 2026' : ($lang === 'fr' ? 'Catalogue Export International 2026' : 'Official B2B Export Catalogue 2026') }}</title>
    <style>
        @page {
            margin: 24px 30px;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.45;
            color: #1e293b;
            background-color: #ffffff;
            direction: {{ $isRtl ? 'rtl' : 'ltr' }};
        }
        
        /* Page Header & Footer */
        .page-header {
            width: 100%;
            border-bottom: 2px solid #D4AF37;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }
        .header-logo {
            font-size: 16px;
            font-weight: bold;
            color: #1B3B2B;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .header-gold {
            color: #b48a1d;
        }
        .header-sub {
            font-size: 8px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header-badge {
            text-align: {{ $isRtl ? 'left' : 'right' }};
            font-size: 8px;
            font-weight: bold;
            color: #b48a1d;
            text-transform: uppercase;
        }

        /* Cover Page Elements */
        .cover-container {
            border: 2px solid #D4AF37;
            background: #fffdfa;
            padding: 24px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .cover-logo-img {
            max-height: 65px;
            margin-bottom: 10px;
        }
        .cover-title {
            font-size: 22px;
            font-weight: bold;
            color: #0f291e;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 4px;
        }
        .cover-sub {
            font-size: 13px;
            font-weight: bold;
            color: #b48a1d;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }
        .cover-desc {
            font-size: 10px;
            color: #334155;
            max-width: 90%;
            margin: 0 auto 16px auto;
            line-height: 1.6;
        }
        .cover-badges-table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }
        .cover-badge-cell {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            border-radius: 4px;
            text-align: center;
            vertical-align: middle;
        }
        .badge-title {
            font-size: 8px;
            font-weight: bold;
            color: #b48a1d;
            text-transform: uppercase;
            display: block;
        }
        .badge-val {
            font-size: 9px;
            font-weight: bold;
            color: #0f291e;
            display: block;
            margin-top: 2px;
        }

        /* Category Header */
        .category-header {
            background: #1B3B2B;
            color: #ffffff;
            padding: 6px 12px;
            border-radius: 4px;
            margin-top: 18px;
            margin-bottom: 12px;
            border-left: 4px solid #D4AF37;
        }
        .category-title {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #ffffff;
        }
        .category-count {
            font-size: 9px;
            color: #e2e8f0;
            float: {{ $isRtl ? 'left' : 'right' }};
            font-weight: normal;
        }

        /* Product Card / Layout */
        .product-card {
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 14px;
            page-break-inside: avoid;
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
        }
        .product-img-cell {
            width: 28%;
            text-align: center;
            vertical-align: top;
            background-color: #faf8f5;
            border: 1px solid #eedfbe;
            border-radius: 4px;
            padding: 8px;
        }
        .product-img {
            max-width: 100%;
            max-height: 125px;
            object-fit: contain;
        }
        .product-info-cell {
            width: 72%;
            vertical-align: top;
            padding-{{ $isRtl ? 'right' : 'left' }}: 14px;
        }
        .product-title {
            font-size: 13px;
            font-weight: bold;
            color: #0f291e;
            margin-bottom: 2px;
        }
        .product-sub {
            font-size: 9px;
            font-weight: bold;
            color: #b48a1d;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .product-desc {
            font-size: 9px;
            color: #475569;
            margin-bottom: 8px;
            line-height: 1.45;
        }
        
        /* Specs Sub-tables */
        .specs-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .specs-table th {
            background-color: #f1f5f9;
            color: #0f291e;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 6px;
            border-bottom: 1px solid #cbd5e1;
            text-align: {{ $isRtl ? 'right' : 'left' }};
        }
        .specs-table td {
            padding: 4px 6px;
            font-size: 8.5px;
            color: #334155;
            border-bottom: 1px solid #e2e8f0;
        }
        .spec-label {
            font-weight: bold;
            color: #64748b;
            width: 38%;
        }
        .spec-val {
            font-weight: bold;
            color: #0f291e;
        }

        .tag-pill {
            display: inline-block;
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 7.5px;
            font-weight: bold;
            margin-right: 3px;
            margin-bottom: 3px;
        }

        /* Commercial Terms & Footer Page */
        .terms-box {
            border: 1px solid #cbd5e1;
            background-color: #f8fafc;
            border-radius: 6px;
            padding: 12px;
            margin-top: 16px;
            page-break-inside: avoid;
        }
        .terms-title {
            font-size: 11px;
            font-weight: bold;
            color: #1B3B2B;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }
        .terms-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .terms-col {
            width: 50%;
            vertical-align: top;
            padding-{{ $isRtl ? 'left' : 'right' }}: 10px;
        }

        .footer-table {
            width: 100%;
            border-top: 1px solid #D4AF37;
            padding-top: 8px;
            margin-top: 18px;
            font-size: 8px;
            color: #64748b;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

    <!-- ==================== HEADER REUTILISABLE ==================== -->
    <table class="page-header">
        <tr>
            <td style="width: 60%; vertical-align: middle;">
                <div class="header-logo">RAYANOVA <span class="header-gold">T.D</span></div>
                <div class="header-sub">
                    {{ $isRtl ? 'التجارة الدولية والتصدير الزراعي المتوسطي • تونس' : ($lang === 'fr' ? 'Négoce International & Agro-Export Méditerranéen • Tunisie' : 'International Trade & Agro-Export • Tunisia') }}
                </div>
            </td>
            <td class="header-badge" style="width: 40%; vertical-align: middle;">
                <div>{{ $isRtl ? 'كتالوج التصدير الرسمي • موسم 2026' : ($lang === 'fr' ? 'Catalogue Officiel Export • Récolte 2026' : 'Official Export Catalogue • Harvest 2026') }}</div>
                <div style="font-size: 7.5px; color: #64748b; margin-top: 2px;">ISO 22000 • EU BIO • FDA • COI Lab</div>
            </td>
        </tr>
    </table>

    <!-- ==================== COUVERTURE OFFICIELLE B2B ==================== -->
    <div class="cover-container">
        @if(!empty($logoPath) && file_exists($logoPath))
            <img src="{{ $logoPath }}" class="cover-logo-img" alt="Rayanova T.D Logo">
        @endif

        <div class="cover-title">RAYANOVA T.D</div>
        <div class="cover-sub">
            {{ $isRtl ? 'كتالوج الصادرات الزراعية والغذائية التونسية 2026' : ($lang === 'fr' ? 'Catalogue Officiel Agroalimentaire Export 2026' : 'Official Agri-Food Export Catalogue 2026') }}
        </div>

        <p class="cover-desc">
            {{ $isRtl 
                ? 'دليل السلع الغذائية الفاخرة المخصصة للتصدير الدولي: زيت زيتون بكر ممتاز تونسي معتمد، تمور دجلة النور الملكية الطبيعية، الطماطم المجففة بالشمس والهريسة، والتونة المعلبة الفاخرة. مطابقة لمعايير الجودة الأوروبية والأمريكية والخليجية.'
                : ($lang === 'fr'
                    ? 'Guide officiel des produits d\'exportation premium : Huile d\'olive vierge extra tunisienne certifiée COI, dattes Deglet Nour branchées de Tozeur, tomates séchées et harissa artisanale, thon entier de Méditerranée. Expéditions maritimes FCL & vrac flexitank conformes Incoterms 2020.'
                    : 'Official international B2B export catalogue featuring certified Tunisian Extra Virgin Olive Oils (COI accredited), premium Deglet Nour branched dates, sun-dried Mediterranean tomatoes, harissa and solid white tuna. Containerized FCL & bulk flexitank deliveries under Incoterms 2020.') }}
        </p>

        <!-- 4 Badges de Confiance Export -->
        <table class="cover-badges-table">
            <tr>
                <td class="cover-badge-cell" style="width: 25%;">
                    <span class="badge-title">{{ $isRtl ? 'شروط التسليم' : 'Delivery Terms' }}</span>
                    <span class="badge-val">FOB • CIF • CFR • EXW</span>
                </td>
                <td class="cover-badge-cell" style="width: 25%;">
                    <span class="badge-title">{{ $isRtl ? 'الموانئ التونسية' : 'Tunisian Gateways' }}</span>
                    <span class="badge-val">Port of Rades & Sfax</span>
                </td>
                <td class="cover-badge-cell" style="width: 25%;">
                    <span class="badge-title">{{ $isRtl ? 'شهادات الجودة' : 'Certifications' }}</span>
                    <span class="badge-val">EU BIO • FDA • ISO 22000</span>
                </td>
                <td class="cover-badge-cell" style="width: 25%;">
                    <span class="badge-title">{{ $isRtl ? 'وسائط الشحن' : 'Logistics Modes' }}</span>
                    <span class="badge-val">FCL • LCL • Flexitank 24,000L</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- ==================== CONTENU DES PRODUITS GROUPES PAR CATEGORIE ==================== -->
    @foreach($categories as $category)
        @if($category->products->count() > 0)
            
            <div class="category-header">
                <span class="category-title">
                    {{ $isRtl ? ($category->name['ar'] ?? $category->name['en'] ?? '') : ($lang === 'fr' ? ($category->name['fr'] ?? $category->name['en'] ?? '') : ($category->name['en'] ?? '')) }}
                </span>
                <span class="category-count">
                    {{ $category->products->count() }} {{ $isRtl ? 'منتجات معتمدة' : ($lang === 'fr' ? 'produits certifiés' : 'certified products') }}
                </span>
            </div>

            @foreach($category->products as $product)
                @php
                    $prodTitle = $isRtl ? ($product->title['ar'] ?? $product->title['en'] ?? '') : ($lang === 'fr' ? ($product->title['fr'] ?? $product->title['en'] ?? '') : ($product->title['en'] ?? ''));
                    $prodSubtitle = $isRtl ? ($product->subtitle['ar'] ?? $product->subtitle['en'] ?? '') : ($lang === 'fr' ? ($product->subtitle['fr'] ?? $product->subtitle['en'] ?? '') : ($product->subtitle['en'] ?? ''));
                    $prodDesc = $isRtl ? ($product->description['ar'] ?? $product->description['en'] ?? '') : ($lang === 'fr' ? ($product->description['fr'] ?? $product->description['en'] ?? '') : ($product->description['en'] ?? ''));
                    $prodImagePath = $productImagePaths[$product->id] ?? null;
                @endphp

                <div class="product-card">
                    <table class="product-table">
                        <tr>
                            <!-- Image du Produit -->
                            <td class="product-img-cell">
                                @if($prodImagePath && file_exists($prodImagePath))
                                    <img src="{{ $prodImagePath }}" class="product-img" alt="{{ $prodTitle }}">
                                @else
                                    <div style="font-size: 8px; color: #94a3b8; padding-top: 30px;">[ Packaging Image ]</div>
                                @endif
                                <div style="margin-top: 6px; font-size: 7.5px; font-weight: bold; color: #0f291e; text-transform: uppercase;">
                                    {{ $product->slug }}
                                </div>
                            </td>

                            <!-- Fiche Technique & Logistique -->
                            <td class="product-info-cell">
                                <div class="product-title">{{ $prodTitle }}</div>
                                @if($prodSubtitle)
                                    <div class="product-sub">{{ $prodSubtitle }}</div>
                                @endif
                                
                                @if($prodDesc)
                                    <div class="product-desc">{{ $prodDesc }}</div>
                                @endif

                                <!-- Formats de Conditionnement & Volumes -->
                                <div style="margin-bottom: 6px;">
                                    <strong style="font-size: 8px; color: #64748b; text-transform: uppercase;">
                                        {{ $isRtl ? 'خيارات التعبئة والأحجام المتاحة:' : ($lang === 'fr' ? 'Conditionnements & Formats Disponibles :' : 'Export Packagings & Available Sizes:') }}
                                    </strong>
                                    <div style="margin-top: 2px;">
                                        @if($product->allowed_packaging_types)
                                            @foreach($product->allowed_packaging_types as $pkg)
                                                <span class="tag-pill">{{ ucfirst(str_replace('_', ' ', $pkg)) }}</span>
                                            @endforeach
                                        @endif
                                        @if($product->allowed_sizes)
                                            @foreach($product->allowed_sizes as $sz)
                                                <span class="tag-pill" style="background-color: #f1f5f9; color: #0f291e; border-color: #cbd5e1;">{{ $sz }}</span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <!-- Tableau des Spécifications Physico-Chimiques & Logistiques -->
                                <table class="specs-table">
                                    <tr>
                                        <td class="spec-label">{{ $isRtl ? 'المصدر والصنف' : ($lang === 'fr' ? 'Origine & Variété' : 'Origin & Cultivar') }}</td>
                                        <td class="spec-val">
                                            @if(str_contains(strtolower($product->slug), 'olive'))
                                                100% Tunisia (Chemlali / Chetoui) • First Cold Extraction
                                            @elseif(str_contains(strtolower($product->slug), 'date'))
                                                Tozeur & Kebili Oases (Tunisia) • Hand Selected
                                            @elseif(str_contains(strtolower($product->slug), 'tomato') || str_contains(strtolower($product->slug), 'harissa'))
                                                Cap Bon & Sidi Bouzid Terroir • Sun-Dried Natural
                                            @else
                                                Tunisian Mediterranean Coastline • Quality Grade A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spec-label">{{ $isRtl ? 'معايير الجودة والتحاليل' : ($lang === 'fr' ? 'Spécifications Laboratoire' : 'Lab Specifications') }}</td>
                                        <td class="spec-val">
                                            @if(str_contains(strtolower($product->slug), 'olive'))
                                                Acidity &le; 0.2% - 0.5% | Peroxide &le; 10 meq O2/kg | COI Standard Compliant
                                            @elseif(str_contains(strtolower($product->slug), 'date'))
                                                Moisture &le; 20% | Purity &ge; 99% | Natural Sun-Cured
                                            @else
                                                phytosanitary certified | Heavy metals: Non-detected | Microbio: Safe
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="spec-label">{{ $isRtl ? 'لوجستيك الحاويات' : ($lang === 'fr' ? 'Capacité de Chargement' : 'FCL Container Loading') }}</td>
                                        <td class="spec-val">
                                            20ft FCL: ~10-14 Pallets | 40ft High Cube: ~21-26 Pallets | Flexitank: 21,500 - 24,000 Liters
                                        </td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                    </table>
                </div>

            @endforeach

        @endif
    @endforeach

    <!-- ==================== CONDITIONS COMMERCIALES & CONTACT ==================== -->
    <div class="terms-box">
        <div class="terms-title">
            {{ $isRtl ? 'شروط التصدير والتسليم الدولي (Incoterms 2020)' : ($lang === 'fr' ? 'Conditions Générales d\'Exportation & Modalités Commerciales' : 'General Export Terms & International Trade Desk') }}
        </div>
        
        <table class="terms-grid">
            <tr>
                <td class="terms-col">
                    <strong style="color: #0f291e; font-size: 8.5px; display: block; margin-bottom: 2px;">
                        {{ $isRtl ? '1. شروط الدفع المعتمدة:' : ($lang === 'fr' ? '1. Modalités de Paiement :' : '1. Accepted Payment Terms:') }}
                    </strong>
                    <p style="font-size: 8px; color: #475569; margin: 0 0 6px 0;">
                        • Irrevocable Confirmed Letter of Credit (L/C at sight)<br>
                        • Cash Against Documents (CAD / D/P via first-class international bank)<br>
                        • T/T Wire Transfer (30% deposit upon order confirmation, 70% against B/L copy)
                    </p>

                    <strong style="color: #0f291e; font-size: 8.5px; display: block; margin-bottom: 2px;">
                        {{ $isRtl ? '2. الوثائق المرفقة بالشحنة:' : ($lang === 'fr' ? '2. Documentation Fournie :' : '2. Standard Export Documentation:') }}
                    </strong>
                    <p style="font-size: 8px; color: #475569; margin: 0;">
                        • Commercial Invoice (3 originals + 3 copies)<br>
                        • Clean on Board Ocean Bill of Lading (3/3 Full Set)<br>
                        • Certificate of Origin (EUR.1 / Arab League / Chamber of Commerce)<br>
                        • Phytosanitary & Health Certificate (Ministry of Agriculture)<br>
                        • Official Certificate of Analysis (COI Accredited Chemical Laboratory)
                    </p>
                </td>

                <td class="terms-col">
                    <strong style="color: #0f291e; font-size: 8.5px; display: block; margin-bottom: 2px;">
                        {{ $isRtl ? '3. مكتب التصدير وطلب عروض الأسعار:' : ($lang === 'fr' ? '3. Contact Desk & Demandes de Cotation :' : '3. Export Desk & Direct Quotations:') }}
                    </strong>
                    <p style="font-size: 8px; color: #475569; margin: 0 0 6px 0;">
                        <strong>RAYANOVA T.D SARL</strong> — Agro-Export Division<br>
                        Republic of Tunisia • Gateways: Port of Rades & Port of Sfax<br>
                        <strong>WhatsApp Trade Desk:</strong> +216 54 817 999<br>
                        <strong>Email:</strong> export@rayanovatd.com<br>
                        <strong>Web:</strong> www.rayanovatd.com
                    </p>

                    <div style="background-color: #f1f5f9; padding: 6px; border-radius: 4px; border: 1px solid #cbd5e1; font-size: 8px; color: #0f291e;">
                        <strong>{{ $isRtl ? 'طلب تسعيرة رسمية فورية (RFQ):' : ($lang === 'fr' ? 'Demande de Cotation Instantanée :' : 'Submit Direct RFQ:') }}</strong><br>
                        {{ $isRtl ? 'يمكنكم إرسال طلبات عروض الأسعار الرسمية عبر الموقع مباشرة مع تحديد ميناء الوصول ونوع التعبئة.' : 'Submit instant RFQs with desired packaging, volume and destination port on our portal.' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- ==================== FOOTER ==================== -->
    <table class="footer-table">
        <tr>
            <td style="width: 50%;">
                RAYANOVA T.D SARL &copy; {{ date('Y') }} • All Rights Reserved.
            </td>
            <td style="width: 50%; text-align: {{ $isRtl ? 'left' : 'right' }};">
                {{ $isRtl ? 'كتالوج التصدير الدولي • سري ومخصص للمهنيين والمستوردين' : 'Confidential B2B Commodity Export Catalogue' }}
            </td>
        </tr>
    </table>

</body>
</html>
