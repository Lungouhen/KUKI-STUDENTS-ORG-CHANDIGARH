<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommitteeMember;

class CommitteeController extends Controller
{
    public function index()
    {
        $committee = CommitteeMember::orderBy('display_order')->get();
        return view('admin.committee.index', compact('committee'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string',
            'institution' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'tenure' => 'required|string',
            'photoFile' => 'nullable|image|max:5120',
        ]);

        $photoPath = '/images/default-avatar-m.png';
        if ($request->hasFile('photoFile')) {
            $path = $request->file('photoFile')->store('uploads/committee', 'public');
            $photoPath = '/storage/' . $path;
        }

        CommitteeMember::create([
            'name' => $validated['name'],
            'designation' => $validated['designation'],
            'institution' => $validated['institution'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'tenure' => $validated['tenure'],
            'photo' => $photoPath,
            'display_order' => CommitteeMember::count() + 1,
        ]);

        return back()->with('success', 'Committee member added.');
    }

    public function edit($id)
    {
        $committee = CommitteeMember::findOrFail($id);
        return view('admin.committee.edit', compact('committee'));
    }

    public function update(Request $request, $id)
    {
        $committee = CommitteeMember::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string',
            'institution' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'tenure' => 'required|string',
            'photoFile' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('photoFile')) {
            $path = $request->file('photoFile')->store('uploads/committee', 'public');
            $validated['photo'] = '/storage/' . $path;
        }

        $committee->update($validated);

        return redirect()->route('admin.committee.index')->with('success', 'Executive leader details updated.');
    }

    public function destroy($id)
    {
        CommitteeMember::findOrFail($id)->delete();
        return back()->with('success', 'Committee member removed.');
    }
}
