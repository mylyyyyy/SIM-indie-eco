<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah user sudah login
        if (!$request->user()) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
        }
        
        // Debugging: Log the user's role and the required role
        \Log::info('EnsureUserHasRole Middleware:', [
            'user_role' => $request->user() ? $request->user()->role : null,
            'required_role' => $role,
        ]);

        // 2. Cek role - jika middleware $role adalah 'admin', terima semua role admin
        // Jika role spesifik diminta, cek role exact match
        if ($role === 'admin') {
            // Izinkan semua role yang memiliki 'admin' di dalamnya, termasuk 'super_admin'
            $allowedRoles = ['super_admin', 'admin_humas', 'admin_registrasi', 'admin_umum', 'admin'];
            if (!in_array($request->user()->role, $allowedRoles)) {
                abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
            }
        } else {
            // Untuk role spesifik lainnya, lakukan exact match
            if ($request->user()->role !== $role) {
                abort(403, 'ANDA TIDAK MEMILIKI AKSES.');
            }
        }

        return $next($request);
    }
}
