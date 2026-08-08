@extends('layouts.default-dashboard')

@section('title', 'Log Aktivitas')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Log Aktivitas</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Audit trail semua aktivitas tindakan pengguna sistem</p>
    </div>

    <div class="card">
        <div class="table-container">
            <table class="table-base datatable w-full" data-server-paginated="true">
                <thead>
                    <tr>
                        <th style="width:20%">Waktu</th>
                        <th style="width:20%">Pengguna</th>
                        <th style="width:15%">Tindakan</th>
                        <th>Deskripsi</th>
                        <th style="width:15%">IP Address / Device</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="text-sm font-medium">
                                {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}
                            </td>
                            <td>
                                @if($log->user)
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $log->user->username }}</div>
                                    <div class="text-xs text-gray-400 capitalize">{{ $log->user->role }}</div>
                                @else
                                    <span class="text-gray-400">Sistem / Pengunjung</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badge = 'badge-gray';
                                    if (str_contains($log->action, 'buat')) $badge = 'badge-green';
                                    elseif (str_contains($log->action, 'update') || str_contains($log->action, 'konfirmasi')) $badge = 'badge-blue';
                                    elseif (str_contains($log->action, 'hapus') || str_contains($log->action, 'tolak')) $badge = 'badge-red';
                                @endphp
                                <span class="{{ $badge }} text-xs font-semibold capitalize">
                                    {{ str_replace('_', ' ', $log->action) }}
                                </span>
                            </td>
                            <td class="text-sm">{{ $log->description }}</td>
                            <td>
                                <div class="text-xs font-mono">{{ $log->ip_address ?? '-' }}</div>
                                <div class="text-[10px] text-gray-400 truncate max-w-[150px]" title="{{ $log->user_agent }}">
                                    {{ $log->user_agent }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-400">Belum ada riwayat aktivitas sistem.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
