<?php

namespace App\Http\Controllers\KeuanganEco;

use App\Http\Controllers\Controller;
use App\Models\EcoIncome;
use Illuminate\Http\Request;

class KeuanganDashboardController extends Controller
{
    public function index(Request $request)
    {
        $queryIncome = EcoIncome::query()->latest('tanggal');

        // FILTER CABANG
        if ($request->filled('cabang')) {
            $queryIncome->where('nama_cabang', $request->cabang);
        }

        // FILTER PERIODE
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $queryIncome->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $incomes = $queryIncome->get();

        // Ambil daftar cabang unik
        $cabangs = EcoIncome::select('nama_cabang')->distinct()->pluck('nama_cabang');

        return view('keuangan-eco.dashboard', compact('incomes', 'cabangs'));
    }
}