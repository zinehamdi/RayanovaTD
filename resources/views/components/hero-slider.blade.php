@props(['slides'])

@php
    $isRtl = LaravelLocalization::getCurrentLocaleDirection() === 'rtl';
@endphp

<section class="relative bg-gradient-to-b from-[#0F172A] via-[#111C2E] to-[#0A101D] py-12 sm:py-20 px-4 sm:px-8 lg:px-12 overflow-hidden border-b border-slate-800">
    
    <!-- Subtle Ambient Glow -->
    <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[1000px] h-[400px] bg-gradient-to-b from-steel-400/10 via-electric/5 to-transparent rounded-full blur-3xl pointer-events-none" aria-hidden="true"></div>

    <div x-data="{
        active: 0,
        total: {{ count($slides) }},
        autoplay: true,
        timer: null,
        isTransitioning: false,
        startAutoplay() {
            this.timer = setInterval(() => {
                if (this.autoplay && !this.isTransitioning) {
                    this.next();
                }
            }, 8000);
        },
        next() {
            if (this.isTransitioning) return;
            this.isTransitioning = true;
            this.active = (this.active + 1) % this.total;
            setTimeout(() => { this.isTransitioning = false; }, 600);
        },
        prev() {
            if (this.isTransitioning) return;
            this.isTransitioning = true;
            this.active = (this.active - 1 + this.total) % this.total;
            setTimeout(() => { this.isTransitioning = false; }, 600);
        },
        goTo(index) {
            if (this.isTransitioning || index === this.active) return;
            this.isTransitioning = true;
            this.active = index;
            setTimeout(() => { this.isTransitioning = false; }, 600);
        }
    }" 
    x-init="startAutoplay()" 
    @mouseenter="autoplay = false" 
    @mouseleave="autoplay = true"
    class="max-w-[1700px] mx-auto relative">

        <!-- Modern Showcase Frame -->
        <div class="relative min-h-[580px] md:min-h-[660px] lg:min-h-[700px] rounded-3xl overflow-hidden border-2 border-slate-700/80 shadow-luxury-3d bg-slate-950 flex items-center justify-center p-6 sm:p-10 md:p-14">

            <!-- Slides Loop -->
            @foreach($slides as $index => $slide)
                <div x-show="active === {{ $index }}" 
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 translate-y-6 scale-[0.98]"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-500 absolute"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute inset-0 flex items-center justify-center p-6 sm:p-10 md:p-16"
                     style="{{ $index === 0 ? '' : 'display: none;' }}">

                    <!-- Crisp High-Definition Background -->
                    <div class="absolute inset-0 z-0 bg-cover bg-center transition-all duration-1000 transform" 
                         style="background-image: url('{{ asset($slide->media_path) }}'); filter: brightness(0.85) saturate(1.15) contrast(1.08);"></div>
                    
                    <!-- Dark Gradient Film for Legibility -->
                    <div class="absolute inset-0 z-0 bg-gradient-to-t from-slate-950 via-slate-950/45 to-slate-950/60 pointer-events-none"></div>

                    <!-- Slide Content Grid -->
                    <div class="max-w-7xl w-full mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center relative z-10">
                        
                        <!-- Left Panel: Text & Actions (7 Cols) -->
                        <div class="lg:col-span-7 space-y-6 text-start p-6 sm:p-10 rounded-3xl bg-slate-900/95 backdrop-blur-2xl border border-slate-700/90 shadow-2xl">
                            
                            <!-- Slide Counter & Badge -->
                            <div class="flex items-center justify-between">
                                @if(($slide->show_badge ?? true) && $slide->localized_badge)
                                    <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full bg-slate-800 border border-slate-700 text-xs font-bold text-steel-300 shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-coolteal-400 animate-ping"></span>
                                        <span class="tracking-wider uppercase font-mono">{{ $slide->localized_badge }}</span>
                                    </div>
                                @endif
                                <span class="text-xs font-mono font-bold text-slate-400 bg-slate-800/80 px-3 py-1 rounded-full border border-slate-700">
                                    0{{ $index + 1 }} / 0{{ count($slides) }}
                                </span>
                            </div>

                            <!-- Slide Title -->
                            <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl font-black tracking-tight text-white leading-tight">
                                {{ $slide->localized_title }}
                            </h2>

                            <!-- Slide Subtitle -->
                            @if($slide->localized_subtitle)
                                <p class="text-sm sm:text-base md:text-lg text-slate-300 font-normal leading-relaxed">
                                    {{ $slide->localized_subtitle }}
                                </p>
                            @endif

                            <!-- CTAs -->
                            <div class="flex flex-wrap items-center gap-4 pt-2">
                                <a href="{{ LaravelLocalization::localizeUrl($slide->cta_url) }}" 
                                   class="px-7 py-4 rounded-2xl bg-gradient-to-r from-steel-500 via-electric to-coolteal-500 hover:from-steel-400 hover:to-coolteal-400 text-slate-950 font-black text-sm sm:text-base shadow-xl shadow-electric/25 border-2 border-white/40 transition-all hover:scale-[1.02] active:scale-[0.98] btn-interactive flex items-center gap-2.5 cursor-pointer">
                                    <span>{{ $slide->localized_cta }}</span>
                                    <svg class="w-4 h-4 {{ $isRtl ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>

                                <a href="{{ LaravelLocalization::localizeUrl('/#products') }}" 
                                   class="px-6 py-4 rounded-2xl bg-slate-800/90 hover:bg-slate-750 text-white font-bold text-sm sm:text-base transition-all hover:scale-[1.02] active:scale-[0.98] border border-slate-700 hover:border-steel-400 flex items-center gap-2.5 shadow-xl backdrop-blur-md btn-interactive cursor-pointer">
                                    <span>{{ LaravelLocalization::getCurrentLocale() === 'ar' ? 'سلكتور التعليب التفاعلي 3D' : 'Interactive 3D Wheel Picker' }}</span>
                                    <svg class="w-4 h-4 text-steel-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                </a>
                            </div>
                        </div>

                        <!-- Right Panel: Lab & Cargo Intelligence Card (5 Cols) -->
                        @if($slide->glass_card_content)
                            <div class="lg:col-span-5 flex justify-center lg:justify-end">
                                <div class="w-full max-w-sm rounded-3xl p-6 sm:p-7 bg-slate-900/95 backdrop-blur-2xl border-2 border-slate-700 shadow-2xl space-y-4">
                                    
                                    <div class="flex items-center justify-between pb-3 border-b border-slate-800">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-steel-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                            <span class="text-xs font-bold uppercase tracking-widest text-steel-300 font-mono">Laboratory Analysis</span>
                                        </div>
                                        <span class="flex h-3 w-3 relative">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-coolteal-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-coolteal-400"></span>
                                        </span>
                                    </div>

                                    <div class="divide-y divide-slate-800 font-mono">
                                        @foreach($slide->glass_card_content as $statKey => $statData)
                                            <div class="py-2.5 flex items-center justify-between">
                                                @if(is_array($statData))
                                                    <span class="text-xs text-slate-400 font-medium">
                                                         {{ LaravelLocalization::getCurrentLocale() === 'ar' ? ($statData['label_ar'] ?? '') : (LaravelLocalization::getCurrentLocale() === 'fr' ? ($statData['label_fr'] ?? '') : ($statData['label_en'] ?? '')) }}
                                                    </span>
                                                    <span class="text-sm font-bold text-white">
                                                        {{ $statData['value'] ?? '' }}
                                                    </span>
                                                @else
                                                    <span class="text-xs text-slate-400 font-medium">
                                                        {{ ucfirst($statKey) }}
                                                    </span>
                                                    <span class="text-sm font-bold text-white">
                                                        {{ $statData }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="pt-3 border-t border-slate-800 flex items-center justify-between text-xs">
                                        <span class="flex items-center gap-1.5 font-bold text-coolteal-300 bg-coolteal-950/60 px-2.5 py-1 rounded-lg border border-coolteal-500/30">
                                            <span>✓</span> COI Accredited
                                        </span>
                                        <span class="text-steel-300 font-bold px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700 font-mono">ISO 22000</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach

            <!-- Slider Controls: Arrows & Bullets -->
            @if(count($slides) > 1)
                <button @click="prev()" 
                        class="hidden md:flex absolute {{ $isRtl ? 'right-6' : 'left-6' }} top-1/2 -translate-y-1/2 w-12 h-12 rounded-2xl bg-slate-900/90 hover:bg-steel-500 border border-slate-700 hover:border-steel-400 items-center justify-center text-white hover:text-slate-950 transition-all z-40 shadow-xl backdrop-blur-md cursor-pointer hover:scale-105 active:scale-95 btn-interactive" 
                        aria-label="Previous Slide">
                    <svg class="w-5 h-5 {{ $isRtl ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button @click="next()" 
                        class="hidden md:flex absolute {{ $isRtl ? 'left-6' : 'right-6' }} top-1/2 -translate-y-1/2 w-12 h-12 rounded-2xl bg-slate-900/90 hover:bg-steel-500 border border-slate-700 hover:border-steel-400 items-center justify-center text-white hover:text-slate-950 transition-all z-40 shadow-xl backdrop-blur-md cursor-pointer hover:scale-105 active:scale-95 btn-interactive" 
                        aria-label="Next Slide">
                    <svg class="w-5 h-5 {{ $isRtl ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>

                <!-- Indicator Dots -->
                <div class="absolute bottom-6 flex items-center gap-2.5 z-40 bg-slate-900/90 backdrop-blur-md px-4 py-2 rounded-full border border-slate-700 shadow-lg">
                    @foreach($slides as $index => $slide)
                        <button @click="goTo({{ $index }})" 
                                class="h-2.5 rounded-full transition-all duration-300 cursor-pointer"
                                :class="active === {{ $index }} ? 'w-8 bg-steel-400 shadow-md shadow-steel-400/50' : 'w-2.5 bg-slate-700 hover:bg-slate-600'"
                                :aria-label="'Slide ' + ({{ $index }} + 1)"></button>
                    @endforeach
                </div>
            @endif

        </div>

    </div>
</section>

