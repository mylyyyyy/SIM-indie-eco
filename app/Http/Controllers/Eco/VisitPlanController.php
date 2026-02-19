<?php

namespace App\Http\Controllers\Eco;
use App\Http\Controllers\Controller;
use App\Models\VisitPlan;
use App\Models\StorePartner; // <--- 1. IMPORT MODEL INI
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class VisitPlanController extends Controller
{
    public function index()
    {
        $plans = VisitPlan::latest()->get();
        
        // 2. AMBIL DATA TOKO YANG AKTIF SAJA
        $stores = StorePartner::where('catatan_status', 'aktif')
                              ->orderBy('nama_toko', 'asc')
                              ->get();

        // 3. KIRIM VARIABEL $stores KE VIEW
        return view('eco.operasional.visit-plan.index', compact('plans', 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required',
            'alamat' => 'required',
            'stok_awal' => 'required|numeric',
            'harga' => 'required|numeric',
            'laku_pack' => 'required|numeric',
            'sisa_pack' => 'required|numeric',
            'tambah_pack' => 'required|numeric',
        ]);

        VisitPlan::create($request->all());
        return redirect()->back()->with('success', 'Plan Kunjungan berhasil ditambahkan!');
    }

    public function destroy(VisitPlan $visitPlan)
    {
        $visitPlan->delete();
        return redirect()->back()->with('success', 'Data dihapus!');
    }

    // Fungsi Download PDF (Sesuai role yang diminta: mengetahui Manager & Admin)
    public function exportPdf()
    {
        $plans = VisitPlan::latest()->get();
        $pdf = Pdf::loadView('eco.operasional.visit-plan.pdf', compact('plans'))->setPaper('a4', 'landscape');
        return $pdf->download('Plan_Kunjungan_' . date('Y-m-d') . '.pdf');
    }
}