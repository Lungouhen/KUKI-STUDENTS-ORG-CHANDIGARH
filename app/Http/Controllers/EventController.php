<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\News;
use Illuminate\Support\Str;

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

    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function registerAttendee(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'institution' => 'required|string',
            'member_id' => 'nullable|string',
        ]);

        $ticketCode = 'TICKET-' . date('Y') . '-' . strtoupper(Str::random(6));

        $reg = EventRegistration::create([
            'event_id' => $event->id,
            'member_id' => $validated['member_id'] ?? null,
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'institution' => $validated['institution'],
            'ticket_code' => $ticketCode,
            'is_attended' => false,
        ]);

        return redirect()->route('events.ticketPass', $reg->ticket_code)->with('success', 'Event RSVP confirmed! Here is your entry pass.');
    }

    public function ticketPass($ticketCode)
    {
        $registration = EventRegistration::with('event')->where('ticket_code', $ticketCode)->firstOrFail();
        return view('events.ticket_pass', compact('registration'));
    }
}
