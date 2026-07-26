<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GalleryItem;
use App\Models\Donation;
use App\Models\ContactMessage;
use App\Models\Setting;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = GalleryItem::orderBy('created_at', 'desc')->paginate(12);
        return view('admin.gallery.index', compact('gallery'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'imageFile' => 'required|image|max:5120',
        ]);

        $path = $request->file('imageFile')->store('uploads/gallery', 'public');

        GalleryItem::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'image_url' => '/storage/' . $path,
            'date' => now()->toDateString(),
        ]);

        return back()->with('success', 'Photo uploaded.');
    }

    public function destroy($id)
    {
        GalleryItem::findOrFail($id)->delete();
        return back()->with('success', 'Photo deleted.');
    }
}
