<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Apply the security headers carried over from the static site, plus a
     * Content-Security-Policy. HSTS is only emitted over HTTPS.
     *
     * NOTE(phase-3): when the public site is ported to Blade, the CSP will be
     * tightened with per-request nonces for the inline <style>/<script> the
     * current design uses. For now the only Laravel-served pages are the
     * /staff dashboard and auth screens, so 'unsafe-inline' is limited to
     * style-src and script-src stays strict ('self' + Turnstile only).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $headers = $response->headers;

        $headers->set('X-Content-Type-Options', 'nosniff');
        $headers->set('X-Frame-Options', 'SAMEORIGIN');
        $headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        if ($request->secure()) {
            $headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        $isStaff = $request->is('staff', 'staff/*');

        $headers->set('Content-Security-Policy', $isStaff ? $this->staffCsp() : $this->publicCsp());

        // Keep the private dashboard out of search indexes.
        if ($isStaff) {
            $headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }

    /**
     * Strict policy for the staff dashboard. script-src is 'self' + Turnstile
     * only (no inline scripts); style-src allows inline for the brand CSS.
     */
    protected function staffCsp(): string
    {
        return implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "frame-ancestors 'self'",
            "img-src 'self' data:",
            "font-src 'self' https://fonts.gstatic.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "script-src 'self' https://challenges.cloudflare.com",
            "frame-src https://challenges.cloudflare.com",
            "connect-src 'self'",
            "form-action 'self'",
            'upgrade-insecure-requests',
        ]);
    }

    /**
     * Public site. No inline scripts are used (hero/reels/popup run from
     * /assets/site.js), so script-src stays 'self'. Allowances: Google Fonts,
     * Google Maps iframes, self-hosted video, and — until Phase 6 — the
     * Web3Forms endpoint for form submission. The Turnstile host is pre-allowed
     * for the Phase 6 form rollout.
     */
    protected function publicCsp(): string
    {
        return implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "frame-ancestors 'self'",
            "img-src 'self' data:",
            "media-src 'self'",
            "font-src 'self' https://fonts.gstatic.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "script-src 'self' https://challenges.cloudflare.com",
            "frame-src https://www.google.com https://maps.google.com https://challenges.cloudflare.com",
            "connect-src 'self' https://api.web3forms.com",
            "form-action 'self' https://api.web3forms.com",
            'upgrade-insecure-requests',
        ]);
    }
}
