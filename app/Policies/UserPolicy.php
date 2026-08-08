<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('administrator');
    }

    public function view(User $user, User $target): bool
    {
        return $user->hasRole('administrator');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('administrator');
    }

    public function update(User $user, User $target): bool
    {
        return $user->hasRole('administrator');
    }

    /**
     * Administrator dapat menghapus user lain, tapi tidak bisa hapus diri sendiri.
     */
    public function delete(User $user, User $target): bool
    {
        return $user->hasRole('administrator') && $user->id !== $target->id;
    }
}
