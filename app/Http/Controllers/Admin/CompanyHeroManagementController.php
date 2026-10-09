<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyIntroHero;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CompanyHeroManagementController extends Controller
{
    /**
     * Formulaire d'édition de la section Hero Corporate Rayanova T.D
     */
    public function edit(): View
    {
        $companyHero = CompanyIntroHero::firstOrCreate(
            ['id' => 1],
            [
                'badge' => [
                    'ar' => '🇹🇳 رايانوفا للتجارة الدولية والتوزيع • ريادة التصدير الزراعي',
                    'en' => '🇹🇳 RAYANOVA T.D • International Trade & Mediterranean Agro-Export',
                    'fr' => '🇹🇳 RAYANOVA T.D • Négoce International & Agro-Export Méditerranéen',
                ],
                'title' => [
                    'ar' => 'بوابتكم المباشرة لتصدير أجود المنتجات الفلاحية وزيت الزيتون التونسي إلى موانئ العالم',
                    'en' => 'Tunisia\'s Premier Gateway for Export-Grade Olive Oil & Mediterranean Commodities',
                    'fr' => 'Votre Passerelle Directe pour l\'Exportation d\'Huile d\'Olive & Produits Méditerranéens d\'Exception',
                ],
                'subtitle' => [
                    'ar' => 'شركة تونسية رائدة متخصصة في سلاسل الإمداد والتصدير الدولي لزيت الزيتون البكر الممتاز، تمور دقلة النور، والمنتجات الفلاحية التونسية المطابقة للمواصفات الأوروبية والأمريكية، بحاويات بحرية وشحن سائب عبر موانئ رادس وصفاقس.',
                    'en' => 'Headquartered in Tunisia, Rayanova T.D bridges authentic Mediterranean agricultural heritage with global B2B supply chains. We deliver certified Extra Virgin Olive Oil, Deglet Nour dates, and private-label packaged goods across international sea and air hubs under Incoterms 2020.',
                    'fr' => 'Établie en Tunisie, Rayanova T.D allie l\'excellence du terroir agricole tunisien aux exigences du commerce international B2B. Exportation d\'huile d\'olive extra vierge, dattes Deglet Nour et marques privées vers les plus grands ports mondiaux selon les Incoterms 2020.',
                ],
                'background_image' => 'images/hero/company_export_hero.jpg',
                'stats' => [
                    ['label_ar' => 'موانئ الشحن والتصدير', 'label_en' => 'Export Gateways', 'label_fr' => 'Ports d\'Exportation', 'value' => 'Rades & Sfax Ports'],
                    ['label_ar' => 'شروط التسليم الدولية', 'label_en' => 'Incoterms 2020', 'label_fr' => 'Incoterms 2020', 'value' => 'FOB • CIF • CFR • EXW'],
                    ['label_ar' => 'مطابقة واعتمادات مخبرية', 'label_en' => 'Global Accreditations', 'label_fr' => 'Accréditations', 'value' => 'EU BIO • FDA • ISO 22000'],
                    ['label_ar' => 'أنماط الشحن واللوجستيك', 'label_en' => 'Logistics Modes', 'label_fr' => 'Modes Logistiques', 'value' => 'FCL • LCL • 24,000L Bulk'],
                ],
                'primary_cta_text' => [
                    'ar' => 'طلب تسعيرة رسمية فورية (RFQ)',
                    'en' => 'Request Official Export RFQ',
                    'fr' => 'Demander une Cotation (RFQ)',
                ],
                'primary_cta_url' => '/rfq',
                'secondary_cta_text' => [
                    'ar' => 'استكشاف المنتجات والتعليب',
                    'en' => 'Browse Export Catalog',
                    'fr' => 'Découvrir le Catalogue',
                ],
                'secondary_cta_url' => '#products',
                'whatsapp_number' => '+21698000000',
                'show_badge' => true,
                'show_banner' => true,
                'is_active' => true,
            ]
        );

        return view('admin.company_hero.edit', compact('companyHero'));
    }

    /**
     * Enregistre les modifications de la section Hero Corporate
     */
    public function update(Request $request): RedirectResponse
    {
        $companyHero = CompanyIntroHero::firstOrCreate(['id' => 1]);

        $validated = $request->validate([
            'badge_ar' => 'nullable|string|max:255',
            'badge_en' => 'nullable|string|max:255',
            'badge_fr' => 'nullable|string|max:255',
            'title_ar' => 'required|string|max:500',
            'title_en' => 'required|string|max:500',
            'title_fr' => 'nullable|string|max:500',
            'subtitle_ar' => 'nullable|string',
            'subtitle_en' => 'nullable|string',
            'subtitle_fr' => 'nullable|string',
            'primary_cta_text_ar' => 'nullable|string|max:255',
            'primary_cta_text_en' => 'nullable|string|max:255',
            'primary_cta_text_fr' => 'nullable|string|max:255',
            'primary_cta_url' => 'nullable|string|max:255',
            'secondary_cta_text_ar' => 'nullable|string|max:255',
            'secondary_cta_text_en' => 'nullable|string|max:255',
            'secondary_cta_text_fr' => 'nullable|string|max:255',
            'secondary_cta_url' => 'nullable|string|max:255',
            'whatsapp_number' => 'nullable|string|max:50',
            'image_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,avif|max:20480',
            'background_image' => 'nullable|string|max:500',
            'stats' => 'nullable|array',
            'show_badge' => 'nullable|boolean',
            'show_banner' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $companyHero->background_image;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $storedPath = $file->store('hero', 'public');

            // Clean up old custom uploaded image if stored locally
            if (!empty($companyHero->background_image) && str_starts_with($companyHero->background_image, 'storage/hero/')) {
                $oldStoragePath = str_replace('storage/', '', $companyHero->background_image);
                Storage::disk('public')->delete($oldStoragePath);
            }

            $imagePath = 'storage/' . $storedPath;
        } elseif (!empty($validated['background_image'])) {
            $imagePath = $validated['background_image'];
        }

        // Parse stats
        $statsArray = [];
        if ($request->has('stats') && is_array($request->input('stats'))) {
            foreach ($request->input('stats') as $stat) {
                if (!empty($stat['value'])) {
                    $statsArray[] = [
                        'label_ar' => $stat['label_ar'] ?? '',
                        'label_en' => $stat['label_en'] ?? '',
                        'label_fr' => $stat['label_fr'] ?? '',
                        'value' => $stat['value'],
                    ];
                }
            }
        }

        if (empty($statsArray)) {
            $statsArray = $companyHero->stats ?? [];
        }

        $companyHero->update([
            'badge' => [
                'ar' => $validated['badge_ar'] ?? '',
                'en' => $validated['badge_en'] ?? '',
                'fr' => $validated['badge_fr'] ?? '',
            ],
            'title' => [
                'ar' => $validated['title_ar'],
                'en' => $validated['title_en'],
                'fr' => $validated['title_fr'] ?? $validated['title_en'],
            ],
            'subtitle' => [
                'ar' => $validated['subtitle_ar'] ?? '',
                'en' => $validated['subtitle_en'] ?? '',
                'fr' => $validated['subtitle_fr'] ?? '',
            ],
            'primary_cta_text' => [
                'ar' => $validated['primary_cta_text_ar'] ?? 'طلب تسعيرة رسمية فورية (RFQ)',
                'en' => $validated['primary_cta_text_en'] ?? 'Request Official Export RFQ',
                'fr' => $validated['primary_cta_text_fr'] ?? 'Demander une Cotation (RFQ)',
            ],
            'primary_cta_url' => $validated['primary_cta_url'] ?? '/rfq',
            'secondary_cta_text' => [
                'ar' => $validated['secondary_cta_text_ar'] ?? 'استكشاف المنتجات والتعليب',
                'en' => $validated['secondary_cta_text_en'] ?? 'Browse Export Catalog',
                'fr' => $validated['secondary_cta_text_fr'] ?? 'Découvrir le Catalogue',
            ],
            'secondary_cta_url' => $validated['secondary_cta_url'] ?? '#products',
            'whatsapp_number' => $validated['whatsapp_number'] ?? '+21698000000',
            'background_image' => $imagePath,
            'stats' => $statsArray,
            'show_badge' => $request->boolean('show_badge', false),
            'show_banner' => $request->boolean('show_banner', false),
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()->route('admin.company_hero.edit')
            ->with('success', __('admin.company_hero_updated_success') ?? 'Company Intro Hero updated successfully.');
    }
}
