<?php

namespace App\Http\Controllers\Indie\AdminLapangan;

use App\Http\Controllers\Controller;
use App\Models\DataOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgresFisikController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil data hanya untuk modul "Progres Fisik" milik user ini
        $progres = DataOperasional::where('user_id', $user->id)
                        ->where('modul_laporan', 'Progres Fisik')
                        ->latest()
                        ->get();

        return view('indie.admin-lapangan.progres-fisik.index', compact('progres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_laporan' => 'required|date',
            'minggu_ke' => 'required|integer|min:1',
            'target_progres' => 'required|numeric|min:0|max:100',
            'realisasi_progres' => 'required|numeric|min:0|max:100',
            'kendala_utama' => 'nullable|string',
            'keterangan_umum' => 'required|string',
            'dokumen_lampiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:3072' // Wajib foto progres maks 3MB
        ]);

        $data = new DataOperasional();
        $data->user_id = Auth::id();
        $data->kode_referensi = 'PRG-' . date('Ym') . '-' . rand(1000, 9999);
        $data->modul_laporan = 'Progres Fisik';
        $data->tanggal_periode = $request->tanggal_laporan;
        $data->keterangan_umum = $request->keterangan_umum;
        $data->status = 'Submit';

        // Masukkan data angka/spesifik ke dalam keranjang JSON
        $data->data_spesifik = [
            'minggu_ke' => $request->minggu_ke,
            'target_progres' => $request->target_progres,
            'realisasi_progres' => $request->realisasi_progres,
            'kendala_utama' => $request->kendala_utama,
        ];

        // Konversi File Foto/PDF ke Base64
        if ($request->hasFile('dokumen_lampiran')) {
            $file = $request->file('dokumen_lampiran');
            $mimeType = $file->getMimeType(); 
            $base64Data = base64_encode(file_get_contents($file->getRealPath()));
            $data->dokumen_lampiran = 'data:' . $mimeType . ';base64,' . $base64Data;
        }

        $data->save();

        return redirect()->back()->with('success', 'Laporan Progres Fisik berhasil dikirim!');
    }

    public function destroy($id)
    {
        $laporan = DataOperasional::where('user_id', Auth::id())
                    ->where('modul_laporan', 'Progres Fisik')
                    ->findOrFail($id);
        
        if ($laporan->status == 'Approved') {
            return redirect()->back()->withErrors(['msg' => 'Data progres yang sudah disetujui tidak dapat dihapus.']);
        }
        
        $laporan->delete();
        return redirect()->back()->with('success', 'Data progres berhasil dihapus!');
    }
}