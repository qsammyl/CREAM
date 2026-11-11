<?php

namespace Tests\Feature\Auth;

use App\Models\StaffInvitation;
use Illuminate\Contracts\Validation\UncompromisedVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcceptInvitationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Avoid the Have-I-Been-Pwned network call for uncompromised().
        $this->app->instance(UncompromisedVerifier::class, new class implements UncompromisedVerifier
        {
            public function verify($data)
            {
                return true;
            }
        });
    }

    private function invite(string $plain, array $overrides = []): StaffInvitation
    {
        return StaffInvitation::create(array_merge([
            'email' => 'invitee@example.com',
            'role' => 'staff',
            'token' => hash('sha256', $plain),
            'expires_at' => now()->addDays(7),
        ], $overrides));
    }

    public function test_valid_token_shows_the_form(): void
    {
        $this->invite('goodtoken');

        $this->get('/staff/invite/goodtoken')->assertOk()->assertSee('invitee@example.com');
    }

    public function test_unknown_token_is_404(): void
    {
        $this->get('/staff/invite/nope')->assertNotFound();
    }

    public function test_expired_token_is_gone(): void
    {
        $this->invite('expiredtoken', ['expires_at' => now()->subDay()]);

        $this->get('/staff/invite/expiredtoken')->assertStatus(410);
    }

    public function test_accepting_creates_an_active_user_and_consumes_the_invite(): void
    {
        $inv = $this->invite('accepttoken', ['role' => 'admin']);

        $this->post('/staff/invite/accepttoken', [
            'name' => 'Brand New',
            'password' => 'a-very-strong-passphrase',
            'password_confirmation' => 'a-very-strong-passphrase',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'email' => 'invitee@example.com', 'role' => 'admin', 'is_active' => true,
        ]);
        $this->assertNotNull($inv->fresh()->accepted_at);

        // The token is single-use — it no longer resolves.
        $this->get('/staff/invite/accepttoken')->assertStatus(410);
    }

    public function test_weak_password_is_rejected(): void
    {
        $this->invite('weaktoken');

        $this->post('/staff/invite/weaktoken', [
            'name' => 'Brand New',
            'password' => 'short',
            'password_confirmation' => 'short',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'invitee@example.com']);
    }
}
