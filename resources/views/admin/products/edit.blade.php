<x-layouts.admin :title="__('admin.edit_product') . ' #' . $product->id . ' — ' . __('admin.brand_name')">

    <div class="max-w-4xl mx-auto space-y-8">
        
        <!-- Top Navigation -->
        <div class="flex items-center gap-3 border-b-2 border-slate-200 pb-4">
            <a href="{{ route('admin.products.index') }}" class="text-sm font-extrabold text-gold-700 hover:text-gold-900 flex items-center gap-1">
                <span>← {{ __('admin.back_to_products') }}</span>
            </a>
            <span class="text-slate-400">•</span>
            <span class="text-xs sm:text-sm text-slate-500 font-medium">{{ __('admin.edit_product') }} #{{ $product->id }}</span>
        </div>

        <div>
            <span class="text-xs sm:text-sm uppercase tracking-widest text-gold-700 font-extrabold flex items-center gap-1.5">
                <span>📦</span>
                <span>{{ __('admin.products_badge') }}</span>
            </span>
            <h1 class="font-serif text-3xl sm:text-4xl font-extrabold text-slate-900 mt-1">{{ __('admin.edit_product') }}</h1>
            <p class="text-sm sm:text-base text-slate-600 mt-1">{{ $product->localized_title }} ({{ $product->slug }})</p>
        </div>

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

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-6 sm:p-10 border-2 border-gold-400/30 shadow-2xl space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1 : Catégorie & Titres Multilingues -->
            <div class="space-y-4">
                <h2 class="text-xs sm:text-sm font-extrabold text-gold-700 uppercase tracking-wider">
                    {{ __('admin.product_general_sec') }}
                </h2>

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-800">{{ __('admin.category') }} *</label>
                    <select name="category_id" required class="w-full px-4 py-3.5 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id) == $cat->id) ? 'selected' : '' }}>
                                {{ $cat->localized_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 pt-2">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-800">{{ __('admin.title_en') }} *</label>
                        <input type="text" name="title_en" value="{{ old('title_en', $product->title['en'] ?? '') }}" required
                               class="w-full px-4 py-3.5 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-medium focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-800">{{ __('admin.title_fr') }}</label>
                        <input type="text" name="title_fr" value="{{ old('title_fr', $product->title['fr'] ?? '') }}"
                               class="w-full px-4 py-3.5 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-medium focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-800 font-arabic" dir="rtl">{{ __('admin.title_ar') }}</label>
                        <input type="text" name="title_ar" value="{{ old('title_ar', $product->title['ar'] ?? '') }}" dir="rtl"
                               class="w-full px-4 py-3.5 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-base font-arabic focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.subtitle_en') }}</label>
                        <input type="text" name="subtitle_en" value="{{ old('subtitle_en', $product->subtitle['en'] ?? '') }}"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-medium focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.subtitle_fr') }}</label>
                        <input type="text" name="subtitle_fr" value="{{ old('subtitle_fr', $product->subtitle['fr'] ?? '') }}"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-medium focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800 font-arabic" dir="rtl">{{ __('admin.subtitle_ar') }}</label>
                        <input type="text" name="subtitle_ar" value="{{ old('subtitle_ar', $product->subtitle['ar'] ?? '') }}" dir="rtl"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-base font-arabic focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.description_en') }}</label>
                        <textarea name="description_en" rows="3" class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-medium focus:border-gold-500 focus:bg-white focus:outline-none">{{ old('description_en', $product->description['en'] ?? '') }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.description_fr') }}</label>
                        <textarea name="description_fr" rows="3" class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-medium focus:border-gold-500 focus:bg-white focus:outline-none">{{ old('description_fr', $product->description['fr'] ?? '') }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800 font-arabic" dir="rtl">{{ __('admin.description_ar') }}</label>
                        <textarea name="description_ar" rows="3" dir="rtl" class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-base font-arabic focus:border-gold-500 focus:bg-white focus:outline-none">{{ old('description_ar', $product->description['ar'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2 : Image Produit Actuelle & Remplacement -->
            <div class="space-y-6 pt-6 border-t border-slate-100">
                <h2 class="text-xs sm:text-sm font-extrabold text-gold-700 uppercase tracking-wider">{{ __('admin.product_media_sec') }}</h2>

                <!-- Current Product Image Preview -->
                <div class="p-5 rounded-2xl bg-amber-50/50 border-2 border-gold-300/60 flex flex-col sm:flex-row sm:items-center gap-5">
                    <div class="w-32 h-32 rounded-2xl bg-white border border-gold-300 p-3 shadow-md flex items-center justify-center shrink-0">
                        <img src="{{ $product->image_url }}" alt="Current Product Image" class="max-h-full max-w-full object-contain">
                    </div>
                    <div class="space-y-1 text-xs sm:text-sm overflow-hidden">
                        <span class="text-xs uppercase font-extrabold text-gold-800 tracking-wider block">
                            {{ __('admin.current_product_image') }}
                        </span>
                        <span class="font-mono text-slate-900 font-extrabold break-all block">{{ $product->featured_image }}</span>
                    </div>
                </div>

                <!-- Replacement Upload Dropzone -->
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-slate-800">
                        {{ __('admin.upload_replacement_image') }}
                    </label>
                    <p class="text-xs text-slate-500 font-medium">{{ __('admin.upload_product_image_hint') }}</p>

                    <div id="dropzone"
                         class="relative border-2 border-dashed border-gold-400 hover:border-gold-500 bg-amber-50/30 hover:bg-amber-50/60 transition-all rounded-3xl p-8 text-center cursor-pointer group shadow-inner">
                        
                        <input type="file" name="image_file" id="image_file" accept="image/jpeg,image/png,image/jpg,image/webp,image/avif"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">

                        <!-- Empty State Box -->
                        <div id="upload_prompt" class="space-y-3 pointer-events-none">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gold-300 via-gold-400 to-gold-600 mx-auto flex items-center justify-center text-slate-950 font-black text-2xl shadow-lg shadow-gold-500/20 group-hover:scale-105 transition-transform">
                                📷
                            </div>
                            <div>
                                <span class="text-sm md:text-base font-extrabold text-slate-900 block group-hover:text-gold-800 transition-colors">
                                    {{ __('admin.drag_drop_image_text') }}
                                </span>
                                <span class="text-xs font-semibold text-slate-500 mt-1 block">
                                    PNG, JPG, WEBP, AVIF (Max: 20MB)
                                </span>
                            </div>
                        </div>

                        <!-- Live Preview Box -->
                        <div id="preview_container" class="hidden space-y-4 pointer-events-none">
                            <div class="relative max-w-xs mx-auto rounded-2xl overflow-hidden border-2 border-gold-400 shadow-xl bg-amber-50/60 p-4">
                                <img id="image_preview" src="" alt="Preview" class="max-h-52 mx-auto object-contain">
                            </div>
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-gold-300 text-xs font-bold text-slate-800 shadow-sm">
                                <span>📄</span>
                                <span id="file_name_display" class="font-mono truncate max-w-xs font-bold"></span>
                                <span id="file_size_display" class="text-slate-500 font-normal"></span>
                            </div>
                            <div class="text-xs font-bold text-gold-700">
                                ↺ {{ __('admin.change_file') }}
                            </div>
                        </div>
                    </div>

                    <!-- Optional: Direct Path / URL Input -->
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-600 mb-1">
                            Or specify direct image file path / asset URL:
                        </label>
                        <input type="text" name="featured_image" value="{{ old('featured_image', $product->featured_image) }}" 
                               placeholder="e.g. images/products/deglet_nour_box.jpg or https://..."
                               class="w-full px-4 py-2.5 rounded-xl bg-amber-50/30 border border-slate-200 text-xs font-mono text-slate-900 focus:border-gold-500 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Section 3 : Conditionnements & Tailles (Packaging Configuration) -->
            <div class="space-y-6 pt-6 border-t border-slate-100">
                <div>
                    <h2 class="text-xs sm:text-sm font-extrabold text-gold-700 uppercase tracking-wider">
                        {{ __('admin.packaging_and_specs_sec') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Sélectionnez les formats de conditionnement réels adaptés à ce produit (ex: Cartons & Bois pour Dattes, Boîtes métal pour Thon, Bollar pour Huile).
                    </p>
                </div>

                @php
                    $groupedPackagings = \App\Services\PackagingService::grouped();
                    $currentPackagings = $product->allowed_packaging_types ?? [];
                    // Handle alias translations for legacy keys
                    $aliases = [
                        'bottle_glass_marasca' => 'bollar',
                        'bottle_glass_dorica' => 'bollar',
                        'tin_can' => 'tin',
                        'bulk' => 'bulk_flexitank',
                        'glass_jar_hermetic' => 'glass_jar',
                        'glass_jar_preserves' => 'glass_jar_tuna',
                        'private_label_cluster' => 'tin_can_easy_open',
                    ];
                    $normalizedCurrent = array_map(fn($k) => $aliases[$k] ?? $k, $currentPackagings);
                @endphp

                <div class="space-y-6">
                    @foreach($groupedPackagings as $gKey => $group)
                        <div class="rounded-2xl border-2 border-gold-300/40 bg-amber-50/30 p-4 sm:p-5 space-y-3">
                            <div class="flex items-center justify-between border-b border-gold-200 pb-2">
                                <span class="text-xs font-black uppercase tracking-wider text-slate-900 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-gold-600"></span>
                                    <span>{{ $group['label_fr'] }} • {{ $group['label_ar'] }}</span>
                                </span>
                                <span class="text-[11px] font-bold text-slate-500 font-mono">({{ count($group['items']) }} formats)</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($group['items'] as $pkg)
                                    @php
                                        $isChecked = in_array($pkg['id'], $normalizedCurrent) || in_array($pkg['id'], $currentPackagings);
                                    @endphp
                                    <label class="flex items-start gap-3 p-3.5 rounded-xl border-2 transition-all cursor-pointer select-none {{ $isChecked ? 'bg-white border-gold-500 shadow-md ring-1 ring-gold-400' : 'bg-white/70 border-slate-200 hover:border-gold-300' }}">
                                        <input type="checkbox" name="allowed_packaging_types[]" value="{{ $pkg['id'] }}" {{ $isChecked ? 'checked' : '' }} class="mt-1 w-4 h-4 rounded text-gold-600 focus:ring-gold-400">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-base">{{ $pkg['icon_emoji'] }}</span>
                                                <span class="text-xs sm:text-sm font-extrabold text-slate-900 truncate">{{ $pkg['name_fr'] }}</span>
                                            </div>
                                            <span class="block text-[11px] font-bold text-gold-800 font-arabic mt-0.5" dir="rtl">{{ $pkg['name_ar'] }}</span>
                                            <span class="block text-[10px] text-slate-500 mt-1 leading-tight">{{ $pkg['desc_fr'] }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Allowed Sizes Config with Presets -->
                <div class="space-y-2 pt-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-bold text-slate-800">{{ __('admin.allowed_sizes_label') }}</label>
                        <span class="text-xs text-slate-500">Séparées par des virgules</span>
                    </div>

                    <input type="text" id="allowed_sizes_input" name="allowed_sizes_input" value="{{ old('allowed_sizes_input', is_array($product->allowed_sizes) ? implode(', ', $product->allowed_sizes) : '') }}"
                           class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 font-mono text-sm sm:text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                    
                    <!-- Quick Presets -->
                    <div class="flex flex-wrap items-center gap-2 pt-1.5">
                        <span class="text-xs font-bold text-slate-600">Raccourcis de Tailles :</span>
                        <button type="button" onclick="document.getElementById('allowed_sizes_input').value = '250ml, 500ml, 750ml, 1L, 3L, 5L'" class="px-2.5 py-1 rounded-lg bg-white border border-slate-300 hover:border-gold-500 text-[11px] font-mono font-bold text-slate-800 shadow-sm cursor-pointer">
                            + Huile (250ml-5L)
                        </button>
                        <button type="button" onclick="document.getElementById('allowed_sizes_input').value = '250g, 500g, 1kg, 2kg, 5kg'" class="px-2.5 py-1 rounded-lg bg-white border border-slate-300 hover:border-gold-500 text-[11px] font-mono font-bold text-slate-800 shadow-sm cursor-pointer">
                            + Dattes (250g-5kg)
                        </button>
                        <button type="button" onclick="document.getElementById('allowed_sizes_input').value = '80g, 125g, 160g, 185g, 400g, 1kg, 1.7kg'" class="px-2.5 py-1 rounded-lg bg-white border border-slate-300 hover:border-gold-500 text-[11px] font-mono font-bold text-slate-800 shadow-sm cursor-pointer">
                            + Thon / Sardines (80g-1.7kg)
                        </button>
                        <button type="button" onclick="document.getElementById('allowed_sizes_input').value = '200g, 280g, 500g, 1kg, 3kg, 10kg Bucket'" class="px-2.5 py-1 rounded-lg bg-white border border-slate-300 hover:border-gold-500 text-[11px] font-mono font-bold text-slate-800 shadow-sm cursor-pointer">
                            + Tomates / Harissa
                        </button>
                        <button type="button" onclick="document.getElementById('allowed_sizes_input').value = '1,000L IBC, 21,500L Flexitank, 24,000L Flexitank'" class="px-2.5 py-1 rounded-lg bg-white border border-slate-300 hover:border-gold-500 text-[11px] font-mono font-bold text-slate-800 shadow-sm cursor-pointer">
                            + Vrac / Flexitank
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 pt-4">
                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.display_order') }}</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $product->sort_order) }}"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 font-mono text-sm sm:text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="flex items-center gap-3 pt-6">
                        <label class="flex items-center gap-3 cursor-pointer text-sm sm:text-base font-extrabold text-slate-900">
                            <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }} class="w-5 h-5 rounded border-2 border-gold-500 text-gold-600 focus:ring-gold-400">
                            <span>{{ __('admin.product_active_label') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center gap-3 pt-6">
                        <label class="flex items-center gap-3 cursor-pointer text-sm sm:text-base font-extrabold text-slate-900">
                            <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }} class="w-5 h-5 rounded border-2 border-gold-500 text-gold-600 focus:ring-gold-400">
                            <span>{{ __('admin.product_featured_label') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <a href="{{ route('admin.products.index') }}" class="text-sm font-extrabold text-slate-600 hover:text-slate-900">
                    ← {{ __('admin.cancel') }}
                </a>

                <button type="submit" class="px-8 py-4 rounded-2xl bg-gradient-to-r from-gold-500 via-gold-400 to-gold-500 hover:from-gold-400 hover:to-gold-300 text-slate-950 font-black text-sm sm:text-base uppercase tracking-wider shadow-xl shadow-gold-500/25 ring-2 ring-gold-300 transition-all cursor-pointer">
                    {{ __('admin.update_product_btn') }}
                </button>
            </div>
        </form>

    </div>

    <!-- Live Image Preview Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('image_file');
            const dropzone = document.getElementById('dropzone');
            const uploadPrompt = document.getElementById('upload_prompt');
            const previewContainer = document.getElementById('preview_container');
            const imagePreview = document.getElementById('image_preview');
            const fileNameDisplay = document.getElementById('file_name_display');
            const fileSizeDisplay = document.getElementById('file_size_display');

            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const fileUrl = URL.createObjectURL(file);
                        uploadPrompt.classList.add('hidden');
                        previewContainer.classList.remove('hidden');

                        fileNameDisplay.textContent = file.name;
                        fileSizeDisplay.textContent = '(' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';
                        imagePreview.src = fileUrl;
                    }
                });

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropzone.addEventListener(eventName, () => {
                        dropzone.classList.add('border-gold-600', 'bg-amber-100/50');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, () => {
                        dropzone.classList.remove('border-gold-600', 'bg-amber-100/50');
                    }, false);
                });
            }
        });
    </script>

</x-layouts.admin>
