<?php

namespace App\Http\Controllers\Indie\AdminLapangan;

use App\Http\Controllers\Controller;
use App\Models\DataOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiProyekController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $absensis = DataOperasional::where('user_id', $user->id)
                        ->where('modul_laporan', 'Absensi Proyek')
                        ->latest()
                        ->get();

        return view('indie.admin-lapangan.absensi-proyek.index', compact('absensis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_absen' => 'required|date',
            'total_pekerja' => 'required|integer|min:1',
            'hadir' => 'required|integer|min:0',
            'lembur' => 'required|integer|min:0',
            'absen' => 'required|integer|min:0',
            'keterangan_umum' => 'nullable|string',
            'dokumen_lampiran' => 'required|file|mimes:pdf,jpg,jpeg,png,xls,xlsx|max:2048' 
        ]);

        $data = new DataOperasional();
        $data->user_id = Auth::id();
        $data->kode_referensi = 'ABS-' . date('Ym') . '-' . rand(1000, 9999);
        $data->modul_laporan = 'Absensi Proyek';
        $data->tanggal_periode = $request->tanggal_absen;
        $data->keterangan_umum = $request->keterangan_umum;
        $data->status = 'Submit';

        // Simpan data spesifik ke JSON
        $data->data_spesifik = [
            'total_pekerja' => $request->total_pekerja,
            'hadir' => $request->hadir,
            'lembur' => $request->lembur,
            'absen' => $request->absen,
        ];

        // Konversi File ke Base64
        if ($request->hasFile('dokumen_lampiran')) {
            $file = $request->file('dokumen_lampiran');
            $mimeType = $file->getMimeType(); 
            $base64Data = base64_encode(file_get_contents($file->getRealPath()));
            $data->dokumen_lampiran = 'data:' . $mimeType . ';base64,' . $base64Data;
        }

        $data->save();

        return redirect()->back()->with('success', 'Log Absensi Proyek berhasil disimpan!');
    }

    public function destroy($id)
    {
        $laporan = DataOperasional::where('user_id', Auth::id())
                    ->where('modul_laporan', 'Absensi Proyek')
                    ->findOrFail($id);
        
        if ($laporan->status == 'Approved') {
            return redirect()->back()->withErrors(['msg' => 'Data yang sudah disetujui tidak dapat dihapus.']);
        }
        
        $laporan->delete();
        return redirect()->back()->with('success', 'Log absensi berhasil dihapus!');
    }
}