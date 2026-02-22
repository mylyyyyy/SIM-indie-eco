<?php

namespace App\Http\Controllers\ManagerUnit;

use App\Http\Controllers\Controller;
use App\Models\LhkpReport;
use App\Models\LhReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth; // Wajib import Auth

class DownloadController extends Controller
{
    public function dashboard()
    {
        // Ambil nama cabang dari Manager Unit yang sedang login
        $cabangManager = Auth::user()->company_name;

        // 1. FILTER LHKP berdasarkan cabang
        $lhkps = LhkpReport::whereHas('user', function($query) use ($cabangManager) {
                    $query->where('company_name', $cabangManager);
                })
                ->latest()
                ->get();

        // 2. FILTER LH (Laporan Harian Kepala Kantor) berdasarkan cabang yang sama
        $lhs = LhReport::whereHas('user', function($query) use ($cabangManager) {
                    $query->where('company_name', $cabangManager);
                })
                ->latest()
                ->get();

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
        $lh = LhReport::findOrFail($id);
        
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