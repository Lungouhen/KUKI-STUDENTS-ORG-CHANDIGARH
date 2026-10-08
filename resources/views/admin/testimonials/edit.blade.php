@extends('layouts.admin')

@section('title', 'Edit Testimonial')

@section('content')
<div class="admin-testimonials-page">
    @if($errors->any())
        <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="testimonialErrorsHeading">
            <h2 id="testimonialErrorsHeading" class="h6 fw-bold">Review the testimonial details</h2>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="card border-0 shadow-sm rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <h1 class="h4 fw-bold text-primary mb-0"><i class="fa-solid fa-quote-left me-2" aria-hidden="true"></i>Edit Testimonial</h1>
            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i>Back to List</a>
        </div>

        <form action="{{ route('admin.testimonials.update', $testimonial->id) }}" method="POST" data-testimonial-form>
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="testimonialAuthorName">Author Name</label>
                    <input type="text" id="testimonialAuthorName" name="author_name" class="form-control" value="{{ old('author_name', $testimonial->author_name) }}" maxlength="255" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="testimonialAuthorTitle">Title / Designation</label>
                    <input type="text" id="testimonialAuthorTitle" name="author_title" class="form-control" value="{{ old('author_title', $testimonial->author_title) }}" maxlength="255" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="testimonialCollege">College / University</label>
                    <input type="text" id="testimonialCollege" name="college_name" class="form-control" value="{{ old('college_name', $testimonial->college_name) }}" maxlength="255" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold" for="testimonialRating">Star Rating</label>
                    <select id="testimonialRating" name="rating" class="form-select" required>
                        @foreach([5, 4, 3, 2, 1] as $rating)
                            <option value="{{ $rating }}" @selected((int) old('rating', $testimonial->rating) === $rating)>{{ $rating }} {{ $rating === 1 ? 'Star' : 'Stars' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold" for="testimonialQuote">Quote / Review</label>
                    <textarea id="testimonialQuote" name="quote" class="form-control" rows="5" maxlength="10000" required>{{ old('quote', $testimonial->quote) }}</textarea>
                </div>
                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2" aria-hidden="true"></i>Update Testimonial</button>
                </div>
            </div>
        </form>
    </section>
</div>
@endsection
