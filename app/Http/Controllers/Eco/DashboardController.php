<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\EcoLocation;
use App\Models\EcoStockLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth; // Wajib import

class DashboardController extends Controller
{
    public function index()
    {
        $cabangEco = Auth::user()->company_name;

        // 1. Ambil Data Lokasi yang SAMA dengan cabang admin
        $locations = EcoLocation::where('name', $cabangEco)->latest()->get();

        // 2. Ambil Riwayat Transaksi khusus cabang ini
        $history = EcoStockLog::whereHas('location', function($query) use ($cabangEco) {
                        $query->where('name', $cabangEco);
                    })->latest()->limit(5)->get();

        // 3. Hitung Statistik Real-time
        $stats = [
            'total_stock'   => $locations->sum('current_stock'),
            'active_shops'  => $locations->where('type', 'shop')->where('status', 'active')->count(),
            // Hitung barang masuk (in) hari ini untuk cabang ini
            'milling_today' => EcoStockLog::where('type', 'in')
                                ->whereHas('location', function($q) use ($cabangEco) {
                                    $q->where('name', $cabangEco);
                                })
                                ->whereDate('created_at', Carbon::today())
                                ->sum('amount')
        ];

        return view('eco.dashboard', compact('stats', 'locations', 'history'));
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:eco_locations,id',
            'type'      => 'required|in:in,out',
            'amount'    => 'required|numeric|min:1',
            'note'      => 'nullable|string|max:255',
        ]);

        $location = EcoLocation::findOrFail($request->branch_id);

        if ($request->type == 'out' && $location->current_stock < $request->amount) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk dikeluarkan!');
        }

        if ($request->type == 'in') {
            $location->increment('current_stock', $request->amount);
        } else {
            $location->decrement('current_stock', $request->amount);
        }

        EcoStockLog::create([
            'location_id' => $location->id,
            'type'        => $request->type,
            'amount'      => $request->amount,
            'description' => $request->note ?? ($request->type == 'in' ? 'Stok Masuk' : 'Stok Keluar'),
        ]);

        return redirect()->back()->with('success', 'Stok berhasil diperbarui!');
    }

    public function exportLog()
    {
        $cabangEco = Auth::user()->company_name;
        $fileName = 'Laporan_Stok_Beras_Eco_' . date('Y-m-d_H-i') . '.csv';
        
        $logs = EcoStockLog::whereHas('location', function($query) use ($cabangEco) {
                    $query->where('name', $cabangEco);
                })->with('location')->latest()->get();

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
        $cabangEco = Auth::user()->company_name;
        $locations = EcoLocation::where('name', $cabangEco)->with(['stockLogs' => function($query) {
            $query->latest()->limit(10);
        }])->get();

        return view('eco.reports-print', compact('locations'));
    }
}