<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        // Link Marketplace (Sesuai Request)
        $links = [
            'shopee' => 'https://shopee.co.id/bkmgalerylabang?uls_trackid=54j9acfr00lv&utm_content=37z897FcPCBJnyKidXrtN5XeoCSB',
            'tokopedia' => 'https://www.tokopedia.com/galerylapasjombang'
        ];

        // UPDATE DATA PRODUK DENGAN GAMBAR YANG LEBIH SESUAI
        $products = [
            [
                'name' => 'Miniatur Kapal Pinisi',
                'category' => 'Kerajinan Kayu',
                'price' => 'Rp 350.000',
                // Gambar Miniatur Kapal
                'image' => 'https://images.unsplash.com/photo-1542042952-6703567d023e?q=80&w=600&auto=format&fit=crop',
                'description' => 'Miniatur detail dibuat dari limbah kayu berkualitas oleh tangan terampil WBP.',
            ],
            [
                'name' => 'Kotak Tisu Ukir',
                'category' => 'Cukil Kayu',
                'price' => 'Rp 75.000',
                // Gambar Kotak Kayu
                'image' => 'https://images.unsplash.com/photo-1610701596007-11502861dcfa?q=80&w=600&auto=format&fit=crop',
                'description' => 'Kotak tisu dengan ukiran motif estetik, cocok untuk ruang tamu.',
            ],
            [
                'name' => 'Hiasan Dinding Tembaga',
                'category' => 'Seni Logam',
                'price' => 'Rp 500.000',
                // Gambar Seni Logam/Abstrak
                'image' => 'https://images.unsplash.com/photo-1578320339912-78d2b2c27162?q=80&w=600&auto=format&fit=crop',
                'description' => 'Hiasan dinding timbul berbahan tembaga dengan bingkai minimalis.',
            ],
            [
                'name' => 'Asbak Resin Unik',
                'category' => 'Kerajinan Resin',
                'price' => 'Rp 45.000',
                // Gambar Kerajinan Tangan/Bowl
                'image' => 'https://images.unsplash.com/photo-1616400619175-5beda3a17896?q=80&w=600&auto=format&fit=crop',
                'description' => 'Asbak dengan desain estetik perpaduan kayu dan resin bening.',
            ],
            [
                'name' => 'Tas Rajut Nilon',
                'category' => 'Rajutan',
                'price' => 'Rp 120.000',
                // Gambar Tas Rajut
                'image' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?q=80&w=600&auto=format&fit=crop',
                'description' => 'Tas selempang wanita bahan nilon, kuat dan modis untuk sehari-hari.',
            ],
            [
                'name' => 'Lukisan Bakar',
                'category' => 'Seni Lukis',
                'price' => 'Rp 200.000',
                // Gambar Lukisan
                'image' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=600&auto=format&fit=crop',
                'description' => 'Lukisan wajah atau pemandangan dengan teknik pembakaran di atas kayu.',
            ],
        ];

        return view('guest.gallery.index', compact('products', 'links'));
    }
}
