<?php

namespace App\Http\Controllers\ManagerUnit;

use App\Http\Controllers\Controller;
use App\Models\LhkpReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Wajib import Auth

class LhkpController extends Controller
{
    public function index()
    {
        // Ambil nama cabang dari Manager Unit yang sedang login
        $cabangManager = Auth::user()->company_name;

        // FILTER: Tampilkan LHKP yang dikelola oleh user di cabang yang sama
        $lhkps = LhkpReport::whereHas('user', function($query) use ($cabangManager) {
                    $query->where('company_name', $cabangManager);
                })
                ->latest()
                ->get();
                
        return view('manager-unit.lhkp.index', compact('lhkps'));
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

        // Simpan data beserta ID User (Manager) yang menginputkan
        $data = $request->all();
        $data['user_id'] = Auth::id(); 
        
        LhkpReport::create($data);

        return redirect()->back()->with('success', 'LHKP berhasil disimpan!');
    }

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

    public function destroy($id)
    {
        $lhkp = LhkpReport::findOrFail($id);
        $lhkp->delete();

        return redirect()->back()->with('success', 'LHKP berhasil dihapus!');
    }
}