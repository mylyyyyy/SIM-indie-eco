<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\ProjectReport;
use App\Models\ProjectPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Laporan yang MENUNGGU Verifikasi (Pending)
        // Kita load relasi 'project' dan 'user' (subkon)
        $pendingReports = ProjectReport::where('status', 'pending')
                            ->with(['project', 'user'])
                            ->orderBy('report_date', 'asc')
                            ->get();

        // 2. Riwayat yang SUDAH Diproses (Approved/Rejected)
        // Kita load relasi payment juga untuk melihat nominal yang sudah dibayar
        $historyReports = ProjectReport::whereIn('status', ['approved', 'rejected'])
                            ->with(['project', 'user', 'payment'])
                            ->latest('updated_at')
                            ->get();

        // 3. Statistik Sederhana
        $stats = [
            'pending' => $pendingReports->count(),
            'paid_today' => ProjectPayment::whereDate('created_at', now())->sum('amount'),
            'total_disbursed' => ProjectPayment::sum('amount'), // Total uang keluar
        ];

        return view('keuangan.dashboard', compact('pendingReports', 'historyReports', 'stats'));
    }

    /**
     * Proses Verifikasi (Setuju / Tolak)
     */
    public function verifyReport(Request $request, $id)
    {
        $report = ProjectReport::findOrFail($id);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_note' => 'nullable|string',
            // Validasi jika disetujui, nominal & metode wajib diisi
            'amount' => 'required_if:action,approve|numeric|min:0',
            'payment_method' => 'required_if:action,approve|in:transfer,cash,cheque',
            'payment_proof' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction(); // Gunakan transaksi database agar aman

        try {
            if ($request->action == 'reject') {
                // --- JIKA DITOLAK ---
                $report->update([
                    'status' => 'rejected',
                    'admin_note' => $request->admin_note ?? 'Laporan ditolak, mohon revisi.',
                ]);
                
                $message = 'Laporan ditolak. Notifikasi dikirim ke Subkon.';

            } else {
                // --- JIKA DISETUJUI ---
                
                // 1. Update Status Laporan
                $report->update([
                    'status' => 'approved',
                    'admin_note' => $request->admin_note ?? 'Pembayaran disetujui.',
                ]);

                // 2. Upload Bukti Transfer (Jika ada)
                $proofBase64 = null;
                if ($request->hasFile('payment_proof')) {
                    $file = $request->file('payment_proof');
                    $mime = $file->getMimeType();
                    $content = file_get_contents($file->getRealPath());
                    $proofBase64 = "data:$mime;base64," . base64_encode($content);
                }

                // 3. Catat ke Tabel Pembayaran (ProjectPayment)
                ProjectPayment::create([
                    'report_id' => $report->id,
                    'finance_user_id' => Auth::id(),
                    'amount' => $request->amount,
                    'payment_method' => $request->payment_method,
                    'payment_date' => now(),
                    'status' => 'paid',
                    'payment_proof' => $proofBase64,
                    'notes' => $request->invoice_number ? "Invoice: " . $request->invoice_number : null,
                ]);

                $message = 'Pembayaran berhasil dicatat & Laporan disetujui!';
            }

            DB::commit();
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}