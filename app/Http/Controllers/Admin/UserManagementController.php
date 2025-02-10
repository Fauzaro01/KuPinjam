<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.usermanagement.index', compact('users'));
    }

    public function create()
    {
        return view('admin.usermanagement.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_telp' => 'required|string|unique:users,no_telp',
            'password' => 'required|string|min:8',
            'role' => 'required|in:karyawan,administrator',
        ]);

        User::create([
            'id' => Str::random(13),
            'username' => $validated['username'],
            'email' => $validated['email'],
            'no_telp' => $validated['no_telp'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('usermanagement.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.usermanagement.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'no_telp' => 'required|string|unique:users,no_telp,' . $id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:karyawan,administrator',
        ]);

        $user = User::findOrFail($id);
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->no_telp = $validated['no_telp'];

        if ($validated['password']) {
            $user->password = Hash::make($validated['password']);
        }

        $user->role = $validated['role'];
        $user->save();

        return redirect()->route('usermanagement.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('usermanagement.index')->with('success', 'User berhasil dihapus.');
    }

    public function downloadcsvuser() {
        $path_file = public_path('assets/static/files/userbulk.csv');
        if (File::exists($path_file)) {
            return response()->download($path_file);
        } else {
            return abort('404', 'Sorry file not found, please contact developer');
        }
    }

    public function showregisterbulk() {
        return view('admin.usermanagement.registeruserbulk');
    }

    public function bulkstoreuser(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv|max:10240'
        ]);

        try {
            $csvData = $this->csvUserToArray($request->file);
            if (!$this->validateHeaders(array_keys($csvData[0]), ['username', 'email', 'no_telp', 'password'])) {
                return redirect()->back()->with('error', 'Header CSV tidak lengkap. Pastikan ada kolom "username", "email", "no_telp", dan "password".');
            }

            foreach ($csvData as $user) {
                User::create([
                    "id" => Str::random(13),
                    "username" => $user['username'],
                    "email" => $user['email'],
                    "no_telp" => $user['no_telp'],
                    "password" => Hash::make($user['password']),
                    "role" => "karyawan"
                ]);
            }

            return response()->json(['success' => 'CSV file imported successfully.', "data" => $csvData]);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan dalam mengimpor file CSV. Coba lagi.');
        }
    }

    
    /* FUNCTION FOR TOOLS */
    
    private function csvUserToArray($file)
    {
        $array = [];
        $handle = fopen($file->getRealPath(), 'r');
        
        $header = fgetcsv($handle);
        
        $header = array_map('strtolower', $header);

        while (($row = fgetcsv($handle)) !== FALSE) {
            $array[] = array_combine($header, $row);
        }
        
        fclose($handle);
        
        return $array;
    }

    private function validateHeaders($header, $requiredHeaders)
    {
        return empty(array_diff($requiredHeaders, array_map('strtolower', $header)));
    }
}
