<?php

namespace App\Http\Controllers\ManagerUnit;

use App\Http\Controllers\Controller;
use App\Models\LhkpReport;
use App\Models\LhReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DownloadController extends Controller
{
    public function dashboard()
    {
        // Ambil semua data untuk ditampilkan di tabel
        $lhkps = LhkpReport::latest()->get();
        $lhs = LhReport::latest()->get();

        return view('manager-unit.dashboard', compact('lhkps', 'lhs'));
    }

    // Fungsi Download PDF LHKP
    public function downloadLhkp($id)
    {
        $lhkp = LhkpReport::findOrFail($id);
        // Pastikan Anda sudah membuat file view PDF-nya di langkah sebelumnya
        $pdf = Pdf::loadView('manager-unit.pdf.lhkp', compact('lhkp'))->setPaper('a4', 'portrait');
        return $pdf->download('LHKP_'.$lhkp->nama_pegawai.'.pdf');
    }

    // Fungsi Download PDF LH
    public function downloadLh($id)
    {
        $lh = LhReport::findOrFail($id);
        
        // Cek apakah data masih berupa string JSON, atau sudah jadi array
        $lh->kegiatan_list = is_string($lh->rincian_kegiatan) 
                                ? json_decode($lh->rincian_kegiatan, true) 
                                : $lh->rincian_kegiatan; 
        
        // Pastikan formatnya selalu array untuk menghindari error di PDF
        if (!is_array($lh->kegiatan_list)) {
            $lh->kegiatan_list = [];
        }
        
        $pdf = Pdf::loadView('manager-unit.pdf.lh', compact('lh'))->setPaper('a4', 'portrait');
        return $pdf->download('LH_Kepala_Kantor_'.$lh->tanggal.'.pdf');
    }
}