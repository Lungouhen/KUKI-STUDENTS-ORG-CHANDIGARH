<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\AuditLog;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $status = request()->query('status', 'all');
        if (! in_array($status, ['all', 'published', 'draft'], true)) {
            $status = 'all';
        }

        $pages = Page::when($status !== 'all', function ($query) use ($status) {
            $query->where('is_published', $status === 'published');
        })->orderByDesc('updated_at')->paginate(10)->withQueryString();

        return view('admin.pages.index', compact('pages', 'status'));
    }

    public function preview($id)
    {
        $page = Page::findOrFail($id);

        return view('pages.show', ['page' => $page, 'isPreview' => true]);
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'template' => ['required', 'string', Rule::in(array_keys(config('page_templates')))],
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'is_published' => 'sometimes|boolean',
        ]);

        Page::create([
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['title']),
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'template' => $validated['template'],
            'meta_title' => $validated['meta_title'] ?? $validated['title'],
            'meta_description' => $validated['meta_description'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

        AuditLog::log('CREATE_PAGE', [
            'title' => $validated['title'],
            'template' => $validated['template'],
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'template' => ['required', 'string', Rule::in(array_keys(config('page_templates')))],
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'is_published' => 'sometimes|boolean',
        ]);

        $validated['is_published'] = $request->boolean('is_published');
        $page->update($validated);

        AuditLog::log('UPDATE_PAGE', [
            'title' => $page->title,
            'template' => $page->template,
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $title = $page->title;
        $page->delete();
        AuditLog::log('DELETE_PAGE', ['title' => $title]);

        return back()->with('success', 'Page deleted.');
    }

    private function uniqueSlug(string $title): string
    {
        $base = trim(substr(Str::slug($title), 0, 240), '-');
        $base = $base !== '' ? $base : 'page';
        $slug = $base;
        $suffix = 2;

        while (Page::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $suffix;
            $suffix++;
        }

        return $slug;
    }
}
