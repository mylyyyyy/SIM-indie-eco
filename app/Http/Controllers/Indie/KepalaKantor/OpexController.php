<?php

namespace App\Http\Controllers\Indie\KepalaKantor;

use App\Http\Controllers\Controller;
use App\Models\RekapAnggaranOpex;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpexController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $opex_reports = RekapAnggaranOpex::where('user_id', $user->id)->latest()->get();

        return view('indie.kepala-kantor.opex.index', compact('opex_reports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_bulan' => 'required|string',
            'total_anggaran' => 'required|numeric|min:0',
            'total_pengeluaran' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'dokumen_lampiran' => 'required|file|mimes:pdf,xls,xlsx,jpg,png|max:2048' // Wajib ada bukti LPJ
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

        RekapAnggaranOpex::create($data);
        return redirect()->back()->with('success', 'Laporan Anggaran Opex berhasil dikirim!');
    }

    public function destroy($id)
    {
        $laporan = RekapAnggaranOpex::where('user_id', Auth::id())->findOrFail($id);
        
        if ($laporan->status == 'Approved') {
            return redirect()->back()->withErrors(['msg' => 'Laporan yang sudah disetujui tidak dapat dihapus.']);
        }
        
        $laporan->delete();
        return redirect()->back()->with('success', 'Laporan Anggaran Opex berhasil dihapus!');
    }
}