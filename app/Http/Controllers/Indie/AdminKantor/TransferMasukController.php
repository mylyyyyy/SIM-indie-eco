<?php

namespace App\Http\Controllers\Indie\AdminKantor;

use App\Http\Controllers\Controller;
use App\Models\RekapTransferMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferMasukController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $transfers = RekapTransferMasuk::where('user_id', $user->id)
                        ->orderBy('tanggal_transfer', 'desc')
                        ->latest()
                        ->get();

        // Hitung total uang yang sudah tervalidasi masuk
        $total_dana_diterima = $transfers->where('status', 'Diterima')->sum('nominal');

        return view('indie.admin-kantor.transfer-masuk.index', compact('transfers', 'total_dana_diterima'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_transfer' => 'required|date',
            'nama_pengirim' => 'required|string|max:255',
            'bank_asal' => 'required|string|max:100',
            'bank_tujuan' => 'required|string|max:100',
            'nominal' => 'required|numeric|min:1',
            'kategori_dana' => 'required|in:Pelunasan Proyek,DP Proyek,Pengembalian Sisa Kas,Lainnya',
            'keterangan' => 'nullable|string',
            'dokumen_lampiran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048' // Wajib lampirkan bukti transfer maks 2MB
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

        RekapTransferMasuk::create($data);
        return redirect()->back()->with('success', 'Bukti transfer berhasil dicatat! Menunggu verifikasi dari Keuangan.');
    }

    public function destroy($id)
    {
        $transfer = RekapTransferMasuk::where('user_id', Auth::id())->findOrFail($id);
        
        if ($transfer->status == 'Diterima') {
            return redirect()->back()->withErrors(['msg' => 'Dana yang sudah dikonfirmasi diterima tidak dapat dihapus.']);
        }
        
        $transfer->delete();
        return redirect()->back()->with('success', 'Catatan transfer berhasil dihapus!');
    }
}