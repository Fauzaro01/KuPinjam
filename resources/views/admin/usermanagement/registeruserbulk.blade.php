@extends('layouts.default-dashboard')

@section('title', 'Import User CSV')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('usermanagement.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Import User CSV</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-0.5">Upload file CSV untuk mendaftarkan banyak user sekaligus</p>
        </div>
    </div>

    {{-- Info format CSV --}}
    <div class="card mb-5 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm text-blue-800 dark:text-blue-200">
                <p class="font-semibold mb-1">Format kolom CSV yang diperlukan:</p>
                <code class="bg-blue-100 dark:bg-blue-900/50 px-2 py-0.5 rounded text-xs font-mono">
                    username, email, no_telp, password
                </code>
                <p class="mt-2">
                    Data yang sudah ada (email atau no_telp duplikat) akan dilewati secara otomatis.
                    Semua user yang berhasil diimpor akan mendapat role <strong>karyawan</strong>.
                </p>
                <a href="{{ route('usermanagement.downloadcsvuser') }}"
                   class="inline-flex items-center gap-1.5 mt-2 text-blue-600 dark:text-blue-400 hover:underline font-medium text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download template CSV
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        @if($errors->any())
            <div class="mb-5 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg">
                @foreach($errors->all() as $error)
                    <p class="text-sm text-red-700 dark:text-red-300">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('usermanagement.bulkstoreuser') }}"
              enctype="multipart/form-data" class="space-y-5"
              x-data="{ loading: false }" @submit="loading = true">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    File CSV
                </label>
                <input type="file" name="file" accept=".csv"
                       class="block w-full text-sm text-gray-700 dark:text-gray-300
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-lg file:border-0
                              file:text-sm file:font-medium
                              file:bg-primary file:text-white
                              hover:file:bg-primary-dark
                              cursor-pointer"
                       required>
                <p class="mt-1.5 text-xs text-gray-400">Format: .csv, Maks. 10MB</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex items-center gap-2" :disabled="loading">
                    <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span x-text="loading ? 'Mengimpor...' : 'Import Sekarang'">Import Sekarang</span>
                </button>
                <a href="{{ route('usermanagement.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
