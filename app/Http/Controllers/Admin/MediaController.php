<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\GalleryItem;
use App\Models\GeneralContent;
use App\Models\MediaAsset;
use App\Models\Page;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q', '');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';
        $usage = $request->query('usage', 'all');
        if (! in_array($usage, ['all', 'used', 'unused'], true)) {
            $usage = 'all';
        }

        $usedPaths = $this->referencedPaths();
        $query = MediaAsset::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('original_name', 'like', '%'.$search.'%')
                        ->orWhere('alt_text', 'like', '%'.$search.'%');
                });
            });

        if ($usage === 'used') {
            $query->whereIn('path', $usedPaths);
        } elseif ($usage === 'unused') {
            $query->whereNotIn('path', $usedPaths);
        }

        $assets = $query->orderByDesc('created_at')
            ->paginate(24)
            ->withQueryString();

        $assets->getCollection()->each(function (MediaAsset $asset) use ($usedPaths) {
            $asset->setAttribute('is_used', $usedPaths->contains($asset->path));
        });

        return view('admin.media.index', compact('assets', 'search', 'usage'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'imageFile' => 'required|image|max:5120',
            'alt_text' => 'required|string|max:255',
        ]);

        $asset = MediaAsset::storeUpload($request->file('imageFile'), $data['alt_text']);
        AuditLog::log('UPLOAD_MEDIA_ASSET', [
            'media_asset_id' => $asset->id,
            'original_name' => $asset->original_name,
        ]);

        return back()->with('success', 'Image added to the media library.');
    }

    public function update(Request $request, $id)
    {
        $asset = MediaAsset::findOrFail($id);
        $data = $request->validate(['alt_text' => 'required|string|max:255']);
        $asset->update(['alt_text' => $data['alt_text']]);

        AuditLog::log('UPDATE_MEDIA_ALT_TEXT', [
            'media_asset_id' => $asset->id,
            'alt_text' => $asset->alt_text,
        ]);

        return back()->with('success', 'Alternative text updated.');
    }

    public function destroy($id)
    {
        $asset = MediaAsset::findOrFail($id);
        if ($this->referencedPaths()->contains($asset->path)) {
            return back()->withErrors(['media' => 'This image is in use. Replace its references before deleting it.']);
        }

        $path = $asset->path;
        $asset->deleteStoredFile();
        AuditLog::log('DELETE_MEDIA_ASSET', [
            'media_asset_id' => $asset->id,
            'path' => $path,
        ]);

        return back()->with('success', 'Unused image removed.');
    }

    private function referencedPaths()
    {
        $paths = GeneralContent::whereNotNull('image')->pluck('image')
            ->merge(GalleryItem::whereNotNull('image_url')->pluck('image_url'))
            ->merge(Page::whereNotNull('featured_image')->pluck('featured_image'));

        Page::whereNotNull('sections')->get(['sections'])->each(function (Page $page) use ($paths) {
            foreach ($page->sections ?? [] as $section) {
                if (! empty($section['image'])) {
                    $paths->push($section['image']);
                }
            }
        });

        foreach ([
            Page::whereNotNull('content')->pluck('content'),
            GeneralContent::whereNotNull('content')->pluck('content'),
        ] as $contents) {
            foreach ($contents as $content) {
                preg_match_all('~(/storage/uploads/media/[^"\'\\s<>()]+)~', (string) $content, $matches);
                $paths = $paths->merge($matches[1] ?? []);
            }
        }

        return $paths->unique()->values();
    }
}
