<?php

namespace App\Http\Controllers\Indie\ManagerUnit;

use App\Http\Controllers\Controller;
use App\Models\LhkpReport;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Hitung statistik LHKP di cabang manager tersebut
        $lhkp_count = LhkpReport::whereHas('user', function($query) use ($user) {
            $query->where('company_name', $user->company_name);
        })->count();

        // Ambil 5 LHKP terbaru untuk ditampilkan di tabel dashboard
        $recent_lhkps = LhkpReport::whereHas('user', function($query) use ($user) {
            $query->where('company_name', $user->company_name);
        })->latest()->limit(5)->get();

        return view('indie.manager-unit.dashboard', compact('lhkp_count', 'recent_lhkps'));
    }
}