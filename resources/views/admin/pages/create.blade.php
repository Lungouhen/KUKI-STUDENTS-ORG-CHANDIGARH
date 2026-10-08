@extends('layouts.admin')

@section('title', 'Create Dynamic Page | KSO CMS')

@section('content')

<div class="card border-0 shadow-sm rounded-4 p-4">
    <h4 class="fw-bold text-primary mb-3"><i class="fa-solid fa-pen-nib me-2"></i> Create Custom Web Page</h4>
    
    <form action="{{ route('admin.pages.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            <div class="col-md-12">
                <label class="form-label fw-bold">Page Title</label>
                <input type="text" name="title" class="form-control form-control-lg" value="{{ old('title') }}" placeholder="e.g. Freshers Hostel Guide 2026" required>
            </div>
            <div class="col-md-12">
                <label class="form-label fw-bold">Excerpt / Short Summary</label>
                <input type="text" name="excerpt" class="form-control" value="{{ old('excerpt') }}" placeholder="Brief summary of the page">
            </div>
            <div class="col-md-12">
                <label class="form-label fw-bold">Legacy Page Content (optional HTML)</label>
                <textarea name="content" class="form-control" rows="8" placeholder="Optional legacy HTML content...">{{ old('content') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Featured image</label>
                <select name="featured_image" class="form-select">
                    <option value="">No featured image</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset->path }}" @selected(old('featured_image') === $asset->path)>{{ $asset->original_name }} — {{ $asset->alt_text }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <fieldset>
                    <legend class="form-label fw-bold">Choose a page template</legend>
                    <div class="row g-3">
                        @foreach(config('page_templates') as $key => $template)
                            <div class="col-sm-6 col-xl-3">
                                <input class="btn-check" type="radio" name="template" id="template-{{ $key }}" value="{{ $key }}" @checked(old('template', 'standard') === $key) required>
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
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Meta Description (SEO)</label>
                <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="publication-status">Editorial status</label>
                <select class="form-select" name="publication_status" id="publication-status">
                    @foreach(['draft' => 'Draft', 'review' => 'In review', 'published' => 'Published', 'scheduled' => 'Scheduled'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('publication_status', 'draft') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="scheduled-publish-at">Publish at (for scheduled pages)</label>
                <input type="datetime-local" class="form-control" name="scheduled_publish_at" id="scheduled-publish-at" value="{{ old('scheduled_publish_at') }}">
            </div>
            <div class="col-12 mt-4 text-end">
                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary rounded-pill me-2">Cancel</a>
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold">Save Page</button>
            </div>
        </div>
    </form>
</div>

@endsection
