<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\AuditLog;

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

        $news = News::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'content' => $validated['content'],
            'author' => $validated['author'] ?? 'Executive Desk',
            'date' => now()->toDateString(),
        ]);

        AuditLog::log('CREATE_NEWS', "News ID: {$news->id}, title: {$news->title}");

        return back()->with('success', 'Announcement published successfully.');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
            'author' => 'nullable|string',
        ]);

        $news->update($validated);

        AuditLog::log('UPDATE_NEWS', "News ID: {$news->id}, title: {$news->title}");

        return redirect()->route('admin.news.index')->with('success', 'Announcement updated.');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        AuditLog::log('DELETE_NEWS', "News ID: {$news->id}, title: {$news->title}");
        $news->delete();

        return back()->with('success', 'News deleted.');
    }
}
