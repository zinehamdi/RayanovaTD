<!DOCTYPE html>
<html lang="{{ LaravelLocalization::getCurrentLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}" class="no-js">
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

    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

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

    <!-- Google Fonts Preconnect & Direct Non-blocking Link -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&family=Cinzel:wght@500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="text-rn-text-1 font-sans antialiased min-h-screen selection:bg-rn-gold selection:text-black flex flex-col relative overflow-x-clip bg-rn-bg-0 text-base">

    <!-- Global Multimodal Logistics Fullscreen Background Stage -->
    <div class="fixed inset-0 pointer-events-none -z-30 overflow-hidden" aria-hidden="true">
        <img id="maritime-parallax-bg"
             src="{{ $companyHero?->image_url ?? asset('images/backgrounds/global_logistics_export.jpg') }}"
             alt=""
             fetchpriority="high"
             decoding="async"
             class="w-full min-w-full h-full min-h-[115vh] object-cover object-center will-change-transform select-none">
    </div>

    @php
        $rawPhone = $companyHero?->whatsapp_number ?? '21698000000';
        $whatsappDigits = preg_replace('/[^0-9]/', '', $rawPhone) ?: '21698000000';
    @endphp

    <!-- Barre d'Annonce Internationale B2B (Slim non-sticky announcement) -->
    <div class="bg-rn-bg-0/90 backdrop-blur-md text-xs sm:text-sm py-2 px-4 sm:px-8 lg:px-12 border-b border-rn-line/10 text-rn-text-2 relative z-[110]">
        <div class="max-w-wide mx-auto flex flex-wrap justify-between items-center gap-3">
            <div class="flex items-center gap-2.5 font-semibold">
                <span class="inline-flex items-center gap-2 text-rn-gold">
                    <span class="w-2 h-2 rounded-full bg-rn-gold"></span>
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'موسم الحصاد والتصدير 2026 متاح الآن رسمياً' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Récolte 2026 disponible à l\'export international' : '2026 Harvest Now Open for International Export') }}
                </span>
                <span class="hidden sm:inline text-rn-line/20">•</span>
                <span class="hidden sm:inline text-rn-text-3 font-normal">Incoterms 2020: FOB, CIF, CFR, EXW</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('catalog.download_pdf', ['locale' => LaravelLocalization::getCurrentLocale(), 'lang' => LaravelLocalization::getCurrentLocale()]) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-amber-400 hover:text-amber-300 transition-colors font-semibold">
                    <span>📄</span>
                    <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'كتالوج المنتجات PDF' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Catalogue B2B PDF' : 'B2B Catalog PDF') }}</span>
                </a>
                <span class="text-rn-line/20">•</span>
                <a href="https://wa.me/{{ $whatsappDigits }}?text={{ urlencode('Hello Rayanova Export Desk, I would like to inquire about quotations.') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-rn-text-2 hover:text-white transition-colors font-medium">
                    <svg class="w-3.5 h-3.5 text-emerald-400 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/></svg>
                    <span>WhatsApp Export Desk</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Header (72px target, frosted glass, horizontal logo, quiet RFQ button) -->
    <header x-data="{ scrolled: false, openMenu: false }" 
            @scroll.window.passive="scrolled = (window.pageYOffset || document.documentElement.scrollTop) > 40"
            x-effect="document.documentElement.classList.toggle('overflow-hidden', openMenu)"
            class="sticky top-0 z-[100] transition-colors duration-300 border-b border-rn-line/10"
            :class="scrolled ? 'rn-glass-strong shadow-lg shadow-black/40' : 'rn-glass-dark'">
        <div class="max-w-wide w-full mx-auto px-4 sm:px-8 lg:px-12">
            <div class="flex items-center justify-between min-h-[72px] py-2">
                
                <!-- Logo & Brand Identity (Horizontal lockup, 40px icon) -->
                <a href="{{ LaravelLocalization::localizeUrl('/') }}" class="flex items-center gap-3 group shrink-0 py-1" aria-label="Rayanova T.D Home">
                    <div class="w-10 h-10 rounded-xl bg-slate-900 border border-rn-line/20 overflow-hidden flex items-center justify-center shadow-md group-hover:border-rn-gold transition-colors shrink-0">
                        <img src="{{ asset('images/brand/logo-icon.jpg') }}" alt="Rayanova T.D" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col">
                        <span class="font-serif text-sm font-bold tracking-widest text-white group-hover:text-rn-gold-soft transition-colors uppercase leading-none">
                            RAYANOVA <span class="text-rn-gold text-xs font-sans font-bold">T.D</span>
                        </span>
                        <span class="text-[10px] text-rn-text-3 font-sans tracking-wider hidden sm:block mt-0.5">TUNISIAN EXPORT DESK</span>
                    </div>
                </a>

                <!-- Navigation Desktop Links (With #export link & gold active indicator) -->
                <nav class="hidden lg:flex flex-1 items-center justify-center max-w-4xl mx-6 xl:mx-8 gap-1 xl:gap-3 text-[15px] font-medium text-rn-text-2">
                    <a href="{{ LaravelLocalization::localizeUrl('/') }}" class="nav-link hover:text-rn-text-1 hover:bg-white/5 transition-all px-3.5 py-1.5 rounded-lg whitespace-nowrap">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الرئيسية' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Accueil' : 'Home') }}
                    </a>
                    <a href="{{ LaravelLocalization::localizeUrl('/#products') }}" class="nav-link hover:text-rn-text-1 hover:bg-white/5 transition-all px-3.5 py-1.5 rounded-lg whitespace-nowrap">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'المنتجات والتعليب' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Produits & Packaging' : 'Products & Packaging') }}
                    </a>
                    <a href="{{ LaravelLocalization::localizeUrl('/#export') }}" class="nav-link hover:text-rn-text-1 hover:bg-white/5 transition-all px-3.5 py-1.5 rounded-lg whitespace-nowrap">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'البنية التحتية التصديرية' : 'Export Architecture' }}
                    </a>
                    <a href="{{ LaravelLocalization::localizeUrl('/#terroir') }}" class="nav-link hover:text-rn-text-1 hover:bg-white/5 transition-all px-3.5 py-1.5 rounded-lg whitespace-nowrap">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'مسار التتبع والمنشأ' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Circuit Traçabilité' : 'Traceability Route') }}
                    </a>
                    <a href="{{ LaravelLocalization::localizeUrl('/#certifications') }}" class="nav-link hover:text-rn-text-1 hover:bg-white/5 transition-all px-3.5 py-1.5 rounded-lg whitespace-nowrap">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الشهادات والتحاليل' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Certifications & COA' : 'Certifications & Lab') }}
                    </a>
                </nav>

                <!-- Actions Right: Language Switcher + Secondary RFQ Cart Button -->
                <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                    
                    <!-- Language Selector Dropdown (44px min-height, rn-glass-light) -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" type="button" class="h-11 flex items-center gap-1.5 px-3 rounded-xl rn-glass-light hover:border-rn-line/30 text-xs sm:text-sm font-semibold text-rn-text-1 transition-all cursor-pointer">
                            <span>{{ strtoupper(LaravelLocalization::getCurrentLocale()) }}</span>
                            <svg class="w-3.5 h-3.5 text-rn-text-3 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="absolute {{ LaravelLocalization::getCurrentLocaleDirection() === 'rtl' ? 'left-0' : 'right-0' }} mt-2 w-40 rn-glass-strong rounded-xl border border-rn-line/14 shadow-2xl py-1.5 z-50">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="flex items-center justify-between px-3.5 py-2.5 min-h-[44px] text-xs sm:text-sm text-rn-text-2 hover:bg-white/10 hover:text-white transition-colors {{ $localeCode === LaravelLocalization::getCurrentLocale() ? 'font-bold text-rn-gold bg-white/5' : 'font-medium' }}">
                                    <span>{{ $properties['native'] }}</span>
                                    <span class="text-[11px] text-rn-text-3 uppercase font-mono">{{ $localeCode }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quote Cart Trigger Button (Quieter secondary glass style + gold counter badge, 44px height) -->
                    <button type="button" 
                            @click="$dispatch('toggle-quote-cart')" 
                            class="relative h-11 flex items-center gap-2 px-3.5 sm:px-4 rounded-xl rn-glass-light hover:border-rn-line/30 text-rn-text-1 font-semibold text-xs sm:text-sm transition-all hover:bg-rn-bg-2/60 cursor-pointer"
                            aria-label="View Quote Cart">
                        <svg class="w-4 h-4 text-rn-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <span class="hidden sm:inline">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'سلة التسعيرة' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Devis' : 'RFQ Cart') }}</span>
                        <!-- Live Counter Badge -->
                        <span class="min-w-[20px] h-5 px-1.5 rounded-full bg-rn-gold text-rn-bg-0 flex items-center justify-center text-[11px] font-bold shadow-sm" x-data="{ count: 0 }" x-on:cart-updated.window="count = $event.detail.count" x-text="count">0</span>
                    </button>

                    <!-- Mobile Hamburger Menu Button (44x44 target) -->
                    <button type="button" 
                            @click="openMenu = !openMenu" 
                            :aria-expanded="openMenu"
                            aria-controls="mobile-navigation-sheet"
                            class="lg:hidden h-11 w-11 flex items-center justify-center rounded-xl rn-glass-light border border-rn-line/20 text-rn-text-1 shadow-sm cursor-pointer shrink-0" 
                            aria-label="Toggle Navigation">
                        <svg x-show="!openMenu" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                        <svg x-show="openMenu" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu Full Sheet Overlay -->
        <div id="mobile-navigation-sheet"
             x-show="openMenu" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             @keydown.escape.window="openMenu = false"
             class="lg:hidden fixed inset-x-0 top-[73px] bottom-0 rn-glass-strong border-b border-rn-line/10 px-6 py-8 flex flex-col justify-between shadow-2xl z-40 overflow-y-auto">
            <nav class="space-y-4 text-lg font-semibold text-rn-text-1">
                <a @click="openMenu = false" href="{{ LaravelLocalization::localizeUrl('/') }}" class="block py-2 border-b border-rn-line/10 hover:text-rn-gold transition-colors">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الرئيسية' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Accueil' : 'Home') }}
                </a>
                <a @click="openMenu = false" href="{{ LaravelLocalization::localizeUrl('/#products') }}" class="block py-2 border-b border-rn-line/10 hover:text-rn-gold transition-colors">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'المنتجات والتعليب' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Produits & Packaging' : 'Products & Packaging') }}
                </a>
                <a @click="openMenu = false" href="{{ LaravelLocalization::localizeUrl('/#export') }}" class="block py-2 border-b border-rn-line/10 hover:text-rn-gold transition-colors">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'البنية التحتية التصديرية' : 'Export Architecture' }}
                </a>
                <a @click="openMenu = false" href="{{ LaravelLocalization::localizeUrl('/#terroir') }}" class="block py-2 border-b border-rn-line/10 hover:text-rn-gold transition-colors">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'مسار التتبع والمنشأ' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Circuit Traçabilité' : 'Traceability Route') }}
                </a>
                <a @click="openMenu = false" href="{{ LaravelLocalization::localizeUrl('/#certifications') }}" class="block py-2 border-b border-rn-line/10 hover:text-rn-gold transition-colors">
                    {{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الشهادات والتحاليل' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Certifications & COA' : 'Certifications & Lab') }}
                </a>
            </nav>
            <div class="pt-6 border-t border-rn-line/10 space-y-4">
                <!-- Locales row -->
                <div class="flex items-center gap-2">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" 
                           class="flex-1 text-center py-2.5 rounded-xl border text-sm font-semibold transition-colors {{ $localeCode === LaravelLocalization::getCurrentLocale() ? 'bg-rn-gold/15 border-rn-gold text-rn-gold' : 'rn-glass-light border-rn-line/10 text-rn-text-2' }}">
                            {{ $properties['native'] }}
                        </a>
                    @endforeach
                </div>
                <!-- WhatsApp Desk -->
                <a href="https://wa.me/{{ $whatsappDigits }}?text={{ urlencode('Hello Rayanova Export Desk, I would like to inquire about quotations.') }}" 
                   target="_blank" rel="noopener noreferrer" 
                   class="w-full flex items-center justify-center gap-2 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm transition-colors shadow-lg">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/></svg>
                    <span>WhatsApp Export Desk</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Application Slot -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Slide-over / Mobile Bottom-Sheet Quote Cart Component -->
    @livewire('quote-cart')

    <!-- Footer Haute Définition Prestige Rayanova (Solid Background) -->
    <footer class="bg-rn-bg-0 border-t border-rn-line/10 mt-20 pt-16 pb-12 relative z-[80] overflow-hidden text-rn-text-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-14">
                
                <!-- Col 1: Brand Info -->
                <div class="space-y-4 md:col-span-1">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-slate-900 border border-rn-line/20 overflow-hidden flex items-center justify-center shadow-lg">
                            <img src="{{ asset('images/brand/logo-icon.jpg') }}" alt="Rayanova T.D" class="w-full h-full object-cover">
                        </div>
                        <span class="font-serif text-2xl font-bold tracking-wider text-white">RAYANOVA <span class="text-rn-gold text-sm font-sans font-bold">T.D</span></span>
                    </div>
                    <p class="text-sm sm:text-base text-rn-text-2 leading-relaxed font-normal">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                            ? 'شركة تونسية رائدة في تصدير زيت الزيتون البكر الممتاز والمنتجات الغذائية المتوسطية ذات الجودة العالمية.'
                            : (LaravelLocalization::getCurrentLocale() === 'fr' 
                                ? 'Société tunisienne de référence pour l\'exportation d\'huile d\'olive extra vierge et de denrées agroalimentaires méditerranéennes d\'exception.'
                                : 'Premier Tunisian export company specializing in high-grade extra virgin olive oil, Deglet Nour dates, and Mediterranean specialty foods.') }}
                    </p>
                    <div class="text-sm sm:text-base text-rn-text-2 space-y-2">
                        <p class="flex items-start gap-2"><span>📍</span> <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'نهج عمر قداح، عمارة مونبليزير، الطابق 2 ب25، 1073 تونس' : 'Rue Omar Kaddah, Imm. Montplaisir, 2e ét. B25, 1073 Tunis' }}</span></p>
                        <p class="text-xs text-rn-text-3 font-mono tracking-wider">MF: 1975095/B/A/M/000 • RNE: 1975095B</p>
                        <p class="flex items-center gap-2 text-rn-cyan">✉️ export@rayanovatd.com</p>
                    </div>
                </div>

                <!-- Col 2: International Export & Incoterms -->
                <div class="space-y-4">
                    <h4 class="text-sm sm:text-base font-bold text-white uppercase tracking-wider">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'التجارة والشحن الدولي' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Export & Logistique' : 'Global Export Logistics') }}</h4>
                    <ul class="text-sm sm:text-base text-rn-text-2 space-y-2.5">
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-rn-gold"></span> Sea Freight FCL & LCL (20ft / 40ft)</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-rn-gold"></span> Bulk Flexitank (24,000L) & IBC</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-rn-gold"></span> Incoterms 2020: FOB, CIF, CFR, EXW</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-rn-gold"></span> Air Freight & Sample Boxes</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-rn-gold"></span> Full Custom & Phytosanitary Clearance</li>
                    </ul>
                </div>

                <!-- Col 3: Quality Standards & Accreditations -->
                <div class="space-y-4">
                    <h4 class="text-sm sm:text-base font-bold text-white uppercase tracking-wider">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'الشهادات والمطابقة' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Certifications & Qualité' : 'Quality & Compliance') }}</h4>
                    <ul class="text-sm sm:text-base text-rn-text-2 space-y-2.5">
                        <li class="flex items-center gap-2"><span class="text-rn-success font-bold text-base">✓</span> EU Organic (Ecocert) Certified</li>
                        <li class="flex items-center gap-2"><span class="text-rn-success font-bold text-base">✓</span> US FDA Food Facility Registered</li>
                        <li class="flex items-center gap-2"><span class="text-rn-success font-bold text-base">✓</span> ISO 22000:2018 & HACCP</li>
                        <li class="flex items-center gap-2"><span class="text-rn-success font-bold text-base">✓</span> COI Chemical & Sensory Lab</li>
                        <li class="flex items-center gap-2"><span class="text-rn-success font-bold text-base">✓</span> EUR1 & Halal Accreditations</li>
                    </ul>
                </div>

                <!-- Col 4: Private Label & Quick Action -->
                <div class="space-y-4">
                    <h4 class="text-sm sm:text-base font-bold text-white uppercase tracking-wider">{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'خدمة العلامة الخاصة' : (LaravelLocalization::getCurrentLocale() === 'fr' ? 'Marque Blanche / OEM' : 'Private Label Solutions') }}</h4>
                    <p class="text-sm sm:text-base text-rn-text-2 leading-relaxed font-normal">
                        {{ LaravelLocalization::getCurrentLocale() === 'ar' 
                            ? 'نوفر التعبئة والتغليف المخصص لكبرى المساحات التجارية وسلاسل التوزيع العالمية.' 
                            : 'Custom packaging, bottle shapes, customized labeling and sealing for international supermarkets and distributors.' }}
                    </p>
                    <a href="https://wa.me/{{ $whatsappDigits }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold transition-all duration-300 shadow-lg border border-emerald-400/30">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/></svg>
                        <span>Direct WhatsApp B2B Desk</span>
                    </a>
                </div>

            </div>

            <div class="border-t border-rn-line/10 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs sm:text-sm text-rn-text-3 font-normal gap-4">
                <p>© {{ date('Y') }} Rayanova T.D (Trading & Distribution). All Rights Reserved.</p>
                <div class="flex items-center gap-6">
                    <a href="{{ LaravelLocalization::localizeUrl('/terms') }}" class="hover:text-white transition-colors">Terms of Export</a>
                    <a href="{{ LaravelLocalization::localizeUrl('/privacy') }}" class="hover:text-white transition-colors">Privacy & Compliance</a>
                    <a href="{{ route('admin.login') }}" class="text-rn-gold hover:text-white transition-colors font-medium">Export Desk Portal 🔒</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bouton Flottant WhatsApp Export Desk (RTL-aware end-6, safe-area-inset-bottom, compact on hero) -->
    <aside aria-label="WhatsApp Export Desk" 
           x-data="{ compact: false }"
           @rn-scene-change.window="compact = ($event.detail && $event.detail.id === 'hero')"
           class="fixed bottom-[calc(1.5rem+env(safe-area-inset-bottom,0px))] end-6 z-40 group">
        <a href="https://wa.me/{{ $whatsappDigits }}?text={{ urlencode('Hello Rayanova T.D Export Desk, I would like to inquire regarding export quotation.') }}"
           target="_blank" rel="noopener noreferrer"
           class="relative flex items-center gap-2 px-3.5 py-3 sm:px-4 sm:py-3 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm shadow-xl shadow-black/50 border border-emerald-400/40 transition-all hover:scale-105 active:scale-95 focus-visible:outline-2 focus-visible:outline-rn-cyan">
            <svg class="w-5 h-5 text-white shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.144.39-.086s1.011.477 1.184.564.289.13.332.202c.043.072.043.419-.101.824z"/></svg>
            <span class="hidden sm:inline" :class="compact ? 'sm:hidden' : ''">WhatsApp Trade Desk</span>
        </a>
    </aside>

    @livewireScripts
</body>
</html>
