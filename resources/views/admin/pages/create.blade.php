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
                <label class="form-label fw-bold">Page Content (HTML allowed)</label>
                <textarea name="content" class="form-control" rows="12" placeholder="Write full HTML or formatted content here..." required>{{ old('content') }}</textarea>
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
            <div class="col-md-6">
                <label class="form-label fw-bold">Meta Title (SEO)</label>
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Meta Description (SEO)</label>
                <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description') }}">
            </div>
            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishCheck" @checked(old('is_published'))>
                    <label class="form-check-input-label fw-bold ms-2" for="publishCheck">Publish immediately on public site</label>
                </div>
            </div>
            <div class="col-12 mt-4 text-end">
                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary rounded-pill me-2">Cancel</a>
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold">Save Page</button>
            </div>
        </div>
    </form>
</div>

@endsection
