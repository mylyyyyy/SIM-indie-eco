<?php

namespace App\Http\Controllers\Indie\KepalaKantor;

use App\Http\Controllers\Controller;
use App\Models\LhkpReport;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        
        $user = Auth::user();

        // Hitung statistik LHKP di cabang kepala kantor tersebut
        $lhkp_count = LhkpReport::whereHas('user', function($query) use ($user) {
            $query->where('company_name', $user->company_name);
        })->count();

        return view('indie.kepala-kantor.dashboard', compact('lhkp_count'));
    }
}