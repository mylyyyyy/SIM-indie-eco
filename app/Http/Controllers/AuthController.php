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
        // 1. Validasi Input
        $credentials = $request->validate([
            // 'email' di sini digunakan sebagai identifier (bisa NIP, username, atau email)
            'email' => ['required', 'string'], 
            'password' => ['required'],
        ]);

        // 2. Fitur "Ingat Saya"
        $remember = $request->boolean('remember');

        // 3. Attempt Login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Ambil role user yang sedang login
            $role = Auth::user()->role;

            // 4. Redirect berdasarkan Role
            switch ($role) {
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));
                
                case 'eco':
                    return redirect()->intended(route('eco.dashboard'));
                
                case 'indie':
                    return redirect()->intended(route('indie.dashboard'));
                
                case 'kepala_kantor':
                    // Ubah route ini jika dashboard kepala kantor punya nama berbeda
                    return redirect()->intended('/kepala-kantor/dashboard');
                
                case 'manager_unit':
                    // Ubah route ini jika dashboard manager unit punya nama berbeda
                    return redirect()->intended('/manager-unit/dashboard');
                
                case 'keuangan_eco':
                case 'keuangan_indie':
                case 'keuangan': // Menjaga kompabilitas dengan role lama (FNC-123)
                    return redirect()->intended(route('keuangan.dashboard'));
                
                case 'subkon_pt':
                    return redirect()->intended(route('subkon-pt.dashboard'));
                
                case 'subkon_eks':
                    return redirect()->intended(route('subkon-eks.dashboard'));
                
                default:
                    // Jika role tidak ada di daftar atas, paksa logout demi keamanan
                    Auth::logout();
                    return back()->withErrors(['email' => 'Hak akses (Role) Anda tidak dikenali sistem.']);
            }
        }

        // 5. Jika Gagal Login (Email/Password salah)
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