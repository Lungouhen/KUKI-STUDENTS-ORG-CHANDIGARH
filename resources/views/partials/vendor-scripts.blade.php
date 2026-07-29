{{--
    Opt-in front-end library loader.
    ------------------------------------------------------------------
    Every library is self-hosted under public/vendor/ (no CDN), and each
    is pulled in only by the pages that actually use it. Pass the bundles
    you need from a view:

        @include('partials.vendor-scripts', ['libs' => ['swiper', 'counterup']])

    jQuery is injected automatically whenever a jQuery-dependent bundle
    (magnific-popup, masonry, counterup) is requested, and is always
    emitted before its dependents.
--}}

@php
    $libs = collect($libs ?? [])->map(fn ($l) => strtolower(trim($l)))->unique();

    // Bundles that require jQuery in the global scope.
    $needsJquery = $libs->intersect(['magnific-popup', 'masonry', 'counterup'])->isNotEmpty();
@endphp

@if($needsJquery)
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
@endif

@if($libs->contains('imagesloaded'))
    <script src="{{ asset('vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
@endif

@if($libs->contains('masonry'))
    {{-- imagesLoaded should precede Masonry so layout is measured after images decode. --}}
    @unless($libs->contains('imagesloaded'))
        <script src="{{ asset('vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    @endunless
    <script src="{{ asset('vendor/masonry/masonry.pkgd.min.js') }}"></script>
@endif

@if($libs->contains('counterup'))
    <script src="{{ asset('vendor/counterup/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('vendor/counterup/counterup2.min.js') }}"></script>
@endif

@if($libs->contains('magnific-popup'))
    <script src="{{ asset('vendor/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
@endif

@if($libs->contains('swiper'))
    <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
@endif

@if($libs->contains('lightgallery'))
    <script src="{{ asset('vendor/lightgallery/lightgallery.min.js') }}"></script>
    <script src="{{ asset('vendor/lightgallery/lg-thumbnail.min.js') }}"></script>
    <script src="{{ asset('vendor/lightgallery/lg-zoom.min.js') }}"></script>
@endif

{{-- Shared initialisers, driven entirely by data-attributes in the markup. --}}
<script src="{{ asset('js/kso-ui.js') }}"></script>
