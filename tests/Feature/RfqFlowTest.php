<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\QuotationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RfqFlowTest extends TestCase
{
    public function test_rfq_page_can_be_rendered(): void
    {
        $response = $this->get('/en/rfq');
        $response->assertStatus(200);
        $response->assertSee('Request for Quotation (RFQ)');
    }

    public function test_client_can_submit_rfq_with_cart_items(): void
    {
        Mail::fake();

        $product = Product::first();
        $this->assertNotNull($product, 'Seed products must exist before running test');

        $cart = [
            'item_1' => [
                'product_id' => $product->id,
                'name' => $product->name,
                'packaging_type' => 'Flexitank',
                'size' => '22000L',
                'quantity' => 2,
                'unit' => 'Tons',
                'estimated_weight' => '44 MT',
            ]
        ];

        $payload = [
            'request_type' => 'quote',
            'company_name' => 'Nordic Oils AS',
            'contact_name' => 'Erik Johansen',
            'email' => 'erik@nordicoils.no',
            'phone' => '+47 912 34 567',
            'country' => 'Norway',
            'destination_port' => 'Port of Oslo',
            'shipping_method' => 'sea_fcl',
            'incoterm' => 'CIF',
            'client_notes' => 'Acidity < 0.3%, Cold extraction certified',
        ];

        $response = $this->withSession(['quote_cart' => $cart])
            ->post('/en/rfq', $payload);

        $quotation = QuotationRequest::where('email', 'erik@nordicoils.no')->latest('id')->first();
        $this->assertNotNull($quotation, 'Quotation record should be saved in database');

        $response->assertRedirect('/en/rfq/success/' . $quotation->reference_number);
        $this->assertEquals('new', $quotation->status);
        $this->assertEquals('CIF', $quotation->incoterm);
        $this->assertCount(1, $quotation->items);

        // Verify cart is emptied
        $this->assertEmpty(session('quote_cart'));

        // Clean up test quote
        $quotation->items()->delete();
        $quotation->messages()->delete();
        $quotation->delete();
    }

    public function test_client_can_view_quote_via_magic_link_and_confirm(): void
    {
        $quotation = QuotationRequest::first();
        $this->assertNotNull($quotation);

        // Test review page loads with secure token
        $response = $this->get('/en/quote/review/' . $quotation->reference_number . '?token=' . $quotation->signed_token);
        $response->assertStatus(200);
        $response->assertSee($quotation->reference_number);

        // Test client confirmation with secure token
        $confirmResponse = $this->post('/en/quote/review/' . $quotation->reference_number . '/confirm?token=' . $quotation->signed_token, [
            'client_notes' => 'Terms approved by procurement committee.',
        ]);

        $confirmResponse->assertRedirect('/en/quote/review/' . $quotation->reference_number . '?token=' . $quotation->signed_token);
        $this->assertEquals('confirmed', $quotation->fresh()->status);
    }

    public function test_client_can_request_revision_via_magic_link(): void
    {
        $quotation = QuotationRequest::first();
        $this->assertNotNull($quotation);

        $modifyResponse = $this->from('/en/quote/review/' . $quotation->reference_number . '?token=' . $quotation->signed_token)
            ->post('/en/quote/review/' . $quotation->reference_number . '/modify?token=' . $quotation->signed_token, [
                'amendment_notes' => 'Please provide updated pricing for CIF Hamburg instead of Rotterdam.',
            ]);

        $modifyResponse->assertRedirect('/en/quote/review/' . $quotation->reference_number . '?token=' . $quotation->signed_token);
        $this->assertEquals('client_modified', $quotation->fresh()->status);

        $this->assertDatabaseHas('quotation_messages', [
            'quotation_request_id' => $quotation->id,
            'message_body' => 'Please provide updated pricing for CIF Hamburg instead of Rotterdam.',
        ]);
    }
}
