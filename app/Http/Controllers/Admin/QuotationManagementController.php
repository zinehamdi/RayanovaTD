<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\QuotationQuotedClientMail;
use App\Models\QuotationItem;
use App\Models\QuotationMessage;
use App\Models\QuotationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class QuotationManagementController extends Controller
{
    /**
     * Liste et filtrage du pipeline de cotation (Kanban / Tableau)
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status');
        $search = $request->query('q');

        $query = QuotationRequest::with(['items.product.category'])->latest('id');

        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        $quotations = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => QuotationRequest::count(),
            'new' => QuotationRequest::where('status', 'new')->count(),
            'quoted' => QuotationRequest::where('status', 'quoted')->count(),
            'client_viewed' => QuotationRequest::where('status', 'client_viewed')->count(),
            'client_modified' => QuotationRequest::where('status', 'client_modified')->count(),
            'confirmed' => QuotationRequest::where('status', 'confirmed')->count(),
            'contract_issued' => QuotationRequest::where('status', 'contract_issued')->count(),
            'closed' => QuotationRequest::where('status', 'closed')->count(),
        ];

        return view('admin.quotations.index', compact('quotations', 'counts', 'statusFilter', 'search'));
    }

    /**
     * Affichage détaillé du dossier commercial
     */
    public function show(int $id): View
    {
        $quotation = QuotationRequest::with(['items.product.category', 'messages', 'contract', 'invoices'])
            ->findOrFail($id);

        return view('admin.quotations.show', compact('quotation'));
    }

    /**
     * Chiffrage officiel de l'offre (Pricing Desk)
     */
    public function price(Request $request, int $id): RedirectResponse
    {
        $quotation = QuotationRequest::with('items')->findOrFail($id);

        $validated = $request->validate([
            'currency' => 'required|in:EUR,USD,GBP,TND',
            'valid_until' => 'required|date|after:today',
            'shipping_cost' => 'nullable|numeric|min:0',
            'insurance_cost' => 'nullable|numeric|min:0',
            'admin_notes' => 'nullable|string|max:1000',
            'items' => 'required|array',
            'items.*.unit_price' => 'required|numeric|min:0.01',
        ]);

        $subtotal = 0;

        foreach ($quotation->items as $item) {
            if (isset($validated['items'][$item->id]['unit_price'])) {
                $unitPrice = (float)$validated['items'][$item->id]['unit_price'];
                $itemTotal = $unitPrice * (float)$item->quantity;

                $item->update([
                    'offered_unit_price' => $unitPrice,
                    'subtotal' => $itemTotal,
                ]);

                $subtotal += $itemTotal;
            }
        }

        $shipping = (float)($validated['shipping_cost'] ?? 0);
        $insurance = (float)($validated['insurance_cost'] ?? 0);
        $totalAmount = $subtotal + $shipping + $insurance;

        $quotation->update([
            'currency' => $validated['currency'],
            'valid_until' => $validated['valid_until'],
            'total_amount' => $totalAmount,
            'admin_notes' => $validated['admin_notes'] ?? null,
            'status' => 'quoted',
        ]);

        // Message de notification interne dans le fil
        QuotationMessage::create([
            'quotation_request_id' => $quotation->id,
            'sender_type' => 'admin',
            'sender_name' => auth()->user()->name,
            'message_body' => sprintf(
                "Official price proposal issued: %s %s (Valid until: %s). Includes %s Incoterms to %s.",
                number_format($totalAmount, 2),
                $validated['currency'],
                $validated['valid_until'],
                $quotation->incoterm,
                $quotation->destination_port
            ),
        ]);

        // Envoi de l'email officiel au client avec le Magic Link
        try {
            Mail::to($quotation->email)->send(new QuotationQuotedClientMail($quotation));
        } catch (\Exception $e) {
            // Continuer si environnement local sans SMTP
        }

        return redirect()->route('admin.quotations.show', $quotation->id)
            ->with('success', 'Quotation successfully priced and dispatched to client.');
    }

    /**
     * Suppression définitive réservée exclusivement au Super Admin
     */
    public function destroy(int $id): RedirectResponse
    {
        // Enforce Super Admin exclusive deletion policy
        abort_unless(auth()->user()->hasRole('super_admin'), 403, 'Only Super Admin holds deletion rights for security & traceability audit.');

        $quotation = QuotationRequest::findOrFail($id);

        $reference = $quotation->reference_number;
        $quotation->items()->delete();
        $quotation->messages()->delete();
        $quotation->delete();

        return redirect()->route('admin.quotations.index')
            ->with('success', "Deal {$reference} has been securely purged.");
    }

    /**
     * Génération et téléchargement du PDF officiel de Cotation Pro-Forma (Admin)
     */
    public function downloadPdf(int $id): \Illuminate\Http\Response
    {
        $quotation = QuotationRequest::with(['items.product.category', 'messages', 'contract'])
            ->findOrFail($id);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.quotation', compact('quotation'))
            ->setPaper('a4', 'portrait')
            ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download("Rayanova_Quotation_{$quotation->reference_number}.pdf");
    }
}

