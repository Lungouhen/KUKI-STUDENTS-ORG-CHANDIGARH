<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\GeneralContent;
use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ContentController extends Controller
{
    private const TITLES = [
        'slider' => 'Homepage Sliders',
        'certificate' => 'Official Certificates',
        'achievement' => 'Achievements & Awards',
        'policy' => 'Organization Policies',
        'notice' => 'Official Notices',
        'campaign' => 'NGO Campaigns',
        'career' => 'Careers & Opportunities',
    ];

    public function index(Request $request)
    {
        $type = $request->query('type', 'slider');
        if (! is_string($type) || ! array_key_exists($type, self::TITLES)) {
            $type = 'slider';
        }

        $status = $request->query('status', 'all');
        if (! is_string($status) || ! in_array($status, ['all', 'published', 'draft', 'review', 'scheduled'], true)) {
            $status = 'all';
        }

        $search = $request->query('q', '');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';
        $contents = GeneralContent::with('mediaAsset')
            ->where('type', $type)
            ->when($status !== 'all', fn ($query) => $query->where('publication_status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%'.$search.'%')
                        ->orWhere('content', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('display_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $title = self::TITLES[$type];
        $assets = MediaAsset::orderByDesc('id')->get();

        return view('admin.content.index', compact('contents', 'type', 'title', 'status', 'search', 'assets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(self::TITLES))],
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'link' => 'nullable|string|max:2048',
            'imageFile' => 'nullable|image|max:5120',
            'image_asset_path' => ['nullable', 'string', Rule::exists('media_assets', 'path')],
            'alt_text' => 'required_with:imageFile|nullable|string|max:255',
            'publication_status' => ['sometimes', Rule::in(['draft', 'review', 'scheduled', 'published'])],
            'scheduled_publish_at' => 'required_if:publication_status,scheduled|nullable|date|after:now',
        ]);

        $asset = $request->file('imageFile')
            ? MediaAsset::storeUpload($request->file('imageFile'), $data['alt_text'])
            : null;

        GeneralContent::create([
            'type' => $data['type'],
            'title' => $data['title'],
            'content' => $data['content'] ?? null,
            'link' => $data['link'] ?? null,
            'image' => $asset?->path ?? $data['image_asset_path'] ?? null,
            'display_order' => GeneralContent::where('type', $data['type'])->max('display_order') + 1,
            'publication_status' => $data['publication_status'] ?? 'published',
            'scheduled_publish_at' => ($data['publication_status'] ?? 'published') === 'scheduled'
                ? $data['scheduled_publish_at']
                : null,
        ]);

        AuditLog::log('CREATE_CONTENT', "Type: {$data['type']}, Title: {$data['title']}");

        return back()->with('success', 'Content added successfully.');
    }

    public function bulk(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(self::TITLES))],
            'ids' => 'required|array|min:1|max:100',
            'ids.*' => 'required|integer|distinct|exists:general_contents,id',
            'action' => ['required', Rule::in(['publish', 'unpublish', 'draft', 'review', 'scheduled', 'delete', 'reorder'])],
            'scheduled_publish_at' => 'required_if:action,scheduled|nullable|date|after:now',
            'display_orders' => 'required_if:action,reorder|nullable|array',
            'display_orders.*' => 'required_if:action,reorder|nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($data) {
            $contents = GeneralContent::where('type', $data['type'])
                ->whereIn('id', $data['ids'])
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($contents->count() !== count($data['ids'])) {
                abort(404);
            }

            foreach ($contents as $content) {
                if ($data['action'] === 'delete') {
                    $content->delete();
                } elseif ($data['action'] === 'reorder') {
                    $displayOrder = $data['display_orders'][$content->id] ?? null;
                    if ($displayOrder === null) {
                        abort(422);
                    }
                    $content->update(['display_order' => $displayOrder]);
                } else {
                    $status = $data['action'] === 'unpublish' ? 'draft' : $data['action'];
                    $content->update([
                        'publication_status' => $status,
                        'scheduled_publish_at' => $status === 'scheduled' ? $data['scheduled_publish_at'] : null,
                    ]);
                }
            }

            AuditLog::log('BULK_UPDATE_CONTENT', [
                'content_ids' => $contents->modelKeys(),
                'type' => $data['type'],
                'action' => $data['action'],
            ]);
        });

        return back()->with('success', 'Selected content updated.');
    }

    public function destroy($id)
    {
        $content = GeneralContent::findOrFail($id);
        AuditLog::log('DELETE_CONTENT', "Type: {$content->type}, Title: {$content->title}");
        $content->delete();

        return back()->with('success', 'Item deleted.');
    }
}
