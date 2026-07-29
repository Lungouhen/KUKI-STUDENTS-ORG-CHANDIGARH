<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GalleryItem;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');

        // Categories are derived from the data itself, so adding a new category
        // in the CMS automatically produces a new filter button.
        $categories = GalleryItem::query()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $gallery = GalleryItem::query()
            ->when($category && $category !== 'All', fn ($q) => $q->where('category', $category))
            ->orderBy('date', 'desc')
            ->get();

        return view('gallery.index', compact('gallery', 'categories', 'category'));
    }
}
