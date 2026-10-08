@extends('layouts.app')

@section('title', 'Photo Gallery | KSO Chandigarh')

@section('content')

<div class="gallery-page">
<div class="public-page-banner bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h1 class="fw-black mb-1">Photo Gallery</h1>
        <p class="small text-light opacity-90 mb-0">Capturing moments of student life, cultural festivals, and community service</p>
    </div>
</div>

<div class="container my-4">
    @if($gallery->isNotEmpty())
        <div class="gallery-filters mb-4" role="group" aria-label="Filter gallery by category">
            <button type="button" class="btn btn-outline-primary active" data-gallery-filter="all" aria-pressed="true">All photos</button>
            @foreach($gallery->pluck('category')->filter()->unique()->sort() as $category)
                <button type="button" class="btn btn-outline-primary" data-gallery-filter="{{ Str::slug($category) }}" aria-pressed="false">{{ $category }}</button>
            @endforeach
            <span class="visually-hidden" id="gallery-filter-status" role="status" aria-live="polite"></span>
        </div>
    @endif
    <div class="masonry-grid" id="galleryGrid">
        @forelse($gallery as $g)
            <div class="masonry-item" data-gallery-category="{{ Str::slug($g->category ?: 'other') }}">
                <figure class="community-photo-card gallery-photo-card mb-0">
                    <a href="{{ asset($g->image_url) }}" class="popup-gallery d-block" aria-label="View full-size photo: {{ $g->title }}">
                        <img src="{{ asset($g->image_url) }}" class="gal-img w-100" alt="{{ $g->mediaAsset?->alt_text ?: ($g->caption ?: $g->title) }}" loading="lazy" onerror="this.hidden = true; this.nextElementSibling.hidden = false;">
                        <div class="community-photo-fallback" hidden aria-hidden="true"><i class="fa-solid fa-users"></i><span>Community moments</span></div>
                    </a>
                    <figcaption><span>{{ $g->category }}</span><strong>{{ $g->title }}</strong></figcaption>
                </figure>
            </div>
        @empty
            <div class="col-12">
                <div class="gallery-empty-state">
                    <i class="fa-regular fa-images" aria-hidden="true"></i>
                    <h3>Community photos are on the way</h3>
                    <p class="mb-0">This gallery will feature moments shared by KSO Chandigarh students and organizers.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

</div>
@endsection
