<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_request_id',
        'product_id',
        'packaging_type',
        'size',
        'quantity',
        'unit',
        'offered_unit_price',
        'subtotal',
        'is_private_label',
        'custom_branding_notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'offered_unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'is_private_label' => 'boolean',
    ];

    public function quotationRequest(): BelongsTo
    {
        return $this->belongsTo(QuotationRequest::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getUnitPriceAttribute(): ?float
    {
        return $this->offered_unit_price !== null ? (float)$this->offered_unit_price : null;
    }

    public function setUnitPriceAttribute($value): void
    {
        $this->attributes['offered_unit_price'] = $value;
    }

    public function getTotalPriceAttribute(): ?float
    {
        return $this->subtotal !== null ? (float)$this->subtotal : null;
    }

    public function setTotalPriceAttribute($value): void
    {
        $this->attributes['subtotal'] = $value;
    }

    public function getLocalizedPackagingAttribute(): string
    {
        $pkg = strtolower($this->packaging_type ?? '');
        $locale = app()->getLocale();

        $labels = [
            'bottle_glass_marasca' => ['ar' => 'قارورة زجاجية ماراسكا (Marasca)', 'en' => 'Marasca Glass Bottle', 'fr' => 'Bouteille Verre Marasca'],
            'bottle_glass_dorica' => ['ar' => 'قارورة زجاجية دوريكا (Dorica)', 'en' => 'Dorica Glass Bottle', 'fr' => 'Bouteille Verre Dorica'],
            'tin_can' => ['ar' => 'صفيحة معدنية فاخرة (Tin Can)', 'en' => 'Metallic Tin Can', 'fr' => 'Bidon Métallique'],
            'tin' => ['ar' => 'صفيحة معدنية فاخرة (Tin Can)', 'en' => 'Metallic Tin Can', 'fr' => 'Bidon Métallique'],
            'flexitank' => ['ar' => 'حاوية مرنة سائبة (Flexitank 22,000L)', 'en' => 'Bulk Flexitank (22,000L)', 'fr' => 'Flexitank Vrac (22 000L)'],
            'ibc_1000l' => ['ar' => 'خزان وسيط IBC سعة 1000 لتر', 'en' => 'Intermediate Bulk Container IBC 1,000L', 'fr' => 'Cuve IBC 1 000L'],
            'carton_window_box' => ['ar' => 'علبة كرتونية بنافذة شفافة', 'en' => 'Window Carton Gift Box', 'fr' => 'Boîte Carton avec Fenêtre'],
            'vacuum_pack' => ['ar' => 'تغليف مفرغ من الهواء (Vacuum)', 'en' => 'Vacuum Sealed Pack', 'fr' => 'Emballage sous-vide'],
            'bollar' => ['ar' => 'قارورة زجاجية (Bollar / Glass)', 'en' => 'Glass Bottle (Bollar)', 'fr' => 'Bouteille en Verre (Bollar)'],
            'bulk' => ['ar' => 'تصدير سائب صناعي (Bulk Export)', 'en' => 'Bulk Export', 'fr' => 'Export en Vrac'],
        ];

        return $labels[$pkg][$locale] ?? $labels[$pkg]['en'] ?? ucfirst(str_replace('_', ' ', $this->packaging_type));
    }
}
