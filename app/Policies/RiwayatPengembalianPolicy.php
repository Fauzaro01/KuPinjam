<?php

namespace App\Policies;

use App\Models\RiwayatPengembalian;
use App\Models\User;

class RiwayatPengembalianPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('administrator');
    }

    public function view(User $user, RiwayatPengembalian $riwayat): bool
    {
        return $user->hasRole('administrator');
    }

    public function konfirmasi(User $user, RiwayatPengembalian $riwayat): bool
    {
        return $user->hasRole('administrator');
    }

    public function tolak(User $user, RiwayatPengembalian $riwayat): bool
    {
        return $user->hasRole('administrator');
    }
}
