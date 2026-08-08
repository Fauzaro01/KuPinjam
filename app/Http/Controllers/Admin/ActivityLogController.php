<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Tampilkan riwayat log aktivitas seluruh pengguna (Admin only).
     */
    public function index()
    {
        $this->authorize('viewAny', \App\Models\User::class);

        $logs = \App\Models\ActivityLog::with('user')
            ->latest('id')
            ->paginate(15);

        return view('admin.activity-log.index', compact('logs'));
    }
}
