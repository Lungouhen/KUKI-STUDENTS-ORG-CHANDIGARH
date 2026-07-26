@extends('layouts.admin')

@section('title', 'Edit Testimonial')

@section('content')

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h4 class="fw-bold text-primary mb-0"><i class="fa-solid fa-quote-left me-2"></i> Edit Testimonial</h4>
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back to List</a>
    </div>

    <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Author Name</label>
                <input type="text" name="author_name" class="form-control" value="{{ old('author_name', $testimonial->author_name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Title / Designation</label>
                <input type="text" name="author_title" class="form-control" value="{{ old('author_title', $testimonial->author_title) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">College / University</label>
                <input type="text" name="college_name" class="form-control" value="{{ old('college_name', $testimonial->college_name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Star Rating</label>
                <select name="rating" class="form-select">
                    <option value="5" {{ $testimonial->rating == 5 ? 'selected' : '' }}>5 Stars</option>
                    <option value="4" {{ $testimonial->rating == 4 ? 'selected' : '' }}>4 Stars</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Quote / Review</label>
                <textarea name="quote" class="form-control" rows="3" required>{{ old('quote', $testimonial->quote) }}</textarea>
            </div>
            <div class="col-12 mt-4 text-end">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2"></i> Update Testimonial</button>
            </div>
        </div>
    </form>
</div>

@endsection
