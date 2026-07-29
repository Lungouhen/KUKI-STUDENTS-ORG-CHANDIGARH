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
                <input type="text" name="title" class="form-control form-control-lg" placeholder="e.g. Freshers Hostel Guide 2026" required>
            </div>
            <div class="col-md-12">
                <label class="form-label fw-bold">Excerpt / Short Summary</label>
                <input type="text" name="excerpt" class="form-control" placeholder="Brief summary of the page">
            </div>
            <div class="col-md-12">
                <label class="form-label fw-bold">Page Content (HTML allowed)</label>
                <textarea name="content" class="form-control" rows="12" data-richtext placeholder="Write full HTML or formatted content here..." required></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Meta Title (SEO)</label>
                <input type="text" name="meta_title" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Meta Description (SEO)</label>
                <input type="text" name="meta_description" class="form-control">
            </div>
            <div class="col-12">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" value="1" id="publishCheck" checked>
                    <label class="form-check-input-label fw-bold ms-2" for="publishCheck">Publish immediately on public site</label>
                </div>
            </div>
            <div class="col-12 mt-4 text-end">
                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary rounded-pill me-2">Cancel</a>
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold">Publish Page</button>
            </div>
        </div>
    </form>
</div>

@endsection
