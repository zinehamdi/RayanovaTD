<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_request_id',
        'contract_id',
        'invoice_number',
        'invoice_type',
        'pdf_path',
        'subtotal',
        'freight_cost',
        'insurance_cost',
        'total_amount',
        'currency',
        'status',
        'due_date',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'freight_cost' => 'decimal:2',
        'insurance_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    public function quotationRequest(): BelongsTo
    {
        return $this->belongsTo(QuotationRequest::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_amount, 2) . ' ' . $this->currency;
    }
}
