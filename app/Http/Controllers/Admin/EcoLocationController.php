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
            'type' => 'required|in:mill,warehouse,shop',
            'current_stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        EcoLocation::create($request->all());

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

    // UPDATE REDIRECT SETELAH UPDATE
    public function update(Request $request, $id)
    {
        $location = EcoLocation::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:mill,warehouse,shop',
            'current_stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $location->update($request->all());

        // Redirect kembali ke index
        return redirect()->route('admin.locations.index')->with('success', 'Data cabang berhasil diperbarui!');
    }
}