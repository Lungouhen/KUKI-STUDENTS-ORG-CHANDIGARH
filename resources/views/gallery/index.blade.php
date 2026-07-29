@extends('layouts.app')

@section('title', 'Photo Gallery | KSO Chandigarh')

@push('styles')
    @include('partials.vendor-styles', ['libs' => ['lightgallery']])
@endpush

@section('content')

<div class="bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h2 class="fw-black mb-1">Photo Gallery</h2>
        <p class="small text-light opacity-90 mb-0">Capturing moments of student life, cultural festivals, and community service</p>
    </div>
</div>

<div class="container my-4">

    {{-- Category filter. Server-rendered links (not JS-only) so each view is
         linkable, shareable and crawlable. --}}
    @if($categories->isNotEmpty())
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
            <a href="{{ route('gallery.index') }}"
               class="btn btn-sm rounded-pill px-3 {{ !$category || $category === 'All' ? 'btn-primary' : 'btn-outline-primary' }}">
                All Photos
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('gallery.index', ['category' => $cat]) }}"
                   class="btn btn-sm rounded-pill px-3 {{ $category === $cat ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    @endif

    @if($gallery->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="fa-solid fa-images fs-1 d-block mb-3 opacity-50"></i>
            <p class="mb-0">No photos have been published{{ $category ? ' in this category' : '' }} yet.</p>
        </div>
    @else
        {{-- Masonry grid + lightGallery lightbox.
             .masonry-sizer defines the column width; Masonry re-lays out once
             imagesLoaded reports every thumbnail has decoded. --}}
        <div class="masonry-grid" data-masonry-grid data-lightgallery
             data-lightgallery='{"selector":"[data-lg-item]"}'>
            <div class="masonry-sizer" data-masonry-sizer></div>

            @foreach($gallery as $g)
                <div class="masonry-item" data-masonry-item>
                    <a href="{{ asset($g->image_url) }}"
                       class="masonry-card"
                       data-lg-item
                       data-sub-html="<h4>{{ e($g->title) }}</h4><p>{{ e($g->caption ?: $g->category) }}</p>">
                        <img src="{{ asset($g->image_url) }}"
                             alt="{{ $g->title }}"
                             loading="lazy"
                             onerror="this.src='{{ asset('images/gallery-1.jpg') }}'">
                        <span class="masonry-card__overlay">
                            <span class="masonry-card__zoom"><i class="fa-solid fa-magnifying-glass"></i></span>
                        </span>
                    </a>
                    <div class="masonry-card__meta">
                        <h6>{{ $g->title }}</h6>
                        <span class="badge bg-light text-muted extra-small">{{ $g->category }}</span>
                        @if($g->date)
                            <small class="text-muted extra-small d-block mt-1">
                                <i class="fa-solid fa-calendar me-1"></i>{{ $g->date->format('d M Y') }}
                            </small>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection

@push('scripts')
    @include('partials.vendor-scripts', ['libs' => ['masonry', 'imagesloaded', 'lightgallery']])
@endpush
