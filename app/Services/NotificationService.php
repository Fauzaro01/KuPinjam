<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    /**
     * Kirim notifikasi baru ke pengguna.
     */
    public function sendNotification(?string $userId, string $title, string $message): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'is_read' => false,
        ]);
    }

    /**
     * Dapatkan notifikasi belum dibaca milik pengguna.
     */
    public function getUnreadNotifications(string $userId)
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->latest()
            ->get();
    }

    /**
     * Tandai notifikasi tertentu sebagai dibaca.
     */
    public function markAsRead(int $id): bool
    {
        $notification = Notification::find($id);
        if ($notification) {
            return $notification->update(['is_read' => true]);
        }
        return false;
    }

    /**
     * Tandai semua notifikasi pengguna sebagai dibaca.
     */
    public function markAllAsRead(string $userId): int
    {
        return Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
