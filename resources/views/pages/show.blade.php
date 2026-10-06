@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)

@section('content')

@if($isPreview ?? false)
    <div class="alert alert-warning rounded-0 mb-0 text-center" role="status">
        Preview only — this page is not publicly available until it is published.
    </div>
@endif

<div class="bg-primary text-white py-5 mb-5">
    <div class="container text-center">
        <h1 class="fw-black display-5 mb-2">{{ $page->title }}</h1>
        @if($page->excerpt)
            <p class="lead opacity-90 mx-auto" style="max-width: 700px;">{{ $page->excerpt }}</p>
        @endif
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border leading-relaxed">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>

@endsection
