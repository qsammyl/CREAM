<?php

namespace App\Console\Commands;

use App\Models\Comment;
use App\Models\Enquiry;
use App\Models\Setting;
use Illuminate\Console\Command;

class PurgeOldRecords extends Command
{
    protected $signature = 'cream:purge-old-records';

    protected $description = 'Permanently delete old closed/spam enquiries and spam comments, per the retention periods in Settings (0 = keep forever).';

    public function handle(): int
    {
        $enquiryDays = (int) Setting::get('retention_enquiries_days', 0);
        $commentDays = (int) Setting::get('retention_comments_days', 0);

        $enquiries = 0;
        if ($enquiryDays > 0) {
            $enquiries = Enquiry::withTrashed()
                ->whereIn('status', ['closed', 'spam'])
                ->where('updated_at', '<', now()->subDays($enquiryDays))
                ->forceDelete();
        }

        $comments = 0;
        if ($commentDays > 0) {
            $comments = Comment::where('status', 'spam')
                ->where('updated_at', '<', now()->subDays($commentDays))
                ->delete();
        }

        $this->info("Purged {$enquiries} enquiry(ies) and {$comments} spam comment(s).");

        return self::SUCCESS;
    }
}
