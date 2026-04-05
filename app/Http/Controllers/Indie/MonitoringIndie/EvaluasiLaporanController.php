<?php

namespace App\Http\Controllers\Indie\MonitoringIndie;

use App\Http\Controllers\Controller;
use App\Models\DataOperasional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluasiLaporanController extends Controller
{
    // ==========================================
    // 1. DASHBOARD MONITORING
    // ==========================================
    public function dashboard()
    {
        // Hitung total laporan yang masih butuh proses persetujuan (Submit/Revisi)
        $pending_count = DataOperasional::whereIn('status', ['Submit', 'Revisi'])->count();
        
        // Ambil 5 aktivitas laporan terbaru dari semua cabang
        $recent_activities = DataOperasional::with('user')
                                ->latest()
                                ->limit(5)
                                ->get();

        return view('indie.monitoring.dashboard', compact('pending_count', 'recent_activities'));
    }

    // ==========================================
    // 2. HALAMAN DAFTAR LAPORAN (APPROVAL)
    // ==========================================
    public function index(Request $request)
    {
        $query = DataOperasional::with('user')->latest();

        // Fitur Filter Cabang (Opsional)
        if ($request->filled('cabang')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('company_name', $request->cabang);
            });
        }

        // Fitur Filter Modul
        if ($request->filled('modul')) {
            $query->where('modul_laporan', $request->modul);
        }

        // Fitur Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $laporans = $query->paginate(15); // Pakai pagination agar tidak berat

        return view('indie.monitoring.evaluasi.index', compact('laporans'));
    }

    // ==========================================
    // 3. PROSES UPDATE STATUS (APPROVE / REVISI)
    // ==========================================
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Approved,Revisi',
            'catatan_evaluator' => 'nullable|string'
        ]);

        $laporan = DataOperasional::findOrFail($id);
        $laporan->status = $request->status;
        $laporan->catatan_evaluator = $request->catatan_evaluator;
        $laporan->save();

        $msg = $request->status == 'Approved' ? 'Laporan berhasil disetujui.' : 'Laporan dikembalikan untuk direvisi.';
        return redirect()->back()->with('success', $msg);
    }
}