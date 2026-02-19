<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\PlasticStock;
use App\Models\StorePartner; // <--- 1. IMPORT MODEL INI
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PlasticStockController extends Controller
{
    public function index()
    {
        $stocks = PlasticStock::latest()->get();
        
        // 2. AMBIL DATA TOKO AKTIF DARI STORE PARTNER
        $stores = StorePartner::where('catatan_status', 'aktif')
                              ->orderBy('nama_toko', 'asc')
                              ->get();

        // 3. KIRIM VARIABEL $stores KE VIEW
        return view('eco.operasional.plastic-stock.index', compact('stocks', 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tempat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jenis_plastik' => 'required|string|max:255',
            'stok_awal' => 'required|numeric|min:0',
            'stok_sisa' => 'required|numeric|min:0',
        ]);

        PlasticStock::create($request->all());
        return redirect()->back()->with('success', 'Laporan stok plastik berhasil ditambahkan!');
    }

    public function destroy(PlasticStock $plasticStock)
    {
        $plasticStock->delete();
        return redirect()->back()->with('success', 'Data stok plastik berhasil dihapus!');
    }

    public function exportPdf()
    {
        $stocks = PlasticStock::orderBy('tanggal', 'desc')->get();
        $pdf = Pdf::loadView('eco.operasional.plastic-stock.pdf', compact('stocks'))->setPaper('a4', 'portrait');
        return $pdf->download('Laporan_Stok_Plastik_' . date('Y-m-d') . '.pdf');
    }
}