<?php

namespace App\Http\Controllers\Indie\AdminLapangan;

use App\Http\Controllers\Controller;
use App\Models\DataOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyBahanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil data hanya untuk modul "Survey Bahan" milik user ini
        $surveys = DataOperasional::where('user_id', $user->id)
                        ->where('modul_laporan', 'Survey Bahan')
                        ->latest()
                        ->get();

        return view('indie.admin-lapangan.survey-bahan.index', compact('surveys'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_survey' => 'required|date',
            'nama_toko' => 'required|string|max:255',
            'lokasi_toko' => 'required|string|max:255',
            'nama_material' => 'required|string|max:255',
            'harga_satuan' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'keterangan_umum' => 'nullable|string',
            'dokumen_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048' // Foto harga/brosur
        ]);

        $data = new DataOperasional();
        $data->user_id = Auth::id();
        $data->kode_referensi = 'SRV-' . date('Ym') . '-' . rand(1000, 9999);
        $data->modul_laporan = 'Survey Bahan';
        $data->tanggal_periode = $request->tanggal_survey;
        $data->keterangan_umum = $request->keterangan_umum;
        $data->status = 'Submit';

        // Simpan data spesifik ke dalam kolom JSON
        $data->data_spesifik = [
            'nama_toko' => $request->nama_toko,
            'lokasi_toko' => $request->lokasi_toko,
            'nama_material' => $request->nama_material,
            'harga_satuan' => $request->harga_satuan,
            'satuan' => $request->satuan,
        ];

        // Konversi File ke Base64 (Jika ada lampiran)
        if ($request->hasFile('dokumen_lampiran')) {
            $file = $request->file('dokumen_lampiran');
            $mimeType = $file->getMimeType(); 
            $base64Data = base64_encode(file_get_contents($file->getRealPath()));
            $data->dokumen_lampiran = 'data:' . $mimeType . ';base64,' . $base64Data;
        }

        $data->save();

        return redirect()->back()->with('success', 'Hasil survey bahan berhasil disimpan!');
    }

    public function destroy($id)
    {
        $survey = DataOperasional::where('user_id', Auth::id())
                    ->where('modul_laporan', 'Survey Bahan')
                    ->findOrFail($id);
        
        if ($survey->status == 'Approved') {
            return redirect()->back()->withErrors(['msg' => 'Data yang sudah disetujui tidak dapat dihapus.']);
        }
        
        $survey->delete();
        return redirect()->back()->with('success', 'Data survey bahan berhasil dihapus!');
    }
}