<x-layouts.admin :title="(__('admin.company_hero_cms') ?? 'Company Intro Hero') . ' — ' . __('admin.brand_name')">

    <div class="max-w-4xl mx-auto space-y-8">
        
        <!-- Top Breadcrumbs -->
        <div class="flex items-center gap-3 border-b-2 border-slate-200 pb-4">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-extrabold text-gold-700 hover:text-gold-900 flex items-center gap-1">
                <span>← {{ __('admin.overview_kpis') }}</span>
            </a>
            <span class="text-slate-400">•</span>
            <span class="text-xs sm:text-sm text-slate-500 font-medium">{{ __('admin.company_hero_cms') ?? 'Company Intro Hero' }}</span>
        </div>

        <div>
            <span class="text-xs sm:text-sm uppercase tracking-widest text-gold-700 font-extrabold flex items-center gap-1.5">
                <span>🏢</span>
                <span>{{ __('admin.company_hero_badge') ?? 'Corporate Identity & Gateway' }}</span>
            </span>
            <h1 class="font-serif text-3xl sm:text-4xl font-extrabold text-slate-900 mt-1">{{ __('admin.company_hero_title') ?? 'Company Introduction Hero' }}</h1>
            <p class="text-sm sm:text-base text-slate-600 mt-1">{{ __('admin.company_hero_subtitle') ?? 'Edit the premier corporate introduction hero placed before the product slides.' }}</p>
        </div>

        @if(session('success'))
            <div class="p-5 rounded-2xl bg-emerald-100 border-2 border-emerald-500 text-emerald-950 text-sm font-bold flex items-center gap-3">
                <span class="text-xl">✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-5 rounded-2xl bg-red-100 border-2 border-red-500 text-red-950 text-sm font-bold space-y-1">
                <div class="flex items-center gap-2">
                    <span>⚠️</span>
                    <span>Please correct the errors below:</span>
                </div>
                <ul class="list-disc list-inside text-xs font-semibold pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.company_hero.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-6 sm:p-10 border-2 border-gold-400/30 shadow-2xl space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1 : Contrôle de Visibilité du Badge et de la Bannière Hero (Show / Hide Options) -->
            <div class="space-y-6 p-6 sm:p-8 rounded-3xl bg-gradient-to-br from-amber-50/80 via-white to-amber-50/40 border-2 border-gold-400/50 shadow-lg">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b-2 border-gold-400/30 pb-3">
                    <div>
                        <span class="text-xs uppercase font-extrabold text-gold-700 tracking-wider flex items-center gap-1.5">
                            <span>👁️</span>
                            <span>1. {{ __('admin.hero_visibility_sec') }}</span>
                        </span>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-0.5">
                            {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'خيارات إظهار وإخفاء الشارة والبانر في الواجهة' : 'Badge & Banner Display Controls (Show / Hide)' }}
                        </h2>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-gold-100 text-gold-900 font-mono text-xs font-bold border border-gold-300 self-start sm:self-auto flex items-center gap-1.5 shadow-sm">
                        <span>⚙️</span>
                        <span>Interactive Controls</span>
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    
                    <!-- Control 1: Hero Badge (Show / Hide) -->
                    <div class="p-5 rounded-2xl bg-white border-2 border-slate-200 hover:border-gold-400 transition-all shadow-sm space-y-4 flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                    <span>🏷️</span>
                                    <span>{{ __('admin.hero_badge_visibility') }}</span>
                                </span>
                                @if(old('show_badge', $companyHero->show_badge ?? true))
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[11px] font-bold border border-emerald-300">
                                        ✓ {{ __('admin.visible') }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md bg-rose-100 text-rose-800 text-[11px] font-bold border border-rose-300">
                                        ✕ {{ __('admin.hidden_opt') }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                {{ __('admin.hero_badge_desc') }}
                            </p>
                        </div>

                        <!-- Segmented Radio Buttons for Badge -->
                        <div class="grid grid-cols-2 gap-2 bg-slate-100 p-1.5 rounded-xl border border-slate-200">
                            <label class="cursor-pointer">
                                <input type="radio" name="show_badge" value="1" class="sr-only peer" {{ old('show_badge', $companyHero->show_badge ?? true) ? 'checked' : '' }}>
                                <div class="w-full py-2 px-3 rounded-lg text-center font-bold text-xs transition-all text-slate-600 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:shadow-md flex items-center justify-center gap-1.5">
                                    <span>✓</span>
                                    <span>{{ __('admin.show_badge_label') }}</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="show_badge" value="0" class="sr-only peer" {{ !old('show_badge', $companyHero->show_badge ?? true) ? 'checked' : '' }}>
                                <div class="w-full py-2 px-3 rounded-lg text-center font-bold text-xs transition-all text-slate-600 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:shadow-md flex items-center justify-center gap-1.5">
                                    <span>✕</span>
                                    <span>{{ __('admin.hide_badge_label') }}</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Control 2: Top Status Banner Strip (Show / Hide) -->
                    <div class="p-5 rounded-2xl bg-white border-2 border-slate-200 hover:border-gold-400 transition-all shadow-sm space-y-4 flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                    <span>⚓</span>
                                    <span>{{ __('admin.hero_banner_visibility') }}</span>
                                </span>
                                @if(old('show_banner', $companyHero->show_banner ?? true))
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[11px] font-bold border border-emerald-300">
                                        ✓ {{ __('admin.visible') }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md bg-rose-100 text-rose-800 text-[11px] font-bold border border-rose-300">
                                        ✕ {{ __('admin.hidden_opt') }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                {{ __('admin.hero_banner_desc') }}
                            </p>
                        </div>

                        <!-- Segmented Radio Buttons for Banner Strip -->
                        <div class="grid grid-cols-2 gap-2 bg-slate-100 p-1.5 rounded-xl border border-slate-200">
                            <label class="cursor-pointer">
                                <input type="radio" name="show_banner" value="1" class="sr-only peer" {{ old('show_banner', $companyHero->show_banner ?? true) ? 'checked' : '' }}>
                                <div class="w-full py-2 px-3 rounded-lg text-center font-bold text-xs transition-all text-slate-600 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:shadow-md flex items-center justify-center gap-1.5">
                                    <span>✓</span>
                                    <span>{{ __('admin.show_banner_label') }}</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="show_banner" value="0" class="sr-only peer" {{ !old('show_banner', $companyHero->show_banner ?? true) ? 'checked' : '' }}>
                                <div class="w-full py-2 px-3 rounded-lg text-center font-bold text-xs transition-all text-slate-600 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:shadow-md flex items-center justify-center gap-1.5">
                                    <span>✕</span>
                                    <span>{{ __('admin.hide_banner_label') }}</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Control 3: Homepage Hero Section (Show / Hide) -->
                    <div class="p-5 rounded-2xl bg-white border-2 border-slate-200 hover:border-gold-400 transition-all shadow-sm space-y-4 flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                    <span>🏢</span>
                                    <span>{{ __('admin.hero_section_visibility') }}</span>
                                </span>
                                @if(old('is_active', $companyHero->is_active ?? true))
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[11px] font-bold border border-emerald-300">
                                        ✓ {{ __('admin.visible') }}
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-md bg-rose-100 text-rose-800 text-[11px] font-bold border border-rose-300">
                                        ✕ {{ __('admin.hidden_opt') }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 leading-relaxed">
                                {{ __('admin.hero_section_desc') }}
                            </p>
                        </div>

                        <!-- Segmented Radio Buttons for Main Hero Section -->
                        <div class="grid grid-cols-2 gap-2 bg-slate-100 p-1.5 rounded-xl border border-slate-200">
                            <label class="cursor-pointer">
                                <input type="radio" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $companyHero->is_active ?? true) ? 'checked' : '' }}>
                                <div class="w-full py-2 px-3 rounded-lg text-center font-bold text-xs transition-all text-slate-600 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:shadow-md flex items-center justify-center gap-1.5">
                                    <span>✓</span>
                                    <span>{{ __('admin.show_hero_label') }}</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="is_active" value="0" class="sr-only peer" {{ !old('is_active', $companyHero->is_active ?? true) ? 'checked' : '' }}>
                                <div class="w-full py-2 px-3 rounded-lg text-center font-bold text-xs transition-all text-slate-600 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:shadow-md flex items-center justify-center gap-1.5">
                                    <span>✕</span>
                                    <span>{{ __('admin.hide_hero_label') }}</span>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Section 2 : Badge & Titres Multilingues -->
            <div class="space-y-6 pt-2">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <h2 class="text-xs sm:text-sm font-extrabold text-gold-700 uppercase tracking-wider">
                        2. {{ __('admin.slide_titles_sec') }}
                    </h2>
                    <span class="text-xs text-slate-400 font-medium">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'تعديل نصوص الشارة والعناوين' : 'Edit badge & headline texts' }}
                    </span>
                </div>

                <!-- Badge Text -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-600">
                            {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'نصوص الشارة (تظهر عند تفعيل خيار إظهار الشارة أعلاه):' : 'Badge texts (shown when Hero Badge is set to Show above):' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div class="space-y-2">
                            <label class="block text-xs sm:text-sm font-bold text-slate-800">Badge (EN)</label>
                            <input type="text" name="badge_en" value="{{ old('badge_en', $companyHero->badge['en'] ?? '') }}"
                                   class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm font-medium focus:border-gold-500 focus:bg-white focus:outline-none">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs sm:text-sm font-bold text-slate-800">Badge (FR)</label>
                            <input type="text" name="badge_fr" value="{{ old('badge_fr', $companyHero->badge['fr'] ?? '') }}"
                                   class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm font-medium focus:border-gold-500 focus:bg-white focus:outline-none">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs sm:text-sm font-bold text-slate-800 font-arabic" dir="rtl">شارة التعريف (AR)</label>
                            <input type="text" name="badge_ar" value="{{ old('badge_ar', $companyHero->badge['ar'] ?? '') }}" dir="rtl"
                                   class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-base font-arabic focus:border-gold-500 focus:bg-white focus:outline-none">
                        </div>
                    </div>
                </div>

                <!-- Main Title -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-800">{{ __('admin.title_en') }} *</label>
                        <textarea name="title_en" rows="3" required
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm font-bold focus:border-gold-500 focus:bg-white focus:outline-none">{{ old('title_en', $companyHero->title['en'] ?? '') }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-800">{{ __('admin.title_fr') }}</label>
                        <textarea name="title_fr" rows="3"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm font-bold focus:border-gold-500 focus:bg-white focus:outline-none">{{ old('title_fr', $companyHero->title['fr'] ?? '') }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-800 font-arabic" dir="rtl">{{ __('admin.title_ar') }} *</label>
                        <textarea name="title_ar" rows="3" dir="rtl" required
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-base font-arabic focus:border-gold-500 focus:bg-white focus:outline-none">{{ old('title_ar', $companyHero->title['ar'] ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Subtitle / Intro Text -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.subtitle_en') }}</label>
                        <textarea name="subtitle_en" rows="4"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-xs sm:text-sm font-medium focus:border-gold-500 focus:bg-white focus:outline-none">{{ old('subtitle_en', $companyHero->subtitle['en'] ?? '') }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.subtitle_fr') }}</label>
                        <textarea name="subtitle_fr" rows="4"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-xs sm:text-sm font-medium focus:border-gold-500 focus:bg-white focus:outline-none">{{ old('subtitle_fr', $companyHero->subtitle['fr'] ?? '') }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800 font-arabic" dir="rtl">{{ __('admin.subtitle_ar') }}</label>
                        <textarea name="subtitle_ar" rows="4" dir="rtl"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-base font-arabic focus:border-gold-500 focus:bg-white focus:outline-none">{{ old('subtitle_ar', $companyHero->subtitle['ar'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2 : Image de Fond de la Plateforme (Background Image) -->
            <div id="background-image-section" class="space-y-6 pt-6 border-t-2 border-gold-400/40 scroll-mt-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                    <div>
                        <span class="text-xs uppercase font-extrabold text-gold-700 tracking-wider flex items-center gap-1.5">
                            <span>🖼️</span>
                            <span>2. {{ __('admin.change_background_image') ?? 'Image de Fond Principale (Hero Parallax)' }}</span>
                        </span>
                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-0.5">
                            صورة الخلفية الكبرى للموقع (Hero & Parallax Background)
                        </h2>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-900 font-mono text-xs font-bold border border-emerald-300 flex items-center gap-1.5 self-start sm:self-auto">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        Live Background
                    </span>
                </div>

                <!-- Informative Guidance Note -->
                <div class="p-4 rounded-2xl bg-amber-50/70 border-2 border-gold-300 text-slate-800 text-xs sm:text-sm font-medium flex items-start gap-3">
                    <span class="text-xl">💡</span>
                    <p class="leading-relaxed">
                        هذه الصورة هي الخلفية الكبرى (Global Parallax Background) التي تغطي كامل الشاشة خلف واجهة الموقع وواجهة الـ Hero. يمكنك <strong>تحميل صورة جديدة من جهازك</strong> أو <strong>اختيار إحدى الخلفيات الجاهزة عالية الدقة</strong> بنقرة واحدة.
                    </p>
                </div>

                <!-- Current Active Image Preview Card -->
                <div class="p-5 rounded-2xl bg-slate-900 text-white border-2 border-gold-400/60 shadow-xl space-y-3">
                    <div class="flex items-center justify-between text-xs font-bold text-gold-300">
                        <span class="uppercase tracking-wider">الخلفية النشطة الحالية (Current Active Background)</span>
                        <span class="font-mono text-[11px] bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-700 text-slate-300">100% Fullscreen</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                        <div class="w-full sm:w-72 h-44 rounded-xl overflow-hidden border-2 border-gold-400 shadow-md bg-slate-950 shrink-0 relative group">
                            <img id="current_active_preview_img" src="{{ $companyHero->image_url }}" alt="Company Hero Background" class="w-full h-full object-cover">
                            <a href="{{ $companyHero->image_url }}" target="_blank" class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-xs font-bold text-white gap-1 backdrop-blur-sm">
                                <span>🔍 عرض الحجم الكامل</span>
                            </a>
                        </div>
                        <div class="space-y-2 text-xs sm:text-sm">
                            <div class="font-mono text-emerald-400 font-extrabold break-all bg-slate-950/90 p-2.5 rounded-xl border border-slate-800" id="current_path_text">
                                {{ $companyHero->background_image }}
                            </div>
                            <p class="text-slate-300 text-xs leading-relaxed">
                                تظهر هذه الصورة بدقة فائقة Ultra-HD مع تأثير Parallax ناعم عند التمرير لتمنح الزوار طابعاً عالمياً فخماً.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Presets Quick Selector -->
                <div class="space-y-3 pt-2">
                    <label class="block text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span>⭐</span>
                        <span>أو اختر من الخلفيات الجاهزة عالية الدقة (One-Click Presets):</span>
                    </label>

                    @php
                        $presets = [
                            [
                                'id' => 'preset_multimodal',
                                'path' => 'images/backgrounds/global_logistics_export.jpg',
                                'title' => 'أسطول اللوجستيك والشحن الدولي',
                                'desc' => 'طائرة شحن جوي، باخرة حاويات وشاحنات نقل بحري',
                                'tag' => 'Multimodal Fleet'
                            ],
                            [
                                'id' => 'preset_port_cargo',
                                'path' => 'images/hero/company_export_hero.jpg',
                                'title' => 'ميناء تصدير زيت الزيتون والبراميل',
                                'desc' => 'أرصفة الشحن البحري، براميل زيت الزيتون ورافعات الميناء',
                                'tag' => 'Port & Drums'
                            ],
                            [
                                'id' => 'preset_vessel',
                                'path' => 'images/backgrounds/maritime_export_vessel.png',
                                'title' => 'باخرة الحاويات في عرض البحر',
                                'desc' => 'سفينة نقل كبرى مبحرة في البحر الأبيض المتوسط',
                                'tag' => 'Container Vessel'
                            ],
                            [
                                'id' => 'preset_olives',
                                'path' => 'images/hero/olive-grove-tunisia.jpg',
                                'title' => 'مزارع وغابات الزيتون التونسية',
                                'desc' => 'طبيعة البحر الأبيض المتوسط وأشجار الزيتون العريقة',
                                'tag' => 'Olive Terroir'
                            ],
                            [
                                'id' => 'preset_oasis',
                                'path' => 'images/hero/oasis-dates.jpg',
                                'title' => 'واحات تمور دقلة النور الملكية',
                                'desc' => 'واحات الجنوب التونسي الخلابة بأشجار النخيل الذهبية',
                                'tag' => 'Saharan Oases'
                            ],
                        ];
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                        @foreach($presets as $preset)
                            @php
                                $isSelected = old('background_image', $companyHero->background_image) === $preset['path'];
                            @endphp
                            <div onclick="selectPreset('{{ $preset['path'] }}', '{{ asset($preset['path']) }}', '{{ $preset['id'] }}')"
                                 id="card_{{ $preset['id'] }}"
                                 class="preset-card p-3 rounded-2xl border-2 {{ $isSelected ? 'border-gold-500 bg-amber-50/80 shadow-md ring-2 ring-gold-400' : 'border-slate-200 bg-white hover:border-gold-300 hover:bg-amber-50/30' }} cursor-pointer transition-all flex flex-col justify-between group space-y-2">
                                <div class="w-full h-28 rounded-xl overflow-hidden bg-slate-900 border border-slate-200 relative group-hover:scale-[1.02] transition-transform">
                                    <img src="{{ asset($preset['path']) }}" alt="{{ $preset['title'] }}" class="w-full h-full object-cover">
                                    <span class="absolute top-2 right-2 px-2 py-0.5 rounded-md bg-slate-900/85 text-[10px] font-mono font-bold text-gold-300 border border-slate-700">
                                        {{ $preset['tag'] }}
                                    </span>
                                </div>
                                <div class="space-y-0.5 text-start">
                                    <h4 class="text-xs sm:text-sm font-extrabold text-slate-900 leading-tight">
                                        {{ $preset['title'] }}
                                    </h4>
                                    <p class="text-[11px] text-slate-500 line-clamp-1">
                                        {{ $preset['desc'] }}
                                    </p>
                                </div>
                                <div class="pt-1 flex items-center justify-between text-xs font-bold {{ $isSelected ? 'text-gold-700' : 'text-slate-400 group-hover:text-gold-600' }}">
                                    <span class="text-[11px]">اختر هذه الخلفية</span>
                                    <span class="preset-indicator text-base">{{ $isSelected ? '✓' : '○' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Custom Upload Dropzone -->
                <div class="space-y-3 pt-3">
                    <label class="block text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span>📤</span>
                        <span>أو قم بتحميل صورة مخصصة جديدة من جهازك (Upload Custom Image):</span>
                    </label>

                    <div id="dropzone"
                         class="relative border-2 border-dashed border-gold-400 hover:border-gold-500 bg-amber-50/30 hover:bg-amber-50/60 transition-all rounded-3xl p-6 sm:p-8 text-center cursor-pointer group shadow-inner">
                        
                        <input type="file" name="image_file" id="image_file" accept="image/jpeg,image/png,image/jpg,image/webp,image/avif"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">

                        <div id="upload_prompt" class="space-y-2 pointer-events-none">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-gold-300 via-gold-400 to-gold-600 mx-auto flex items-center justify-center text-slate-950 font-black text-2xl shadow-lg shadow-gold-500/20 group-hover:scale-105 transition-transform">
                                📷
                            </div>
                            <div>
                                <span class="text-sm md:text-base font-extrabold text-slate-900 block group-hover:text-gold-800 transition-colors">
                                    {{ __('admin.drag_drop_image_text') ?? 'انقر هنا لاختيار صورة من جهازك أو اسحبها إلى هنا' }}
                                </span>
                                <span class="text-xs font-semibold text-slate-500 mt-0.5 block">
                                    PNG, JPG, WEBP, AVIF (حجم أقصى: 20MB — يُفضل دقة 1920x1080 أو أعلى)
                                </span>
                            </div>
                        </div>

                        <div id="preview_container" class="hidden space-y-3 pointer-events-none">
                            <div class="relative max-w-sm mx-auto rounded-2xl overflow-hidden border-2 border-gold-400 shadow-xl bg-slate-950 p-2">
                                <img id="image_preview" src="" alt="Preview" class="max-h-48 mx-auto object-contain rounded-xl">
                            </div>
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-xl bg-white border border-gold-300 text-xs font-bold text-slate-800 shadow-sm">
                                <span>📄 صورة محددة:</span>
                                <span id="file_name_display" class="font-mono truncate max-w-xs font-bold text-gold-700"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden or Manual Direct Path Input -->
                    <div class="pt-1">
                        <label class="block text-xs font-bold text-slate-600 mb-1">
                            المسار الداخلي للصورة المحددة (Internal Image Path):
                        </label>
                        <input type="text" name="background_image" id="background_image_input" 
                               value="{{ old('background_image', $companyHero->background_image) }}" 
                               placeholder="images/backgrounds/global_logistics_export.jpg"
                               class="w-full px-4 py-2.5 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-xs sm:text-sm font-mono focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>
                </div>

                <!-- Section Quick Save Button -->
                <div class="pt-2 flex items-center justify-between border-t border-slate-200">
                    <span class="text-xs text-slate-500">
                        ⚡ يمكنك حفظ الصورة فوراً دون الحاجة لتعديل باقي الحقول
                    </span>
                    <button type="submit" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-gold-500 via-gold-400 to-amber-500 hover:from-gold-400 hover:to-amber-400 text-slate-950 font-black text-xs sm:text-sm shadow-lg shadow-gold-500/25 transition-all hover:scale-105 active:scale-95 cursor-pointer border-2 border-gold-600 flex items-center gap-2">
                        <span>💾</span>
                        <span>حفظ صورة الخلفية الآن</span>
                    </button>
                </div>
            </div>

            <!-- Section 3 : Statistiques & Métriques Clés d'Exportation -->
            <div class="space-y-6 pt-6 border-t border-slate-100">
                <h2 class="text-xs sm:text-sm font-extrabold text-gold-700 uppercase tracking-wider">
                    3. {{ __('admin.glass_specs_sec') ?? 'Key Export Metrics & Accreditations' }}
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @php
                        $statsList = old('stats', $companyHero->stats ?? [
                            ['label_ar' => 'موانئ الشحن والتصدير', 'label_en' => 'Export Gateways', 'label_fr' => 'Ports d\'Exportation', 'value' => 'Rades & Sfax Ports'],
                            ['label_ar' => 'شروط التسليم الدولية', 'label_en' => 'Incoterms 2020', 'label_fr' => 'Incoterms 2020', 'value' => 'FOB • CIF • CFR • EXW'],
                            ['label_ar' => 'مطابقة واعتمادات مخبرية', 'label_en' => 'Global Accreditations', 'label_fr' => 'Accréditations', 'value' => 'EU BIO • FDA • ISO 22000'],
                            ['label_ar' => 'أنماط الشحن واللوجستيك', 'label_en' => 'Logistics Modes', 'label_fr' => 'Modes Logistiques', 'value' => 'FCL • LCL • 24,000L Bulk'],
                        ]);
                    @endphp

                    @for($i = 0; $i < 4; $i++)
                        <div class="p-5 rounded-2xl bg-amber-50/40 border-2 border-gold-300/50 space-y-3">
                            <span class="text-xs font-black uppercase tracking-wider text-gold-800">
                                Metric #{{ $i + 1 }}
                            </span>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700">Value / Metric *</label>
                                <input type="text" name="stats[{{ $i }}][value]" value="{{ $statsList[$i]['value'] ?? '' }}" placeholder="e.g. FOB • CIF • CFR"
                                       class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 text-sm font-extrabold focus:border-gold-500 focus:outline-none">
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600">Label (EN)</label>
                                    <input type="text" name="stats[{{ $i }}][label_en]" value="{{ $statsList[$i]['label_en'] ?? '' }}" placeholder="Label EN"
                                           class="w-full px-2.5 py-1.5 rounded-lg bg-white border border-slate-300 text-xs font-medium focus:border-gold-500 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600">Label (FR)</label>
                                    <input type="text" name="stats[{{ $i }}][label_fr]" value="{{ $statsList[$i]['label_fr'] ?? '' }}" placeholder="Label FR"
                                           class="w-full px-2.5 py-1.5 rounded-lg bg-white border border-slate-300 text-xs font-medium focus:border-gold-500 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 font-arabic" dir="rtl">الوصف (AR)</label>
                                    <input type="text" name="stats[{{ $i }}][label_ar]" value="{{ $statsList[$i]['label_ar'] ?? '' }}" placeholder="الوصف" dir="rtl"
                                           class="w-full px-2.5 py-1.5 rounded-lg bg-white border border-slate-300 text-xs font-arabic focus:border-gold-500 focus:outline-none">
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Section 4 : Boutons d'Action & WhatsApp -->
            <div class="space-y-6 pt-6 border-t border-slate-100">
                <h2 class="text-xs sm:text-sm font-extrabold text-gold-700 uppercase tracking-wider">
                    4. {{ __('admin.cta_buttons_sec') ?? 'Call to Action & Contacts' }}
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <!-- Primary CTA -->
                    <div class="space-y-3 p-4 rounded-2xl bg-amber-50/30 border border-gold-200">
                        <span class="text-xs font-black text-gold-800 uppercase">Primary CTA Button</span>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700">Button Label (AR / EN / FR)</label>
                            <input type="text" name="primary_cta_text_ar" value="{{ old('primary_cta_text_ar', $companyHero->primary_cta_text['ar'] ?? '') }}" placeholder="طلب تسعيرة رسمية فورية (RFQ)" dir="rtl"
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-xs font-arabic focus:border-gold-500 focus:outline-none mb-1">
                            <input type="text" name="primary_cta_text_en" value="{{ old('primary_cta_text_en', $companyHero->primary_cta_text['en'] ?? '') }}" placeholder="Request Official Export RFQ"
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-xs font-medium focus:border-gold-500 focus:outline-none mb-1">
                            <input type="text" name="primary_cta_text_fr" value="{{ old('primary_cta_text_fr', $companyHero->primary_cta_text['fr'] ?? '') }}" placeholder="Demander une Cotation (RFQ)"
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-xs font-medium focus:border-gold-500 focus:outline-none">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Target URL</label>
                            <input type="text" name="primary_cta_url" value="{{ old('primary_cta_url', $companyHero->primary_cta_url) }}" placeholder="/rfq"
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-xs font-mono focus:border-gold-500 focus:outline-none">
                        </div>
                    </div>

                    <!-- Secondary CTA & WhatsApp -->
                    <div class="space-y-3 p-4 rounded-2xl bg-amber-50/30 border border-gold-200">
                        <span class="text-xs font-black text-gold-800 uppercase">Secondary CTA & WhatsApp</span>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700">Secondary Button Label (AR / EN / FR)</label>
                            <input type="text" name="secondary_cta_text_ar" value="{{ old('secondary_cta_text_ar', $companyHero->secondary_cta_text['ar'] ?? '') }}" placeholder="استكشاف المنتجات والتعليب" dir="rtl"
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-xs font-arabic focus:border-gold-500 focus:outline-none mb-1">
                            <input type="text" name="secondary_cta_text_en" value="{{ old('secondary_cta_text_en', $companyHero->secondary_cta_text['en'] ?? '') }}" placeholder="Browse Export Catalog"
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-xs font-medium focus:border-gold-500 focus:outline-none mb-1">
                            <input type="text" name="secondary_cta_text_fr" value="{{ old('secondary_cta_text_fr', $companyHero->secondary_cta_text['fr'] ?? '') }}" placeholder="Découvrir le Catalogue"
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-xs font-medium focus:border-gold-500 focus:outline-none">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">WhatsApp Export Desk Number</label>
                            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $companyHero->whatsapp_number) }}" placeholder="+21698000000"
                                   class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-xs font-mono focus:border-gold-500 focus:outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 6 : Sauvegarde & Actions -->
            <div class="pt-6 border-t-2 border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-500">
                    <span>⚡</span>
                    <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'سيتم تطبيق خيارات الإظهار والإخفاء والتعديلات فوراً على الواجهة' : 'All display and content changes will take effect immediately upon saving' }}</span>
                </div>

                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-3.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold transition-all">
                        {{ __('admin.cancel') }}
                    </a>
                    <button type="submit" class="flex-1 sm:flex-none px-8 py-3.5 rounded-2xl bg-gradient-to-r from-gold-500 via-gold-400 to-amber-500 hover:from-gold-400 hover:to-amber-400 text-slate-950 font-black text-sm shadow-xl shadow-gold-500/25 transition-all hover:scale-105 active:scale-95 cursor-pointer border-2 border-gold-600">
                        💾 {{ __('admin.save_changes') ?? 'Save Changes' }}
                    </button>
                </div>
            </div>

        </form>

    </div>

    <!-- Script de Prévisualisation d'Image & Sélection de Présélections -->
    <script>
        function selectPreset(path, assetUrl, presetId) {
            // Update input value
            const inputPath = document.getElementById('background_image_input');
            if (inputPath) {
                inputPath.value = path;
            }

            // Update main preview card
            const currentImg = document.getElementById('current_active_preview_img');
            if (currentImg) {
                currentImg.src = assetUrl;
            }
            const currentPathText = document.getElementById('current_path_text');
            if (currentPathText) {
                currentPathText.textContent = path;
            }

            // Clear uploaded file input if any was selected
            const inputFile = document.getElementById('image_file');
            if (inputFile) {
                inputFile.value = '';
            }
            const uploadPrompt = document.getElementById('upload_prompt');
            const previewContainer = document.getElementById('preview_container');
            if (uploadPrompt && previewContainer) {
                uploadPrompt.classList.remove('hidden');
                previewContainer.classList.add('hidden');
            }

            // Highlight selected card & unhighlight others
            document.querySelectorAll('.preset-card').forEach(card => {
                card.classList.remove('border-gold-500', 'bg-amber-50/80', 'shadow-md', 'ring-2', 'ring-gold-400');
                card.classList.add('border-slate-200', 'bg-white');
                const indicator = card.querySelector('.preset-indicator');
                if (indicator) indicator.textContent = '○';
                const footer = card.querySelector('div:last-child');
                if (footer) {
                    footer.classList.remove('text-gold-700');
                    footer.classList.add('text-slate-400');
                }
            });

            const selectedCard = document.getElementById('card_' + presetId);
            if (selectedCard) {
                selectedCard.classList.remove('border-slate-200', 'bg-white');
                selectedCard.classList.add('border-gold-500', 'bg-amber-50/80', 'shadow-md', 'ring-2', 'ring-gold-400');
                const indicator = selectedCard.querySelector('.preset-indicator');
                if (indicator) indicator.textContent = '✓';
                const footer = selectedCard.querySelector('div:last-child');
                if (footer) {
                    footer.classList.remove('text-slate-400');
                    footer.classList.add('text-gold-700');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const inputFile = document.getElementById('image_file');
            const uploadPrompt = document.getElementById('upload_prompt');
            const previewContainer = document.getElementById('preview_container');
            const imagePreview = document.getElementById('image_preview');
            const fileNameDisplay = document.getElementById('file_name_display');
            const currentActiveImg = document.getElementById('current_active_preview_img');
            const currentPathText = document.getElementById('current_path_text');

            if (inputFile) {
                inputFile.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        fileNameDisplay.textContent = file.name;
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            imagePreview.src = event.target.result;
                            uploadPrompt.classList.add('hidden');
                            previewContainer.classList.remove('hidden');

                            // Also reflect in the active preview above
                            if (currentActiveImg) {
                                currentActiveImg.src = event.target.result;
                            }
                            if (currentPathText) {
                                currentPathText.textContent = 'ملف مخصص جديد: ' + file.name;
                            }

                            // Uncheck preset cards
                            document.querySelectorAll('.preset-card').forEach(card => {
                                card.classList.remove('border-gold-500', 'bg-amber-50/80', 'shadow-md', 'ring-2', 'ring-gold-400');
                                card.classList.add('border-slate-200', 'bg-white');
                                const indicator = card.querySelector('.preset-indicator');
                                if (indicator) indicator.textContent = '○';
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
</x-layouts.admin>
