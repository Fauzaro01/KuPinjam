<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UserManagementController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $showTrashed = $request->boolean('trashed');

        if ($showTrashed) {
            $users = User::onlyTrashed()->latest()->paginate(15);
        } else {
            $users = User::latest()->paginate(15);
        }

        return view('admin.usermanagement.index', compact('users', 'showTrashed'));
    }

    public function create()
    {
        $this->authorize('create', User::class);

        return view('admin.usermanagement.create');
    }

    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $this->userService->createUser($request->validated());

        return redirect()->route('usermanagement.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        return view('admin.usermanagement.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $this->userService->updateUser($user, $request->validated());

        return redirect()->route('usermanagement.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        $this->userService->deleteUser($user);

        return redirect()->route('usermanagement.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Restore user yang di-soft-delete (admin only).
     */
    public function restore($id)
    {
        $this->authorize('create', User::class);

        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('usermanagement.index')
            ->with('success', "User '{$user->username}' berhasil dipulihkan.");
    }

    public function downloadcsvuser()
    {
        $path_file = public_path('userbulk.csv');
        if (File::exists($path_file)) {
            return response()->download($path_file);
        }

        return abort(404, 'File not found, please contact developer.');
    }

    public function showregisterbulk()
    {
        $this->authorize('create', User::class);

        return view('admin.usermanagement.registeruserbulk');
    }

    public function bulkstoreuser(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'file' => 'required|mimes:csv|max:10240',
        ]);

        try {
            $result = $this->userService->bulkImportFromCsv($request->file('file'));

            $message = "Import selesai: {$result['imported']} berhasil, {$result['skipped']} dilewati karena duplikat.";

            return redirect()->route('usermanagement.index')
                ->with('success', $message);
        } catch (\Throwable $th) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan dalam mengimpor file CSV. Coba lagi.');
        }
    }
}
