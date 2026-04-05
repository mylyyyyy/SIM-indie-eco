<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\EcoLocation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        // Mengambil data lokasi/cabang yang aktif saja
        $locations = EcoLocation::where('status', 'active')->get(); 
        
        return view('admin.users.index', compact('users', 'locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:users', 
            
            // PERBAIKAN: Semua role sudah terdaftar di sini tanpa ada spasi atau baris baru (newline) yang merusak format
            'role' => 'required|in:admin,subkon_pt,subkon_eks,eco,indie,keuangan,kepala_kantor,manager_unit,manager_wilayah,keuangan_eco,keuangan_indie,manager_unit_indie,kepala_kantor_indie,admin_lapangan_indie,monitoring_indie,monitoring_eco',
            
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'company_name' => $request->company_name,
            'wilayah' => $request->wilayah,
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            
            // PERBAIKAN: Daftar role disamakan dengan fungsi store (semua role Indie & Eco dimasukkan)
            'role' => 'required|in:admin,subkon_pt,subkon_eks,eco,indie,keuangan,kepala_kantor,manager_unit,manager_wilayah,keuangan_eco,keuangan_indie,manager_unit_indie,kepala_kantor_indie,admin_lapangan_indie,monitoring_indie,monitoring_eco',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'company_name' => $request->company_name,
            'wilayah' => $request->wilayah, 
            'phone' => $request->phone,
        ];

        // Jika password diisi, maka update password
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'Pengguna berhasil dihapus!');
    }
}