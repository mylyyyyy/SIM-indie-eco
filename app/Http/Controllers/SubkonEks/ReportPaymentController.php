<?php

namespace App\Http\Controllers\SubkonEks;

use App\Http\Controllers\Controller;
use App\Models\ProjectReport;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportPaymentController extends Controller
{
    public function index()
    {
        $reports = ProjectReport::where('user_id', Auth::id())
                    ->with('project')
                    ->latest()
                    ->get();
                    
        // View diarahkan ke folder report-payments
        return view('subkon-eks.report-payments.index', compact('reports'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('subkon-eks.report-payments.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'report_date' => 'required|date',
            'progress_percentage' => 'required|numeric|min:0|max:100',
            'work_description' => 'required|string',
            'document' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $imageBase64 = null;
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $mime = $file->getMimeType();
            $content = file_get_contents($file->getRealPath());
            $imageBase64 = "data:$mime;base64," . base64_encode($content);
        }

        ProjectReport::create([
            'user_id' => Auth::id(),
            'project_id' => $request->project_id,
            'report_date' => $request->report_date,
            'progress_percentage' => $request->progress_percentage,
            'work_description' => $request->work_description,
            'documentation_path' => $imageBase64,
            'status' => 'pending',
        ]);

        // Redirect ke route report-payments.index
        return redirect()->route('subkon-eks.report-payments.index')
            ->with('success', 'Laporan klaim pembayaran berhasil dikirim!');
    }
}