<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'], 
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $role = Auth::user()->role;

            switch ($role) {
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));
                
                // ===== KELOMPOK ECO =====
                case 'eco':
                    return redirect()->intended(route('eco.dashboard'));
                case 'kepala_kantor':
                    return redirect()->intended('/kepala-kantor/dashboard');
                case 'manager_unit':
                case 'manager_wilayah': 
                    return redirect()->intended('/manager-unit/dashboard');
                case 'keuangan_eco':
                    return redirect()->intended(route('keuangan_eco.visit-results.index'));
                case 'monitoring_eco':
                    return redirect()->intended('monitoring-eco/dashboard');

                // ===== KELOMPOK INDIE =====
                case 'indie': // Admin Kantor Indie
                    return redirect()->intended(route('indie.dashboard'));
                case 'manager_unit_indie':
                    return redirect()->intended('/indie/manager-unit/dashboard');
                case 'kepala_kantor_indie':
                    return redirect()->intended('/indie/kepala-kantor/dashboard');
                case 'admin_lapangan_indie':
                    return redirect()->intended('/indie/admin-lapangan/dashboard');
                case 'monitoring_indie':
                    return redirect()->intended('/indie/monitoring/dashboard');
                case 'keuangan_indie':
                case 'keuangan': 
                    return redirect()->intended(route('keuangan.dashboard'));

                // ===== KELOMPOK SUBKON =====
                case 'subkon_pt':
                    return redirect()->intended(route('subkon-pt.dashboard'));
                case 'subkon_eks':
                    return redirect()->intended(route('subkon-eks.dashboard'));
                
                default:
                    Auth::logout();
                    return back()->withErrors(['email' => 'Hak akses (Role) Anda tidak dikenali sistem.']);
            }
        }

        return back()->withErrors([
            'email' => 'NIB/NIP atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}