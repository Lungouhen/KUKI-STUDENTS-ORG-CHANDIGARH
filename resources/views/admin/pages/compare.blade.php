@extends('layouts.admin')

@section('title', 'Compare page revision')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold text-primary mb-1">Revision v{{ $revision->version }} comparison</h4>
            <p class="text-muted mb-0">{{ $page->title }} · saved {{ $revision->created_at->format('Y-m-d H:i') }}</p>
        </div>
        <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-outline-secondary">Back to page</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered align-top">
            <thead><tr><th style="width:15%">Field</th><th>Revision v{{ $revision->version }}</th><th>Current</th></tr></thead>
            <tbody>
                @foreach(['title' => 'Title', 'excerpt' => 'Excerpt', 'content' => 'HTML content', 'template' => 'Template', 'featured_image' => 'Featured image', 'meta_title' => 'Meta title', 'meta_description' => 'Meta description', 'sections' => 'Structured sections'] as $field => $label)
                    <tr>
                        <th>{{ $label }}</th>
                        <td><pre class="text-wrap mb-0">{{ is_array($revision->snapshot[$field] ?? null) ? json_encode($revision->snapshot[$field], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : ($revision->snapshot[$field] ?? '') }}</pre></td>
                        <td><pre class="text-wrap mb-0">{{ is_array($current[$field] ?? null) ? json_encode($current[$field], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : ($current[$field] ?? '') }}</pre></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
