<?php

namespace App\Http\Controllers\SubkonPt;

use App\Http\Controllers\Controller;
use App\Models\SubkonPtDailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Jika Manager Unit Indie, tampilkan semua. Jika Subkon PT, tampilkan miliknya saja.
        if ($user->role == 'manager_unit_indie' || $user->role == 'monitoring_indie') {
            $dailyReports = SubkonPtDailyReport::latest('tanggal')->get();
        } else {
            $dailyReports = SubkonPtDailyReport::where('user_id', $user->id)->latest('tanggal')->get();
        }

        return view('subkon-pt.daily-reports.index', compact('dailyReports'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'cuaca' => 'required|string',
            'jumlah_pekerja' => 'required|integer|min:0',
            'pekerjaan_dilakukan' => 'required|string',
            'kendala' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        SubkonPtDailyReport::create($data);

        return redirect()->back()->with('success', 'Laporan Harian berhasil disimpan!');
    }

    public function destroy($id)
    {
        SubkonPtDailyReport::where('user_id', Auth::id())->findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Laporan Harian berhasil dihapus!');
    }
}