<?php

namespace App\Http\Controllers\KepalaKantor;

use App\Http\Controllers\Controller;
use App\Models\LhReport;
use Illuminate\Http\Request;

class LhController extends Controller
{
    public function index()
    {
        // Ambil history laporan milik user ini (atau semua jika kebijakan membolehkan)
        $lhs = LhReport::latest()->limit(10)->get();
        return view('kepala-kantor.dashboard', compact('lhs'));
    }

    public function store(Request $request)
    {
        // Tambahkan validasi mimes untuk memastikan hanya gambar yang bisa lewat
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|array',
            'kegiatan.*' => 'required|string',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Max 2MB
        ], [
            'dokumentasi.image' => 'File lampiran harus berupa gambar.',
            'dokumentasi.mimes' => 'Format gambar harus berupa jpeg, png, jpg, atau gif.',
            'dokumentasi.max' => 'Ukuran gambar maksimal adalah 2MB.'
        ]);

        $data = [
            'tanggal' => $request->tanggal,
            // Encode array kegiatan menjadi JSON agar bisa masuk ke 1 kolom database
            'rincian_kegiatan' => json_encode($request->kegiatan), 
        ];

        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $filename = time() . '_lh.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/lh'), $filename);
            $data['dokumentasi'] = $filename;
        }

        LhReport::create($data);

        return redirect()->back()->with('success', 'Laporan Harian berhasil disubmit!');
    }
}