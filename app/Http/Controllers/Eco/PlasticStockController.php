<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\PlasticStock;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PlasticStockController extends Controller
{
    public function index()
    {
        $cabangEco = Auth::user()->company_name;
        
        // Filter agar hanya melihat stok plastik di cabangnya sendiri
        $stocks = PlasticStock::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->latest()->get();
        
        // Kita tidak butuh data StorePartner lagi di halaman ini
        return view('eco.operasional.plastic-stock.index', compact('stocks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_plastik' => 'required|string|max:255',
            'stok_awal' => 'required|numeric|min:0',
            'stok_sisa' => 'required|numeric|min:0',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); 
        
        // OTOMATIS MENGAMBIL NAMA CABANG DARI AKUN LOGIN
        $data['nama_cabang'] = Auth::user()->company_name ?? 'Pusat';

        PlasticStock::create($data);
        return redirect()->back()->with('success', 'Laporan stok plastik berhasil ditambahkan!');
    }

    // =====================================
    // FITUR BARU: UPDATE STOK PLASTIK
    // =====================================
    public function update(Request $request, $id)
    {
        $plasticStock = PlasticStock::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'jenis_plastik' => 'required|string|max:255',
            'stok_awal' => 'required|numeric|min:0',
            'stok_sisa' => 'required|numeric|min:0',
        ]);

        // Catatan: nama_cabang tidak diupdate agar tetap konsisten sesuai cabang awal pembuat
        $plasticStock->update([
            'tanggal' => $request->tanggal,
            'jenis_plastik' => $request->jenis_plastik,
            'stok_awal' => $request->stok_awal,
            'stok_sisa' => $request->stok_sisa,
        ]);

        return redirect()->back()->with('success', 'Data stok plastik berhasil diperbarui!');
    }

    public function destroy(PlasticStock $plasticStock)
    {
        $plasticStock->delete();
        return redirect()->back()->with('success', 'Data stok plastik berhasil dihapus!');
    }

    public function exportPdf()
    {
        if(Auth::user()->role == 'admin_kantor_eco') {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh laporan PDF.');
        }

        $cabangEco = Auth::user()->company_name;
        $stocks = PlasticStock::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->orderBy('tanggal', 'desc')->get();
                    
        $pdf = Pdf::loadView('eco.operasional.plastic-stock.pdf', compact('stocks'))->setPaper('a4', 'portrait');
        return $pdf->download('Laporan_Stok_Plastik_' . date('Y-m-d') . '.pdf');
    }
}