<?php

namespace App\Http\Controllers\SubkonEks;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProjectReport;
use App\Models\Project; 
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Menampilkan Halaman Form Input Laporan
     */
    public function create()
    {
        // Ambil data proyek yang statusnya 'berjalan' untuk dropdown
        $projects = Project::where('status', 'berjalan')->get();

        // Tampilkan view form
        return view('subkon-eks.create', compact('projects'));
    }

    /**
     * Mencetak Laporan ke PDF
     */
    public function print($id)
    {
       $report = ProjectReport::with(['project', 'user'])->findOrFail($id);

    // Validasi Akses (Tetap pakai ini)
    if ($report->user_id !== Auth::id() && $report->status !== 'approved') {
        abort(403);
    }

    // GANTI LOGIKA INI:
    // Alih-alih generate PDF di server, kita return View biasa
    // File view-nya tetap sama: 'subkon-eks.print'
    return view('subkon-eks.print', compact('report'));
    }

    /**
     * Menyimpan Data Laporan ke Database
     */
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'report_date' => 'required|date',
            'work_description' => 'required|string',
            'progress_percentage' => 'required|numeric|min:0|max:100',
            'documentation' => 'nullable|image|max:2048', 
        ]);

        $imageBase64 = null;

        if ($request->hasFile('documentation')) {
            $file = $request->file('documentation');
            $mime = $file->getMimeType();
            $content = file_get_contents($file->getRealPath());
            $base64 = base64_encode($content);
            $imageBase64 = "data:$mime;base64,$base64";
        }

        ProjectReport::create([
            'project_id' => $request->project_id,
            'user_id' => Auth::id(),
            'report_date' => $request->report_date,
            'work_description' => $request->work_description,
            'progress_percentage' => $request->progress_percentage,
            'documentation_path' => $imageBase64,
            'status' => 'pending',
        ]);

        return redirect()->route('subkon-eks.dashboard')->with('success', 'Laporan berhasil dikirim dan menunggu verifikasi.');
    }

    /**
     * MENGHAPUS LAPORAN (FUNGSI YANG HILANG SEBELUMNYA)
     */
    public function destroy($id)
    {
        // 1. Cari Laporan berdasarkan ID dan Pastikan milik User yang login
        $report = ProjectReport::where('id', $id)
                               ->where('user_id', Auth::id())
                               ->firstOrFail();

        // 2. Opsional: Cegah hapus jika status sudah 'approved'
        if ($report->status === 'approved') {
            return back()->with('error', 'Laporan yang sudah disetujui tidak dapat dihapus.');
        }

        // 3. Hapus Data
        $report->delete();

        // 4. Kembali dengan pesan sukses
        return back()->with('success', 'Laporan berhasil dihapus.');
    }
}