<?php

namespace App\Http\Controllers\Indie\KepalaKantor;

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
        $lhkps = LhkpReport::whereHas('user', function($query) use ($user) {
                    $query->where('company_name', $user->company_name);
                })->latest()->get();

        return view('indie.kepala-kantor.lhkp.index', compact('lhkps')); 
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

        return redirect()->back()->with('success', 'Evaluasi Kinerja berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $lhkp = LhkpReport::where('user_id', Auth::id())->findOrFail($id);
        $lhkp->update($request->all());
        return redirect()->back()->with('success', 'Evaluasi Kinerja diperbarui!');
    }

    public function destroy($id)
    {
        $lhkp = LhkpReport::where('user_id', Auth::id())->findOrFail($id);
        $lhkp->delete();
        return redirect()->back()->with('success', 'Evaluasi Kinerja dihapus!');
    }

    public function exportPdf()
    {
        $user = Auth::user();
        $lhkps = LhkpReport::whereHas('user', function($query) use ($user) {
                    $query->where('company_name', $user->company_name);
                })->orderBy('tanggal', 'desc')->get();

        $pdf = Pdf::loadView('subkon-pt.lhkp.pdf', compact('lhkps'))->setPaper('a4', 'landscape');
        return $pdf->download('Evaluasi_Kinerja_SDM_' . date('Y-m-d') . '.pdf');
    }
}