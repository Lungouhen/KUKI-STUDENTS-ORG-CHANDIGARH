<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GalleryItem;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = GalleryItem::orderBy('date', 'desc')->get();
        return view('gallery.index', compact('gallery'));
    }
}
