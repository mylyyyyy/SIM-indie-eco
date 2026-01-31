<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the news.
     */
    public function index(Request $request)
    {
        $query = News::where('status', 'published');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        // Category filter (assuming there's a category field, or use tags/content)
        if ($request->has('category') && !empty($request->category)) {
            $category = $request->category;
            $query->where('content', 'like', '%' . $category . '%'); // Simple filter, can be improved
        }

        $allNews = $query->latest()->paginate(10)->appends($request->query());
        return view('news.index', compact('allNews'));
    }

    /**
     * Display the specified news.
     */
    public function show(News $news)
    {
        // Ensure only published news can be viewed publicly
        if ($news->status !== 'published') {
            abort(404);
        }

        // Get previous and next news articles
        $previousNews = News::where('status', 'published')
            ->where('created_at', '<', $news->created_at)
            ->orderBy('created_at', 'desc')
            ->first();

        $nextNews = News::where('status', 'published')
            ->where('created_at', '>', $news->created_at)
            ->orderBy('created_at', 'asc')
            ->first();

        return view('news.show', compact('news', 'previousNews', 'nextNews'));
    }
}
