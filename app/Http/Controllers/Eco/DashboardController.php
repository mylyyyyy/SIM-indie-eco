<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\EcoLocation;
use App\Models\EcoStockLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class DashboardController extends Controller
{
    public function exportLog()
    {
        $fileName = 'Laporan_Stok_Beras_Eco_' . date('Y-m-d_H-i') . '.csv';
        $logs = EcoStockLog::with('location')->latest()->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['Tanggal', 'Waktu', 'Lokasi', 'Tipe Aktivitas', 'Jumlah (Kg)', 'Keterangan'];

        $callback = function() use($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                $row['Tanggal'] = $log->created_at->format('Y-m-d');
                $row['Waktu'] = $log->created_at->format('H:i:s');
                $row['Lokasi'] = $log->location->name ?? 'Tidak diketahui';
                $row['Tipe Aktivitas'] = $log->type == 'in' ? 'Barang Masuk' : 'Barang Keluar';
                $row['Jumlah (Kg)'] = $log->amount;
                $row['Keterangan'] = $log->description;

                fputcsv($file, array($row['Tanggal'], $row['Waktu'], $row['Lokasi'], $row['Tipe Aktivitas'], $row['Jumlah (Kg)'], $row['Keterangan']));
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

public function exportReport()
    {
        // Ambil data lokasi (gudang/toko/selep) beserta log riwayatnya
        $locations = EcoLocation::with(['stockLogs' => function($query) {
            $query->latest()->limit(10); // Batasi 10 riwayat terakhir per lokasi agar tidak terlalu panjang
        }])->get();

        return view('eco.reports-print', compact('locations'));
    }

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