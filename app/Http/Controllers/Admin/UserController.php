<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {$users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        // 1. PERBAIKAN VALIDASI
        $request->validate([
            'name' => 'required|string|max:255',
            // Ganti 'email' menjadi 'string' agar NIP/NIB bisa masuk (tanpa @)
            'email' => 'required|string|max:255|unique:users', 
            'role' => 'required|in:admin,subkon_pt,subkon_eks,eco,indie,keuangan',
            'password' => 'required|string|min:8',
        ]);

        // 2. PERBAIKAN SIMPAN DATA
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            // Pastikan kolom ini benar-benar ada di Database Anda
            'company_name' => $request->company_name,
            'specialization' => $request->specialization,
            'phone' => $request->phone,
            // Hapus 'address' jika tidak ada inputnya di form blade Anda
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            // Hapus validasi 'email', ganti string
            'email' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
           'role' => 'required|in:admin,subkon_pt,subkon_eks,eco,indie,keuangan',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'company_name' => $request->company_name,
            'specialization' => $request->specialization,
            'phone' => $request->phone,
        ];

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