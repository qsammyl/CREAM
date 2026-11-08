<?php

namespace App\Policies;

use App\Models\Asset;
use App\Models\User;

class AssetPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // admin + staff
    }

    public function view(User $user, Asset $asset): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Asset $asset): bool
    {
        return true;
    }

    /** Soft delete (move to bin) — staff allowed. */
    public function delete(User $user, Asset $asset): bool
    {
        return true;
    }

    /** Restore from bin — admin only. */
    public function restore(User $user, Asset $asset): bool
    {
        return $user->isAdmin();
    }

    /** Permanent delete — admin only. */
    public function forceDelete(User $user, Asset $asset): bool
    {
        return $user->isAdmin();
    }
}
