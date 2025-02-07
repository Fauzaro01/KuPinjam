<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kendaraan;
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
            new Middleware('guest', except: ['logout', 'dashboard']),
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
            "no_telp" => "required|integer|unique:users",
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

    // public function bulkstore(Request $request) {
    //     $request->validate([
    //         'file' => 'required|mimes:csv|max:10240'
    //     ]);

    //     try {
    //         foreach ($this->csvToArray($request->file) as $user) {
    //             User::create([
    //                 "id" => Str::random(13),
    //                 "username" => $req->username,
    //                 "email" => $req->email,
    //                 "no_telp" => $req->no_telp,
    //                 "password" => Hash::make($req->password),
    //                 "role" => "karyawan" 
    //             ]);
    //         }
    //     } catch (\Throwable $th) {
            
    //     }

    // return response()->json(['success' => 'CSV file imported successfully.', "data" => $this->csvToArray($request->file)]);
    // }

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
                return view('dashboard.main', [
                    'users' => User::all(),
                    'kendaraan' => Kendaraan::all()
                ]);
            }

            return view('dashboard.main');
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

    /* Function Mengubah File CSV menjadi ARRAY */

    // private function csvToArray($file)
    // {
    //     $array = [];
    //     $handle = fopen($file->getRealPath(), 'r');
        
    //     $header = fgetcsv($handle);
        
    //     while (($row = fgetcsv($handle)) !== FALSE) {
    //         $array[] = array_combine($header, $row);
    //     }

    //     fclose($handle);

    //     return $array;
    // }

}
