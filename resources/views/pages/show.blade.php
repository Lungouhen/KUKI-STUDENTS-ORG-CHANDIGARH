@extends('layouts.app')

@section('title', $page->meta_title ?: $page->title)
@section('meta_description', $page->meta_description ?: ($page->excerpt ?: (\App\Models\Setting::get('seoDescription') ?: \App\Models\Setting::get('tagline', 'Empowering Students • Preserving Culture • Serving Community'))))
@section('robots', $page->is_published && \App\Models\Setting::get('seoIndexingEnabled', true) ? 'index,follow' : 'noindex,nofollow')
@section('og_type', 'article')
@section('og_image', $page->featured_image ? asset($page->featured_image) : (\App\Models\Setting::get('seoSocialImage') ? asset(\App\Models\Setting::get('seoSocialImage')) : ''))

@section('content')

<div class="cms-page">
@if($isPreview ?? false)
    <div class="alert alert-warning rounded-0 mb-0 text-center" role="status">
        Preview only — this page is not publicly available until it is published.
    </div>
@endif

@include($page->templateView())

@if($page->featured_image)
    <div class="container my-4">
        <img class="img-fluid rounded-4" src="{{ asset($page->featured_image) }}" alt="{{ $mediaAltText[$page->featured_image] ?? $page->title }}" loading="lazy">
    </div>
@endif

@include('pages.sections', ['sections' => $page->sections ?? [], 'mediaAltText' => $mediaAltText ?? collect()])

</div>
@endsection
