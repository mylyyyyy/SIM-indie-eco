<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Team; // <--- WAJIB TAMBAHKAN INI (Model Team)

class PublicController extends Controller
{
    // ==========================================
    // 1. HALAMAN UTAMA / LANDING PAGE (welcome)
    // ==========================================
    public function welcome()
    {
        // Ambil data Team dari database
        $teams = Team::orderBy('urutan', 'asc')->get();

        // Ambil 3 Berita Terbaru untuk ditampilkan di Home
        $berita = DB::table('berita')
                    ->where('status', 'publish')
                    ->orderBy('tanggal_publish', 'desc')
                    ->limit(3)
                    ->get();

        // Kirim ke view 'welcome.blade.php'
        return view('welcome', compact('teams', 'berita'));
    }

    // ==========================================
    // 2. HALAMAN LIST SEMUA BERITA
    // ==========================================
    public function index()
    {
        $berita = DB::table('berita')
                    ->where('status', 'publish')
                    ->orderBy('tanggal_publish', 'desc')
                    ->paginate(9);

        return view('components.berita', compact('berita'));
    }

    // ==========================================
    // 3. HALAMAN DETAIL BERITA
    // ==========================================
    public function show($id)
    {
        $item = DB::table('berita')->where('id_berita', $id)->first();

        if (!$item) {
            abort(404);
        }

        $terbaru = DB::table('berita')
                    ->where('status', 'publish')
                    ->where('id_berita', '!=', $id)
                    ->orderBy('tanggal_publish', 'desc')
                    ->limit(5)
                    ->get();

        return view('components.berita-detail', compact('item', 'terbaru'));
    }
}