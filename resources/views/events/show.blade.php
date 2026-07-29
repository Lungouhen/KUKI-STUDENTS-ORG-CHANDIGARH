@extends('layouts.app')

@section('title', $event->title . ' | KSO Chandigarh Event')

@push('styles')
    @include('partials.vendor-styles', ['libs' => ['magnific-popup']])
@endpush

@section('content')

<div class="bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2">{{ $event->category }}</span>
        <h2 class="fw-black mb-1">{{ $event->title }}</h2>
        <p class="small text-light opacity-90 mb-0"><i class="fa-solid fa-location-dot me-1"></i> {{ $event->venue }}</p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-5">
        <div class="col-lg-7">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                {{-- Click the poster to open it full-size in Magnific Popup. --}}
                <a href="{{ asset($event->image ?? '/images/event-freshers.jpg') }}"
                   data-mfp="image"
                   data-mfp-title="{{ $event->title }}"
                   class="event-poster d-block mb-4"
                   title="View full-size poster">
                    <img src="{{ asset($event->image ?? '/images/event-freshers.jpg') }}"
                         class="img-fluid rounded-3 w-100"
                         style="max-height: 350px; object-fit: cover;"
                         alt="{{ $event->title }}"
                         onerror="this.src='{{ asset('images/event-freshers.jpg') }}'">
                    <span class="event-poster__hint"><i class="fa-solid fa-magnifying-glass me-1"></i> Click to enlarge</span>
                </a>
                
                <h4 class="fw-bold text-primary mb-3">Event Details</h4>
                <p class="text-secondary leading-relaxed">{{ $event->description }}</p>

                <div class="p-3 bg-light rounded-3 border mt-4">
                    <div class="row g-2 extra-small text-dark">
                        <div class="col-6"><i class="fa-solid fa-calendar text-primary me-2"></i> <strong>Date:</strong> {{ $event->date ? $event->date->format('Y-m-d') : '' }}</div>
                        <div class="col-6"><i class="fa-solid fa-clock text-warning me-2"></i> <strong>Time:</strong> {{ $event->time ?? '10:00 AM' }}</div>
                        <div class="col-12 mt-2"><i class="fa-solid fa-location-dot text-danger me-2"></i> <strong>Venue:</strong> {{ $event->venue }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                <h4 class="fw-bold text-primary mb-3"><i class="fa-solid fa-ticket me-2"></i> RSVP / Register for Event</h4>
                <p class="extra-small text-muted mb-4">Register to receive your official digital event entry pass</p>

                <form action="{{ route('events.registerAttendee', $event->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" class="form-control" placeholder="e.g. Seinthang Haokip" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="name@gmail.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="phone" class="form-control" placeholder="+91 9876543210" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">College / Institution <span class="text-danger">*</span></label>
                        <input type="text" name="institution" class="form-control" placeholder="e.g. Panjab University" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">KSO Member ID (Optional)</label>
                        <input type="text" name="member_id" class="form-control text-uppercase" placeholder="KSO-CHD-2026-0001">
                    </div>
                    <button type="submit" class="btn btn-accent btn-lg w-100 fw-bold shadow">
                        <i class="fa-solid fa-check-circle me-2"></i> Confirm RSVP Registration
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    @include('partials.vendor-scripts', ['libs' => ['magnific-popup']])
@endpush
