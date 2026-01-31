<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:4',
            'saran'  => 'nullable|string|max:1000',
        ]);

        // 2. Simpan ke Database
        Survey::create([
            'rating'     => $validated['rating'],
            'saran'      => $validated['saran'],
            'ip_address' => $request->ip(), // Menyimpan IP untuk mencegah spam (opsional)
            'user_agent' => $request->header('User-Agent'),
        ]);

        // 3. Return JSON response (karena kita pakai AJAX)
        return response()->json([
            'status'  => 'success',
            'message' => 'Terima kasih atas penilaian Anda!'
        ]);
    }
}
