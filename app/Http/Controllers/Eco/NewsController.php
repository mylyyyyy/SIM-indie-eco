<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    // Menampilkan Daftar Berita
    public function index()
    {
        $berita = DB::table('berita')->orderBy('tanggal_publish', 'desc')->get();
        return view('eco.news.index', compact('berita'));
    }

    // Menampilkan Form Tambah
    public function create()
    {
        return view('eco.news.create');
    }

    // Menyimpan Berita Baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
            'gambar' => 'nullable|image|max:2048', // Max 2MB
            'status' => 'required|in:draft,publish'
        ]);

        $imageBase64 = null;
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $imageBase64 = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file));
        }

        DB::table('berita')->insert([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'gambar' => $imageBase64,
            'penulis' => Auth::user()->name, // Otomatis ambil nama 
            'tanggal_publish' => now(),
            'status' => $request->status
        ]);

        return redirect()->route('eco.news.index')->with('success', 'Berita berhasil diterbitkan!');
    }

    // Menampilkan Form Edit
    public function edit($id)
    {
        $berita = DB::table('berita')->where('id_berita', $id)->first();
        return view('eco.news.edit', compact('berita'));
    }

    // Update Berita
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
            'status' => 'required|in:draft,publish'
        ]);

        $data = [
            'judul' => $request->judul,
            'isi' => $request->isi,
            'status' => $request->status,
            'tanggal_publish' => now() // Update tanggal saat diedit (opsional)
        ];

        // Hanya update gambar jika ada upload baru
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $data['gambar'] = 'data:' . $file->getMimeType() . ';base64,' . base64_encode(file_get_contents($file));
        }

        DB::table('berita')->where('id_berita', $id)->update($data);

        return redirect()->route('eco.news.index')->with('success', 'Berita berhasil diperbarui!');
    }

    // Hapus Berita
    public function destroy($id)
    {
        DB::table('berita')->where('id_berita', $id)->delete();
        return redirect()->route('eco.news.index')->with('success', 'Berita berhasil dihapus!');
    }
}