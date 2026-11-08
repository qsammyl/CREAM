<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_renders(): void
    {
        $this->get('/staff/login')->assertOk()->assertSee('Sign in');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password-12345')]);

        $response = $this->post('/staff/login', [
            'email' => $user->email,
            'password' => 'password-12345',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/staff');
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password-12345')]);

        $response = $this->from('/staff/login')->post('/staff/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/staff/login');
        $response->assertSessionHasErrors('email');
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->inactive()->create(['password' => Hash::make('password-12345')]);

        $this->from('/staff/login')->post('/staff/login', [
            'email' => $user->email,
            'password' => 'password-12345',
        ]);

        $this->assertGuest();
    }

    public function test_dashboard_requires_authentication(): void
    {
        $this->get('/staff')->assertRedirect('/staff/login');
    }
}
