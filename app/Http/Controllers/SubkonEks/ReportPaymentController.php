<?php

namespace App\Http\Controllers\SubkonEks;

use App\Http\Controllers\Controller;
use App\Models\ProjectPayment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportPaymentController extends Controller
{
    // Menampilkan daftar klaim
    public function index()
    {
        $payments = ProjectPayment::where('user_id', Auth::id()) // Hanya punya user yang login
                    ->with('project') // Eager load relasi proyek
                    ->latest() // Urutkan dari yang terbaru
                    ->get();
                    
        return view('subkon-eks.report-payments.index', compact('payments'));
    }

    // Menampilkan form tambah
    public function create()
    {
        $projects = Project::all(); // Ambil semua proyek untuk dropdown
        return view('subkon-eks.report-payments.create', compact('projects'));
    }

    // Menyimpan data ke database
    public function store(Request $request)
    {
        // 1. Validasi Input (Nama harus sama dengan 'name' di form HTML)
        $request->validate([
            'project_id'   => 'required|exists:projects,id',
            'payment_date' => 'required|date',
            'amount'       => 'required|numeric|min:0', 
            'description'  => 'required|string',
            'document'     => 'required|image|max:5120', // Max 5MB
        ]);

        // 2. Proses Konversi Gambar ke Base64
        $docBase64 = null;
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            // Mengubah file jadi string base64 agar bisa disimpan di kolom tipe LONGTEXT
            $docBase64 = "data:" . $file->getMimeType() . ";base64," . base64_encode(file_get_contents($file));
        }

        // 3. Simpan ke Database project_payments
        ProjectPayment::create([
            'user_id'         => Auth::id(),
            'project_id'      => $request->project_id,
            'amount'          => $request->amount,
            'payment_date'    => $request->payment_date,
            'notes'           => $request->description, // Deskripsi masuk ke kolom notes
            'claim_document'  => $docBase64,            // Gambar bukti tagihan
            'status'          => 'pending',             // Default status
            
            // Kolom ini biarkan null, nanti diisi orang keuangan
            'finance_user_id' => null,
            'payment_proof'   => null,
            'payment_method'  => null,
        ]);

        return redirect()->route('subkon-eks.report-payments.index')
            ->with('success', 'Klaim berhasil diajukan! Menunggu verifikasi Keuangan.');
    }
}