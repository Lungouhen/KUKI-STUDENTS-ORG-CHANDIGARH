@extends('layouts.admin')

@section('title', 'Testimonials & Alumni Speak | KSO CMS')

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

    <div class="row g-4">
        <div class="col-lg-7">
            <section class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white p-3">
                    <h2 class="h5 fw-bold text-primary mb-3"><i class="fa-solid fa-comments me-2" aria-hidden="true"></i>Student &amp; Alumni Testimonials</h2>
                    <form action="{{ route('admin.testimonials.index') }}" method="GET" class="row g-2">
                        <div class="col">
                            <label class="visually-hidden" for="testimonialSearch">Search testimonials</label>
                            <input id="testimonialSearch" name="q" value="{{ $search }}" class="form-control form-control-sm" type="search" placeholder="Search author, college, or quote">
                        </div>
                        <div class="col-auto"><button type="submit" class="btn btn-sm btn-outline-primary">Search</button></div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 extra-small" aria-describedby="testimonialTableCaption">
                            <caption id="testimonialTableCaption" class="visually-hidden">Student and alumni testimonials</caption>
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Author Name</th>
                                    <th scope="col">Title / College</th>
                                    <th scope="col">Quote</th>
                                    <th scope="col">Rating</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($testimonials as $testimonial)
                                    <tr>
                                        <td class="fw-bold text-dark">{{ $testimonial->author_name }}</td>
                                        <td>{{ $testimonial->author_title }}<br><small class="text-muted">{{ $testimonial->college_name }}</small></td>
                                        <td class="testimonial-quote">{{ $testimonial->quote }}</td>
                                        <td class="text-warning" aria-label="{{ $testimonial->rating }} out of 5 stars">
                                            @for($i = 0; $i < $testimonial->rating; $i++)
                                                <i class="fa-solid fa-star" aria-hidden="true"></i>
                                            @endfor
                                            <span class="visually-hidden">{{ $testimonial->rating }} out of 5 stars</span>
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit testimonial by {{ $testimonial->author_name }}"><i class="fa-solid fa-pen" aria-hidden="true"></i></a>
                                            <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="d-inline" data-testimonial-delete>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Delete testimonial by {{ $testimonial->author_name }}"><i class="fa-solid fa-trash" aria-hidden="true"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">No testimonials match your search.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($testimonials->hasPages())
                        <div class="p-3">{{ $testimonials->links() }}</div>
                    @endif
                </div>
            </section>
        </div>

        <div class="col-lg-5">
            <section class="card border-0 shadow-sm rounded-4 p-4">
                <h2 class="h5 fw-bold text-primary mb-3"><i class="fa-solid fa-quote-left me-2" aria-hidden="true"></i>Add Alumni Testimonial</h2>
                <form action="{{ route('admin.testimonials.store') }}" method="POST" data-testimonial-form>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="testimonialAuthorName">Author Name</label>
                        <input type="text" id="testimonialAuthorName" name="author_name" class="form-control" value="{{ old('author_name') }}" maxlength="255" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="testimonialAuthorTitle">Title / Designation</label>
                        <input type="text" id="testimonialAuthorTitle" name="author_title" class="form-control" value="{{ old('author_title') }}" maxlength="255" placeholder="e.g. Alumni (MA Economics 2022)" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="testimonialCollege">College / University</label>
                        <input type="text" id="testimonialCollege" name="college_name" class="form-control" value="{{ old('college_name') }}" maxlength="255" placeholder="e.g. Panjab University" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="testimonialQuote">Quote / Review</label>
                        <textarea id="testimonialQuote" name="quote" class="form-control" rows="4" maxlength="10000" required>{{ old('quote') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="testimonialRating">Star Rating</label>
                        <select id="testimonialRating" name="rating" class="form-select" required>
                            @foreach([5, 4, 3, 2, 1] as $rating)
                                <option value="{{ $rating }}" @selected((int) old('rating', 5) === $rating)>{{ $rating }} {{ $rating === 1 ? 'Star' : 'Stars' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Save Testimonial</button>
                </form>
            </section>
        </div>
    </div>
</div>
@endsection
