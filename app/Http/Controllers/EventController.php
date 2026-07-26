<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\News;
use App\Models\GalleryItem;
use App\Models\Donation;
use App\Models\ContactMessage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->query('category');
        $query = Event::query();

        if ($category && $category !== 'All') {
            $query->where('category', $category);
        }

        $events = $query->orderBy('date', 'desc')->get();
        $news = News::orderBy('date', 'desc')->get();

        return view('events.index', compact('events', 'news', 'category'));
    }
}
