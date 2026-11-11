<?php

namespace Tests\Feature\Staff;

use App\Mail\StaffInvitationMail;
use App\Models\StaffInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create(['two_factor_confirmed_at' => now()]);
    }

    public function test_staff_cannot_invite(): void
    {
        $this->actingAs(User::factory()->staff()->create())
            ->post('/staff/invitations', ['email' => 'x@example.com', 'role' => 'staff'])
            ->assertForbidden();
    }

    public function test_admin_can_send_an_invitation(): void
    {
        Mail::fake();

        $this->actingAs($this->admin())
            ->post('/staff/invitations', ['email' => 'New.Person@Example.com', 'role' => 'staff'])
            ->assertRedirect()
            ->assertSessionHas('flash');

        $inv = StaffInvitation::first();
        $this->assertNotNull($inv);
        $this->assertSame('new.person@example.com', $inv->email);
        $this->assertSame(64, strlen($inv->token)); // sha-256 hex, not the raw token
        $this->assertNull($inv->accepted_at);

        Mail::assertQueued(StaffInvitationMail::class);
    }

    public function test_cannot_invite_an_existing_user_email(): void
    {
        $existing = User::factory()->create();

        $this->actingAs($this->admin())
            ->post('/staff/invitations', ['email' => $existing->email, 'role' => 'staff'])
            ->assertSessionHasErrors('email');

        $this->assertSame(0, StaffInvitation::count());
    }

    public function test_resend_rotates_the_token(): void
    {
        Mail::fake();
        $inv = StaffInvitation::create([
            'email' => 'p@example.com', 'role' => 'staff',
            'token' => hash('sha256', 'original'), 'expires_at' => now()->addDay(),
        ]);

        $this->actingAs($this->admin())->post('/staff/invitations/'.$inv->id.'/resend')->assertRedirect();

        $this->assertNotSame(hash('sha256', 'original'), $inv->fresh()->token);
        Mail::assertQueued(StaffInvitationMail::class);
    }

    public function test_revoke_deletes_the_invitation(): void
    {
        $inv = StaffInvitation::create([
            'email' => 'p@example.com', 'role' => 'staff',
            'token' => hash('sha256', 'tok'), 'expires_at' => now()->addDay(),
        ]);

        $this->actingAs($this->admin())->delete('/staff/invitations/'.$inv->id)->assertRedirect();

        $this->assertDatabaseMissing('staff_invitations', ['id' => $inv->id]);
    }
}
