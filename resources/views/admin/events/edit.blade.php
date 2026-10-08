@extends('layouts.admin')

@section('title', 'Edit Event - ' . $event->title)

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

    <section class="card border-0 shadow-sm rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <h1 class="h4 fw-bold text-primary mb-0"><i class="fa-solid fa-calendar-pen me-2" aria-hidden="true"></i>Edit Scheduled Event</h1>
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i>Back to List</a>
        </div>

        <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data" data-event-form>
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold" for="eventTitle">Event Title</label>
                    <input type="text" id="eventTitle" name="title" class="form-control" value="{{ old('title', $event->title) }}" maxlength="255" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold" for="eventCategory">Category</label>
                    <select id="eventCategory" name="category" class="form-select" required>
                        @if(!in_array(old('category', $event->category), ['Cultural', 'Sports', 'Academic', 'Social Service'], true))
                            <option value="{{ old('category', $event->category) }}" selected>{{ old('category', $event->category) }}</option>
                        @endif
                        @foreach(['Cultural', 'Sports', 'Academic', 'Social Service'] as $category)
                            <option value="{{ $category }}" @selected(old('category', $event->category) === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold" for="eventDate">Date</label>
                    <input type="date" id="eventDate" name="date" class="form-control" value="{{ old('date', $event->date?->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold" for="eventTime">Time</label>
                    <input type="text" id="eventTime" name="time" class="form-control" value="{{ old('time', $event->time) }}" maxlength="100">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold" for="eventStatus">Event status</label>
                    <select id="eventStatus" name="status" class="form-select" required>
                        @foreach(['Upcoming', 'Ongoing', 'Completed'] as $eventStatus)
                            <option value="{{ $eventStatus }}" @selected(old('status', $event->status) === $eventStatus)>{{ $eventStatus }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold" for="eventVenue">Venue</label>
                    <input type="text" id="eventVenue" name="venue" class="form-control" value="{{ old('venue', $event->venue) }}" maxlength="255" required>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold" for="eventDescription">Description</label>
                    <textarea id="eventDescription" name="description" class="form-control" rows="5" maxlength="50000" required>{{ old('description', $event->description) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="eventImage">Event Poster / Image</label>
                    <input type="file" id="eventImage" name="imageFile" class="form-control" accept="image/png,image/jpeg,image/gif,image/webp,image/avif,image/bmp">
                    <div class="form-text">Optional replacement image, up to 5 MB.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="eventPublicationStatus">Editorial status</label>
                    <select id="eventPublicationStatus" name="publication_status" class="form-select" data-schedule-status>
                        @foreach(['published' => 'Published', 'draft' => 'Draft', 'review' => 'In review', 'scheduled' => 'Scheduled'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('publication_status', $event->publication_status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="eventPublishAt">Publish at (scheduled only)</label>
                    <input type="datetime-local" id="eventPublishAt" name="scheduled_publish_at" value="{{ old('scheduled_publish_at', $event->scheduled_publish_at?->format('Y-m-d\TH:i')) }}" class="form-control" data-schedule-time>
                </div>
                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2" aria-hidden="true"></i>Update Event</button>
                </div>
            </div>
        </form>
    </section>
</div>
@endsection
