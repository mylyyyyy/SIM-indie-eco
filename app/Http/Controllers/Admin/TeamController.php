<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    // 1. TAMPILKAN DATA
    public function index()
    {
        // Urutkan berdasarkan kolom 'urutan' dari kecil ke besar
        $teams = Team::orderBy('urutan', 'asc')->get();
        return view('admin.teams.index', compact('teams'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('admin.teams.create');
    }

    // 3. SIMPAN DATA BARU
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
            'urutan' => 'integer'
        ]);

        $data = $request->all();

        // LOGIKA UPLOAD KE BASE64
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            // Ambil konten file dan encode ke base64
            $base64 = base64_encode(file_get_contents($file));
            // Format string agar bisa dibaca browser: data:image/png;base64,....
            $data['photo'] = 'data:' . $file->getMimeType() . ';base64,' . $base64;
        }

        Team::create($data);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Anggota tim berhasil ditambahkan!');
    }

    // 4. FORM EDIT
    public function edit(Team $team)
    {
        return view('admin.teams.edit', compact('team'));
    }

    // 5. UPDATE DATA
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'urutan' => 'integer'
        ]);

        $data = $request->all();

        // Cek apakah user upload foto baru?
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $base64 = base64_encode(file_get_contents($file));
            $data['photo'] = 'data:' . $file->getMimeType() . ';base64,' . $base64;
        } else {
            // Jika tidak upload foto, hapus key 'photo' dari array data
            // agar foto lama di database tidak tertimpa null/kosong
            unset($data['photo']);
        }

        $team->update($data);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Data tim berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('admin.teams.index')
            ->with('success', 'Anggota tim berhasil dihapus.');
    }
}