<?php

namespace App\Http\Controllers;

use App\Models\CommitteeMember;
use App\Models\Event;
use App\Models\GalleryItem;
use App\Models\GeneralContent;
use App\Models\Member;
use App\Models\News;
use App\Models\Partner;
use App\Models\Setting;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'membersCount' => Member::where('status', 'Approved')->count() ?: (int) Setting::get('baseMemberCount', 150),
            'collegesCount' => (int) Setting::get('collegesCount', 12),
            'eventsCount' => Event::count() ?: (int) Setting::get('baseEventCount', 25),
            'helpline' => Setting::get('helpline', '+91 98765 43211'),
        ];

        // Hero slides are managed in the CMS under Content → Homepage Slider.
        // Previously this content type was editable but never rendered anywhere.
        $slides = GeneralContent::where('type', 'slider')
            ->where('is_published', true)
            ->orderBy('display_order')
            ->get();

        $achievements = GeneralContent::where('type', 'achievement')
            ->where('is_published', true)
            ->orderBy('display_order')
            ->take(8)
            ->get();

        $committee = CommitteeMember::orderBy('display_order')->take(4)->get();
        $upcomingEvents = Event::where('status', 'Upcoming')->orderBy('date')->take(3)->get();
        $latestNews = News::orderBy('date', 'desc')->take(3)->get();
        $galleryHighlights = GalleryItem::orderBy('date', 'desc')->take(8)->get();

        $testimonials = Testimonial::where('is_featured', true)->latest()->take(9)->get();

        $partners = Partner::where('status', 'Active')->orderBy('name')->get();

        return view('home', compact(
            'stats',
            'slides',
            'achievements',
            'committee',
            'upcomingEvents',
            'latestNews',
            'galleryHighlights',
            'testimonials',
            'partners'
        ));
    }
}
