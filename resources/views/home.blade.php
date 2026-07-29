@extends('layouts.app')

@section('title', 'Kuki Students\' Organisation Chandigarh | Official Website')

@push('styles')
    @include('partials.vendor-styles', ['libs' => ['swiper', 'lightgallery']])
@endpush

@section('content')

@php
    // Static fallback so the hero is never empty before any slide is published.
    $heroCta = '<div class="d-flex flex-wrap justify-content-center gap-3">
        <a href="'.route('membership.register').'" class="btn btn-accent btn-lg shadow-lg">
            <i class="fa-solid fa-user-plus me-2"></i> Become a Member
        </a>
        <a href="'.route('membership.verifyForm').'" class="btn btn-outline-light btn-lg rounded-pill px-4">
            <i class="fa-solid fa-qrcode me-2"></i> Verify ID Card
        </a>
        <a href="'.route('events.index').'" class="btn text-white rounded-pill btn-lg px-4" style="background:#0d9488;">
            <i class="fa-solid fa-calendar-check me-2"></i> View Events
        </a>
    </div>';
@endphp

<!-- ─── Hero (CMS-managed slides via Content → Homepage Slider) ─── -->
@if($slides->isNotEmpty())
    <div class="hero-swiper swiper" data-swiper='{"effect":"fade","fadeEffect":{"crossFade":true},"autoplay":{"delay":6000,"disableOnInteraction":false},"speed":700}'>
        <div class="swiper-wrapper">
            @foreach($slides as $slide)
                <div class="swiper-slide">
                    <div class="hero-slide">
                        @if($slide->image)
                            <img src="{{ asset($slide->image) }}" alt="{{ $slide->title }}"
                                 class="hero-slide__bg"
                                 loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                                 fetchpriority="{{ $loop->first ? 'high' : 'auto' }}">
                        @endif
                        <div class="hero-slide__scrim"></div>
                        <div class="container px-4 position-relative text-center text-white">
                            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-3 shadow">
                                <i class="fa-solid fa-graduation-cap me-1"></i> Official Student Portal • Chandigarh UT
                            </span>
                            <h1 class="display-4 fw-black mb-3">{{ $slide->title }}</h1>
                            @if($slide->content)
                                <p class="lead mb-4 mx-auto" style="max-width: 800px;">{{ $slide->content }}</p>
                            @endif
                            @if($slide->link)
                                <a href="{{ $slide->link }}" class="btn btn-accent btn-lg shadow-lg">
                                    Find Out More <i class="fa-solid fa-arrow-right ms-1"></i>
                                </a>
                            @else
                                {!! $heroCta !!}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($slides->count() > 1)
            <div class="swiper-pagination"></div>
            <button class="swiper-button-prev" type="button" aria-label="Previous slide"></button>
            <button class="swiper-button-next" type="button" aria-label="Next slide"></button>
        @endif
    </div>
@else
    <div class="hero-section text-center text-white position-relative py-5">
        <div class="container px-4 z-1 py-4">
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-3 shadow">
                <i class="fa-solid fa-graduation-cap me-1"></i> Official Student Portal • Chandigarh UT
            </span>
            <h1 class="display-4 fw-black mb-3">KUKI STUDENTS' ORGANISATION CHANDIGARH</h1>
            <p class="lead mb-4 mx-auto style-max-width" style="max-width: 800px;">
                Uniting, Empowering, and Guiding Kuki Students Across Educational Institutions in Chandigarh, Mohali &amp; Panchkula.
            </p>
            {!! $heroCta !!}
        </div>
    </div>
@endif

<!-- Stats Bar -->
<div class="container my-5">
    <div class="row g-4 text-center">
        <div class="col-md-3 col-6">
            <div class="stat-card p-4 bg-white shadow-sm border border-light text-center">
                <div class="icon-circle bg-primary-lt mx-auto mb-3 text-primary fs-3">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h3 class="fw-bold text-primary mb-1"><span data-counter>{{ $stats['membersCount'] }}</span>+</h3>
                <p class="text-muted small mb-0 fw-semibold">Active Members</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card p-4 bg-white shadow-sm border border-light text-center">
                <div class="icon-circle bg-success-lt mx-auto mb-3 text-success fs-3">
                    <i class="fa-solid fa-university"></i>
                </div>
                <h3 class="fw-bold text-success mb-1"><span data-counter>{{ $stats['collegesCount'] }}</span>+</h3>
                <p class="text-muted small mb-0 fw-semibold">Colleges & Universities</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card p-4 bg-white shadow-sm border border-light text-center">
                <div class="icon-circle bg-warning-lt mx-auto mb-3 text-warning fs-3">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <h3 class="fw-bold text-dark mb-1"><span data-counter>{{ $stats['eventsCount'] }}</span>+</h3>
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
            @if($galleryHighlights->isNotEmpty())
                {{-- Click any tile to open the full lightGallery lightbox. --}}
                <div class="home-gallery-grid" data-lightgallery>
                    @foreach($galleryHighlights->take(4) as $g)
                        <a href="{{ asset($g->image_url) }}"
                           class="home-gallery-tile {{ $loop->first ? 'home-gallery-tile--lead' : '' }}"
                           data-lg-item
                           data-sub-html="<h4>{{ e($g->title) }}</h4><p>{{ e($g->category) }}</p>">
                            <img src="{{ asset($g->image_url) }}" alt="{{ $g->title }}" loading="lazy"
                                 onerror="this.src='{{ asset('images/gallery-1.jpg') }}'">
                            <span class="home-gallery-tile__zoom"><i class="fa-solid fa-magnifying-glass"></i></span>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('gallery.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                        Browse Full Gallery <i class="fa-solid fa-images ms-1"></i>
                    </a>
                </div>
            @else
                <div class="position-relative p-3 bg-white rounded-4 shadow border">
                    <img src="{{ asset('images/gallery-1.jpg') }}" class="img-fluid rounded-3 w-100" alt="KSO Event" style="height: 340px; object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 m-4 p-3 bg-dark bg-opacity-75 text-white rounded-3" style="max-width: 80%;">
                        <div class="fw-bold">Chavang Kut &amp; Cultural Extravaganza</div>
                        <div class="small text-warning">Preserving Rich Heritage &amp; Folk Art</div>
                    </div>
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
                            <img src="{{ asset($e->image ?? '/images/event-freshers.jpg') }}" class="d-none d-sm-block" style="width: 140px; object-fit: cover;" onerror="this.src='/images/event-freshers.jpg'">
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

<!-- ─── Testimonials (CMS: Content → Testimonials) ─── -->
@if($testimonials->isNotEmpty())
<div class="bg-white py-5 border-top">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary-lt text-primary px-3 py-2 rounded-pill fw-bold mb-2">VOICES</span>
            <h2 class="display-6 fw-bold text-dark">What Our Students Say</h2>
            <p class="text-muted mb-0">Experiences from members and alumni across the Tricity.</p>
        </div>

        <div class="swiper testimonial-swiper pb-5"
             data-swiper='{"autoplay":{"delay":5500,"disableOnInteraction":false},"breakpoints":{"768":{"slidesPerView":2},"1200":{"slidesPerView":3}}}'>
            <div class="swiper-wrapper">
                @foreach($testimonials as $t)
                    <div class="swiper-slide h-auto">
                        <figure class="testimonial-card h-100">
                            <i class="fa-solid fa-quote-left testimonial-card__mark"></i>
                            <blockquote class="testimonial-card__quote">{{ $t->quote }}</blockquote>
                            @if($t->rating)
                                <div class="testimonial-card__rating" aria-label="{{ $t->rating }} out of 5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= $t->rating ? '' : 'is-muted' }}"></i>
                                    @endfor
                                </div>
                            @endif
                            <figcaption class="testimonial-card__author">
                                <img src="{{ asset($t->photo ?: '/images/default-avatar-m.png') }}" alt=""
                                     onerror="this.src='{{ asset('images/default-avatar-m.png') }}'">
                                <span>
                                    <strong>{{ $t->author_name }}</strong>
                                    <small>{{ $t->author_title }}{{ $t->college_name ? ' • '.$t->college_name : '' }}</small>
                                </span>
                            </figcaption>
                        </figure>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</div>
@endif

<!-- ─── Partners & Supporters (CMS: Programmes → Partners) ─── -->
@if($partners->isNotEmpty())
<div class="container my-5">
    <div class="text-center mb-4">
        <span class="badge bg-teal text-white px-3 py-2 rounded-pill fw-bold mb-2" style="background:#0d9488;">COLLABORATIONS</span>
        <h2 class="display-6 fw-bold text-dark">Partners &amp; Supporters</h2>
    </div>

    <div class="swiper partner-swiper"
         data-swiper='{"slidesPerView":2,"spaceBetween":16,"autoplay":{"delay":2800,"disableOnInteraction":false},"pagination":false,"breakpoints":{"576":{"slidesPerView":3},"992":{"slidesPerView":5}}}'>
        <div class="swiper-wrapper">
            @foreach($partners as $p)
                <div class="swiper-slide">
                    <div class="partner-chip" title="{{ $p->name }}">
                        <i class="fa-solid fa-handshake"></i>
                        <span>{{ $p->name }}</span>
                        <small>{{ $p->category }}</small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Institutional Affiliations Marquee -->
<div class="bg-primary text-white py-4 my-5">
    <div class="container text-center mb-3">
        <span class="text-uppercase extra-small tracking-wider text-light opacity-75">Representing Students Across Leading Institutions in Tricity</span>
    </div>
    <div class="marquee-container">
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

@endsection

@push('scripts')
    @include('partials.vendor-scripts', ['libs' => ['swiper', 'counterup', 'lightgallery']])
@endpush
