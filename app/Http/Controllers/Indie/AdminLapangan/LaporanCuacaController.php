<?php

namespace App\Http\Controllers\Indie\AdminLapangan;

use App\Http\Controllers\Controller;
use App\Models\DataOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanCuacaController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $cuacas = DataOperasional::where('user_id', $user->id)
                        ->where('modul_laporan', 'Laporan Cuaca')
                        ->latest()
                        ->get();

        return view('indie.admin-lapangan.cuaca.index', compact('cuacas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_laporan' => 'required|date',
            'kondisi_pagi' => 'required|string',
            'kondisi_siang' => 'required|string',
            'kondisi_sore' => 'required|string',
            'kondisi_malam' => 'required|string',
            'jam_hujan' => 'required|numeric|min:0|max:24',
            'keterangan_umum' => 'nullable|string',
            'dampak_pekerjaan' => 'required|string',
            'dokumen_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048' // Foto kondisi lapangan saat hujan/cerah
        ]);

        $data = new DataOperasional();
        $data->user_id = Auth::id();
        $data->kode_referensi = 'CUA-' . date('Ym') . '-' . rand(1000, 9999);
        $data->modul_laporan = 'Laporan Cuaca';
        $data->tanggal_periode = $request->tanggal_laporan;
        $data->keterangan_umum = $request->keterangan_umum;
        $data->status = 'Submit';

        // Simpan data spesifik ke JSON
        $data->data_spesifik = [
            'pagi' => $request->kondisi_pagi,
            'siang' => $request->kondisi_siang,
            'sore' => $request->kondisi_sore,
            'malam' => $request->kondisi_malam,
            'jam_hujan' => $request->jam_hujan,
            'dampak_pekerjaan' => $request->dampak_pekerjaan,
        ];

        // Konversi File ke Base64 (Jika Ada)
        if ($request->hasFile('dokumen_lampiran')) {
            $file = $request->file('dokumen_lampiran');
            $mimeType = $file->getMimeType(); 
            $base64Data = base64_encode(file_get_contents($file->getRealPath()));
            $data->dokumen_lampiran = 'data:' . $mimeType . ';base64,' . $base64Data;
        }

        $data->save();

        return redirect()->back()->with('success', 'Laporan kondisi cuaca berhasil disimpan!');
    }

    public function destroy($id)
    {
        $laporan = DataOperasional::where('user_id', Auth::id())
                    ->where('modul_laporan', 'Laporan Cuaca')
                    ->findOrFail($id);
        
        if ($laporan->status == 'Approved') {
            return redirect()->back()->withErrors(['msg' => 'Data cuaca yang sudah disetujui tidak dapat dihapus.']);
        }
        
        $laporan->delete();
        return redirect()->back()->with('success', 'Laporan cuaca berhasil dihapus!');
    }
}