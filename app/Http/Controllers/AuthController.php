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
        // 1. Validasi
        $credentials = $request->validate([
            // HAPUS 'email' DARI ARRAY VALIDASI
            // Cukup 'required' saja agar menerima string apapun (NIP, Username, Email)
            'email' => ['required', 'string'], 
            'password' => ['required'],
        ]);

        // 2. Ingat Saya
        $remember = $request->boolean('remember');

        // 3. Attempt Login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            // Redirect sesuai role
            switch ($role) {
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));
                case 'subkon_pt':
                    return redirect()->intended(route('subkon-pt.dashboard'));
                case 'subkon_eks':
                    return redirect()->intended(route('subkon-eks.dashboard'));
                    case 'keuangan':
                    return redirect()->intended(route('keuangan.dashboard'));
                    case 'eco':
                    return redirect()->intended(route('eco.dashboard'));
                    case 'indie':
                    return redirect()->intended(route('indie.dashboard'));
                default:
                    Auth::logout();
                    return back()->withErrors(['email' => 'Role akun tidak dikenali.']);
            }
        }

        // 4. Jika Gagal Login
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