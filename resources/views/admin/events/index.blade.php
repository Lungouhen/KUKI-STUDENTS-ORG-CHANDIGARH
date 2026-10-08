@extends('layouts.admin')

@section('title', 'Events Management | KSO CMS')

@section('content')
<div class="admin-events-page">
    @if($errors->any())
        <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="eventErrorsHeading">
            <h2 id="eventErrorsHeading" class="h6 fw-bold">Review the event details</h2>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <section class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white p-3">
                    <h2 class="h5 fw-bold text-primary mb-3"><i class="fa-solid fa-calendar-days me-2" aria-hidden="true"></i>Events</h2>
                    <form action="{{ route('admin.events.index') }}" method="GET" class="row g-2">
                        <div class="col-md-6">
                            <label for="event-search" class="visually-hidden">Search events</label>
                            <input id="event-search" name="q" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search title, venue, or description">
                        </div>
                        <div class="col-md-4">
                            <label for="event-publication-status" class="visually-hidden">Filter editorial status</label>
                            <select id="event-publication-status" name="publication_status" class="form-select form-select-sm">
                                @foreach(['all' => 'All editorial statuses', 'draft' => 'Draft', 'review' => 'In review', 'scheduled' => 'Scheduled', 'published' => 'Published'] as $value => $label)
                                    <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2"><button type="submit" class="btn btn-sm btn-outline-primary w-100">Filter</button></div>
                    </form>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2 p-3 border-bottom">
                    <form action="{{ route('admin.events.bulk') }}" method="POST" id="eventsBulkForm" class="d-flex flex-wrap align-items-center gap-2">
                        @csrf
                        <label for="events-bulk-status" class="small fw-bold">Selected events:</label>
                        <select id="events-bulk-status" name="publication_status" class="form-select form-select-sm" required data-schedule-status>
                            <option value="">Set status</option>
                            @foreach(['draft' => 'Draft', 'review' => 'In review', 'published' => 'Published', 'scheduled' => 'Scheduled'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('publication_status') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <label class="visually-hidden" for="events-bulk-publish-at">Scheduled publish time</label>
                        <input id="events-bulk-publish-at" type="datetime-local" name="scheduled_publish_at" value="{{ old('scheduled_publish_at') }}" class="form-control form-control-sm" aria-describedby="eventsBulkScheduleHint" data-schedule-time>
                        <button type="submit" class="btn btn-sm btn-outline-primary">Apply</button>
                    </form>
                    <span id="eventsBulkScheduleHint" class="visually-hidden">Required only when setting selected events to scheduled.</span>
                    <p class="w-100 small text-danger mb-0" data-events-bulk-error role="alert" hidden>Select at least one event before applying a bulk status.</p>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 extra-small" aria-describedby="eventsTableCaption">
                            <caption id="eventsTableCaption" class="visually-hidden">Events matching the selected search and editorial filters</caption>
                            <thead class="table-light">
                                <tr>
                                    <th scope="col"><input type="checkbox" id="select-all-events" aria-label="Select all events on this page" form="eventsBulkForm"></th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Date &amp; Time</th>
                                    <th scope="col">Venue</th>
                                    <th scope="col">Event status</th>
                                    <th scope="col">Editorial</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($events as $event)
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="{{ $event->id }}" class="event-selection" aria-label="Select {{ $event->title }}" form="eventsBulkForm"></td>
                                        <td class="fw-bold text-dark">{{ $event->title }}</td>
                                        <td><span class="badge bg-primary-lt text-primary">{{ $event->category }}</span></td>
                                        <td>{{ $event->date?->format('Y-m-d') }} ({{ $event->time }})</td>
                                        <td>{{ $event->venue }}</td>
                                        <td><span class="badge {{ $event->status === 'Upcoming' ? 'bg-success' : 'bg-secondary' }}">{{ $event->status }}</span></td>
                                        <td>
                                            <span class="badge {{ $event->publication_status === 'published' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($event->publication_status) }}</span>
                                            @if($event->scheduled_publish_at)<small class="d-block">{{ $event->scheduled_publish_at->format('Y-m-d H:i') }}</small>@endif
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit {{ $event->title }}"><i class="fa-solid fa-pen" aria-hidden="true"></i></a>
                                            <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="d-inline" data-event-delete>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Delete {{ $event->title }}"><i class="fa-solid fa-trash" aria-hidden="true"></i></button>
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
            </section>
        </div>

        <div class="col-lg-4">
            <section class="card border-0 shadow-sm rounded-4 p-4">
                <h2 class="h5 fw-bold text-primary mb-3"><i class="fa-solid fa-plus-circle me-2" aria-hidden="true"></i>Create New Event</h2>
                <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data" data-event-form>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="eventTitle">Event Title</label>
                        <input type="text" id="eventTitle" name="title" class="form-control" value="{{ old('title') }}" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="eventCategory">Category</label>
                        <select id="eventCategory" name="category" class="form-select" required>
                            @foreach(['Cultural', 'Sports', 'Academic', 'Social Service'] as $category)
                                <option value="{{ $category }}" @selected(old('category', 'Cultural') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label fw-bold" for="eventDate">Date</label>
                            <input type="date" id="eventDate" name="date" class="form-control" value="{{ old('date') }}" required>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label fw-bold" for="eventTime">Time</label>
                            <input type="text" id="eventTime" name="time" class="form-control" value="{{ old('time') }}" maxlength="100" placeholder="e.g. 10:00 AM - 04:00 PM">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="eventVenue">Venue</label>
                        <input type="text" id="eventVenue" name="venue" class="form-control" value="{{ old('venue') }}" maxlength="255" placeholder="Auditorium / Ground Name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="eventStatus">Event status</label>
                        <select id="eventStatus" name="status" class="form-select" required>
                            @foreach(['Upcoming', 'Ongoing', 'Completed'] as $eventStatus)
                                <option value="{{ $eventStatus }}" @selected(old('status', 'Upcoming') === $eventStatus)>{{ $eventStatus }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="eventDescription">Description</label>
                        <textarea id="eventDescription" name="description" class="form-control" rows="3" maxlength="50000" required>{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="eventImage">Poster / Image</label>
                        <input type="file" id="eventImage" name="imageFile" class="form-control" accept="image/png,image/jpeg,image/gif,image/webp,image/avif,image/bmp">
                        <div class="form-text">Optional image, up to 5 MB.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="eventPublicationStatus">Editorial status</label>
                        <select id="eventPublicationStatus" name="publication_status" class="form-select" data-schedule-status>
                            @foreach(['published' => 'Publish now', 'draft' => 'Draft', 'review' => 'In review', 'scheduled' => 'Scheduled'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('publication_status', 'published') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="eventPublishAt">Publish at (scheduled only)</label>
                        <input type="datetime-local" id="eventPublishAt" name="scheduled_publish_at" value="{{ old('scheduled_publish_at') }}" class="form-control" data-schedule-time>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Save Event</button>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection
