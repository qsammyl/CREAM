<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorRequirementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_without_two_factor_is_forced_to_profile(): void
    {
        $admin = User::factory()->admin()->create(); // no 2FA confirmed

        $this->actingAs($admin)->get('/staff')->assertRedirect(route('staff.profile'));
    }

    public function test_admin_with_two_factor_can_reach_dashboard(): void
    {
        $admin = User::factory()->admin()->create(['two_factor_confirmed_at' => now()]);

        $this->actingAs($admin)->get('/staff')->assertOk();
    }

    public function test_staff_without_two_factor_can_reach_dashboard(): void
    {
        $staff = User::factory()->staff()->create();

        $this->actingAs($staff)->get('/staff')->assertOk();
    }
}
