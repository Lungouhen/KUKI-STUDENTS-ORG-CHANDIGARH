<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\CommitteeMember;
use App\Models\Event;
use App\Models\News;
use App\Models\GalleryItem;
use App\Models\Donation;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'membersCount' => Member::where('status', 'Approved')->count() ?: (int)\App\Models\Setting::get('baseMemberCount', 150),
            'collegesCount' => (int)\App\Models\Setting::get('collegesCount', 12),
            'eventsCount' => Event::count() ?: (int)\App\Models\Setting::get('baseEventCount', 25),
            'helpline' => \App\Models\Setting::get('helpline', '+91 98765 43211')
        ];

        $committee = CommitteeMember::orderBy('display_order')->take(4)->get();
        $upcomingEvents = Event::where('status', 'Upcoming')->orderBy('date')->take(3)->get();
        $latestNews = News::orderBy('date', 'desc')->take(3)->get();
        $galleryHighlights = GalleryItem::take(6)->get();

        return view('home', compact('stats', 'committee', 'upcomingEvents', 'latestNews', 'galleryHighlights'));
    }
}
