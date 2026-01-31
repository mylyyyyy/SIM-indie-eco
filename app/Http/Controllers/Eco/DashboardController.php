<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\EcoLocation;
use App\Models\EcoStockLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Lokasi dari Database
        $locations = EcoLocation::latest()->get();

        // 2. Ambil Riwayat Transaksi (5 Terakhir)
        $history = EcoStockLog::with('location')->latest()->limit(5)->get();

        // 3. Hitung Statistik Real-time
        $stats = [
            'total_stock'   => $locations->sum('current_stock'),
            'active_shops'  => $locations->where('type', 'shop')->where('status', 'active')->count(),
            // Hitung barang masuk (in) hari ini
            'milling_today' => EcoStockLog::where('type', 'in')
                                ->whereDate('created_at', Carbon::today())
                                ->sum('amount')
        ];

        return view('eco.dashboard', compact('stats', 'locations', 'history'));
    }

    public function updateStock(Request $request)
    {
        // Validasi Input
        $request->validate([
            'branch_id' => 'required|exists:eco_locations,id',
            'type'      => 'required|in:in,out',
            'amount'    => 'required|numeric|min:1',
            'note'      => 'nullable|string|max:255',
        ]);

        $location = EcoLocation::findOrFail($request->branch_id);

        // Cek Stok Cukup (Jika Barang Keluar)
        if ($request->type == 'out' && $location->current_stock < $request->amount) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk dikeluarkan!');
        }

        // 1. Update Stok di Tabel Lokasi
        if ($request->type == 'in') {
            $location->increment('current_stock', $request->amount);
        } else {
            $location->decrement('current_stock', $request->amount);
        }

        // 2. Simpan Riwayat ke Log
        EcoStockLog::create([
            'location_id' => $location->id,
            'type'        => $request->type,
            'amount'      => $request->amount,
            'description' => $request->note ?? ($request->type == 'in' ? 'Stok Masuk' : 'Stok Keluar'),
        ]);

        return redirect()->back()->with('success', 'Stok berhasil diperbarui!');
    }
}