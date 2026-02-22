<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\StoreRiceStock;
use App\Models\StorePartner;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class StoreRiceStockController extends Controller
{
    public function index()
    {
        $cabangEco = Auth::user()->company_name;
        $stocks = StoreRiceStock::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->latest('tanggal')->get();
                    
        $stores = StorePartner::where('catatan_status', 'aktif')
                              ->whereHas('user', function($q) use ($cabangEco) {
                                  $q->where('company_name', $cabangEco);
                              })->orderBy('nama_toko', 'asc')->get();
        
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

        $data = $request->all();
        $data['user_id'] = Auth::id(); // Simpan ID User
        StoreRiceStock::create($data);

        return redirect()->back()->with('success', 'Data stok beras toko berhasil disimpan!');
    }

    public function destroy(StoreRiceStock $storeRiceStock)
    {
        $storeRiceStock->delete();
        return redirect()->back()->with('success', 'Data stok beras toko berhasil dihapus!');
    }

    public function exportPdf()
    {
        $cabangEco = Auth::user()->company_name;
        $stocks = StoreRiceStock::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->orderBy('tanggal', 'desc')->get();
                    
        $pdf = Pdf::loadView('eco.operasional.store-rice-stock.pdf', compact('stocks'))->setPaper('a4', 'portrait');
        return $pdf->download('Stok_Beras_Toko_' . date('Y-m-d') . '.pdf');
    }
}