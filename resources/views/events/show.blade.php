@extends('layouts.app')

@section('title', $event->title . ' | KSO Chandigarh Event')

@section('content')

<div class="event-detail-page">
<div class="public-page-banner bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold mb-2">{{ $event->category }}</span>
        <h1 class="fw-black mb-1">{{ $event->title }}</h1>
        <p class="small text-light opacity-90 mb-0"><i class="fa-solid fa-location-dot me-1"></i> {{ $event->venue }}</p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-5">
        <div class="col-lg-7">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                <x-event-image :event="$event" variant="detail" class="rounded-3 mb-4" />
                
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

                <form action="{{ route('events.registerAttendee', $event->id) }}" method="POST" id="eventRegistrationForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="event-full-name">Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="event-full-name" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" autocomplete="name" maxlength="255" @error('full_name') aria-invalid="true" aria-describedby="event-full-name-error" @enderror placeholder="e.g. Seinthang Haokip" required>
                        @error('full_name')<div class="invalid-feedback" id="event-full-name-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="event-email">Email Address <span class="text-danger">*</span></label>
                        <input type="email" id="event-email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" autocomplete="email" @error('email') aria-invalid="true" aria-describedby="event-email-error" @enderror placeholder="name@gmail.com" required>
                        @error('email')<div class="invalid-feedback" id="event-email-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="event-phone">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" id="event-phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" autocomplete="tel" @error('phone') aria-invalid="true" aria-describedby="event-phone-error" @enderror placeholder="+91 9876543210" required>
                        @error('phone')<div class="invalid-feedback" id="event-phone-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="event-institution">College / Institution <span class="text-danger">*</span></label>
                        <input type="text" id="event-institution" name="institution" class="form-control @error('institution') is-invalid @enderror" value="{{ old('institution') }}" autocomplete="organization" @error('institution') aria-invalid="true" aria-describedby="event-institution-error" @enderror placeholder="e.g. Panjab University" required>
                        @error('institution')<div class="invalid-feedback" id="event-institution-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold" for="event-member-id">KSO Member ID (Optional)</label>
                        <input type="text" id="event-member-id" name="member_id" class="form-control text-uppercase @error('member_id') is-invalid @enderror" value="{{ old('member_id') }}" autocomplete="off" @error('member_id') aria-invalid="true" aria-describedby="event-member-id-error" @enderror placeholder="KSO-CHD-2026-0001">
                        @error('member_id')<div class="invalid-feedback" id="event-member-id-error">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-accent btn-lg w-100 fw-bold shadow">
                        <i class="fa-solid fa-check-circle me-2" aria-hidden="true"></i><span id="event-registration-label">Confirm RSVP Registration</span>
                    </button>
                    <span id="event-registration-status" class="visually-hidden" role="status" aria-live="polite"></span>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
