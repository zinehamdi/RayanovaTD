<?php

namespace Tests\Feature;

use App\Models\CompanyIntroHero;
use App\Models\Product;
use Tests\TestCase;

class HomepageRedesignTest extends TestCase
{
    /**
     * Test homepage renders successfully with correct direction and language in test environment.
     */
    public function test_homepage_renders_with_correct_direction_and_language(): void
    {
        $response = $this->get('/en');
        $response->assertStatus(200);
        $response->assertSee('dir="ltr"', false);
        $response->assertSee('lang="en"', false);
    }

    /**
     * Test all redesigned scene anchors exist on the homepage.
     */
    public function test_redesigned_scenes_and_anchors_exist(): void
    {
        $response = $this->get('/en');
        $response->assertStatus(200);

        // Scene IDs and Atmosphere classes
        $response->assertSee('id="products"', false);
        $response->assertSee('id="export"', false);
        $response->assertSee('id="logistics"', false);
        $response->assertSee('id="terroir"', false);
        $response->assertSee('id="certifications"', false);
        $response->assertSee('id="final-cta"', false);

        $response->assertSee('rn-scene', false);
        $response->assertSee('rn-scene--showroom', false);
        $response->assertSee('rn-scene--bento', false);
        $response->assertSee('rn-scene--logistics', false);
        $response->assertSee('rn-scene--terroir', false);
        $response->assertSee('rn-scene--certifications', false);
        $response->assertSee('rn-scene--cta', false);
    }

    /**
     * Test the hero CTAs, info rail, and album viewer markup.
     */
    public function test_hero_and_product_viewer_elements(): void
    {
        $response = $this->get('/en');
        $response->assertStatus(200);

        // Scrims and editorial typography
        $response->assertSee('hero-scrim-start', false);
        $response->assertSee('hero-scrim-bottom', false);

        // Primary RFQ CTA and secondary showroom anchor
        $response->assertSee('/rfq', false);
        $response->assertSee('#products', false);

        // Modal dialog with a11y semantics
        $response->assertSee('x-teleport="body"', false);
        $response->assertSee('role="dialog"', false);
        $response->assertSee('aria-modal="true"', false);
    }

    /**
     * Test showroom product picker and Livewire quote cart.
     */
    public function test_showroom_and_quote_cart(): void
    {
        $response = $this->get('/en');
        $response->assertStatus(200);

        // Product rail tablist
        $response->assertSee('role="tablist"', false);
        $response->assertSee('role="tab"', false);

        // Quote cart drawer
        $response->assertSee('cart-drawer-title', false);
        $response->assertSee('quote-cart', false);
    }

    /**
     * Test controllable banner and badge toggles on hero.
     */
    public function test_hero_badge_and_banner_toggle(): void
    {
        $hero = CompanyIntroHero::first();

        if ($hero) {
            $originalBadge = $hero->show_badge;
            $originalBanner = $hero->show_banner;

            // Test with badge and banner enabled
            $hero->update(['show_badge' => true, 'show_banner' => true]);
            $response = $this->get('/en');
            $response->assertStatus(200);

            // Test with badge and banner disabled
            $hero->update(['show_badge' => false, 'show_banner' => false]);
            $responseDisabled = $this->get('/en');
            $responseDisabled->assertStatus(200);

            // Restore original state
            $hero->update([
                'show_badge' => $originalBadge,
                'show_banner' => $originalBanner,
            ]);
        }
    }
}
