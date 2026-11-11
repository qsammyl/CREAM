<?php

namespace App\Http\Controllers\Staff;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Mail\StaffInvitationMail;
use App\Models\StaffInvitation;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InvitationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email'),
            ],
            'role' => ['required', Rule::enum(Role::class)],
        ]);

        $email = strtolower(trim($data['email']));

        // One live invitation per email — replace any previous pending one.
        StaffInvitation::where('email', $email)->whereNull('accepted_at')->delete();

        $plain = Str::random(48);

        $invitation = StaffInvitation::create([
            'email' => $email,
            'role' => $data['role'],
            'token' => hash('sha256', $plain),
            'invited_by' => $request->user()->id,
            'expires_at' => now()->addDays(7),
        ]);

        Mail::to($email)->queue(new StaffInvitationMail($invitation, $plain));
        ActivityLogger::log('invite', $invitation, properties: ['email' => $email, 'role' => $data['role']]);

        return back()->with('flash', "Invitation sent to {$email}.");
    }

    public function resend(StaffInvitation $invitation): RedirectResponse
    {
        if ($invitation->isAccepted()) {
            return back()->with('flash_error', 'That invitation has already been accepted.');
        }

        // Rotate the token and extend the window.
        $plain = Str::random(48);
        $invitation->update([
            'token' => hash('sha256', $plain),
            'expires_at' => now()->addDays(7),
        ]);

        Mail::to($invitation->email)->queue(new StaffInvitationMail($invitation, $plain));
        ActivityLogger::log('invite:resend', $invitation);

        return back()->with('flash', "Invitation re-sent to {$invitation->email}.");
    }

    public function destroy(StaffInvitation $invitation): RedirectResponse
    {
        ActivityLogger::log('invite:revoke', $invitation, properties: ['email' => $invitation->email]);
        $invitation->delete();

        return back()->with('flash', 'Invitation revoked.');
    }
}
