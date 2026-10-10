<?php

namespace App\Http\Controllers;

use App\Mail\QuotationReceivedClientMail;
use App\Models\Product;
use App\Models\QuotationItem;
use App\Models\QuotationMessage;
use App\Models\QuotationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class QuotationController extends Controller
{
    /**
     * Affiche le formulaire officiel de demande de cotation (RFQ)
     */
    public function create(): View
    {
        $cart = session()->get('quote_cart', []);
        $products = Product::where('is_active', true)->orderBy('sort_order')->get();

        return view('rfq.create', compact('cart', 'products'));
    }

    /**
     * Enregistre la demande de cotation et déclenche les emails
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'country' => 'required|string|max:100',
            'tax_number' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'incoterm' => 'required|in:FOB,CIF,CFR,EXW',
            'destination_port' => 'required|string|max:255',
            'shipping_method' => 'required|in:sea_fcl,sea_lcl,air',
            'request_type' => 'required|in:quote,sample_request,private_label',
            'client_notes' => 'nullable|string|max:2000',
        ]);

        $cart = session()->get('quote_cart', []);
        
        // Si le panier de session est vide, on vérifie si un produit par défaut a été soumis
        if (empty($cart) && $request->filled('single_product_id')) {
            $product = Product::findOrFail($request->single_product_id);
            $cart[] = [
                'product_id' => $product->id,
                'packaging_type' => $request->input('single_packaging', 'bottle_glass_marasca'),
                'size' => $request->input('single_size', '500ml'),
                'quantity' => (float)$request->input('single_quantity', 1000),
                'unit' => 'units',
                'is_private_label' => $request->boolean('single_private_label'),
            ];
        }

        if (empty($cart)) {
            return redirect()->back()->withErrors([
                'cart' => LaravelLocalization::getCurrentLocale() === 'ar' 
                    ? 'الرجاء اختيار منتج واحد على الأقل وتحديد سعة التعليب قبل إرسال الطلب.' 
                    : 'Please select at least one product with its packaging configuration before submitting.'
            ])->withInput();
        }

        // Génération de la référence unique séquentielle
        $year = date('Y');
        $count = QuotationRequest::whereYear('created_at', $year)->count() + 1;
        $reference = sprintf('RFQ-%s-%04d', $year, $count);

        // Création du deal
        $quotation = QuotationRequest::create([
            'reference_number' => $reference,
            'request_type' => $validated['request_type'],
            'company_name' => $validated['company_name'],
            'contact_name' => $validated['contact_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'whatsapp' => $validated['whatsapp'] ?? null,
            'country' => $validated['country'],
            'tax_number' => $validated['tax_number'] ?? null,
            'website' => $validated['website'] ?? null,
            'incoterm' => $validated['incoterm'],
            'destination_port' => $validated['destination_port'],
            'shipping_method' => $validated['shipping_method'],
            'currency' => 'EUR',
            'status' => 'new',
            'signed_token' => Str::random(48),
            'client_notes' => $validated['client_notes'] ?? null,
        ]);

        // Sauvegarde des articles demandés
        foreach ($cart as $item) {
            QuotationItem::create([
                'quotation_request_id' => $quotation->id,
                'product_id' => $item['product_id'],
                'packaging_type' => $item['packaging_type'],
                'size' => $item['size'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'] ?? 'units',
                'is_private_label' => $item['is_private_label'] ?? false,
            ]);
        }

        // Création du message initial dans le fil de discussion
        QuotationMessage::create([
            'quotation_request_id' => $quotation->id,
            'sender_type' => 'client',
            'sender_name' => $validated['contact_name'],
            'message_body' => $validated['client_notes'] ?? 'Initial RFQ inquiry received.',
        ]);

        // Envoi de l'email de confirmation au client
        try {
            Mail::to($quotation->email)->send(new QuotationReceivedClientMail($quotation));
        } catch (\Exception $e) {
            // Continuer sans bloquer en cas de problème SMTP local
        }

        // Nettoyer le panier
        session()->forget('quote_cart');

        return redirect()->route('rfq.success', [
            'reference' => $quotation->reference_number
        ]);
    }

    /**
     * Page de confirmation de dépôt de dossier
     */
    public function success(string $reference): View
    {
        $quotation = QuotationRequest::with('items.product')->where('reference_number', $reference)->firstOrFail();

        return view('rfq.success', compact('quotation'));
    }

    /**
     * MAGIC LINK INTERACTIF (Consultation, Négociation & Confirmation sans mot de passe)
     */
    public function review(string $reference, Request $request): View
    {
        $quotation = QuotationRequest::with(['items.product', 'messages', 'contract'])
            ->where('reference_number', $reference)
            ->firstOrFail();

        // Vérification de sécurité du token
        if ($request->token !== $quotation->signed_token && !URL::hasValidSignature($request)) {
            abort(403, 'Invalid or expired secure quotation access link.');
        }

        // Marquer comme consulté par le client
        if (in_array($quotation->status, ['quoted', 'new'])) {
            $quotation->update([
                'last_viewed_at' => now(),
                'status' => 'client_viewed',
            ]);
        }

        return view('rfq.review', compact('quotation'));
    }

    /**
     * Confirmation de l'offre par le client via le Magic Link
     */
    public function confirm(string $reference, Request $request): RedirectResponse
    {
        $quotation = QuotationRequest::where('reference_number', $reference)->firstOrFail();

        if ($request->token !== $quotation->signed_token && !URL::hasValidSignature($request)) {
            abort(403, 'Unauthorized action.');
        }

        $quotation->update([
            'status' => 'confirmed',
        ]);

        QuotationMessage::create([
            'quotation_request_id' => $quotation->id,
            'sender_type' => 'client',
            'sender_name' => $quotation->contact_name,
            'message_body' => 'Deal approved and confirmed by client online via secure link.',
        ]);

        return redirect()->back()->with('success', LaravelLocalization::getCurrentLocale() === 'ar' 
            ? 'تم تأكيد الصفقة بنجاح! سيتواصل معك مدير التصدير لإصدار العقد الرسمي ووثائق الشحن.' 
            : 'Deal successfully confirmed! Our trade desk is now issuing your official export sales contract.');
    }

    /**
     * Demande d'ajustement ou négociation par le client via le Magic Link
     */
    public function modify(string $reference, Request $request): RedirectResponse
    {
        $quotation = QuotationRequest::where('reference_number', $reference)->firstOrFail();

        if ($request->token !== $quotation->signed_token && !URL::hasValidSignature($request)) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'amendment_notes' => 'required|string|max:2000',
        ]);

        $quotation->update([
            'status' => 'client_modified',
        ]);

        QuotationMessage::create([
            'quotation_request_id' => $quotation->id,
            'sender_type' => 'client',
            'sender_name' => $quotation->contact_name,
            'message_body' => $validated['amendment_notes'],
        ]);

        return redirect()->back()->with('success', LaravelLocalization::getCurrentLocale() === 'ar' 
            ? 'تم إرسال طلب التعديل بنجاح إلى مدير المبيعات وسيقوم بمراجعة التسعيرة فوراً.' 
            : 'Your amendment request has been submitted to our trade desk. An updated quote will be issued.');
    }

    /**
     * Téléchargement du PDF officiel de Cotation par le client / utilisateur
     */
    public function downloadPdf(string $reference, Request $request): \Illuminate\Http\Response
    {
        $quotation = QuotationRequest::with(['items.product.category', 'contract'])
            ->where('reference_number', $reference)
            ->firstOrFail();

        // Sécurité : autoriser avec signed_token OU signature d'URL signée OU utilisateur connecté
        if ($request->token !== $quotation->signed_token && !URL::hasValidSignature($request) && !auth()->check()) {
            abort(403, 'Unauthorized or expired quotation link.');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.quotation', compact('quotation'))
            ->setPaper('a4', 'portrait')
            ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        $fileName = "Rayanova_Quotation_{$quotation->reference_number}.pdf";

        if ($request->has('download')) {
            return $pdf->download($fileName);
        }

        return $pdf->stream($fileName);
    }
}
