@extends('layouts.default-dashboard')

@section('title', 'Manajemen User')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen User</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola semua akun pengguna sistem</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('usermanagement.bulkcreate') }}" class="btn-secondary flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Import CSV
            </a>
            <a href="{{ route('usermanagement.create') }}" class="btn-primary flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah User
            </a>
        </div>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="table-base datatable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. Telp</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    @if($user->avatar)
                                        <img src="{{ Storage::url($user->avatar) }}"
                                             class="w-8 h-8 rounded-full object-cover flex-shrink-0" alt="Avatar">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                                            <span class="text-white text-xs font-semibold">
                                                {{ strtoupper(substr($user->username, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                    <span class="font-medium">{{ $user->username }}</span>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->no_telp }}</td>
                            <td>
                                @if($user->role === 'administrator')
                                    <span class="badge-blue capitalize">{{ $user->role }}</span>
                                @else
                                    <span class="badge-gray capitalize">{{ $user->role }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('usermanagement.edit', $user->id) }}"
                                       class="btn-secondary text-xs py-1 px-3">Edit</a>
                                    @if(Auth::user()->id !== $user->id)
                                        <form method="POST"
                                              action="{{ route('usermanagement.destroy', $user->id) }}"
                                              onsubmit="return confirm('Hapus user {{ $user->username }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-danger text-xs py-1 px-3">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-400">Belum ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
