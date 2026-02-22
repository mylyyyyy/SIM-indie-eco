<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\PlasticStock;
use App\Models\StorePartner;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PlasticStockController extends Controller
{
    public function index()
    {
        $cabangEco = Auth::user()->company_name;
        $stocks = PlasticStock::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->latest()->get();
        
        $stores = StorePartner::where('catatan_status', 'aktif')
                              ->whereHas('user', function($q) use ($cabangEco) {
                                  $q->where('company_name', $cabangEco);
                              })->orderBy('nama_toko', 'asc')->get();

        return view('eco.operasional.plastic-stock.index', compact('stocks', 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tempat_select' => 'required',
            'tanggal' => 'required|date',
            'jenis_plastik' => 'required|string|max:255',
            'stok_awal' => 'required|numeric|min:0',
            'stok_sisa' => 'required|numeric|min:0',
        ]);

        $data = $request->except(['tempat_select', 'tempat_manual']);
        $data['user_id'] = Auth::id(); // Simpan ID User

        if ($request->tempat_select == 'Lainnya') {
            $request->validate(['tempat_manual' => 'required|string|max:255']);
            $data['tempat'] = $request->tempat_manual;
        } else {
            $data['tempat'] = $request->tempat_select;
        }

        PlasticStock::create($data);
        return redirect()->back()->with('success', 'Laporan stok plastik berhasil ditambahkan!');
    }

    public function destroy(PlasticStock $plasticStock)
    {
        $plasticStock->delete();
        return redirect()->back()->with('success', 'Data stok plastik berhasil dihapus!');
    }

    public function exportPdf()
    {
        $cabangEco = Auth::user()->company_name;
        $stocks = PlasticStock::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->orderBy('tanggal', 'desc')->get();
                    
        $pdf = Pdf::loadView('eco.operasional.plastic-stock.pdf', compact('stocks'))->setPaper('a4', 'portrait');
        return $pdf->download('Laporan_Stok_Plastik_' . date('Y-m-d') . '.pdf');
    }
}