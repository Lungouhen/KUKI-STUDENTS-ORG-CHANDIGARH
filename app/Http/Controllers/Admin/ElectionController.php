<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Election;
use App\Models\Candidate;
use App\Models\Term;
use App\Models\Member;
use App\Models\AuditLog;

class ElectionController extends Controller
{
    public function index()
    {
        $elections = Election::with('term')->orderBy('election_date', 'desc')->get();
        $terms = Term::all();
        return view('admin.elections.index', compact('elections', 'terms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'term_id' => 'required|exists:terms,id',
            'position' => 'required|string',
            'election_date' => 'required|date',
            'status' => 'required|string',
        ]);

        Election::create($validated);
        AuditLog::log('CREATE_ELECTION', "Position: {$validated['position']}");

        return back()->with('success', 'Election scheduled successfully.');
    }

    public function show($id)
    {
        $election = Election::with('candidates.member')->findOrFail($id);
        $members = Member::where('status', 'Approved')->get();
        return view('admin.elections.show', compact('election', 'members'));
    }

    public function addCandidate(Request $request, $id)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
        ]);

        Candidate::create([
            'election_id' => $id,
            'member_id' => $validated['member_id'],
        ]);

        return back()->with('success', 'Candidate added to election.');
    }

    public function updateVotes(Request $request, $candidateId)
    {
        $candidate = Candidate::findOrFail($candidateId);
        $candidate->votes_received = $request->input('votes', 0);
        $candidate->save();

        return back()->with('success', 'Votes updated.');
    }
}
