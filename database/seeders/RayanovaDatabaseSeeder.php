<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\HeroSlide;
use App\Models\Product;
use App\Models\ProductCertificate;
use App\Models\QuotationItem;
use App\Models\QuotationMessage;
use App\Models\QuotationRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RayanovaDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Rôles et Permissions (Double niveau RBAC)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_dashboard',
            'manage_products',
            'delete_products',      // Exclusif Super Admin
            'manage_quotations',
            'delete_quotations',    // Exclusif Super Admin
            'issue_contracts',
            'manage_hero',
            'manage_users',         // Exclusif Super Admin
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdminRole->syncPermissions(Permission::all());

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions([
            'view_dashboard',
            'manage_products',
            'manage_quotations',
            'issue_contracts',
            'manage_hero',
        ]);

        // Création des utilisateurs
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@rayanovatd.com'],
            [
                'name' => 'Direction Générale (Super Admin)',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        $salesAdmin = User::firstOrCreate(
            ['email' => 'sales@rayanovatd.com'],
            [
                'name' => 'Responsable Export & Ventes',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
        $salesAdmin->assignRole('admin');

        // 2. Certifications Internationales
        $certBio = ProductCertificate::create([
            'name' => [
                'ar' => 'شهادة الزراعة العضوية الأوروبية',
                'en' => 'EU Organic / Ecocert Certified',
                'fr' => 'Certificat Agriculture Biologique (Ecocert)',
            ],
            'issuer' => 'Ecocert / EU Organic',
            'badge_icon' => 'leaf',
            'valid_until' => now()->addYears(2),
        ]);

        $certFda = ProductCertificate::create([
            'name' => [
                'ar' => 'اعتماد إدارة الغذاء والدواء الأمريكية FDA',
                'en' => 'US FDA Registered Facility',
                'fr' => 'Enregistrement FDA USA',
            ],
            'issuer' => 'US FDA',
            'badge_icon' => 'shield-check',
            'valid_until' => now()->addYears(3),
        ]);

        $certIso = ProductCertificate::create([
            'name' => [
                'ar' => 'شهادة سلامة الأغذية ISO 22000 & HACCP',
                'en' => 'ISO 22000:2018 & HACCP Certified',
                'fr' => 'Certification ISO 22000 & HACCP',
            ],
            'issuer' => 'TÜV Rheinland',
            'badge_icon' => 'award',
            'valid_until' => now()->addYears(2),
        ]);

        $certCoi = ProductCertificate::create([
            'name' => [
                'ar' => 'تحليل مخبري معتمد من المجلس الدولي للزيتون COI',
                'en' => 'COI Accredited Chemical & Sensory Lab Test',
                'fr' => 'Analyses Chimiques Agréées COI',
            ],
            'issuer' => 'Conseil Oléicole International (COI)',
            'badge_icon' => 'flask',
            'valid_until' => now()->addMonths(8),
        ]);

        // 3. Catégories
        $catOlive = Category::create([
            'slug' => 'huile-olive-extra-vierge',
            'name' => [
                'ar' => 'زيت الزيتون البكر الممتاز',
                'en' => 'Extra Virgin Olive Oil',
                'fr' => 'Huile d\'Olive Extra Vierge',
            ],
            'description' => [
                'ar' => 'أنقى زيوت الزيتون التونسية المعصورة على البارد مع حموضة منخفضة وغنى بالبوليفينول.',
                'en' => 'Cold-pressed authentic Tunisian olive oil, low acidity, rich in polyphenols.',
                'fr' => 'Pure huile d\'olive tunisienne première pression à froid, faible acidité et haute teneur en polyphénols.',
            ],
            'image_path' => 'images/categories/olive-oil.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $catDates = Category::create([
            'slug' => 'dattes-deglet-nour',
            'name' => [
                'ar' => 'تمور دقلة النور الملكية',
                'en' => 'Deglet Nour Royal Dates',
                'fr' => 'Dattes Deglet Nour de Tunisie',
            ],
            'description' => [
                'ar' => 'سيدة التمور الطبيعية من واحات الجريد وقبلي، ذهبية وشفافة بقوام عسلي.',
                'en' => 'The Queen of dates from the oases of Tozeur & Kebili. Golden, translucent, and naturally sweet.',
                'fr' => 'Reine des dattes des oasis de Tozeur et Kébili, translucide et miellée.',
            ],
            'image_path' => 'images/categories/dates.jpg',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $catCondiments = Category::create([
            'slug' => 'terroir-epices-tomates',
            'name' => [
                'ar' => 'طماطم مجففة، هريسة وتوابل المتوسط',
                'en' => 'Sundried Tomatoes, Harissa & Spices',
                'fr' => 'Tomates Séchées, Harissa & Épices',
            ],
            'description' => [
                'ar' => 'منتجات تقليدية طبيعية مجففة تحت شمس المتوسط مع هريسة كاب بون الأصيلة.',
                'en' => 'Artisanal sun-dried Mediterranean tomatoes, traditional Cap Bon harissa and pure sea salt.',
                'fr' => 'Tomates séchées au soleil méditerranéen, harissa artisanale du Cap Bon et sel marin.',
            ],
            'image_path' => 'images/categories/condiments.jpg',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $catCanned = Category::create([
            'slug' => 'conserves-mer-mediterranee',
            'name' => [
                'ar' => 'مصبرات بحرية (تونة وسردين)',
                'en' => 'Canned Tuna & Sardines',
                'fr' => 'Conserves de la Mer',
            ],
            'description' => [
                'ar' => 'أسماك المتوسط الطازجة المصبرة في زيت الزيتون البكر الممتاز.',
                'en' => 'Premium wild Mediterranean tuna and sardines preserved in extra virgin olive oil.',
                'fr' => 'Thon et sardines sauvages de Méditerranée confits à l\'huile d\'olive extra vierge.',
            ],
            'image_path' => 'images/categories/canned-fish.jpg',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // 4. Produits
        $prodOliveTresor = Product::create([
            'category_id' => $catOlive->id,
            'slug' => 'rayanova-tresor-organic-olive-oil',
            'title' => [
                'ar' => 'رايانوفا تريزور — زيت زيتون عضوي بكر ممتاز',
                'en' => 'Rayanova Trésor — Organic Extra Virgin Olive Oil',
                'fr' => 'Rayanova Trésor — Huile d\'Olive Extra Vierge Bio',
            ],
            'subtitle' => [
                'ar' => 'عصرة أولى على البارد • صنف شتوي و شملالي • حصاد مبكر',
                'en' => 'Early Harvest • Cold Extracted • Chétoui & Chemlali Cultivars',
                'fr' => 'Récolte Précoce • Extraction à Froid • Chétoui & Chemlali',
            ],
            'description' => [
                'ar' => 'زيت زيتون ذو جودة استثنائية بنكهة عشبية متوازنة مع مرارة خفيفة وحدّة مميزة تدل على غناه بمضادات الأكسدة الطبيعية والبوليفينول.',
                'en' => 'Award-winning organic extra virgin olive oil boasting an intense fruity aroma with artichoke and green almond notes. High stability and polyphenol content.',
                'fr' => 'Huile d\'olive d\'exception au fruité vert intense, notes d\'amande et d\'artichaut. Idéale pour les circuits d\'épicerie fine et la distribution haut de gamme.',
            ],
            'featured_image' => 'images/products/olive-oil-tresor.png',
            'gallery' => [
                'images/products/olive-oil-tresor-1.png',
                'images/products/olive-oil-tresor-2.png',
                'images/products/olive-oil-harvest.png',
            ],
            'nutritional_specs' => [
                'variety' => 'Chétoui (Nord) & Chemlali (Sahel)',
                'acidity' => '< 0.18%',
                'peroxides' => '< 7.5 meq O2/kg',
                'polyphenols' => '520 mg/kg',
                'harvest_year' => '2025/2026',
                'extraction' => 'Continuous 2-phase cold extraction (< 24°C)',
            ],
            'logistics_specs' => [
                'gross_weight_bottle_500ml' => '0.89 kg',
                'bottles_per_box' => 12,
                'boxes_per_pallet_euro' => 70,
                'total_bottles_per_pallet' => 840,
                'fcl_20ft_capacity' => '10 Pallets (~8,400 bottles) or 21,500L Flexitank',
            ],
            'allowed_packaging_types' => [
                'bollar',
                'pet',
                'tin',
                'squiz',
                'bulk_flexitank',
            ],
            'allowed_sizes' => [
                '250ml',
                '500ml',
                '750ml',
                '1L',
                '3L',
                '5L',
            ],
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 1,
            'meta_title' => [
                'ar' => 'زيت زيتون تونسي عضوي بالجملة والتصدير | Rayanova T.D',
                'en' => 'Wholesale Tunisian Organic Olive Oil Export | Rayanova T.D',
                'fr' => 'Export Huile d\'Olive Vierge Extra Bio Tunisie | Rayanova T.D',
            ],
            'meta_description' => [
                'ar' => 'تصدير زيت زيتون تونسي بكر ممتاز معبأ وبالجملة صب Flexitank. شهادات BIO و FDA مع تحاليل مخبرية معتمدة.',
                'en' => 'Bulk Flexitank & Private Label bottled Extra Virgin Olive Oil from Tunisia. Certified BIO, ISO, FDA.',
                'fr' => 'Exportateur d\'huile d\'olive tunisienne en vrac Flexitank et bouteilles personnalisées Private Label. Certifié BIO, ISO, FDA.',
            ],
        ]);
        $prodOliveTresor->certificates()->attach([$certBio->id, $certCoi->id, $certIso->id, $certFda->id]);

        $prodDates = Product::create([
            'category_id' => $catDates->id,
            'slug' => 'deglet-nour-oasis-premium-branch',
            'title' => [
                'ar' => 'دقلة النور التونسية الفاخرة — عراجين طبيعية',
                'en' => 'Deglet Nour Branched Dates — Premium Natural',
                'fr' => 'Dattes Deglet Nour Branchées — Qualité Supérieure',
            ],
            'subtitle' => [
                'ar' => 'تمور طبيعية على أغصانها • واحات توزر وقبلي • صنف ممتاز',
                'en' => 'Naturally on branch • Tozeur & Kebili Oases • Grade A',
                'fr' => 'Dattes naturelles sur branche • Terroir de Tozeur & Kébili • Calibre Extra',
            ],
            'description' => [
                'ar' => 'أرقى أنواع التمور التونسية على أغصانها الطبيعية، منتقاة حبة حبة، بلون كهرماني ذهبي شفاف، ونسبة رطوبة متوازنة بدون إضافات سكرية.',
                'en' => 'The authentic Deglet Nour dates presented on their natural stems. Rich in fibers and minerals, with a soft honey-like texture and bright amber translucency.',
                'fr' => 'Les plus belles dattes de Tunisie présentées sur leurs tiges naturelles. Texture moelleuse, robe dorée translucide, sans conservateurs artificiels.',
            ],
            'featured_image' => 'images/products/deglet_nour_box.jpg',
            'gallery' => [
                'images/products/dates-branched-1.png',
                'images/products/dates-boxes.png',
            ],
            'nutritional_specs' => [
                'variety' => 'Deglet Nour Naturelle',
                'origin' => 'Tozeur & Kébili, South Tunisia',
                'moisture' => '20% - 24%',
                'caliber' => 'Medium / Large (Grade A)',
                'preservatives' => 'None (100% Raw & Natural)',
                'harvest_year' => '2025/2026',
            ],
            'logistics_specs' => [
                'standard_carton_weight' => '1 kg / 2 kg / 5 kg',
                'cartons_per_pallet' => 120,
                'pallet_weight' => '~600 kg',
                'container_40ft_capacity' => '20-22 Metric Tons',
            ],
            'allowed_packaging_types' => [
                'carton_box',
                'wood_box',
                'barquette_window',
                'vacuum_bag',
                'bulk_carton',
            ],
            'allowed_sizes' => [
                '250g',
                '500g',
                '1kg',
                '2kg',
                '5kg',
            ],
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 2,
        ]);
        $prodDates->certificates()->attach([$certBio->id, $certIso->id, $certFda->id]);

        $prodTomatoes = Product::create([
            'category_id' => $catCondiments->id,
            'slug' => 'tomates-sechees-soleil-mediterranee',
            'title' => [
                'ar' => 'طماطم مجففة تحت شمس المتوسط في زيت الزيتون',
                'en' => 'Sun-Dried Mediterranean Tomatoes in Olive Oil',
                'fr' => 'Tomates Séchées au Soleil à l\'Huile d\'Olive',
            ],
            'subtitle' => [
                'ar' => 'مجففة طبيعياً بالملح البحري • متبلة بالأعشاب البرية',
                'en' => 'Sun-dried with sea salt • Infused with wild herbs',
                'fr' => 'Séchées traditionnellement au sel marin • Macérées aux herbes sauvages',
            ],
            'description' => [
                'ar' => 'طماطم ناضجة مروية بشمس تونس، مجففة بالطريقة التقليدية ومغمورة في زيت الزيتون البكر الممتاز مع إكليل الجبل والثوم.',
                'en' => 'Sun-ripened Mediterranean tomatoes dried under the open sky and preserved in extra virgin olive oil with wild rosemary and capers.',
                'fr' => 'Tomates gorgées de soleil, séchées naturellement et conservées dans l\'huile d\'olive extra vierge aux aromates de Méditerranée.',
            ],
            'featured_image' => 'images/products/sundried_tomatoes_jar.jpg',
            'nutritional_specs' => [
                'origin' => 'Cap Bon & Sahel, Tunisia',
                'salt_content' => '8% - 10%',
                'oil_type' => 'Extra Virgin Olive Oil',
            ],
            'logistics_specs' => [
                'jar_sizes' => '200g, 280g, 1kg, 3kg, 10kg bucket',
            ],
            'allowed_packaging_types' => [
                'glass_jar',
                'food_service_bucket',
                'bulk_dry_vacuum_pack',
            ],
            'allowed_sizes' => [
                '200g',
                '280g',
                '1kg',
                '3kg',
                '10kg Bucket',
            ],
            'is_featured' => true,
            'is_active' => true,
            'sort_order' => 3,
        ]);
        $prodTomatoes->certificates()->attach([$certIso->id, $certFda->id]);

        $prodTuna = Product::create([
            'category_id' => $catCanned->id,
            'slug' => 'thon-entier-huile-olive-extra-vierge',
            'title' => [
                'ar' => 'تونة بيضاء كاملة بزيت الزيتون البكر الممتاز',
                'en' => 'Solid White Tuna in Extra Virgin Olive Oil',
                'fr' => 'Thon Blanc Entier à l\'Huile d\'Olive Extra Vierge',
            ],
            'subtitle' => [
                'ar' => 'صيد مستدام • قطع كاملة منقاة يدوياً',
                'en' => 'Wild caught • Hand-packed solid loins',
                'fr' => 'Pêche durable en Méditerranée • Morceaux entiers emboîtés à la main',
            ],
            'description' => [
                'ar' => 'لحم تونة فاخر منتقى بعناية ومغلف بأجود أنواع زيت الزيتون البكر الممتاز لنكهة غنية وغذاء صحي غني بالأوميغا 3.',
                'en' => 'Prime solid loins of wild Mediterranean tuna, hand-packed in cold-pressed extra virgin olive oil. Rich in Omega-3.',
                'fr' => 'Morceaux nobles de thon sauvage de Méditerranée, parés à la main et sublimés par l\'huile d\'olive extra vierge pressée à froid.',
            ],
            'featured_image' => 'images/products/canned_tuna_can.jpg',
            'nutritional_specs' => [
                'species' => 'Thunnus alalunga / Thunnus thynnus',
                'fish_content' => '> 75%',
                'oil' => 'Extra Virgin Olive Oil 100%',
                'omega3' => 'High natural Omega-3 fatty acids',
            ],
            'logistics_specs' => [
                'cans_per_tray' => 24,
                'trays_per_pallet' => 140,
            ],
            'allowed_packaging_types' => [
                'tin_can_easy_open',
                'glass_jar_tuna',
                'food_service_tin',
            ],
            'allowed_sizes' => [
                '80g',
                '160g',
                '200g',
                '1kg Foodservice',
                '1.7kg Horeca',
            ],
            'is_featured' => false,
            'is_active' => true,
            'sort_order' => 4,
        ]);
        $prodTuna->certificates()->attach([$certIso->id, $certFda->id]);

        // 5. Slides du Hero (Gérés depuis le Dashboard)
        HeroSlide::create([
            'title' => [
                'ar' => 'الذهب الأخضر المتوسطي — أرقى زيوت الزيتون التونسية',
                'en' => 'Mediterranean Liquid Gold — Pure Tunisian Olive Oil',
                'fr' => 'L\'Or Vert Méditerranéen — Huile d\'Olive d\'Exception',
            ],
            'subtitle' => [
                'ar' => 'عصرة أولى على البارد، معبأة وبالجملة صب للتصدير الدولي إلى موانئ العالم.',
                'en' => 'Certified organic extra virgin olive oil. Bulk Flexitank & luxury private label export.',
                'fr' => 'Première pression à froid, certifiée BIO et livrée en conteneurs Flexitank ou bouteilles de prestige.',
            ],
            'badge_text' => [
                'ar' => 'جودة معتمدة دولياً • حصاد 2026',
                'en' => '100% Organic & COI Certified • 2026 Harvest',
                'fr' => 'Certifié BIO & COI • Récolte 2026',
            ],
            'cta_text' => [
                'ar' => 'طلب تسعيرة أو عينات',
                'en' => 'Request a Quote / Samples',
                'fr' => 'Demander un Devis / Échantillons',
            ],
            'cta_url' => '/catalog',
            'media_type' => 'image',
            'media_path' => 'images/hero/olive-grove-tunisia.jpg',
            'overlay_opacity' => 0.45,
            'glass_card_content' => [
                'stat1' => ['value' => '< 0.2%', 'label_en' => 'Acidity', 'label_ar' => 'نسبة حموضة', 'label_fr' => 'Acidité'],
                'stat2' => ['value' => '520 mg', 'label_en' => 'Polyphenols', 'label_ar' => 'بوليفينول', 'label_fr' => 'Polyphénols'],
                'stat3' => ['value' => 'FCL / LCL', 'label_en' => 'Worldwide Shipping', 'label_ar' => 'شحن دولي', 'label_fr' => 'Fret Mondial'],
            ],
            'sort_order' => 1,
            'is_active' => true,
        ]);

        HeroSlide::create([
            'title' => [
                'ar' => 'دقلة النور الملكية — فخر الواحات التونسية',
                'en' => 'Deglet Nour Royal Dates — Pearl of the Sahara',
                'fr' => 'Dattes Royales Deglet Nour — Joyau du Sahara',
            ],
            'subtitle' => [
                'ar' => 'عراجين طبيعية وتمور منتقاة يدوياً من واحات توزر وقبلي للتصدير الفوري.',
                'en' => 'Naturally sweet, translucent amber dates harvested with ancient savoir-faire.',
                'fr' => 'Dattes branchées et naturelles sélectionnées pour les plus grandes tables du monde.',
            ],
            'badge_text' => [
                'ar' => 'منتج تونسي أصلي 100%',
                'en' => 'Authentic Tunisian Origin',
                'fr' => 'Origine Tunisie Garantie',
            ],
            'cta_text' => [
                'ar' => 'استكشف التمور التونسية',
                'en' => 'Explore Royal Dates',
                'fr' => 'Découvrir nos Dattes',
            ],
            'cta_url' => '/category/dattes-deglet-nour',
            'media_type' => 'image',
            'media_path' => 'images/hero/oasis-dates.jpg',
            'overlay_opacity' => 0.50,
            'glass_card_content' => [
                'stat1' => ['value' => 'Grade A', 'label_en' => 'Quality', 'label_ar' => 'صنف ممتاز', 'label_fr' => 'Qualité'],
                'stat2' => ['value' => 'Raw & Pure', 'label_en' => 'No Additives', 'label_ar' => 'بدون مواد حافظة', 'label_fr' => 'Sans additifs'],
                'stat3' => ['value' => '20 Tons FCL', 'label_en' => 'Container Loads', 'label_ar' => 'حمولات حاويات', 'label_fr' => 'Conteneurs complets'],
            ],
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // 6. Exemple Réaliste de Demande de Cotation B2B (Deal en cours)
        $quoteDemo = QuotationRequest::create([
            'reference_number' => 'RFQ-2026-0001',
            'request_type' => 'quote',
            'company_name' => 'Nordic Bio Gourmet Import GmbH',
            'contact_name' => 'Hans Gruber',
            'email' => 'h.gruber@nordicbio-gourmet.de',
            'phone' => '+49 40 88992211',
            'whatsapp' => '+49 170 9988776',
            'country' => 'Germany',
            'tax_number' => 'DE-298374921',
            'website' => 'https://nordicbio-gourmet.de',
            'incoterm' => 'CIF',
            'destination_port' => 'Port of Hamburg, Germany',
            'shipping_method' => 'sea_fcl',
            'currency' => 'EUR',
            'status' => 'quoted',
            'valid_until' => now()->addDays(12),
            'signed_token' => Str::random(48),
            'last_viewed_at' => now()->subHours(3),
            'client_notes' => 'Please quote for 1x 20ft container of bottled Organic Extra Virgin Olive Oil under Rayanova label and 5 pallets of Deglet Nour branched dates. Delivery expected in Hamburg by November 2026.',
            'admin_notes' => 'Quoted with CIF Hamburg conditions including marine insurance and EUR1 certificate.',
            'total_amount' => 64800.00,
        ]);

        QuotationItem::create([
            'quotation_request_id' => $quoteDemo->id,
            'product_id' => $prodOliveTresor->id,
            'packaging_type' => 'bottle_glass_marasca',
            'size' => '500ml',
            'quantity' => 8400,
            'unit' => 'units',
            'offered_unit_price' => 6.20,
            'subtotal' => 52080.00,
            'is_private_label' => false,
        ]);

        QuotationItem::create([
            'quotation_request_id' => $quoteDemo->id,
            'product_id' => $prodDates->id,
            'packaging_type' => 'carton_window_box',
            'size' => '1kg',
            'quantity' => 2544,
            'unit' => 'units',
            'offered_unit_price' => 5.00,
            'subtotal' => 12720.00,
            'is_private_label' => false,
        ]);

        QuotationMessage::create([
            'quotation_request_id' => $quoteDemo->id,
            'sender_type' => 'client',
            'sender_name' => 'Hans Gruber',
            'message_body' => 'Guten Tag, we are looking forward to receiving your official laboratory analysis sheet along with the proforma quotation.',
        ]);

        QuotationMessage::create([
            'quotation_request_id' => $quoteDemo->id,
            'sender_type' => 'admin',
            'sender_name' => 'Responsable Export Rayanova',
            'message_body' => 'Dear Mr. Gruber, thank you for your interest. We have attached the full COI certified chemical analysis (acidity < 0.18%) and generated your CIF Hamburg quotation valid for 12 days.',
        ]);
    }
}
