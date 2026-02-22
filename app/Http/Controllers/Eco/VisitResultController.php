<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\VisitResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitResultController extends Controller
{
    public function index()
    {
        $cabangEco = Auth::user()->company_name;
        $results = VisitResult::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->latest('tanggal')->get();
                    
        return view('eco.operasional.visit-result.index', compact('results'));
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

        $data = $request->all();
        $data['user_id'] = Auth::id(); // Simpan ID User
        VisitResult::create($data);
        
        return redirect()->back()->with('success', 'Hasil kunjungan toko berhasil disimpan!');
    }

    public function destroy(VisitResult $visitResult)
    {
        $visitResult->delete();
        return redirect()->back()->with('success', 'Data hasil kunjungan dihapus!');
    }

    public function indexKeuangan(Request $request)
    {
        $cabangEco = Auth::user()->company_name;
        
        // Filter awal berdasarkan cabang keuangan tersebut
        $query = VisitResult::whereHas('user', function($q) use ($cabangEco) {
                        $q->where('company_name', $cabangEco);
                    })->orderBy('tanggal', 'desc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $results = $query->get();
        return view('eco.operasional.visit-result.keuangan', compact('results'));
    }

    public function exportExcel(Request $request)
    {
        $cabangEco = Auth::user()->company_name;
        
        $query = VisitResult::whereHas('user', function($q) use ($cabangEco) {
                        $q->where('company_name', $cabangEco);
                    })->orderBy('tanggal', 'asc');

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $results = $query->get();

        return response(view('eco.operasional.visit-result.excel', compact('results')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="Laporan_Kunjungan_Eco_'.date('Y-m-d').'.xls"');
    }
}