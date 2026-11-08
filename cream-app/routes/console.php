<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Hourly digest of new pending comments to the moderation inbox.
Schedule::command('comments:digest')->hourly()->withoutOverlapping();

// Drain the database queue (queued mail etc.) — the hPanel cron runs
// `php artisan schedule:run` every minute on the server.
Schedule::command('queue:work --stop-when-empty --max-time=55')
    ->everyMinute()->withoutOverlapping();
