<?php

namespace App\Http\Controllers\KepalaKantor;

use App\Http\Controllers\Controller;
use App\Models\LhReport;
use App\Models\LhReportFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LhController extends Controller
{
    public function index()
    {
        $lhs = LhReport::with('fotos')
                ->where('user_id', Auth::id())
                ->latest()
                ->limit(20)
                ->get(); 
                
        return view('kepala-kantor.dashboard', compact('lhs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|array',
            'kegiatan.*' => 'required|string',
            'dokumentasi.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' 
        ], [
            'dokumentasi.*.image' => 'File lampiran harus berupa gambar.',
            'dokumentasi.*.mimes' => 'Format gambar harus berupa jpeg, png, jpg, atau gif.',
            'dokumentasi.*.max' => 'Ukuran setiap gambar maksimal adalah 2MB.'
        ]);

        // 1. Simpan Data Utama
        $laporan = LhReport::create([
            'user_id' => Auth::id(), 
            'nama_cabang' => Auth::user()->company_name ?? 'Pusat', 
            'tanggal' => $request->tanggal,
            'rincian_kegiatan' => json_encode(array_values(array_filter($request->kegiatan))), 
        ]);

        // 2. Simpan Multiple Foto sebagai Base64 ke Database
        if ($request->hasFile('dokumentasi')) {
            foreach ($request->file('dokumentasi') as $file) {
                $mimeType = $file->getMimeType(); 
                $base64Data = base64_encode(file_get_contents($file->getRealPath()));
                $base64String = 'data:' . $mimeType . ';base64,' . $base64Data;
                
                LhReportFoto::create([
                    'lh_report_id' => $laporan->id,
                    'path_foto' => $base64String // Menyimpan string Base64 yang panjang
                ]);
            }
        }

        return redirect()->back()->with('success', 'Laporan Harian beserta dokumentasi berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $lh = LhReport::with('fotos')->where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|array',
            'kegiatan.*' => 'required|string',
            'dokumentasi.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);

        $lh->update([
            'tanggal' => $request->tanggal,
            'rincian_kegiatan' => json_encode(array_values(array_filter($request->kegiatan))),
        ]);

        // Jika user upload foto baru saat update
        if ($request->hasFile('dokumentasi')) {
            // Hapus data foto lama di database
            $lh->fotos()->delete();

            // Masukkan foto baru (Base64)
            foreach ($request->file('dokumentasi') as $file) {
                $mimeType = $file->getMimeType(); 
                $base64Data = base64_encode(file_get_contents($file->getRealPath()));
                $base64String = 'data:' . $mimeType . ';base64,' . $base64Data;

                LhReportFoto::create([
                    'lh_report_id' => $lh->id,
                    'path_foto' => $base64String
                ]);
            }
        }

        return redirect()->back()->with('success', 'Laporan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $lh = LhReport::where('user_id', Auth::id())->findOrFail($id);
        // Karena tidak pakai Storage fisik, cukup delete laporan utama. 
        // Data foto base64 di tabel relasi akan otomatis terhapus berkat ON DELETE CASCADE.
        $lh->delete();

        return redirect()->back()->with('success', 'Laporan beserta seluruh dokumentasinya berhasil dihapus!');
    }
}