<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 301-redirect the static site's .html URLs to their clean equivalents,
 * e.g. /about.html → /about, /listings/x.html → /listings/x,
 * /index.html and /blog/index.html → their directory root. This preserves
 * every URL the static site exposed (it served clean URLs and 301'd .html).
 */
class RedirectLegacyHtml
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path(); // e.g. "about.html", no leading slash, no query

        if (str_ends_with($path, '.html')) {
            $clean = substr($path, 0, -5);                 // strip ".html"
            $clean = preg_replace('#(^|/)index$#', '', $clean); // index → directory root
            $target = '/'.ltrim((string) $clean, '/');

            if ($qs = $request->getQueryString()) {
                $target .= '?'.$qs;
            }

            return redirect($target, 301);
        }

        return $next($request);
    }
}
