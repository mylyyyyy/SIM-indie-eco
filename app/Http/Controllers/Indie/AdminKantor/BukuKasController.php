<?php

namespace App\Http\Controllers\Indie\AdminKantor;

use App\Http\Controllers\Controller;
use App\Models\BukuKasIndie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BukuKasController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil data kas milik admin yang sedang login
        $kas_items = BukuKasIndie::where('user_id', $user->id)
                        ->orderBy('tanggal', 'desc')
                        ->latest()
                        ->get();

        // Hitung Saldo
        $total_pemasukan = $kas_items->where('jenis_transaksi', 'Pemasukan')->sum('nominal');
        $total_pengeluaran = $kas_items->where('jenis_transaksi', 'Pengeluaran')->sum('nominal');
        $saldo_akhir = $total_pemasukan - $total_pengeluaran;

        return view('indie.admin-kantor.buku-kas.index', compact('kas_items', 'total_pemasukan', 'total_pengeluaran', 'saldo_akhir'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'jenis_transaksi' => 'required|in:Pemasukan,Pengeluaran',
            'nominal' => 'required|numeric|min:1',
            'keterangan' => 'required|string',
            'dokumen_lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048' // Bukti Kwitansi/Nota
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'Submit';

        // Konversi File ke Base64
        if ($request->hasFile('dokumen_lampiran')) {
            $file = $request->file('dokumen_lampiran');
            $mimeType = $file->getMimeType(); 
            $base64Data = base64_encode(file_get_contents($file->getRealPath()));
            $data['dokumen_lampiran'] = 'data:' . $mimeType . ';base64,' . $base64Data;
        }

        BukuKasIndie::create($data);
        return redirect()->back()->with('success', 'Transaksi Kas berhasil dicatat!');
    }

    public function destroy($id)
    {
        $kas = BukuKasIndie::where('user_id', Auth::id())->findOrFail($id);
        
        if ($kas->status == 'Approved') {
            return redirect()->back()->withErrors(['msg' => 'Transaksi yang sudah disetujui tidak dapat dihapus.']);
        }
        
        $kas->delete();
        return redirect()->back()->with('success', 'Data kas berhasil dihapus!');
    }
}