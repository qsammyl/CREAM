<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('staff.profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Log the current user out of all their other sessions / devices.
     * Requires the AuthenticateSession middleware (active on the staff group).
     */
    public function logoutOtherSessions(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ], [], ['password' => 'password']);

        Auth::logoutOtherDevices($request->input('password'));

        ActivityLogger::log('logout_other_sessions', $request->user(), actor: $request->user());

        return back()->with('status', 'other-sessions-logged-out');
    }
}
