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

        // CATATAN OTOMASI: Jika ada pengurangan/penambahan Master Stok Beras Kantor, 
        // Anda bisa meletakkan kode pengurangannya di sini.

        return redirect()->back()->with('success', 'Data stok beras toko berhasil disimpan!');
    }

    // =========================================
    // FITUR BARU: UPDATE DATA STOK
    // =========================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_toko' => 'required|string|max:255',
            'stok_2_5kg' => 'required|integer|min:0',
            'stok_5kg' => 'required|integer|min:0',
        ]);

        $stock = StoreRiceStock::findOrFail($id);

        /* * LOGIKA AUTO UPDATE TOTAL STOK (Sesuai BRD):
         * Jika Anda memiliki tabel Master Stok, hitung selisihnya di sini.
         * Contoh:
         * $selisih_2_5kg = $request->stok_2_5kg - $stock->stok_2_5kg;
         * MasterStok::where('cabang', Auth::user()->company_name)->decrement('stok_2_5kg', $selisih_2_5kg);
         */

        $stock->update([
            'tanggal' => $request->tanggal,
            'nama_toko' => $request->nama_toko,
            'stok_2_5kg' => $request->stok_2_5kg,
            'stok_5kg' => $request->stok_5kg,
        ]);

        return redirect()->back()->with('success', 'Data stok beras berhasil diperbarui!');
    }

    public function destroy(StoreRiceStock $storeRiceStock)
    {
        $storeRiceStock->delete();
        return redirect()->back()->with('success', 'Data stok beras toko berhasil dihapus!');
    }

    public function exportPdf()
    {
        // PENCEGAHAN AKSES DOWNLOAD DARI URL UNTUK ADMIN KANTOR
        if(Auth::user()->role == 'admin_kantor_eco') {
            abort(403, 'Anda tidak memiliki akses untuk mengunduh laporan ini.');
        }

        $cabangEco = Auth::user()->company_name;
        $stocks = StoreRiceStock::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->orderBy('tanggal', 'desc')->get();
                    
        $pdf = Pdf::loadView('eco.operasional.store-rice-stock.pdf', compact('stocks'))->setPaper('a4', 'portrait');
        return $pdf->download('Stok_Beras_Toko_' . date('Y-m-d') . '.pdf');
    }
}