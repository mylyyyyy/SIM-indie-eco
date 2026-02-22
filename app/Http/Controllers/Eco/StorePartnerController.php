<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\StorePartner;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class StorePartnerController extends Controller
{
    public function index()
    {
        $cabangEco = Auth::user()->company_name;
        $partners = StorePartner::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->latest()->get();
                    
        return view('eco.operasional.store-partner.index', compact('partners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_update' => 'required|date',
            'kantor_cabang' => 'required|string|max:255',
            'kode_toko' => 'required|string|max:50|unique:store_partners',
            'nama_toko' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'catatan_status' => 'required|in:aktif,tidak aktif',
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); // Simpan ID User

        if ($request->hasFile('foto_toko')) {
            $file = $request->file('foto_toko');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/stores'), $filename);
            $data['foto_toko'] = $filename;
        }

        StorePartner::create($data);
        return redirect()->back()->with('success', 'Data Mitra Toko berhasil ditambahkan!');
    }

    public function destroy(StorePartner $storePartner)
    {
        if ($storePartner->foto_toko) {
            $imagePath = public_path('uploads/stores/' . $storePartner->foto_toko);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $storePartner->delete();
        return redirect()->back()->with('success', 'Data Mitra Toko berhasil dihapus!');
    }

    public function exportPdf()
    {
        $cabangEco = Auth::user()->company_name;
        $partners = StorePartner::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->orderBy('nama_toko', 'asc')->get();
                    
        $pdf = Pdf::loadView('eco.operasional.store-partner.pdf', compact('partners'))->setPaper('a4', 'landscape');
        return $pdf->download('Rekap_Mitra_Toko_' . date('Y-m-d') . '.pdf');
    }
}