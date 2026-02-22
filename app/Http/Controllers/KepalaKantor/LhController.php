<?php

namespace App\Http\Controllers\KepalaKantor;

use App\Http\Controllers\Controller;
use App\Models\LhReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Auth; // Wajib import Auth

class LhController extends Controller
{
    public function index()
    {
        // FILTER: Hanya ambil laporan yang dibuat oleh user (Kepala Kantor) ini saja
        $lhs = LhReport::where('user_id', Auth::id())
                ->latest()
                ->limit(20)
                ->get(); 
                
        return view('kepala-kantor.dashboard', compact('lhs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|array',
            'kegiatan.*' => 'required|string',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' 
        ], [
            'dokumentasi.image' => 'File lampiran harus berupa gambar.',
            'dokumentasi.mimes' => 'Format gambar harus berupa jpeg, png, jpg, atau gif.',
            'dokumentasi.max' => 'Ukuran gambar maksimal adalah 2MB.'
        ]);

        $data = [
            'user_id' => Auth::id(), // SIMPAN ID USER YANG LOGIN
            'tanggal' => $request->tanggal,
            'rincian_kegiatan' => json_encode(array_values(array_filter($request->kegiatan))), 
        ];

        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $filename = time() . '_lh.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/lh'), $filename);
            $data['dokumentasi'] = $filename;
        }

        LhReport::create($data);

        return redirect()->back()->with('success', 'Laporan Harian berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        // Pastikan hanya bisa mengedit laporannya sendiri
        $lh = LhReport::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|array',
            'kegiatan.*' => 'required|string',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);

        $data = [
            'tanggal' => $request->tanggal,
            'rincian_kegiatan' => json_encode(array_values(array_filter($request->kegiatan))), 
        ];

        if ($request->hasFile('dokumentasi')) {
            if ($lh->dokumentasi && File::exists(public_path('uploads/lh/' . $lh->dokumentasi))) {
                File::delete(public_path('uploads/lh/' . $lh->dokumentasi));
            }
            $file = $request->file('dokumentasi');
            $filename = time() . '_lh_update.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/lh'), $filename);
            $data['dokumentasi'] = $filename;
        }

        $lh->update($data);

        return redirect()->back()->with('success', 'Laporan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Pastikan hanya bisa menghapus laporannya sendiri
        $lh = LhReport::where('user_id', Auth::id())->findOrFail($id);

        if ($lh->dokumentasi && File::exists(public_path('uploads/lh/' . $lh->dokumentasi))) {
            File::delete(public_path('uploads/lh/' . $lh->dokumentasi));
        }

        $lh->delete();

        return redirect()->back()->with('success', 'Laporan berhasil dihapus!');
    }
}