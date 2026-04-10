<?php

namespace App\Http\Controllers\SubkonPt;

use App\Http\Controllers\Controller;
use App\Models\SubkonPtWeeklyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeeklyReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Manager lihat semua, Subkon lihat miliknya saja
        if ($user->role == 'manager_unit_indie' || $user->role == 'monitoring_indie') {
            $weeklyReports = SubkonPtWeeklyReport::latest('periode_mulai')->get();
        } else {
            $weeklyReports = SubkonPtWeeklyReport::where('user_id', $user->id)->latest('periode_mulai')->get();
        }

        return view('subkon-pt.weekly-reports.index', compact('weeklyReports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'minggu_ke' => 'required|integer|min:1',
            'periode_mulai' => 'required|date',
            'periode_selesai' => 'required|date|after_or_equal:periode_mulai',
            'progress_minggu_ini' => 'required|numeric|min:0|max:100',
            'pekerjaan_diselesaikan' => 'required|string',
            'rencana_minggu_depan' => 'required|string',
            'kendala' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        
        SubkonPtWeeklyReport::create($data);

        return redirect()->back()->with('success', 'Laporan Mingguan berhasil disimpan!');
    }

    public function destroy($id)
    {
        SubkonPtWeeklyReport::where('user_id', Auth::id())->findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Laporan Mingguan berhasil dihapus!');
    }
}