<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Rayanova T.D — Premium Tunisian Agro-Food & Olive Oil Export' }}</title>
    <meta name="description" content="{{ $description ?? 'Certified Organic Extra Virgin Olive Oil, Deglet Nour Dates, Sun-dried Tomatoes and Gourmet Seafood Export from Tunisia.' }}">

    <!-- OpenGraph & Social Cards -->
    <meta property="og:title" content="{{ $title ?? 'Rayanova T.D' }}">
    <meta property="og:description" content="{{ $description ?? 'Tunisian Premium Agro-Food & Olive Oil Export' }}">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('images/og-rayanova.jpg') }}">

    <!-- Multilingual SEO Hreflang Tags -->
    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        <link rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" />
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}" />

    <!-- Schema.org JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Rayanova T.D",
      "alternateName": "Rayanova Trading & Distribution",
      "url": "{{ url('/') }}",
      "description": "International Tunisian B2B commodities and agro-food exporter: Extra Virgin Olive Oil, Deglet Nour Dates, Cap Bon Harissa, Sun-dried Tomatoes and Canned Tuna.",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Rue Omar Kaddah, Imm. Montplaisir, 2e étage B25, Av. Khaireddine Pacha",
        "addressLocality": "Montplaisir, Tunis",
        "postalCode": "1073",
        "addressCountry": "TN"
      },
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+216-71-890-120",
        "contactType": "sales",
        "areaServed": ["Worldwide", "European Union", "North America", "Middle East", "Asia"],
        "availableLanguage": ["English", "Arabic", "French"]
      }
    }
    </script>

    <!-- Fonts & Vite Assets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="text-white font-sans antialiased min-h-screen selection:bg-steel-500 selection:text-black flex flex-col relative overflow-x-clip text-base">

    <!-- Global Multimodal Logistics Fullscreen Background Stage (3la Kol 3ardh Screen) -->
    <div class="fixed inset-0 pointer-events-none -z-30 overflow-hidden" aria-hidden="true">
        <img id="maritime-parallax-bg"
             src="{{ $companyHero?->image_url ?? asset('images/backgrounds/global_logistics_export.jpg') }}"
             alt="Tunisian Global Agro-Food Logistics & Maritime Export"
             class="w-full min-w-full h-full min-h-[115vh] object-cover object-center will-change-transform select-none">
    </div>

    <!-- Barre d'Annonce Internationale B2B (Frosted Maritime Glass) -->
    <div class="bg-black/60 backdrop-blur-md text-sm sm:text-base py-3 px-4 border-b border-white/10 text-slate-200 shadow-lg relative z-[110]">
        <div class="max-w-7xl mx-auto flex flex-wrap justify-between items-center gap-3">
            <div class="flex items-center gap-3 font-bold">
                <span class="inline-flex items-center gap-2 text-coolteal">
                    <span class="w-2.5 h-2.5 rounded-full bg-coolteal animate-ping"></span>
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'موسم الحصاد والتصدير 2026 متاح الآن رسمياً' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Récolte 2026 disponible à l\'export international' : '2026 Harvest Now Open for International Export') }}
                </span>
                <span class="hidden sm:inline text-slate-600 font-black">•</span>
                <span class="hidden sm:inline text-slate-300 font-semibold font-mono">Incoterms 2020: FOB, CIF, CFR, EXW</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="https://wa.me/21698000000?text={{ urlencode('Hello Rayanova Export Desk, I would like to inquire about quotations.') }}" target="_blank" class="inline-flex items-center gap-2 text-slate-200 hover:text-steel-300 transition-colors font-bold btn-interactive">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-coolteal fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/></svg>
                    <span>WhatsApp Export Desk</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Header (Sleek Frosted Glass) -->
    <header class="sticky top-0 z-[100] bg-black/60 backdrop-blur-2xl border-b border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.5)] transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-22">
                
                <!-- Logo & Brand Identity -->
                <a href="{{ LaravelLocalization::localizeUrl('/') }}" class="flex items-center gap-3.5 group">
                    <div class="w-13 h-13 rounded-2xl bg-slate-950 border-2 border-slate-700 overflow-hidden flex items-center justify-center shadow-lg group-hover:scale-105 group-hover:border-steel-400 transition-all shrink-0">
                        <img src="{{ asset('images/brand/logo-icon.jpg') }}" alt="Rayanova T.D" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-serif text-2xl sm:text-3xl font-black tracking-wider text-white group-hover:text-steel-300 transition-colors">
                            RAYANOVA <span class="text-steel-400 text-lg font-sans font-black tracking-normal">T.D</span>
                        </span>
                        <span class="text-[11px] sm:text-xs tracking-widest text-slate-400 uppercase font-bold">International Trade & Agro-Export</span>
                    </div>
                </a>

                <!-- Navigation Desktop Links -->
                <nav class="hidden lg:flex items-center gap-8 text-base font-bold text-slate-200">
                    <a href="{{ LaravelLocalization::localizeUrl('/') }}" class="hover:text-steel-400 transition-colors py-1">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الرئيسية' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Accueil' : 'Home') }}
                    </a>
                    <a href="{{ LaravelLocalization::localizeUrl('/#products') }}" class="hover:text-steel-400 transition-colors py-1">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'المنتجات والتعليب' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Produits & Packaging' : 'Products & Packaging') }}
                    </a>
                    <a href="{{ LaravelLocalization::localizeUrl('/#terroir') }}" class="hover:text-steel-400 transition-colors py-1">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'أصالة المنشأ' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Terroirs de Tunisie' : 'Tunisian Terroirs') }}
                    </a>
                    <a href="{{ LaravelLocalization::localizeUrl('/#certifications') }}" class="hover:text-steel-400 transition-colors py-1">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الشهادات والتحاليل' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Certifications & COA' : 'Certifications & Lab') }}
                    </a>
                </nav>

                <!-- Actions Droite : Sélecteur de Langue + Panier Devis -->
                <div class="flex items-center gap-3.5">
                    
                    <!-- Language Selector Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" type="button" class="flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-slate-800/80 hover:bg-slate-800 border border-slate-700 hover:border-steel-400 text-sm font-bold text-slate-200 transition-all shadow-sm cursor-pointer btn-interactive">
                            <span>{{ strtoupper(LaravelLocalization::getCurrentLocale()) }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute {{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'left-0' : 'right-0' }} mt-2 w-44 bg-slate-900 rounded-2xl border border-slate-700 shadow-2xl py-2 z-50">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-800 hover:text-steel-300 transition-colors {{ $localeCode === LaravelLocalization::getCurrentLocale() ? 'font-black text-steel-400 bg-slate-800/80' : 'font-semibold' }}">
                                    <span>{{ $properties['native'] }}</span>
                                    <span class="text-xs text-slate-500 uppercase font-mono">{{ $localeCode }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quote Cart Trigger Button (Electric Blue & Cool Teal) -->
                    <button type="button" 
                            @click="$dispatch('toggle-quote-cart')" 
                            class="relative flex items-center gap-2.5 px-5 sm:px-6 py-3 rounded-xl bg-gradient-to-r from-steel-600 via-steel-500 to-electric hover:from-steel-500 hover:to-steel-400 text-white font-black text-sm sm:text-base border border-steel-400 shadow-xl shadow-steel-600/35 ring-2 ring-steel-400/40 transition-all hover:scale-105 active:scale-95 btn-interactive cursor-pointer"
                            aria-label="View Quote Cart">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span class="hidden sm:inline">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'سلة التسعيرة' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Devis' : 'RFQ Cart') }}</span>
                        <!-- Live Counter Badge -->
                        <span class="w-5 h-5 rounded-full bg-slate-950 text-steel-300 flex items-center justify-center text-xs font-black shadow-md" x-data="{ count: 0 }" x-on:cart-updated.window="count = $event.detail.count" x-text="count">0</span>
                    </button>

                    <!-- Mobile Hamburger Menu Button -->
                    <button type="button" @click="$dispatch('toggle-mobile-menu')" class="lg:hidden p-3 rounded-xl bg-slate-800 border border-slate-700 text-slate-200 shadow-md btn-interactive" aria-label="Toggle Navigation">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                    </button>

                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu Overlay -->
        <div x-data="{ openMenu: false }" 
             x-on:toggle-mobile-menu.window="openMenu = !openMenu" 
             x-show="openMenu" 
             x-transition 
             class="lg:hidden bg-black/95 backdrop-blur-2xl border-b border-white/10 px-6 py-6 space-y-4 shadow-2xl relative z-[105]">
            <a @click="openMenu = false" href="{{ LaravelLocalization::localizeUrl('/') }}" class="block text-base font-bold text-slate-200 hover:text-steel-400">
                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الرئيسية' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Accueil' : 'Home') }}
            </a>
            <a @click="openMenu = false" href="{{ LaravelLocalization::localizeUrl('/#products') }}" class="block text-base font-bold text-slate-200 hover:text-steel-400">
                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'المنتجات والتعليب' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Produits & Packaging' : 'Products & Packaging') }}
            </a>
            <a @click="openMenu = false" href="{{ LaravelLocalization::localizeUrl('/#terroir') }}" class="block text-base font-bold text-slate-200 hover:text-steel-400">
                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'أصالة المنشأ' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Terroirs de Tunisie' : 'Tunisian Terroirs') }}
            </a>
            <a @click="openMenu = false" href="{{ LaravelLocalization::localizeUrl('/#certifications') }}" class="block text-base font-bold text-slate-200 hover:text-steel-400">
                {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الشهادات والتحاليل' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Certifications & COA' : 'Certifications & Lab') }}
            </a>
        </div>
    </header>

    <!-- Main Application Slot -->
    <main class="flex-grow">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <!-- Slide-over / Mobile Bottom-Sheet Quote Cart Component -->
    @livewire('quote-cart')

    <!-- Footer Haute Définition Prestige Rayanova (Frosted Glass Translucide) -->
    <footer class="bg-black/75 backdrop-blur-2xl border-t border-white/15 mt-20 pt-16 pb-12 relative z-[80] overflow-hidden text-slate-200">
        <!-- Footer Ambient Glow -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[200px] bg-gradient-to-b from-steel-500/10 to-transparent rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Footer Accent Line -->
            <div class="section-accent-line w-36 mx-auto mb-12"></div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-14">
                
                <!-- Col 1: Brand Info -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-slate-950 border-2 border-slate-700 overflow-hidden flex items-center justify-center shadow-lg">
                            <img src="{{ asset('images/brand/logo-icon.jpg') }}" alt="Rayanova T.D" class="w-full h-full object-cover">
                        </div>
                        <span class="font-serif text-2xl font-black tracking-wider text-white">RAYANOVA <span class="text-steel-400 text-sm font-sans font-black">T.D</span></span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed font-normal">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                            ? 'شركة تونسية رائدة في تصدير زيت الزيتون البكر الممتاز والمنتجات الغذائية المتوسطية ذات الجودة العالمية.'
                            : (LaravelLocalization::getCurrentLocale() === 'fr' 
                                ? 'Société tunisienne de référence pour l\'exportation d\'huile d\'olive extra vierge et de denrées agroalimentaires méditerranéennes d\'exception.'
                                : 'Premier Tunisian export company specializing in high-grade extra virgin olive oil, Deglet Nour dates, and Mediterranean specialty foods.') }}
                    </p>
                    <div class="text-sm text-slate-300 space-y-1.5 font-medium">
                        <p class="flex items-start gap-2"><span>📍</span> <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'نهج عمر قداح، عمارة مونبليزير، الطابق 2 ب25، 1073 تونس' : 'Rue Omar Kaddah, Imm. Montplaisir, 2e ét. B25, 1073 Tunis' }}</span></p>
                        <p class="text-xs text-slate-500 font-mono tracking-wider">MF: 1975095/B/A/M/000 • RNE: 1975095B</p>
                        <p class="flex items-center gap-2 text-steel-400 font-mono">✉️ export@rayanovatd.com</p>
                    </div>
                </div>

                <!-- Col 2: International Export & Incoterms -->
                <div class="space-y-4">
                    <h4 class="text-sm sm:text-base font-black text-white uppercase tracking-wider">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'التجارة والشحن الدولي' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Export & Logistique' : 'Global Export Logistics') }}</h4>
                    <ul class="text-sm text-slate-400 space-y-2.5 font-medium">
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-steel-400"></span> Sea Freight FCL & LCL (20ft / 40ft)</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-steel-400"></span> Bulk Flexitank (24,000L) & IBC</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-steel-400"></span> Incoterms 2020: FOB, CIF, CFR, EXW</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-steel-400"></span> Air Freight & DHL Sample Boxes</li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-steel-400"></span> Full Custom & Phytosanitary Clearance</li>
                    </ul>
                </div>

                <!-- Col 3: Quality Standards & Accreditations -->
                <div class="space-y-4">
                    <h4 class="text-sm sm:text-base font-black text-white uppercase tracking-wider">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الشهادات والمطابقة' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Certifications & Qualité' : 'Quality & Compliance') }}</h4>
                    <ul class="text-sm text-slate-400 space-y-2.5 font-medium">
                        <li class="flex items-center gap-2"><span class="text-coolteal font-black text-base">✓</span> EU Organic (Ecocert) Certified</li>
                        <li class="flex items-center gap-2"><span class="text-coolteal font-black text-base">✓</span> US FDA Food Facility Registered</li>
                        <li class="flex items-center gap-2"><span class="text-coolteal font-black text-base">✓</span> ISO 22000:2018 & HACCP</li>
                        <li class="flex items-center gap-2"><span class="text-coolteal font-black text-base">✓</span> COI Chemical & Sensory Lab</li>
                        <li class="flex items-center gap-2"><span class="text-coolteal font-black text-base">✓</span> EUR1 & Halal Accreditations</li>
                    </ul>
                </div>

                <!-- Col 4: Private Label & Quick Action -->
                <div class="space-y-4">
                    <h4 class="text-sm sm:text-base font-black text-white uppercase tracking-wider">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'خدمة العلامة الخاصة' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Marque Blanche / OEM' : 'Private Label Solutions') }}</h4>
                    <p class="text-sm text-slate-400 leading-relaxed font-normal">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                            ? 'نوفر التعبئة والتغليف المخصص لكبرى المساحات التجارية وسلاسل التوزيع العالمية.' 
                            : 'Custom packaging, bottle shapes, customized labeling and sealing for international supermarkets and distributors.' }}
                    </p>
                    <a href="https://wa.me/21698000000" target="_blank" class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-500 hover:to-teal-400 text-white text-sm font-bold transition-all duration-300 hover:scale-105 shadow-xl shadow-teal-600/30 border border-teal-300 btn-interactive">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/></svg>
                        <span>Direct WhatsApp B2B Desk</span>
                    </a>
                </div>

            </div>

            <div class="border-t border-slate-800/80 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs sm:text-sm text-slate-500 font-medium gap-4">
                <p>© {{ date('Y') }} Rayanova T.D (Trading & Distribution). All Rights Reserved.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ LaravelLocalization::localizeUrl('/terms') }}" class="hover:text-steel-400 transition-colors">Terms of Export</a>
                    <a href="{{ LaravelLocalization::localizeUrl('/privacy') }}" class="hover:text-steel-400 transition-colors">Privacy & Compliance</a>
                    <a href="{{ route('admin.login') }}" class="text-steel-400 hover:text-steel-300 transition-colors font-bold">Export Desk Portal 🔒</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bouton Flottant WhatsApp Export Desk -->
    <aside aria-label="WhatsApp Export Desk" class="fixed bottom-6 right-6 z-40 group">
        <a href="https://wa.me/21698000000?text={{ urlencode('Hello Rayanova T.D Export Desk, I would like to inquire regarding export quotation.') }}"
           target="_blank" rel="noopener noreferrer"
           class="relative flex items-center gap-3 px-5 py-3.5 rounded-full bg-gradient-to-r from-steel-600 to-coolteal hover:from-steel-500 hover:to-teal-400 text-white font-bold text-sm sm:text-base shadow-2xl shadow-steel-600/40 border border-steel-300 transition-all hover:scale-105 active:scale-95 btn-interactive">
            <span class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-coolteal animate-ping"></span>
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/></svg>
            <span class="hidden sm:inline">WhatsApp Trade Desk</span>
        </a>
    </aside>

    @livewireScripts
</body>
</html>
