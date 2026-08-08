@extends('layouts.default-dashboard')

@section('title', 'Edit Pengumuman')

@section('content')
<div class="space-y-6 max-w-2xl">
    <div>
        <a href="{{ route('announcement.index') }}" class="text-sm text-primary hover:underline">&larr; Kembali ke daftar</a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Edit Pengumuman</h1>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('announcement.update', $announcement->id) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="title" class="label-base">Judul Pengumuman</label>
                <input type="text" id="title" name="title"
                       value="{{ old('title', $announcement->title) }}"
                       class="input-base mt-1" required>
            </div>
            <div>
                <label for="content" class="label-base">Isi Pengumuman</label>
                <textarea id="content" name="content" rows="5"
                          class="input-base mt-1" required>{{ old('content', $announcement->content) }}</textarea>
            </div>
            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-700/30 rounded-xl">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                       {{ $announcement->is_active ? 'checked' : '' }}
                       class="w-4 h-4 rounded text-primary">
                <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                    Aktifkan pengumuman ini (tampil di dashboard pengguna)
                </label>
            </div>
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('announcement.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
