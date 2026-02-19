<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     * Menggunakan ...$roles agar bisa mengecek banyak role sekaligus (contoh: role:keuangan,keuangan_indie)
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Ambil role user saat ini
        $userRole = Auth::user()->role;

        // 3. Cek apakah role user ada di dalam daftar yang diizinkan route
        // (Fungsi in_array mengecek: apakah role user ada di dalam daftar $roles?)
        if (! in_array($userRole, $roles)) {
            // Jika tidak ada, tolak akses
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }

        return $next($request);
    }
}