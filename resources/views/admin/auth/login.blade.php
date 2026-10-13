<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="min-h-full bg-[#FAF8F5]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.command_center') }} — {{ __('admin.brand_name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Outfit:wght@300;400;500;600;700;800&family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Favicon & Touch Icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#FAF8F5] text-slate-800 {{ app()->getLocale() === 'ar' ? 'font-arabic' : 'font-sans' }} antialiased flex flex-col items-center justify-center p-3 sm:p-4 py-6 relative overflow-y-auto selection:bg-gold-500 selection:text-white">

    <!-- Glowing Background Ambient Glow -->
    <div class="fixed inset-0 pointer-events-none -z-10" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_50%_at_50%_0%,rgba(212,175,55,0.15),transparent_70%)]"></div>
        <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-[radial-gradient(ellipse_90%_40%_at_50%_100%,rgba(27,59,43,0.08),transparent_70%)]"></div>
    </div>

    <!-- Compact Centered Card Container -->
    <div class="w-full max-w-[410px] relative z-10 space-y-3.5 my-auto">
        
        <!-- Top Utility Row (Back to Portal & Language Switcher) -->
        <div class="flex items-center justify-between px-1">
            <a href="{{ LaravelLocalization::localizeUrl('/') }}" 
               class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-500 hover:text-slate-900 transition-colors">
                <span class="text-xs">←</span> {{ __('admin.open_vitrine') }}
            </a>

            <!-- Compact Language Switcher -->
            <div class="p-0.5 rounded-xl bg-white/90 backdrop-blur-md border border-gold-300/50 shadow-sm flex items-center gap-0.5 text-[11px] font-bold">
                <a href="{{ route('admin.setLocale', 'ar') }}"
                   class="py-0.5 px-2 rounded-lg transition-all {{ app()->getLocale() === 'ar' ? 'bg-gold-500 text-slate-950 shadow-xs' : 'text-slate-600 hover:text-slate-950' }}">
                    🇸🇦 ع
                </a>
                <a href="{{ route('admin.setLocale', 'en') }}"
                   class="py-0.5 px-2 rounded-lg transition-all {{ app()->getLocale() === 'en' ? 'bg-gold-500 text-slate-950 shadow-xs' : 'text-slate-600 hover:text-slate-950' }}">
                    🇬🇧 EN
                </a>
                <a href="{{ route('admin.setLocale', 'fr') }}"
                   class="py-0.5 px-2 rounded-lg transition-all {{ app()->getLocale() === 'fr' ? 'bg-gold-500 text-slate-950 shadow-xs' : 'text-slate-600 hover:text-slate-950' }}">
                    🇫🇷 FR
                </a>
            </div>
        </div>

        <!-- Refined Brand Header (Logo Never Cut Off) -->
        <div class="text-center space-y-1.5">
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-slate-950 border border-gold-400/60 mx-auto overflow-hidden shadow-lg shadow-gold-500/20 ring-2 ring-gold-300/40 p-0.5">
                <img src="{{ asset('images/brand/logo-rayanova-td.jpg') }}" alt="Rayanova T.D" class="w-full h-full object-cover rounded-[14px]">
            </div>
            <div>
                <h1 class="font-serif text-lg sm:text-xl font-bold tracking-widest text-slate-900 leading-tight">
                    RAYANOVA <span class="text-gold-600 font-sans font-bold text-sm">T.D</span>
                </h1>
                <p class="text-[10.5px] font-bold text-gold-800 tracking-wider uppercase mt-0.5">
                    {{ __('admin.login_badge') }}
                </p>
            </div>
        </div>

        <!-- Compact Luxury Login Card -->
        <div class="bg-white/95 backdrop-blur-xl rounded-2xl p-5 sm:p-6 border border-gold-300/60 shadow-xl shadow-gold-950/5 ring-1 ring-gold-200/50 space-y-4">
            
            <div class="border-b border-gold-300/30 pb-2.5">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wide">{{ __('admin.login_title') }}</h2>
                <p class="text-xs text-slate-500 mt-0.5 leading-snug">{{ __('admin.login_subtitle') }}</p>
            </div>

            @if($errors->any())
                <div class="p-2.5 rounded-xl bg-red-50 border border-red-300 text-red-950 text-xs font-semibold flex items-center gap-2">
                    <span>⚠️</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-3.5">
                @csrf

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">{{ __('admin.staff_email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="admin@rayanovatd.com"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-amber-50/30 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm font-medium focus:border-gold-500 focus:bg-white focus:ring-2 focus:ring-gold-400/20 focus:outline-none transition-all">
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700">{{ __('admin.desk_password') }}</label>
                    <input type="password" name="password" required
                           placeholder="••••••••••••"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-amber-50/30 border border-slate-200 text-slate-900 placeholder-slate-400 text-sm font-medium focus:border-gold-500 focus:bg-white focus:ring-2 focus:ring-gold-400/20 focus:outline-none transition-all">
                </div>

                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600 text-xs font-medium">
                        <input type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-slate-300 text-gold-600 focus:ring-gold-400">
                        <span>{{ __('admin.remember_session') }}</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 sm:py-3 rounded-xl bg-gradient-to-r from-gold-500 via-gold-400 to-gold-500 hover:from-gold-400 hover:to-gold-300 text-slate-950 text-xs sm:text-sm font-black uppercase tracking-wider shadow-md hover:shadow-lg ring-1 ring-gold-300/60 transition-all cursor-pointer">
                    {{ __('admin.sign_in_btn') }}
                </button>
            </form>

            <div class="pt-2.5 border-t border-gold-300/30 text-center text-[10.5px] font-semibold text-slate-400 flex items-center justify-center gap-1.5">
                <span>🔒 {{ __('admin.security_footer') }}</span>
            </div>
        </div>

    </div>

</body>
</html>
