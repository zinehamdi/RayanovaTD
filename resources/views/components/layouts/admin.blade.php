<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full bg-[#F8F5EE]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? __('admin.brand_name') . ' — ' . __('admin.command_center') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Outfit:wght@300;400;500;600;700;800&family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Styles & Scripts Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#F8F5EE] text-slate-800 {{ app()->getLocale() === 'ar' ? 'font-arabic' : 'font-sans' }} antialiased overflow-x-hidden selection:bg-gold-500 selection:text-white">

    <!-- Vivid Warm Ambient Glow -->
    <div class="fixed inset-0 pointer-events-none -z-10" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_50%_-10%,rgba(212,175,55,0.12),transparent_70%)]"></div>
        <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-[radial-gradient(ellipse_90%_50%_at_50%_100%,rgba(16,185,129,0.06),transparent_70%)]"></div>
    </div>

    <div class="min-h-full flex flex-col md:flex-row">

        <!-- Sidebar Navigation (Imperial Emerald & Gold) -->
        <aside class="w-full md:w-80 bg-gradient-to-b from-[#0B251A] via-[#0E3324] to-[#081C13] border-r-2 rtl:border-r-0 rtl:border-l-2 border-gold-500/30 p-6 md:p-8 flex flex-col justify-between shrink-0 shadow-2xl relative z-20 text-white">
            <div class="space-y-6">
                
                <!-- Logo & Brand Header -->
                <div class="flex items-center gap-3.5 pb-5 border-b border-gold-500/20">
                    <div class="w-12 h-12 rounded-2xl bg-black border-2 border-gold-400/80 overflow-hidden flex items-center justify-center shadow-xl shadow-gold-500/20 ring-2 ring-gold-300/40 shrink-0">
                        <img src="{{ asset('images/brand/logo-icon.jpg') }}" alt="Rayanova T.D" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <span class="block font-serif text-lg font-bold tracking-wider text-white">{{ __('admin.brand_name') }}</span>
                        <span class="block text-xs tracking-widest uppercase text-gold-300 font-bold">{{ __('admin.command_center') }}</span>
                    </div>
                </div>

                <!-- Language Switcher (AR / EN / FR) -->
                <div class="p-1.5 rounded-2xl bg-black/40 border border-gold-500/30 flex items-center justify-between gap-1 shadow-inner">
                    <a href="{{ route('admin.setLocale', 'ar') }}"
                       class="flex-1 py-1.5 px-2 rounded-xl text-center text-xs font-black transition-all {{ app()->getLocale() === 'ar' ? 'bg-gold-500 text-slate-950 shadow-md ring-1 ring-gold-300' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        🇸🇦 العربية
                    </a>
                    <a href="{{ route('admin.setLocale', 'en') }}"
                       class="flex-1 py-1.5 px-2 rounded-xl text-center text-xs font-black transition-all {{ app()->getLocale() === 'en' ? 'bg-gold-500 text-slate-950 shadow-md ring-1 ring-gold-300' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        🇬🇧 EN
                    </a>
                    <a href="{{ route('admin.setLocale', 'fr') }}"
                       class="flex-1 py-1.5 px-2 rounded-xl text-center text-xs font-black transition-all {{ app()->getLocale() === 'fr' ? 'bg-gold-500 text-slate-950 shadow-md ring-1 ring-gold-300' : 'text-slate-300 hover:text-white hover:bg-white/10' }}">
                        🇫🇷 FR
                    </a>
                </div>

                <!-- Navigation Links -->
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm md:text-base font-bold transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-gold-500 to-gold-400 text-slate-950 shadow-lg shadow-gold-500/30 ring-1 ring-gold-300' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                        <span class="text-xl">📊</span>
                        <span>{{ __('admin.overview_kpis') }}</span>
                    </a>

                    <a href="{{ route('admin.quotations.index') }}" class="flex items-center justify-between px-4 py-3.5 rounded-2xl text-sm md:text-base font-bold transition-all {{ request()->routeIs('admin.quotations.*') ? 'bg-gradient-to-r from-gold-500 to-gold-400 text-slate-950 shadow-lg shadow-gold-500/30 ring-1 ring-gold-300' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                        <div class="flex items-center gap-3.5">
                            <span class="text-xl">📜</span>
                            <span>{{ __('admin.deals_quotations') }}</span>
                        </div>
                        @php
                            $pendingCount = \App\Models\QuotationRequest::where('status', 'new')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black {{ request()->routeIs('admin.quotations.*') ? 'bg-slate-950 text-gold-300' : 'bg-amber-400 text-slate-950 shadow-md' }}">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm md:text-base font-bold transition-all {{ request()->routeIs('admin.products.*') ? 'bg-gradient-to-r from-gold-500 to-gold-400 text-slate-950 shadow-lg shadow-gold-500/30 ring-1 ring-gold-300' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                        <span class="text-xl">📦</span>
                        <span>{{ __('admin.products_catalog_cms') }}</span>
                    </a>

                    <a href="{{ route('admin.company_hero.edit') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm md:text-base font-bold transition-all {{ request()->routeIs('admin.company_hero.*') ? 'bg-gradient-to-r from-gold-500 to-gold-400 text-slate-950 shadow-lg shadow-gold-500/30 ring-1 ring-gold-300' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                        <span class="text-xl">🖼️</span>
                        <span>{{ __('admin.company_hero_cms') ?? 'صورة الخلفية والواجهة (Hero & Background)' }}</span>
                    </a>

                    <a href="{{ route('admin.slides.index') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm md:text-base font-bold transition-all {{ request()->routeIs('admin.slides.*') ? 'bg-gradient-to-r from-gold-500 to-gold-400 text-slate-950 shadow-lg shadow-gold-500/30 ring-1 ring-gold-300' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                        <span class="text-xl">🖼️</span>
                        <span>{{ __('admin.hero_slider_cms') }}</span>
                    </a>

                    @if(auth()->user()->hasRole('super_admin'))
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm md:text-base font-bold transition-all {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-gold-500 to-gold-400 text-slate-950 shadow-lg shadow-gold-500/30 ring-1 ring-gold-300' : 'text-slate-200 hover:text-white hover:bg-white/10' }}">
                            <span class="text-xl">👥</span>
                            <span>{{ __('admin.team_administrators') ?? 'فريق العمل والمشرفين' }}</span>
                        </a>
                    @endif

                    <a href="{{ url('/') }}" target="_blank" class="flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm md:text-base font-semibold text-gold-300 hover:text-gold-200 hover:bg-white/10 transition-all border border-gold-500/20 mt-4">
                        <span class="text-xl">🌐</span>
                        <span>{{ __('admin.open_vitrine') }}</span>
                    </a>
                </nav>
            </div>

            <!-- Profile & Logout Footer -->
            <div class="pt-6 border-t border-gold-500/20 space-y-4">
                <a href="{{ route('admin.profile.edit') }}" 
                   class="flex items-center justify-between p-2.5 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 transition-all cursor-pointer group"
                   title="{{ __('admin.profile_settings') ?? 'تغيير كلمة المرور والملف الشخصي' }}">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-gold-400 to-gold-600 text-slate-950 font-black text-sm flex items-center justify-center shadow-md shrink-0 group-hover:ring-2 group-hover:ring-gold-400">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <span class="block text-sm font-bold text-white truncate group-hover:text-gold-300 transition-colors">{{ auth()->user()->name ?? 'Administrator' }}</span>
                            <span class="block text-xs font-semibold {{ auth()->user()->hasRole('super_admin') ? 'text-gold-300' : 'text-emerald-300' }}">
                                {{ auth()->user()->hasRole('super_admin') ? __('admin.super_admin_role') : __('admin.sales_officer_role') }}
                            </span>
                        </div>
                    </div>
                    <span class="text-xs text-gold-400 font-bold px-2 py-1 rounded-lg bg-gold-500/10 border border-gold-500/20 group-hover:bg-gold-500 group-hover:text-slate-950 transition-all">
                        ⚙️ {{ __('admin.edit') ?? 'تعديل' }}
                    </span>
                </a>


                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-red-500/20 hover:bg-red-500/30 border border-red-400/40 text-red-200 text-sm font-bold transition-all shadow-sm cursor-pointer">
                        <span>{{ __('admin.sign_out') }}</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Workspace Area -->
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto bg-[#F8F5EE]">
            
            <!-- Topbar with Flash Messages -->
            @if(session('success'))
                <div class="m-6 p-4 rounded-2xl bg-emerald-100 border-2 border-emerald-500 text-emerald-950 text-sm md:text-base font-bold flex items-center justify-between shadow-md">
                    <span>✅ {{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="m-6 p-4 rounded-2xl bg-red-100 border-2 border-red-500 text-red-950 text-sm md:text-base font-bold flex items-center justify-between shadow-md">
                    <span>⚠️ {{ session('error') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="m-6 p-4 rounded-2xl bg-blue-100 border-2 border-blue-500 text-blue-950 text-sm md:text-base font-bold flex items-center justify-between shadow-md">
                    <span>ℹ️ {{ session('info') }}</span>
                </div>
            @endif

            <div class="p-4 sm:p-6 md:p-8 lg:p-10 w-full max-w-[1600px] mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>

</body>
</html>
