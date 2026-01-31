<?php

namespace App\Http\Controllers\SubkonEks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProjectReport;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Ambil Statistik Laporan User Ini
        $stats = [
            'total' => ProjectReport::where('user_id', $userId)->count(),
            'approved' => ProjectReport::where('user_id', $userId)->where('status', 'approved')->count(),
            'rejected' => ProjectReport::where('user_id', $userId)->where('status', 'rejected')->count(),
            'pending' => ProjectReport::where('user_id', $userId)->where('status', 'pending')->count(),
        ];

        // 2. Ambil 5 Laporan Terakhir untuk tabel ringkasan
        $recentReports = ProjectReport::where('user_id', $userId)
            ->with('project')
            ->latest()
            ->take(5)
            ->get();

        return view('subkon-eks.dashboard', compact('stats', 'recentReports'));
    }
}