<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommitteeMember;
use App\Models\GalleryItem;
use App\Models\Donation;
use App\Models\ContactMessage;
use App\Models\Setting;

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

    public function destroy($id)
    {
        CommitteeMember::findOrFail($id)->delete();
        return back()->with('success', 'Committee member removed.');
    }
}
