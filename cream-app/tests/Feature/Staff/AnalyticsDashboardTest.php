<?php

namespace Tests\Feature\Staff;

use App\Models\PageView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_is_redirected_to_login(): void
    {
        $this->get('/staff/analytics')->assertRedirect('/staff/login');
    }

    public function test_any_staff_member_can_view_analytics(): void
    {
        PageView::factory()->count(4)->create();

        $this->actingAs(User::factory()->staff()->create())
            ->get('/staff/analytics')
            ->assertOk()
            ->assertSee('Page views')
            ->assertSee('Daily traffic');
    }

    public function test_totals_count_views_and_daily_unique_visitors(): void
    {
        // Today: 3 views from 2 distinct visitors.
        PageView::factory()->visitor('aaa')->create();
        PageView::factory()->visitor('aaa')->create();
        PageView::factory()->visitor('bbb')->create();
        // Older than the 7-day window — excluded.
        PageView::factory()->visitor('ccc')->on(now()->subDays(40))->create();

        $this->actingAs(User::factory()->staff()->create())
            ->get('/staff/analytics?days=7')
            ->assertOk()
            ->assertViewHas('totalViews', 3)
            ->assertViewHas('totalVisitors', 2);
    }

    public function test_an_invalid_range_falls_back_to_thirty_days(): void
    {
        $this->actingAs(User::factory()->staff()->create())
            ->get('/staff/analytics?days=999')
            ->assertOk()
            ->assertViewHas('days', 30);
    }
}
