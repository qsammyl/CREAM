<?php

namespace Tests\Feature\Public;

use App\Mail\EnquiryReceivedMail;
use App\Models\Asset;
use App\Models\Enquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EnquirySubmissionTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'type' => 'contact',
            'name' => 'Kofi Annan',
            'email' => 'kofi@example.com',
            'phone' => '+233 24 000 0000',
            'country' => 'Ghana',
            'message' => 'I would like to buy a home in East Legon.',
            'enquiry_type' => 'Buy a Property',
            'budget' => '300K – 500K',
            'timeline' => '3 – 6 months',
        ], $overrides);
    }

    public function test_contact_page_form_posts_to_enquiries(): void
    {
        $this->get('/contact')->assertOk()->assertSee(route('enquiries.store'));
    }

    public function test_enquiry_is_stored_and_notification_queued(): void
    {
        Mail::fake();

        $this->post('/enquiries', $this->payload())
            ->assertRedirect()
            ->assertSessionHas('enquiry_status', 'sent');

        $e = Enquiry::first();
        $this->assertNotNull($e);
        $this->assertSame('contact', $e->type->value);
        $this->assertSame('new', $e->status->value);
        $this->assertStringStartsWith('ENQ-', $e->reference);
        // Type-specific fields land in details, not columns.
        $this->assertSame('300K – 500K', $e->details['budget']);
        $this->assertSame('3 – 6 months', $e->details['timeline']);

        Mail::assertQueued(EnquiryReceivedMail::class);
    }

    public function test_ajax_submission_returns_json(): void
    {
        Mail::fake();

        $this->postJson('/enquiries', $this->payload())
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertSame(1, Enquiry::count());
    }

    public function test_honeypot_silently_discards_and_sends_nothing(): void
    {
        Mail::fake();

        $this->post('/enquiries', $this->payload(['botcheck' => '1']))
            ->assertRedirect()
            ->assertSessionHas('enquiry_status', 'sent');

        $this->assertSame(0, Enquiry::count());
        Mail::assertNothingQueued();
    }

    public function test_name_is_required(): void
    {
        $this->post('/enquiries', $this->payload(['name' => '']))->assertSessionHasErrors('name');
        $this->assertSame(0, Enquiry::count());
    }

    public function test_email_or_phone_is_required(): void
    {
        $this->post('/enquiries', $this->payload(['email' => '', 'phone' => '']))
            ->assertSessionHasErrors(['email', 'phone']);
    }

    public function test_phone_only_submission_is_accepted(): void
    {
        Mail::fake();

        $this->post('/enquiries', $this->payload(['type' => 'short_stay', 'email' => '', 'phone' => '+233240000000']))
            ->assertRedirect();

        $this->assertSame(1, Enquiry::count());
    }

    public function test_listing_enquiry_links_the_asset(): void
    {
        Mail::fake();
        $asset = Asset::factory()->create();

        $this->post('/enquiries', $this->payload([
            'type' => 'listing', 'asset_id' => $asset->id,
            'subject' => 'Property enquiry — '.$asset->title,
        ]));

        $this->assertSame($asset->id, Enquiry::first()->asset_id);
    }

    public function test_newsletter_opt_in_is_recorded(): void
    {
        Mail::fake();

        $this->post('/enquiries', $this->payload(['newsletter' => '1']));

        $this->assertTrue(Enquiry::first()->newsletter_opt_in);
    }

    public function test_rate_limited_after_six_submissions(): void
    {
        Mail::fake();

        for ($i = 0; $i < 6; $i++) {
            $this->post('/enquiries', $this->payload())->assertRedirect();
        }

        $this->post('/enquiries', $this->payload())->assertStatus(429);
    }
}
