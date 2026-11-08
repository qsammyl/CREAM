<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_cannot_access_admin_only_activity_log(): void
    {
        $staff = User::factory()->staff()->create();

        $this->actingAs($staff)->get('/staff/activity')->assertForbidden();
    }

    public function test_admin_can_access_activity_log(): void
    {
        $admin = User::factory()->admin()->create(['two_factor_confirmed_at' => now()]);

        $this->actingAs($admin)->get('/staff/activity')->assertOk();
    }
}
