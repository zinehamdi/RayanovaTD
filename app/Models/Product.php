<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'slug',
        'title',
        'subtitle',
        'description',
        'featured_image',
        'gallery',
        'nutritional_specs',
        'logistics_specs',
        'allowed_packaging_types',
        'allowed_sizes',
        'is_featured',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    protected $casts = [
        'title' => 'array',
        'subtitle' => 'array',
        'description' => 'array',
        'gallery' => 'array',
        'nutritional_specs' => 'array',
        'logistics_specs' => 'array',
        'allowed_packaging_types' => 'array',
        'allowed_sizes' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'meta_title' => 'array',
        'meta_description' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function certificates(): BelongsToMany
    {
        return $this->belongsToMany(ProductCertificate::class, 'certificate_product');
    }

    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function getLocalizedTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->title[$locale] ?? $this->title['en'] ?? $this->title['ar'] ?? '';
    }

    public function getLocalizedSubtitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->subtitle[$locale] ?? $this->subtitle['en'] ?? $this->subtitle['ar'] ?? '';
    }

    public function getLocalizedDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->description[$locale] ?? $this->description['en'] ?? $this->description['ar'] ?? '';
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->featured_image)) {
            return asset('images/products/olive_oil_bottle.jpg');
        }

        if (str_starts_with($this->featured_image, 'http://') || str_starts_with($this->featured_image, 'https://')) {
            return $this->featured_image;
        }

        $cleaned = ltrim($this->featured_image, '/');

        // Check if file exists on disk, if not check fallbacks
        if (!file_exists(public_path($cleaned))) {
            $catSlug = strtolower($this->category->slug ?? '');
            if (str_contains($catSlug, 'date') || str_contains(strtolower($this->slug), 'date')) {
                return asset('images/products/deglet_nour_box.jpg');
            }
            if (str_contains(strtolower($this->slug), 'tomato')) {
                return asset('images/products/sundried_tomatoes_jar.jpg');
            }
            if (str_contains(strtolower($this->slug), 'tuna') || str_contains(strtolower($this->slug), 'thon')) {
                return asset('images/products/canned_tuna_can.jpg');
            }
            return asset('images/products/olive_oil_bottle.jpg');
        }

        $version = filemtime(public_path($cleaned));
        return asset($cleaned) . '?v=' . $version;
    }

    /**
     * Get allowed packaging configurations for this product
     */
    public function getAvailablePackagings(): array
    {
        return \App\Services\PackagingService::getPackagingsForProduct($this);
    }

    /**
     * Get allowed sizes for this product or fallback to sensible defaults
     */
    public function getAvailableSizes(): array
    {
        if (!empty($this->allowed_sizes) && is_array($this->allowed_sizes)) {
            return $this->allowed_sizes;
        }

        $packagings = $this->getAvailablePackagings();
        if (!empty($packagings[0]['default_sizes'])) {
            return $packagings[0]['default_sizes'];
        }

        return ['1L', '5L', 'Bulk Container'];
    }
}
