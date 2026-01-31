<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LowonganController extends Controller
{

    public function index()
    {
       
        $lowongans = Lowongan::with('departemen')->get();
        return view('admin.lowongan.index', compact('lowongans'));
    }

    public function create()
    {
        $departemens = Departemen::all();
        return view('admin.lowongan.create', compact('departemens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'posisi' => 'required',
            'dept_id' => 'required',
            'quota' => 'required|numeric',
            'deskripsi' => 'required',
        ]);

        Lowongan::create([
            'dept_id' => $request->dept_id,
            'posisi' => $request->posisi,
            'quota' => $request->quota,
            'deskripsi' => $request->deskripsi,
            'user_create' => Auth::user()->name, 
        ]);

        return redirect()->route('lowongan.index')->with('success', 'Lowongan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $lowongan = Lowongan::findOrFail($id);
        $departemens = Departemen::all();
        return view('admin.lowongan.edit', compact('lowongan', 'departemens'));
    }

    public function update(Request $request, $id)
    {
        $lowongan = Lowongan::findOrFail($id);
        $lowongan->update([
            'dept_id' => $request->dept_id,
            'posisi' => $request->posisi,
            'quota' => $request->quota,
            'deskripsi' => $request->deskripsi,
            'user_update' => Auth::user()->name,
        ]);

        return redirect()->route('lowongan.index')->with('success', 'Lowongan berhasil diupdate');
    }

    public function destroy($id)
    {
        Lowongan::destroy($id);
        return redirect()->route('lowongan.index')->with('success', 'Lowongan dihapus');
    }
}