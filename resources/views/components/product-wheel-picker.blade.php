@props(['product'])

@php
    $packagings = $product->getAvailablePackagings();
    $initialPackaging = $packagings[0]['id'] ?? 'bollar';
    $allowedSizes = $packagings[0]['default_sizes'] ?? ($product->allowed_sizes ?? ['1L', '5L']);
    $nutritionalSpecs = $product->nutritional_specs ?? [];
    $logisticsSpecs = $product->logistics_specs ?? [];
@endphp

<div x-data="{
    packagingList: {{ json_encode($packagings) }},
    selectedPackaging: '{{ $initialPackaging }}',
    sizes: {{ json_encode($allowedSizes) }},
    selectedIndex: 0,
    quantity: 1000,
    unit: 'units',
    isPrivateLabel: false,
    toastVisible: false,
    toastMessage: '',

    init() {
        if (this.sizes.length > 1) {
            this.selectedIndex = 0;
        }
        if (this.selectedPackaging.includes('bulk') || this.selectedPackaging.includes('flexitank')) {
            this.quantity = 1;
            this.unit = 'flexitanks';
        } else if (this.selectedPackaging.includes('carton') || this.selectedPackaging.includes('bucket')) {
            this.quantity = 100;
            this.unit = 'cartons';
        }
    },

    get selectedSize() {
        return this.sizes[this.selectedIndex] || this.sizes[0] || '';
    },

    get currentPackagingObj() {
        return this.packagingList.find(p => p.id === this.selectedPackaging) || this.packagingList[0] || {};
    },

    get activeProductImage() {
        let defaultImage = '{{ $product->image_url }}';
        let pkg = (this.selectedPackaging || '').toLowerCase();
        let cat = '{{ strtolower($product->category->slug ?? "") }}';
        let prod = '{{ strtolower($product->slug ?? "") }}';

        @if(!Str::startsWith($product->featured_image, 'storage/'))
            if (cat.includes('olive') || cat.includes('oil') || prod.includes('oil')) {
                if (pkg.includes('bottle') || pkg.includes('marasca') || pkg.includes('glass') || pkg.includes('dorica')) {
                    return '{{ asset("images/products/marasca_studio_clean.jpg") }}';
                }
                if (pkg.includes('bulk') || pkg.includes('flexitank') || pkg.includes('ibc')) {
                    return '{{ asset("images/products/bulk_drum_flexitank.jpg") }}';
                }
                if (pkg.includes('tin')) {
                    return '{{ asset("images/products/olive_oil_tin.jpg") }}';
                }
            }
            if (cat.includes('date') || prod.includes('date') || prod.includes('deglet')) {
                return '{{ asset("images/products/deglet_nour_box.jpg") }}';
            }
            if (cat.includes('mer') || cat.includes('thon') || prod.includes('thon') || prod.includes('tuna') || prod.includes('sardine')) {
                return '{{ asset("images/products/canned_tuna_can.jpg") }}';
            }
            if (cat.includes('tomate') || prod.includes('tomate')) {
                return '{{ asset("images/products/sundried_tomatoes_jar.jpg") }}';
            }
        @endif

        return defaultImage;
    },

    selectPackaging(id) {
        this.selectedPackaging = id;
        let pkgObj = this.packagingList.find(p => p.id === id);
        if (pkgObj && pkgObj.default_sizes && pkgObj.default_sizes.length > 0) {
            this.sizes = pkgObj.default_sizes;
        } else {
            this.sizes = {{ json_encode($allowedSizes) }};
        }
        this.selectedIndex = 0;

        if (id.includes('bulk') || id.includes('flexitank')) {
            this.quantity = 1;
            this.unit = 'flexitanks';
        } else if (id.includes('carton') || id.includes('bucket')) {
            this.quantity = 100;
            this.unit = 'cartons';
        } else {
            this.quantity = 1000;
            this.unit = 'units';
        }
        this.vibrate();
    },

    selectSize(index) {
        if (index >= 0 && index < this.sizes.length) {
            this.selectedIndex = index;
            this.vibrate();
        }
    },

    nextSize() {
        if (this.selectedIndex < this.sizes.length - 1) {
            this.selectedIndex++;
        } else {
            this.selectedIndex = 0;
        }
        this.vibrate();
    },

    prevSize() {
        if (this.selectedIndex > 0) {
            this.selectedIndex--;
        } else {
            this.selectedIndex = this.sizes.length - 1;
        }
        this.vibrate();
    },

    vibrate() {
        if (typeof window !== 'undefined' && window.navigator && window.navigator.vibrate) {
            window.navigator.vibrate(8);
        }
    },

    addToCart() {
        $dispatch('add-to-cart', {
            productId: {{ $product->id }},
            packagingType: this.selectedPackaging,
            size: this.selectedSize,
            quantity: parseFloat(this.quantity) || 1,
            unit: this.unit,
            isPrivateLabel: this.isPrivateLabel
        });

        this.toastMessage = '{{ LaravelLocalization::getCurrentLocale() === "ar" ? "تمت إضافة التكوين والمواصفات إلى سلة التسعيرة بنجاح!" : (LaravelLocalization::getCurrentLocale() === "fr" ? "Produit et conditionnement ajoutés au dossier de cotation !" : "Configuration successfully added to RFQ dossier!") }}';
        this.toastVisible = true;
        setTimeout(() => { this.toastVisible = false; }, 3500);
    }
}" 
class="rn-glass rounded-3xl p-4 sm:p-6 lg:p-6 xl:p-7 border border-rn-line/12 transition-all duration-300 shadow-2xl relative overflow-hidden">

    <!-- Notification Toast de Confirmation -->
    <div x-show="toastVisible" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
         class="absolute top-4 inset-x-4 sm:inset-x-auto sm:end-6 z-50 rn-glass-strong border border-rn-gold/60 rounded-2xl p-4 shadow-2xl flex items-center gap-3 backdrop-blur-2xl"
         style="display: none;"
         role="status"
         aria-live="polite">
        <span class="flex h-3 w-3 relative">
            <span class="relative inline-flex rounded-full h-3 w-3 bg-rn-gold"></span>
        </span>
        <span class="text-xs font-bold text-rn-text-1 tracking-wide" x-text="toastMessage"></span>
    </div>

    <!-- Layout Dual-Pane (Desktop 12 Columns, Cols 1-7 Stage & Cols 8-12 Info) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-7 items-stretch relative z-10">
        
        <!-- Panneau 1 : Vitrine Photographique Dominante (Cols 1-7, 58% width) -->
        <div class="lg:col-span-7 flex flex-col justify-between h-full space-y-2.5">
            
            <!-- Vitrine Haute Définition sur Piédestal Radial Sombre (Plein format / Fit Height) -->
            <div class="relative w-full flex-1 h-full min-h-[380px] lg:min-h-[460px] xl:min-h-[490px] rounded-3xl overflow-hidden border border-rn-line/12 shadow-2xl transition-all duration-500 bg-rn-bg-0 flex items-center justify-center group">
                
                <!-- Piédestal d'éclairage radial vert-olive sombre (§14) -->
                <div class="absolute inset-0 pointer-events-none" 
                     style="background: radial-gradient(65% 55% at 50% 55%, rgb(var(--rn-olive-deep) / 0.45) 0%, transparent 80%);"></div>

                <!-- Image Plein Cadre Haute Définition -->
                <img :src="activeProductImage" 
                     :alt="'{{ $product->localized_title }}'" 
                     width="640"
                     height="480"
                     loading="lazy"
                     decoding="async"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 select-none">

                <!-- Vignette Dégradée Subtile de Protection des Textes -->
                <div class="absolute inset-0 bg-gradient-to-t from-rn-bg-0/85 via-transparent to-rn-bg-0/40 pointer-events-none"></div>

                <!-- Badge Flottant Supérieur : Packaging Actif & Terroir -->
                <div class="absolute top-4 inset-x-4 flex items-center justify-between pointer-events-none z-10">
                    <span class="px-3.5 py-1.5 rounded-xl bg-rn-bg-0/85 backdrop-blur-md border border-rn-line/12 text-rn-gold text-xs font-mono font-bold flex items-center gap-2 shadow-lg">
                        <span class="w-2 h-2 rounded-full bg-rn-gold animate-pulse"></span>
                        <span x-text="currentPackagingObj?.badge || 'Export Grade'"></span>
                    </span>
                    <span class="px-3 py-1 rounded-xl bg-rn-bg-0/80 backdrop-blur-md text-rn-text-2 text-xs font-mono font-semibold border border-rn-line/10">
                        🇹🇳 100% Terroir
                    </span>
                </div>

                <!-- Badge Flottant Inférieur : Forme & Contenance Actuelle -->
                <div class="absolute bottom-4 inset-x-4 z-10 flex items-center justify-between gap-3 p-3 rounded-2xl rn-glass-dark border border-rn-line/12 shadow-2xl">
                    <div class="flex items-center gap-2.5 truncate">
                        <span class="w-8 h-8 rounded-xl bg-rn-bg-0 border border-rn-line/12 flex items-center justify-center text-base shrink-0 shadow-sm"
                              x-text="currentPackagingObj?.icon_emoji || '📦'">
                        </span>
                        <div class="truncate text-start">
                            <span class="block text-xs font-bold text-rn-text-1 uppercase tracking-wider font-mono truncate" 
                                  x-text="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'currentPackagingObj.name_ar' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'currentPackagingObj.name_fr' : 'currentPackagingObj.name_en') }}"></span>
                            <span class="block text-[11px] text-rn-text-3 font-medium">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'المعاينة المباشرة للمنتج' : 'Live Interactive Visual' }}</span>
                        </div>
                    </div>
                    <div class="px-3.5 py-1.5 rounded-xl bg-rn-gold text-rn-bg-0 font-bold text-xs sm:text-sm font-mono shadow-md shrink-0" x-text="selectedSize">
                    </div>
                </div>
            </div>

            <!-- Certifications Associées sous la photo -->
            @if($product->certificates && count($product->certificates) > 0)
                <div class="flex flex-wrap items-center gap-2 pt-1 shrink-0">
                    @foreach($product->certificates as $cert)
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl rn-glass-light border border-rn-line/10 text-xs text-rn-text-2 font-medium" title="{{ $cert->issuer }}">
                            <span class="text-rn-gold font-bold">✓</span>
                            <span>{{ $cert->localized_name }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        <!-- Panneau 2 : Spécifications, Sélecteurs & CTA (Cols 8-12) -->
        <div class="lg:col-span-5 space-y-3 sm:space-y-3.5 text-start flex flex-col justify-between">
            
            <!-- Titre et Origine -->
            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-mono font-bold uppercase tracking-wider text-rn-olive">
                        {{ $product->category->localized_name ?? 'Agro-Export' }}
                    </span>
                    <span class="text-xs text-rn-text-3 font-mono">
                        COI Verified
                    </span>
                </div>
                <h3 class="font-display text-xl sm:text-2xl font-bold text-rn-text-1 tracking-tight">
                    {{ $product->localized_title }}
                </h3>
                @if($product->localized_subtitle)
                    <p class="text-xs sm:text-sm text-rn-gold font-semibold tracking-wide">
                        {{ $product->localized_subtitle }} • 🇹🇳 100% Tunisian Terroir
                    </p>
                @endif
                <p class="text-xs text-rn-text-2 leading-relaxed font-normal pt-0.5 line-clamp-2">
                    {{ $product->localized_description }}
                </p>
            </div>

            <!-- 1. SÉLECTEUR DE FORMAT DE CONDITIONNEMENT (PACKAGING TYPE - SEGMENTED BAR §15) -->
            <div class="space-y-1.5 pt-0.5">
                <div class="flex items-center justify-between">
                    <label class="text-xs sm:text-sm font-bold text-rn-text-1 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rn-gold"></span>
                        <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? '1. نوع وشكل التعليب والتغليف :' : '1. Packaging Format :' }}</span>
                    </label>
                    <span class="text-xs font-mono font-bold text-rn-gold" x-text="currentPackagingObj?.badge || ''"></span>
                </div>
                
                <div role="radiogroup" aria-label="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'نوع التعليب' : 'Packaging Format' }}" class="flex items-center gap-2 overflow-x-auto no-scrollbar py-0.5">
                    <template x-for="pkg in packagingList" :key="pkg.id">
                        <button type="button" 
                                role="radio"
                                :aria-checked="selectedPackaging === pkg.id ? 'true' : 'false'"
                                @click="selectPackaging(pkg.id)" 
                                class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-3.5 sm:py-2 rounded-xl text-start transition-all duration-200 cursor-pointer shrink-0"
                                :class="selectedPackaging === pkg.id 
                                    ? 'rn-glass border border-rn-gold shadow-md shadow-rn-gold/15 ring-2 ring-rn-gold/30 text-rn-text-1' 
                                    : 'rn-glass-light border border-rn-line/10 text-rn-text-2 hover:border-rn-line/20 hover:text-rn-text-1'">
                            <span class="text-base" x-text="pkg.icon_emoji || '📦'"></span>
                            <span class="text-xs font-bold whitespace-nowrap"
                                  x-text="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'pkg.name_ar' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'pkg.name_fr' : 'pkg.name_en') }}"></span>
                        </button>
                    </template>
                </div>
                <div class="text-[11px] text-rn-text-3 font-medium min-h-[1rem] truncate"
                     x-text="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'currentPackagingObj.desc_ar' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'currentPackagingObj.desc_fr' : 'currentPackagingObj.desc_en') }}"></div>
            </div>

            <!-- 2. SÉLECTEUR DE CAPACITÉ / TAILLE (SIZE RAIL §15) -->
            <div class="space-y-1.5 pt-0.5">
                <div class="flex items-center justify-between">
                    <label class="text-xs sm:text-sm font-bold text-rn-text-1 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rn-gold"></span>
                        <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? '2. سعة وحجم العبوة :' : '2. Capacity & Container Size :' }}</span>
                    </label>
                    <span class="text-xs font-mono font-bold text-rn-gold px-2.5 py-0.5 rounded-lg rn-glass-light border border-rn-line/10" x-text="selectedSize"></span>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Prev Size Button -->
                    <button type="button" 
                            @click="prevSize()" 
                            class="w-9 h-9 rounded-xl rn-glass-light hover:border-rn-line/25 flex items-center justify-center text-rn-text-1 transition-all active:scale-95 cursor-pointer shrink-0"
                            aria-label="Previous Size">
                        <svg class="w-4 h-4 {{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>

                    <!-- Horizontal Size Rail with Keyboard Support -->
                    <div role="radiogroup" 
                         aria-label="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'سعة العبوة' : 'Container Size' }}" 
                         class="flex-1 flex items-center gap-1.5 sm:gap-2 overflow-x-auto no-scrollbar py-0.5"
                         @keydown.arrow-right.prevent="{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'prevSize()' : 'nextSize()' }}"
                         @keydown.arrow-left.prevent="{{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'nextSize()' : 'prevSize()' }}"
                         @keydown.home.prevent="selectSize(0)"
                         @keydown.end.prevent="selectSize(sizes.length - 1)">
                        <template x-for="(s, idx) in sizes" :key="idx">
                            <button type="button" 
                                    role="radio"
                                    :aria-checked="selectedIndex === idx ? 'true' : 'false'"
                                    @click="selectSize(idx)" 
                                    class="px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full text-xs sm:text-sm font-bold font-mono transition-all duration-200 cursor-pointer shrink-0"
                                    :class="selectedIndex === idx 
                                        ? 'bg-rn-gold text-rn-bg-0 shadow-md shadow-rn-gold/25 scale-[1.02] ring-2 ring-rn-gold/30' 
                                        : 'rn-glass-light text-rn-text-2 hover:text-rn-text-1 hover:border-rn-line/25'">
                                <span x-text="s"></span>
                            </button>
                        </template>
                    </div>

                    <!-- Next Size Button -->
                    <button type="button" 
                            @click="nextSize()" 
                            class="w-9 h-9 rounded-xl rn-glass-light hover:border-rn-line/25 flex items-center justify-center text-rn-text-1 transition-all active:scale-95 cursor-pointer shrink-0"
                            aria-label="Next Size">
                        <svg class="w-4 h-4 {{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- 3. SPÉCIFICATIONS TECHNIQUES DANS UN DISCLOSURE DÉPLIABLE (<details>) (§14) -->
            @if(!empty($nutritionalSpecs))
                <details class="rn-glass-light rounded-xl p-2.5 sm:p-3 border border-rn-line/10 text-xs group">
                    <summary class="cursor-pointer font-bold text-xs uppercase tracking-wider text-rn-gold flex items-center justify-between select-none list-none">
                        <span class="flex items-center gap-1.5">
                            <span>🔬</span>
                            <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'التحاليل والخصائص المخبرية (COI Approved)' : 'Laboratory & Chemical Specifications' }}</span>
                        </span>
                        <span class="text-rn-text-3 font-mono text-[11px] group-open:rotate-180 transition-transform duration-200">▼</span>
                    </summary>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5 pt-2 border-t border-rn-line/8 mt-2">
                        @foreach($nutritionalSpecs as $specKey => $specValue)
                            <div class="p-2 rounded-lg bg-rn-bg-0/60 border border-rn-line/8">
                                <span class="block text-[10px] text-rn-text-3 uppercase tracking-wider font-semibold truncate">{{ str_replace('_', ' ', $specKey) }}</span>
                                <span class="block text-xs font-bold text-rn-text-1 mt-0.5 font-mono">{{ $specValue }}</span>
                            </div>
                        @endforeach
                    </div>
                </details>
            @endif

            <!-- 4. CALCULATEUR LOGISTIQUE ET QUANTITÉ -->
            <div class="rounded-xl rn-glass-light border border-rn-line/10 p-2.5 sm:p-3 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div>
                    <span class="text-rn-text-3 block font-medium">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'تقدير الحمولة والشحن الدولي :' : 'Freight & Logistics Estimate :' }}</span>
                    <span class="text-rn-gold font-bold text-xs mt-0.5 block font-mono" x-text="currentPackagingObj?.logistics_hint || '1 Euro Pallet ≈ 720 units • 1x 20ft FCL = 10 Pallets'"></span>
                </div>
                
                <div class="flex items-center gap-2">
                    <label class="text-rn-text-1 font-bold">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الكمية :' : 'Qty :' }}</label>
                    <input type="number" 
                           x-model="quantity" 
                           min="1" 
                           class="w-20 px-2 py-1 rounded-lg bg-rn-bg-0 border border-rn-line/15 text-center text-sm font-bold text-rn-text-1 focus:border-rn-gold focus:outline-none focus:ring-2 focus:ring-rn-gold/30">
                    <span class="text-xs font-mono text-rn-text-3 uppercase" x-text="unit"></span>
                </div>
            </div>

            <!-- Option Marque Blanche (Private Label) -->
            <div class="flex items-center gap-2.5">
                <label class="relative flex items-center gap-2.5 cursor-pointer select-none py-0.5 min-h-[36px]">
                    <input type="checkbox" x-model="isPrivateLabel" class="w-4 h-4 rounded-md border-rn-line/20 bg-rn-bg-0 text-rn-gold focus:ring-rn-gold accent-rn-gold">
                    <span class="text-xs text-rn-text-2 font-medium">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'طلب تعبئة وتغليف بالعلامة الخاصة للمشتري (Private Label / OEM)' : 'Request Custom Buyer Branding & Private Label (OEM)' }}
                    </span>
                </label>
            </div>

            <!-- Bouton d'Action Principal (Add to RFQ - Gold Gradient, Sticky on Mobile §23) -->
            <div class="pt-1 sticky bottom-4 z-20 sm:static sm:bottom-auto">
                <button type="button" 
                        @click="addToCart()" 
                        class="w-full min-h-[46px] py-2.5 px-6 rounded-full bg-gradient-to-r from-rn-gold-soft to-rn-gold hover:from-rn-gold hover:to-rn-gold-soft text-rn-bg-0 font-bold text-sm shadow-xl shadow-rn-gold/25 transition-all hover:scale-[1.01] active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer backdrop-blur-md">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'أضف هذا التكوين إلى سلة عروض الأسعار (RFQ)' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Ajouter cette configuration au dossier de cotation' : 'Add Configuration to Quotation Dossier') }}</span>
                </button>
            </div>

        </div>

    </div>

</div>
