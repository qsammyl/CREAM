<?php

namespace Tests\Feature\Staff;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create(['two_factor_confirmed_at' => now()]);
    }

    private function staff(): User
    {
        return User::factory()->staff()->create();
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Test Home',
            'category' => 'sale',
            'status' => 'draft',
            'currency' => 'USD',
            'price_period' => 'total',
            'country' => 'Ghana',
        ], $overrides);
    }

    public function test_guest_cannot_reach_asset_admin(): void
    {
        $this->get('/staff/assets')->assertRedirect('/staff/login');
    }

    public function test_all_dashboard_asset_screens_render(): void
    {
        $staff = $this->staff();
        $asset = Asset::factory()->create();

        $this->actingAs($staff)->get('/staff/assets')->assertOk();
        $this->actingAs($staff)->get('/staff/assets/create')->assertOk();
        $this->actingAs($staff)->get('/staff/assets/'.$asset->slug.'/edit')->assertOk()->assertSee($asset->reference);
        $this->actingAs($staff)->get('/staff/assets/bin')->assertOk();
        $this->actingAs($staff)->get('/staff/assets/'.$asset->slug.'/preview')->assertOk();
    }

    public function test_staff_can_create_a_draft(): void
    {
        $this->actingAs($this->staff())
            ->post('/staff/assets', $this->payload())
            ->assertRedirect();

        $this->assertDatabaseHas('assets', ['title' => 'Test Home', 'status' => 'draft']);
    }

    public function test_publish_button_publishes_and_stamps_date(): void
    {
        $this->actingAs($this->staff())
            ->post('/staff/assets', $this->payload(['title' => 'Live Home', 'publish' => '1']));

        $asset = Asset::where('title', 'Live Home')->first();
        $this->assertSame('published', $asset->status->value);
        $this->assertNotNull($asset->published_at);
    }

    public function test_auto_reference_and_slug_are_generated(): void
    {
        $this->actingAs($this->staff())->post('/staff/assets', $this->payload(['title' => 'Royal Court']));

        $asset = Asset::where('title', 'Royal Court')->first();
        $this->assertSame('royal-court', $asset->slug);
        $this->assertMatchesRegularExpression('/^CR-RC-\d{2}$/', $asset->reference);
    }

    public function test_delete_soft_deletes_to_bin(): void
    {
        $a = Asset::factory()->create();

        $this->actingAs($this->staff())->delete('/staff/assets/'.$a->slug)->assertRedirect();

        $this->assertSoftDeleted('assets', ['id' => $a->id]);
    }

    public function test_staff_cannot_restore_or_force_delete(): void
    {
        $a = Asset::factory()->create();
        $a->delete();

        $this->actingAs($this->staff())->post('/staff/assets/'.$a->id.'/restore')->assertForbidden();
        $this->actingAs($this->staff())->delete('/staff/assets/'.$a->id.'/force')->assertForbidden();
    }

    public function test_admin_can_restore(): void
    {
        $a = Asset::factory()->create();
        $a->delete();

        $this->actingAs($this->admin())->post('/staff/assets/'.$a->id.'/restore')->assertRedirect();

        $this->assertDatabaseHas('assets', ['id' => $a->id, 'deleted_at' => null]);
    }

    public function test_duplicate_creates_a_draft_copy(): void
    {
        $a = Asset::factory()->create();

        $this->actingAs($this->staff())->post('/staff/assets/'.$a->slug.'/duplicate')->assertRedirect();

        $this->assertDatabaseHas('assets', ['title' => $a->title.' (copy)', 'status' => 'draft']);
    }
}
