<?php

namespace App\Console\Commands;

use App\Mail\CommentDigestMail;
use App\Models\Comment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SendCommentDigest extends Command
{
    protected $signature = 'comments:digest';

    protected $description = 'Email the moderation inbox a digest of new pending comments (at most once per hour).';

    public function handle(): int
    {
        $since = Cache::get('comments.digest.last', now()->subHour());

        $new = Comment::query()->with('article')
            ->where('status', 'pending')
            ->where('created_at', '>', $since)
            ->get();

        if ($new->isNotEmpty()) {
            Mail::to(config('cream.moderation_email'))->queue(new CommentDigestMail($new));
            $this->info("Queued a digest for {$new->count()} new comment(s).");
        } else {
            $this->info('No new comments since the last digest.');
        }

        Cache::put('comments.digest.last', now());

        return self::SUCCESS;
    }
}
