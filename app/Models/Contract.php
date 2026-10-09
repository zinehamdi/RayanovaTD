<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_request_id',
        'contract_number',
        'pdf_path',
        'payment_terms',
        'bank_details',
        'required_documents',
        'dhl_tracking_number',
        'signed_copy_path',
        'status',
    ];

    protected $casts = [
        'bank_details' => 'array',
        'required_documents' => 'array',
    ];

    public function quotationRequest(): BelongsTo
    {
        return $this->belongsTo(QuotationRequest::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
