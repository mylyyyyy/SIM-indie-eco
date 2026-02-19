<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\PlasticStock;
use App\Models\StorePartner;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PlasticStockController extends Controller
{
    public function index()
    {
        $stocks = PlasticStock::latest()->get();
        
        // Ambil data toko aktif untuk dropdown
        $stores = StorePartner::where('catatan_status', 'aktif')
                              ->orderBy('nama_toko', 'asc')
                              ->get();

        return view('eco.operasional.plastic-stock.index', compact('stocks', 'stores'));
    }

    public function store(Request $request)
    {
        // 1. Validasi dasar
        $request->validate([
            'tempat_select' => 'required', // Dropdown wajib dipilih
            'tanggal' => 'required|date',
            'jenis_plastik' => 'required|string|max:255',
            'stok_awal' => 'required|numeric|min:0',
            'stok_sisa' => 'required|numeric|min:0',
        ]);

        $data = $request->except(['tempat_select', 'tempat_manual']);

        // 2. Logika Penentuan Tempat / Toko
        if ($request->tempat_select == 'Lainnya') {
            // Jika pilih "Lainnya", input manual wajib diisi
            $request->validate([
                'tempat_manual' => 'required|string|max:255',
            ]);
            $data['tempat'] = $request->tempat_manual;
        } else {
            // Jika pilih dari list, gunakan value dropdown
            $data['tempat'] = $request->tempat_select;
        }

        // 3. Simpan ke database
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
        $stocks = PlasticStock::orderBy('tanggal', 'desc')->get();
        $pdf = Pdf::loadView('eco.operasional.plastic-stock.pdf', compact('stocks'))->setPaper('a4', 'portrait');
        return $pdf->download('Laporan_Stok_Plastik_' . date('Y-m-d') . '.pdf');
    }
}