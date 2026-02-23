<?php

namespace App\Http\Controllers\SubkonPt;

use App\Http\Controllers\Controller;
use App\Models\LhReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LhDownloadController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Mengambil Laporan Harian (LH) dari user (Subkon Eks/Pelaksana) yang 1 cabang
        $lhs = LhReport::whereHas('user', function($query) use ($user) {
                    $query->where('company_name', $user->company_name);
                })->latest()->get();

        return view('subkon-pt.lh-download.index', compact('lhs'));
    }
}