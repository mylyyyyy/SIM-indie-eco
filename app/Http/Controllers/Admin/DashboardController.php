<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\EcoLocation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class DashboardController extends Controller
{
    public function index()
    {
        // 1. DATA USER & TOTAL PROYEK
        $subkonPT  = User::where('role', 'subkon_pt')->count();
        $subkonEks = User::where('role', 'subkon_eks')->count();
        $totalProjects = Project::count();

        // 2. DATA PER DIVISI (Eco vs Indie)
        $ecoProjects   = Project::where(function($q) {
                            $q->where('project_name', 'LIKE', '%Eco%')
                              ->orWhere('project_name', 'LIKE', '%Residence%');
                        })->count();
                        
        $indieProjects = $totalProjects - $ecoProjects;

        $teamEco   = User::where('role', 'eco')->count();
        $teamIndie = User::where('role', 'indie')->count();

        // 3. DATA CABANG
        $totalLocations = EcoLocation::count();
        $totalStock     = EcoLocation::sum('current_stock');
        $activeLocations = EcoLocation::where('status', 'active')->count();

        // =================================================================
        // 4. DATA CHART (ALL TIME - GROUP BY MONTH)
        // =================================================================
        
        // Ambil daftar bulan unik yang ada di database, urutkan dari terlama
        $months = Project::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month_year")
                    ->distinct()
                    ->orderBy('month_year', 'asc')
                    ->pluck('month_year'); // Contoh hasil: ['2023-01', '2023-02', ...]

        $chartLabels = [];
        $chartEco    = [];
        $chartIndie  = [];

        // Jika data kosong, default ke bulan ini agar chart tidak error
        if ($months->isEmpty()) {
            $months->push(Carbon::now()->format('Y-m'));
        }

        foreach ($months as $ym) {
            // Label Chart (Contoh: Jan 2024)
            $chartLabels[] = Carbon::createFromFormat('Y-m', $ym)->format('M Y');

            // Hitung Eco di bulan tersebut
            $chartEco[] = Project::where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), $ym)
                ->where(function($q) { 
                    $q->where('project_name', 'LIKE', '%Eco%')->orWhere('project_name', 'LIKE', '%Residence%'); 
                })->count();

            // Hitung Indie di bulan tersebut
            $chartIndie[] = Project::where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), $ym)
                ->where(function($q) { 
                    $q->where('project_name', 'NOT LIKE', '%Eco%')->where('project_name', 'NOT LIKE', '%Residence%'); 
                })->count();
        }

        return view('admin.dashboard', compact(
            'subkonPT', 'subkonEks', 'totalProjects',
            'ecoProjects', 'indieProjects', 'teamEco', 'teamIndie',
            'totalLocations', 'totalStock', 'activeLocations',
            'chartLabels', 'chartEco', 'chartIndie'
        ));
    }
}