<?php

namespace Tests\Feature\Public;

use App\Models\Asset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_html_urls_redirect_to_clean(): void
    {
        $this->get('/about.html')->assertRedirect('/about');
        $this->get('/properties/buy.html')->assertRedirect('/properties/buy');
        $this->get('/index.html')->assertRedirect('/');
    }

    public function test_changing_slug_creates_301_redirect(): void
    {
        $a = Asset::factory()->create(['slug' => 'old-slug']);

        $a->update(['slug' => 'new-slug']);

        $this->assertDatabaseHas('asset_redirects', ['old_slug' => 'old-slug', 'asset_id' => $a->id]);

        $response = $this->get('/listings/old-slug');
        $response->assertStatus(301);
        $response->assertRedirect('/listings/new-slug');
    }
}
