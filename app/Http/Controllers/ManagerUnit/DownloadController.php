<?php

namespace App\Http\Controllers\ManagerUnit;

use App\Http\Controllers\Controller;
use App\Models\LhkpReport;
use App\Models\LhReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth; 

class DownloadController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role === 'manager_wilayah') {
            $lhkps = LhkpReport::whereHas('user', function($query) use ($user) {
                        $query->where('wilayah', $user->wilayah);
                    })->latest()->get();

            // WAJIB ditambahkan with('fotos') agar foto Base64-nya ikut terbawa
            $lhs = LhReport::with('fotos')
                    ->whereHas('user', function($query) use ($user) {
                        $query->where('wilayah', $user->wilayah);
                    })->latest()->get();
        } else {
            $lhkps = LhkpReport::whereHas('user', function($query) use ($user) {
                        $query->where('company_name', $user->company_name);
                    })->latest()->get();

            // WAJIB ditambahkan with('fotos') agar foto Base64-nya ikut terbawa
            $lhs = LhReport::with('fotos')
                    ->whereHas('user', function($query) use ($user) {
                        $query->where('company_name', $user->company_name);
                    })->latest()->get();
        }

        return view('manager-unit.dashboard', compact('lhkps', 'lhs'));
    }

    // Fungsi Download PDF LHKP
    public function downloadLhkp($id)
    {
        $lhkp = LhkpReport::findOrFail($id);
        $pdf = Pdf::loadView('manager-unit.pdf.lhkp', compact('lhkp'))->setPaper('a4', 'portrait');
        return $pdf->download('LHKP_'.$lhkp->nama_pegawai.'.pdf');
    }

    // Fungsi Download PDF LH
    public function downloadLh($id)
    {
        // Pastikan load relasi foto untuk keperluan PDF (jika Anda ingin menampilkan foto di dalam PDF)
        $lh = LhReport::with('fotos')->findOrFail($id);
        
        $lh->kegiatan_list = is_string($lh->rincian_kegiatan) 
                                ? json_decode($lh->rincian_kegiatan, true) 
                                : $lh->rincian_kegiatan; 
        
        if (!is_array($lh->kegiatan_list)) {
            $lh->kegiatan_list = [];
        }
        
        $pdf = Pdf::loadView('manager-unit.pdf.lh', compact('lh'))->setPaper('a4', 'portrait');
        return $pdf->download('LH_Kepala_Kantor_'.$lh->tanggal.'.pdf');
    }
}