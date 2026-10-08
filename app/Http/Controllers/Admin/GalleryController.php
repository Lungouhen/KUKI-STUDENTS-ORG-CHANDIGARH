<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\GalleryItem;
use App\Models\MediaAsset;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    public function index()
    {
        $search = request()->query('q', '');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';
        $gallery = GalleryItem::with('mediaAsset')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%')
                        ->orWhere('caption', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();
        $assets = MediaAsset::orderByDesc('id')->get();

        return view('admin.gallery.index', compact('gallery', 'assets', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:120',
            'caption' => 'nullable|string|max:500',
            'imageFile' => 'nullable|required_without:image_asset_path|image|max:5120',
            'image_asset_path' => ['nullable', 'required_without:imageFile', 'string', Rule::exists('media_assets', 'path')],
            'alt_text' => 'required_with:imageFile|nullable|string|max:255',
        ]);

        $asset = $request->file('imageFile')
            ? MediaAsset::storeUpload($request->file('imageFile'), $validated['alt_text'])
            : MediaAsset::where('path', $validated['image_asset_path'])->firstOrFail();

        GalleryItem::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'image_url' => $asset->path,
            'date' => now()->toDateString(),
            'caption' => $validated['caption'] ?? null,
        ]);

        AuditLog::log('CREATE_GALLERY_ITEM', [
            'title' => $validated['title'],
            'media_asset_id' => $asset->id,
        ]);

        return back()->with('success', 'Photo uploaded.');
    }

    public function destroy($id)
    {
        $item = GalleryItem::findOrFail($id);
        AuditLog::log('DELETE_GALLERY_ITEM', ['gallery_item_id' => $item->id, 'title' => $item->title]);
        $item->delete();

        return back()->with('success', 'Photo deleted.');
    }
}
