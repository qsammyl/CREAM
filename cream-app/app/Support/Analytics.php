<?php

namespace App\Support;

use Illuminate\Support\Carbon;

/**
 * Privacy-preserving helpers for first-party, cookieless page-view analytics.
 *
 * Unique visitors are counted with a salted hash of IP+User-Agent whose salt
 * ROTATES DAILY, so the same person browsing on two different days produces two
 * unrelated hashes — the raw IP is never stored and visits cannot be linked
 * across days. (The audit log in App\Support\ActivityLogger deliberately uses
 * the stable app key instead, so admins CAN correlate a user's own actions.)
 */
class Analytics
{
    /**
     * A salt that is stable within a calendar day and unguessable without the
     * app key. Changing each day is what makes the visitor hash non-correlatable
     * across days.
     */
    public static function dailySalt(?Carbon $on = null): string
    {
        $day = ($on ?? now())->format('Y-m-d');

        return hash_hmac('sha256', 'cream-analytics|'.$day, (string) config('app.key'));
    }

    /**
     * The per-day visitor fingerprint. Null when there is nothing to fingerprint.
     */
    public static function visitorHash(?string $ip, ?string $userAgent, ?Carbon $on = null): ?string
    {
        if (empty($ip) && empty($userAgent)) {
            return null;
        }

        return hash_hmac('sha256', $ip.'|'.$userAgent, self::dailySalt($on));
    }

    /**
     * Crawlers, scrapers, link-preview fetchers, uptime monitors and headless
     * tooling — excluded from the figures so they reflect real people. An empty
     * User-Agent is treated as a bot (real browsers always send one).
     */
    public static function isBot(?string $userAgent): bool
    {
        if (empty($userAgent)) {
            return true;
        }

        $ua = strtolower($userAgent);

        foreach (self::BOT_SIGNATURES as $needle) {
            if (str_contains($ua, $needle)) {
                return true;
            }
        }

        return false;
    }

    /** @var list<string> */
    private const BOT_SIGNATURES = [
        'bot', 'crawl', 'spider', 'slurp', 'facebookexternalhit', 'embedly',
        'quora link preview', 'whatsapp', 'telegrambot', 'skypeuripreview',
        'headless', 'phantom', 'puppeteer', 'playwright', 'selenium',
        'curl', 'wget', 'python-', 'go-http-client', 'java/', 'okhttp',
        'scrapy', 'httpclient', 'axios', 'semrush', 'ahrefs', 'mj12',
        'dotbot', 'petalbot', 'bytespider', 'gptbot', 'ccbot', 'claudebot',
        'lighthouse', 'pingdom', 'uptimerobot', 'statuscake', 'monitoring',
    ];
}
