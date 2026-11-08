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

        $headers->set('Content-Security-Policy', $this->csp());

        // Keep the private dashboard out of search indexes.
        if ($request->is('staff', 'staff/*')) {
            $headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }

    protected function csp(): string
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
}
