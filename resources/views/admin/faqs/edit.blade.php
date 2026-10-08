@extends('layouts.admin')

@section('title', 'Edit FAQ')

@section('content')

<div class="admin-faqs-page">
    @if($errors->any())
        <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="faqErrorsHeading">
            <h2 id="faqErrorsHeading" class="h6 fw-bold">Review the FAQ details</h2>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="card border-0 shadow-sm rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <h1 class="h4 fw-bold text-primary mb-0"><i class="fa-solid fa-circle-question me-2" aria-hidden="true"></i> Edit FAQ</h1>
            <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Back to List</a>
        </div>

        <form action="{{ route('admin.faqs.update', $faq->id) }}" method="POST" data-faq-form>
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-bold" for="faqQuestion">Question</label>
                    <input type="text" name="question" id="faqQuestion" class="form-control" value="{{ old('question', $faq->question) }}" required @if($errors->has('question')) aria-invalid="true" aria-describedby="faqQuestionError" @endif>
                    @error('question')<div id="faqQuestionError" class="form-text text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold" for="faqCategory">Category</label>
                    <select name="category" id="faqCategory" class="form-select" required @if($errors->has('category')) aria-invalid="true" aria-describedby="faqCategoryError" @endif>
                        @if(!in_array(old('category', $faq->category), ['Membership', 'Admissions', 'Emergency Cell', 'Hostel & PG'], true))
                            <option value="{{ old('category', $faq->category) }}" selected>{{ old('category', $faq->category) }}</option>
                        @endif
                        <option value="Membership" @selected(old('category', $faq->category) === 'Membership')>Membership</option>
                        <option value="Admissions" @selected(old('category', $faq->category) === 'Admissions')>Admissions</option>
                        <option value="Emergency Cell" @selected(old('category', $faq->category) === 'Emergency Cell')>Emergency Cell</option>
                        <option value="Hostel & PG" @selected(old('category', $faq->category) === 'Hostel & PG')>Hostel & PG</option>
                    </select>
                    @error('category')<div id="faqCategoryError" class="form-text text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-bold" for="faqAnswer">Answer</label>
                    <textarea name="answer" id="faqAnswer" class="form-control" rows="5" required @if($errors->has('answer')) aria-invalid="true" aria-describedby="faqAnswerError" @endif>{{ old('answer', $faq->answer) }}</textarea>
                    @error('answer')<div id="faqAnswerError" class="form-text text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2" aria-hidden="true"></i> Update FAQ</button>
                </div>
            </div>
        </form>
    </section>
</div>

@endsection
