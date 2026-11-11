<?php

namespace Tests\Feature\Analytics;

use App\Models\Asset;
use App\Models\PageView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageViewTrackingTest extends TestCase
{
    use RefreshDatabase;

    /** @var array<string, string> */
    private array $browser = [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36',
    ];

    public function test_a_public_page_view_is_recorded(): void
    {
        $this->get('/about', $this->browser)->assertOk();

        $this->assertDatabaseCount('page_views', 1);
        $this->assertDatabaseHas('page_views', ['path' => '/about']);
    }

    public function test_visitor_is_fingerprinted_without_storing_the_ip(): void
    {
        $this->get('/about', $this->browser)->assertOk();

        $view = PageView::firstOrFail();
        $this->assertNotNull($view->visitor_hash);
        $this->assertSame(64, strlen($view->visitor_hash));
        $this->assertStringNotContainsString('127.0.0.1', $view->visitor_hash);
    }

    public function test_bots_are_not_counted(): void
    {
        $this->get('/about', ['User-Agent' => 'Googlebot/2.1 (+http://www.google.com/bot.html)'])->assertOk();

        $this->assertDatabaseCount('page_views', 0);
    }

    public function test_do_not_track_is_honoured(): void
    {
        $this->get('/about', $this->browser + ['DNT' => '1'])->assertOk();

        $this->assertDatabaseCount('page_views', 0);
    }

    public function test_the_staff_dashboard_is_not_tracked(): void
    {
        $this->actingAs(User::factory()->staff()->create())
            ->get('/staff', $this->browser)->assertOk();

        $this->assertDatabaseCount('page_views', 0);
    }

    public function test_non_get_requests_are_not_tracked(): void
    {
        $this->post('/feedback', [], $this->browser);

        $this->assertDatabaseCount('page_views', 0);
    }

    public function test_missing_pages_are_not_tracked(): void
    {
        $this->get('/listings/does-not-exist', $this->browser)->assertNotFound();

        $this->assertDatabaseCount('page_views', 0);
    }

    public function test_listing_views_are_linked_to_their_asset(): void
    {
        $asset = Asset::factory()->create();

        $this->get('/listings/'.$asset->slug, $this->browser)->assertOk();

        $this->assertDatabaseHas('page_views', [
            'path' => '/listings/'.$asset->slug,
            'asset_id' => $asset->id,
        ]);
    }

    public function test_an_external_referrer_is_recorded_but_same_site_is_not(): void
    {
        $this->get('/about', $this->browser + ['referer' => 'https://www.google.com/search?q=credence'])->assertOk();
        $this->get('/contact', $this->browser + ['referer' => 'http://localhost/about'])->assertOk();

        $this->assertDatabaseHas('page_views', ['path' => '/about', 'referer_host' => 'google.com']);
        $this->assertDatabaseHas('page_views', ['path' => '/contact', 'referer_host' => null]);
    }
}
