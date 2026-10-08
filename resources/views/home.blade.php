@extends('layouts.app')

@section('title', 'Kuki Students\' Organisation Chandigarh | Official Website')

@section('content')

<div class="home-page">
<!-- Hero Section -->
<div class="hero-section position-relative py-5">
    <div class="container position-relative py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7 hero-copy">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-3">
                    <i class="fa-solid fa-graduation-cap me-1" aria-hidden="true"></i> {{ "KUKI STUDENTS' ORGANISATION CHANDIGARH" }} · Student community
                </span>
                <h1 class="display-4 fw-black mb-3">Kuki students, stronger together.</h1>
                <p class="lead mb-4">
                    Uniting, empowering, and guiding Kuki students across educational institutions in Chandigarh, Mohali, and Panchkula.
                </p>
                <div class="d-flex flex-wrap gap-3 hero-actions">
                    <a href="{{ route('membership.register') }}" class="btn btn-accent btn-lg shadow-lg">
                        <i class="fa-solid fa-user-plus me-2" aria-hidden="true"></i> Become a Member
                    </a>
                    <a href="{{ route('membership.verifyForm') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                        <i class="fa-solid fa-qrcode me-2" aria-hidden="true"></i> Verify ID Card
                    </a>
                    <a href="{{ route('events.index') }}" class="btn btn-teal text-white rounded-pill btn-lg px-4">
                        <i class="fa-solid fa-calendar-check me-2" aria-hidden="true"></i> View Events
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                @php($featuredPhoto = $galleryHighlights->first())
                <figure class="home-hero-visual mb-0">
                    @if($featuredPhoto)
                        <img src="{{ asset($featuredPhoto->image_url) }}" alt="{{ $featuredPhoto->mediaAsset?->alt_text ?: ($featuredPhoto->caption ?: $featuredPhoto->title) }}" fetchpriority="high" onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                        <div class="home-hero-art" hidden aria-hidden="true">
                            <span class="home-hero-art-mark">KSO</span>
                            <i class="fa-solid fa-users"></i>
                            <span class="home-hero-art-caption">Students supporting students</span>
                        </div>
                        <figcaption>
                            <span class="small text-uppercase fw-bold">From our community</span>
                            <strong>{{ $featuredPhoto->title }}</strong>
                        </figcaption>
                    @else
                        <div class="home-hero-art" aria-hidden="true">
                            <span class="home-hero-art-mark">KSO</span>
                            <i class="fa-solid fa-users"></i>
                            <span class="home-hero-art-caption">Students supporting students</span>
                        </div>
                    @endif
                </figure>
            </div>
        </div>
    </div>
</div>

<!-- Stats Bar -->
<div class="container my-5">
    <div class="row g-4 text-center">
        <div class="col-md-3 col-6">
            <div class="stat-card p-4 bg-white shadow-sm border border-light text-center">
                <div class="icon-circle bg-primary-lt mx-auto mb-3 text-primary fs-3">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h3 class="fw-bold text-primary mb-1" aria-label="{{ number_format($stats['membersCount']) }}+ active members"><span class="counter" data-counter-target="{{ $stats['membersCount'] }}" aria-hidden="true">{{ $stats['membersCount'] }}</span><span aria-hidden="true">+</span></h3>
                <p class="text-muted small mb-0 fw-semibold">Active Members</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card p-4 bg-white shadow-sm border border-light text-center">
                <div class="icon-circle bg-success-lt mx-auto mb-3 text-success fs-3">
                    <i class="fa-solid fa-university"></i>
                </div>
                <h3 class="fw-bold text-success mb-1" aria-label="{{ number_format($stats['collegesCount']) }}+ colleges and universities"><span class="counter" data-counter-target="{{ $stats['collegesCount'] }}" aria-hidden="true">{{ $stats['collegesCount'] }}</span><span aria-hidden="true">+</span></h3>
                <p class="text-muted small mb-0 fw-semibold">Colleges & Universities</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card p-4 bg-white shadow-sm border border-light text-center">
                <div class="icon-circle bg-warning-lt mx-auto mb-3 text-warning fs-3">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <h3 class="fw-bold text-dark mb-1" aria-label="{{ number_format($stats['eventsCount']) }}+ annual events held"><span class="counter" data-counter-target="{{ $stats['eventsCount'] }}" aria-hidden="true">{{ $stats['eventsCount'] }}</span><span aria-hidden="true">+</span></h3>
                <p class="text-muted small mb-0 fw-semibold">Annual Events Held</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card p-4 bg-white shadow-sm border border-light text-center">
                <div class="icon-circle bg-danger-lt mx-auto mb-3 text-danger fs-3">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <h3 class="fw-bold text-danger mb-1">24/7</h3>
                <p class="text-muted small mb-0 fw-semibold">Emergency Helpline</p>
            </div>
        </div>
    </div>
</div>

@if($galleryHighlights->count() > 2)
<section class="container my-5 public-gallery-preview" aria-labelledby="community-gallery-heading">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <span class="section-eyebrow">Life at KSO</span>
            <h2 id="community-gallery-heading" class="fw-bold text-primary mb-1">Moments from our community</h2>
            <p class="text-muted mb-0">Shared by students and organizers in Chandigarh.</p>
        </div>
        <a href="{{ route('gallery.index') }}" class="btn btn-outline-primary rounded-pill px-4">
            Visit the gallery <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i>
        </a>
    </div>
    <div class="row g-4">
        @foreach($galleryHighlights->slice(2, 3) as $photo)
            <div class="col-sm-6 col-lg-4">
                <figure class="community-photo-card mb-0">
                    <img src="{{ asset($photo->image_url) }}" alt="{{ $photo->mediaAsset?->alt_text ?: ($photo->caption ?: $photo->title) }}" loading="lazy" onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                    <div class="community-photo-fallback" hidden aria-hidden="true"><i class="fa-solid fa-users"></i><span>Community moments</span></div>
                    <figcaption>
                        <span>{{ $photo->category }}</span>
                        <strong>{{ $photo->title }}</strong>
                    </figcaption>
                </figure>
            </div>
        @endforeach
    </div>
</section>
@endif

<!-- About Brief -->
<div class="container my-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <div class="pe-lg-3">
                <span class="badge bg-teal text-white px-3 py-2 rounded-pill mb-2 fw-semibold" style="background:#0d9488;">WHO WE ARE</span>
                <h2 class="display-6 fw-bold text-primary mb-3">Serving the Student Community in the City Beautiful</h2>
                <p class="text-secondary leading-relaxed">
                    The <strong>Kuki Students' Organisation (KSO) Chandigarh</strong> is the apex non-governmental, non-profit student body representing and supporting student scholars from Manipur and the North-East studying across colleges, institutes, and Panjab University in Chandigarh.
                </p>
                <p class="text-secondary leading-relaxed">
                    We provide admission guidance, hostel accommodation support, legal/medical welfare assistance, cultural promotion, and academic mentorship for students pursuing higher education.
                </p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-circle-check text-success fs-4 me-2"></i>
                        <span class="fw-bold text-dark">Verified Student ID Cards</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-circle-check text-success fs-4 me-2"></i>
                        <span class="fw-bold text-dark">Hostel & Accommodation Desk</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-circle-check text-success fs-4 me-2"></i>
                        <span class="fw-bold text-dark">24/7 Emergency Relief Cell</span>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('about') }}" class="btn btn-primary rounded-pill px-4">
                        Learn More About Us <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            @php($communityPhoto = $galleryHighlights->get(1))
            @if($communityPhoto)
                <figure class="community-feature-photo mb-0">
                    <img src="{{ asset($communityPhoto->image_url) }}" alt="{{ $communityPhoto->mediaAsset?->alt_text ?: ($communityPhoto->caption ?: $communityPhoto->title) }}" loading="lazy" onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                    <div class="community-photo-fallback" hidden aria-hidden="true"><i class="fa-solid fa-people-group"></i><span>Community moments</span></div>
                    <figcaption>{{ $communityPhoto->title }}</figcaption>
                </figure>
            @else
                <div class="community-feature-card">
                    <span class="section-eyebrow">Here for one another</span>
                    <i class="fa-solid fa-hand-holding-heart" aria-hidden="true"></i>
                    <h3>Support that feels like community.</h3>
                    <p>From guidance and welfare support to cultural connection, students can find a place to belong.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Leadership Preview -->
<div class="bg-white py-5 border-top border-bottom">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary-lt text-primary px-3 py-2 rounded-pill fw-bold mb-2">LEADERSHIP</span>
            <h2 class="display-6 fw-bold text-dark">Executive Committee (2025 - 2026)</h2>
            <p class="text-muted">Dedicated student leaders committed to serving and guiding the student body.</p>
        </div>

        <div class="row g-4 justify-content-center">
            @foreach($committee as $c)
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-3 hover-lift bg-white">
                        <img src="{{ asset($c->photo ?? '/images/default-avatar-m.png') }}" class="rounded-circle mx-auto mb-3 border border-2 border-primary" style="width: 80px; height: 80px; object-fit: cover;" onerror="this.src='/images/default-avatar-m.png'">
                        <h6 class="fw-bold text-dark mb-1">{{ $c->name }}</h6>
                        <span class="badge bg-teal text-white rounded-pill px-2 py-1 extra-small mb-2" style="background:#0d9488;">{{ $c->designation }}</span>
                        <p class="text-muted extra-small mb-0"><i class="fa-solid fa-graduation-cap me-1"></i> {{ $c->institution }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('about') }}" class="btn btn-outline-primary rounded-pill px-4">
                View Full Executive Body <i class="fa-solid fa-users ms-1"></i>
            </a>
        </div>
    </div>
</div>

<!-- Upcoming Events & News Highlights -->
<div class="container my-5">
    <div class="row g-5">
        <div class="col-lg-7">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <span class="badge bg-warning-lt text-dark px-3 py-1 rounded-pill fw-bold">CALENDAR</span>
                    <h3 class="fw-bold text-primary mb-0 mt-1">Upcoming Events</h3>
                </div>
                <a href="{{ route('events.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
            </div>
            <div class="row g-3">
                @foreach($upcomingEvents as $e)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden d-flex flex-row hover-lift bg-white">
                            <x-event-image :event="$e" variant="compact" class="d-none d-sm-flex" />
                            <div class="p-3 flex-grow-1">
                                <span class="badge bg-primary-lt text-primary extra-small fw-bold mb-1">{{ $e->category }}</span>
                                <h6 class="fw-bold text-dark mb-1">{{ $e->title }}</h6>
                                <div class="text-muted extra-small mb-2"><i class="fa-solid fa-calendar me-1"></i> {{ $e->date ? $e->date->format('Y-m-d') : '' }} • <i class="fa-solid fa-location-dot me-1"></i> {{ $e->venue }}</div>
                                <a href="{{ route('events.index') }}" class="btn btn-sm btn-outline-primary py-0 px-2 extra-small">View Event Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-lg-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <span class="badge bg-danger-lt text-danger px-3 py-1 rounded-pill fw-bold">UPDATES</span>
                    <h3 class="fw-bold text-dark mb-0 mt-1">Notices & News</h3>
                </div>
                <a href="{{ route('events.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">Archive</a>
            </div>
            <div class="list-group shadow-sm border-0">
                @foreach($latestNews as $n)
                    <div class="list-group-item list-group-item-action p-3 border-0 border-bottom">
                        <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                            <span class="badge bg-danger text-white extra-small">{{ $n->category }}</span>
                            <small class="text-muted extra-small"><i class="fa-solid fa-clock me-1"></i> {{ $n->date ? $n->date->format('Y-m-d') : '' }}</small>
                        </div>
                        <h6 class="mb-1 fw-bold text-dark fs-6">{{ $n->title }}</h6>
                        <p class="mb-1 text-secondary extra-small text-truncate" style="max-width: 320px;">{{ $n->content }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Institutional Affiliations Marquee -->
<div class="bg-primary text-white py-4 my-5">
    <div class="container text-center mb-3">
        <span class="text-uppercase extra-small tracking-wider text-light opacity-75">Representing Students Across Leading Institutions in Tricity</span>
    </div>
    <div class="marquee-container" role="group" aria-label="Institutions represented by KSO students">
        <div class="marquee-content d-flex align-items-center gap-5 fs-6 fw-bold">
            <span><i class="fa-solid fa-graduation-cap me-2 text-warning"></i> Panjab University (PU) Sector 14</span>
            <span><i class="fa-solid fa-graduation-cap me-2 text-warning"></i> MCM DAV College Sector 36</span>
            <span><i class="fa-solid fa-graduation-cap me-2 text-warning"></i> DAV College Sector 10</span>
            <span><i class="fa-solid fa-graduation-cap me-2 text-warning"></i> PGGC Sector 11</span>
            <span><i class="fa-solid fa-graduation-cap me-2 text-warning"></i> Punjab Engineering College (PEC)</span>
            <span><i class="fa-solid fa-graduation-cap me-2 text-warning"></i> GGDSD College Sector 32</span>
            <span><i class="fa-solid fa-graduation-cap me-2 text-warning"></i> GMCH Sector 32</span>
            <span><i class="fa-solid fa-graduation-cap me-2 text-warning"></i> PGIMER Chandigarh</span>
        </div>
    </div>
</div>

</div>
@endsection
