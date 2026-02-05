<?php

namespace App\Http\Controllers\Eco;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortofolioController extends Controller
{
    public function index()
    {
        // Hanya ambil data milik divisi ECO
        $portfolios = Portfolio::where('division', 'eco')->latest()->get();
        return view('eco.portfolios.index', compact('portfolios'));
    }

    public function edit($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        return view('eco.portfolios.edit', compact('portfolio'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|max:2048',
            'completion_date' => 'required|date',
        ]);

        $imageBase64 = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $mime = $file->getMimeType();
            $content = file_get_contents($file->getRealPath());
            $imageBase64 = "data:$mime;base64," . base64_encode($content);
        }

        Portfolio::create([
            'title' => $request->title,
            'division' => 'eco', // Otomatis set divisi ECO
            'category' => $request->category, // Jenis Penjualan
            'client_name' => $request->client_name, // Nama Pelanggan
            'completion_date' => $request->completion_date, // Tanggal Distribusi
            'description' => $request->description, // Keterangan
            'location' => $request->location, // Tujuan Distribusi
            'image_path' => $imageBase64,
        ]);

        return redirect()->route('eco.portfolios.index')->with('success', 'Data kegiatan berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $portfolio = Portfolio::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $request->title,
            'category' => $request->category,
            'client_name' => $request->client_name,
            'completion_date' => $request->completion_date,
            'description' => $request->description,
            'location' => $request->location,
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $mime = $file->getMimeType();
            $content = file_get_contents($file->getRealPath());
            $data['image_path'] = "data:$mime;base64," . base64_encode($content);
        }

        $portfolio->update($data);

        return redirect()->route('eco.portfolios.index')->with('success', 'Data kegiatan diperbarui!');
    }

    public function destroy($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->delete();
        return redirect()->back()->with('success', 'Data dihapus.');
    }
}