<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'issuer',
        'badge_icon',
        'pdf_file_path',
        'valid_until',
    ];

    protected $casts = [
        'name' => 'array',
        'valid_until' => 'date',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'certificate_product');
    }

    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->name[$locale] ?? $this->name['en'] ?? $this->name['ar'] ?? '';
    }
}
