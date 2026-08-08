<?php

namespace App\Providers;

use App\Models\RiwayatPengembalian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Suplai jumlah pengembalian pending ke sidebar hanya sekali per request,
        // menggantikan query langsung di Blade view.
        View::composer('layouts.sidebar-dashboard', function ($view) {
            $pendingSidebar = Auth::check()
                ? RiwayatPengembalian::where('status', 'pending')->count()
                : 0;
            $view->with('pendingSidebar', $pendingSidebar);
        });

        View::composer('layouts.default-dashboard', function ($view) {
            $unreadNotifications = Auth::check()
                ? \App\Models\Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->latest()
                    ->get()
                : collect();
            $activeAnnouncements = \App\Models\Announcement::where('is_active', true)
                ->latest()
                ->take(3)
                ->get();
            $view->with('unreadNotifications', $unreadNotifications)
                 ->with('activeAnnouncements', $activeAnnouncements);
        });
    }
}
