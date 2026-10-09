<x-layouts.admin :title="__('admin.products_cms_title') . ' — ' . __('admin.brand_name')">

    <div class="space-y-8">
        
        <!-- Header & Add Button -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <span class="text-xs sm:text-sm uppercase tracking-widest text-gold-700 font-extrabold flex items-center gap-1.5">
                    <span>📦</span>
                    <span>{{ __('admin.products_badge') }}</span>
                </span>
                <h1 class="font-serif text-3xl sm:text-4xl font-extrabold text-slate-900 mt-1">{{ __('admin.products_cms_title') }}</h1>
                <p class="text-sm sm:text-base text-slate-600 mt-1">{{ __('admin.products_cms_subtitle') }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <!-- Dropdown Download PDF Catalogue -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" type="button"
                            class="px-5 py-3.5 rounded-2xl bg-white hover:bg-gold-50 border-2 border-gold-500 text-slate-950 font-black text-sm md:text-base shadow-md transition-all flex items-center gap-2 cursor-pointer">
                        <span>📥</span>
                        <span>{{ __('admin.download_catalogue_pdf') }}</span>
                        <svg class="w-4 h-4 text-gold-600 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="open" x-transition style="display: none;"
                         class="absolute right-0 mt-2 w-64 rounded-2xl bg-white border-2 border-gold-400/80 shadow-2xl z-50 overflow-hidden py-2 divide-y divide-slate-100">
                        <a href="{{ route('admin.products.catalogue_pdf', ['lang' => 'en']) }}"
                           class="flex items-center justify-between px-4 py-3 text-sm font-bold text-slate-800 hover:bg-gold-50 hover:text-slate-950 transition-colors">
                            <span class="flex items-center gap-2"><span>🇬🇧</span> English Edition</span>
                            <span class="text-xs font-mono text-gold-700 bg-gold-100 px-2 py-0.5 rounded font-black">PDF</span>
                        </a>
                        <a href="{{ route('admin.products.catalogue_pdf', ['lang' => 'fr']) }}"
                           class="flex items-center justify-between px-4 py-3 text-sm font-bold text-slate-800 hover:bg-gold-50 hover:text-slate-950 transition-colors">
                            <span class="flex items-center gap-2"><span>🇫🇷</span> Édition Française</span>
                            <span class="text-xs font-mono text-gold-700 bg-gold-100 px-2 py-0.5 rounded font-black">PDF</span>
                        </a>
                        <a href="{{ route('admin.products.catalogue_pdf', ['lang' => 'ar']) }}"
                           class="flex items-center justify-between px-4 py-3 text-sm font-bold text-slate-800 hover:bg-gold-50 hover:text-slate-950 transition-colors">
                            <span class="flex items-center gap-2"><span>🇸🇦</span> النسخة العربية</span>
                            <span class="text-xs font-mono text-gold-700 bg-gold-100 px-2 py-0.5 rounded font-black">PDF</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.products.create') }}" class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-gold-500 to-gold-400 hover:from-gold-400 hover:to-gold-300 text-slate-950 font-black text-sm md:text-base uppercase tracking-wider shadow-lg shadow-gold-500/25 ring-2 ring-gold-300 transition-all flex items-center gap-2 cursor-pointer">
                    <span>{{ __('admin.add_new_product') }}</span>
                </a>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white rounded-3xl p-6 border-2 border-gold-400/30 shadow-xl flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <input type="text" name="q" value="{{ $search }}" placeholder="{{ __('admin.search_products_placeholder') }}"
                       class="px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 placeholder-slate-400 text-sm sm:text-base w-72 focus:border-gold-500 focus:bg-white focus:outline-none font-medium">

                <select name="category_id" class="px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                    <option value="">{{ __('admin.all_categories') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                            {{ $cat->localized_name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="px-5 py-3 rounded-2xl bg-gradient-to-r from-gold-500 to-gold-400 hover:from-gold-400 hover:to-gold-300 text-slate-950 font-black text-sm sm:text-base shadow-md transition-all cursor-pointer">
                    {{ __('admin.search') }}
                </button>

                @if($search || $categoryId)
                    <a href="{{ route('admin.products.index') }}" class="text-sm font-bold text-slate-600 hover:text-slate-900 px-2 underline">
                        {{ __('admin.clear') }}
                    </a>
                @endif
            </form>

            <div class="text-xs sm:text-sm font-bold text-slate-500">
                {{ $products->total() }} {{ __('admin.products_found') }}
            </div>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($products as $product)
                <div class="bg-white rounded-3xl border-2 border-gold-400/30 overflow-hidden shadow-xl hover:shadow-2xl transition-all flex flex-col justify-between group">
                    
                    <!-- Product Image Stage -->
                    <div class="relative h-60 bg-amber-50/50 p-6 flex items-center justify-center overflow-hidden border-b border-gold-400/20">
                        <img src="{{ $product->image_url }}" alt="{{ $product->localized_title }}"
                             class="max-h-full max-w-full object-contain drop-shadow-xl group-hover:scale-105 transition-transform duration-300">

                        <!-- Badges -->
                        <div class="absolute top-4 left-4 right-4 flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-xs font-black shadow-md {{ $product->is_active ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' }}">
                                {{ $product->is_active ? '● ' . __('admin.active') : '○ ' . __('admin.hidden') }}
                            </span>
                            @if($product->category)
                                <span class="px-3 py-1 rounded-xl bg-slate-900 text-gold-300 font-mono text-xs font-black shadow-sm">
                                    {{ $product->category->localized_name }}
                                </span>
                            @endif
                        </div>

                        @if($product->is_featured)
                            <div class="absolute bottom-3 left-4">
                                <span class="px-2.5 py-0.5 rounded-full bg-gold-400 text-slate-950 font-black text-xs shadow-sm">
                                    ⭐ {{ __('admin.featured') }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="p-6 space-y-4 flex-1 flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="font-mono text-xs text-gold-700 font-extrabold">
                                {{ $product->slug }}
                            </div>
                            <h3 class="font-serif text-lg sm:text-xl font-black text-slate-900 leading-tight">
                                {{ $product->localized_title }}
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-600 line-clamp-2">
                                {{ $product->localized_subtitle }}
                            </p>

                            <!-- Packaging Badges -->
                            @if($product->allowed_packaging_types)
                                <div class="flex flex-wrap gap-1.5 pt-2">
                                    @foreach($product->allowed_packaging_types as $pkg)
                                        <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-slate-700 text-xs font-bold border border-gold-200">
                                            {{ ucfirst(str_replace('_', ' ', $pkg)) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="px-4 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 border-2 border-gold-300 text-slate-950 text-xs sm:text-sm font-black transition-all">
                                ✏️ {{ __('admin.edit_product') }}
                            </a>

                            @can('delete_products')
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                      onsubmit="return confirm('Do you confirm deleting product {{ $product->localized_title }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-red-100 hover:bg-red-200 border border-red-300 text-red-800 text-xs sm:text-sm font-bold transition-all cursor-pointer">
                                        {{ __('admin.delete') }}
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full bg-white rounded-3xl p-12 text-center text-slate-500 border-2 border-gold-300/40 shadow-xl space-y-3">
                    <p class="text-base font-medium">{{ __('admin.no_products_found') }}</p>
                    <a href="{{ route('admin.products.create') }}" class="inline-block text-sm font-black text-gold-700 hover:underline">
                        {{ __('admin.add_new_product') }}
                    </a>
                </div>
            @endforelse
        </div>

        @if($products->hasPages())
            <div class="p-6 bg-white rounded-3xl border-2 border-gold-400/30 shadow-xl">
                {{ $products->links() }}
            </div>
        @endif

    </div>

</x-layouts.admin>
