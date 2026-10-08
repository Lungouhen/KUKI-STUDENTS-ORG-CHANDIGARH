@extends('layouts.admin')

@section('title', 'Edit Page - ' . $page->title)

@section('content')

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h4 class="fw-bold text-primary mb-0"><i class="fa-solid fa-file-pen me-2"></i> Edit Dynamic Web Page</h4>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back to List</a>
    </div>

    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label fw-bold">Page Title</label>
                <input type="text" name="title" class="form-control form-control-lg" value="{{ old('title', $page->title) }}" required>
            </div>
            <div class="col-md-12">
                <label class="form-label fw-bold">Excerpt / Short Summary</label>
                <input type="text" name="excerpt" class="form-control" value="{{ old('excerpt', $page->excerpt) }}">
            </div>
            <div class="col-md-12">
                <label class="form-label fw-bold">Legacy Page Content (optional HTML)</label>
                <textarea name="content" class="form-control" rows="8">{{ old('content', $page->content) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Featured image</label>
                <select name="featured_image" class="form-select">
                    <option value="">No featured image</option>
                    @if($page->featured_image && !$assets->contains('path', $page->featured_image))
                        <option value="{{ $page->featured_image }}" @selected(old('featured_image', $page->featured_image) === $page->featured_image)>Existing image</option>
                    @endif
                    @foreach($assets as $asset)
                        <option value="{{ $asset->path }}" @selected(old('featured_image', $page->featured_image) === $asset->path)>{{ $asset->original_name }} — {{ $asset->alt_text }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <fieldset>
                    <legend class="form-label fw-bold">Choose a page template</legend>
                    <div class="row g-3">
                        @foreach(config('page_templates') as $key => $template)
                            <div class="col-sm-6 col-xl-3">
                                <input class="btn-check" type="radio" name="template" id="template-{{ $key }}" value="{{ $key }}" @checked(old('template', $page->template ?? 'standard') === $key) required>
                                <label class="btn btn-outline-primary text-start w-100 h-100 p-3" for="template-{{ $key }}">
                                    <span class="d-block fw-bold">{{ $template['name'] }}</span>
                                    <span class="d-block small mt-2">{{ $template['description'] }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </fieldset>
            </div>
            @include('admin.pages._sections')
            <div class="col-md-6">
                <label class="form-label fw-bold">Meta Title (SEO)</label>
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Meta Description (SEO)</label>
                <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $page->meta_description) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="publication-status">Editorial status</label>
                <select class="form-select" name="publication_status" id="publication-status">
                    @foreach(['draft' => 'Draft', 'review' => 'In review', 'published' => 'Published', 'scheduled' => 'Scheduled'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('publication_status', $page->publication_status ?? ($page->is_published ? 'published' : 'draft')) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="scheduled-publish-at">Publish at (for scheduled pages)</label>
                <input type="datetime-local" class="form-control" name="scheduled_publish_at" id="scheduled-publish-at" value="{{ old('scheduled_publish_at', $page->scheduled_publish_at?->format('Y-m-d\TH:i')) }}">
            </div>
            <div class="col-12 mt-4 text-end">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2"></i> Update Page</button>
            </div>
        </div>
    </form>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4 mt-4">
    <h5 class="fw-bold text-primary">Revision history</h5>
    <div class="table-responsive">
        <table class="table table-sm align-middle">
            <thead><tr><th>Version</th><th>Saved by</th><th>Date</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($page->revisions as $revision)
                    <tr>
                        <td>v{{ $revision->version }}</td>
                        <td>{{ $revision->author?->name ?? 'System' }}</td>
                        <td>{{ $revision->created_at->format('Y-m-d H:i') }}</td>
                        <td class="d-flex gap-1">
                            <a class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener" href="{{ route('admin.pages.revisions.preview', [$page->id, $revision->id]) }}">Preview</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.pages.revisions.compare', [$page->id, $revision->id]) }}">Compare</a>
                            @if(!$loop->first)
                                <form method="POST" action="{{ route('admin.pages.revisions.restore', [$page->id, $revision->id]) }}" onsubmit="return confirm('Restore this version? A new revision will be created.')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-warning">Restore</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-muted">No revisions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
