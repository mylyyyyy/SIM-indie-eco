<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\LhkpReport;
use Illuminate\Http\Request;

class LhkpController extends Controller
{
    public function index()
    {
        $lhkps = LhkpReport::latest()->get();
        return view('eco.lhkp.index', compact('lhkps'));
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

        LhkpReport::create($request->all());

        return redirect()->back()->with('success', 'LHKP berhasil disimpan!');
    }

    // --- TAMBAHAN: FUNGSI UPDATE ---
    public function update(Request $request, $id)
    {
        $lhkp = LhkpReport::findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'tempat' => 'required|string',
            'nama_pegawai' => 'required|string',
            'nip' => 'required|string',
            'divisi' => 'required|string',
            'progres_pekerjaan' => 'required|string',
        ]);

        $lhkp->update($request->all());

        return redirect()->back()->with('success', 'LHKP berhasil diperbarui!');
    }

    // --- TAMBAHAN: FUNGSI HAPUS ---
    public function destroy($id)
    {
        $lhkp = LhkpReport::findOrFail($id);
        $lhkp->delete();

        return redirect()->back()->with('success', 'LHKP berhasil dihapus!');
    }
}