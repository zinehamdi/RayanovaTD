<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyIntroHero extends Model
{
    use HasFactory;

    protected $fillable = [
        'badge',
        'title',
        'subtitle',
        'background_image',
        'stats',
        'primary_cta_text',
        'primary_cta_url',
        'secondary_cta_text',
        'secondary_cta_url',
        'whatsapp_number',
        'show_badge',
        'show_banner',
        'is_active',
    ];

    protected $casts = [
        'badge' => 'array',
        'title' => 'array',
        'subtitle' => 'array',
        'stats' => 'array',
        'primary_cta_text' => 'array',
        'secondary_cta_text' => 'array',
        'show_badge' => 'boolean',
        'show_banner' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getLocalizedBadgeAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->badge[$locale] ?? $this->badge['en'] ?? $this->badge['ar'] ?? '';
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

    public function getLocalizedPrimaryCtaAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->primary_cta_text[$locale] ?? $this->primary_cta_text['en'] ?? $this->primary_cta_text['ar'] ?? 'RFQ Quotation';
    }

    public function getLocalizedSecondaryCtaAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->secondary_cta_text[$locale] ?? $this->secondary_cta_text['en'] ?? $this->secondary_cta_text['ar'] ?? 'Explore Products';
    }

    public function getImageUrlAttribute(): string
    {
        $defaultFallback = 'images/backgrounds/global_logistics_export.jpg';

        if (empty($this->background_image)) {
            return asset($defaultFallback);
        }

        if (str_starts_with($this->background_image, 'http://') || str_starts_with($this->background_image, 'https://')) {
            return $this->background_image;
        }

        $cleaned = ltrim($this->background_image, '/');

        if (!file_exists(public_path($cleaned))) {
            return asset($defaultFallback);
        }

        $version = filemtime(public_path($cleaned));
        return asset($cleaned) . '?v=' . $version;
    }
}
