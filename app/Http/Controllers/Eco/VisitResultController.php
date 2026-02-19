<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\VisitResult;
use App\Models\StorePartner;
use Illuminate\Http\Request;

class VisitResultController extends Controller
{
    // ==========================================
    // FUNGSI UNTUK ADMIN KANTOR (ECO) - INPUT DATA
    // ==========================================
    public function index()
    {
        $results = VisitResult::latest('tanggal')->get();
        $stores = StorePartner::where('catatan_status', 'aktif')->orderBy('nama_toko', 'asc')->get();
        return view('eco.operasional.visit-result.index', compact('results', 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|string|max:20',
            'tanggal' => 'required|date',
            'nama_toko' => 'required|string|max:255',
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
        $query = VisitResult::orderBy('tanggal', 'desc');

        // Fitur Filter Tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $results = $query->get();
        return view('eco.operasional.visit-result.keuangan', compact('results'));
    }

    public function exportExcel(Request $request)
    {
        $query = VisitResult::orderBy('tanggal', 'asc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $results = $query->get();

        // Download otomatis sebagai file .xls (Excel)
        return response(view('eco.operasional.visit-result.excel', compact('results')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="Hasil_Kunjungan_Toko_'.date('Y-m-d').'.xls"');
    }
}