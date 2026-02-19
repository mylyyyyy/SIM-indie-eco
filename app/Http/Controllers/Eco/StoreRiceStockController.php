<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\StoreRiceStock;
use App\Models\StorePartner;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class StoreRiceStockController extends Controller
{
    public function index()
    {
        $stocks = StoreRiceStock::latest('tanggal')->get();
        // Ambil data toko untuk dropdown
        $stores = StorePartner::where('catatan_status', 'aktif')->orderBy('nama_toko', 'asc')->get();
        
        return view('eco.operasional.store-rice-stock.index', compact('stocks', 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_admin' => 'required|string|max:255',
            'nama_toko' => 'required|string|max:255',
            'stok_2_5kg' => 'required|integer|min:0',
            'stok_5kg' => 'required|integer|min:0',
        ]);

        StoreRiceStock::create($request->all());

        return redirect()->back()->with('success', 'Data stok beras toko berhasil disimpan!');
    }

    public function destroy(StoreRiceStock $storeRiceStock)
    {
        $storeRiceStock->delete();
        return redirect()->back()->with('success', 'Data stok beras toko berhasil dihapus!');
    }

    public function exportPdf()
    {
        $stocks = StoreRiceStock::orderBy('tanggal', 'desc')->get();
        $pdf = Pdf::loadView('eco.operasional.store-rice-stock.pdf', compact('stocks'))->setPaper('a4', 'portrait');
        return $pdf->download('Stok_Beras_Toko_' . date('Y-m-d') . '.pdf');
    }
}
