<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\SoldRice;
use App\Models\StorePartner;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SoldRiceController extends Controller
{
    public function index()
    {
        $cabangEco = Auth::user()->company_name;
        $soldRices = SoldRice::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->latest()->get();
        
        $stores = StorePartner::where('catatan_status', 'aktif')
                              ->whereHas('user', function($q) use ($cabangEco) {
                                  $q->where('company_name', $cabangEco);
                              })->orderBy('nama_toko', 'asc')->get();

        return view('eco.operasional.sold-rice.index', compact('soldRices', 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tempat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nama_toko_select' => 'required', 
            'kunjungan_ke' => 'required|in:1,2,3,4,5,6',
            'ukuran' => 'required|in:2.5kg,5kg',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); // Simpan ID User

        if ($request->nama_toko_select == 'Lainnya') {
            $request->validate(['nama_toko_manual' => 'required|string|max:255']);
            $data['nama_toko'] = $request->nama_toko_manual;
        } else {
            $data['nama_toko'] = $request->nama_toko_select;
        }

        SoldRice::create($data);
        return redirect()->back()->with('success', 'Data beras terjual berhasil disimpan!');
    }

    public function destroy(SoldRice $soldRice)
    {
        $soldRice->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }

    public function exportPdf()
    {
        $cabangEco = Auth::user()->company_name;
        $soldRices = SoldRice::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->orderBy('tanggal', 'desc')->get();
                    
        $pdf = Pdf::loadView('eco.operasional.sold-rice.pdf', compact('soldRices'))->setPaper('a4', 'portrait');
        return $pdf->download('Daftar_Beras_Terjual_' . date('Y-m-d') . '.pdf');
    }
}