<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="h-full bg-[#FEFCF7]">
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
<body class="h-full bg-[#FEFCF7] text-slate-800 {{ app()->getLocale() === 'ar' ? 'font-arabic' : 'font-sans' }} antialiased flex items-center justify-center p-4 sm:p-6 relative overflow-hidden selection:bg-gold-500 selection:text-white">

    <!-- Glowing Background Ambient Glow -->
    <div class="fixed inset-0 pointer-events-none -z-10" aria-hidden="true">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_50%_-10%,rgba(212,175,55,0.18),transparent_70%)]"></div>
        <div class="absolute bottom-0 left-0 right-0 h-1/2 bg-[radial-gradient(ellipse_90%_50%_at_50%_100%,rgba(16,185,129,0.10),transparent_70%)]"></div>
    </div>

    <div class="w-full max-w-lg relative z-10 space-y-6">
        
        <!-- Language Switcher on Login -->
        <div class="flex items-center justify-center gap-2">
            <div class="p-1 rounded-2xl bg-white/80 backdrop-blur-md border border-gold-300/60 shadow-md flex items-center gap-1">
                <a href="{{ route('admin.setLocale', 'ar') }}"
                   class="py-1 px-3 rounded-xl text-xs font-black transition-all {{ app()->getLocale() === 'ar' ? 'bg-gold-500 text-slate-950 shadow-sm' : 'text-slate-700 hover:text-slate-950' }}">
                    🇸🇦 العربية
                </a>
                <a href="{{ route('admin.setLocale', 'en') }}"
                   class="py-1 px-3 rounded-xl text-xs font-black transition-all {{ app()->getLocale() === 'en' ? 'bg-gold-500 text-slate-950 shadow-sm' : 'text-slate-700 hover:text-slate-950' }}">
                    🇬🇧 English
                </a>
                <a href="{{ route('admin.setLocale', 'fr') }}"
                   class="py-1 px-3 rounded-xl text-xs font-black transition-all {{ app()->getLocale() === 'fr' ? 'bg-gold-500 text-slate-950 shadow-sm' : 'text-slate-700 hover:text-slate-950' }}">
                    🇫🇷 Français
                </a>
            </div>
        </div>

        <!-- Brand Header -->
        <div class="text-center space-y-3">
            <div class="w-24 h-24 rounded-3xl bg-black border-2 border-gold-400 mx-auto overflow-hidden shadow-2xl shadow-gold-500/30 ring-4 ring-gold-300/50">
                <img src="{{ asset('images/brand/logo-rayanova-td.jpg') }}" alt="Rayanova T.D" class="w-full h-full object-cover">
            </div>
            <h1 class="font-serif text-3xl sm:text-4xl font-extrabold tracking-wider text-slate-900">{{ __('admin.brand_name') }}</h1>
            <p class="text-sm text-gold-700 uppercase tracking-widest font-bold">{{ __('admin.login_badge') }}</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl p-8 sm:p-10 border-2 border-gold-400/40 shadow-2xl shadow-gold-500/15 ring-1 ring-gold-200/50 space-y-6">
            
            <div class="border-b border-gold-400/20 pb-4">
                <h2 class="text-lg sm:text-xl font-bold text-slate-900 uppercase tracking-wider">{{ __('admin.login_title') }}</h2>
                <p class="text-sm text-slate-600 mt-1">{{ __('admin.login_subtitle') }}</p>
            </div>

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-red-100 border-2 border-red-500 text-red-950 text-sm font-bold flex items-center gap-2">
                    <span>⚠️</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-800">{{ __('admin.staff_email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="admin@rayanovatd.com"
                           class="w-full px-4 py-3.5 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 placeholder-slate-400 text-base font-medium focus:border-gold-500 focus:bg-white focus:ring-4 focus:ring-gold-400/20 focus:outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-800">{{ __('admin.desk_password') }}</label>
                    <input type="password" name="password" required
                           placeholder="••••••••••••"
                           class="w-full px-4 py-3.5 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 placeholder-slate-400 text-base font-medium focus:border-gold-500 focus:bg-white focus:ring-4 focus:ring-gold-400/20 focus:outline-none transition-all">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2.5 cursor-pointer text-slate-700 text-sm font-semibold">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-2 border-slate-300 text-gold-600 focus:ring-gold-400">
                        <span>{{ __('admin.remember_session') }}</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-4 rounded-2xl bg-gradient-to-r from-gold-500 via-gold-400 to-gold-500 hover:from-gold-400 hover:to-gold-300 text-slate-950 text-base font-black uppercase tracking-wider shadow-xl shadow-gold-500/30 ring-2 ring-gold-300/60 transition-all cursor-pointer">
                    {{ __('admin.sign_in_btn') }}
                </button>
            </form>

            <div class="pt-4 border-t border-gold-400/20 text-center text-xs font-semibold text-slate-500 flex items-center justify-center gap-2">
                <span>{{ __('admin.security_footer') }}</span>
            </div>
        </div>

    </div>

</body>
</html>
