<?php

namespace Tests\Feature\Staff;

use App\Enums\EnquiryType;
use App\Models\Enquiry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnquiryInboxTest extends TestCase
{
    use RefreshDatabase;

    private function staff(): User
    {
        return User::factory()->staff()->create();
    }

    public function test_guest_cannot_reach_inbox(): void
    {
        $this->get('/staff/enquiries')->assertRedirect('/staff/login');
    }

    public function test_inbox_renders(): void
    {
        Enquiry::factory()->create();

        $this->actingAs($this->staff())->get('/staff/enquiries')->assertOk();
    }

    public function test_opening_a_new_enquiry_marks_it_read(): void
    {
        $e = Enquiry::factory()->create(); // status = new

        $this->actingAs($this->staff())->get('/staff/enquiries/'.$e->id)->assertOk();

        $this->assertDatabaseHas('enquiries', ['id' => $e->id, 'status' => 'read']);
    }

    public function test_status_can_be_updated(): void
    {
        $e = Enquiry::factory()->read()->create();

        $this->actingAs($this->staff())
            ->patch('/staff/enquiries/'.$e->id.'/status', ['status' => 'replied'])
            ->assertRedirect();

        $this->assertDatabaseHas('enquiries', ['id' => $e->id, 'status' => 'replied']);
    }

    public function test_type_filter_narrows_results(): void
    {
        Enquiry::factory()->type(EnquiryType::Sell)->create(['name' => 'SELLER-ONE']);
        Enquiry::factory()->type(EnquiryType::Contact)->create(['name' => 'CONTACT-ONE']);

        $this->actingAs($this->staff())
            ->get('/staff/enquiries?type=sell')
            ->assertOk()
            ->assertSee('SELLER-ONE')
            ->assertDontSee('CONTACT-ONE');
    }

    public function test_bulk_mark_spam(): void
    {
        $ids = Enquiry::factory()->count(3)->create()->pluck('id')->all();

        $this->actingAs($this->staff())
            ->post('/staff/enquiries/bulk', ['action' => 'spam', 'ids' => $ids])
            ->assertRedirect();

        $this->assertSame(3, Enquiry::where('status', 'spam')->count());
    }

    public function test_delete_soft_deletes(): void
    {
        $e = Enquiry::factory()->create();

        $this->actingAs($this->staff())->delete('/staff/enquiries/'.$e->id)->assertRedirect();

        $this->assertSoftDeleted('enquiries', ['id' => $e->id]);
    }
}
