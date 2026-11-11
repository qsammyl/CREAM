<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\StaffInvitation;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AcceptInvitationController extends Controller
{
    public function show(string $token): View
    {
        $invitation = $this->validInvitation($token);

        return view('auth.accept-invitation', [
            'invitation' => $invitation,
            'token' => $token,
        ]);
    }

    public function accept(Request $request, string $token): RedirectResponse
    {
        $invitation = $this->validInvitation($token);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(12)->uncompromised()],
        ]);

        // Guard against the email having been claimed since the invite was sent.
        if (User::where('email', $invitation->email)->exists()) {
            abort(410);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $invitation->email,
            'password' => $data['password'], // 'hashed' cast hashes on save
            'role' => $invitation->role,
            'is_active' => true,
        ]);

        $invitation->update(['accepted_at' => now()]);

        ActivityLogger::log('create', $user, $request, properties: ['via' => 'invitation'], actor: $user);

        return redirect()->route('login')->with('status',
            'Your account is ready — sign in below.'.($user->isAdmin() ? ' You will be asked to set up two-factor authentication.' : ''));
    }

    /** Resolve a usable invitation from the plaintext token, or 404/410. */
    protected function validInvitation(string $token): StaffInvitation
    {
        $invitation = StaffInvitation::where('token', hash('sha256', $token))->first();

        abort_if($invitation === null, 404);
        abort_if($invitation->isAccepted(), 410);
        abort_if($invitation->isExpired(), 410);

        return $invitation;
    }
}
