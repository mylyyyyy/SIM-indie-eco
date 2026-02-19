<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\MillingReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MillingReportController extends Controller
{
    public function index()
    {
        $reports = MillingReport::latest('tanggal')->get();
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

        MillingReport::create($request->all());

        return redirect()->back()->with('success', 'Laporan Selep berhasil ditambahkan!');
    }

    public function destroy(MillingReport $millingReport)
    {
        $millingReport->delete();
        return redirect()->back()->with('success', 'Data Laporan Selep berhasil dihapus!');
    }

    public function exportPdf()
    {
        $reports = MillingReport::orderBy('tanggal', 'desc')->get();
        $pdf = Pdf::loadView('eco.operasional.milling-report.pdf', compact('reports'))->setPaper('a4', 'portrait');
        return $pdf->download('Laporan_Selep_' . date('Y-m-d') . '.pdf');
    }
}