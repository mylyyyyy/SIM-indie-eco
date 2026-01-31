<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus user admin lama jika ada, untuk menghindari duplikat
        User::where('email', 'admin@lapasjombang.go.id')->delete();

        // Buat user admin baru
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@lapasjombang.go.id',
            'role' => 'admin',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
            'email_verified_at' => now(),
        ]);
    }
}
