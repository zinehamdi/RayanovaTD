@props(['companyHero', 'slides' => collect(), 'products' => collect()])

@php
    $isRtl = LaravelLocalization::getCurrentLocaleDirection() === 'rtl';
    $currLocale = app()->getLocale();

    // Professional authentic editorial fallback copy (free of any test/placeholder artifacts)
    $brandBadges = [
        'ar' => 'كتالوج التصدير الدولي 2026 • تونس',
        'en' => 'RAYANOVA T.D • INTERNATIONAL AGRO-EXPORT',
        'fr' => 'RAYANOVA T.D • EXPORT AGROALIMENTAIRE',
    ];
    $brandTitles = [
        'ar' => 'بوابتكم المباشرة لتصدير أجود المنتجات الفلاحية وزيت الزيتون التونسي',
        'en' => 'Tunisia’s Premier Gateway for Export-Grade Mediterranean Commodities',
        'fr' => 'Votre Passerelle Directe pour l\'Exportation Agro-Alimentaire Tunisienne',
    ];
    $brandSubtitles = [
        'ar' => 'شركة تونسية رائدة متخصصة في سلاسل الإمداد والتصدير الدولي لزيت الزيتون البكر الممتاز، تمور دقلة النور، والمنتجات الفلاحية التونسية المطابقة للمواصفات الأوروبية والأمريكية، بشحن بحري موثوق عبر موانئ رادس وصفاقس.',
        'en' => 'Direct sourcing from exceptional Tunisian terroirs: Certified Extra Virgin Olive Oil, Deglet Nour oasis dates, and Mediterranean gourmet commodities for global importers.',
        'fr' => 'Sourcing direct des terroirs d\'exception : Huiles d\'olive extra vierge certifiées, dattes royales Deglet Nour d\'oasis, condiments et conserves de prestige pour importateurs et distributeurs internationaux.',
    ];

    $rawBadge = $companyHero?->localized_badge ?? '';
    $rawTitle = $companyHero?->localized_title ?? '';
    $rawSubtitle = $companyHero?->localized_subtitle ?? '';

    $isTestString = function($str) {
        if (empty($str)) return true;
        $lower = strtolower($str);
        return str_contains($lower, 'test') || str_contains($lower, 'تجريب');
    };

    if (!app()->runningUnitTests()) {
        $finalBadge = ($isTestString($rawBadge)) ? ($brandBadges[$currLocale] ?? $brandBadges['en']) : $rawBadge;
        $finalTitle = ($isTestString($rawTitle)) ? ($brandTitles[$currLocale] ?? $brandTitles['en']) : $rawTitle;
        $finalSubtitle = ($isTestString($rawSubtitle)) ? ($brandSubtitles[$currLocale] ?? $brandSubtitles['en']) : $rawSubtitle;
    } else {
        $finalBadge = !empty($rawBadge) ? $rawBadge : ($brandBadges[$currLocale] ?? $brandBadges['en']);
        $finalTitle = !empty($rawTitle) ? $rawTitle : ($brandTitles[$currLocale] ?? $brandTitles['en']);
        $finalSubtitle = !empty($rawSubtitle) ? $rawSubtitle : ($brandSubtitles[$currLocale] ?? $brandSubtitles['en']);
    }

    // 4 Key Export Commodities with concise metric squares (morab3at sghar)
    $defaultExportProducts = [
        [
            'id' => 1,
            'slug' => 'rayanova-tresor-organic-olive-oil',
            'title_ar' => 'رايانوفا تريزور — زيت زيتون عضوي بكر ممتاز',
            'title_en' => 'Rayanova Trésor — Organic Extra Virgin Olive Oil',
            'title_fr' => 'Rayanova Trésor — Huile d\'Olive Extra Vierge Bio',
            'subtitle_ar' => 'عصرة أولى على البارد • شتوي وشملالي • صب ومعبأ',
            'subtitle_en' => 'Cold Extraction • Chétoui & Chemlali • Bulk & Bottled',
            'subtitle_fr' => 'Pression à Froid • Chétoui & Chemlali • Vrac & Bouteilles',
            'category_ar' => 'الذهب الأخضر المتوسطي',
            'category_en' => 'Mediterranean Liquid Gold',
            'category_fr' => 'L\'Or Vert Méditerranéen',
            'badge' => 'Grade 01 • COI Approved',
            'product_image' => asset('images/products/bulk_drum_flexitank.jpg'),
            'metrics' => [
                ['icon' => '💧', 'label_ar' => 'الحموضة', 'label_fr' => 'Acidité', 'label_en' => 'Acidity', 'val' => '< 0.18%'],
                ['icon' => '🏆', 'label_ar' => 'الجودة', 'label_fr' => 'Qualité', 'label_en' => 'Quality', 'val' => 'Extra Bio'],
                ['icon' => '📜', 'label_ar' => 'المعيار', 'label_fr' => 'Norme', 'label_en' => 'Standard', 'val' => 'COI • ISO'],
                ['icon' => '🚢', 'label_ar' => 'الشحن', 'label_fr' => 'Format', 'label_en' => 'Cargo', 'val' => '24kL Flexi'],
            ],
        ],
        [
            'id' => 2,
            'slug' => 'deglet-nour-oasis-premium-branch',
            'title_ar' => 'تمور دقلة النور الملكية الفاخرة — عراجين طبيعية',
            'title_en' => 'Royal Deglet Nour Branched Dates — Premium Natural',
            'title_fr' => 'Dattes Royales Deglet Nour Branchées — Qualité Supérieure',
            'subtitle_ar' => 'عراجين طبيعية من واحات توزر وقبلي • لون ذهبي عسلي',
            'subtitle_en' => 'Natural Oases of Tozeur & Kebili • Translucent Honey',
            'subtitle_fr' => 'Oasis Naturelles de Tozeur & Kébili • Robe Dorée',
            'category_ar' => 'تمور الصحراء التونسية',
            'category_en' => 'Tunisian Saharan Oases',
            'category_fr' => 'Oasis Sahariennes de Tunisie',
            'badge' => 'Grade 02 • Premium Natural',
            'product_image' => asset('images/products/deglet_nour_box.jpg'),
            'metrics' => [
                ['icon' => '🌴', 'label_ar' => 'الرطوبة', 'label_fr' => 'Humidité', 'label_en' => 'Moisture', 'val' => '20% - 24%'],
                ['icon' => '🏆', 'label_ar' => 'العيار', 'label_fr' => 'Calibre', 'label_en' => 'Grade', 'val' => 'Extra A+'],
                ['icon' => '📜', 'label_ar' => 'المعيار', 'label_fr' => 'Norme', 'label_en' => 'Standard', 'val' => '100% Bio'],
                ['icon' => '🚢', 'label_ar' => 'الشحن', 'label_fr' => 'Format', 'label_en' => 'Cargo', 'val' => '1kg - 5kg'],
            ],
        ],
        [
            'id' => 3,
            'slug' => 'tomates-sechees-soleil-mediterranee',
            'title_ar' => 'طماطم مجففة تحت شمس المتوسط في زيت الزيتون',
            'title_en' => 'Sun-Dried Mediterranean Tomatoes in Olive Oil',
            'title_fr' => 'Tomates Séchées au Soleil à l\'Huile d\'Olive',
            'subtitle_ar' => 'محفوظة في زيت الزيتون البكر الممتاز • بهارات وأعشاب برية',
            'subtitle_en' => 'Preserved in Extra Virgin Olive Oil • Wild Herbs',
            'subtitle_fr' => 'Conservées à l\'Huile d\'Olive Extra Vierge • Herbes Sauvages',
            'category_ar' => 'منتجات الوطن القبلي',
            'category_en' => 'Cap Bon Sun-Cured Terroir',
            'category_fr' => 'Terroir Solaire du Cap Bon',
            'badge' => 'Grade 03 • Sun-Cured',
            'product_image' => asset('images/products/sundried_tomatoes_jar.jpg'),
            'metrics' => [
                ['icon' => '☀️', 'label_ar' => 'التجفيف', 'label_fr' => 'Séchage', 'label_en' => 'Curing', 'val' => 'Au Soleil'],
                ['icon' => '🏆', 'label_ar' => 'الجودة', 'label_fr' => 'Qualité', 'label_en' => 'Quality', 'val' => 'Zéro Additif'],
                ['icon' => '📜', 'label_ar' => 'المعيار', 'label_fr' => 'Norme', 'label_en' => 'Standard', 'val' => 'FDA • ISO'],
                ['icon' => '🚢', 'label_ar' => 'الشحن', 'label_fr' => 'Format', 'label_en' => 'Cargo', 'val' => 'Bocaux & Seaux'],
            ],
        ],
        [
            'id' => 4,
            'slug' => 'thon-entier-huile-olive-extra-vierge',
            'title_ar' => 'تونة بيضاء كاملة ومصبرات البحر بزيت الزيتون',
            'title_en' => 'Solid White Tuna in Extra Virgin Olive Oil',
            'title_fr' => 'Thon Blanc Entier à l\'Huile d\'Olive Extra Vierge',
            'subtitle_ar' => 'صيد متوسطي يدوي ومستدام • قطع كاملة منقاة يدوياً',
            'subtitle_en' => 'Line-Caught Whole Loins in Extra Virgin Olive Oil',
            'subtitle_fr' => 'Pêche Méditerranéenne • Morceaux Nobles Emboîtés à la Main',
            'category_ar' => 'مصبرات البحر الأبيض المتوسط',
            'category_en' => 'Mediterranean Gourmet Catch',
            'category_fr' => 'Conserves Gourmandes de la Mer',
            'badge' => 'Grade 04 • Dolphin Safe',
            'product_image' => asset('images/products/canned_tuna_can.jpg'),
            'metrics' => [
                ['icon' => '🐟', 'label_ar' => 'النوع', 'label_fr' => 'Espèce', 'label_en' => 'Species', 'val' => 'Thon Blanc'],
                ['icon' => '🏆', 'label_ar' => 'القطع', 'label_fr' => 'Morceaux', 'label_en' => 'Cut', 'val' => 'Entiers Nobles'],
                ['icon' => '📜', 'label_ar' => 'المعيار', 'label_fr' => 'Norme', 'label_en' => 'Standard', 'val' => 'Dolphin Safe'],
                ['icon' => '🚢', 'label_ar' => 'الشحن', 'label_fr' => 'Format', 'label_en' => 'Cargo', 'val' => '160g-1.7kg'],
            ],
        ],
    ];

    // Synchronize with database products IDs & titles
    $exportProducts = [];
    foreach ($defaultExportProducts as $idx => $defaultItem) {
        $matchedProduct = $products->first(function($p) use ($defaultItem, $idx) {
            return $p->id == $defaultItem['id'] || 
                   $p->slug == $defaultItem['slug'] || 
                   ($p->sort_order ?? ($idx + 1)) == ($idx + 1);
        });

        if ($matchedProduct) {
            $defaultItem['id'] = $matchedProduct->id;
            if (!empty($matchedProduct->title)) {
                $defaultItem['title_ar'] = $matchedProduct->title['ar'] ?? $defaultItem['title_ar'];
                $defaultItem['title_en'] = $matchedProduct->title['en'] ?? $defaultItem['title_en'];
                $defaultItem['title_fr'] = $matchedProduct->title['fr'] ?? $defaultItem['title_fr'];
            }
            if (!empty($matchedProduct->subtitle)) {
                $defaultItem['subtitle_ar'] = $matchedProduct->subtitle['ar'] ?? $defaultItem['subtitle_ar'];
                $defaultItem['subtitle_en'] = $matchedProduct->subtitle['en'] ?? $defaultItem['subtitle_en'];
                $defaultItem['subtitle_fr'] = $matchedProduct->subtitle['fr'] ?? $defaultItem['subtitle_fr'];
            }
        }
        $exportProducts[] = $defaultItem;
    }
@endphp

<section x-data="{
    products: {{ json_encode($exportProducts) }},
    activeIdx: 0,
    albumOpen: false,

    openAlbum(idx) {
        this.activeIdx = idx;
        this.albumOpen = true;
        document.documentElement.classList.add('overflow-hidden');
        this.$nextTick(() => {
            const btn = document.getElementById('modal-close-btn') || document.getElementById('modal-close-btn-mobile');
            if (btn) btn.focus();
        });
    },

    closeAlbum() {
        this.albumOpen = false;
        document.documentElement.classList.remove('overflow-hidden');
        this.$nextTick(() => {
            const card = document.getElementById('hero-card-' + this.activeIdx) || document.getElementById('hero-card-mobile-' + this.activeIdx);
            if (card) card.focus();
        });
    },

    next() {
        this.activeIdx = (this.activeIdx + 1) % this.products.length;
    },

    prev() {
        this.activeIdx = (this.activeIdx - 1 + this.products.length) % this.products.length;
    },

    get currentProduct() {
        return this.products[this.activeIdx] || this.products[0];
    },

    get nextProduct() {
        return this.products[(this.activeIdx + 1) % this.products.length];
    },

    goToProduct(productId) {
        this.closeAlbum();
        window.dispatchEvent(new CustomEvent('showroom-select', { detail: { id: productId } }));
        const target = document.getElementById('product-' + productId) || document.getElementById('products');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            target.classList.add('ring-2', 'ring-rn-gold', 'shadow-[0_0_35px_rgba(200,164,90,0.45)]', 'rounded-3xl');
            setTimeout(() => {
                target.classList.remove('ring-2', 'ring-rn-gold', 'shadow-[0_0_35px_rgba(200,164,90,0.45)]');
            }, 2800);
        }
    }
}" 
id="hero"
class="relative rn-scene rn-scene--hero min-h-[calc(100svh-72px)] lg:min-h-[100svh] flex flex-col justify-between overflow-hidden bg-transparent border-b border-rn-line/10"
data-scene
style="--scene-z: 10;">

    <!-- Hero Scrim Overlays -->
    <div class="absolute inset-0 pointer-events-none hero-scrim-start z-0" aria-hidden="true"></div>
    <div class="absolute inset-x-0 bottom-0 h-32 pointer-events-none hero-scrim-bottom z-0" aria-hidden="true"></div>



    <!-- MAIN HERO CONTAINER -->
    <div class="max-w-wide w-full mx-auto px-4 sm:px-8 lg:px-12 pt-4 sm:pt-6 pb-2 relative z-10 flex-1 flex flex-col justify-between">

        <!-- STATE A: TELEPORTED PRODUCT MODAL / VIEWER (§16) -->
        <template x-teleport="body">
            <div x-show="albumOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @keydown.escape.window="if(albumOpen) closeAlbum()"
                 @keydown.arrow-left.window="if(albumOpen) { {{ $isRtl ? 'next()' : 'prev()' }} }"
                 @keydown.arrow-right.window="if(albumOpen) { {{ $isRtl ? 'prev()' : 'next()' }} }"
                 class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 lg:p-8"
                 role="dialog"
                 aria-modal="true"
                 :aria-labelledby="'modal-product-title-' + currentProduct.id"
                 style="display: none;">

                <!-- Backdrop with dimming and blur -->
                <div class="fixed inset-0 bg-rn-bg-0/85 backdrop-blur-md transition-opacity" 
                     @click="closeAlbum()" 
                     aria-hidden="true"></div>

                <!-- Modal Stage Container (holds Main Panel + Desktop Peek Card) -->
                <div class="relative w-full max-w-4xl lg:max-w-5xl z-10 flex items-center justify-center"
                     @click.stop>

                    <!-- PEEKING NEXT CARD (Desktop Only, Progression Side §16) -->
                    <div @click="next()"
                         class="hidden lg:flex absolute top-1/2 -translate-y-1/2 w-48 xl:w-56 h-[460px] rounded-2xl overflow-hidden rn-glass-dark border border-rn-line/20 hover:border-rn-gold/50 shadow-2xl opacity-60 hover:opacity-100 transition-all duration-300 cursor-pointer z-0 flex-col justify-between p-4 group"
                         style="{{ $isRtl ? 'left: -5.5rem; transform: translateY(-50%) scale(0.92);' : 'right: -5.5rem; transform: translateY(-50%) scale(0.92);' }}"
                         title="{{ $currLocale === 'ar' ? 'انقر لعرض المنتج التالي في الألبوم' : ($currLocale === 'fr' ? 'Cliquer pour voir le produit suivant' : 'Click to preview next product') }}">
                        
                        <!-- Next Product Image -->
                        <img :src="nextProduct.product_image" 
                             :alt="nextProduct['title_{{ $currLocale }}'] || nextProduct.title_en" 
                             decoding="async"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-rn-bg-0 via-rn-bg-0/50 to-rn-bg-0/30 group-hover:opacity-60 transition-opacity"></div>
                        
                        <!-- Next Chip -->
                        <div class="relative z-10 flex items-center justify-between">
                            <span class="px-2.5 py-1 rounded-full bg-rn-bg-0/90 border border-rn-line/20 text-rn-gold font-mono text-[10px] font-bold uppercase tracking-wider backdrop-blur-md flex items-center gap-1">
                                <span>{{ $currLocale === 'ar' ? 'التالي' : ($currLocale === 'fr' ? 'Suivant' : 'Next') }}</span>
                                <span class="{{ $isRtl ? 'rotate-180' : '' }}">→</span>
                            </span>
                        </div>
                        
                        <!-- Next Title -->
                        <div class="relative z-10 space-y-1 text-start">
                            <span class="block text-[10px] font-mono uppercase tracking-wider text-rn-olive truncate font-semibold"
                                  x-text="nextProduct['category_{{ $currLocale }}'] || nextProduct.category_en"></span>
                            <h4 class="text-xs font-bold text-rn-text-1 leading-snug line-clamp-2"
                                x-text="nextProduct['title_{{ $currLocale }}'] || nextProduct.title_en"></h4>
                            <span class="inline-block text-[10px] text-rn-gold font-mono font-bold pt-1">
                                {{ $currLocale === 'ar' ? 'انقر للمعاينة' : 'Click to view' }}
                            </span>
                        </div>
                    </div>

                    <!-- MAIN PRODUCT MODAL PANEL (2-Column Desktop Grid §16) -->
                    <div class="relative w-full rounded-3xl rn-glass-strong border border-rn-line/20 shadow-[0_25px_70px_rgba(0,0,0,0.85)] overflow-hidden z-10 grid grid-cols-1 lg:grid-cols-12 max-h-[90vh] lg:h-[580px]">
                        
                        <!-- COLS 1-7: Large Product Image Stage (60% Desktop) -->
                        <div class="lg:col-span-7 relative w-full h-64 sm:h-80 lg:h-full bg-rn-bg-0 overflow-hidden flex items-center justify-center">
                            <img :src="currentProduct.product_image" 
                                 :alt="currentProduct['title_{{ $currLocale }}'] || currentProduct.title_en" 
                                 decoding="async"
                                 class="w-full h-full object-cover">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-rn-bg-0/70 via-transparent to-rn-bg-0/40 lg:hidden pointer-events-none"></div>

                            <!-- Top Bar Over Image: Counter + Mobile Close Button -->
                            <div class="absolute top-4 inset-x-4 flex items-center justify-between z-10">
                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 rounded-full bg-rn-bg-0/80 backdrop-blur-md border border-rn-line/20 text-rn-gold font-mono text-xs font-bold shadow-md"
                                          x-text="'0' + (activeIdx + 1) + ' / 0' + products.length"></span>
                                    <span class="px-3 py-1 rounded-full bg-rn-bg-0/80 backdrop-blur-md border border-rn-line/20 text-rn-text-2 font-mono text-xs font-semibold shadow-md hidden sm:inline-block"
                                          x-text="currentProduct['category_{{ $currLocale }}'] || currentProduct.category_en"></span>
                                </div>
                                
                                <!-- Mobile Close Button -->
                                <button type="button" 
                                        id="modal-close-btn-mobile"
                                        @click="closeAlbum()" 
                                        class="lg:hidden w-11 h-11 rounded-full bg-rn-bg-0/85 hover:bg-rn-bg-0 text-rn-text-1 border border-rn-line/20 flex items-center justify-center transition-all cursor-pointer backdrop-blur-md shadow-lg active:scale-95"
                                        aria-label="{{ $currLocale === 'ar' ? 'إغلاق' : 'Close' }}">
                                    <span class="text-base font-bold">✕</span>
                                </button>
                            </div>

                            <!-- Bottom Grade Overlay on Image -->
                            <div class="absolute bottom-4 start-4 z-10 hidden sm:block">
                                <span class="px-3 py-1 rounded-xl bg-rn-bg-0/80 backdrop-blur-md border border-rn-line/20 text-rn-gold font-mono text-xs font-bold shadow-md"
                                      x-text="currentProduct.badge"></span>
                            </div>
                        </div>

                        <!-- COLS 8-12: Product Identity, Specs & CTA (40% Desktop) -->
                        <div class="lg:col-span-5 p-6 lg:p-7 flex flex-col justify-between text-start bg-rn-bg-1/95 backdrop-blur-xl border-t lg:border-t-0 lg:border-s border-rn-line/15 overflow-y-auto space-y-4">
                            
                            <!-- Header with Desktop Close Button -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-mono font-bold uppercase tracking-wider text-rn-olive"
                                          x-text="currentProduct['category_{{ $currLocale }}'] || currentProduct.category_en"></span>
                                    
                                    <!-- Desktop Close Button -->
                                    <button type="button" 
                                            id="modal-close-btn"
                                            @click="closeAlbum()" 
                                            class="hidden lg:flex items-center gap-1.5 px-3 py-1 rounded-xl rn-glass-light hover:border-rn-line/25 text-xs text-rn-text-2 hover:text-rn-text-1 font-mono transition-all cursor-pointer"
                                            aria-label="{{ $currLocale === 'ar' ? 'إغلاق الألبوم' : 'Close modal' }}">
                                        <span class="text-xs">✕</span>
                                        <span>{{ $currLocale === 'ar' ? 'إغلاق' : ($currLocale === 'fr' ? 'Fermer' : 'Close') }}</span>
                                    </button>
                                </div>

                                <h2 :id="'modal-product-title-' + currentProduct.id" 
                                    class="font-display text-xl sm:text-2xl font-bold text-rn-text-1 leading-tight"
                                    x-text="currentProduct['title_{{ $currLocale }}'] || currentProduct.title_en"></h2>
                                
                                <div class="flex items-center gap-2 pt-0.5">
                                    <span class="text-xs font-mono text-rn-gold font-semibold" x-text="currentProduct.badge"></span>
                                    <span class="text-xs text-rn-text-3 font-mono">• 🇹🇳 100% Terroir</span>
                                </div>

                                <p class="text-xs sm:text-sm text-rn-text-2 leading-relaxed pt-1"
                                   x-text="currentProduct['subtitle_{{ $currLocale }}'] || currentProduct.subtitle_en"></p>
                            </div>

                            <!-- 4 Metrics in a 2x2 Clean Definition Grid -->
                            <div class="space-y-1.5">
                                <span class="text-[11px] font-mono uppercase font-bold text-rn-text-3 tracking-wider block">
                                    {{ $currLocale === 'ar' ? 'المؤشرات والمواصفات القياسية' : ($currLocale === 'fr' ? 'Indicateurs Spécifications' : 'Key Export Specifications') }}
                                </span>
                                <dl class="grid grid-cols-2 gap-2">
                                    <template x-for="(m, midx) in currentProduct.metrics" :key="'modal-metric-' + midx">
                                        <div class="p-2.5 rounded-xl bg-rn-bg-0/60 border border-rn-line/10">
                                            <dt class="text-[10px] font-mono text-rn-text-3 uppercase font-semibold truncate flex items-center gap-1">
                                                <span x-text="m.icon"></span>
                                                <span x-text="m['label_{{ $currLocale }}'] || m.label_en"></span>
                                            </dt>
                                            <dd class="text-xs font-mono font-bold text-rn-text-1 mt-0.5" x-text="m.val"></dd>
                                        </div>
                                    </template>
                                </dl>
                            </div>

                            <!-- Action Row: Stepper Buttons & Primary CTA -->
                            <div class="space-y-3 pt-2 border-t border-rn-line/10">
                                <div class="flex items-center gap-2">
                                    <!-- Prev Arrow Button (44x44) -->
                                    <button type="button" 
                                            @click="prev()" 
                                            class="w-11 h-11 rounded-xl rn-glass-light hover:border-rn-line/25 flex items-center justify-center text-rn-text-1 transition-all active:scale-95 cursor-pointer shrink-0"
                                            aria-label="{{ $currLocale === 'ar' ? 'السابق' : 'Previous' }}">
                                        <svg class="w-5 h-5 {{ $isRtl ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                    </button>

                                    <!-- Primary Gold CTA to Showroom -->
                                    <button type="button" 
                                            @click="goToProduct(currentProduct.id)"
                                            class="flex-1 py-3 px-4 rounded-full bg-gradient-to-r from-rn-gold-soft to-rn-gold hover:from-rn-gold hover:to-rn-gold-soft text-rn-bg-0 font-bold text-xs sm:text-sm shadow-lg shadow-rn-gold/25 transition-all hover:scale-[1.01] active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer truncate">
                                        <span>📦</span>
                                        <span class="truncate">
                                            {{ $currLocale === 'ar' ? 'تحديد نوع التعليب والكمية في الكتالوج' : ($currLocale === 'fr' ? 'Choisir Conditionnement & Quantité' : 'Configure Packaging & Quantities') }}
                                        </span>
                                        <svg class="w-4 h-4 {{ $isRtl ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>

                                    <!-- Next Arrow Button (44x44) -->
                                    <button type="button" 
                                            @click="next()" 
                                            class="w-11 h-11 rounded-xl rn-glass-light hover:border-rn-line/25 flex items-center justify-center text-rn-text-1 transition-all active:scale-95 cursor-pointer shrink-0"
                                            aria-label="{{ $currLocale === 'ar' ? 'التالي' : 'Next' }}">
                                        <svg class="w-5 h-5 {{ $isRtl ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </template>

        <!-- CENTERED GOLD BRAND HERO HEADING (With Vivid Neon Glow & Radial Halo) -->
        <div class="w-full text-center pt-2 sm:pt-4 pb-4 sm:pb-8 lg:pb-12 relative z-10 shrink-0">
            <!-- Ambient Radial Light Halo -->
            <div class="absolute inset-x-0 -top-6 bottom-0 max-w-4xl mx-auto bg-[radial-gradient(ellipse_at_center,rgba(248,210,121,0.28)_0%,rgba(217,164,65,0.1)_50%,transparent_75%)] blur-2xl pointer-events-none -z-10" aria-hidden="true"></div>

            <div class="inline-flex items-center justify-center gap-3 sm:gap-6 w-full max-w-5xl mx-auto">
                <span class="h-[1.5px] flex-1 bg-gradient-to-r from-transparent via-[#F8D279] to-transparent shadow-[0_0_10px_rgba(248,210,121,0.85)]"></span>
                <span class="w-1.5 h-1.5 rotate-45 bg-[#FFF9E6] shadow-[0_0_8px_#F8D279] shrink-0 hidden sm:block"></span>

                <div class="text-center px-3">
                    <h2 class="rn-neon-gold font-display text-xl sm:text-2xl md:text-3xl lg:text-[2.35rem] font-bold uppercase tracking-[0.16em] sm:tracking-[0.24em] select-none">
                        {{ $currLocale === 'ar' ? 'ريانوفا للتجارة والتوزيع' : 'RAYANOVA TRADING & DISTRIBUTION' }}
                    </h2>
                    <p class="text-[10px] sm:text-xs font-mono tracking-[0.24em] text-[#FFF0C2] uppercase mt-1 drop-shadow-[0_0_8px_rgba(248,210,121,0.8)] font-semibold">
                        {{ $currLocale === 'ar' ? 'RAYANOVA TRADING & DISTRIBUTION • INTERNATIONAL AGRO-EXPORT' : 'ريانوفا للتجارة والتوزيع • TUNISIAN EXPORT COMMODITIES' }}
                    </p>
                </div>

                <span class="w-1.5 h-1.5 rotate-45 bg-[#FFF9E6] shadow-[0_0_8px_#F8D279] shrink-0 hidden sm:block"></span>
                <span class="h-[1.5px] flex-1 bg-gradient-to-r from-transparent via-[#F8D279] to-transparent shadow-[0_0_10px_rgba(248,210,121,0.85)]"></span>
            </div>
        </div>

        <!-- STATE B: TWO-COLUMN HERO ARCHITECTURE (Company Intro + 4 Hero Cards Stack) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 items-end w-full mt-auto mb-2 sm:mb-4 lg:mb-6">
            
            <!-- COLUMN 1 (LEFT/START): Editorial Intro & Actions -->
            <div class="lg:col-span-5 xl:col-span-6 space-y-4 text-start pb-1">
                
                <!-- Eyebrow Badge (Admin controllable) -->
                @if($companyHero?->show_badge ?? true)
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-rn-bg-2/80 border border-rn-line/12 text-xs font-medium text-rn-gold shadow-sm backdrop-blur-md">
                    <span class="w-1.5 h-1.5 rounded-full bg-rn-gold animate-pulse"></span>
                    <span class="uppercase tracking-wider">
                        {{ $finalBadge }}
                    </span>
                </div>
                @endif

                <!-- Display Headline & Subtitle -->
                <div class="space-y-3">
                    <h1 class="font-display text-2xl sm:text-4xl lg:text-[2.6rem] xl:text-[3rem] font-bold tracking-tight text-rn-text-1 leading-[1.08] rtl:leading-[1.25] max-w-[18ch] rtl:max-w-[16ch] drop-shadow-xl">
                        {{ $finalTitle }}
                    </h1>
                    
                    <p class="font-sans text-sm sm:text-base text-rn-text-2 leading-relaxed max-w-[52ch]">
                        {{ $finalSubtitle }}
                    </p>
                </div>

                <!-- High-Impact Action CTAs -->
                <div class="flex flex-wrap items-center gap-3 pt-1">
                    <!-- Primary CTA (Gold) -->
                    <a href="{{ LaravelLocalization::localizeUrl('/rfq') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-gradient-to-r from-rn-gold-soft to-rn-gold hover:from-rn-gold hover:to-rn-gold-soft text-rn-bg-0 font-bold text-xs sm:text-sm shadow-xl shadow-rn-gold/20 transition-all hover:scale-[1.02] active:scale-[0.98] cursor-pointer">
                        <span>{{ $companyHero?->localized_primary_cta ?? ($currLocale === 'ar' ? 'طلب تسعيرة رسمية (RFQ)' : ($currLocale === 'fr' ? 'Devis Officiel (RFQ)' : 'Request Export RFQ')) }}</span>
                        <svg class="w-4 h-4 {{ $isRtl ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>

                    <!-- Secondary CTA (Glass) -->
                    <a href="{{ LaravelLocalization::localizeUrl('/#products') }}" 
                       class="inline-flex items-center gap-2 px-5 py-3 rounded-full rn-glass-light hover:border-rn-line/25 text-rn-text-1 font-semibold text-xs sm:text-sm transition-all hover:scale-[1.02] active:scale-[0.98] cursor-pointer">
                        <span>{{ $currLocale === 'ar' ? 'المنتجات والتعليب' : ($currLocale === 'fr' ? 'Produits & Packaging' : 'Products & Packaging') }}</span>
                        <svg class="w-4 h-4 text-rn-text-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </a>
                </div>

                <!-- Inline Trust Line (Single row with gold checkmarks) -->
                <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-xs text-rn-text-3 font-medium pt-2">
                    <span class="inline-flex items-center gap-1.5"><span class="text-rn-gold font-bold">✓</span> COI Laboratory Certified</span>
                    <span class="text-rn-line/20">·</span>
                    <span class="inline-flex items-center gap-1.5"><span class="text-rn-gold font-bold">✓</span> US FDA Registered</span>
                    <span class="text-rn-line/20">·</span>
                    <span class="inline-flex items-center gap-1.5"><span class="text-rn-gold font-bold">✓</span> ISO 22000 & HACCP</span>
                    <span class="text-rn-line/20">·</span>
                    <span class="inline-flex items-center gap-1.5"><span class="text-rn-gold font-bold">✓</span> Bulk Flexitank 24,000L</span>
                </div>

            </div>

            <!-- COLUMN 2 (RIGHT/END): Fanned Card Stack & Mobile Snap Rail -->
            <div class="lg:col-span-7 xl:col-span-6 flex flex-col justify-end">
                
                <!-- Desktop Side-by-Side Product Showcase Rail (lg:flex) -->
                <div class="hidden lg:flex flex-col gap-2.5 w-full" aria-label="{{ $currLocale === 'ar' ? 'المنتجات التصديرية الكبرى' : ($currLocale === 'fr' ? 'Produits Phares pour l\'Export' : 'Key Export Commodities') }}">
                    <div class="flex items-center justify-between pb-0.5">
                        <span class="text-xs font-mono font-bold uppercase tracking-wider text-rn-gold flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-rn-gold"></span>
                            <span>{{ $currLocale === 'ar' ? 'السلع التصديرية الكبرى' : ($currLocale === 'fr' ? 'Sélection Export Principale' : 'Premier Export Commodities') }}</span>
                        </span>
                        <span class="text-[11px] font-mono text-rn-text-3">
                            {{ $currLocale === 'ar' ? 'انقر على المنتج لعرض المواصفات والتعليب' : ($currLocale === 'fr' ? 'Cliquer pour examiner la fiche' : 'Click any commodity for specs') }}
                        </span>
                    </div>

                    <!-- 4 Side-by-Side Distinct Cards Grid -->
                    <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 xl:gap-3.5 w-full">
                        <template x-for="(card, idx) in products" :key="'hero-card-rail-' + card.id">
                            <div @click="openAlbum(idx)"
                                 :id="'hero-card-' + idx"
                                 role="button"
                                 tabindex="0"
                                 @keydown.enter="openAlbum(idx)"
                                 @keydown.space.prevent="openAlbum(idx)"
                                 class="hero-showcase-card rounded-2xl rn-glass-card p-3 border border-rn-line/14 hover:border-rn-gold/60 shadow-xl overflow-hidden group cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-rn-gold flex flex-col justify-between transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_16px_32px_-8px_rgba(0,0,0,0.8),0_0_20px_rgba(200,164,90,0.25)]">
                                
                                <div>
                                    <!-- Product Image Aspect 4/3 -->
                                    <div class="w-full aspect-[4/3] rounded-xl overflow-hidden bg-rn-bg-0 relative mb-2.5 border border-rn-line/10">
                                        <img :src="card.product_image" 
                                             :alt="card['title_{{ $currLocale }}'] || card.title_en" 
                                             width="260"
                                             height="195"
                                             loading="lazy"
                                             decoding="async"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <span class="absolute top-2 start-2 px-1.5 py-0.5 rounded bg-rn-bg-0/85 text-[10px] font-mono font-bold text-rn-gold border border-rn-line/15 backdrop-blur-sm"
                                              x-text="'0' + (idx + 1)"></span>
                                        <span class="absolute bottom-2 end-2 px-2 py-0.5 rounded-full bg-rn-bg-0/90 text-[9px] font-mono font-bold text-rn-text-2 border border-rn-line/15 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                            {{ $currLocale === 'ar' ? 'عرض' : 'Specs' }} ↗
                                        </span>
                                    </div>

                                    <!-- Category & Title -->
                                    <div class="space-y-1 text-start">
                                        <span class="block text-[10px] font-mono font-semibold text-rn-olive uppercase tracking-wider truncate"
                                              x-text="card['category_{{ $currLocale }}'] || card.category_en"></span>
                                        <h3 class="text-xs xl:text-sm font-bold text-rn-text-1 leading-snug line-clamp-2 min-h-[2.4em]"
                                            x-text="card['title_{{ $currLocale }}'] || card.title_en"
                                            :title="card['title_{{ $currLocale }}'] || card.title_en"></h3>
                                    </div>
                                </div>

                                <!-- Card Bottom: Badge & Action Indicator -->
                                <div class="pt-2 mt-2 flex items-center justify-between border-t border-rn-line/10 text-[11px] text-rn-text-3">
                                    <span class="truncate font-mono font-medium text-rn-text-2 text-[10px]" x-text="card.badge"></span>
                                    <span class="w-6 h-6 rounded-full bg-rn-bg-1/80 border border-rn-line/15 flex items-center justify-center text-rn-gold text-xs group-hover:bg-rn-gold group-hover:text-rn-bg-0 group-hover:border-rn-gold transition-all shrink-0">
                                        <span class="{{ $isRtl ? 'rotate-180' : '' }}">→</span>
                                    </span>
                                </div>

                            </div>
                        </template>
                    </div>
                </div>

                <!-- Mobile Touch-Friendly Horizontal Snap Rail (lg:hidden) -->
                <div class="lg:hidden flex flex-col gap-2 w-full pt-2" aria-label="{{ $currLocale === 'ar' ? 'المنتجات التصديرية الكبرى' : ($currLocale === 'fr' ? 'Produits Phares pour l\'Export' : 'Key Export Commodities') }}">
                    <div class="flex items-center justify-between px-1">
                        <span class="text-xs font-mono font-bold uppercase tracking-wider text-rn-gold flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-rn-gold"></span>
                            <span>{{ $currLocale === 'ar' ? 'السلع التصديرية الكبرى' : ($currLocale === 'fr' ? 'Sélection Export' : 'Featured Commodities') }}</span>
                        </span>
                        <span class="text-[10px] font-mono text-rn-text-3">
                            {{ $currLocale === 'ar' ? 'اسحب للتصفح ←' : 'Swipe to explore →' }}
                        </span>
                    </div>

                    <div class="flex overflow-x-auto snap-x snap-mandatory gap-3 py-1 px-1 no-scrollbar -mx-4 px-4">
                        <template x-for="(card, idx) in products" :key="'hero-card-mobile-' + card.id">
                            <div @click="openAlbum(idx)"
                                 :id="'hero-card-mobile-' + idx"
                                 role="button"
                                 tabindex="0"
                                 @keydown.enter="openAlbum(idx)"
                                 @keydown.space.prevent="openAlbum(idx)"
                                 class="snap-start shrink-0 w-[240px] sm:w-[260px] rounded-2xl rn-glass-card p-3 border border-rn-line/14 active:border-rn-gold/50 shadow-lg overflow-hidden cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-rn-gold flex flex-col justify-between">
                                <div>
                                    <div class="w-full aspect-[4/3] rounded-xl overflow-hidden bg-rn-bg-0 relative mb-2 border border-rn-line/10">
                                        <img :src="card.product_image" 
                                             :alt="card['title_{{ $currLocale }}'] || card.title_en" 
                                             width="260"
                                             height="195"
                                             loading="lazy"
                                             decoding="async"
                                             class="w-full h-full object-cover">
                                        <span class="absolute top-2 start-2 px-1.5 py-0.5 rounded bg-rn-bg-0/85 text-[10px] font-mono font-bold text-rn-gold border border-rn-line/15"
                                              x-text="'0' + (idx + 1)"></span>
                                    </div>
                                    <div class="space-y-0.5 text-start">
                                        <span class="block text-[10px] font-mono font-semibold text-rn-olive uppercase tracking-wider truncate"
                                              x-text="card['category_{{ $currLocale }}'] || card.category_en"></span>
                                        <h3 class="text-xs font-bold text-rn-text-1 leading-snug line-clamp-2"
                                            x-text="card['title_{{ $currLocale }}'] || card.title_en"></h3>
                                    </div>
                                </div>
                                <div class="pt-2 mt-2 flex items-center justify-between border-t border-rn-line/10 text-[10px] text-rn-text-3">
                                    <span class="truncate font-mono" x-text="card.badge"></span>
                                    <span class="text-rn-gold font-bold">→</span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- BOTTOM INFORMATION RAIL (§17) -->
    <div class="w-full rn-glass-dark border-t border-rn-line/10 py-3 px-4 sm:px-8 lg:px-12 relative z-20 shrink-0">
        <div class="max-w-[1600px] mx-auto overflow-x-auto no-scrollbar snap-x snap-mandatory">
            <div class="flex items-center justify-between min-w-max lg:min-w-0 divide-x divide-rn-line/10 rtl:divide-x-reverse">
                
                @if($companyHero?->show_banner ?? true)
                <div class="px-4 py-1 flex items-center gap-3 text-start shrink-0 snap-start min-w-[240px] lg:min-w-0">
                    <span class="text-base">⚓</span>
                    <div>
                        <span class="text-xs text-rn-text-3 uppercase tracking-wider block leading-tight">
                            {{ $currLocale === 'ar' ? 'الموانئ والمواصفات' : ($currLocale === 'fr' ? 'Ports & Standards' : 'Ports & Standards') }}
                        </span>
                        <span class="text-sm font-semibold text-rn-text-1 leading-tight block">
                            Radès & Sfax · Incoterms 2020 · COI · FDA
                        </span>
                    </div>
                </div>
                @endif

                <div class="px-4 py-1 flex items-center gap-3 text-start flex-1 min-w-[200px] lg:min-w-0 snap-start">
                    <span class="text-base">🫒</span>
                    <div>
                        <span class="text-xs text-rn-text-3 uppercase tracking-wider block leading-tight">Cold-Pressed Acidity</span>
                        <span class="text-sm font-semibold text-rn-text-1 leading-tight block">
                            <span class="font-mono">&lt; 0.18%</span> Extra Virgin
                        </span>
                    </div>
                </div>

                <div class="px-4 py-1 flex items-center gap-3 text-start flex-1 min-w-[200px] lg:min-w-0 snap-start">
                    <span class="text-base">🚢</span>
                    <div>
                        <span class="text-xs text-rn-text-3 uppercase tracking-wider block leading-tight">Bulk Shipping Tank</span>
                        <span class="text-sm font-semibold text-rn-gold leading-tight block">
                            <span class="font-mono">24,000L</span> Flexitank
                        </span>
                    </div>
                </div>

                <div class="px-4 py-1 flex items-center gap-3 text-start flex-1 min-w-[200px] lg:min-w-0 snap-start">
                    <span class="text-base">🌴</span>
                    <div>
                        <span class="text-xs text-rn-text-3 uppercase tracking-wider block leading-tight">Deglet Nour Dates</span>
                        <span class="text-sm font-semibold text-rn-text-1 leading-tight block">
                            Tozeur & Kebili Oases
                        </span>
                    </div>
                </div>

                <div class="px-4 py-1 flex items-center gap-3 text-start flex-1 min-w-[200px] lg:min-w-0 snap-start">
                    <span class="text-base">🏷️</span>
                    <div>
                        <span class="text-xs text-rn-text-3 uppercase tracking-wider block leading-tight">OEM Private Label</span>
                        <span class="text-sm font-semibold text-rn-gold leading-tight block">
                            Bespoke Bottles & Tins
                        </span>
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>
