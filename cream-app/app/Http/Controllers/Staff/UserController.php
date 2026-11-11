<?php

namespace App\Http\Controllers\Staff;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\StaffInvitation;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('staff.users.index', [
            'users' => User::orderByDesc('is_active')->orderBy('name')->get(),
            'invitations' => StaffInvitation::with('inviter')
                ->whereNull('accepted_at')->latest()->get(),
        ]);
    }

    public function edit(User $user): View
    {
        return view('staff.users.edit', [
            'user' => $user,
            'roles' => Role::options(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::enum(Role::class)],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $isSelf = $user->id === $request->user()->id;
        $willBeActive = (bool) ($data['is_active'] ?? false);
        $willBeAdmin = $data['role'] === Role::Admin->value;

        // Never let an admin lock themselves out of their own account.
        if ($isSelf && (! $willBeActive || ! $willBeAdmin)) {
            return back()->with('flash_error', 'You cannot change your own role or active status here.')->withInput();
        }

        // Never leave the system without an active admin.
        if (! $this->wouldKeepAnActiveAdmin($user, $willBeActive && $willBeAdmin)) {
            return back()->with('flash_error', 'There must be at least one active administrator.')->withInput();
        }

        $user->update([
            'name' => $data['name'],
            'email' => strtolower(trim($data['email'])),
            'role' => $data['role'],
            'is_active' => $willBeActive,
        ]);
        ActivityLogger::log('update', $user, properties: ['role' => $user->role->value, 'is_active' => $user->is_active]);

        return redirect()->route('staff.users.index')->with('flash', 'Account updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('flash_error', 'You cannot delete your own account.');
        }

        if (! $this->wouldKeepAnActiveAdmin($user, false)) {
            return back()->with('flash_error', 'There must be at least one active administrator.');
        }

        ActivityLogger::log('delete', $user, properties: ['email' => $user->email]);
        $user->delete();

        return redirect()->route('staff.users.index')->with('flash', 'Account deleted.');
    }

    /**
     * Would at least one active admin remain after this user becomes
     * (active-admin = $userStaysActiveAdmin)?
     */
    protected function wouldKeepAnActiveAdmin(User $user, bool $userStaysActiveAdmin): bool
    {
        $others = User::where('id', '!=', $user->id)
            ->where('role', Role::Admin->value)
            ->where('is_active', true)
            ->exists();

        return $others || $userStaysActiveAdmin;
    }
}
