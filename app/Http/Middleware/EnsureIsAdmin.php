<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    /**
     * Pastikan user yang mengakses adalah administrator.
     * Mengembalikan 403 jika bukan administrator.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->hasRole('administrator')) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk administrator.');
        }

        return $next($request);
    }
}
