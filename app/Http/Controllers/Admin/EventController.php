<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\News;
use App\Models\CommitteeMember;
use App\Models\GalleryItem;
use App\Models\Donation;
use App\Models\ContactMessage;
use App\Models\Setting;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('date', 'desc')->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'date' => 'required|date',
            'time' => 'nullable|string',
            'venue' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|string',
            'imageFile' => 'nullable|image|max:5120',
        ]);

        $imgPath = '/images/event-freshers.jpg';
        if ($request->hasFile('imageFile')) {
            $path = $request->file('imageFile')->store('uploads/events', 'public');
            $imgPath = '/storage/' . $path;
        }

        Event::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'venue' => $validated['venue'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'image' => $imgPath,
        ]);

        return back()->with('success', 'Event created successfully.');
    }

    public function destroy($id)
    {
        Event::findOrFail($id)->delete();
        return back()->with('success', 'Event deleted.');
    }
}
