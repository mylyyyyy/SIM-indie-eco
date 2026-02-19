<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Models\ProjectPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan ini untuk Transaction

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Klaim yang MENUNGGU Verifikasi (Status: Pending)
        $pendingPayments = ProjectPayment::where('status', 'pending')
                            ->with(['project', 'requestor']) 
                            ->orderBy('created_at', 'asc') 
                            ->get();

        // 2. Riwayat Transaksi (Paid / Rejected)
        $historyPayments = ProjectPayment::whereIn('status', ['paid', 'rejected'])
                            ->with(['project', 'requestor', 'finance'])
                            ->latest()
                            ->get();

        // 3. Statistik Ringkas
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

        // Validasi Umum
        $request->validate([
            'action' => 'required|in:approve,reject',
            // Jika Reject -> Note Wajib
            'admin_note' => 'required_if:action,reject|string|nullable',
            // Jika Approve -> Amount, Method, Proof Wajib
            'amount' => 'required_if:action,approve|numeric|min:0',
            'payment_method' => 'required_if:action,approve|string|nullable',
            'payment_proof' => 'required_if:action,approve|image|max:5120|nullable',
        ]);

        DB::beginTransaction(); // Mulai Transaksi Database

        
        try {
            // A. JIKA DITOLAK
            if ($request->action == 'reject') {
                $payment->update([
                    'status' => 'rejected',
                    'finance_user_id' => Auth::id(),
                    'notes' => $payment->notes . ' | [DITOLAK]: ' . $request->admin_note 
                ]);

                DB::commit();
                return back()->with('success', 'Pengajuan pembayaran berhasil DITOLAK.');
            } 
            
            // B. JIKA DISETUJUI (BAYAR)
            else {
                // Proses Upload Bukti Transfer
                $proofBase64 = null;
                if ($request->hasFile('payment_proof')) {
                    $file = $request->file('payment_proof');
                    $proofBase64 = "data:" . $file->getMimeType() . ";base64," . base64_encode(file_get_contents($file));
                }

                // Update Data (Dari Pending -> Paid)
                $payment->update([
                    'status' => 'paid',
                    'finance_user_id' => Auth::id(), // Siapa yang transfer
                    'amount' => $request->amount, // Nominal final
                    'payment_method' => $request->payment_method,
                    'payment_proof' => $proofBase64,
                    'invoice_number' => $request->invoice_number,
                    // Tambahkan info approval ke notes jika ada
                    'notes' => $request->invoice_number ? $payment->notes . ' | Ref: ' . $request->invoice_number : $payment->notes
                ]);

                DB::commit();
                return back()->with('success', 'Pembayaran berhasil DIKONFIRMASI.');
            }

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika ada error
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}