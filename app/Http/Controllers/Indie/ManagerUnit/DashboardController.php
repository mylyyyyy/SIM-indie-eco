<?php

namespace App\Http\Controllers\Indie\ManagerUnit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// INI YANG SEBELUMNYA KURANG (IMPORT MODEL):
use App\Models\LhkpReport;
use App\Models\SubkonPtDailyReport;
use App\Models\SubkonPtWeeklyReport;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Ambil data LHKP (Gunakan kolom 'tempat')
        $lhkp_count = LhkpReport::where('tempat', $user->company_name)->count();
        
        $recent_lhkps = LhkpReport::where('tempat', $user->company_name)
                            ->orderBy('tanggal', 'desc')
                            ->take(5)
                            ->get();

        // 2. HITUNG JUMLAH LAPORAN HARIAN DARI SEMUA SUBKON
        $daily_report_count = SubkonPtDailyReport::count();

        // 3. HITUNG JUMLAH LAPORAN MINGGUAN DARI SEMUA SUBKON
        $weekly_report_count = SubkonPtWeeklyReport::count();

        return view('indie.manager-unit.dashboard', compact(
            'lhkp_count', 
            'recent_lhkps',
            'daily_report_count',
            'weekly_report_count'
        ));
    }
}