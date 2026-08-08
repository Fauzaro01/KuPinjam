@extends('layouts.default-dashboard')

@section('title', 'Manajemen User')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen User</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola semua akun pengguna sistem</p>
        </div>
        @if(!$showTrashed)
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
        @endif
    </div>

    {{-- Tab aktif / dihapus --}}
    <div class="flex items-center gap-2 border-b border-gray-200 dark:border-slate-700">
        <a href="{{ route('usermanagement.index') }}"
           class="pb-2 px-1 text-sm font-medium border-b-2 transition-colors
                  {{ !$showTrashed ? 'border-primary text-primary' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
            Pengguna Aktif
        </a>
        <a href="{{ route('usermanagement.index', ['trashed' => 1]) }}"
           class="pb-2 px-1 text-sm font-medium border-b-2 transition-colors
                  {{ $showTrashed ? 'border-red-500 text-red-500' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
            Dihapus
        </a>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="table-base datatable w-full" data-server-paginated="true">
                <thead>
                    <tr>
                        <th style="width:25%">Nama</th>
                        <th>Email</th>
                        <th style="width:14%">No. Telp</th>
                        <th style="width:10%">Role</th>
                        <th style="width:14%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="{{ $showTrashed ? 'opacity-70' : '' }}">
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
                                    <div>
                                        <span class="font-medium">{{ $user->username }}</span>
                                        @if($showTrashed)
                                            <span class="badge-red text-xs ml-1">Dihapus</span>
                                        @endif
                                    </div>
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
                            <td class="whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if($showTrashed)
                                        {{-- Restore user --}}
                                        <form method="POST"
                                              action="{{ route('usermanagement.restore', $user->id) }}"
                                              onsubmit="return confirm('Pulihkan user {{ $user->username }}?')">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-secondary text-xs py-1 px-3 text-green-700 border-green-400 hover:bg-green-50 dark:text-green-400 dark:border-green-600 dark:hover:bg-green-900/30">
                                                Pulihkan
                                            </button>
                                        </form>
                                    @else
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
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-400">
                                {{ $showTrashed ? 'Tidak ada user yang dihapus.' : 'Belum ada data user.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="mt-4">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
