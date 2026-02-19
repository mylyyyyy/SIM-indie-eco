<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\VisitResult;
// use App\Models\StorePartner; // <-- Tidak perlu dipanggil lagi
use Illuminate\Http\Request;

class VisitResultController extends Controller
{
    // ==========================================
    // FUNGSI UNTUK ADMIN KANTOR (ECO) - INPUT DATA
    // ==========================================
    public function index()
    {
        $results = VisitResult::latest('tanggal')->get();
        
        // Kita hapus pemanggilan $stores karena inputnya sekarang manual
        return view('eco.operasional.visit-result.index', compact('results'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|string|max:20',
            'tanggal' => 'required|date',
            'nama_toko' => 'required|string|max:255', // Validasi string biasa
            'alamat' => 'required|string',
            'titip_sisa_awal_pack' => 'required|integer|min:0',
            'harga_rp' => 'required|numeric|min:0',
            'laku_pack' => 'required|integer|min:0',
            'sisa_pack' => 'required|integer|min:0',
            'tambah_pack' => 'required|integer|min:0',
            'total_pack' => 'required|integer|min:0',
            'keterangan_bayar' => 'nullable|string',
        ]);

        VisitResult::create($request->all());
        return redirect()->back()->with('success', 'Hasil kunjungan toko berhasil disimpan!');
    }

    public function destroy(VisitResult $visitResult)
    {
        $visitResult->delete();
        return redirect()->back()->with('success', 'Data hasil kunjungan dihapus!');
    }

    // ==========================================
    // FUNGSI UNTUK KEUANGAN ECO - FILTER & EXPORT
    // ==========================================
    public function indexKeuangan(Request $request)
    {
        // Default: Urutkan dari yang terbaru
        $query = VisitResult::orderBy('tanggal', 'desc');

        // Logika Filter Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $results = $query->get();
        
        return view('eco.operasional.visit-result.keuangan', compact('results'));
    }

    public function exportExcel(Request $request)
    {
        // Default: Urutkan dari tanggal terlama (untuk laporan)
        $query = VisitResult::orderBy('tanggal', 'asc');

        // Logika Filter Tanggal (Sama dengan index)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $results = $query->get();

        // Download otomatis sebagai file .xls (Excel)
        return response(view('eco.operasional.visit-result.excel', compact('results')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="Laporan_Kunjungan_Eco_'.date('Y-m-d').'.xls"');
    }
}