<?php

namespace App\Mail;

use App\Models\QuotationRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class QuotationQuotedClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $magicLink;

    public function __construct(public QuotationRequest $quotation, public ?string $pdfPath = null)
    {
        // Génération du lien signé sécurisé (valide jusqu'à la date d'expiration de l'offre ou 14 jours)
        $expiration = $this->quotation->valid_until ? $this->quotation->valid_until->endOfDay() : now()->addDays(14);
        
        $this->magicLink = URL::temporarySignedRoute(
            'quote.review',
            $expiration,
            [
                'locale' => app()->getLocale(),
                'reference' => $this->quotation->reference_number,
                'token' => $this->quotation->signed_token,
            ]
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Official Export Quotation ' . $this->quotation->reference_number . ' — Rayanova T.D',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quotation-priced',
        );
    }

    public function attachments(): array
    {
        $attachments = [];
        if ($this->pdfPath && file_exists(storage_path('app/' . $this->pdfPath))) {
            $attachments[] = Attachment::fromPath(storage_path('app/' . $this->pdfPath))
                ->as('Quotation_' . $this->quotation->reference_number . '.pdf')
                ->withMime('application/pdf');
        }
        return $attachments;
    }
}
