<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeroSlideManagementController extends Controller
{
    /**
     * Liste des slides du Hero Slider avec ordre et état
     */
    public function index(): View
    {
        $slides = HeroSlide::orderBy('sort_order')->get();

        return view('admin.slides.index', compact('slides'));
    }

    /**
     * Formulaire de création de slide
     */
    public function create(): View
    {
        return view('admin.slides.create');
    }

    /**
     * Enregistre une nouvelle slide
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_fr' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:500',
            'subtitle_fr' => 'nullable|string|max:500',
            'subtitle_ar' => 'nullable|string|max:500',
            'badge_en' => 'nullable|string|max:100',
            'badge_fr' => 'nullable|string|max:100',
            'badge_ar' => 'nullable|string|max:100',
            'cta_en' => 'nullable|string|max:100',
            'cta_fr' => 'nullable|string|max:100',
            'cta_ar' => 'nullable|string|max:100',
            'cta_url' => 'nullable|string|max:255',
            'media_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,avif,mp4,webm|max:20480',
            'media_path' => 'nullable|string|max:500',
            'overlay_opacity' => 'nullable|numeric|between:0,1',
            'sort_order' => 'nullable|integer',
            'show_badge' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        if (!$request->hasFile('media_file') && empty($validated['media_path'])) {
            return back()->withErrors(['media_file' => 'Please upload a media file from your computer.'])->withInput();
        }

        $mediaPath = $validated['media_path'] ?? '';
        $mediaType = 'image';

        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $extension = strtolower($file->getClientOriginalExtension());
            if (in_array($extension, ['mp4', 'webm', 'ogg'])) {
                $mediaType = 'video';
            }
            $storedPath = $file->store('slides', 'public');
            $mediaPath = 'storage/' . $storedPath;
        }

        HeroSlide::create([
            'title' => [
                'en' => $validated['title_en'],
                'fr' => $validated['title_fr'] ?? $validated['title_en'],
                'ar' => $validated['title_ar'] ?? $validated['title_en'],
            ],
            'subtitle' => [
                'en' => $validated['subtitle_en'] ?? '',
                'fr' => $validated['subtitle_fr'] ?? '',
                'ar' => $validated['subtitle_ar'] ?? '',
            ],
            'badge_text' => [
                'en' => $validated['badge_en'] ?? 'Tunisian Heritage',
                'fr' => $validated['badge_fr'] ?? 'Terroir Tunisien',
                'ar' => $validated['badge_ar'] ?? 'تراث تونسي أصيل',
            ],
            'cta_text' => [
                'en' => $validated['cta_en'] ?? 'Request Export Quote',
                'fr' => $validated['cta_fr'] ?? 'Demander une Cotation',
                'ar' => $validated['cta_ar'] ?? 'اطلب تسعيرة تصدير',
            ],
            'cta_url' => $validated['cta_url'] ?? '/rfq',
            'media_type' => $mediaType,
            'media_path' => $mediaPath,
            'overlay_opacity' => $validated['overlay_opacity'] ?? 0.60,
            'glass_card_content' => [
                'stat1' => ['value' => 'COI Lab Tested', 'label_en' => 'Quality Assay', 'label_fr' => 'Contrôle Qualité', 'label_ar' => 'جودة معتمدة'],
                'stat2' => ['value' => 'FCL / Flexitank', 'label_en' => 'Export Mode', 'label_fr' => 'Mode Export', 'label_ar' => 'شحن وتصدير'],
            ],
            'sort_order' => $validated['sort_order'] ?? 10,
            'show_badge' => $request->boolean('show_badge', false),
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('admin.slides.index')
            ->with('success', 'Hero slide successfully created.');
    }

    /**
     * Formulaire d'édition de slide
     */
    public function edit(int $id): View
    {
        $slide = HeroSlide::findOrFail($id);

        return view('admin.slides.edit', compact('slide'));
    }

    /**
     * Mise à jour d'une slide existante
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $slide = HeroSlide::findOrFail($id);

        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_fr' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:500',
            'subtitle_fr' => 'nullable|string|max:500',
            'subtitle_ar' => 'nullable|string|max:500',
            'badge_en' => 'nullable|string|max:100',
            'badge_fr' => 'nullable|string|max:100',
            'badge_ar' => 'nullable|string|max:100',
            'cta_en' => 'nullable|string|max:100',
            'cta_fr' => 'nullable|string|max:100',
            'cta_ar' => 'nullable|string|max:100',
            'cta_url' => 'nullable|string|max:255',
            'media_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,avif,mp4,webm|max:20480',
            'media_path' => 'nullable|string|max:500',
            'overlay_opacity' => 'nullable|numeric|between:0,1',
            'sort_order' => 'nullable|integer',
            'show_badge' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $mediaPath = $slide->media_path;
        $mediaType = $slide->media_type ?? 'image';

        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $extension = strtolower($file->getClientOriginalExtension());
            $mediaType = in_array($extension, ['mp4', 'webm', 'ogg']) ? 'video' : 'image';
            $storedPath = $file->store('slides', 'public');
            $mediaPath = 'storage/' . $storedPath;
        } elseif (!empty($validated['media_path'])) {
            $mediaPath = $validated['media_path'];
        }

        $slide->update([
            'title' => [
                'en' => $validated['title_en'],
                'fr' => $validated['title_fr'] ?? $slide->title['fr'] ?? '',
                'ar' => $validated['title_ar'] ?? $slide->title['ar'] ?? '',
            ],
            'subtitle' => [
                'en' => $validated['subtitle_en'] ?? '',
                'fr' => $validated['subtitle_fr'] ?? '',
                'ar' => $validated['subtitle_ar'] ?? '',
            ],
            'badge_text' => [
                'en' => $validated['badge_en'] ?? '',
                'fr' => $validated['badge_fr'] ?? '',
                'ar' => $validated['badge_ar'] ?? '',
            ],
            'cta_text' => [
                'en' => $validated['cta_en'] ?? '',
                'fr' => $validated['cta_fr'] ?? '',
                'ar' => $validated['cta_ar'] ?? '',
            ],
            'cta_url' => $validated['cta_url'] ?? $slide->cta_url,
            'media_type' => $mediaType,
            'media_path' => $mediaPath,
            'overlay_opacity' => $validated['overlay_opacity'] ?? $slide->overlay_opacity,
            'sort_order' => $validated['sort_order'] ?? $slide->sort_order,
            'show_badge' => $request->boolean('show_badge', false),
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('admin.slides.index')
            ->with('success', 'Hero slide successfully updated.');
    }

    /**
     * Suppression exclusive par le Super Admin
     */
    public function destroy(int $id): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('super_admin'), 403, 'Only Super Admin can delete hero slides.');

        $slide = HeroSlide::findOrFail($id);
        $slide->delete();

        return redirect()->route('admin.slides.index')
            ->with('success', 'Hero slide deleted successfully.');
    }
}
