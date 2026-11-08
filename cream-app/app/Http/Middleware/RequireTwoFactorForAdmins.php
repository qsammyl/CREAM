<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Admins must have confirmed TOTP two-factor before using the dashboard.
 * Until they do, they are confined to the profile / 2FA-setup screens.
 */
class RequireTwoFactorForAdmins
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->isAdmin() && is_null($user->two_factor_confirmed_at)) {
            $allowed = $request->is('staff/profile')
                || $request->is('staff/profile/*')
                || $request->is('staff/user/*')      // Fortify 2FA + password-confirm endpoints
                || $request->is('staff/logout');

            if (! $allowed) {
                return redirect()->route('staff.profile')->with('status', 'two-factor-required');
            }
        }

        return $next($request);
    }
}
