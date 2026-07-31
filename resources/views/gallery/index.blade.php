@extends('layouts.app')

@section('title', 'Photo Gallery | KSO Chandigarh')

@section('content')

<div class="bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h2 class="fw-black mb-1">Photo Gallery</h2>
        <p class="small text-light opacity-90 mb-0">Capturing moments of student life, cultural festivals, and community service</p>
    </div>
</div>

<div class="container my-4">
    <div class="masonry-grid" id="galleryGrid">
        @foreach($gallery as $g)
            <div class="masonry-item">
                <div class="gallery-item shadow-sm border bg-white p-2 text-center">
                    <a href="{{ asset($g->image_url) }}" class="popup-gallery d-block" title="{{ $g->title }}">
                        <img src="{{ asset($g->image_url) }}" class="img-fluid rounded gal-img w-100" style="height: 200px; object-fit: cover;" alt="{{ $g->title }}" onerror="this.src='/images/gallery-1.jpg'">
                    </a>
                    <div class="mt-2">
                        <h6 class="fw-bold text-dark mb-0 extra-small">{{ $g->title }}</h6>
                        <span class="badge bg-light text-muted extra-small">{{ $g->category }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
