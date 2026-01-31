<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectReport;

class ReportController extends Controller
{
    // ... method lain (index, show, dll) ...

    public function exportAll()
    {
        // Ambil semua proyek beserta laporannya (Eager Loading)
        // Kita urutkan laporan berdasarkan tanggal terbaru
        $projects = Project::with(['reports' => function($query) {
                        $query->orderBy('created_at', 'desc');
                    }, 'reports.user']) // Load juga data user pelapor
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.reports.print_all', compact('projects'));
    }
}