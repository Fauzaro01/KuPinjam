@extends('layouts.default-dashboard')

@section('title', 'Pengumuman')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengumuman</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelola pengumuman yang tampil di dashboard seluruh pengguna.</p>
        </div>
        <a href="{{ route('announcement.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Pengumuman
        </a>
    </div>

    <div class="card">
        @if($announcements->isEmpty())
            <div class="py-12 text-center text-gray-400 dark:text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <p class="text-base">Belum ada pengumuman.</p>
            </div>
        @else
            <div class="divide-y divide-gray-100 dark:divide-slate-700">
                @foreach($announcements as $a)
                    <div class="flex items-start gap-4 py-4 px-2 hover:bg-gray-50 dark:hover:bg-slate-700/30 rounded-xl transition-colors">
                        {{-- Status indicator --}}
                        <div class="mt-1 flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $a->is_active ? 'bg-green-500' : 'bg-gray-300 dark:bg-slate-600' }}"></div>

                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $a->title }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">{{ $a->content }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $a->created_at->diffForHumans() }}</p>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            {{-- Toggle Active --}}
                            <form method="POST" action="{{ route('announcement.toggle', $a->id) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs px-2.5 py-1 rounded-lg font-medium transition-colors
                                    {{ $a->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400 hover:bg-green-200' : 'bg-gray-100 text-gray-500 dark:bg-slate-700 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-slate-600' }}">
                                    {{ $a->is_active ? '● Aktif' : '○ Nonaktif' }}
                                </button>
                            </form>
                            <a href="{{ route('announcement.edit', $a->id) }}" class="btn-secondary py-1 px-2.5 text-xs">Edit</a>
                            <form method="POST" action="{{ route('announcement.destroy', $a->id) }}" onsubmit="return confirm('Hapus pengumuman ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger py-1 px-2.5 text-xs">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $announcements->links() }}</div>
        @endif
    </div>
</div>
@endsection
