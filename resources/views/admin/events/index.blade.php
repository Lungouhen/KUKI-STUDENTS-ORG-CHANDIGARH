@extends('layouts.admin')

@section('title', 'Events Management | KSO CMS')

@section('content')

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3">
                <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-calendar-days me-2"></i> All Scheduled Events</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 extra-small">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Date & Time</th>
                                <th>Venue</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $e)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $e->title }}</td>
                                    <td><span class="badge bg-primary-lt text-primary">{{ $e->category }}</span></td>
                                    <td>{{ $e->date ? $e->date->format('Y-m-d') : '' }} ({{ $e->time }})</td>
                                    <td>{{ $e->venue }}</td>
                                    <td><span class="badge {{ $e->status === 'Upcoming' ? 'bg-success' : 'bg-secondary' }}">{{ $e->status }}</span></td>
                                    <td>
                                        <form action="{{ route('admin.events.destroy', $e->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete event?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-plus-circle me-2"></i> Create New Event</h5>
            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Event Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="Cultural">Cultural</option>
                        <option value="Sports">Sports</option>
                        <option value="Academic">Academic</option>
                        <option value="Social Service">Social Service</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Time</label>
                    <input type="text" name="time" class="form-control" placeholder="e.g. 10:00 AM - 04:00 PM">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Venue</label>
                    <input type="text" name="venue" class="form-control" placeholder="Auditorium / Ground Name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="Upcoming">Upcoming</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Poster / Image</label>
                    <input type="file" name="imageFile" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Save Event</button>
            </form>
        </div>
    </div>
</div>

@endsection
