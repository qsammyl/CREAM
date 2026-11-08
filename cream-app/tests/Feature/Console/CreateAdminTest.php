<?php

namespace Tests\Feature\Console;

use App\Models\User;
use Illuminate\Contracts\Validation\UncompromisedVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateAdminTest extends TestCase
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

    public function test_it_creates_an_active_admin(): void
    {
        $this->artisan('cream:create-admin')
            ->expectsQuestion('Full name', 'Jane Admin')
            ->expectsQuestion('Email address', 'jane@example.com')
            ->expectsQuestion('Password (min 12 chars, checked against known breaches)', 'a-very-strong-passphrase')
            ->expectsQuestion('Confirm password', 'a-very-strong-passphrase')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
            'role' => 'admin',
            'is_active' => true,
        ]);

        $user = User::where('email', 'jane@example.com')->first();
        $this->assertTrue($user->isAdmin());
    }

    public function test_it_rejects_a_short_password(): void
    {
        $this->artisan('cream:create-admin')
            ->expectsQuestion('Full name', 'Jane Admin')
            ->expectsQuestion('Email address', 'jane@example.com')
            ->expectsQuestion('Password (min 12 chars, checked against known breaches)', 'short')
            ->expectsQuestion('Confirm password', 'short')
            ->assertExitCode(1);

        $this->assertDatabaseMissing('users', ['email' => 'jane@example.com']);
    }
}
