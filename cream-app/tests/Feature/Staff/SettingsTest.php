<?php

namespace Tests\Feature\Staff;

use App\Models\Setting;
use App\Models\User;
use App\Support\SiteSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create(['two_factor_confirmed_at' => now()]);
    }

    public function test_staff_cannot_reach_settings(): void
    {
        $this->actingAs(User::factory()->staff()->create())
            ->get('/staff/settings')->assertForbidden();
    }

    public function test_admin_can_view_settings(): void
    {
        $this->actingAs($this->admin())->get('/staff/settings')->assertOk();
    }

    public function test_admin_can_save_settings_and_they_overlay_config(): void
    {
        $this->actingAs($this->admin())
            ->put('/staff/settings', [
                'phone_display' => '+233 99 999 9999',
                'email' => 'newdesk@credence.test',
                'retention_enquiries_days' => '90',
            ])
            ->assertRedirect()
            ->assertSessionHas('flash');

        $this->assertDatabaseHas('settings', ['key' => 'phone_display']);

        // The overlay applies the stored value over the config default.
        SiteSettings::apply();
        $this->assertSame('+233 99 999 9999', config('cream.phone.display'));
        $this->assertSame('newdesk@credence.test', config('cream.email'));
        $this->assertSame(90, (int) Setting::get('retention_enquiries_days'));
    }

    public function test_invalid_email_is_rejected(): void
    {
        $this->actingAs($this->admin())
            ->put('/staff/settings', ['email' => 'not-an-email'])
            ->assertSessionHasErrors('email');
    }
}
