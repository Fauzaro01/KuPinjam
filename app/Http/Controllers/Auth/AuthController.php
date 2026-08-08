<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kendaraan;
use App\Models\Peminjaman;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuthController extends Controller implements HasMiddleware
{
    public static function middleware() {
        return [
            new Middleware('guest', except: ['logout', 'dashboard', 'changePassword', 'accountSecurity']),
        ];
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function showLogin() {
        return view('auth.login');
    }

    public function store(Request $req) {
        $req->validate([
            "username" => "required|max:250",
            "email" => "required|max:250|unique:users",
            "no_telp" => "required|numeric|unique:users",
            "password" => "required|min:8|confirmed",
        ]);

        User::create([
            "id" => Str::random(13),
            "username" => $req->username,
            "email" => $req->email,
            "no_telp" => $req->no_telp,
            "password" => Hash::make($req->password),
            "role" => "karyawan" 
        ]);

        $credentials = $req->only('email', 'password');
        Auth::attempt($credentials);
        $req->session()->regenerate();
        return redirect()->route('dashboard')->withSuccess('Kamu berhasil mendaftar dan juga masuk ke dashboard!');

    }


    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'email' => "required|email",
            'password' => "required"
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->hasRole('administrator')) {
                return redirect()->route('dashboard')->withSuccess('Kamu berhasil masuk sebagai Administrator!');
            } else {
                return redirect()->route('dashboard')->withSuccess('Kamu berhasil masuk sebagai Karyawan!');
            }
        }

        return back()->withErrors([
            "email" => "Kredensial yang anda berikan tidak cocok dengan data kami"
        ])->onlyInput('email');
    }


    public function dashboard() {
        if (Auth::check()) {
            if (Auth::user()->hasRole('administrator')) {
                $pendingPengembalian = \App\Models\RiwayatPengembalian::where('status', 'pending')->count();

                return view('dashboard.main', [
                    'users'               => \App\Models\User::all(),
                    'kendaraan'           => \App\Models\Kendaraan::all(),
                    'peminjamans'         => \App\Models\Peminjaman::with(['user', 'kendaraan'])->get(),
                    'pendingPengembalian' => $pendingPengembalian,
                ]);
            }

            // Karyawan: hanya data milik sendiri
            $myPeminjamans = \App\Models\Peminjaman::with(['kendaraan', 'riwayatPengembalian'])
                ->where('user_id', Auth::id())
                ->latest()
                ->get();

            return view('dashboard.main', [
                'myPeminjamans' => $myPeminjamans,
            ]);
        }

        return redirect()->route('login')
            ->withErrors([
                'email' => 'Please login to access the dashboard.',
            ])->onlyInput('email');
    }

     public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');;
    }

    public function accountSecurity() {
        return view('auth.security');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru harus memiliki minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return redirect()->back()->with([
                'error' => 'Password saat ini tidak valid.',
            ]);
        }

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with([
            'success' => 'Password berhasil diubah.',
        ]);
    }

}
