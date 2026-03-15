<?php

namespace App\Http\Controllers\Indie\ManagerUnit;

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
        
        // Menampilkan LHKP khusus cabang/unit manager tersebut
        $lhkps = LhkpReport::whereHas('user', function($query) use ($user) {
                    $query->where('company_name', $user->company_name);
                })->latest()->get();

        return view('indie.manager-unit.lhkp.index', compact('lhkps')); 
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
        $data['user_id'] = Auth::id(); 
        LhkpReport::create($data);

        return redirect()->back()->with('success', 'LHKP berhasil disimpan!');
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
        return redirect()->back()->with('success', 'LHKP diperbarui!');
    }

    public function destroy($id)
    {
        $lhkp = LhkpReport::where('user_id', Auth::id())->findOrFail($id);
        $lhkp->delete();
        return redirect()->back()->with('success', 'LHKP dihapus!');
    }

    public function exportPdf()
    {
        $user = Auth::user();
        
        $lhkps = LhkpReport::whereHas('user', function($query) use ($user) {
                    $query->where('company_name', $user->company_name);
                })->orderBy('tanggal', 'desc')->get();

        // Menggunakan view PDF yang sama dengan Subkon PT agar seragam
        $pdf = Pdf::loadView('subkon-pt.lhkp.pdf', compact('lhkps'))->setPaper('a4', 'landscape');
        return $pdf->download('Rekap_LHKP_Indie_' . date('Y-m-d') . '.pdf');
    }
}