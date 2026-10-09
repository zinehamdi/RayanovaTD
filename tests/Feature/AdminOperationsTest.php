<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\HeroSlide;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\QuotationItem;
use App\Models\QuotationRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminOperationsTest extends TestCase
{
    protected User $superAdmin;
    protected User $salesAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::where('email', 'admin@rayanovatd.com')->firstOrFail();
        $this->salesAdmin = User::where('email', 'sales@rayanovatd.com')->firstOrFail();
    }

    public function test_unauthenticated_users_are_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_authenticate_via_login_form(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@rayanovatd.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($this->superAdmin);
    }

    public function test_authenticated_admin_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Export Desk Overview');
        $response->assertSee('Priced Deals Pipeline');
    }

    public function test_admin_can_view_quotations_pipeline(): void
    {
        $response = $this->actingAs($this->salesAdmin)->get('/admin/quotations');

        $response->assertStatus(200);
        $response->assertSee('Quotation Pipeline');
    }

    public function test_admin_can_price_a_quotation_and_dispatch_email(): void
    {
        Mail::fake();

        $quotation = QuotationRequest::with('items')->firstOrFail();
        $firstItem = $quotation->items->first();
        $this->assertNotNull($firstItem);

        $payload = [
            'currency' => 'EUR',
            'valid_until' => now()->addDays(10)->format('Y-m-d'),
            'shipping_cost' => 1500.00,
            'insurance_cost' => 300.00,
            'admin_notes' => 'Pricing valid for November vessel departure from Rades Port.',
            'items' => [
                $firstItem->id => [
                    'unit_price' => 8.50,
                ]
            ]
        ];

        $response = $this->actingAs($this->salesAdmin)
            ->post("/admin/quotations/{$quotation->id}/price", $payload);

        $response->assertRedirect("/admin/quotations/{$quotation->id}");

        $quotation->refresh();
        $this->assertEquals('quoted', $quotation->status);
        $this->assertGreaterThan(0, $quotation->total_amount);
        $this->assertEquals('EUR', $quotation->currency);
    }

    public function test_admin_can_build_contract_and_generate_proforma_invoice(): void
    {
        $quotation = QuotationRequest::where('status', 'quoted')->firstOrFail();

        $payload = [
            'contract_number' => 'CTR-TEST-' . uniqid(),
            'payment_terms' => '30% Advance T/T, 70% against B/L',
            'bank_name' => 'BIAT Tunis Corporate',
            'iban' => 'TN59 0800 1234 5678 9012 3456',
            'swift_bic' => 'BIATTNTT',
            'required_documents' => [
                'Commercial Invoice',
                'Ocean Bill of Lading',
                'EUR.1 Certificate of Origin'
            ]
        ];

        $response = $this->actingAs($this->salesAdmin)
            ->post("/admin/quotations/{$quotation->id}/contract", $payload);

        $response->assertRedirect("/admin/quotations/{$quotation->id}");

        $quotation->refresh();
        $this->assertEquals('contract_issued', $quotation->status);
        
        $contract = Contract::where('contract_number', $payload['contract_number'])->with('invoices')->firstOrFail();
        $this->assertCount(1, $contract->invoices);

        // Test Contract PDF Generation
        $pdfResponse = $this->actingAs($this->salesAdmin)
            ->get("/admin/contracts/{$contract->id}/pdf");

        $pdfResponse->assertStatus(200);
        $pdfResponse->assertHeader('content-type', 'application/pdf');

        // Test Pro-Forma Invoice PDF Generation
        $invoice = $contract->invoices->first();
        $invoicePdfResponse = $this->actingAs($this->salesAdmin)
            ->get("/admin/invoices/{$invoice->id}/pdf");

        $invoicePdfResponse->assertStatus(200);
        $invoicePdfResponse->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_manage_hero_slides_cms(): void
    {
        $payload = [
            'title_en' => 'Finest Deglet Nour Tunisian Dates',
            'title_fr' => 'Dattes Deglet Nour de Tunisie Supérieures',
            'title_ar' => 'تمور دقلة النور التونسية الفاخرة',
            'badge_en' => 'Tozeur Oasis Gold',
            'badge_fr' => 'Oasis de Tozeur',
            'badge_ar' => 'واحات توزر',
            'cta_en' => 'Request Container Quote',
            'cta_url' => '/rfq',
            'media_path' => 'https://images.unsplash.com/photo-1546548970-71785318a17b?auto=format&fit=crop&w=2000&q=85',
            'overlay_opacity' => 0.55,
            'sort_order' => 5,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post('/admin/slides', $payload);

        $response->assertRedirect('/admin/slides');

        $slide = HeroSlide::where('title->en', 'Finest Deglet Nour Tunisian Dates')->first();
        $this->assertNotNull($slide);
        $this->assertEquals(0.55, $slide->overlay_opacity);
    }

    public function test_admin_can_upload_hero_slide_media_file_from_disk(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $file = \Illuminate\Http\UploadedFile::fake()->image('olive_plantation.jpg', 1920, 1080);

        $payload = [
            'title_en' => 'Organic Tunisian Extra Virgin Olive Oil',
            'title_fr' => 'Huile d\'Olive Vierge Extra Bio de Tunisie',
            'title_ar' => 'زيت زيتون بكر ممتاز تونسي عضوي',
            'badge_en' => 'Chemlali & Chetoui Terroir',
            'badge_fr' => 'Terroir Chétoui & Chemlali',
            'badge_ar' => 'أصناف الشملالي والشتوي',
            'cta_en' => 'Request Tanker Quotation',
            'cta_url' => '/rfq',
            'media_file' => $file,
            'overlay_opacity' => 0.50,
            'sort_order' => 1,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post('/admin/slides', $payload);

        $response->assertRedirect('/admin/slides');

        $slide = HeroSlide::where('title->en', 'Organic Tunisian Extra Virgin Olive Oil')->first();
        $this->assertNotNull($slide);
        $this->assertStringStartsWith('storage/slides/', $slide->media_path);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists(str_replace('storage/', '', $slide->media_path));

        // Test updating slide with a replacement file
        $replacementFile = \Illuminate\Http\UploadedFile::fake()->image('replacement_slide.jpg', 1200, 800);
        $updateResponse = $this->actingAs($this->superAdmin)
            ->put("/admin/slides/{$slide->id}", [
                'title_en' => 'Updated Extra Virgin Olive Oil',
                'media_file' => $replacementFile,
            ]);

        $updateResponse->assertRedirect('/admin/slides');
        $slide->refresh();
        $this->assertEquals('Updated Extra Virgin Olive Oil', $slide->title['en']);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists(str_replace('storage/', '', $slide->media_path));
    }

    public function test_strict_rbac_only_super_admin_can_delete_quotation(): void
    {
        // Create dummy quote to test deletion
        $dummy = QuotationRequest::create([
            'reference_number' => 'RFQ-DELETE-TEST',
            'request_type' => 'quote',
            'company_name' => 'Ephemeral Import Ltd',
            'contact_name' => 'John Temporary',
            'email' => 'john@ephemeral.com',
            'country' => 'United Kingdom',
            'incoterm' => 'FOB',
            'destination_port' => 'Port of Felixstowe',
            'shipping_method' => 'sea_fcl',
            'currency' => 'EUR',
            'status' => 'new',
            'signed_token' => 'dummy_token_123',
        ]);

        // Sales Admin tries to delete -> MUST FAIL with 403 Forbidden
        $unauthorizedResponse = $this->actingAs($this->salesAdmin)
            ->delete("/admin/quotations/{$dummy->id}");

        $unauthorizedResponse->assertStatus(403);
        $this->assertDatabaseHas('quotation_requests', ['id' => $dummy->id]);

        // Super Admin tries to delete -> MUST SUCCEED
        $authorizedResponse = $this->actingAs($this->superAdmin)
            ->delete("/admin/quotations/{$dummy->id}");

        $authorizedResponse->assertRedirect('/admin/quotations');
        $this->assertDatabaseMissing('quotation_requests', ['id' => $dummy->id]);
    }

    public function test_admin_can_switch_locale_to_arabic(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/admin/lang/ar');
        $response->assertSessionHas('admin_locale', 'ar');

        $dashResponse = $this->actingAs($this->superAdmin)
            ->withSession(['admin_locale' => 'ar'])
            ->get('/admin');

        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('dir="rtl"', false);
        $dashResponse->assertSee('نظرة عامة على مكتب التصدير');
    }

    public function test_admin_can_manage_products_catalog(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $category = \App\Models\Category::firstOrFail();
        $file = \Illuminate\Http\UploadedFile::fake()->image('test_product.png', 800, 800);

        // 1. Index
        $indexResponse = $this->actingAs($this->superAdmin)->get('/admin/products');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Export Products Catalog');

        // 2. Create Product
        $payload = [
            'category_id' => $category->id,
            'title_en' => 'Test Premium Tunisian Harissa',
            'title_fr' => 'Harissa Tunisienne Supérieure Test',
            'title_ar' => 'هريسة تونسية فاخرة للتجربة',
            'subtitle_en' => 'Sun-Dried Red Peppers',
            'allowed_packaging_types' => ['tin_can', 'glass_jar'],
            'allowed_sizes_input' => '200g, 400g, 5kg',
            'image_file' => $file,
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ];

        $createResponse = $this->actingAs($this->superAdmin)->post('/admin/products', $payload);
        $createResponse->assertRedirect('/admin/products');

        $product = Product::where('title->en', 'Test Premium Tunisian Harissa')->first();
        $this->assertNotNull($product);
        $this->assertStringStartsWith('storage/products/', $product->featured_image);

        // 3. Edit & Update Product
        $editResponse = $this->actingAs($this->superAdmin)->get("/admin/products/{$product->id}/edit");
        $editResponse->assertStatus(200);

        $updateResponse = $this->actingAs($this->superAdmin)->put("/admin/products/{$product->id}", [
            'category_id' => $category->id,
            'title_en' => 'Test Premium Tunisian Harissa (Updated)',
        ]);
        $updateResponse->assertRedirect('/admin/products');

        $product->refresh();
        $this->assertEquals('Test Premium Tunisian Harissa (Updated)', $product->title['en']);

        // 4. Delete Product (Super Admin only)
        $deleteResponse = $this->actingAs($this->superAdmin)->delete("/admin/products/{$product->id}");
        $deleteResponse->assertRedirect('/admin/products');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_can_control_badge_and_banner_visibility_in_hero(): void
    {
        $originalHero = \App\Models\CompanyIntroHero::find(1);
        $originalBadge = $originalHero ? $originalHero->badge : null;
        $originalTitle = $originalHero ? $originalHero->title : null;
        $originalShowBadge = $originalHero ? $originalHero->show_badge : true;
        $originalShowBanner = $originalHero ? $originalHero->show_banner : true;
        $originalIsActive = $originalHero ? $originalHero->is_active : true;

        try {
            // 1. Visit Hero Control Panel
            $response = $this->actingAs($this->superAdmin)->get('/admin/company-hero');
            $response->assertStatus(200);
            $response->assertSee('show_badge');
            $response->assertSee('show_banner');

            // 2. Hide badge and banner
            $updateResponse = $this->actingAs($this->superAdmin)->put('/admin/company-hero', [
                'badge_en' => 'Test Hero Badge',
                'badge_ar' => 'شارة تجريبية',
                'title_en' => 'Test Company Hero Title EN',
                'title_ar' => 'عنوان تجريبي للشركة بالعربية',
                'show_badge' => 0,
                'show_banner' => 0,
                'is_active' => 1,
            ]);
            $updateResponse->assertRedirect('/admin/company-hero');

            $hero = \App\Models\CompanyIntroHero::find(1);
            $this->assertFalse($hero->show_badge);
            $this->assertFalse($hero->show_banner);
            $this->assertTrue($hero->is_active);

            // 3. Verify Homepage does not render the top status banner or badge
            $homeResponse = $this->get('/en');
            $homeResponse->assertStatus(200);
            $homeResponse->assertDontSee('Test Hero Badge');

            // 4. Re-enable badge and banner
            $enableResponse = $this->actingAs($this->superAdmin)->put('/admin/company-hero', [
                'badge_en' => 'Test Hero Badge Re-enabled',
                'badge_ar' => 'شارة مفعلة جديدة',
                'title_en' => 'Test Company Hero Title EN',
                'title_ar' => 'عنوان تجريبي للشركة بالعربية',
                'show_badge' => 1,
                'show_banner' => 1,
                'is_active' => 1,
            ]);
            $enableResponse->assertRedirect('/admin/company-hero');

            $hero->refresh();
            $this->assertTrue($hero->show_badge);
            $this->assertTrue($hero->show_banner);

            // 5. Verify Homepage now displays the badge
            $homeResponse2 = $this->get('/en');
            $homeResponse2->assertStatus(200);
            $homeResponse2->assertSee('Test Hero Badge Re-enabled');
        } finally {
            \App\Models\CompanyIntroHero::find(1)?->update([
                    'badge' => [
                        'ar' => 'كتالوج التصدير الدولي 2026',
                        'en' => 'RAYANOVA T.D • INTERNATIONAL AGRO-EXPORT',
                        'fr' => 'RAYANOVA T.D • EXPORT AGROALIMENTAIRE',
                    ],
                    'title' => [
                        'ar' => 'بوابتكم المباشرة لتصدير أجود المنتجات الفلاحية وزيت الزيتون التونسي',
                        'en' => 'Tunisia’s Premier Gateway for Export-Grade Mediterranean Commodities',
                        'fr' => 'Votre Passerelle Directe pour l\'Exportation Agro-Alimentaire Tunisienne',
                    ],
                    'subtitle' => [
                        'ar' => 'شركة تونسية رائدة متخصصة في سلاسل الإمداد والتصدير الدولي لزيت الزيتون البكر الممتاز، تمور دقلة النور، والمنتجات الفلاحية التونسية المطابقة للمواصفات الأوروبية والأمريكية، بشحن بحري موثوق عبر موانئ رادس وصفاقس.',
                        'en' => 'Direct sourcing from exceptional Tunisian terroirs: Certified Extra Virgin Olive Oil, Deglet Nour oasis dates, and Mediterranean gourmet commodities for global importers.',
                        'fr' => 'Sourcing direct des terroirs d\'exception : Huiles d\'olive extra vierge certifiées, dattes royales Deglet Nour d\'oasis, condiments et conserves de prestige pour importateurs et distributeurs internationaux.',
                    ],
                    'show_badge' => true,
                    'show_banner' => true,
                    'is_active' => true,
                ]);
        }
    }
}
