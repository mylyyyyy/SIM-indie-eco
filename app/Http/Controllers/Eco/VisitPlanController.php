<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\VisitPlan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class VisitPlanController extends Controller
{
    public function index()
    {
        $cabangEco = Auth::user()->company_name;
        $plans = VisitPlan::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->latest()->get();
                    
        return view('eco.operasional.visit-plan.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255', 
            'alamat' => 'required|string',
            'stok_awal' => 'required|numeric',
            'harga' => 'required|numeric',
            'laku_pack' => 'required|numeric',
            'sisa_pack' => 'required|numeric',
            'tambah_pack' => 'required|numeric',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); // Simpan ID User
        VisitPlan::create($data);
        
        return redirect()->back()->with('success', 'Plan Kunjungan berhasil ditambahkan!');
    }

    public function destroy(VisitPlan $visitPlan)
    {
        $visitPlan->delete();
        return redirect()->back()->with('success', 'Data dihapus!');
    }

    public function exportPdf()
    {
        $cabangEco = Auth::user()->company_name;
        $plans = VisitPlan::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->latest()->get();
                    
        $pdf = Pdf::loadView('eco.operasional.visit-plan.pdf', compact('plans'))->setPaper('a4', 'landscape');
        return $pdf->download('Plan_Kunjungan_' . date('Y-m-d') . '.pdf');
    }
}