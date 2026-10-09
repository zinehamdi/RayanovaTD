<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\QuotationRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ContractManagementController extends Controller
{
    /**
     * Formulaire de configuration du contrat international
     */
    public function create(int $quotationId): View
    {
        $quotation = QuotationRequest::with(['items.product'])->findOrFail($quotationId);

        // Numérotation automatique du contrat
        $year = date('Y');
        $count = Contract::whereYear('created_at', $year)->count() + 1;
        $suggestedNumber = sprintf('CTR-RYN-%s-%04d', $year, $count);

        return view('admin.contracts.create', compact('quotation', 'suggestedNumber'));
    }

    /**
     * Enregistre le contrat et initialise la facture pro-forma
     */
    public function store(Request $request, int $quotationId): RedirectResponse
    {
        $quotation = QuotationRequest::with('items')->findOrFail($quotationId);

        $validated = $request->validate([
            'contract_number' => 'required|string|unique:contracts,contract_number|max:100',
            'payment_terms' => 'required|string|max:255',
            'bank_name' => 'required|string|max:150',
            'iban' => 'required|string|max:50',
            'swift_bic' => 'required|string|max:20',
            'required_documents' => 'nullable|array',
            'dhl_tracking_number' => 'nullable|string|max:100',
        ]);

        $contract = Contract::create([
            'quotation_request_id' => $quotation->id,
            'contract_number' => $validated['contract_number'],
            'payment_terms' => $validated['payment_terms'],
            'bank_details' => [
                'bank_name' => $validated['bank_name'],
                'beneficiary' => 'RAYANOVA T.D SARL (Export Division)',
                'iban' => $validated['iban'],
                'swift_bic' => $validated['swift_bic'],
                'country' => 'Tunisia',
            ],
            'required_documents' => $validated['required_documents'] ?? [
                'Commercial Invoice (3 originals + 3 copies)',
                'Original Bill of Lading (3/3 Full Clean on Board Ocean B/L)',
                'Certificate of Origin (EUR.1 / Arab League / Chamber of Commerce)',
                'Phytosanitary & Health Certificate (Ministry of Agriculture)',
                'Certificate of Analysis (COI / Certified Chemical Laboratory)',
                'Packing List and Weight Certificate',
            ],
            'dhl_tracking_number' => $validated['dhl_tracking_number'] ?? null,
            'status' => 'draft',
        ]);

        // Création de la facture pro-forma automatique
        $invoiceCount = Invoice::whereYear('created_at', date('Y'))->count() + 1;
        $invoiceNumber = sprintf('PI-RYN-%s-%04d', date('Y'), $invoiceCount);

        Invoice::create([
            'quotation_request_id' => $quotation->id,
            'contract_id' => $contract->id,
            'invoice_number' => $invoiceNumber,
            'invoice_type' => 'proforma',
            'subtotal' => $quotation->total_amount ?? 0,
            'freight_cost' => 0,
            'insurance_cost' => 0,
            'total_amount' => $quotation->total_amount ?? 0,
            'currency' => $quotation->currency ?? 'EUR',
            'status' => 'unpaid',
            'due_date' => now()->addDays(15),
        ]);

        $quotation->update([
            'status' => 'contract_issued',
        ]);

        return redirect()->route('admin.quotations.show', $quotation->id)
            ->with('success', "Contract {$contract->contract_number} and Pro-Forma Invoice generated.");
    }

    /**
     * Génération et téléchargement du PDF de Contrat International A4
     */
    public function downloadPdf(int $contractId): Response
    {
        $contract = Contract::with(['quotationRequest.items.product'])->findOrFail($contractId);
        $quotation = $contract->quotationRequest;

        $pdf = Pdf::loadView('pdf.contract', compact('contract', 'quotation'))
            ->setPaper('a4', 'portrait')
            ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download("Rayanova_Contract_{$contract->contract_number}.pdf");
    }

    /**
     * Génération et téléchargement du PDF de la Facture Pro-Forma A4
     */
    public function downloadInvoicePdf(int $invoiceId): Response
    {
        $invoice = Invoice::with(['quotationRequest.items.product', 'contract'])->findOrFail($invoiceId);
        $quotation = $invoice->quotationRequest;
        $contract = $invoice->contract;

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice', 'quotation', 'contract'))
            ->setPaper('a4', 'portrait')
            ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download("Rayanova_{$invoice->invoice_number}.pdf");
    }
}
