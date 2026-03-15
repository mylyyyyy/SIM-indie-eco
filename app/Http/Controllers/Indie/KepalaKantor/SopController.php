<?php

namespace App\Http\Controllers\Indie\KepalaKantor;

use App\Http\Controllers\Controller;
use App\Models\LaporanSop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SopController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $sops = LaporanSop::where('user_id', $user->id)->latest()->get();

        return view('indie.kepala-kantor.sop.index', compact('sops'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_bulan' => 'required|string',
            'skor_kepatuhan' => 'required|integer|min:0|max:100',
            'jumlah_pelanggaran' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'dokumen_lampiran' => 'required|file|mimes:pdf,xls,xlsx,jpg,png|max:2048'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'Submit';

        // Konversi ke Base64
        if ($request->hasFile('dokumen_lampiran')) {
            $file = $request->file('dokumen_lampiran');
            $mimeType = $file->getMimeType(); 
            $base64Data = base64_encode(file_get_contents($file->getRealPath()));
            $data['dokumen_lampiran'] = 'data:' . $mimeType . ';base64,' . $base64Data;
        }

        LaporanSop::create($data);
        return redirect()->back()->with('success', 'Laporan Kepatuhan SOP berhasil dikirim!');
    }

    public function destroy($id)
    {
        $laporan = LaporanSop::where('user_id', Auth::id())->findOrFail($id);
        
        if ($laporan->status == 'Approved') {
            return redirect()->back()->withErrors(['msg' => 'Laporan yang sudah disetujui tidak dapat dihapus.']);
        }
        
        $laporan->delete();
        return redirect()->back()->with('success', 'Laporan SOP berhasil dihapus!');
    }
}