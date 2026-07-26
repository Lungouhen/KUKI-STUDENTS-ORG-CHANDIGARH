@extends('layouts.admin')

@section('title', 'Testimonials & Alumni Speak | KSO CMS')

@section('content')

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3">
                <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-comments me-2"></i> Student & Alumni Testimonials</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 extra-small">
                        <thead class="table-light">
                            <tr>
                                <th>Author Name</th>
                                <th>Title / College</th>
                                <th>Quote</th>
                                <th>Rating</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($testimonials as $t)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $t->author_name }}</td>
                                    <td>{{ $t->author_title }}<br><small class="text-muted">{{ $t->college_name }}</small></td>
                                    <td class="text-truncate" style="max-width: 200px;">{{ $t->quote }}</td>
                                    <td class="text-warning">
                                        @for($i=0; $i<$t->rating; $i++) <i class="fa-solid fa-star"></i> @endfor
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.testimonials.destroy', $t->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete testimonial?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-quote-left me-2"></i> Add Alumni Testimonial</h5>
            <form action="{{ route('admin.testimonials.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Author Name</label>
                    <input type="text" name="author_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Title / Designation</label>
                    <input type="text" name="author_title" class="form-control" placeholder="e.g. Alumni (MA Economics 2022)" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">College / University</label>
                    <input type="text" name="college_name" class="form-control" placeholder="e.g. Panjab University" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Quote / Review</label>
                    <textarea name="quote" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Star Rating</label>
                    <select name="rating" class="form-select">
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Save Testimonial</button>
            </form>
        </div>
    </div>
</div>

@endsection
