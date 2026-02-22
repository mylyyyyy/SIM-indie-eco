<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\MillingReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class MillingReportController extends Controller
{
    public function index()
    {
        $cabangEco = Auth::user()->company_name;
        $reports = MillingReport::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->latest('tanggal')->get();
                    
        return view('eco.operasional.milling-report.index', compact('reports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'ambil_beras_kg' => 'required|numeric|min:0',
            'jumlah_pack' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); // Simpan ID User
        MillingReport::create($data);

        return redirect()->back()->with('success', 'Laporan Selep berhasil ditambahkan!');
    }

    public function destroy(MillingReport $millingReport)
    {
        $millingReport->delete();
        return redirect()->back()->with('success', 'Data Laporan Selep berhasil dihapus!');
    }

    public function exportPdf()
    {
        $cabangEco = Auth::user()->company_name;
        $reports = MillingReport::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->orderBy('tanggal', 'desc')->get();
                    
        $pdf = Pdf::loadView('eco.operasional.milling-report.pdf', compact('reports'))->setPaper('a4', 'portrait');
        return $pdf->download('Laporan_Selep_' . date('Y-m-d') . '.pdf');
    }
}