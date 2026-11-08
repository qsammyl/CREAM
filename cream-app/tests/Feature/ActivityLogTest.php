<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_login_is_recorded_and_updates_last_login(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password-12345')]);

        $this->post('/staff/login', [
            'email' => $user->email,
            'password' => 'password-12345',
        ]);

        $this->assertDatabaseHas('activity_log', [
            'user_id' => $user->id,
            'action' => 'login',
        ]);

        $this->assertNotNull($user->fresh()->last_login_at);
    }

    public function test_failed_login_is_recorded_without_storing_raw_ip(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password-12345')]);

        $this->post('/staff/login', [
            'email' => $user->email,
            'password' => 'wrong',
        ]);

        $this->assertDatabaseHas('activity_log', ['action' => 'failed_login']);

        // IP is only ever stored hashed (64 hex chars), never raw.
        $log = \App\Models\ActivityLog::where('action', 'failed_login')->first();
        $this->assertTrue($log->ip_hash === null || preg_match('/^[0-9a-f]{64}$/', $log->ip_hash) === 1);
    }
}
