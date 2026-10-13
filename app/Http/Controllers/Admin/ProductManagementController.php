<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductManagementController extends Controller
{
    /**
     * Génération et téléchargement du Catalogue Export B2B en format PDF A4
     */
    public function exportCataloguePdf(Request $request): Response
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', 120);

        $lang = $request->query('lang', app()->getLocale() ?: 'en');
        if (!in_array($lang, ['en', 'fr', 'ar'])) {
            $lang = 'en';
        }

        $isRtl = ($lang === 'ar');

        $categories = Category::with(['products' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }])->orderBy('sort_order')->get();

        // Local logo path for fast & zero-memory direct Dompdf rendering
        $logoPath = null;
        $candidateLogo = public_path('images/brand/logo-rayanova-td.jpg');
        if (file_exists($candidateLogo)) {
            $logoPath = $candidateLogo;
        } elseif (file_exists(public_path('images/brand/logo.png'))) {
            $logoPath = public_path('images/brand/logo.png');
        }

        // Map product image direct absolute paths
        $productImagePaths = [];
        foreach ($categories as $category) {
            foreach ($category->products as $prod) {
                $imgPath = null;
                if (!empty($prod->featured_image)) {
                    $cleaned = ltrim($prod->featured_image, '/');
                    if (file_exists(public_path($cleaned))) {
                        $imgPath = public_path($cleaned);
                    }
                }

                if (!$imgPath || !file_exists($imgPath)) {
                    $catSlug = strtolower($category->slug ?? '');
                    if (str_contains(strtolower($prod->slug), 'grenade') || str_contains(strtolower($prod->slug), 'pomegranate')) {
                        $fallback = public_path('images/products/pomegranate_export_box.jpg');
                    } elseif (str_contains(strtolower($prod->slug), 'cerise')) {
                        $fallback = public_path('images/products/cherry_tomatoes_export_tray.jpg');
                    } elseif (str_contains($catSlug, 'date') || str_contains(strtolower($prod->slug), 'date')) {
                        $fallback = public_path('images/products/deglet_nour_box.jpg');
                    } elseif (str_contains(strtolower($prod->slug), 'tomato') || str_contains(strtolower($prod->slug), 'harissa')) {
                        $fallback = public_path('images/products/sundried_tomatoes_jar.jpg');
                    } elseif (str_contains(strtolower($prod->slug), 'tuna') || str_contains(strtolower($prod->slug), 'thon')) {
                        $fallback = public_path('images/products/canned_tuna_can.jpg');
                    } else {
                        $fallback = public_path('images/products/olive_oil_bottle.jpg');
                    }
                    if (file_exists($fallback)) {
                        $imgPath = $fallback;
                    }
                }

                if ($imgPath && file_exists($imgPath)) {
                    $productImagePaths[$prod->id] = $imgPath;
                }
            }
        }

        $ar = fn(?string $text) => \App\Services\PdfArabicHelper::format($text, $isRtl);

        $pdf = Pdf::loadView('pdf.catalogue', compact(
            'categories',
            'lang',
            'isRtl',
            'logoPath',
            'productImagePaths',
            'ar'
        ))
        ->setPaper('a4', 'portrait')
        ->setOption([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'chroot' => [public_path(), base_path('storage/app/public')],
        ]);

        $filename = sprintf('Rayanova_TD_Product_Export_Catalogue_2026_%s.pdf', strtoupper($lang));

        if ($request->has('download') || $request->boolean('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }
    /**
     * Liste des produits du catalogue avec filtrage et recherche
     */
    public function index(Request $request): View
    {
        $search = $request->input('q');
        $categoryId = $request->input('category_id');

        $query = Product::with('category')->orderBy('sort_order');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('slug', 'like', "%{$search}%")
                  ->orWhere('title->en', 'like', "%{$search}%")
                  ->orWhere('title->fr', 'like', "%{$search}%")
                  ->orWhere('title->ar', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.products.index', compact('products', 'categories', 'search', 'categoryId'));
    }

    /**
     * Formulaire de création de produit
     */
    public function create(): View
    {
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Enregistre un nouveau produit dans le catalogue
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title_en' => 'required|string|max:255',
            'title_fr' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:255',
            'subtitle_fr' => 'nullable|string|max:255',
            'subtitle_ar' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_fr' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:20480',
            'featured_image' => 'nullable|string|max:500',
            'allowed_packaging_types' => 'nullable|array',
            'allowed_sizes_input' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if (!$request->hasFile('image_file') && empty($validated['featured_image'])) {
            return back()->withErrors(['image_file' => 'Please upload a product image from your computer.'])->withInput();
        }

        $imagePath = $validated['featured_image'] ?? 'images/products/olive-oil-tresor.png';

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $storedPath = $file->store('products', 'public');
            $imagePath = 'storage/' . $storedPath;
        }

        // Parsing allowed sizes from comma-separated input
        $sizesArray = [];
        if (!empty($validated['allowed_sizes_input'])) {
            $sizesArray = array_values(array_filter(array_map('trim', explode(',', $validated['allowed_sizes_input']))));
        }
        if (empty($sizesArray)) {
            $sizesArray = ['1L', '5L', 'Bulk Container'];
        }

        $packagingArray = $request->input('allowed_packaging_types', ['bulk', 'bottle_glass_marasca']);

        $slugBase = Str::slug($validated['title_en']);
        $slug = $slugBase;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$slugBase}-{$counter}";
            $counter++;
        }

        Product::create([
            'category_id' => $validated['category_id'],
            'slug' => $slug,
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
            'description' => [
                'en' => $validated['description_en'] ?? '',
                'fr' => $validated['description_fr'] ?? '',
                'ar' => $validated['description_ar'] ?? '',
            ],
            'featured_image' => $imagePath,
            'gallery' => [$imagePath],
            'allowed_packaging_types' => $packagingArray,
            'allowed_sizes' => $sizesArray,
            'is_featured' => $request->boolean('is_featured', false),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 10,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully in export catalog.');
    }

    /**
     * Formulaire d'édition de produit
     */
    public function edit(int $id): View
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('sort_order')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Met à jour les informations du produit
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title_en' => 'required|string|max:255',
            'title_fr' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:255',
            'subtitle_fr' => 'nullable|string|max:255',
            'subtitle_ar' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_fr' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image_file' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:20480',
            'featured_image' => 'nullable|string|max:500',
            'allowed_packaging_types' => 'nullable|array',
            'allowed_sizes_input' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $imagePath = $product->featured_image;

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $storedPath = $file->store('products', 'public');
            $imagePath = 'storage/' . $storedPath;
        } elseif (!empty($validated['featured_image'])) {
            $imagePath = $validated['featured_image'];
        }

        $sizesArray = $product->allowed_sizes ?? ['1L', '5L'];
        if (!empty($validated['allowed_sizes_input'])) {
            $sizesArray = array_values(array_filter(array_map('trim', explode(',', $validated['allowed_sizes_input']))));
        }

        $packagingArray = $request->input('allowed_packaging_types', $product->allowed_packaging_types ?? ['bulk']);

        $product->update([
            'category_id' => $validated['category_id'],
            'title' => [
                'en' => $validated['title_en'],
                'fr' => $validated['title_fr'] ?? $product->title['fr'] ?? '',
                'ar' => $validated['title_ar'] ?? $product->title['ar'] ?? '',
            ],
            'subtitle' => [
                'en' => $validated['subtitle_en'] ?? '',
                'fr' => $validated['subtitle_fr'] ?? '',
                'ar' => $validated['subtitle_ar'] ?? '',
            ],
            'description' => [
                'en' => $validated['description_en'] ?? '',
                'fr' => $validated['description_fr'] ?? '',
                'ar' => $validated['description_ar'] ?? '',
            ],
            'featured_image' => $imagePath,
            'allowed_packaging_types' => $packagingArray,
            'allowed_sizes' => $sizesArray,
            'is_featured' => $request->boolean('is_featured', false),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? $product->sort_order,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Suppression exclusive par le Super Admin
     */
    public function destroy(int $id): RedirectResponse
    {
        abort_unless(auth()->user()->hasRole('super_admin'), 403, 'Only Super Admin can delete catalog products.');

        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
