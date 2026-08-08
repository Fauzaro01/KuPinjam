<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function show()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $this->userService->updateProfile($user, $request->validated());

        return redirect()->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = Auth::user();

        $this->userService->updateAvatar($user, $request->file('avatar'));

        return redirect()->route('profile.show')
            ->with('success', 'Foto profil berhasil diperbarui.');
    }
}
