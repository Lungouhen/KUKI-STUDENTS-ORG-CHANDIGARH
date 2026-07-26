@extends('layouts.admin')

@section('title', 'Edit FAQ')

@section('content')

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h4 class="fw-bold text-primary mb-0"><i class="fa-solid fa-circle-question me-2"></i> Edit FAQ</h4>
        <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back to List</a>
    </div>

    <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label fw-bold">Question</label>
                <input type="text" name="question" class="form-control" value="{{ old('question', $faq->question) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Category</label>
                <select name="category" class="form-select" required>
                    <option value="Membership" {{ $faq->category == 'Membership' ? 'selected' : '' }}>Membership</option>
                    <option value="Admissions" {{ $faq->category == 'Admissions' ? 'selected' : '' }}>Admissions</option>
                    <option value="Emergency Cell" {{ $faq->category == 'Emergency Cell' ? 'selected' : '' }}>Emergency Cell</option>
                    <option value="Hostel & PG" {{ $faq->category == 'Hostel & PG' ? 'selected' : '' }}>Hostel & PG</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Answer</label>
                <textarea name="answer" class="form-control" rows="4" required>{{ old('answer', $faq->answer) }}</textarea>
            </div>
            <div class="col-12 mt-4 text-end">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2"></i> Update FAQ</button>
            </div>
        </div>
    </form>
</div>

@endsection
