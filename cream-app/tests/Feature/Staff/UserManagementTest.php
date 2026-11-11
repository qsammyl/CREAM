<?php

namespace Tests\Feature\Staff;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create(['two_factor_confirmed_at' => now()]);
    }

    public function test_staff_cannot_reach_user_management(): void
    {
        $this->actingAs(User::factory()->staff()->create())
            ->get('/staff/users')->assertForbidden();
    }

    public function test_admin_sees_the_user_list(): void
    {
        $admin = $this->admin();
        User::factory()->create(['name' => 'LISTED-PERSON']);

        $this->actingAs($admin)->get('/staff/users')->assertOk()->assertSee('LISTED-PERSON');
    }

    public function test_admin_can_change_role_and_status(): void
    {
        $target = User::factory()->staff()->create();

        $this->actingAs($this->admin())->put('/staff/users/'.$target->id, [
            'name' => $target->name,
            'email' => $target->email,
            'role' => 'admin',
            'is_active' => '1',
        ])->assertRedirect(route('staff.users.index'));

        $this->assertDatabaseHas('users', ['id' => $target->id, 'role' => 'admin', 'is_active' => true]);
    }

    public function test_admin_can_deactivate_another_user(): void
    {
        $target = User::factory()->staff()->create();

        $this->actingAs($this->admin())->put('/staff/users/'.$target->id, [
            'name' => $target->name, 'email' => $target->email, 'role' => 'staff',
            // is_active omitted = unchecked
        ])->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $target->id, 'is_active' => false]);
    }

    public function test_admin_cannot_demote_or_deactivate_self(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->put('/staff/users/'.$admin->id, [
            'name' => $admin->name, 'email' => $admin->email, 'role' => 'staff',
        ])->assertSessionHas('flash_error');

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin', 'is_active' => true]);
    }

    public function test_admin_cannot_delete_self(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->delete('/staff/users/'.$admin->id)->assertSessionHas('flash_error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_another_user(): void
    {
        $target = User::factory()->staff()->create();

        $this->actingAs($this->admin())->delete('/staff/users/'.$target->id)
            ->assertRedirect(route('staff.users.index'));

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }
}
