<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Faq;
use App\Models\MediaAsset;

class PageController extends Controller
{
    public function show($slug)
    {
        $query = Page::where('slug', $slug);

        $isAdmin = auth()->check() && auth()->user()->is_admin;
        if (! $isAdmin) {
            $query->where('is_published', true);
        }

        $page = $query->firstOrFail();

        if ($page->is_published) {
            $page->increment('view_count');
        }

        $imagePaths = collect($page->sections ?? [])
            ->pluck('image')
            ->filter()
            ->push($page->featured_image)
            ->unique()
            ->values();
        $mediaAltText = MediaAsset::whereIn('path', $imagePaths)->pluck('alt_text', 'path');

        return view('pages.show', compact('page', 'mediaAltText'));
    }

    public function faqs()
    {
        $faqs = Faq::where('is_published', true)->orderBy('sort_order')->get()->groupBy('category');
        return view('pages.faqs', compact('faqs'));
    }
}
