<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\SoldRice;
use App\Models\StorePartner;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SoldRiceController extends Controller
{
    public function index()
    {
        $soldRices = SoldRice::latest()->get();
        
        // Ambil data toko aktif untuk dropdown
        $stores = StorePartner::where('catatan_status', 'aktif')
                              ->orderBy('nama_toko', 'asc')
                              ->get();

        return view('eco.operasional.sold-rice.index', compact('soldRices', 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tempat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nama_toko' => 'required|string|max:255',
            'kunjungan_ke' => 'required|in:1,2,3,4,5,6',
            'ukuran' => 'required|in:2.5kg,5kg',
        ]);

        SoldRice::create($request->all());
        return redirect()->back()->with('success', 'Data beras terjual berhasil disimpan!');
    }

    public function destroy(SoldRice $soldRice)
    {
        $soldRice->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function exportPdf()
    {
        $soldRices = SoldRice::orderBy('tanggal', 'desc')->get();
        $pdf = Pdf::loadView('eco.operasional.sold-rice.pdf', compact('soldRices'))->setPaper('a4', 'portrait');
        return $pdf->download('Daftar_Beras_Terjual_' . date('Y-m-d') . '.pdf');
    }
}