<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    private const PUBLICATION_STATUSES = ['draft', 'review', 'scheduled', 'published'];

    public function index(Request $request)
    {
        $status = $request->query('publication_status', 'all');
        if (! is_string($status) || ! in_array($status, ['all', ...self::PUBLICATION_STATUSES], true)) {
            $status = 'all';
        }

        $search = $request->query('q', '');
        $search = is_string($search) ? mb_substr(trim($search), 0, 100) : '';
        $events = Event::query()
            ->when($status !== 'all', fn ($query) => $query->where('publication_status', $status))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', '%'.$search.'%')
                        ->orWhere('category', 'like', '%'.$search.'%')
                        ->orWhere('venue', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%');
                });
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.events.index', compact('events', 'status', 'search'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $status = $data['publication_status'] ?? 'published';
        $image = $request->file('imageFile')
            ? '/storage/'.$request->file('imageFile')->store('uploads/events', 'public')
            : '/images/event-freshers.jpg';

        $event = Event::create([
            ...array_intersect_key($data, array_flip(['title', 'category', 'date', 'time', 'venue', 'description', 'status'])),
            'image' => $image,
            'publication_status' => $status,
            'scheduled_publish_at' => $status === 'scheduled' ? $data['scheduled_publish_at'] : null,
        ]);

        AuditLog::log('CREATE_EVENT', [
            'event_id' => $event->id,
            'title' => $event->title,
            'publication_status' => $status,
        ]);

        return back()->with('success', 'Event created successfully.');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);

        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $data = $this->validatedData($request);
        $status = $data['publication_status'] ?? $event->publication_status;
        $updates = array_intersect_key($data, array_flip(['title', 'category', 'date', 'time', 'venue', 'description', 'status']));
        $updates['publication_status'] = $status;
        $updates['scheduled_publish_at'] = $status === 'scheduled'
            ? ($data['scheduled_publish_at'] ?? $event->scheduled_publish_at)
            : null;

        if ($request->hasFile('imageFile')) {
            $updates['image'] = '/storage/'.$request->file('imageFile')->store('uploads/events', 'public');
        }

        $event->update($updates);
        AuditLog::log('UPDATE_EVENT', [
            'event_id' => $event->id,
            'title' => $event->title,
            'publication_status' => $status,
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function bulk(Request $request)
    {
        $data = $request->validate([
            'ids' => 'required|array|min:1|max:100',
            'ids.*' => 'required|integer|distinct|exists:events,id',
            'publication_status' => ['required', Rule::in(self::PUBLICATION_STATUSES)],
            'scheduled_publish_at' => 'required_if:publication_status,scheduled|nullable|date|after:now',
        ]);

        DB::transaction(function () use ($data) {
            $events = Event::whereIn('id', $data['ids'])->orderBy('id')->lockForUpdate()->get();
            foreach ($events as $event) {
                $event->update([
                    'publication_status' => $data['publication_status'],
                    'scheduled_publish_at' => $data['publication_status'] === 'scheduled'
                        ? $data['scheduled_publish_at']
                        : null,
                ]);
            }

            AuditLog::log('BULK_UPDATE_EVENTS', [
                'event_ids' => $events->modelKeys(),
                'publication_status' => $data['publication_status'],
            ]);
        });

        return back()->with('success', 'Selected events updated.');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        AuditLog::log('DELETE_EVENT', ['event_id' => $event->id, 'title' => $event->title]);
        $event->delete();

        return back()->with('success', 'Event deleted.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'date' => 'required|date',
            'time' => 'nullable|string|max:100',
            'venue' => 'required|string|max:255',
            'description' => 'required|string|max:50000',
            'status' => ['required', Rule::in(['Upcoming', 'Ongoing', 'Completed'])],
            'imageFile' => 'nullable|image|max:5120',
            'publication_status' => ['sometimes', Rule::in(self::PUBLICATION_STATUSES)],
            'scheduled_publish_at' => 'required_if:publication_status,scheduled|nullable|date|after:now',
        ]);
    }
}
