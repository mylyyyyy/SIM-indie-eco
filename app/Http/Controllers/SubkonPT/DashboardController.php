<?php

namespace App\Http\Controllers\SubkonPT;

use App\Http\Controllers\Controller;
use App\Models\ProjectReport;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil yang Pending (Untuk Tab Utama)
        $pendingReports = ProjectReport::where('status', 'pending')
            ->with(['user', 'project'])
            ->latest()
            ->get();

        // 2. Ambil yang Sudah Diproses (Approved/Rejected)
        // Kita limit 50 terakhir agar tidak terlalu berat
        $processedReports = ProjectReport::whereIn('status', ['approved', 'rejected'])
            ->with(['user', 'project'])
            ->latest()
            ->take(50)
            ->get();
        
        return view('subkon-pt.dashboard', compact('pendingReports', 'processedReports'));
    }

  public function updateStatus(Request $request, $id)
    {
        $report = ProjectReport::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_note' => 'nullable|string',
            'rating' => 'nullable|integer|min:0|max:100', // Validasi rating
        ]);

        $report->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'rating' => $request->status == 'approved' ? $request->rating : null, // Rating hanya jika approved
        ]);

        $msg = $request->status == 'approved' ? 'Laporan disetujui & dinilai!' : 'Laporan ditolak!';
        
        return back()->with('success', $msg);
    }
}