<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\MediaAsset;
use App\Models\Page;
use App\Models\PageRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    private const REVISION_FIELDS = [
        'title',
        'excerpt',
        'content',
        'template',
        'featured_image',
        'meta_title',
        'meta_description',
        'sections',
    ];

    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        if (! in_array($status, ['all', 'draft', 'review', 'scheduled', 'published'], true)) {
            $status = 'all';
        }

        $search = $request->query('q', '');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';
        $pages = Page::query()
            ->when($status !== 'all', fn ($query) => $query->where('publication_status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%'.$search.'%')
                        ->orWhere('slug', 'like', '%'.$search.'%')
                        ->orWhere('excerpt', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pages.index', compact('pages', 'status', 'search'));
    }

    public function preview($id)
    {
        $page = Page::findOrFail($id);

        return $this->renderPreview($page);
    }

    public function previewRevision($id, $revisionId)
    {
        $page = Page::findOrFail($id);
        $revision = $page->revisions()->findOrFail($revisionId);
        $page->forceFill($revision->snapshot);

        return $this->renderPreview($page);
    }

    public function compareRevision($id, $revisionId)
    {
        $page = Page::findOrFail($id);
        $revision = $page->revisions()->findOrFail($revisionId);

        return view('admin.pages.compare', [
            'page' => $page,
            'revision' => $revision,
            'current' => $this->revisionSnapshot($page),
        ]);
    }

    public function create()
    {
        $assets = MediaAsset::orderByDesc('id')->get();

        return view('admin.pages.create', compact('assets'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedPageData($request);

        DB::transaction(function () use ($data) {
            $status = $data['publication_status']
                ?? (request()->boolean('is_published') ? 'published' : 'draft');
            $page = Page::create([
                'title' => $data['title'],
                'slug' => $this->uniqueSlug($data['title']),
                'excerpt' => $data['excerpt'] ?? null,
                'content' => $data['content'] ?? '',
                'template' => $data['template'] ?? 'standard',
                'featured_image' => $data['featured_image'] ?? null,
                'meta_title' => $data['meta_title'] ?? $data['title'],
                'meta_description' => $data['meta_description'] ?? null,
                'sections' => $data['sections'] ?? null,
                'publication_status' => $status,
                'scheduled_publish_at' => $status === 'scheduled' ? $data['scheduled_publish_at'] : null,
                'is_published' => $status === 'published',
            ]);

            $this->createRevision($page);
            AuditLog::log('CREATE_PAGE', [
                'page_id' => $page->id,
                'title' => $page->title,
                'template' => $page->template,
                'publication_status' => $status,
            ]);
        });

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit($id)
    {
        $page = Page::with('revisions.author')->findOrFail($id);
        $assets = MediaAsset::orderByDesc('id')->get();

        return view('admin.pages.edit', compact('page', 'assets'));
    }

    public function update(Request $request, $id)
    {
        $data = $this->validatedPageData($request, Page::findOrFail($id));

        DB::transaction(function () use ($data, $request, $id) {
            $page = Page::query()->lockForUpdate()->findOrFail($id);
            $before = $this->revisionSnapshot($page);
            $status = $data['publication_status']
                ?? ($request->boolean('is_published') ? 'published' : 'draft');

            $pageUpdates = array_intersect_key($data, array_flip(self::REVISION_FIELDS));
            $pageUpdates['template'] ??= $page->template ?: 'standard';
            $page->fill($pageUpdates + [
                'publication_status' => $status,
                'scheduled_publish_at' => $status === 'scheduled' ? $data['scheduled_publish_at'] : null,
                'is_published' => $status === 'published',
            ]);

            $after = $this->revisionSnapshot($page);
            $contentChanged = $before !== $after;
            $page->save();

            if ($contentChanged) {
                $this->createRevision($page);
            }

            AuditLog::log('UPDATE_PAGE', [
                'page_id' => $page->id,
                'title' => $page->title,
                'template' => $page->template,
                'publication_status' => $status,
                'revision_created' => $contentChanged,
            ]);
        });

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function restoreRevision($id, $revisionId)
    {
        DB::transaction(function () use ($id, $revisionId) {
            $page = Page::query()->lockForUpdate()->findOrFail($id);
            $revision = $page->revisions()->findOrFail($revisionId);
            $before = $this->revisionSnapshot($page);
            $page->fill($revision->snapshot);

            if ($before !== $this->revisionSnapshot($page)) {
                $page->save();
                $this->createRevision($page);
            }

            AuditLog::log('RESTORE_PAGE_REVISION', [
                'page_id' => $page->id,
                'revision_id' => $revision->id,
                'version' => $revision->version,
            ]);
        });

        return redirect()->route('admin.pages.edit', $id)->with('success', 'Page revision restored.');
    }

    public function bulk(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1|max:100',
            'ids.*' => 'required|integer|distinct|exists:pages,id',
            'action' => ['required', Rule::in(['publish', 'draft', 'review', 'delete'])],
        ]);

        DB::transaction(function () use ($data) {
            $pages = Page::whereIn('id', $data['ids'])->orderBy('id')->lockForUpdate()->get();
            foreach ($pages as $page) {
                if ($data['action'] === 'delete') {
                    $page->delete();

                    continue;
                }

                $page->update([
                    'publication_status' => $data['action'],
                    'scheduled_publish_at' => null,
                    'is_published' => $data['action'] === 'publish',
                ]);
            }

            AuditLog::log('BULK_UPDATE_PAGES', [
                'page_ids' => $pages->modelKeys(),
                'action' => $data['action'],
            ]);
        });

        return back()->with('success', 'Selected pages updated.');
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $title = $page->title;
        $page->delete();
        AuditLog::log('DELETE_PAGE', ['page_id' => $page->id, 'title' => $title]);

        return back()->with('success', 'Page deleted.');
    }

    private function renderPreview(Page $page)
    {
        $imagePaths = collect($page->sections ?? [])
            ->pluck('image')
            ->filter()
            ->push($page->featured_image)
            ->unique()
            ->values();
        $mediaAltText = MediaAsset::whereIn('path', $imagePaths)->pluck('alt_text', 'path');

        return view('pages.show', [
            'page' => $page,
            'isPreview' => true,
            'mediaAltText' => $mediaAltText,
        ]);
    }

    private function validatedPageData(Request $request, ?Page $existingPage = null): array
    {
        $sections = $request->input('sections');
        if ($request->boolean('sections_present') && ! is_array($sections)) {
            $sections = null;
            $request->merge(['sections' => null]);
        }

        if (is_array($sections)) {
            $sections = array_values(array_filter($sections, function ($section) {
                if (! is_array($section)) {
                    return true;
                }

                return collect(['heading', 'text', 'image', 'link_label', 'link_url'])
                    ->contains(fn ($field) => is_string($section[$field] ?? null) && trim($section[$field]) !== '');
            }));
            $request->merge(['sections' => $sections ?: null]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:2000',
            'content' => 'nullable|string',
            'template' => ['sometimes', 'string', Rule::in(array_keys(config('page_templates')))],
            'featured_image' => 'nullable|string|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'publication_status' => ['sometimes', Rule::in(['draft', 'review', 'scheduled', 'published'])],
            'scheduled_publish_at' => 'required_if:publication_status,scheduled|nullable|date|after:now',
            'sections' => 'nullable|array|max:30',
            'sections.*' => 'required|array:type,heading,text,image,link_label,link_url',
            'sections.*.type' => ['required', 'string', Rule::in(['heading', 'text', 'image', 'callout', 'button'])],
            'sections.*.heading' => 'nullable|string|max:255',
            'sections.*.text' => 'nullable|string|max:10000',
            'sections.*.image' => ['nullable', 'string', Rule::exists('media_assets', 'path')],
            'sections.*.link_label' => 'nullable|string|max:120',
            'sections.*.link_url' => ['nullable', 'string', 'max:2048', 'regex:/^(https?:\/\/[^\s]+|\/(?!\/)[^\s]*|#[A-Za-z0-9_-]+)$/i'],
        ]);

        $validator->after(function ($validator) use ($request, $existingPage) {
            foreach ($request->input('sections', []) as $index => $section) {
                if (! is_array($section) || empty($section['type'])) {
                    continue;
                }

                $required = match ($section['type']) {
                    'heading' => ['heading'],
                    'text', 'callout' => ['text'],
                    'image' => ['image'],
                    'button' => ['link_label', 'link_url'],
                    default => [],
                };

                foreach ($required as $field) {
                    if (empty($section[$field])) {
                        $validator->errors()->add("sections.{$index}.{$field}", 'This field is required for this section type.');
                    }
                }
            }

            $content = trim((string) $request->input('content', ''));
            $hasSections = is_array($request->input('sections')) && count($request->input('sections')) > 0;
            if ($content === '' && ! $hasSections) {
                $validator->errors()->add('content', 'Add page content or at least one structured section.');
            }

            $featuredImage = $request->input('featured_image');
            if (
                $featuredImage
                && ! MediaAsset::where('path', $featuredImage)->exists()
                && $featuredImage !== $existingPage?->featured_image
            ) {
                $validator->errors()->add('featured_image', 'Select an image from the media library.');
            }
        });

        return $validator->validate();
    }

    private function revisionSnapshot(Page $page): array
    {
        return collect(self::REVISION_FIELDS)
            ->mapWithKeys(fn ($field) => [$field => $page->getAttribute($field)])
            ->all();
    }

    private function createRevision(Page $page): PageRevision
    {
        $version = PageRevision::where('page_id', $page->id)->max('version') + 1;

        return $page->revisions()->create([
            'version' => $version,
            'snapshot' => $this->revisionSnapshot($page),
            'changed_by' => auth()->id(),
        ]);
    }

    private function uniqueSlug(string $title): string
    {
        $base = trim(substr(Str::slug($title), 0, 240), '-');
        $base = $base !== '' ? $base : 'page';
        $slug = $base;
        $suffix = 2;

        while (Page::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }
}
