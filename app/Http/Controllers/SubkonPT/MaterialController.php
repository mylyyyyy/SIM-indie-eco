<?php

namespace App\Http\Controllers\SubkonPt;

use App\Http\Controllers\Controller;
use App\Models\SubkonPtMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Sama seperti sebelumnya: Manager lihat semua, Subkon lihat miliknya saja
        if ($user->role == 'manager_unit_indie' || $user->role == 'monitoring_indie') {
            $materials = SubkonPtMaterial::latest('tanggal')->get();
        } else {
            $materials = SubkonPtMaterial::where('user_id', $user->id)->latest('tanggal')->get();
        }

        return view('subkon-pt.materials.index', compact('materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'nama_material' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'stok_awal' => 'required|numeric|min:0',
            'material_masuk' => 'required|numeric|min:0',
            'material_terpakai' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        
        // Hitung sisa stok otomatis: (Awal + Masuk) - Terpakai
        $data['sisa_stok'] = ($request->stok_awal + $request->material_masuk) - $request->material_terpakai;

        SubkonPtMaterial::create($data);

        return redirect()->back()->with('success', 'Data Material berhasil dicatat!');
    }

    public function destroy($id)
    {
        SubkonPtMaterial::where('user_id', Auth::id())->findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data Material berhasil dihapus!');
    }
}