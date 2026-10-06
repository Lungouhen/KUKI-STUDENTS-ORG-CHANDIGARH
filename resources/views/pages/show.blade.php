@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)

@section('content')

@if($isPreview ?? false)
    <div class="alert alert-warning rounded-0 mb-0 text-center" role="status">
        Preview only — this page is not publicly available until it is published.
    </div>
@endif

@include($page->templateView())

@endsection
