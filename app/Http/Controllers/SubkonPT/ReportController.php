<?php

namespace App\Http\Controllers\SubkonPt;

use App\Http\Controllers\Controller;
use App\Models\SubkonPtReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Jika yang login adalah Manager (atau atasan lainnya)
        if ($user->role == 'manager_unit_indie' || $user->role == 'monitoring_indie') {
            // Tampilkan SEMUA laporan dari Subkon
            $reports = SubkonPtReport::latest('tanggal_laporan')->get();
        } else {
            // Jika Subkon PT, hanya tampilkan laporan miliknya sendiri
            $reports = SubkonPtReport::where('user_id', $user->id)->latest('tanggal_laporan')->get();
        }

        return view('subkon-pt.reports.index', compact('reports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'report_type' => 'required|in:mingguan,bulanan',
            'periode' => 'required|string|max:100',
            'tanggal_laporan' => 'required|date',
            'progress_target' => 'required|numeric|min:0|max:100',
            'progress_actual' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        SubkonPtReport::create($data);

        return redirect()->back()->with('success', 'Laporan Progres berhasil disimpan!');
    }

    public function destroy($id)
    {
        SubkonPtReport::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Laporan berhasil dihapus!');
    }
}