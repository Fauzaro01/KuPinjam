<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Catat aktivitas baru ke log database.
     */
    public function log(string $action, string $description, ?string $userId = null): ActivityLog
    {
        $userId = $userId ?? Auth::id();

        return ActivityLog::create([
            'user_id'     => $userId,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}
