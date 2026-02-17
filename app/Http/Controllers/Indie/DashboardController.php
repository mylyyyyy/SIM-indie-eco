<?php

namespace App\Http\Controllers\Indie;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DashboardController extends Controller
{
public function exportPdf()
    {
        // Ambil semua data proyek khusus Indie
        $projects = Project::where(function($q) {
            $q->where('project_name', 'NOT LIKE', '%Eco%')
              ->where('project_name', 'NOT LIKE', '%Residence%');
        })->latest()->get();

        // Panggil view cetak (print preview browser)
        return view('indie.pdf.projects-report', compact('projects'));
    }
public function index()
    {
        // 1. DEFINE FILTER LOGIC (INDIE = NOT ECO)
        // Kita simpan query dasar ini agar bisa di-reuse
        $indieQuery = Project::where(function($q) {
            $q->where('project_name', 'NOT LIKE', '%Eco%')
              ->where('project_name', 'NOT LIKE', '%Residence%');
        });

        // 2. STATISTIK UTAMA
        $totalProjects = $indieQuery->count();
        
        // Asumsi status 'completed' ada, jika tidak ada hitung semua sebagai aktif sementara
        $completedProjects = (clone $indieQuery)->where('status', 'completed')->count();
        $activeProjects    = $totalProjects - $completedProjects;

        $totalPortfolios = Portfolio::where('division', 'indie')->count();
        $totalNews       = DB::table('berita')->count(); // Berita global (bisa difilter jika ada kolom divisi)

        // 3. TABEL PROYEK TERBARU (Limit 5)
        $recentProjects = (clone $indieQuery)->latest()->limit(5)->get();

        // 4. DATA CHART (Tren Proyek Indie 6 Bulan Terakhir)
        $chartLabels = [];
        $chartData   = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M Y');
            $chartLabels[] = $monthName;

            $count = Project::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where(function($q) {
                    $q->where('project_name', 'NOT LIKE', '%Eco%')
                      ->where('project_name', 'NOT LIKE', '%Residence%');
                })->count();

            $chartData[] = $count;
        }

        return view('indie.dashboard', compact(
            'totalProjects', 
            'activeProjects', 
            'completedProjects', 
            'totalPortfolios',
            'recentProjects',
            'chartLabels',
            'chartData'
        ));
    }
}