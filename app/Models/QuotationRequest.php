<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class QuotationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'request_type',
        'company_name',
        'contact_name',
        'email',
        'phone',
        'whatsapp',
        'country',
        'tax_number',
        'website',
        'incoterm',
        'destination_port',
        'shipping_method',
        'currency',
        'status',
        'valid_until',
        'signed_token',
        'last_viewed_at',
        'client_notes',
        'admin_notes',
        'total_amount',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'last_viewed_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(QuotationMessage::class);
    }

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_amount ?? 0, 2) . ' ' . $this->currency;
    }
}
