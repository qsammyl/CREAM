<?php

use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\RedirectLegacyHtml;
use App\Http\Middleware\RequireTwoFactorForAdmins;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\TrackPageView;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 301 legacy .html URLs to clean URLs (global — runs before routing,
        // so it also catches .html paths that match no route).
        $middleware->prepend(RedirectLegacyHtml::class);

        // Security headers on every response.
        $middleware->append(SecurityHeaders::class);

        // First-party cookieless page-view analytics (records on terminate; it
        // self-filters to public HTML page loads, so it is safe to run globally).
        $middleware->append(TrackPageView::class);

        // Route middleware aliases.
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'active' => EnsureUserIsActive::class,
            '2fa.admin' => RequireTwoFactorForAdmins::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
