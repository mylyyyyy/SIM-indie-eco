<?php

namespace App\Http\Controllers\SubkonPt;

use App\Http\Controllers\Controller;
use App\Models\LhkpReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class LhkpController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Manager Proyek hanya melihat LHKP di cabang yang sama
        $lhkps = LhkpReport::whereHas('user', function($query) use ($user) {
                    $query->where('company_name', $user->company_name);
                })->latest()->get();

        // Anda bisa gunakan tampilan lhkp yang sama dengan manager unit (karena strukturnya sama)
        // atau buat view baru khusus subkon pt. Di sini saya asumsikan pakai view terpisah.
        return view('subkon-pt.lhkp.index', compact('lhkps')); 
    }

    public function exportPdf()
    {
        $user = Auth::user();
        
        // Tarik data LHKP yang sama dengan cabang manager
        $lhkps = LhkpReport::whereHas('user', function($query) use ($user) {
                    $query->where('company_name', $user->company_name);
                })->orderBy('tanggal', 'desc')->get();

        $pdf = Pdf::loadView('subkon-pt.lhkp.pdf', compact('lhkps'))->setPaper('a4', 'landscape');
        return $pdf->download('Laporan_LHKP_Proyek_' . date('Y-m-d') . '.pdf');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'tempat' => 'required|string',
            'nama_pegawai' => 'required|string',
            'nip' => 'required|string',
            'divisi' => 'required|string',
            'progres_pekerjaan' => 'required|string',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); // Wajib Simpan ID
        LhkpReport::create($data);

        return redirect()->back()->with('success', 'LHKP Proyek berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $lhkp = LhkpReport::where('user_id', Auth::id())->findOrFail($id);
        
        $request->validate([
            'tanggal' => 'required|date',
            'tempat' => 'required|string',
            'progres_pekerjaan' => 'required|string',
        ]);

        $lhkp->update($request->all());
        return redirect()->back()->with('success', 'LHKP Proyek diperbarui!');
    }

    public function destroy($id)
    {
        $lhkp = LhkpReport::where('user_id', Auth::id())->findOrFail($id);
        $lhkp->delete();
        return redirect()->back()->with('success', 'LHKP Proyek dihapus!');
    }
}