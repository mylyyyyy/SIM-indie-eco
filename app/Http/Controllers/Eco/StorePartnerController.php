<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\StorePartner;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
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
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Batas ukuran file
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); 

        // KONVERSI FOTO MENJADI BASE64
        if ($request->hasFile('foto_toko')) {
            $file = $request->file('foto_toko');
            $mimeType = $file->getMimeType(); 
            $base64Data = base64_encode(file_get_contents($file->getRealPath()));
            $data['foto_toko'] = 'data:' . $mimeType . ';base64,' . $base64Data;
        }

        StorePartner::create($data);
        return redirect()->back()->with('success', 'Data Mitra Toko berhasil ditambahkan!');
    }

    // =====================================
    // FITUR BARU: UPDATE DATA TOKO
    // =====================================
    public function update(Request $request, $id)
    {
        $storePartner = StorePartner::findOrFail($id);

        $request->validate([
            'tanggal_update' => 'required|date',
            'kantor_cabang' => 'required|string|max:255',
            'kode_toko' => 'required|string|max:50|unique:store_partners,kode_toko,'.$storePartner->id,
            'nama_toko' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'catatan_status' => 'required|in:aktif,tidak aktif',
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except(['_token', '_method']);

        // Jika upload foto baru, ganti dengan string Base64 yang baru
        if ($request->hasFile('foto_toko')) {
            $file = $request->file('foto_toko');
            $mimeType = $file->getMimeType(); 
            $base64Data = base64_encode(file_get_contents($file->getRealPath()));
            $data['foto_toko'] = 'data:' . $mimeType . ';base64,' . $base64Data;
        }

        $storePartner->update($data);

        return redirect()->back()->with('success', 'Data Mitra Toko berhasil diperbarui!');
    }

    public function destroy(StorePartner $storePartner)
    {
        // Karena Base64 tersimpan di tabel (bukan di storage), kita cukup delete modelnya saja
        $storePartner->delete();
        return redirect()->back()->with('success', 'Data Mitra Toko berhasil dihapus!');
    }

    public function exportPdf()
    {
        // CEGAH ADMIN KANTOR UNDUH PDF (Sesuai BRD)
        if(Auth::user()->role == 'admin_kantor_eco') {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh laporan PDF.');
        }

        $cabangEco = Auth::user()->company_name;
        // PENTING: Toko Nonaktif tetap masuk dalam laporan PDF
        $partners = StorePartner::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->orderBy('nama_toko', 'asc')->get();
                    
        $pdf = Pdf::loadView('eco.operasional.store-partner.pdf', compact('partners'))->setPaper('a4', 'landscape');
        return $pdf->download('Rekap_Mitra_Toko_' . date('Y-m-d') . '.pdf');
    }
}