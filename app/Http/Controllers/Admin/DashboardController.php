<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Event;
use App\Models\News;
use App\Models\Donation;
use App\Models\ContactMessage;
use App\Models\Term;
use App\Models\Project;
use App\Models\Beneficiary;
use App\Models\AuditLog;

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
            'totalProjects' => Project::count(),
        ];

        $recentMembers = Member::orderBy('created_at', 'desc')->take(5)->get();
        $recentMessages = ContactMessage::orderBy('created_at', 'desc')->take(5)->get();
        $recentAudit = AuditLog::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentMembers', 'recentMessages', 'recentAudit'));
    }

    public function terms()
    {
        $terms = Term::orderBy('start_date', 'desc')->get();
        return view('admin.terms.index', compact('terms'));
    }

    public function storeTerm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        Term::create($validated);
        return back()->with('success', 'Executive term defined.');
    }

    public function beneficiaries()
    {
        $beneficiaries = Beneficiary::with('project')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.beneficiaries.index', compact('beneficiaries'));
    }

    public function elections()
    {
        return view('admin.elections.index');
    }
}
