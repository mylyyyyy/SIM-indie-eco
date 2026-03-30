<?php

namespace App\Http\Controllers\Indie\AdminKantor;

use App\Http\Controllers\Controller;
use App\Models\ArsipTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArsipTransaksiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $arsips = ArsipTransaksi::where('user_id', $user->id)
                        ->orderBy('tanggal', 'desc')
                        ->latest()
                        ->get();

        return view('indie.admin-kantor.arsip-transaksi.index', compact('arsips'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|in:Invoice,Kwitansi,Faktur Pajak,Bukti Bayar,Lainnya',
            'nama_dokumen' => 'required|string|max:255',
            'nama_pihak_terkait' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'dokumen_lampiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:3072' // Wajib lampirkan file maks 3MB
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Konversi File ke Base64
        if ($request->hasFile('dokumen_lampiran')) {
            $file = $request->file('dokumen_lampiran');
            $mimeType = $file->getMimeType(); 
            $base64Data = base64_encode(file_get_contents($file->getRealPath()));
            $data['dokumen_lampiran'] = 'data:' . $mimeType . ';base64,' . $base64Data;
        }

        ArsipTransaksi::create($data);
        return redirect()->back()->with('success', 'Dokumen berhasil diarsipkan!');
    }

    public function destroy($id)
    {
        $arsip = ArsipTransaksi::where('user_id', Auth::id())->findOrFail($id);
        $arsip->delete();
        return redirect()->back()->with('success', 'Arsip dokumen berhasil dihapus!');
    }
}