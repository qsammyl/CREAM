<?php

namespace Tests\Feature\Public;

use App\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_draft_asset_is_not_public(): void
    {
        $a = Asset::factory()->draft()->create();

        $this->get('/listings/'.$a->slug)->assertNotFound();
    }

    public function test_published_asset_is_public(): void
    {
        $a = Asset::factory()->create();

        $this->get('/listings/'.$a->slug)->assertOk()->assertSee($a->reference);
    }

    public function test_sold_asset_is_excluded_from_default_grid_but_viewable_directly(): void
    {
        $sold = Asset::factory()->sold()->create();

        // Buy grid shows only published / under offer
        $this->get('/properties/buy')->assertOk()->assertDontSee($sold->reference);

        // Sold is still public and directly viewable
        $this->get('/listings/'.$sold->slug)->assertOk();
    }

    public function test_home_and_sitemap_render(): void
    {
        Asset::factory()->count(3)->create();

        $this->get('/')->assertOk();
        $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml');
    }
}
