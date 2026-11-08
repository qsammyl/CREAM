<?php

namespace Tests\Feature\Public;

use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeedbackSubmissionTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Nana Yaa',
            'email' => 'nana@example.com',
            'role' => 'Buyer · Accra',
            'rating' => 5,
            'body' => 'A genuinely excellent experience from start to finish.',
            'consent' => '1',
        ], $overrides);
    }

    public function test_feedback_form_renders(): void
    {
        $this->get('/feedback')->assertOk()->assertSee('Share your');
    }

    public function test_feedback_is_stored_as_pending_with_consent(): void
    {
        $this->post('/feedback', $this->payload())
            ->assertRedirect(route('feedback'))
            ->assertSessionHas('feedback_status', 'thanks');

        $t = Testimonial::first();
        $this->assertNotNull($t);
        $this->assertSame('pending', $t->status->value);
        $this->assertTrue($t->consent);
        $this->assertSame(5, $t->rating);
        // IP is stored only as an HMAC, never the raw address.
        $this->assertNotNull($t->ip_hash);
        $this->assertNotSame('127.0.0.1', $t->ip_hash);
    }

    public function test_feedback_without_consent_is_stored_but_not_publishable(): void
    {
        $this->post('/feedback', $this->payload(['consent' => null]));

        $t = Testimonial::first();
        $this->assertNotNull($t);
        $this->assertFalse($t->consent);
        $this->assertFalse($t->canPublish());
    }

    public function test_honeypot_silently_discards(): void
    {
        $this->post('/feedback', $this->payload(['website' => 'http://spam.example']))
            ->assertRedirect(route('feedback'))
            ->assertSessionHas('feedback_status', 'thanks');

        $this->assertSame(0, Testimonial::count());
    }

    public function test_validation_requires_body_and_rating(): void
    {
        $this->post('/feedback', $this->payload(['body' => '', 'rating' => '']))
            ->assertSessionHasErrors(['body', 'rating']);

        $this->assertSame(0, Testimonial::count());
    }

    public function test_rate_limited_after_three_submissions(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $this->post('/feedback', $this->payload())->assertRedirect();
        }

        $this->post('/feedback', $this->payload())->assertStatus(429);
    }

    public function test_public_testimonials_page_shows_only_approved(): void
    {
        Testimonial::factory()->approved()->create(['body' => 'PUBLISHED-STORY']);
        Testimonial::factory()->create(['body' => 'PENDING-STORY']);

        $this->get('/testimonials')
            ->assertOk()
            ->assertSee('PUBLISHED-STORY')
            ->assertDontSee('PENDING-STORY');
    }

    public function test_email_is_never_shown_on_public_page(): void
    {
        Testimonial::factory()->approved()->create([
            'name' => 'Private Person',
            'email' => 'secret@example.com',
        ]);

        $this->get('/testimonials')->assertOk()->assertDontSee('secret@example.com');
    }
}
