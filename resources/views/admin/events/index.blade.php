@extends('layouts.admin')

@section('title', 'Events Management | KSO CMS')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3">
                <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-calendar-days me-2"></i> Events</h5>
                <form action="{{ route('admin.events.index') }}" method="GET" class="row g-2">
                    <div class="col-md-6"><label for="event-search" class="visually-hidden">Search events</label><input id="event-search" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search title, venue, or description"></div>
                    <div class="col-md-4">
                        <label for="event-publication-status" class="visually-hidden">Filter editorial status</label>
                        <select id="event-publication-status" name="publication_status" class="form-select form-select-sm">
                            @foreach(['all' => 'All editorial statuses', 'draft' => 'Draft', 'review' => 'In review', 'scheduled' => 'Scheduled', 'published' => 'Published'] as $value => $label)
                                <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2"><button class="btn btn-sm btn-outline-primary w-100">Filter</button></div>
                </form>
            </div>
            <form action="{{ route('admin.events.bulk') }}" method="POST">
                @csrf
                <div class="d-flex flex-wrap align-items-center gap-2 p-3 border-bottom">
                    <label for="events-bulk-status" class="small fw-bold">Selected:</label>
                    <select id="events-bulk-status" name="publication_status" class="form-select form-select-sm" style="max-width:150px" required>
                        <option value="">Set status</option>
                        @foreach(['draft' => 'Draft', 'review' => 'In review', 'published' => 'Published', 'scheduled' => 'Scheduled'] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <input type="datetime-local" name="scheduled_publish_at" class="form-control form-control-sm" style="max-width:210px" aria-label="Scheduled publish time">
                    <button class="btn btn-sm btn-outline-primary">Apply</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 extra-small">
                            <thead class="table-light">
                                <tr><th><input type="checkbox" id="select-all-events" aria-label="Select all events"></th><th>Title</th><th>Category</th><th>Date & Time</th><th>Venue</th><th>Event status</th><th>Editorial</th><th>Actions</th></tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="{{ $event->id }}" class="event-selection" aria-label="Select {{ $event->title }}"></td>
                                        <td class="fw-bold text-dark">{{ $event->title }}</td>
                                        <td><span class="badge bg-primary-lt text-primary">{{ $event->category }}</span></td>
                                        <td>{{ $event->date?->format('Y-m-d') }} ({{ $event->time }})</td>
                                        <td>{{ $event->venue }}</td>
                                        <td><span class="badge {{ $event->status === 'Upcoming' ? 'bg-success' : 'bg-secondary' }}">{{ $event->status }}</span></td>
                                        <td>
                                            <span class="badge {{ $event->publication_status === 'published' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($event->publication_status) }}</span>
                                            @if($event->scheduled_publish_at)<small class="d-block">{{ $event->scheduled_publish_at->format('Y-m-d H:i') }}</small>@endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit {{ $event->title }}"><i class="fa-solid fa-pen"></i></a>
                                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete event?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" aria-label="Delete {{ $event->title }}"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center text-muted py-4">No events match these filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">{{ $events->links() }}</div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-plus-circle me-2"></i> Create New Event</h5>
            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3"><label class="form-label fw-bold">Event Title</label><input type="text" name="title" class="form-control" required></div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Category</label>
                    <select name="category" class="form-select" required><option value="Cultural">Cultural</option><option value="Sports">Sports</option><option value="Academic">Academic</option><option value="Social Service">Social Service</option></select>
                </div>
                <div class="mb-3"><label class="form-label fw-bold">Date</label><input type="date" name="date" class="form-control" required></div>
                <div class="mb-3"><label class="form-label fw-bold">Time</label><input type="text" name="time" class="form-control" placeholder="e.g. 10:00 AM - 04:00 PM"></div>
                <div class="mb-3"><label class="form-label fw-bold">Venue</label><input type="text" name="venue" class="form-control" placeholder="Auditorium / Ground Name" required></div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Event status</label>
                    <select name="status" class="form-select"><option value="Upcoming">Upcoming</option><option value="Completed">Completed</option></select>
                </div>
                <div class="mb-3"><label class="form-label fw-bold">Description</label><textarea name="description" class="form-control" rows="3" required></textarea></div>
                <div class="mb-3"><label class="form-label fw-bold">Poster / Image</label><input type="file" name="imageFile" class="form-control" accept="image/*"></div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Editorial status</label>
                    <select name="publication_status" class="form-select">
                        @foreach(['published' => 'Publish now', 'draft' => 'Draft', 'review' => 'In review', 'scheduled' => 'Scheduled'] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3"><label class="form-label fw-bold">Publish at (scheduled only)</label><input type="datetime-local" name="scheduled_publish_at" class="form-control"></div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Save Event</button>
            </form>
        </div>
    </div>
</div>
<script>
document.getElementById('select-all-events')?.addEventListener('change', event => {
    document.querySelectorAll('.event-selection').forEach(input => input.checked = event.target.checked);
});
</script>
@endsection
