<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;

class AccountPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function show(User $user, Account $account): bool
    {
        return $account->members->contains($user->id);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Account $account): bool
    {
        return $user->id === $account->creator_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, Account $account): bool
    {
        return $user->id === $account->creator_id;
    }

    /**
     * Determine whether the user can view, invite and remove members.
     */
    public function accessMembers(User $user, Account $account)
    {
        return $user->id === $account->creator_id;
    }

    public function viewInvitations(User $user, Account $account) {
        return $account->members->contains($user->id);
    }

    public function sendInvitations(User $user, Account $account) {
        return $user->id === $account->creator_id;
    }
}
