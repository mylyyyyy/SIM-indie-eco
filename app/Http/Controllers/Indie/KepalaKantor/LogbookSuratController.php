<?php

namespace App\Http\Controllers\Indie\KepalaKantor;

use App\Http\Controllers\Controller;
use App\Models\LogbookSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogbookSuratController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $logbooks = LogbookSurat::where('user_id', $user->id)->latest()->get();

        return view('indie.kepala-kantor.logbook-surat.index', compact('logbooks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_bulan' => 'required|string',
            'jumlah_surat_masuk' => 'required|integer|min:0',
            'jumlah_surat_keluar' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'dokumen_lampiran' => 'nullable|file|mimes:pdf,jpg,png,jpeg,xls,xlsx|max:2048'
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

        LogbookSurat::create($data);
        return redirect()->back()->with('success', 'Laporan Logbook Surat berhasil dikirim!');
    }

    public function destroy($id)
    {
        $logbook = LogbookSurat::where('user_id', Auth::id())->findOrFail($id);
        
        if ($logbook->status == 'Approved') {
            return redirect()->back()->withErrors(['msg' => 'Data yang sudah disetujui tidak dapat dihapus.']);
        }
        
        $logbook->delete();
        return redirect()->back()->with('success', 'Data Logbook Surat berhasil dihapus!');
    }
}