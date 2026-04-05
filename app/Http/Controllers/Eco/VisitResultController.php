<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\VisitResult;
use App\Models\MasterStok; // Sesuaikan dengan nama model stok gudang/master Anda jika ada
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitResultController extends Controller
{
    /**
     * TAMPILAN DASHBOARD INPUT & RIWAYAT (Bagi Admin / Manager Operasional)
     */
    public function index()
    {
        $cabangEco = Auth::user()->company_name;
        
        $results = VisitResult::whereHas('user', function($query) use ($cabangEco) {
                        $query->where('company_name', $cabangEco);
                    })->latest('tanggal')->get();
                    
        return view('eco.operasional.visit-result.index', compact('results'));
    }

    /**
     * PROSES SIMPAN DATA KUNJUNGAN BARU
     */
    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|string|max:20',
            'tanggal' => 'required|date',
            'nama_toko' => 'required|string|max:255',
            'alamat' => 'required|string',
            'titip_sisa_awal_pack' => 'required|integer|min:0',
            'harga_rp' => 'required|numeric|min:0',
            'laku_pack' => 'required|integer|min:0',
            'sisa_pack' => 'required|integer|min:0',
            'tambah_pack' => 'required|integer|min:0',
            'total_pack' => 'required|integer|min:0',
            'return_pack' => 'required|integer|min:0', 
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); 
        
        // OTOMASI PERHITUNGAN NOMINAL PEMBAYARAN (Harga x Laku)
        $data['nominal_pembayaran'] = $request->harga_rp * $request->laku_pack;
        
        VisitResult::create($data);

        // LOGIKA AUTO STOK RETURN: Jika ada beras kembali, tambahkan ke stok gudang cabang
        if ($request->return_pack > 0) {
            /* PENTING: Buka komentar di bawah ini jika Anda sudah memiliki tabel MasterStok
            
            $master = MasterStok::where('nama_cabang', Auth::user()->company_name)->first();
            if($master) {
                $master->increment('total_stok', $request->return_pack);
            }
            
            */
        }
        
        return redirect()->back()->with('success', 'Hasil kunjungan toko berhasil disimpan!');
    }

    /**
     * PROSES EDIT DATA KUNJUNGAN
     */
    public function update(Request $request, $id)
    {
        $visitResult = VisitResult::findOrFail($id);

        $request->validate([
            'hari' => 'required|string|max:20',
            'tanggal' => 'required|date',
            'nama_toko' => 'required|string|max:255',
            'alamat' => 'required|string',
            'titip_sisa_awal_pack' => 'required|integer|min:0',
            'harga_rp' => 'required|numeric|min:0',
            'laku_pack' => 'required|integer|min:0',
            'sisa_pack' => 'required|integer|min:0',
            'tambah_pack' => 'required|integer|min:0',
            'total_pack' => 'required|integer|min:0',
            'return_pack' => 'required|integer|min:0',
        ]);

        $data = $request->all();
        
        // OTOMASI PERHITUNGAN NOMINAL PEMBAYARAN SAAT DI-EDIT (Harga x Laku)
        $data['nominal_pembayaran'] = $request->harga_rp * $request->laku_pack;

        // LOGIKA KALKULASI SELISIH RETURN STOK
        $selisihReturn = $request->return_pack - $visitResult->return_pack;
        
        if ($selisihReturn != 0) {
            /* PENTING: Buka komentar di bawah ini jika Anda sudah memiliki tabel MasterStok
            
            $master = MasterStok::where('nama_cabang', Auth::user()->company_name)->first();
            if($master) {
                // Jika $selisihReturn positif, stok nambah. Jika negatif, stok berkurang.
                $master->total_stok += $selisihReturn;
                $master->save();
            }
            
            */
        }

        $visitResult->update($data);
        
        return redirect()->back()->with('success', 'Data hasil kunjungan berhasil diperbarui!');
    }

    /**
     * PROSES HAPUS DATA KUNJUNGAN
     */
    public function destroy(VisitResult $visitResult)
    {
        // LOGIKA MENGURANGI STOK GUDANG JIKA DATA KUNJUNGAN DIHAPUS
        if ($visitResult->return_pack > 0) {
            /* PENTING: Buka komentar di bawah ini jika Anda sudah memiliki tabel MasterStok
            
            $master = MasterStok::where('nama_cabang', Auth::user()->company_name)->first();
            if($master) {
                $master->decrement('total_stok', $visitResult->return_pack);
            }
            
            */
        }

        $visitResult->delete();
        return redirect()->back()->with('success', 'Data hasil kunjungan dihapus!');
    }

    /**
     * TAMPILAN HALAMAN UNTUK DIVISI KEUANGAN
     */
    public function indexKeuangan(Request $request)
    {
        $cabangEco = Auth::user()->company_name;
        
        // Filter awal berdasarkan cabang akun yang sedang login
        $query = VisitResult::whereHas('user', function($q) use ($cabangEco) {
                        $q->where('company_name', $cabangEco);
                    })->orderBy('tanggal', 'desc');

        // Jika ada filter rentang tanggal (Start Date & End Date)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $results = $query->get();
        
        return view('eco.operasional.visit-result.keuangan', compact('results'));
    }

    /**
     * FUNGSI DOWNLOAD KE FORMAT EXCEL
     */
    public function exportExcel(Request $request)
    {
        $cabangEco = Auth::user()->company_name;
        
        // Ambil data sesuai cabang
        $query = VisitResult::whereHas('user', function($q) use ($cabangEco) {
                        $q->where('company_name', $cabangEco);
                    })->orderBy('tanggal', 'asc');

        // Jika didownload berdasarkan filter rentang tanggal
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        $results = $query->get();

        // Render file blade excel.blade.php menjadi sebuah file .xls
        return response(view('eco.operasional.visit-result.excel', compact('results')))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="Laporan_Kunjungan_Eco_'.date('Y-m-d').'.xls"');
    }
}