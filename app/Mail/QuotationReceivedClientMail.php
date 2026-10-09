<?php

namespace App\Mail;

use App\Models\QuotationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuotationReceivedClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public QuotationRequest $quotation)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmation of Quotation Request ' . $this->quotation->reference_number . ' — Rayanova T.D',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quotation-received',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
