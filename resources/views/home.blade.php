<x-layouts.app :title="LaravelLocalization::getCurrentLocale() === 'ar' ? 'رايانوفا — تصدير زيت الزيتون والمنتجات الغذائية التونسية' : 'Rayanova T.D — Premium Tunisian Agro-Food & Olive Oil Export'">

    <!-- 1. Cinema Hero Rayanova T.D (§13) -->
    @if($companyHero?->is_active ?? true)
        <x-company-intro-hero :companyHero="$companyHero" :slides="$slides" :products="$products" />
    @endif

    <!-- 2. Products Showroom Scene (§14 & §19 - moved up directly after hero) -->
    <section id="products" class="rn-scene rn-scene--showroom" data-scene style="--scene-z: 20;">
        <div class="rn-scene__inner space-y-1.5 lg:space-y-2" x-data="{ activeId: {{ $products[0]->id ?? 1 }} }" @showroom-select.window="activeId = $event.detail.id">
            
            <!-- Section Header (Ultra Compact for Single-Screen Viewport Fit) -->
            <div class="text-center max-w-3xl mx-auto space-y-0.5 reveal-up">
                <div class="flex items-center justify-center gap-2 flex-wrap">
                    <span class="px-2.5 py-0.5 rounded-full rn-glass-light border border-rn-line/12 text-[10px] font-bold text-rn-gold uppercase tracking-widest font-mono shadow-sm inline-block">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'كتالوج التصدير الدولي 2026' : 'Export Catalog & Interactive Packaging' }}
                    </span>
                    <a href="{{ route('catalog.download_pdf', ['locale' => LaravelLocalization::getCurrentLocale(), 'lang' => LaravelLocalization::getCurrentLocale()]) }}"
                       target="_blank"
                       title="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'تحميل كتالوج المنتجات الرسمي بصيغة PDF' : 'Download Official B2B Export Catalog PDF' }}"
                       class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 hover:bg-amber-500/25 text-amber-400 border border-amber-500/40 transition-all hover:scale-105 shadow-sm cursor-pointer">
                        <span>📄</span>
                        <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'تحميل الكتالوج (PDF)' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Télécharger Catalogue (PDF)' : 'Download B2B Catalog (PDF)') }}</span>
                    </a>
                </div>
                <h2 class="font-display text-base sm:text-lg lg:text-xl font-bold text-rn-text-1 tracking-tight leading-tight">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'حدد التكوين وسعة التعليب المناسبة لصفقتك' : 'Configure Sizes & Packaging for Your Order' }}
                </h2>
                <p class="text-[11px] text-rn-text-2 font-normal leading-tight max-w-xl mx-auto line-clamp-1 hidden sm:block">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                        ? 'اختر الأحجام والعبوات المناسبة لصفقتك مع تقدير فوري للوجستيات الحاوية والشهادات المخبرية.' 
                        : (LaravelLocalization::getCurrentLocale() === 'fr'
                            ? 'Personnalisez les capacités de conteneurs, formats de conditionnement et volumes avec estimation logistique instantanée.'
                            : 'Customize container capacities, packaging styles, and volumes with instant container logistics estimates.') }}
                </p>
            </div>

            <!-- Product Rail (§14, tablist) -->
            <div class="w-full overflow-x-auto no-scrollbar py-0.5 reveal-up" role="tablist" aria-label="{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'قائمة المنتجات التصديرية' : 'Export Products Rail' }}">
                <div class="flex items-center gap-1.5 sm:gap-2 min-w-max mx-auto justify-center px-1">
                    @foreach($products as $prod)
                        <button type="button" 
                                role="tab"
                                id="tab-product-{{ $prod->id }}"
                                aria-controls="product-{{ $prod->id }}"
                                :aria-selected="activeId === {{ $prod->id }} ? 'true' : 'false'"
                                @click="activeId = {{ $prod->id }}"
                                class="inline-flex items-center gap-2 px-2.5 py-1 rounded-xl transition-all duration-200 cursor-pointer"
                                :class="activeId === {{ $prod->id }} 
                                    ? 'rn-glass border border-rn-gold shadow-md shadow-rn-gold/15 ring-2 ring-rn-gold/30 text-rn-text-1 scale-[1.01]' 
                                    : 'rn-glass-light border border-rn-line/10 text-rn-text-2 hover:border-rn-line/20 hover:text-rn-text-1'">
                            <span class="w-6 h-6 rounded-lg overflow-hidden bg-rn-bg-0 shrink-0 border border-rn-line/12">
                                <img src="{{ $prod->image_url }}" alt="" width="24" height="24" loading="lazy" decoding="async" class="w-full h-full object-cover">
                            </span>
                            <div class="text-start">
                                <span class="block text-[9px] font-mono uppercase tracking-wider text-rn-olive leading-none">
                                    {{ $prod->category->localized_name ?? 'Export' }}
                                </span>
                                <span class="block text-xs font-bold leading-tight truncate max-w-[110px] sm:max-w-[140px]">
                                    {{ $prod->localized_title }}
                                </span>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Grille des Produits avec Product Wheel Picker (Showroom Stage) -->
            <div class="relative w-full">
                @foreach($products as $product)
                    <div id="product-{{ $product->id }}" 
                         role="tabpanel"
                         aria-labelledby="tab-product-{{ $product->id }}"
                         class="scroll-mt-28 transition-all duration-300"
                         x-show="activeId === {{ $product->id }}"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 translate-y-3 scale-[0.98]"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                        <x-product-wheel-picker :product="$product" />
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- 3. Export Quality & Chemical Laboratory Analysis Scene (§14 & §19, id="export") -->
    <section id="export" class="rn-scene rn-scene--bento" data-scene style="--scene-z: 30;">
        <div class="rn-scene__inner space-y-4 lg:space-y-6">
            
            <!-- Bento Section Header -->
            <div class="text-center max-w-3xl mx-auto space-y-2 reveal-up">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full rn-glass-light border border-rn-line/12 text-xs font-mono font-bold text-rn-gold shadow-inner">
                    <span class="w-2 h-2 rounded-full bg-rn-gold"></span>
                    <span class="uppercase tracking-widest">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'المختبر والمعايير الكيميائية التصديرية' : 'Chemical Purity & Lab Analysis Terminal' }}</span>
                </div>
                <h2 class="font-display text-xl sm:text-2xl lg:text-3xl font-bold text-rn-text-1 tracking-tight leading-tight">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'دقة كيميائية استثنائية واعتماد دولي صارم' : 'Precision Chemical Quality & Accredited Lab Analysis' }}
                </h2>
                <p class="text-xs sm:text-sm text-rn-text-2 leading-relaxed font-normal">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                        ? 'كل دفعة زيت زيتون تصديرية تخضع لتحاليل كروماتوغرافية وحسية معتمدة دولياً لضمان أدنى نسبة حموضة وأعلى تركيز للبوليفينول الطبيعي.' 
                        : 'Accredited chromatographic and organoleptic testing ensuring extra-low acidity, high polyphenol density, and guaranteed compliance with International Olive Council (COI) standards.' }}
                </p>
            </div>

            <!-- Lab Analysis Grid (Col-Span-7 Terminal + Col-Span-5 Panel Console) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6 items-stretch">
                
                <!-- Tile 1: Terminal Laboratoire & Pureté Chimique COI (Col-Span-7) -->
                <div class="lg:col-span-7 rounded-3xl rn-glass--flat border border-rn-line/12 hover:border-rn-gold/50 p-5 sm:p-6 lg:p-7 flex flex-col justify-between transition-all duration-300 shadow-xl relative overflow-hidden group reveal-left">
                    
                    <div class="space-y-5">
                        <!-- Top Bar with Index & Status -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="font-mono text-xs font-black text-rn-gold bg-rn-bg-0/90 px-3 py-1.5 rounded-lg border border-rn-line/12">01</span>
                                <span class="text-xs font-mono font-bold text-rn-text-2 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-rn-gold"></span>
                                    COI Laboratory Certified
                                </span>
                            </div>
                            <span class="text-[11px] font-mono text-rn-text-3 bg-rn-bg-0/80 px-2.5 py-1 rounded-md border border-rn-line/8">
                                Batch Testing Protocol
                            </span>
                        </div>

                        <!-- Header & Description -->
                        <div>
                            <h3 class="text-xl sm:text-2xl font-display font-bold text-rn-text-1 tracking-tight">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'مختبر الجودة ونقاء المؤشرات الكيميائية' : 'Chemical Purity & Lab Analysis Terminal' }}
                            </h3>
                            <p class="text-xs sm:text-sm text-rn-text-2 mt-2 leading-relaxed">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                                    ? 'كل دفعة زيت زيتون تصديرية تخضع لتحاليل كروماتوغرافية وحسية معتمدة دولياً لضمان أدنى نسبة حموضة وأعلى تركيز للبوليفينول الطبيعي.' 
                                    : 'Every export batch is accompanied by an accredited chemical analysis certificate verifying extra-low acidity, peroxide index, and elevated natural polyphenol density.' }}
                            </p>
                        </div>

                        <!-- 4 Live Chemistry Meters -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5 pt-1">
                            <div class="p-3 rounded-2xl bg-rn-bg-0/60 border border-rn-line/5 space-y-1 text-center group-hover:border-rn-line/15 transition-colors">
                                <span class="text-[10px] font-mono font-semibold uppercase tracking-wider text-rn-text-3 block">Acidity (FFA)</span>
                                <span class="text-lg sm:text-xl font-mono font-extrabold text-rn-gold block">&lt; 0.18%</span>
                                <span class="text-[9px] text-rn-text-3 font-mono">COI limit: 0.80%</span>
                            </div>
                            <div class="p-3 rounded-2xl bg-rn-bg-0/60 border border-rn-line/5 space-y-1 text-center group-hover:border-rn-line/15 transition-colors">
                                <span class="text-[10px] font-mono font-semibold uppercase tracking-wider text-rn-text-3 block">Polyphenols</span>
                                <span class="text-lg sm:text-xl font-mono font-extrabold text-rn-text-1 block">520+ mg/kg</span>
                                <span class="text-[9px] text-rn-text-3 font-mono">Natural Anti-ox</span>
                            </div>
                            <div class="p-3 rounded-2xl bg-rn-bg-0/60 border border-rn-line/5 space-y-1 text-center group-hover:border-rn-line/15 transition-colors">
                                <span class="text-[10px] font-mono font-semibold uppercase tracking-wider text-rn-text-3 block">Peroxide Val.</span>
                                <span class="text-lg sm:text-xl font-mono font-extrabold text-rn-text-1 block">&lt; 6.2 meq</span>
                                <span class="text-[9px] text-rn-text-3 font-mono">Max limit: 20</span>
                            </div>
                            <div class="p-3 rounded-2xl bg-rn-bg-0/60 border border-rn-line/5 space-y-1 text-center group-hover:border-rn-line/15 transition-colors">
                                <span class="text-[10px] font-mono font-semibold uppercase tracking-wider text-rn-text-3 block">UV Abs. K270</span>
                                <span class="text-lg sm:text-xl font-mono font-extrabold text-rn-text-1 block">&lt; 0.14</span>
                                <span class="text-[9px] text-rn-text-3 font-mono">Purity Indicator</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footprint specs -->
                    <div class="pt-5 mt-5 border-t border-rn-line/10 flex flex-wrap items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-2 font-mono text-rn-text-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'شهادة تحليل كيميائي وحسي مع كل شحنة' : 'CoA (Certificate of Analysis) Included with every B/L' }}</span>
                        </div>
                        <span class="font-mono text-rn-gold bg-rn-bg-0/80 px-2.5 py-1 rounded-md border border-rn-line/10">ISO 17025 Accredited</span>
                    </div>
                </div>

                <!-- Tile 2: Organoleptic & Quality Assurance Console (Col-Span-5) -->
                <div class="lg:col-span-5 rounded-3xl rn-glass--flat border border-rn-line/12 hover:border-rn-gold/50 p-5 sm:p-6 lg:p-7 flex flex-col justify-between transition-all duration-300 shadow-xl relative overflow-hidden group reveal-right">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="font-mono text-xs font-black text-rn-gold bg-rn-bg-0/90 px-3 py-1.5 rounded-lg border border-rn-line/12">02</span>
                                <span class="text-xs font-mono font-bold text-emerald-400 flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                    Sensory & Organoleptic Panel
                                </span>
                            </div>
                            <span class="text-[11px] font-mono text-rn-text-3 bg-rn-bg-0/80 px-2.5 py-1 rounded-md border border-rn-line/8">
                                COI Tasting Panel
                            </span>
                        </div>

                        <div>
                            <h3 class="text-xl sm:text-2xl font-display font-bold text-rn-text-1 tracking-tight">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'لجنة التذوق الحسي والضمان النوعي' : 'Organoleptic Verification & Quality Guarantee' }}
                            </h3>
                            <p class="text-xs sm:text-sm text-rn-text-2 mt-2 leading-relaxed">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                                    ? 'تقييم حسي صارم من قبل خبراء معتمدين لتأكيد خلو الزيت من أي عيوب حسية وثبات نكهته الفاكهية الطازجة.' 
                                    : 'Certified panel tasting assessment verifying absolute zero sensory defects, intense fruitiness, and balanced pungency across every single extraction.' }}
                            </p>
                        </div>

                        <div class="space-y-2.5 font-mono text-xs">
                            <div class="p-3 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 flex items-center justify-between">
                                <span class="text-rn-text-2">Sensory Tasting Score</span>
                                <span class="text-rn-gold font-bold">&gt; 8.0 / 10 (Extra Virgin)</span>
                            </div>
                            <div class="p-3 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 flex items-center justify-between">
                                <span class="text-rn-text-2">Defect Median (Md)</span>
                                <span class="text-emerald-400 font-bold">0.0 (Zero Defects)</span>
                            </div>
                            <div class="p-3 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 flex items-center justify-between">
                                <span class="text-rn-text-2">Gas Chromatography</span>
                                <span class="text-rn-text-1 font-bold">100% Purity Verified</span>
                            </div>
                            <div class="p-3 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 flex items-center justify-between">
                                <span class="text-rn-text-2">Cold Extraction Index</span>
                                <span class="text-rn-text-1 font-bold">&lt; 27°C Mechanical Only</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5 mt-5 border-t border-rn-line/10 flex items-center justify-between text-xs font-mono text-rn-text-3">
                        <span>Harvest Batch Traceability</span>
                        <span class="text-rn-gold font-bold">Guaranteed Purity</span>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- 4. Global Logistics & Bulk Freight Scene (§14 & §19, id="logistics") -->
    <section id="logistics" class="rn-scene rn-scene--logistics" data-scene style="--scene-z: 40;">
        <div class="rn-scene__inner space-y-4 lg:space-y-6">
            
            <!-- Logistics Section Header -->
            <div class="text-center max-w-3xl mx-auto space-y-2 reveal-up">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full rn-glass-light border border-rn-line/12 text-xs font-mono font-bold text-rn-cyan shadow-inner">
                    <span class="w-2 h-2 rounded-full bg-rn-cyan"></span>
                    <span class="uppercase tracking-widest">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'اللوجستيات والشحن البحري السائب' : 'Global Logistics & Turnkey Export' }}</span>
                </div>
                <h2 class="font-display text-xl sm:text-2xl lg:text-3xl font-bold text-rn-text-1 tracking-tight leading-tight">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'شحن بحري متكامل وحلول تعبئة مرنة' : 'Multimodal Freight & Bulk Export Infrastructure' }}
                </h2>
                <p class="text-xs sm:text-sm text-rn-text-2 leading-relaxed font-normal">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                        ? 'إمكانيات صناعية ضخمة للشحن الصب عبر خزانات الفليكسيتانك أو الحاويات المعبأة وخطوط التعليب بالعلامة الخاصة للمستوردين.' 
                        : 'Industrial bulk freight via food-grade Flexitanks with nitrogen blanketing, full container loads (FCL), turnkey OEM bottling, and fast Mediterranean trade corridors.' }}
                </p>
            </div>

            <!-- 3 Columns Grid (Col 4 / Col 4 / Col 4) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6 items-stretch reveal-stagger">
                
                <!-- Tile 1: Logistique Maritime & Capacités Vrac Flexitank -->
                <div class="rounded-3xl rn-glass--flat border border-rn-line/12 hover:border-rn-gold/50 p-5 sm:p-6 lg:p-6 flex flex-col justify-between transition-all duration-300 shadow-xl relative overflow-hidden group reveal-child">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="font-mono text-xs font-black text-rn-gold bg-rn-bg-0/90 px-3 py-1.5 rounded-lg border border-rn-line/12">01</span>
                                <span class="text-xs font-mono font-bold text-rn-gold flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-rn-gold"></span>
                                    Bulk & FCL Capacities
                                </span>
                            </div>
                            <span class="text-[11px] font-mono text-rn-text-3 bg-rn-bg-0/80 px-2.5 py-1 rounded-md border border-rn-line/8">
                                Port of Radès & Sfax
                            </span>
                        </div>

                        <div>
                            <h3 class="text-lg sm:text-xl font-display font-bold text-rn-text-1 tracking-tight">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الشحن البحري السائب وحاويات FCL' : 'Maritime Bulk & Freight Logistics' }}
                            </h3>
                            <p class="text-xs sm:text-sm text-rn-text-2 mt-2 leading-relaxed">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                                    ? 'إمكانيات ضخمة للشحن الصب عبر خزانات الفليكسيتانك الغذائية مع حقن النيتروجين لمنع الأكسدة، أو الحاويات الكاملة المعبأة على منصات خشبية.' 
                                    : 'Industrial bulk freight via food-grade Flexitanks with nitrogen blanketing, or full container loads (FCL) on fumigated Euro pallets.' }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <div class="p-2.5 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 flex items-center justify-between">
                                <span class="text-xs font-mono text-rn-text-2">Flexitank 20ft Box</span>
                                <span class="text-xs font-mono font-bold text-rn-gold">24,000L / 21.6 MT</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 flex items-center justify-between">
                                <span class="text-xs font-mono text-rn-text-2">20ft FCL Bottled</span>
                                <span class="text-xs font-mono font-bold text-rn-text-1">10 Pallets (11kL)</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 flex items-center justify-between">
                                <span class="text-xs font-mono text-rn-text-2">40ft FCL High Cube</span>
                                <span class="text-xs font-mono font-bold text-rn-text-1">21 - 23 Pallets</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 flex items-center justify-between">
                                <span class="text-xs font-mono text-rn-text-2">IBC Tote Tanks</span>
                                <span class="text-xs font-mono font-bold text-rn-text-3">1,000L Food-Grade</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5 mt-5 border-t border-rn-line/10 flex items-center justify-between text-xs font-mono text-rn-text-3">
                        <span>Nitrogen-Blanketed Filling</span>
                        <span class="text-rn-gold font-bold">Zero Oxidation</span>
                    </div>
                </div>

                <!-- Tile 2: Studio Embouteillage OEM & Private Label -->
                <div class="rounded-3xl rn-glass--flat border border-rn-line/12 hover:border-rn-gold/50 p-5 sm:p-6 lg:p-6 flex flex-col justify-between transition-all duration-300 shadow-xl relative overflow-hidden group reveal-child">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="font-mono text-xs font-black text-rn-gold bg-rn-bg-0/90 px-3 py-1.5 rounded-lg border border-rn-line/12">02</span>
                                <span class="text-xs font-mono font-bold text-rn-olive flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-rn-olive"></span>
                                    Turnkey OEM Solutions
                                </span>
                            </div>
                            <span class="text-[11px] font-mono text-rn-text-3 bg-rn-bg-0/80 px-2.5 py-1 rounded-md border border-rn-line/8">
                                Retail Ready
                            </span>
                        </div>

                        <div>
                            <h3 class="text-lg sm:text-xl font-display font-bold text-rn-text-1 tracking-tight">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'تعبئة وتغليف بالعلامة التجارية الخاصة (OEM)' : 'Turnkey OEM & Private Label Bottling' }}
                            </h3>
                            <p class="text-xs sm:text-sm text-rn-text-2 mt-2 leading-relaxed">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                                    ? 'نوفر خطوط تعبئة متكاملة لعلامتكم التجارية: زجاجات ماراسكا ودوريكا، صفائح معدنية مطبوعة، وعبوات فاخرة للتمور مع طباعة الباركود واللغات المتعددة وفق لوائح الاستيراد.' 
                                    : 'End-to-end bespoke bottling and packaging for retail brands: Marasca & Dorica UV glass, custom lithographed tins, and multilingual regulatory labels.' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div class="p-2.5 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 space-y-0.5">
                                <span class="font-bold text-rn-text-1 block">Glass Bottles</span>
                                <span class="text-rn-text-3 font-mono text-[10px]">Marasca & Dorica</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 space-y-0.5">
                                <span class="font-bold text-rn-text-1 block">Litho Tins</span>
                                <span class="text-rn-text-3 font-mono text-[10px]">1L, 3L & 5L Tins</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 space-y-0.5">
                                <span class="font-bold text-rn-text-1 block">Date Boxes</span>
                                <span class="text-rn-text-3 font-mono text-[10px]">1kg - 5kg Kraft</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 space-y-0.5">
                                <span class="font-bold text-rn-text-1 block">Compliance</span>
                                <span class="text-rn-text-3 font-mono text-[10px]">EAN/UPC & Barcodes</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5 mt-5 border-t border-rn-line/10 flex items-center justify-between text-xs font-mono text-rn-text-2">
                        <span>Anti-Drip Pourers</span>
                        <span class="text-rn-gold font-bold">Custom Labels</span>
                    </div>
                </div>

                <!-- Tile 3: Passerelles Commerciales & Incoterms Mondiaux -->
                <div class="rounded-3xl rn-glass--flat border border-rn-line/12 hover:border-rn-gold/50 p-5 sm:p-6 lg:p-6 flex flex-col justify-between transition-all duration-300 shadow-xl relative overflow-hidden group reveal-child">
                    <div class="space-y-5">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="font-mono text-xs font-black text-rn-gold bg-rn-bg-0/90 px-3 py-1.5 rounded-lg border border-rn-line/12">03</span>
                                <span class="text-xs font-mono font-bold text-rn-gold flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-rn-gold"></span>
                                    Global Trade Gateways
                                </span>
                            </div>
                            <span class="text-[11px] font-mono text-rn-text-3 bg-rn-bg-0/80 px-2.5 py-1 rounded-md border border-rn-line/8">
                                FOB • CIF • CFR • DDP
                            </span>
                        </div>

                        <div>
                            <h3 class="text-lg sm:text-xl font-display font-bold text-rn-text-1 tracking-tight">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'مسارات الشحن وأزمنة العبور الدولية' : 'Global Trade Corridors & Fast Transit' }}
                            </h3>
                            <p class="text-xs sm:text-sm text-rn-text-2 mt-2 leading-relaxed">
                                {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                                    ? 'موقع استراتيجي على مفترق الطرق البحرية في البحر الأبيض المتوسط يوفر أسرع مسارات عبور إلى موانئ أوروبا وأمريكا والخليج وآسيا.' 
                                    : 'Direct maritime shipping connections from Tunisian commercial ports to global trading hubs with competitive transit times and full export documentation.' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 font-mono text-xs">
                            <div class="p-2 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 flex items-center justify-between">
                                <span class="text-rn-text-2 text-[11px]">Marseille</span>
                                <span class="text-rn-gold font-bold">36-48h</span>
                            </div>
                            <div class="p-2 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 flex items-center justify-between">
                                <span class="text-rn-text-2 text-[11px]">Rotterdam</span>
                                <span class="text-rn-text-1 font-bold">5-7 Days</span>
                            </div>
                            <div class="p-2 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 flex items-center justify-between">
                                <span class="text-rn-text-2 text-[11px]">New York</span>
                                <span class="text-rn-text-1 font-bold">14-18d</span>
                            </div>
                            <div class="p-2 rounded-xl bg-rn-bg-0/60 border border-rn-line/5 flex items-center justify-between">
                                <span class="text-rn-text-2 text-[11px]">Jebel Ali</span>
                                <span class="text-rn-text-1 font-bold">12-15d</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5 mt-5 border-t border-rn-line/10 flex items-center justify-between text-xs font-mono text-rn-text-2">
                        <span>EUR.1 & Phyto Clearances</span>
                        <span class="text-rn-gold font-bold">Fast Track Customs</span>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- 5. Interactive Supply Chain Route Timeline Scene (§18 & §19) -->
    <section id="terroir" class="rn-scene rn-scene--terroir" data-scene style="--scene-z: 50;">
        <div class="rn-scene__inner space-y-4 lg:space-y-6">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto space-y-2 lg:space-y-3 reveal-up">
                <span class="text-xs font-mono font-bold text-rn-olive uppercase tracking-widest px-4 py-1.5 rounded-full rn-glass-light border border-rn-line/12 inline-flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-rn-olive"></span>
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'مسار التتبع وسلسلة الإمداد الكاملة' : 'Farm-to-Port Traceability Timeline' }}
                </span>
                <h2 class="font-display text-2xl sm:text-3xl lg:text-4xl font-bold text-rn-text-1 tracking-tight leading-tight">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'من مزارع الزيتون العريقة وواحات الجنوب إلى موانئ التصدير' : 'From Ancient Mediterranean Groves to Global Container Ports' }}
                </h2>
                <p class="text-xs sm:text-sm text-rn-text-2 leading-relaxed font-normal max-w-2xl mx-auto">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar'
                        ? 'رحلة متكاملة تخضع لرقابة صارمة في كل مرحلة لضمان وصول المنتج التونسي بأعلى معايير النقاء والجودة المعتمدة عالمياً.'
                        : 'A seamless, strictly monitored four-stage journey ensuring uncompromised purity, full harvest traceability, and fast maritime dispatch.' }}
                </p>
            </div>

            <!-- Route Timeline Cards (4 Sequential Stages with Desktop Connecting Line) -->
            <div class="relative">
                <!-- Desktop Horizontal Timeline Connecting Line (§18) -->
                <div class="hidden lg:block absolute top-4 inset-x-16 h-px bg-gradient-to-r from-rn-olive/30 via-rn-gold/50 to-rn-cyan/40 z-0"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6 relative z-10 reveal-stagger">
                    
                    <!-- Stage 01: Terroir & Harvest -->
                    <div class="space-y-2.5 reveal-up">
                        <div class="hidden lg:flex w-7 h-7 rounded-full bg-rn-bg-0 border border-rn-olive text-rn-olive font-mono font-bold text-xs items-center justify-center mx-auto shadow-md">01</div>
                        <div class="rounded-3xl rn-glass border border-rn-line/12 hover:border-rn-gold p-5 sm:p-6 flex flex-col justify-between transition-all duration-300 group hover:-translate-y-1 shadow-xl">
                            <div class="space-y-3.5">
                                <div class="flex items-center justify-between">
                                    <span class="w-8 h-8 rounded-xl bg-rn-bg-0 text-rn-gold font-mono font-bold text-xs flex items-center justify-center border border-rn-line/12 lg:hidden">01</span>
                                    <span class="text-[11px] font-mono text-rn-olive bg-rn-olive/15 px-2.5 py-0.5 rounded-lg border border-rn-olive/30 font-semibold">Harvest Stage</span>
                                </div>
                                <div class="aspect-[16/10] rounded-2xl overflow-hidden relative border border-rn-line/8 group-hover:border-rn-line/20 transition-colors">
                                    <img src="{{ asset('images/hero/olive-grove-tunisia.jpg') }}" alt="Harvest Terroir" width="400" height="250" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-rn-bg-0/80 via-transparent to-transparent"></div>
                                    <span class="absolute bottom-2 start-2 text-[11px] font-mono font-bold text-rn-text-1 bg-rn-bg-0/80 px-2 py-0.5 rounded backdrop-blur-sm">Chétoui & Chemlali</span>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-display font-bold text-rn-text-1">
                                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الأرض والجني المبكر' : 'Terroir & Early Harvest' }}
                                    </h3>
                                    <p class="text-xs text-rn-text-2 mt-1 leading-relaxed">
                                        {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                                            ? 'جني يدوي منتقى في أوج النضج من شمال تونس والوطن القبلي، إلى جانب عراجين دقلة النور من واحات توزر وقبلي.' 
                                            : 'Selective early harvesting from ancient groves in Cap Bon, Zaghouan, and royal palm oases in Tozeur & Kebili.' }}
                                    </p>
                                </div>
                            </div>
                            <div class="pt-3.5 mt-3.5 border-t border-rn-line/10 flex items-center justify-between text-[11px] font-mono text-rn-text-3">
                                <span>Oct - Jan</span>
                                <span class="text-rn-gold font-bold">100% Traceable</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stage 02: Extraction & Sorting -->
                    <div class="space-y-2.5 reveal-up">
                        <div class="hidden lg:flex w-7 h-7 rounded-full bg-rn-bg-0 border border-rn-olive text-rn-olive font-mono font-bold text-xs items-center justify-center mx-auto shadow-md">02</div>
                        <div class="rounded-3xl rn-glass border border-rn-line/12 hover:border-rn-gold p-5 sm:p-6 flex flex-col justify-between transition-all duration-300 group hover:-translate-y-1 shadow-xl">
                            <div class="space-y-3.5">
                                <div class="flex items-center justify-between">
                                    <span class="w-8 h-8 rounded-xl bg-rn-bg-0 text-rn-gold font-mono font-bold text-xs flex items-center justify-center border border-rn-line/12 lg:hidden">02</span>
                                    <span class="text-[11px] font-mono text-rn-olive bg-rn-olive/15 px-2.5 py-0.5 rounded-lg border border-rn-olive/30 font-semibold">Milling Stage</span>
                                </div>
                                <div class="aspect-[16/10] rounded-2xl overflow-hidden relative border border-rn-line/8 group-hover:border-rn-line/20 transition-colors">
                                    <img src="{{ asset('images/products/olive_oil_bottle.jpg') }}" alt="Cold Extraction" width="400" height="250" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-rn-bg-0/80 via-transparent to-transparent"></div>
                                    <span class="absolute bottom-2 start-2 text-[11px] font-mono font-bold text-rn-text-1 bg-rn-bg-0/80 px-2 py-0.5 rounded backdrop-blur-sm">&lt; 27°C Cold Press</span>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-display font-bold text-rn-text-1">
                                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'العصر على البارد والفرز' : 'Cold Extraction & Sorting' }}
                                    </h3>
                                    <p class="text-xs text-rn-text-2 mt-1 leading-relaxed">
                                        {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                                            ? 'عصر ميكانيكي مستمر خلال ساعات قليلة من القطف دون إضافة حرارة، مع فرز بصري دقيق لحبات التمور والطماطم المجففة.' 
                                            : 'Centrifugal cold extraction within 12 hours under 27°C to lock in antioxidants, alongside optical sorting for dates.' }}
                                    </p>
                                </div>
                            </div>
                            <div class="pt-3.5 mt-3.5 border-t border-rn-line/10 flex items-center justify-between text-[11px] font-mono text-rn-text-3">
                                <span>&lt; 27°C Limit</span>
                                <span class="text-rn-gold font-bold">Zero Additives</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stage 03: Lab Certification -->
                    <div class="space-y-2.5 reveal-up">
                        <div class="hidden lg:flex w-7 h-7 rounded-full bg-rn-bg-0 border border-rn-gold text-rn-gold font-mono font-bold text-xs items-center justify-center mx-auto shadow-md">03</div>
                        <div class="rounded-3xl rn-glass border border-rn-line/12 hover:border-rn-gold p-5 sm:p-6 flex flex-col justify-between transition-all duration-300 group hover:-translate-y-1 shadow-xl">
                            <div class="space-y-3.5">
                                <div class="flex items-center justify-between">
                                    <span class="w-8 h-8 rounded-xl bg-rn-bg-0 text-rn-gold font-mono font-bold text-xs flex items-center justify-center border border-rn-line/12 lg:hidden">03</span>
                                    <span class="text-[11px] font-mono text-rn-gold bg-rn-gold/15 px-2.5 py-0.5 rounded-lg border border-rn-gold/30 font-semibold">Lab Analysis</span>
                                </div>
                                <div class="aspect-[16/10] rounded-2xl overflow-hidden relative border border-rn-line/8 group-hover:border-rn-line/20 transition-colors">
                                    <img src="{{ asset('images/hero/company_export_hero.jpg') }}" alt="Quality Lab" width="400" height="250" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-rn-bg-0/80 via-transparent to-transparent"></div>
                                    <span class="absolute bottom-2 start-2 text-[11px] font-mono font-bold text-rn-text-1 bg-rn-bg-0/80 px-2 py-0.5 rounded backdrop-blur-sm">COI Lab Verification</span>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-display font-bold text-rn-text-1">
                                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'التحاليل المخبرية والاعتماد' : 'Lab Certification & CoA' }}
                                    </h3>
                                    <p class="text-xs text-rn-text-2 mt-1 leading-relaxed">
                                        {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                                            ? 'فحص شامل لنسب الأحماض الدهنية والبيروكسيد، وتقييم حسي عبر لجان تذوق معتمدة، مع إصدار شهادة مطابقة لكل دفعة.' 
                                            : 'Complete HPLC chromatographic panel and organoleptic tasting score. Batch-specific Certificate of Analysis issued.' }}
                                    </p>
                                </div>
                            </div>
                            <div class="pt-3.5 mt-3.5 border-t border-rn-line/10 flex items-center justify-between text-[11px] font-mono text-rn-text-3">
                                <span>Extra Virgin</span>
                                <span class="text-rn-gold font-bold">Acidity &lt; 0.18%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stage 04: Port Dispatch -->
                    <div class="space-y-2.5 reveal-up">
                        <div class="hidden lg:flex w-7 h-7 rounded-full bg-rn-bg-0 border border-rn-cyan text-rn-cyan font-mono font-bold text-xs items-center justify-center mx-auto shadow-md">04</div>
                        <div class="rounded-3xl rn-glass border border-rn-line/12 hover:border-rn-gold p-5 sm:p-6 flex flex-col justify-between transition-all duration-300 group hover:-translate-y-1 shadow-xl">
                            <div class="space-y-3.5">
                                <div class="flex items-center justify-between">
                                    <span class="w-8 h-8 rounded-xl bg-rn-bg-0 text-rn-gold font-mono font-bold text-xs flex items-center justify-center border border-rn-line/12 lg:hidden">04</span>
                                    <span class="text-[11px] font-mono text-rn-cyan bg-rn-cyan/15 px-2.5 py-0.5 rounded-lg border border-rn-cyan/30 font-semibold">Port Dispatch</span>
                                </div>
                                <div class="aspect-[16/10] rounded-2xl overflow-hidden relative border border-rn-line/8 group-hover:border-rn-line/20 transition-colors">
                                    <img src="{{ asset('images/products/bulk_drum_flexitank.jpg') }}" alt="Maritime Shipping" width="400" height="250" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-rn-bg-0/80 via-transparent to-transparent"></div>
                                    <span class="absolute bottom-2 start-2 text-[11px] font-mono font-bold text-rn-text-1 bg-rn-bg-0/80 px-2 py-0.5 rounded backdrop-blur-sm">Radès & Sfax</span>
                                </div>
                                <div>
                                    <h3 class="text-base sm:text-lg font-display font-bold text-rn-text-1">
                                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الشحن البحري والتخليص الجمركي' : 'Port Loading & Dispatch' }}
                                    </h3>
                                    <p class="text-xs text-rn-text-2 mt-1 leading-relaxed">
                                        {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                                            ? 'شحن مباشر عبر حاويات 20ft/40ft أو خزانات الفليكسيتانك مع النيتروجين، وإتمام الفحص الصحي النباتي والشهادات الجمركية.' 
                                            : 'Direct container loading at Port of Radès & Sfax, phytosanitary release, customs sealing, and bill of lading dispatch.' }}
                                    </p>
                                </div>
                            </div>
                            <div class="pt-3.5 mt-3.5 border-t border-rn-line/10 flex items-center justify-between text-[11px] font-mono text-rn-text-3">
                                <span>Clearance: 24h</span>
                                <span class="text-rn-gold font-bold">Departure</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- 6. Accredited Certifications Scene (§19) -->
    <section id="certifications" class="rn-scene rn-scene--certifications" data-scene style="--scene-z: 60;">
        <div class="rn-scene__inner space-y-4 lg:space-y-6">
            
            <div class="text-center max-w-2xl mx-auto space-y-2 lg:space-y-3 reveal-up">
                <span class="text-xs sm:text-sm font-bold text-rn-gold rn-glass-light border border-rn-line/12 px-4 py-1.5 rounded-full uppercase tracking-widest font-mono shadow-sm inline-block">Global Compliance</span>
                <h2 class="font-display text-2xl sm:text-3xl lg:text-4xl font-bold text-rn-text-1">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الشهادات والتحاليل المخبرية المعتمدة' : 'Accredited Certifications & Lab Analyses' }}
                </h2>
                <p class="text-xs sm:text-sm text-rn-text-2 font-normal max-w-2xl mx-auto">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar'
                        ? 'مطابقة موثقة لأسواق الاتحاد الأوروبي والولايات المتحدة والمملكة المتحدة ومجلس التعاون الخليجي.'
                        : 'Guaranteed compliance for the European Union, United States, United Kingdom, and Gulf Cooperation Council (GCC).' }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6 reveal-stagger">
                @foreach($certificates as $index => $cert)
                    <div class="p-5 sm:p-6 rounded-3xl rn-glass hover:border-rn-gold space-y-3.5 group cursor-default transition-all border border-rn-line/12 shadow-lg hover:-translate-y-1 reveal-child">
                        
                        <div class="w-11 h-11 rounded-2xl bg-rn-bg-0/90 border border-rn-gold/30 flex items-center justify-center text-rn-gold shadow-sm group-hover:scale-105 group-hover:border-rn-gold transition-all duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-rn-text-1">{{ $cert->localized_name }}</h3>
                            <p class="text-xs text-rn-text-3 mt-0.5 font-medium">Issued by {{ $cert->issuer }}</p>
                        </div>
                        <div class="pt-3 border-t border-rn-line/10 flex items-center justify-between text-xs">
                            <span class="text-rn-success font-semibold flex items-center gap-1.5 bg-rn-bg-0/80 px-2 py-0.5 rounded-lg border border-rn-success/20 text-[11px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-rn-success"></span>
                                Verified Active
                            </span>
                            <span class="text-rn-gold font-mono font-bold bg-rn-bg-0 px-2.5 py-0.5 rounded-lg border border-rn-line/12 shadow-sm text-[11px]">{{ $cert->valid_until ? $cert->valid_until->format('Y') : 'Valid' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <!-- 7. Final Conversion CTA Scene (§19 & §21) -->
    <section id="final-cta" class="rn-scene rn-scene--cta" data-scene style="--scene-z: 70;">
        <div class="rn-scene__inner">
            <div class="max-w-4xl mx-auto text-center space-y-4 sm:space-y-6 py-4 sm:py-6 reveal-up">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rn-bg-2/80 border border-rn-line/12 text-xs font-mono font-bold text-rn-gold shadow-sm backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-rn-gold"></span>
                    <span class="uppercase tracking-widest">
                        RAYANOVA T.D • DIRECT MEDITERRANEAN SOURCING
                    </span>
                </div>

                <div class="space-y-3 sm:space-y-4">
                    <h2 class="font-display text-2xl sm:text-3xl lg:text-4xl font-bold text-rn-text-1 tracking-tight leading-tight">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'جاهزون لتلبية عقودكم التصديرية وشحناتكم الكبرى' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Prêt à Sécuriser Votre Prochain Contrat d\'Export Méditerranéen ?' : 'Ready to Secure Your Next Mediterranean Export Contract?') }}
                    </h2>
                    <p class="text-xs sm:text-sm text-rn-text-2 leading-relaxed max-w-2xl mx-auto font-normal">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                            ? 'تواصل مع مكتب التصدير لطلب عينات مخبرية، دراسة أسعار الشحن، أو إعداد عروض أسعار رسمية فورية.' 
                            : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Contactez directement notre bureau export pour des échantillons certifiés, des fiches de conditionnement et des cotations officielles Incoterms 2020.' : 'Connect directly with our export desk for certified laboratory samples, bespoke packaging specifications, and competitive Incoterms 2020 quotations.') }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-4 pt-1">
                    <a href="{{ LaravelLocalization::localizeUrl('/rfq') }}" 
                       class="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-gradient-to-r from-rn-gold-soft to-rn-gold hover:from-rn-gold hover:to-rn-gold-soft text-rn-bg-0 font-bold text-sm shadow-xl shadow-rn-gold/25 transition-all hover:scale-[1.02] active:scale-[0.98] cursor-pointer">
                        <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'طلب تسعيرة رسمية (RFQ)' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Demander un Devis (RFQ)' : 'Request Export RFQ') }}</span>
                        <svg class="w-4 h-4 {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $companyHero->whatsapp_number ?? '21698000000') }}?text={{ urlencode('Hello Rayanova T.D Export Desk, I would like to inquire about quotations.') }}" 
                       target="_blank" 
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-emerald-950/80 hover:bg-emerald-900 text-emerald-300 hover:text-white font-semibold text-sm transition-all hover:scale-[1.02] border border-emerald-500/40 backdrop-blur-md cursor-pointer">
                        <svg class="w-4 h-4 fill-current text-emerald-400" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"></path></svg>
                        <span>WhatsApp Export Desk</span>
                    </a>
                </div>

                <div class="pt-3 flex flex-wrap items-center justify-center gap-6 text-xs text-rn-text-3 font-mono">
                    <span>Tunis HQ / Radès Port</span>
                    <span>•</span>
                    <span>Incoterms 2020: FOB · CIF · CFR · DDP</span>
                    <span>•</span>
                    <span>Response within 4 Business Hours</span>
                </div>
            </div>
        </div>
    </section>

</x-layouts.app>
