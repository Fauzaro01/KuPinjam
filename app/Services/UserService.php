<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService
{
    public function createUser(array $data): User
    {
        return User::create([
            'id'       => Str::random(13),
            'username' => $data['username'],
            'email'    => $data['email'],
            'no_telp'  => $data['no_telp'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);
    }

    public function updateUser(User $user, array $data): User
    {
        $user->username = $data['username'];
        $user->email    = $data['email'];
        $user->no_telp  = $data['no_telp'];
        $user->role     = $data['role'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return $user->fresh();
    }

    public function deleteUser(User $user): void
    {
        $user->delete();
    }

    /**
     * Import users dari file CSV.
     * Skip baris yang email atau no_telp-nya duplikat.
     *
     * @return array{ imported: int, skipped: int, skipped_rows: array }
     */
    public function bulkImportFromCsv(UploadedFile $file): array
    {
        $imported    = 0;
        $skipped     = 0;
        $skippedRows = [];

        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $header = array_map('strtolower', array_map('trim', $header));

        $rowNumber = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;
            $data = array_combine($header, $row);

            $email   = trim($data['email'] ?? '');
            $noTelp  = trim($data['no_telp'] ?? '');

            $duplicate = User::where('email', $email)
                             ->orWhere('no_telp', $noTelp)
                             ->exists();

            if ($duplicate) {
                $skipped++;
                $skippedRows[] = [
                    'row'    => $rowNumber,
                    'email'  => $email,
                    'no_telp'=> $noTelp,
                    'reason' => 'Email atau no_telp sudah terdaftar',
                ];
                continue;
            }

            User::create([
                'id'       => Str::random(13),
                'username' => trim($data['username']),
                'email'    => $email,
                'no_telp'  => $noTelp,
                'password' => Hash::make(trim($data['password'])),
                'role'     => 'karyawan',
            ]);

            $imported++;
        }

        fclose($handle);

        return [
            'imported'     => $imported,
            'skipped'      => $skipped,
            'skipped_rows' => $skippedRows,
        ];
    }

    public function updateProfile(User $user, array $data): User
    {
        $user->username = $data['username'];
        $user->no_telp  = $data['no_telp'];
        $user->save();

        return $user->fresh();
    }

    public function updateAvatar(User $user, UploadedFile $file): User
    {
        // Hapus avatar lama jika ada
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $file->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        return $user->fresh();
    }
}
