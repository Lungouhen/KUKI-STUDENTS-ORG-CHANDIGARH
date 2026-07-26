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
                <label class="form-label fw-bold">Page Content (HTML allowed)</label>
                <textarea name="content" class="form-control" rows="12" required>{{ old('content', $page->content) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Meta Title (SEO)</label>
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Meta Description (SEO)</label>
                <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $page->meta_description) }}">
            </div>
            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishCheck" {{ $page->is_published ? 'checked' : '' }}>
                    <label class="form-check-input-label fw-bold ms-2" for="publishCheck">Published on public site</label>
                </div>
            </div>
            <div class="col-12 mt-4 text-end">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2"></i> Update Page</button>
            </div>
        </div>
    </form>
</div>

@endsection
