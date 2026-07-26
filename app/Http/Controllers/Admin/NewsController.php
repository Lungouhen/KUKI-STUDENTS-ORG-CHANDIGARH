<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('date', 'desc')->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
            'author' => 'nullable|string',
        ]);

        News::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'content' => $validated['content'],
            'author' => $validated['author'] ?? 'Executive Desk',
            'date' => now()->toDateString(),
        ]);

        return back()->with('success', 'Announcement published successfully.');
    }

    public function destroy($id)
    {
        News::findOrFail($id)->delete();
        return back()->with('success', 'News deleted.');
    }
}
