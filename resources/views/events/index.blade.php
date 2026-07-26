@extends('layouts.app')

@section('title', 'Events & News | KSO Chandigarh')

@section('content')

<div class="bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h2 class="fw-black mb-1">Events & Official Announcements</h2>
        <p class="small text-light opacity-90 mb-0">Stay connected with upcoming cultural meets, sports, and press releases</p>
    </div>
</div>

<div class="container my-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div class="btn-group" role="group">
            <a href="{{ route('events.index', ['category' => 'All']) }}" class="btn {{ !$category || $category == 'All' ? 'btn-primary active' : 'btn-outline-primary' }}">All Events</a>
            <a href="{{ route('events.index', ['category' => 'Cultural']) }}" class="btn {{ $category == 'Cultural' ? 'btn-primary active' : 'btn-outline-primary' }}">Cultural</a>
            <a href="{{ route('events.index', ['category' => 'Sports']) }}" class="btn {{ $category == 'Sports' ? 'btn-primary active' : 'btn-outline-primary' }}">Sports</a>
            <a href="{{ route('events.index', ['category' => 'Academic']) }}" class="btn {{ $category == 'Academic' ? 'btn-primary active' : 'btn-outline-primary' }}">Academic</a>
            <a href="{{ route('events.index', ['category' => 'Social Service']) }}" class="btn {{ $category == 'Social Service' ? 'btn-primary active' : 'btn-outline-primary' }}">Social Service</a>
        </div>
    </div>

    <div class="row g-4">
        @forelse($events as $e)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white hover-lift">
                    <img src="{{ asset($e->image ?? '/images/event-freshers.jpg') }}" class="card-img-top" style="height: 180px; object-fit: cover;" onerror="this.src='/images/event-freshers.jpg'">
                    <div class="card-body p-3 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary-lt text-primary extra-small fw-bold">{{ $e->category }}</span>
                            <span class="badge {{ $e->status === 'Upcoming' ? 'bg-success' : 'bg-secondary' }} extra-small">{{ $e->status }}</span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-2">{{ $e->title }}</h5>
                        <p class="card-text text-muted extra-small mb-3">{{ $e->description }}</p>
                        <div class="mt-auto pt-2 border-top extra-small text-secondary">
                            <div class="mb-1"><i class="fa-solid fa-calendar-day text-primary me-1"></i> <strong>Date:</strong> {{ $e->date ? $e->date->format('Y-m-d') : '' }} ({{ $e->time ?? '10:00 AM' }})</div>
                            <div><i class="fa-solid fa-location-dot text-danger me-1"></i> <strong>Venue:</strong> {{ $e->venue }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-4">No events found in this category.</div>
        @endforelse
    </div>

    <div class="mt-5">
        <h3 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="fa-solid fa-newspaper text-danger me-2"></i> All Press Notices & News</h3>
        <div class="row g-3">
            @foreach($news as $n)
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-danger text-white extra-small">{{ $n->category }}</span>
                            <small class="text-muted extra-small"><i class="fa-solid fa-calendar me-1"></i> {{ $n->date ? $n->date->format('Y-m-d') : '' }}</small>
                        </div>
                        <h5 class="fw-bold text-dark mb-2">{{ $n->title }}</h5>
                        <p class="text-secondary extra-small mb-2">{{ $n->content }}</p>
                        <small class="text-primary fw-bold extra-small mt-auto"><i class="fa-solid fa-user-pen me-1"></i> {{ $n->author }}</small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
