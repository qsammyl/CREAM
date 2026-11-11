<?php

namespace App\Http\Middleware;

use App\Models\Asset;
use App\Models\PageView;
use App\Support\Analytics;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * First-party, cookieless page-view analytics. Runs on terminate() so recording
 * never adds latency to the response, and is wrapped so a logging failure can
 * never break a page. Only successful public HTML page loads by real people are
 * counted — the /staff dashboard, bots, non-GET requests and Do-Not-Track
 * browsers are all skipped.
 */
class TrackPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        try {
            if (! $this->shouldRecord($request, $response)) {
                return;
            }

            PageView::create([
                'path' => mb_substr($request->getPathInfo(), 0, 255),
                'visitor_hash' => Analytics::visitorHash($request->ip(), $request->userAgent()),
                'referer_host' => $this->externalRefererHost($request),
                'utm_source' => $this->utmSource($request),
                'country' => $this->country($request),
                'asset_id' => $this->assetId($request),
            ]);
        } catch (\Throwable $e) {
            Log::debug('page-view tracking skipped: '.$e->getMessage());
        }
    }

    protected function shouldRecord(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET')) {
            return false;
        }

        // Respect Do-Not-Track — we keep no cookies, but honour the signal too.
        if ($request->header('DNT') === '1' || $request->header('Sec-GPC') === '1') {
            return false;
        }

        // Private dashboard + framework/utility paths are never visitor pages.
        if ($request->is('staff', 'staff/*', 'sitemap.xml', 'robots.txt', 'up')) {
            return false;
        }

        // Must be a real, matched page returning rendered HTML (skips redirects,
        // 404s, JSON, XML sitemaps and binary/file responses).
        if ($request->route() === null || $response->getStatusCode() !== 200) {
            return false;
        }

        if (! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return false;
        }

        return ! Analytics::isBot($request->userAgent());
    }

    /** The referrer host, but only when it is a different site (external). */
    protected function externalRefererHost(Request $request): ?string
    {
        $referer = $request->headers->get('referer');
        if (empty($referer)) {
            return null;
        }

        $host = parse_url($referer, PHP_URL_HOST);
        if (empty($host) || strcasecmp($host, (string) $request->getHost()) === 0) {
            return null;
        }

        $host = preg_replace('/^www\./i', '', strtolower($host));

        return mb_substr($host, 0, 128);
    }

    protected function utmSource(Request $request): ?string
    {
        $source = $request->query('utm_source');
        if (! is_string($source) || $source === '') {
            return null;
        }

        return mb_substr(trim(strip_tags($source)), 0, 64) ?: null;
    }

    /** Two-letter country from Cloudflare's CF-IPCountry header, when present. */
    protected function country(Request $request): ?string
    {
        $cc = strtoupper((string) $request->headers->get('CF-IPCountry'));

        return preg_match('/^[A-Z]{2}$/', $cc) && $cc !== 'XX' ? $cc : null;
    }

    /** Link listing views to their asset so "most-viewed listings" works. */
    protected function assetId(Request $request): ?int
    {
        if ($request->route()?->getName() !== 'listings.show') {
            return null;
        }

        $slug = $request->route('slug');

        return $slug ? Asset::where('slug', $slug)->value('id') : null;
    }
}
