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

        // Real trailing-12-month series for the dashboard charts. These were
        // previously hardcoded arrays, so admins were shown invented figures.
        $chart = $this->monthlyTrends();

        return view('admin.dashboard', compact(
            'stats', 'recentMembers', 'recentMessages', 'recentAudit', 'chart'
        ));
    }

    /**
     * Build a 12-month window of member registrations and donation income.
     *
     * Aggregated in PHP rather than with a driver-specific date function so the
     * same code works on both SQLite and MySQL.
     *
     * @return array{labels: list<string>, members: list<int>, donations: list<float>}
     */
    private function monthlyTrends(): array
    {
        $start = now()->copy()->startOfMonth()->subMonths(11);

        $labels = [];
        $buckets = [];
        for ($i = 0; $i < 12; $i++) {
            $month = $start->copy()->addMonths($i);
            $labels[] = $month->format('M Y');
            $buckets[$month->format('Y-m')] = ['members' => 0, 'donations' => 0.0];
        }

        Member::where('created_at', '>=', $start)
            ->get(['created_at'])
            ->each(function ($member) use (&$buckets) {
                $key = $member->created_at?->format('Y-m');
                if ($key && isset($buckets[$key])) {
                    $buckets[$key]['members']++;
                }
            });

        Donation::where('status', 'Completed')
            ->where('created_at', '>=', $start)
            ->get(['created_at', 'amount'])
            ->each(function ($donation) use (&$buckets) {
                $key = $donation->created_at?->format('Y-m');
                if ($key && isset($buckets[$key])) {
                    $buckets[$key]['donations'] += (float) $donation->amount;
                }
            });

        return [
            'labels' => $labels,
            'members' => array_values(array_column($buckets, 'members')),
            'donations' => array_map(
                static fn ($v) => round($v, 2),
                array_values(array_column($buckets, 'donations'))
            ),
        ];
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
