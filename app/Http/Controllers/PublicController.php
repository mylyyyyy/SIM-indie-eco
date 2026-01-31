<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    // Halaman list semua berita (Pagination)
    public function index()
    {
        $berita = DB::table('berita')
                    ->where('status', 'publish')
                    ->orderBy('tanggal_publish', 'desc')
                    ->paginate(9); // 9 item per halaman

        return view('components.berita', compact('berita'));
    }

    // Halaman detail satu berita
    public function show($id)
    {
        $item = DB::table('berita')->where('id_berita', $id)->first();

        if (!$item) {
            abort(404);
        }

        // Ambil berita lain untuk sidebar (opsional)
        $terbaru = DB::table('berita')
                    ->where('status', 'publish')
                    ->where('id_berita', '!=', $id)
                    ->orderBy('tanggal_publish', 'desc')
                    ->limit(5)
                    ->get();

        return view('components.berita-detail', compact('item', 'terbaru'));
    }
}