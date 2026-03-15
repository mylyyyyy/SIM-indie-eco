<?php

namespace App\Http\Controllers\Indie\KepalaKantor;

use App\Http\Controllers\Controller;
use App\Models\InventarisKantor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventarisController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $inventaris = InventarisKantor::where('user_id', $user->id)->latest()->get();

        return view('indie.kepala-kantor.inventaris.index', compact('inventaris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode_bulan' => 'required|string',
            'total_aset_baik' => 'required|integer|min:0',
            'total_aset_rusak' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'dokumen_lampiran' => 'required|file|mimes:pdf,xls,xlsx|max:2048'
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

        InventarisKantor::create($data);
        return redirect()->back()->with('success', 'Laporan Inventaris berhasil dikirim!');
    }

    public function destroy($id)
    {
        $laporan = InventarisKantor::where('user_id', Auth::id())->findOrFail($id);
        
        if ($laporan->status == 'Approved') {
            return redirect()->back()->withErrors(['msg' => 'Laporan yang sudah disetujui tidak dapat dihapus.']);
        }
        
        $laporan->delete();
        return redirect()->back()->with('success', 'Laporan Inventaris berhasil dihapus!');
    }
}