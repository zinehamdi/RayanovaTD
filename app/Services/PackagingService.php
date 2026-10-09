<?php

namespace App\Services;

use App\Models\Product;

class PackagingService
{
    /**
     * Master Registry of all Export Packaging Types
     */
    public static function all(): array
    {
        return [
            // ==========================================
            // GROUP 1: OLIVE OIL & LIQUID AGRO-PRODUCTS
            // ==========================================
            'bollar' => [
                'id' => 'bollar',
                'group' => 'olive_oil',
                'group_label_ar' => 'زيوت وسوائل غذائية',
                'group_label_fr' => 'Huiles & Liquides',
                'group_label_en' => 'Oils & Liquids',
                'name_en' => 'Glass Bottle (Bollar / Marasca)',
                'name_fr' => 'Bouteille Verre (Bollar / Marasca)',
                'name_ar' => 'قارورة بلار (زجاج رفيع Marasca)',
                'desc_en' => 'Marasca & Dorica UV-Protective Dark Glass',
                'desc_fr' => 'Verre teinté anti-UV Marasca & Dorica',
                'desc_ar' => 'زجاج داكن عازل للأشعة فوق البنفسجية',
                'shape' => 'bollar',
                'icon_emoji' => '🫙',
                'badge' => 'Prestige Horeca',
                'default_sizes' => ['250ml', '500ml', '750ml', '1L'],
                'logistics_hint' => '1 Euro Pallet ≈ 720-840 bottles • 1x 20ft FCL = 10 Pallets',
            ],
            'pet' => [
                'id' => 'pet',
                'group' => 'olive_oil',
                'group_label_ar' => 'زيوت وسوائل غذائية',
                'group_label_fr' => 'Huiles & Liquides',
                'group_label_en' => 'Oils & Liquids',
                'name_en' => 'Clear PET Bottle',
                'name_fr' => 'Bouteille PET Cristal',
                'name_ar' => 'عبوة بي إي تي (PET كريستال)',
                'desc_en' => 'Food-Grade Shatterproof Lightweight PET',
                'desc_fr' => 'PET alimentaire léger et incassable',
                'desc_ar' => 'بلاستيك غذائي مقوى خفيف وغير قابل للكسر',
                'shape' => 'pet',
                'icon_emoji' => '🍾',
                'badge' => 'Retail Friendly',
                'default_sizes' => ['1L', '3L', '5L'],
                'logistics_hint' => '1 Euro Pallet ≈ 640-800 bottles • Lightweight freight',
            ],
            'tin' => [
                'id' => 'tin',
                'group' => 'olive_oil',
                'group_label_ar' => 'زيوت وسوائل غذائية',
                'group_label_fr' => 'Huiles & Liquides',
                'group_label_en' => 'Oils & Liquids',
                'name_en' => 'Metal Canister (Tin / Bidon)',
                'name_fr' => 'Bidon Fer-Blanc (Tin)',
                'name_ar' => 'صفائح معدنية (Tin / علبة حديد)',
                'desc_en' => '100% Light & Oxygen Hermetic Barrier',
                'desc_fr' => 'Barrière totale oxygène & lumière',
                'desc_ar' => 'حماية كاملة 100% من الضوء والأكسجين',
                'shape' => 'tin',
                'icon_emoji' => '🥫',
                'badge' => 'Long Shelf-Life',
                'default_sizes' => ['1L', '3L', '5L'],
                'logistics_hint' => '1 Euro Pallet ≈ 160-240 cans (5L) • 1x 20ft FCL = 10 Pallets',
            ],
            'squiz' => [
                'id' => 'squiz',
                'group' => 'olive_oil',
                'group_label_ar' => 'زيوت وسوائل غذائية',
                'group_label_fr' => 'Huiles & Liquides',
                'group_label_en' => 'Oils & Liquids',
                'name_en' => 'Squeeze Bottle (Squiz)',
                'name_fr' => 'Flacon Souple (Squiz)',
                'name_ar' => 'عبوة ضغط مرنة (Squiz)',
                'desc_en' => 'Ergonomic Precision Silicone Valve Cap',
                'desc_fr' => 'Bouchon valve anti-goutte CHR',
                'desc_ar' => 'صمام سيليكون مانع للتقطير وعصري',
                'shape' => 'squiz',
                'icon_emoji' => '🧴',
                'badge' => 'Chef & Culinary',
                'default_sizes' => ['250ml', '375ml', '500ml', '750ml'],
                'logistics_hint' => '1 Euro Pallet ≈ 960 units • Ergonomic Horeca line',
            ],
            'bulk_flexitank' => [
                'id' => 'bulk_flexitank',
                'group' => 'bulk_industrial',
                'group_label_ar' => 'شحن صناعي وسائب',
                'group_label_fr' => 'Vrac & Industriel',
                'group_label_en' => 'Bulk & Industrial',
                'name_en' => 'Bulk Flexitank / IBC',
                'name_fr' => 'Citerne Flexitank / IBC',
                'name_ar' => 'شحن سائب (Flexitank / صب)',
                'desc_en' => '24,000L Sea Flexitank & 1,000L IBC',
                'desc_fr' => 'Flexitank 24 000L & Conteneur IBC',
                'desc_ar' => 'حاويات بحرية صب 24,000 لتر للصناعيين',
                'shape' => 'bulk',
                'icon_emoji' => '🛢️',
                'badge' => 'Industrial Export',
                'default_sizes' => ['1,000L IBC', '21,500L Flexitank', '24,000L Flexitank'],
                'logistics_hint' => '1x 20ft Container = 1 Flexitank (21,500 - 24,000 Liters)',
            ],

            // ==========================================
            // GROUP 2: DATES & DEGLET NOUR (تمور وفواكه)
            // ==========================================
            'carton_box' => [
                'id' => 'carton_box',
                'group' => 'dates',
                'group_label_ar' => 'تمور دقلة النور والفواكه',
                'group_label_fr' => 'Dattes Deglet Nour & Fruits',
                'group_label_en' => 'Dates & Fruits',
                'name_en' => 'Export Carton Box',
                'name_fr' => 'Boîte Carton Export',
                'name_ar' => 'علبة كرتون تصدير (Carton Box)',
                'desc_en' => 'Heavy-Duty Export Corrugated Display Box',
                'desc_fr' => 'Carton ondulé renforcé pour distribution supermarché',
                'desc_ar' => 'كرتون تصدير مقوى عالي الجودة للأسواق الكبرى',
                'shape' => 'carton',
                'icon_emoji' => '📦',
                'badge' => 'Retail & Supermarket',
                'default_sizes' => ['500g', '1kg', '2kg', '5kg'],
                'logistics_hint' => '1 Master Box = 10x 1kg or 12x 500g • 1x 40ft Reefer = 22 Pallets',
            ],
            'wood_box' => [
                'id' => 'wood_box',
                'group' => 'dates',
                'group_label_ar' => 'تمور دقلة النور والفواكه',
                'group_label_fr' => 'Dattes Deglet Nour & Fruits',
                'group_label_en' => 'Dates & Fruits',
                'name_en' => 'Prestige Wooden Box',
                'name_fr' => 'Coffret Bois Prestige',
                'name_ar' => 'صندوق خشب فاخر (Coffret Bois)',
                'desc_en' => 'Luxury Handcrafted Wood for Gourmet & Gift Boutiques',
                'desc_fr' => 'Bois noble gravé pour épiceries fines et cadeaux prestige',
                'desc_ar' => 'خشب طبيعي منقوش ومطعم لمتاجر الذواقة والهدايا',
                'shape' => 'wood',
                'icon_emoji' => '🪵',
                'badge' => 'Gourmet Luxury',
                'default_sizes' => ['500g', '1kg', '2kg'],
                'logistics_hint' => '1 Euro Pallet ≈ 480 wooden boxes • High-margin luxury line',
            ],
            'barquette_window' => [
                'id' => 'barquette_window',
                'group' => 'dates',
                'group_label_ar' => 'تمور دقلة النور والفواكه',
                'group_label_fr' => 'Dattes Deglet Nour & Fruits',
                'group_label_en' => 'Dates & Fruits',
                'name_en' => 'Clear Display Tray (Ravier)',
                'name_fr' => 'Ravier Barquette Fenêtre',
                'name_ar' => 'علبة بلاستيك شفافة (Ravier / Barquette)',
                'desc_en' => 'Thermo-sealed Clear Tray with Fruit Fork',
                'desc_fr' => 'Barquette transparente avec fourchette dégustation',
                'desc_ar' => 'علبة بلاستيك شفاف مع شوكة تقديم جاهزة للاستهلاك',
                'shape' => 'barquette',
                'icon_emoji' => '🍱',
                'badge' => 'Grab & Go Retail',
                'default_sizes' => ['200g', '400g', '500g'],
                'logistics_hint' => '1 Master Box = 24x 400g • 1 Euro Pallet ≈ 1,440 ravigo trays',
            ],
            'vacuum_bag' => [
                'id' => 'vacuum_bag',
                'group' => 'dates',
                'group_label_ar' => 'تمور دقلة النور والفواكه',
                'group_label_fr' => 'Dattes Deglet Nour & Fruits',
                'group_label_en' => 'Dates & Fruits',
                'name_en' => 'Vacuum Sealed Pack',
                'name_fr' => 'Sachet Sous-Vide',
                'name_ar' => 'كيس مفرغ من الهواء (Sous-Vide)',
                'desc_en' => 'Moisture-Locked Premium Branch / Pitted Dates',
                'desc_fr' => 'Fraîcheur et moelleux préservés sous vide',
                'desc_ar' => 'حفظ فائق للطراوة والرطوبة الطبيعية للتمر',
                'shape' => 'vacuum',
                'icon_emoji' => '🛍️',
                'badge' => 'Freshness Lock',
                'default_sizes' => ['250g', '500g', '1kg'],
                'logistics_hint' => '1 Master Box = 12x 500g • Compact high-density shipping',
            ],
            'bulk_carton' => [
                'id' => 'bulk_carton',
                'group' => 'dates',
                'group_label_ar' => 'تمور دقلة النور والفواكه',
                'group_label_fr' => 'Dattes Deglet Nour & Fruits',
                'group_label_en' => 'Dates & Fruits',
                'name_en' => 'Bulk Master Carton (5-10kg)',
                'name_fr' => 'Carton Vrac Industriel (5-10kg)',
                'name_ar' => 'كرتون شحن صب (Vrac / 5-10 كغ)',
                'desc_en' => 'Wholesale Unsorted/Branched Dates for Repackers',
                'desc_fr' => 'Dattes branchées ou standard pour industriels et grossistes',
                'desc_ar' => 'كرتون جملة وصب للمصانع وشركات إعادة التعبئة',
                'shape' => 'bulk_carton',
                'icon_emoji' => '📦',
                'badge' => 'Wholesale Bulk',
                'default_sizes' => ['5kg', '10kg', '20kg'],
                'logistics_hint' => '1x 40ft Reefer Container ≈ 2,200 cartons (5kg) = 11 Tonnes',
            ],

            // ==========================================
            // GROUP 3: CANNED SEAFOOD (تونة وسردين معلب)
            // ==========================================
            'tin_can_easy_open' => [
                'id' => 'tin_can_easy_open',
                'group' => 'seafood',
                'group_label_ar' => 'تونة وسردين وأسماك معلبة',
                'group_label_fr' => 'Conserves de Thon & Sardines',
                'group_label_en' => 'Canned Tuna & Sardines',
                'name_en' => 'Easy-Open Metal Tin Can',
                'name_fr' => 'Boîte Métal Easy-Open',
                'name_ar' => 'علبة صفيح سهلة الفتح (Easy-Open)',
                'desc_en' => 'Hermetic Coated Aluminium/Tin Can with Pull-Tab',
                'desc_fr' => 'Boîte sertie vernis alimentaire avec ouverture facile',
                'desc_ar' => 'علبة صفيح صحية مع حلقة فتح سهلة وسريعة',
                'shape' => 'tin_can',
                'icon_emoji' => '🥫',
                'badge' => 'Retail Staple',
                'default_sizes' => ['80g', '125g', '160g', '185g', '400g'],
                'logistics_hint' => '1 Master Case = 48x 160g cans • 1x 20ft FCL = 1,800 cases',
            ],
            'glass_jar_tuna' => [
                'id' => 'glass_jar_tuna',
                'group' => 'seafood',
                'group_label_ar' => 'تونة وسردين وأسماك معلبة',
                'group_label_fr' => 'Conserves de Thon & Sardines',
                'group_label_en' => 'Canned Tuna & Sardines',
                'name_en' => 'Solid Fillets in Glass Jar',
                'name_fr' => 'Bocal Verre Filets Nobles',
                'name_ar' => 'مرطبان زجاجي لشرائح التونة الفاخرة',
                'desc_en' => 'Whole Solid Yellowfin Fillets in Extra Virgin Olive Oil',
                'desc_fr' => 'Filets nobles entiers de thon dans huile d\'olive vierge extra',
                'desc_ar' => 'شرائح تونة كاملة غارقة في زيت زيتون بكر ممتاز',
                'shape' => 'glass_jar',
                'icon_emoji' => '🫙',
                'badge' => 'Artisanal Prestige',
                'default_sizes' => ['200g', '350g', '1kg'],
                'logistics_hint' => '1 Master Tray = 12x 200g jars • Gourmet delicatessen line',
            ],
            'food_service_tin' => [
                'id' => 'food_service_tin',
                'group' => 'seafood',
                'group_label_ar' => 'تونة وسردين وأسماك معلبة',
                'group_label_fr' => 'Conserves de Thon & Sardines',
                'group_label_en' => 'Canned Tuna & Sardines',
                'name_en' => 'Horeca Club Can (1-5kg)',
                'name_fr' => 'Boîte Métal Horeca (1-5kg)',
                'name_ar' => 'علبة صفيح للمطاعم والفنادق (1-5 كغ)',
                'desc_en' => 'High-Yield Large Can for Chefs, Hotels & Food Manufacturers',
                'desc_fr' => 'Grand conditionnement pour restaurants et pizzerias',
                'desc_ar' => 'سعات كبرى للمطاعم والفنادق والمخابز والمصانع',
                'shape' => 'tin_can',
                'icon_emoji' => '🥫',
                'badge' => 'Horeca & Catering',
                'default_sizes' => ['1kg', '1.7kg', '2kg', '5kg'],
                'logistics_hint' => '1 Master Case = 6x 1.7kg cans • 1 Euro Pallet ≈ 400kg',
            ],

            // ====================================================
            // GROUP 4: SUN-DRIED SPECIALTIES, HARISSA & SPICES
            // ====================================================
            'glass_jar' => [
                'id' => 'glass_jar',
                'group' => 'terroir_specialties',
                'group_label_ar' => 'طماطم مجففة، هريسة ومخللات',
                'group_label_fr' => 'Tomates Séchées, Harissa & Terroir',
                'group_label_en' => 'Sun-Dried Tomatoes & Terroir',
                'name_en' => 'Hermetic Glass Jar',
                'name_fr' => 'Bocal Verre Hermétique',
                'name_ar' => 'مرطبان زجاجي محكم (Glass Jar)',
                'desc_en' => 'Sun-Dried Tomatoes & Harissa in Pure Olive Oil & Capers',
                'desc_fr' => 'Tomates séchées et harissa en bain d\'huile d\'olive et câpres',
                'desc_ar' => 'مرطبان زجاجي محكم في زيت الزيتون والأعشاب التونسية',
                'shape' => 'glass_jar',
                'icon_emoji' => '🫙',
                'badge' => 'Deli Gourmet',
                'default_sizes' => ['200g', '280g', '500g', '1kg'],
                'logistics_hint' => '1 Master Carton = 12x 280g • 1 Euro Pallet ≈ 1,152 jars',
            ],
            'food_service_bucket' => [
                'id' => 'food_service_bucket',
                'group' => 'terroir_specialties',
                'group_label_ar' => 'طماطم مجففة، هريسة ومخللات',
                'group_label_fr' => 'Tomates Séchées, Harissa & Terroir',
                'group_label_en' => 'Sun-Dried Tomatoes & Terroir',
                'name_en' => 'Food-Grade Horeca Bucket (3-10kg)',
                'name_fr' => 'Seau Alimentaire Horeca (3-10kg)',
                'name_ar' => 'دلو غذائي للمطاعم والفنادق (3-10 كغ)',
                'desc_en' => 'Hermetic Plastic Bucket with Handle for Gastronomy',
                'desc_fr' => 'Seau plastique hermétique avec anse pour traiteurs et CHR',
                'desc_ar' => 'دلو بلاستيك غذائي بمقبض وإغلاق محكم للمطاعم ومصانع الأغذية',
                'shape' => 'bucket',
                'icon_emoji' => '🪣',
                'badge' => 'Foodservice Grade',
                'default_sizes' => ['3kg', '5kg', '10kg'],
                'logistics_hint' => '1 Euro Pallet ≈ 60 buckets (10kg) = 600kg netto',
            ],
            'bulk_dry_vacuum_pack' => [
                'id' => 'bulk_dry_vacuum_pack',
                'group' => 'terroir_specialties',
                'group_label_ar' => 'طماطم مجففة، هريسة ومخللات',
                'group_label_fr' => 'Tomates Séchées, Harissa & Terroir',
                'group_label_en' => 'Sun-Dried Tomatoes & Terroir',
                'name_en' => 'Bulk Dry Vacuum Pack (5-25kg)',
                'name_fr' => 'Sachet Vrac Sous-Vide (5-25kg)',
                'name_ar' => 'أكياس مجففة مفرغة صب (5-25 كغ)',
                'desc_en' => 'Oil-Free Sun-Dried Halves for Food Manufacturers & Re-packers',
                'desc_fr' => 'Tomates demi-séchées sous vide sans huile pour reconditionneurs',
                'desc_ar' => 'طماطم مجففة بدون زيت في أكياس مفرغة صب للمصانع',
                'shape' => 'vacuum',
                'icon_emoji' => '🛍️',
                'badge' => 'Industrial Grade',
                'default_sizes' => ['5kg', '10kg', '25kg'],
                'logistics_hint' => '1x 20ft Container = 800 cartons (15kg) = 12 Tonnes',
            ],
        ];
    }

    /**
     * Get all packagings grouped by category for Admin UI
     */
    public static function grouped(): array
    {
        $all = self::all();
        $grouped = [];

        foreach ($all as $item) {
            $groupKey = $item['group'];
            if (!isset($grouped[$groupKey])) {
                $grouped[$groupKey] = [
                    'label_ar' => $item['group_label_ar'],
                    'label_fr' => $item['group_label_fr'],
                    'label_en' => $item['group_label_en'],
                    'items' => [],
                ];
            }
            $grouped[$groupKey]['items'][] = $item;
        }

        return $grouped;
    }

    /**
     * Get list of packaging items specifically allowed for a given product
     */
    public static function getPackagingsForProduct(?Product $product): array
    {
        $all = self::all();
        
        if (!$product) {
            return array_values($all);
        }

        $allowedKeys = $product->allowed_packaging_types;

        // Legacy / Alias mapping for old keys
        $aliases = [
            'bottle_glass_marasca' => 'bollar',
            'bottle_glass_dorica' => 'bollar',
            'tin_can' => 'tin',
            'bulk' => 'bulk_flexitank',
            'glass_jar_hermetic' => 'glass_jar',
            'glass_jar_preserves' => 'glass_jar_tuna',
            'private_label_cluster' => 'tin_can_easy_open',
        ];

        if (!empty($allowedKeys) && is_array($allowedKeys)) {
            $result = [];
            foreach ($allowedKeys as $key) {
                $mappedKey = $aliases[$key] ?? $key;
                if (isset($all[$mappedKey]) && !isset($result[$mappedKey])) {
                    $result[$mappedKey] = $all[$mappedKey];
                }
            }
            if (!empty($result)) {
                return array_values($result);
            }
        }

        // Fallback based on Product Category or Slug
        $catSlug = strtolower($product->category->slug ?? '');
        $prodSlug = strtolower($product->slug ?? '');

        if (str_contains($catSlug, 'date') || str_contains($prodSlug, 'date') || str_contains($prodSlug, 'deglet')) {
            return [
                $all['carton_box'],
                $all['wood_box'],
                $all['barquette_window'],
                $all['vacuum_bag'],
                $all['bulk_carton'],
            ];
        }

        if (str_contains($catSlug, 'mer') || str_contains($catSlug, 'thon') || str_contains($catSlug, 'fish') || str_contains($prodSlug, 'thon') || str_contains($prodSlug, 'sardine') || str_contains($prodSlug, 'tuna')) {
            return [
                $all['tin_can_easy_open'],
                $all['glass_jar_tuna'],
                $all['food_service_tin'],
            ];
        }

        if (str_contains($catSlug, 'tomate') || str_contains($catSlug, 'epice') || str_contains($prodSlug, 'tomate') || str_contains($prodSlug, 'harissa')) {
            return [
                $all['glass_jar'],
                $all['food_service_bucket'],
                $all['bulk_dry_vacuum_pack'],
            ];
        }

        // Default to Olive Oil line
        return [
            $all['bollar'],
            $all['pet'],
            $all['tin'],
            $all['squiz'],
            $all['bulk_flexitank'],
        ];
    }
}
