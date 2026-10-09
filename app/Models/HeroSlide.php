<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'badge_text',
        'cta_text',
        'cta_url',
        'media_type',
        'media_path',
        'overlay_opacity',
        'glass_card_content',
        'sort_order',
        'show_badge',
        'is_active',
    ];

    protected $casts = [
        'title' => 'array',
        'subtitle' => 'array',
        'badge_text' => 'array',
        'cta_text' => 'array',
        'glass_card_content' => 'array',
        'overlay_opacity' => 'decimal:2',
        'show_badge' => 'boolean',
        'is_active' => 'boolean',
    ];

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

    public function getLocalizedBadgeAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->badge_text[$locale] ?? $this->badge_text['en'] ?? $this->badge_text['ar'] ?? '';
    }

    public function getLocalizedCtaAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->cta_text[$locale] ?? $this->cta_text['en'] ?? $this->cta_text['ar'] ?? '';
    }
}
