<?php

namespace Tests\Feature\Console;

use App\Mail\CommentDigestMail;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CommentDigestTest extends TestCase
{
    use RefreshDatabase;

    public function test_digest_is_queued_when_there_are_new_pending_comments(): void
    {
        Mail::fake();
        Comment::factory()->create(); // pending, created now

        $this->artisan('comments:digest')->assertExitCode(0);

        Mail::assertQueued(CommentDigestMail::class);
    }

    public function test_no_digest_when_nothing_new(): void
    {
        Mail::fake();

        $this->artisan('comments:digest')->assertExitCode(0);

        Mail::assertNothingQueued();
    }
}
