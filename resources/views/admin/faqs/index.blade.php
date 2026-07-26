@extends('layouts.admin')

@section('title', 'FAQs Manager | KSO CMS')

@section('content')

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3">
                <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-circle-question me-2"></i> Frequently Asked Questions</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 extra-small">
                        <thead class="table-light">
                            <tr>
                                <th>Question</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faqs as $f)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $f->question }}</td>
                                    <td><span class="badge bg-primary-lt text-primary">{{ $f->category }}</span></td>
                                    <td>
                                        <form action="{{ route('admin.faqs.destroy', $f->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete FAQ?')">
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
            <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-plus-circle me-2"></i> Add FAQ</h5>
            <form action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Question</label>
                    <input type="text" name="question" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="Membership">Membership</option>
                        <option value="Admissions">Admissions</option>
                        <option value="Emergency Cell">Emergency Cell</option>
                        <option value="Hostel & PG">Hostel & PG</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Answer</label>
                    <textarea name="answer" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Save FAQ</button>
            </form>
        </div>
    </div>
</div>

@endsection
