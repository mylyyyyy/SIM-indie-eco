<?php

namespace App\Http\Controllers\SubkonEks;

use App\Http\Controllers\Controller;
use App\Models\LhReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class LhController extends Controller
{
    public function index()
    {
        // Pelaksana lapangan hanya bisa melihat laporan yang dia buat sendiri
        $lhs = LhReport::where('user_id', Auth::id())->latest()->get();
        return view('subkon-eks.lh.index', compact('lhs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kegiatan' => 'required|array',
            'kegiatan.*' => 'required|string',
            'dokumentasi' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' 
        ]);

        $data = [
            'user_id' => Auth::id(),
            'tanggal' => $request->tanggal,
            'rincian_kegiatan' => json_encode(array_values(array_filter($request->kegiatan))), 
        ];

        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $filename = time() . '_lh_proyek.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/lh'), $filename);
            $data['dokumentasi'] = $filename;
        }

        LhReport::create($data);
        return redirect()->back()->with('success', 'Laporan Harian Proyek berhasil dikirim!');
    }

    public function destroy($id)
    {
        $lh = LhReport::where('user_id', Auth::id())->findOrFail($id);

        if ($lh->dokumentasi && File::exists(public_path('uploads/lh/' . $lh->dokumentasi))) {
            File::delete(public_path('uploads/lh/' . $lh->dokumentasi));
        }

        $lh->delete();
        return redirect()->back()->with('success', 'Laporan Proyek dihapus!');
    }
}