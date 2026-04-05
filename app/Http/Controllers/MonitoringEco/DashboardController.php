<?php

namespace App\Http\Controllers\MonitoringEco;

use App\Http\Controllers\Controller;
use App\Models\LhkpReport;      // Laporan Manager Unit
use App\Models\LhReport;        // Laporan Kepala Kantor
use App\Models\VisitResult;     // Laporan Operasional Eco
use App\Models\SoldRice;        // Laporan Operasional Eco
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil 10 Laporan Kinerja Pegawai (Manager Unit) Terbaru
        $lhkps = LhkpReport::with('user')->latest()->limit(10)->get();

        // 2. Ambil 10 Laporan Harian (Kepala Kantor) Terbaru
        $lhs = LhReport::with(['user', 'fotos'])->latest()->limit(10)->get();

        // 3. Ambil 10 Hasil Kunjungan Toko (Admin Eco) Terbaru
        $visits = VisitResult::with('user')->latest('tanggal')->limit(10)->get();

        // 4. Ambil 10 Laporan Beras Terjual (Admin Eco) Terbaru
        $soldRices = SoldRice::with('user')->latest('tanggal')->limit(10)->get();

        // 5. Hitung Statistik Singkat
        $stats = [
            'total_kunjungan_bulan_ini' => VisitResult::whereMonth('tanggal', date('m'))->count(),
            'total_beras_terjual_bulan_ini' => SoldRice::whereMonth('tanggal', date('m'))->sum('jumlah_pack'),
            'total_lh_bulan_ini' => LhReport::whereMonth('tanggal', date('m'))->count(),
        ];

        return view('monitoring-eco.dashboard', compact('lhkps', 'lhs', 'visits', 'soldRices', 'stats'));
    }
}