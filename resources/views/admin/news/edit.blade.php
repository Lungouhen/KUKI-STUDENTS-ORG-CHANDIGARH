@extends('layouts.admin')

@section('title', 'Edit Announcement - ' . $news->title)

@section('content')

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h4 class="fw-bold text-primary mb-0"><i class="fa-solid fa-pen-to-square me-2"></i> Edit Notice / Announcement</h4>
        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back to List</a>
    </div>

    <form action="{{ route('admin.news.update', $news->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-bold">Headline Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $news->title) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Category</label>
                <select name="category" class="form-select" required>
                    <option value="Notice" {{ $news->category == 'Notice' ? 'selected' : '' }}>Notice</option>
                    <option value="Welfare" {{ $news->category == 'Welfare' ? 'selected' : '' }}>Welfare</option>
                    <option value="Academic" {{ $news->category == 'Academic' ? 'selected' : '' }}>Academic</option>
                    <option value="Press Release" {{ $news->category == 'Press Release' ? 'selected' : '' }}>Press Release</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Content</label>
                <textarea name="content" class="form-control" rows="5" required>{{ old('content', $news->content) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Author</label>
                <input type="text" name="author" class="form-control" value="{{ old('author', $news->author) }}">
            </div>
            <div class="col-12 mt-4 text-end">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2"></i> Update Announcement</button>
            </div>
        </div>
    </form>
</div>

@endsection
