@extends('layouts.admin')

@section('title', 'Edit Event - ' . $event->title)

@section('content')

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h4 class="fw-bold text-primary mb-0"><i class="fa-solid fa-calendar-pen me-2"></i> Edit Scheduled Event</h4>
        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back to List</a>
    </div>

    <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-bold">Event Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Category</label>
                <select name="category" class="form-select" required>
                    <option value="Cultural" {{ $event->category == 'Cultural' ? 'selected' : '' }}>Cultural</option>
                    <option value="Sports" {{ $event->category == 'Sports' ? 'selected' : '' }}>Sports</option>
                    <option value="Academic" {{ $event->category == 'Academic' ? 'selected' : '' }}>Academic</option>
                    <option value="Social Service" {{ $event->category == 'Social Service' ? 'selected' : '' }}>Social Service</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Date</label>
                <input type="date" name="date" class="form-control" value="{{ $event->date ? $event->date->format('Y-m-d') : '' }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Time</label>
                <input type="text" name="time" class="form-control" value="{{ old('time', $event->time) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Status</label>
                <select name="status" class="form-select">
                    <option value="Upcoming" {{ $event->status == 'Upcoming' ? 'selected' : '' }}>Upcoming</option>
                    <option value="Ongoing" {{ $event->status == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="Completed" {{ $event->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Venue</label>
                <input type="text" name="venue" class="form-control" value="{{ old('venue', $event->venue) }}" required>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Description</label>
                <textarea name="description" class="form-control" rows="4" required>{{ old('description', $event->description) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Event Poster / Image</label>
                <input type="file" name="imageFile" class="form-control" accept="image/*">
            </div>
            <div class="col-12 mt-4 text-end">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2"></i> Update Event</button>
            </div>
        </div>
    </form>
</div>

@endsection
