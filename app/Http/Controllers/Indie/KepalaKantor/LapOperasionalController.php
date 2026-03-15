<?php

namespace App\Http\Controllers\Indie\KepalaKantor;

use App\Http\Controllers\Controller;
use App\Models\LaporanOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LapOperasionalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $laporans = LaporanOperasional::where('user_id', $user->id)
                        ->latest()
                        ->get();

        return view('indie.kepala-kantor.lap-operasional.index', compact('laporans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_bulan' => 'required|string',
            'ringkasan_kegiatan' => 'required|string',
            // Batasi ukuran file max 2MB agar Base64 tidak memberatkan database
            'dokumen_lampiran' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'Submit'; 

        // ==========================================
        // UBAH FILE MENJADI FORMAT BASE64
        // ==========================================
        if ($request->hasFile('dokumen_lampiran')) {
            $file = $request->file('dokumen_lampiran');
            // Ambil format/tipe file (misal: application/pdf atau image/png)
            $mimeType = $file->getMimeType(); 
            // Konversi fisik file menjadi teks Base64
            $base64Data = base64_encode(file_get_contents($file->getRealPath()));
            // Rangkai menjadi format Data URI
            $data['dokumen_lampiran'] = 'data:' . $mimeType . ';base64,' . $base64Data;
        }

        LaporanOperasional::create($data);
        return redirect()->back()->with('success', 'Laporan Operasional berhasil disubmit!');
    }

    public function destroy($id)
    {
        $laporan = LaporanOperasional::where('user_id', Auth::id())->findOrFail($id);
        
        if ($laporan->status == 'Approved') {
            return redirect()->back()->withErrors(['msg' => 'Laporan yang sudah disetujui tidak dapat dihapus.']);
        }
        
        // Tidak perlu hapus file fisik (File::delete) karena data berbentuk teks di dalam Database
        $laporan->delete();
        
        return redirect()->back()->with('success', 'Laporan Operasional berhasil dihapus!');
    }
}