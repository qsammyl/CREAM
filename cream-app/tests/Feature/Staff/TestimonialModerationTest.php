<?php

namespace Tests\Feature\Staff;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialModerationTest extends TestCase
{
    use RefreshDatabase;

    private function staff(): User
    {
        return User::factory()->staff()->create();
    }

    public function test_guest_cannot_reach_moderation(): void
    {
        $this->get('/staff/testimonials')->assertRedirect('/staff/login');
    }

    public function test_moderation_screen_renders(): void
    {
        Testimonial::factory()->create();

        $this->actingAs($this->staff())->get('/staff/testimonials')->assertOk();
    }

    public function test_consented_testimonial_can_be_published_and_appears_publicly(): void
    {
        $t = Testimonial::factory()->create(['consent' => true, 'body' => 'SHINING-REVIEW']);

        $this->actingAs($this->staff())->post('/staff/testimonials/'.$t->id.'/approve')->assertRedirect();

        $this->assertDatabaseHas('testimonials', ['id' => $t->id, 'status' => 'approved']);
        $this->get('/testimonials')->assertSee('SHINING-REVIEW');
    }

    public function test_approve_is_blocked_without_consent(): void
    {
        $t = Testimonial::factory()->noConsent()->create();

        $this->actingAs($this->staff())
            ->post('/staff/testimonials/'.$t->id.'/approve')
            ->assertRedirect()
            ->assertSessionHas('flash_error');

        $this->assertDatabaseHas('testimonials', ['id' => $t->id, 'status' => 'pending']);
    }

    public function test_archive_and_spam(): void
    {
        $staff = $this->staff();

        $a = Testimonial::factory()->create();
        $this->actingAs($staff)->post('/staff/testimonials/'.$a->id.'/archive')->assertRedirect();
        $this->assertDatabaseHas('testimonials', ['id' => $a->id, 'status' => 'archived']);

        $s = Testimonial::factory()->create();
        $this->actingAs($staff)->post('/staff/testimonials/'.$s->id.'/spam')->assertRedirect();
        $this->assertDatabaseHas('testimonials', ['id' => $s->id, 'status' => 'spam']);
    }

    public function test_feature_toggles_homepage_pin(): void
    {
        $t = Testimonial::factory()->approved()->create(['is_featured' => false]);

        $this->actingAs($this->staff())->post('/staff/testimonials/'.$t->id.'/feature')->assertRedirect();
        $this->assertDatabaseHas('testimonials', ['id' => $t->id, 'is_featured' => true]);

        $this->actingAs($this->staff())->post('/staff/testimonials/'.$t->id.'/feature')->assertRedirect();
        $this->assertDatabaseHas('testimonials', ['id' => $t->id, 'is_featured' => false]);
    }

    public function test_bulk_approve_skips_non_consented(): void
    {
        $ok = Testimonial::factory()->count(2)->create(['consent' => true])->pluck('id')->all();
        $blocked = Testimonial::factory()->noConsent()->create()->id;

        $this->actingAs($this->staff())
            ->post('/staff/testimonials/bulk', ['action' => 'approve', 'ids' => array_merge($ok, [$blocked])])
            ->assertRedirect();

        $this->assertSame(2, Testimonial::where('status', 'approved')->count());
        $this->assertDatabaseHas('testimonials', ['id' => $blocked, 'status' => 'pending']);
    }

    public function test_update_display_details(): void
    {
        $t = Testimonial::factory()->create(['name' => 'lower case', 'rating' => 3]);

        $this->actingAs($this->staff())->put('/staff/testimonials/'.$t->id, [
            'name' => 'Proper Name',
            'role' => 'Buyer · East Legon, 2025',
            'rating' => 5,
            'sort_order' => 2,
        ])->assertRedirect();

        $this->assertDatabaseHas('testimonials', [
            'id' => $t->id, 'name' => 'Proper Name', 'rating' => 5, 'sort_order' => 2,
        ]);
    }

    public function test_delete_soft_deletes(): void
    {
        $t = Testimonial::factory()->create();

        $this->actingAs($this->staff())->delete('/staff/testimonials/'.$t->id)->assertRedirect();

        $this->assertSoftDeleted('testimonials', ['id' => $t->id]);
    }

    public function test_qr_card_renders_with_svg_and_feedback_link(): void
    {
        $this->actingAs($this->staff())->get('/staff/testimonials/qr')
            ->assertOk()
            ->assertSee('<svg', false)
            ->assertSee('/feedback');
    }
}
