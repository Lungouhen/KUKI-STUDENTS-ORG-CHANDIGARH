<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Event;
use App\Models\News;
use App\Models\Donation;
use App\Models\ContactMessage;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalMembers' => Member::count(),
            'pendingMembers' => Member::where('status', 'Pending')->count(),
            'approvedMembers' => Member::where('status', 'Approved')->count(),
            'totalEvents' => Event::count(),
            'totalDonations' => Donation::where('status', 'Completed')->sum('amount'),
            'unreadMessages' => ContactMessage::where('status', 'Unread')->count(),
        ];

        $recentMembers = Member::orderBy('created_at', 'desc')->take(5)->get();
        $recentMessages = ContactMessage::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentMembers', 'recentMessages'));
    }
}
