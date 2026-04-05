<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\EcoIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EcoIncomeController extends Controller
{
    public function index()
    {
        $cabangEco = Auth::user()->company_name;
        
        // Hanya tampilkan data dari cabang admin yang login
        $incomes = EcoIncome::where('nama_cabang', $cabangEco)->latest('tanggal')->get();
                    
        return view('eco.operasional.income.index', compact('incomes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|string',
            'tanggal' => 'required|date',
            'nama_toko' => 'required|string|max:255',
            'jumlah_plastik_2_5kg' => 'required|integer|min:0',
            'jumlah_plastik_5kg' => 'required|integer|min:0',
            'harga_jual_per_plastik' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        
        // NAMA CABANG OTOMATIS DARI LOGIN
        $data['nama_cabang'] = Auth::user()->company_name ?? 'Pusat';

        // TOTAL HARGA OTOMATIS (Jumlah Plastik * Harga)
        $totalPlastik = $request->jumlah_plastik_2_5kg + $request->jumlah_plastik_5kg;
        $data['total_harga_jual'] = $totalPlastik * $request->harga_jual_per_plastik;

        EcoIncome::create($data);

        return redirect()->back()->with('success', 'Data Pemasukan berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $income = EcoIncome::findOrFail($id);

        $request->validate([
            'hari' => 'required|string',
            'tanggal' => 'required|date',
            'nama_toko' => 'required|string|max:255',
            'jumlah_plastik_2_5kg' => 'required|integer|min:0',
            'jumlah_plastik_5kg' => 'required|integer|min:0',
            'harga_jual_per_plastik' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        
        $totalPlastik = $request->jumlah_plastik_2_5kg + $request->jumlah_plastik_5kg;
        $data['total_harga_jual'] = $totalPlastik * $request->harga_jual_per_plastik;

        $income->update($data);

        return redirect()->back()->with('success', 'Data Pemasukan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        EcoIncome::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}