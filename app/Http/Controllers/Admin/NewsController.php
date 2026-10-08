<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class NewsController extends Controller
{
    private const PUBLICATION_STATUSES = ['draft', 'review', 'scheduled', 'published'];

    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        if (! is_string($status) || ! in_array($status, ['all', ...self::PUBLICATION_STATUSES], true)) {
            $status = 'all';
        }

        $search = $request->query('q', '');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';

        $news = News::query()
            ->when($status !== 'all', fn ($query) => $query->where('publication_status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%')
                        ->orWhere('content', 'like', '%'.$search.'%')
                        ->orWhere('author', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.news.index', compact('news', 'status', 'search'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $status = $data['publication_status'] ?? 'published';
        $news = News::create([
            'title' => $data['title'],
            'category' => $data['category'],
            'content' => $data['content'],
            'author' => $data['author'] ?? 'Executive Desk',
            'date' => now()->toDateString(),
            'publication_status' => $status,
            'scheduled_publish_at' => $status === 'scheduled' ? $data['scheduled_publish_at'] : null,
        ]);

        AuditLog::log('CREATE_NEWS', "News ID: {$news->id}, title: {$news->title}");
        if ($status !== 'published') {
            AuditLog::log('SET_NEWS_PUBLICATION_STATUS', [
                'news_id' => $news->id,
                'publication_status' => $status,
                'scheduled_publish_at' => $news->scheduled_publish_at?->toIso8601String(),
            ]);
        }

        return back()->with('success', $status === 'published' ? 'Announcement published successfully.' : 'Announcement saved.');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);

        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        $previousStatus = $news->publication_status;
        $data = $this->validatedData($request);
        $status = $data['publication_status'] ?? $news->publication_status;
        $news->update([
            ...array_intersect_key($data, array_flip(['title', 'category', 'content', 'author'])),
            'publication_status' => $status,
            'scheduled_publish_at' => $status === 'scheduled'
                ? ($data['scheduled_publish_at'] ?? $news->scheduled_publish_at)
                : null,
        ]);

        AuditLog::log('UPDATE_NEWS', "News ID: {$news->id}, title: {$news->title}");
        if ($previousStatus !== $status) {
            AuditLog::log('SET_NEWS_PUBLICATION_STATUS', [
                'news_id' => $news->id,
                'previous_status' => $previousStatus,
                'publication_status' => $status,
                'scheduled_publish_at' => $news->scheduled_publish_at?->toIso8601String(),
            ]);
        }

        return redirect()->route('admin.news.index')->with('success', 'Announcement updated.');
    }

    public function bulk(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1|max:100',
            'ids.*' => 'required|integer|distinct|exists:news,id',
            'publication_status' => ['required', Rule::in(self::PUBLICATION_STATUSES)],
            'scheduled_publish_at' => 'required_if:publication_status,scheduled|nullable|date|after:now',
        ]);

        DB::transaction(function () use ($data) {
            $news = News::whereIn('id', $data['ids'])->orderBy('id')->lockForUpdate()->get();
            foreach ($news as $item) {
                $item->update([
                    'publication_status' => $data['publication_status'],
                    'scheduled_publish_at' => $data['publication_status'] === 'scheduled'
                        ? $data['scheduled_publish_at']
                        : null,
                ]);
            }

            AuditLog::log('BULK_UPDATE_NEWS', [
                'news_ids' => $news->modelKeys(),
                'publication_status' => $data['publication_status'],
            ]);
        });

        return back()->with('success', 'Selected announcements updated.');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        AuditLog::log('DELETE_NEWS', "News ID: {$news->id}, title: {$news->title}");
        $news->delete();

        return back()->with('success', 'News deleted.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'content' => 'required|string|max:50000',
            'author' => 'nullable|string|max:255',
            'publication_status' => ['sometimes', Rule::in(self::PUBLICATION_STATUSES)],
            'scheduled_publish_at' => 'required_if:publication_status,scheduled|nullable|date|after:now',
        ]);
    }
}
