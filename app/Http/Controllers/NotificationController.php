<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        protected \App\Services\NotificationService $notificationService
    ) {}

    /**
     * Tandai satu notifikasi sebagai dibaca.
     */
    public function markAsRead($id)
    {
        $this->notificationService->markAsRead($id);
        return redirect()->back()->with('success', 'Notifikasi ditandai dibaca.');
    }

    /**
     * Tandai semua notifikasi milik user yang aktif sebagai dibaca.
     */
    public function markAllAsRead()
    {
        $this->notificationService->markAllAsRead(auth()->id());
        return redirect()->back()->with('success', 'Semua notifikasi ditandai dibaca.');
    }
}
