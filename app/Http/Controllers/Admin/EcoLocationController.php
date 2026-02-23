<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EcoLocation;
use Illuminate\Http\Request;

class EcoLocationController extends Controller
{
    public function index()
    {
        $locations = EcoLocation::latest()->get();
        return view('admin.locations.index', compact('locations'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // PERBAIKAN: Tambahkan 'indie' ke dalam validasi ini
            'type' => 'required|in:mill,warehouse,shop,indie', 
            'current_stock' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        // Jika tipenya indie, pastikan stoknya 0
        if ($request->type == 'indie') {
            $data['current_stock'] = 0;
        }

        EcoLocation::create($data);

        return redirect()->route('admin.locations.index')->with('success', 'Cabang berhasil ditambahkan!');
    }
    
    public function destroy($id)
    {
        EcoLocation::findOrFail($id)->delete();
        return redirect()->route('admin.locations.index')->with('success', 'Cabang dihapus.');
    }

    public function edit($id)
    {
        $location = EcoLocation::findOrFail($id);
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, $id)
    {
        $location = EcoLocation::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            // PERBAIKAN: Tambahkan 'indie' ke dalam validasi ini
            'type' => 'required|in:mill,warehouse,shop,indie',
            'current_stock' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->all();
        if ($request->type == 'indie') {
            $data['current_stock'] = 0;
        }

        $location->update($data);

        return redirect()->route('admin.locations.index')->with('success', 'Data cabang berhasil diperbarui!');
    }
}