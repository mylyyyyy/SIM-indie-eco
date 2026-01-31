<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginHistory;

class LoginHistoryController extends Controller
{
    public function index()
    {
        // AMBIL SEMUA DATA (Tanpa filter user_id)
        // 'with('user')' berguna untuk memanggil nama/role user dari tabel users agar tidak berat (N+1 problem)
        $histories = LoginHistory::with('user')
                        ->orderBy('login_at', 'desc')
                        ->paginate(20); // Menampilkan 20 data per halaman

        return view('login-history.index', compact('histories'));
    }
}