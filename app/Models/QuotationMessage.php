<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_request_id',
        'sender_type',
        'sender_name',
        'message_body',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function quotationRequest(): BelongsTo
    {
        return $this->belongsTo(QuotationRequest::class);
    }
}
