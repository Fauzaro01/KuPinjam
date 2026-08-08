<?php

namespace App\Policies;

use App\Models\Peminjaman;
use App\Models\RiwayatPengembalian;
use App\Models\User;

class PeminjamanPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Peminjaman $peminjaman): bool
    {
        return $user->hasRole('administrator') || $peminjaman->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Hanya administrator yang bisa membuat peminjaman melalui form admin.
     */
    public function adminCreate(User $user): bool
    {
        return $user->hasRole('administrator');
    }

    public function update(User $user, Peminjaman $peminjaman): bool
    {
        return $user->hasRole('administrator');
    }

    public function delete(User $user, Peminjaman $peminjaman): bool
    {
        return $user->hasRole('administrator');
    }

    /**
     * Karyawan pemilik peminjaman dan belum ada pengajuan pending.
     */
    public function ajukanPengembalian(User $user, Peminjaman $peminjaman): bool
    {
        if ($peminjaman->user_id !== $user->id) {
            return false;
        }

        if ($peminjaman->status_peminjaman !== 'dipinjam') {
            return false;
        }

        $hasPending = RiwayatPengembalian::where('peminjaman_id', $peminjaman->id)
            ->where('status', 'pending')
            ->exists();

        return !$hasPending;
    }
}
