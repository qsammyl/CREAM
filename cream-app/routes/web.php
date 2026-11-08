<?php

use App\Http\Controllers\Staff\ActivityController;
use App\Http\Controllers\Staff\DashboardController;
use App\Http\Controllers\Staff\ProfileController;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site
|--------------------------------------------------------------------------
| The public, database-driven pages are ported from the static site in
| Phase 3. For now, the root redirects into the staff area.
*/

Route::get('/', fn () => redirect()->route('staff.dashboard'));

/*
|--------------------------------------------------------------------------
| Staff dashboard (/staff)
|--------------------------------------------------------------------------
| Fortify registers the auth routes (login, logout, password reset, 2FA,
| profile/password updates) under the same "staff" prefix via config.
*/

Route::middleware(['auth', 'active', AuthenticateSession::class, '2fa.admin'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        Route::post('/profile/logout-other-sessions', [ProfileController::class, 'logoutOtherSessions'])
            ->name('profile.logout-others');

        // Admin-only
        Route::middleware('admin')->group(function () {
            Route::get('/activity', [ActivityController::class, 'index'])->name('activity');
        });
    });
