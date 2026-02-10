<?php

namespace App\Http\Controllers;
use App\Models\News;
use App\Models\Announcement;
use App\Models\Team;
class HomeController extends Controller
{
    public function index()
    {
        $news = News::latest()->take(3)->get();
        $announcements = Announcement::latest()->take(5)->get();
$teams = Team::orderBy('urutan', 'asc')->get();
        // Kirim data ke view welcome
        return view('welcome', compact('news', 'announcements', 'teams'));
    }

    public function contact()
    {
        return view('contact.index');
    }
}