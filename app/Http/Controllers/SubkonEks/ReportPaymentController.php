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
        $payments = ProjectPayment::where('user_id', Auth::id()) 
                    ->with('project') 
                    ->latest() 
                    ->get();
                    
        return view('subkon-eks.report-payments.index', compact('payments'));
    }

    // Menampilkan form tambah
    public function create()
    {
        $projects = Project::all(); 
        return view('subkon-eks.report-payments.create', compact('projects'));
    }

    // Menyimpan data ke database
    public function store(Request $request)
    {
        // 1. Validasi Input (Ubah validasi document menjadi PDF)
        $request->validate([
            'project_id'   => 'required|exists:projects,id',
            'payment_date' => 'required|date',
            'amount'       => 'required|numeric|min:0', 
            'description'  => 'required|string',
            'document'     => 'required|mimes:pdf|max:5120', // Hanya menerima PDF
        ]);

        // 2. Proses Konversi PDF ke Base64
        $docBase64 = null;
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            // Mengubah file jadi string base64
            $docBase64 = "data:" . $file->getMimeType() . ";base64," . base64_encode(file_get_contents($file));
        }

        // 3. Simpan ke Database
        ProjectPayment::create([
            'user_id'         => Auth::id(),
            'project_id'      => $request->project_id,
            'amount'          => $request->amount,
            'payment_date'    => $request->payment_date,
            'notes'           => $request->description,
            'claim_document'  => $docBase64, // Menyimpan Base64 PDF
            'status'          => 'pending',
            'finance_user_id' => null,
            'payment_proof'   => null,
            'payment_method'  => null,
        ]);

        return redirect()->route('subkon-eks.report-payments.index')
            ->with('success', 'Klaim berhasil diajukan! Menunggu verifikasi Keuangan.');
    }
}