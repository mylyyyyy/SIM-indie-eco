<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectReport; // Asumsi nama model laporan
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. DATA USER
        $subkonPT  = User::where('role', 'subkon_pt')->count();
        $subkonEks = User::where('role', 'subkon_eks')->count();
        
        // 2. DATA PROYEK (Total)
        $totalProjects = Project::count();

        // 3. DATA PROYEK (Per Kategori - Asumsi pakai string matching jika kolom kategori belum strict)
        // Jika Anda punya kolom 'division' atau 'type' di tabel projects, ganti logic ini.
        $ecoProjects   = Project::where('project_name', 'LIKE', '%Eco%')
                                ->orWhere('project_name', 'LIKE', '%Residence%')
                                ->orWhere('project_name', 'LIKE', '%Subsidi%')
                                ->count();
                                
        $indieProjects = Project::where('project_name', 'LIKE', '%Indie%')
                                ->orWhere('project_name', 'LIKE', '%Commercial%')
                                ->orWhere('project_name', 'LIKE', '%Bridge%')
                                ->orWhere('project_name', 'LIKE', '%Gedung%')
                                ->count();

        // Jika logic di atas kurang akurat, sisa proyek masuk ke Indie atau hitung selisihnya
        if ($ecoProjects + $indieProjects < $totalProjects) {
            $indieProjects = $totalProjects - $ecoProjects;
        }

        // 4. DATA TIM DIVISI (Role Baru)
        $teamEco = User::where('role', 'eco')->count();
        $teamIndie = User::where('role', 'indie')->count();

        return view('admin.dashboard', compact(
            'subkonPT', 'subkonEks', 'totalProjects',
            'ecoProjects', 'indieProjects',
            'teamEco', 'teamIndie'
        ));
    }
}