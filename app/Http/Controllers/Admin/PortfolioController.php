<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::latest()->get();
        return view('admin.portfolios.index', compact('portfolios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'image' => 'nullable|image|max:2048', // Max 2MB
            'completion_date' => 'required|date',
        ]);

        $imageBase64 = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $mime = $file->getMimeType();
            $content = file_get_contents($file->getRealPath());
            $base64 = base64_encode($content);
            $imageBase64 = "data:$mime;base64,$base64";
        }

        Portfolio::create([
            'title' => $request->title,
            'category' => $request->category,
            'client_name' => $request->client_name,
            'completion_date' => $request->completion_date,
            'description' => $request->description,
            'image_path' => $imageBase64,
        ]);

        return redirect()->back()->with('success', 'Portofolio berhasil ditambahkan!');
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
        ];

        // Update gambar hanya jika ada upload baru
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $mime = $file->getMimeType();
            $content = file_get_contents($file->getRealPath());
            $base64 = base64_encode($content);
            $data['image_path'] = "data:$mime;base64,$base64";
        }

        $portfolio->update($data);

        return redirect()->back()->with('success', 'Portofolio berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->delete();
        return redirect()->back()->with('success', 'Portofolio dihapus.');
    }
}