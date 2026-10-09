<x-layouts.admin :title="__('admin.edit_slide') . ' #' . $slide->id . ' — ' . __('admin.brand_name')">

    <div class="max-w-4xl mx-auto space-y-8">
        
        <div class="flex items-center gap-3 border-b-2 border-slate-200 pb-4">
            <a href="{{ route('admin.slides.index') }}" class="text-sm font-extrabold text-gold-700 hover:text-gold-900 flex items-center gap-1">
                <span>← Back to Hero Slider CMS</span>
            </a>
            <span class="text-slate-400">•</span>
            <span class="text-xs sm:text-sm text-slate-500 font-medium">{{ __('admin.edit_slide') }} #{{ $slide->id }}</span>
        </div>

        <div>
            <span class="text-xs sm:text-sm uppercase tracking-widest text-gold-700 font-extrabold flex items-center gap-1.5">
                <span>🖼️</span>
                <span>{{ __('admin.slides_cms_badge') }}</span>
            </span>
            <h1 class="font-serif text-3xl sm:text-4xl font-extrabold text-slate-900 mt-1">{{ __('admin.edit_slide') }}</h1>
            <p class="text-sm sm:text-base text-slate-600 mt-1">{{ __('admin.slides_cms_subtitle') }}</p>
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

        <form action="{{ route('admin.slides.update', $slide->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-6 sm:p-10 border-2 border-gold-400/30 shadow-2xl space-y-8">
            @csrf
            @method('PUT')

            <!-- Section 1 : Titres Multilingues -->
            <div class="space-y-4">
                <h2 class="text-xs sm:text-sm font-extrabold text-gold-700 uppercase tracking-wider">{{ __('admin.slide_titles_sec') }}</h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-800">{{ __('admin.title_en') }}</label>
                        <input type="text" name="title_en" value="{{ old('title_en', $slide->title['en'] ?? '') }}" required
                               class="w-full px-4 py-3.5 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-medium focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-800">{{ __('admin.title_fr') }}</label>
                        <input type="text" name="title_fr" value="{{ old('title_fr', $slide->title['fr'] ?? '') }}"
                               class="w-full px-4 py-3.5 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-medium focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-bold text-slate-800 font-arabic" dir="rtl">{{ __('admin.title_ar') }}</label>
                        <input type="text" name="title_ar" value="{{ old('title_ar', $slide->title['ar'] ?? '') }}" dir="rtl"
                               class="w-full px-4 py-3.5 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-base font-arabic focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>
                </div>

                <div class="pt-2 flex items-center justify-between border-t border-slate-100">
                    <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <span>🏷️</span>
                        <span>{{ __('admin.hero_badge_visibility') }}</span>
                    </span>
                    <div class="inline-flex items-center gap-2 bg-slate-100 p-1 rounded-xl border border-slate-200">
                        <label class="cursor-pointer">
                            <input type="radio" name="show_badge" value="1" class="sr-only peer" {{ old('show_badge', $slide->show_badge ?? true) ? 'checked' : '' }}>
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all text-slate-600 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:shadow-sm inline-block">
                                ✓ {{ __('admin.show_badge_label') }}
                            </span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="show_badge" value="0" class="sr-only peer" {{ !old('show_badge', $slide->show_badge ?? true) ? 'checked' : '' }}>
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all text-slate-600 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:shadow-sm inline-block">
                                ✕ {{ __('admin.hide_badge_label') }}
                            </span>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.badge_en') }}</label>
                        <input type="text" name="badge_en" value="{{ old('badge_en', $slide->badge_text['en'] ?? '') }}"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-medium focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.badge_fr') }}</label>
                        <input type="text" name="badge_fr" value="{{ old('badge_fr', $slide->badge_text['fr'] ?? '') }}"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-medium focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800 font-arabic" dir="rtl">{{ __('admin.badge_ar') }}</label>
                        <input type="text" name="badge_ar" value="{{ old('badge_ar', $slide->badge_text['ar'] ?? '') }}" dir="rtl"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-base font-arabic focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Section 2 : Téléversement Fichier Média & CTA -->
            <div class="space-y-6 pt-6 border-t border-slate-100">
                <h2 class="text-xs sm:text-sm font-extrabold text-gold-700 uppercase tracking-wider">{{ __('admin.media_cta_sec') }}</h2>

                <!-- Current Media Display -->
                <div class="p-5 rounded-2xl bg-amber-50/50 border-2 border-gold-300/60 space-y-3">
                    <span class="text-xs uppercase font-extrabold text-gold-800 tracking-wider block">
                        {{ __('admin.current_media_preview') }}
                    </span>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="w-full sm:w-48 h-28 rounded-xl overflow-hidden border border-gold-400/60 shadow-md bg-slate-900 shrink-0">
                            @if(Str::endsWith($slide->media_path, ['.mp4', '.webm', '.ogg']))
                                <video src="{{ asset($slide->media_path) }}" class="w-full h-full object-cover"></video>
                            @else
                                <img src="{{ asset($slide->media_path) }}" alt="Current Slide Media" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="space-y-1 text-xs sm:text-sm overflow-hidden">
                            <span class="font-bold text-slate-700 block">Path / Source:</span>
                            <span class="font-mono text-slate-900 font-extrabold break-all block">{{ $slide->media_path }}</span>
                        </div>
                    </div>
                </div>

                <!-- Replace Media File Box (Luxury Dropzone with Live Preview) -->
                <div class="space-y-3">
                    <label class="block text-sm font-bold text-slate-800">
                        {{ __('admin.upload_media_file_optional') }}
                    </label>
                    <p class="text-xs text-slate-500 font-medium">{{ __('admin.upload_hint') }}</p>

                    <div id="dropzone"
                         class="relative border-2 border-dashed border-gold-400 hover:border-gold-500 bg-amber-50/30 hover:bg-amber-50/60 transition-all rounded-3xl p-8 text-center cursor-pointer group shadow-inner">
                        
                        <input type="file" name="media_file" id="media_file" accept="image/jpeg,image/png,image/jpg,image/webp,image/avif,video/mp4,video/webm"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">

                        <!-- Empty State Box -->
                        <div id="upload_prompt" class="space-y-3 pointer-events-none">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gold-300 via-gold-400 to-gold-600 mx-auto flex items-center justify-center text-slate-950 font-black text-2xl shadow-lg shadow-gold-500/20 group-hover:scale-105 transition-transform">
                                📁
                            </div>
                            <div>
                                <span class="text-sm md:text-base font-extrabold text-slate-900 block group-hover:text-gold-800 transition-colors">
                                    {{ __('admin.drag_drop_text') }}
                                </span>
                                <span class="text-xs font-semibold text-slate-500 mt-1 block">
                                    {{ __('admin.supported_formats') }}
                                </span>
                            </div>
                        </div>

                        <!-- Live Replacement Preview Box (Hidden until new file selected) -->
                        <div id="preview_container" class="hidden space-y-4 pointer-events-none">
                            <div class="relative max-w-md mx-auto rounded-2xl overflow-hidden border-2 border-gold-400 shadow-xl bg-slate-950">
                                <img id="image_preview" src="" alt="Preview" class="hidden w-full h-56 object-cover">
                                <video id="video_preview" controls class="hidden w-full h-56 object-cover bg-black"></video>
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
                </div>

                <!-- CTA & Display Settings Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.cta_label_en') }}</label>
                        <input type="text" name="cta_en" value="{{ old('cta_en', $slide->cta_text['en'] ?? '') }}"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 text-sm sm:text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.cta_url_target') }}</label>
                        <input type="text" name="cta_url" value="{{ old('cta_url', $slide->cta_url) }}"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 font-mono text-sm sm:text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.overlay_opacity') }}</label>
                        <input type="number" step="0.05" min="0" max="1" name="overlay_opacity" value="{{ old('overlay_opacity', $slide->overlay_opacity) }}"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 font-mono text-sm sm:text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                    <div class="space-y-2">
                        <label class="block text-xs sm:text-sm font-bold text-slate-800">{{ __('admin.display_order') }}</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $slide->sort_order) }}"
                               class="w-full px-4 py-3 rounded-2xl bg-amber-50/40 border-2 border-slate-200 text-slate-900 font-mono text-sm sm:text-base font-bold focus:border-gold-500 focus:bg-white focus:outline-none">
                    </div>

                    <div class="flex items-center gap-3 pt-6">
                        <label class="flex items-center gap-3 cursor-pointer text-sm sm:text-base font-extrabold text-slate-900">
                            <input type="checkbox" name="is_active" value="1" {{ $slide->is_active ? 'checked' : '' }} class="w-5 h-5 rounded border-2 border-gold-500 text-gold-600 focus:ring-gold-400">
                            <span>{{ __('admin.slide_active_label') }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <a href="{{ route('admin.slides.index') }}" class="text-sm font-extrabold text-slate-600 hover:text-slate-900">
                    ← {{ __('admin.cancel') }}
                </a>

                <button type="submit" class="px-8 py-4 rounded-2xl bg-gradient-to-r from-gold-500 via-gold-400 to-gold-500 hover:from-gold-400 hover:to-gold-300 text-slate-950 font-black text-sm sm:text-base uppercase tracking-wider shadow-xl shadow-gold-500/25 ring-2 ring-gold-300 transition-all cursor-pointer">
                    {{ __('admin.update_slide_btn') }}
                </button>
            </div>
        </form>

    </div>

    <!-- Live Media Preview Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('media_file');
            const dropzone = document.getElementById('dropzone');
            const uploadPrompt = document.getElementById('upload_prompt');
            const previewContainer = document.getElementById('preview_container');
            const imagePreview = document.getElementById('image_preview');
            const videoPreview = document.getElementById('video_preview');
            const fileNameDisplay = document.getElementById('file_name_display');
            const fileSizeDisplay = document.getElementById('file_size_display');

            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const isVideo = file.type.startsWith('video/') || file.name.endsWith('.mp4') || file.name.endsWith('.webm');
                        const fileUrl = URL.createObjectURL(file);

                        uploadPrompt.classList.add('hidden');
                        previewContainer.classList.remove('hidden');

                        fileNameDisplay.textContent = file.name;
                        fileSizeDisplay.textContent = '(' + (file.size / (1024 * 1024)).toFixed(2) + ' MB)';

                        if (isVideo) {
                            imagePreview.classList.add('hidden');
                            videoPreview.classList.remove('hidden');
                            videoPreview.src = fileUrl;
                        } else {
                            videoPreview.classList.add('hidden');
                            imagePreview.classList.remove('hidden');
                            imagePreview.src = fileUrl;
                        }
                    }
                });

                // Drag and drop feedback
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
