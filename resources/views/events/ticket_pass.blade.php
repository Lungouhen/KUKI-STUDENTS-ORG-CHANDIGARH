@extends('layouts.app')

@section('title', 'Event Entry Pass | KSO Chandigarh')

@section('content')

<div class="event-pass-page">
<div class="container my-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 p-4 text-start bg-white border-top border-4 border-primary" id="ticketPrintArea">
                <div class="text-center mb-3 border-bottom pb-3">
                    <img src="{{ asset('images/kso-logo.jpg') }}" alt="KSO Chandigarh emblem" class="rounded-circle border border-warning mb-2" width="50" height="50" onerror="this.src='/images/default-avatar-m.png'">
                    <h5 class="fw-bold text-primary mb-0">KSO CHANDIGARH</h5>
                    <p class="text-warning fw-bold extra-small mb-0">Official Event Entry Pass</p>
                </div>

                <div class="badge bg-primary text-white p-2 w-100 text-center fw-bold fs-6 mb-3">
                    {{ $registration->event->title }}
                </div>

                <table class="table table-sm table-borderless extra-small mb-3">
                    <caption class="visually-hidden">Event pass details</caption>
                    <tr><th class="text-muted">Pass Code:</th><td class="fw-bold text-danger fs-6">{{ $registration->ticket_code }}</td></tr>
                    <tr><th class="text-muted">Attendee:</th><td class="fw-bold text-dark">{{ $registration->full_name }}</td></tr>
                    <tr><th class="text-muted">College:</th><td>{{ $registration->institution }}</td></tr>
                    <tr><th class="text-muted">Phone:</th><td>{{ $registration->phone }}</td></tr>
                    <tr><th class="text-muted">Date & Time:</th><td>{{ $registration->event->date ? $registration->event->date->format('Y-m-d') : '' }} ({{ $registration->event->time ?? '10:00 AM' }})</td></tr>
                    <tr><th class="text-muted">Venue:</th><td>{{ $registration->event->venue }}</td></tr>
                </table>

                <div class="event-pass-instructions text-center p-3 bg-light rounded border">
                    <i class="fa-solid fa-ticket fs-2 text-primary" aria-hidden="true"></i>
                    <div class="extra-small text-muted mt-1">Present your pass code at the venue entry desk.</div>
                </div>
            </div>

            <div class="mt-4">
                <button type="button" id="printEventPass" class="btn btn-accent btn-lg rounded-pill px-5 fw-bold shadow">
                    <i class="fa-solid fa-print me-2" aria-hidden="true"></i> Print Event Pass
                </button>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
