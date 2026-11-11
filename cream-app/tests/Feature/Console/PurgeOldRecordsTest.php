<?php

namespace Tests\Feature\Console;

use App\Models\Comment;
use App\Models\Enquiry;
use App\Models\PageView;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurgeOldRecordsTest extends TestCase
{
    use RefreshDatabase;

    public function test_nothing_is_purged_when_retention_is_zero(): void
    {
        Enquiry::factory()->spam()->create(['updated_at' => now()->subYear()]);

        $this->artisan('cream:purge-old-records')->assertExitCode(0);

        $this->assertSame(1, Enquiry::withTrashed()->count());
    }

    public function test_old_spam_and_closed_records_are_purged(): void
    {
        Setting::put('retention_enquiries_days', 30);
        Setting::put('retention_comments_days', 30);

        $oldSpam = Enquiry::factory()->spam()->create(['updated_at' => now()->subDays(60)]);
        $oldClosed = Enquiry::factory()->create(['status' => 'closed', 'updated_at' => now()->subDays(60)]);
        $recentSpam = Enquiry::factory()->spam()->create(['updated_at' => now()->subDays(5)]);
        $newEnquiry = Enquiry::factory()->create(['updated_at' => now()->subDays(60)]); // status new — kept

        $oldSpamComment = Comment::factory()->spam()->create(['updated_at' => now()->subDays(60)]);
        $recentSpamComment = Comment::factory()->spam()->create(['updated_at' => now()->subDays(5)]);

        $this->artisan('cream:purge-old-records')->assertExitCode(0);

        $this->assertDatabaseMissing('enquiries', ['id' => $oldSpam->id]);
        $this->assertDatabaseMissing('enquiries', ['id' => $oldClosed->id]);
        $this->assertDatabaseHas('enquiries', ['id' => $recentSpam->id]);
        $this->assertDatabaseHas('enquiries', ['id' => $newEnquiry->id]);

        $this->assertDatabaseMissing('comments', ['id' => $oldSpamComment->id]);
        $this->assertDatabaseHas('comments', ['id' => $recentSpamComment->id]);
    }

    public function test_old_page_views_are_purged_but_recent_ones_kept(): void
    {
        Setting::put('retention_analytics_days', 30);

        $old = PageView::factory()->on(now()->subDays(60))->create();
        $recent = PageView::factory()->on(now()->subDays(5))->create();

        $this->artisan('cream:purge-old-records')->assertExitCode(0);

        $this->assertDatabaseMissing('page_views', ['id' => $old->id]);
        $this->assertDatabaseHas('page_views', ['id' => $recent->id]);
    }

    public function test_page_views_are_kept_when_retention_is_zero(): void
    {
        PageView::factory()->on(now()->subYear())->create();

        $this->artisan('cream:purge-old-records')->assertExitCode(0);

        $this->assertDatabaseCount('page_views', 1);
    }
}
