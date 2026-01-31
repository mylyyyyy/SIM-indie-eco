<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\Pendaftar;
use Illuminate\Http\Request;

class PendaftarController extends Controller
{
    public function index()
    {
        $lowongans = Lowongan::with('departemen')->where('quota', '>', 0)->get();
        return view('guest.index', compact('lowongans'));
    }

    public function create($id)
    {
        $lowongan = Lowongan::with('departemen')->findOrFail($id);
        return view('guest.apply', compact('lowongan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'gender' => 'required',
            'dob' => 'required|date',
            'address' => 'required',
            'no_telp' => 'required',
            'university' => 'required',
            'major' => 'required',
            'ipk' => 'required|numeric',
            'path_cv' => 'required|mimes:pdf,jpg,png|max:2048', 
        ]);

        $file = $request->file('path_cv');
        
        $file_content = file_get_contents($file->getRealPath());
        $base64 = base64_encode($file_content);
        $mime_type = $file->getMimeType();
        
        $base64_data = "data:{$mime_type};base64,{$base64}";

        Pendaftar::create([
            'id_lowongan' => $request->id_lowongan,
            'name' => $request->name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'address' => $request->address,
            'no_telp' => $request->no_telp,
            'university' => $request->university,
            'major' => $request->major,
            'ipk' => $request->ipk,
            'status' => 'P',
            'path_cv' => $base64_data, 
        ]);

        return redirect()->route('home')->with('success', 'Lamaran berhasil dikirim!');
    }
}