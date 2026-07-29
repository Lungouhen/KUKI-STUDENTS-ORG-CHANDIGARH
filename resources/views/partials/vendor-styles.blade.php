{{--
    Companion to partials/vendor-scripts — emits only the stylesheets a page
    actually needs. Include it from inside the view's "styles" stack, e.g.
    a push block wrapping:

        @include('partials.vendor-styles', ['libs' => ['swiper']])
--}}

@php
    $libs = collect($libs ?? [])->map(fn ($l) => strtolower(trim($l)))->unique();
@endphp

@if($libs->contains('swiper'))
    <link href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
@endif

@if($libs->contains('lightgallery'))
    <link href="{{ asset('vendor/lightgallery/css/lightgallery-bundle.min.css') }}" rel="stylesheet">
@endif

@if($libs->contains('magnific-popup'))
    <link href="{{ asset('vendor/magnific-popup/magnific-popup.css') }}" rel="stylesheet">
@endif
