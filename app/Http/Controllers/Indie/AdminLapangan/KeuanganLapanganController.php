<?php

namespace App\Http\Controllers\Indie\AdminLapangan;

use App\Http\Controllers\Controller;
use App\Models\DataOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeuanganLapanganController extends Controller
{
    // ==========================================
    // 1. HALAMAN DASHBOARD ADMIN LAPANGAN
    // ==========================================
    public function dashboard()
    {
        $user = Auth::user();

        // Hitung statistik dari tabel Master JSON
        $total_keuangan = DataOperasional::where('user_id', $user->id)->where('modul_laporan', 'Keuangan Lapangan')->count();
        $total_survey = DataOperasional::where('user_id', $user->id)->where('modul_laporan', 'Survey Bahan')->count();

        return view('indie.admin-lapangan.dashboard', compact('total_keuangan', 'total_survey'));
    }


    // ==========================================
    // 2. MODUL LAPORAN KEUANGAN (KAS KECIL)
    // ==========================================
    public function index()
    {
        $user = Auth::user();
        
        // Ambil khusus data "Keuangan Lapangan"
        $laporans = DataOperasional::where('user_id', $user->id)
                        ->where('modul_laporan', 'Keuangan Lapangan')
                        ->latest()
                        ->get();

        return view('indie.admin-lapangan.keuangan.index', compact('laporans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_bulan' => 'required|string',
            'saldo_awal' => 'required|numeric|min:0',
            'total_pemasukan' => 'required|numeric|min:0',
            'total_pengeluaran' => 'required|numeric|min:0',
            'keterangan_umum' => 'nullable|string',
            'dokumen_lampiran' => 'required|file|mimes:pdf,xls,xlsx,jpg,png|max:2048'
        ]);

        $data = new DataOperasional();
        $data->user_id = Auth::id();
        $data->kode_referensi = 'KEU-' . date('Ym') . '-' . rand(1000, 9999);
        $data->modul_laporan = 'Keuangan Lapangan';
        $data->tanggal_periode = date('Y-m-d'); // Default hari ini untuk patokan sorting
        $data->status = 'Submit';
        $data->keterangan_umum = $request->keterangan_umum;

        // RAHASIA MASTER JSON: Simpan angka-angkanya ke array
        $data->data_spesifik = [
            'periode_bulan' => $request->periode_bulan,
            'saldo_awal' => $request->saldo_awal,
            'total_pemasukan' => $request->total_pemasukan,
            'total_pengeluaran' => $request->total_pengeluaran,
        ];

        // Konversi File ke Base64
        if ($request->hasFile('dokumen_lampiran')) {
            $file = $request->file('dokumen_lampiran');
            $mimeType = $file->getMimeType(); 
            $base64Data = base64_encode(file_get_contents($file->getRealPath()));
            $data->dokumen_lampiran = 'data:' . $mimeType . ';base64,' . $base64Data;
        }

        $data->save();
        return redirect()->back()->with('success', 'Laporan Keuangan Lapangan berhasil dikirim!');
    }

    public function destroy($id)
    {
        $laporan = DataOperasional::where('user_id', Auth::id())
                        ->where('modul_laporan', 'Keuangan Lapangan')
                        ->findOrFail($id);
        
        if ($laporan->status == 'Approved') {
            return redirect()->back()->withErrors(['msg' => 'Laporan yang sudah disetujui tidak dapat dihapus.']);
        }
        
        $laporan->delete();
        return redirect()->back()->with('success', 'Laporan Keuangan berhasil dihapus!');
    }
}