<x-layouts.admin :title="__('admin.slides_cms_title') . ' — ' . __('admin.brand_name')">

    <div class="space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-xs sm:text-sm uppercase tracking-widest text-gold-700 font-extrabold flex items-center gap-1.5">
                    <span>🖼️</span>
                    <span>{{ __('admin.slides_cms_badge') }}</span>
                </span>
                <h1 class="font-serif text-3xl sm:text-4xl font-extrabold text-slate-900 mt-1">{{ __('admin.slides_cms_title') }}</h1>
                <p class="text-sm sm:text-base text-slate-600 mt-1">{{ __('admin.slides_cms_subtitle') }}</p>
            </div>

            <a href="{{ route('admin.slides.create') }}" class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-gold-500 to-gold-400 hover:from-gold-400 hover:to-gold-300 text-slate-950 font-black text-sm md:text-base uppercase tracking-wider shadow-lg shadow-gold-500/25 ring-2 ring-gold-300 transition-all flex items-center gap-2 cursor-pointer">
                <span>{{ __('admin.create_new_slide') }}</span>
            </a>
        </div>

        <!-- Slides Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($slides as $slide)
                <div class="bg-white rounded-3xl border-2 border-gold-400/30 overflow-hidden shadow-xl hover:shadow-2xl transition-all flex flex-col justify-between">
                    
                    <!-- Thumbnail with Overlay Preview -->
                    <div class="relative h-56 bg-slate-900 overflow-hidden">
                        @if(Str::endsWith($slide->media_path, ['.mp4', '.webm', '.ogg']))
                            <video src="{{ asset($slide->media_path) }}" class="w-full h-full object-cover"></video>
                        @else
                            <img src="{{ asset($slide->media_path) }}" alt="Slide Background" class="w-full h-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-slate-950" style="opacity: {{ $slide->overlay_opacity ?? 0.55 }};"></div>
                        
                        <!-- Badges in Preview -->
                        <div class="absolute top-4 left-4 right-4 flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-xs font-black shadow-md {{ $slide->is_active ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' }}">
                                {{ $slide->is_active ? '● ' . __('admin.active') : '○ ' . __('admin.hidden') }}
                            </span>
                            <span class="px-3 py-1 rounded-xl bg-black/70 text-white font-mono text-xs font-bold shadow-sm">
                                {{ __('admin.order') }}: #{{ $slide->sort_order }}
                            </span>
                        </div>

                        <!-- Title Preview inside Slide -->
                        <div class="absolute bottom-4 left-4 right-4 space-y-1">
                            <span class="inline-block px-2.5 py-0.5 rounded-full bg-gold-400 text-slate-950 text-xs font-black shadow-sm">
                                {{ is_array($slide->badge_text) ? ($slide->badge_text[app()->getLocale()] ?? $slide->badge_text['en'] ?? 'Badge') : $slide->badge_text }}
                            </span>
                            <h3 class="font-serif text-base sm:text-lg font-black text-white line-clamp-1">
                                {{ is_array($slide->title) ? ($slide->title[app()->getLocale()] ?? $slide->title['en'] ?? 'Slide Title') : $slide->title }}
                            </h3>
                        </div>
                    </div>

                    <!-- Slide Details -->
                    <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                        <div class="space-y-2 text-sm">
                            <div class="text-slate-700">
                                <strong class="font-extrabold text-slate-900">EN:</strong> {{ is_array($slide->title) ? ($slide->title['en'] ?? 'N/A') : $slide->title }}
                            </div>
                            <div class="text-slate-700">
                                <strong class="font-extrabold text-slate-900">FR:</strong> {{ is_array($slide->title) ? ($slide->title['fr'] ?? 'N/A') : 'N/A' }}
                            </div>
                            <div class="text-slate-700 font-arabic text-base" dir="rtl">
                                <strong class="font-extrabold text-slate-900 font-sans">AR:</strong> {{ is_array($slide->title) ? ($slide->title['ar'] ?? 'غير محدد') : 'N/A' }}
                            </div>
                            <div class="text-gold-800 font-semibold pt-1 text-xs sm:text-sm">
                                <strong>CTA:</strong> {{ is_array($slide->cta_text) ? ($slide->cta_text[app()->getLocale()] ?? $slide->cta_text['en'] ?? 'Quote') : $slide->cta_text }} → <span class="font-mono text-slate-600 font-normal">{{ $slide->cta_url }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('admin.slides.edit', $slide->id) }}" class="px-4 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 border-2 border-gold-300 text-slate-950 text-xs sm:text-sm font-black transition-all">
                                {{ __('admin.edit_slide') }}
                            </a>

                            @can('delete_quotations')
                                <form action="{{ route('admin.slides.destroy', $slide->id) }}" method="POST"
                                      onsubmit="return confirm('Do you confirm deleting this hero slide?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-red-100 hover:bg-red-200 border border-red-300 text-red-800 text-xs sm:text-sm font-bold transition-all cursor-pointer">
                                        {{ __('admin.delete_slide') }}
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl p-12 text-center text-slate-500 border-2 border-gold-300/40 shadow-xl space-y-3">
                    <p class="text-base font-medium">{{ __('admin.no_slides') }}</p>
                    <a href="{{ route('admin.slides.create') }}" class="inline-block text-sm font-black text-gold-700 hover:underline">
                        {{ __('admin.create_first_slide') }}
                    </a>
                </div>
            @endforelse
        </div>

    </div>

</x-layouts.admin>
