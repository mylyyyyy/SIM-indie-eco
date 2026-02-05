<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\ProjectPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Klaim yang MENUNGGU Verifikasi (Status: Pending)
        $pendingPayments = ProjectPayment::where('status', 'pending')
                            ->with(['project', 'requestor']) // Load data Proyek & Subkon
                            ->orderBy('created_at', 'asc') // Urutkan dari yang terlama
                            ->get();

        // 2. Riwayat Transaksi (Paid / Rejected)
        $historyPayments = ProjectPayment::whereIn('status', ['paid', 'rejected'])
                            ->with(['project', 'requestor', 'finance'])
                            ->latest()
                            ->get();

        // 3. Statistik
        $stats = [
            'pending' => $pendingPayments->count(),
            'paid_today' => ProjectPayment::where('status', 'paid')
                                ->whereDate('updated_at', now())
                                ->sum('amount'),
            'total_disbursed' => ProjectPayment::where('status', 'paid')
                                ->sum('amount'),
        ];

        return view('keuangan.dashboard', compact('pendingPayments', 'historyPayments', 'stats'));
    }

    // FUNGSI PROSES VERIFIKASI (UPDATE DATA)
    public function verifyReport(Request $request, $id)
    {
        $payment = ProjectPayment::findOrFail($id);

        // A. JIKA DITOLAK
        if ($request->action == 'reject') {
            $request->validate(['admin_note' => 'required|string']);

            $payment->update([
                'status' => 'rejected',
                'finance_user_id' => Auth::id(),
                'notes' => $payment->notes . ' | REVISI: ' . $request->admin_note 
            ]);

            return back()->with('success', 'Pengajuan pembayaran ditolak.');
        } 
        
        // B. JIKA DISETUJUI (BAYAR)
        else {
            $request->validate([
                'amount' => 'required|numeric|min:0',
                'payment_method' => 'required|string',
                'payment_proof' => 'required|image|max:2048', // Bukti Transfer Wajib
                'invoice_number' => 'nullable|string'
            ]);

            // Proses Upload Bukti Transfer
            $proofBase64 = null;
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $proofBase64 = "data:" . $file->getMimeType() . ";base64," . base64_encode(file_get_contents($file));
            }

            // Update Data yang sudah ada (Dari Pending -> Paid)
            $payment->update([
                'status' => 'paid',
                'finance_user_id' => Auth::id(), // Siapa yang transfer
                'amount' => $request->amount, // Nominal final (bisa diedit keuangan)
                'payment_method' => $request->payment_method,
                'payment_proof' => $proofBase64,
                'invoice_number' => $request->invoice_number,
                // Catatan tambahan opsional
                'notes' => $request->admin_note ? $payment->notes . ' | INFO: ' . $request->admin_note : $payment->notes
            ]);

            return back()->with('success', 'Pembayaran berhasil dikonfirmasi & bukti tersimpan.');
        }
    }
}