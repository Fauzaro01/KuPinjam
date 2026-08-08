<?php

namespace App\Policies;

use App\Models\Kendaraan;
use App\Models\User;

class KendaraanPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Kendaraan $kendaraan): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('administrator');
    }

    public function update(User $user, Kendaraan $kendaraan): bool
    {
        return $user->hasRole('administrator');
    }

    public function delete(User $user, Kendaraan $kendaraan): bool
    {
        return $user->hasRole('administrator');
    }
}
