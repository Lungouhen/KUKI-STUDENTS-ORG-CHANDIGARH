<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GalleryItem;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = GalleryItem::where('image_url', 'not like', '/images/gallery-%')
            ->orderByDesc('date')
            ->get();
        return view('gallery.index', compact('gallery'));
    }
}
