@extends('layouts.admin')

@section('title', 'FAQs Manager | KSO CMS')

@section('content')

<div class="admin-faqs-page">
    @if($errors->any())
        <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="faqErrorsHeading">
            <h2 id="faqErrorsHeading" class="h6 fw-bold">Review the FAQ details</h2>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforelse
            </ul>
        </div>
    @endif

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3">
                <h2 class="h5 fw-bold text-primary mb-0"><i class="fa-solid fa-circle-question me-2" aria-hidden="true"></i> Frequently Asked Questions</h2>
            </div>
            <div class="card-body p-0">
                <div class="p-3">
                    <label class="visually-hidden" for="faqSearch">Search this FAQ page</label>
                    <input id="faqSearch" class="form-control" type="search" placeholder="Search questions or categories" autocomplete="off" data-faq-search>
                    <p class="form-text mb-0 mt-2" role="status" aria-live="polite" data-faq-search-status>{{ $faqs->count() }} FAQs on this page</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 extra-small" aria-describedby="faqTableCaption">
                        <caption id="faqTableCaption" class="visually-hidden">FAQ questions, categories, and available actions</caption>
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Question</th>
                                <th scope="col">Category</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($faqs as $f)
                                <tr data-faq-row>
                                    <td class="fw-bold text-dark">{{ $f->question }}</td>
                                    <td><span class="badge bg-primary-lt text-primary">{{ $f->category }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.faqs.edit', $f->id) }}" class="btn btn-sm btn-outline-primary me-1" aria-label="Edit FAQ: {{ $f->question }}">
                                            <i class="fa-solid fa-pen" aria-hidden="true"></i><span class="visually-hidden">Edit</span>
                                        </a>
                                        <form action="{{ route('admin.faqs.destroy', $f->id) }}" method="POST" class="d-inline" data-faq-delete>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Delete FAQ: {{ $f->question }}"><i class="fa-solid fa-trash" aria-hidden="true"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr data-faq-empty>
                                    <td colspan="3" class="text-center py-4 text-muted">No FAQs have been added yet.</td>
                                </tr>
                            @endforeach
                            <tr data-faq-no-results hidden>
                                <td colspan="3" class="text-center py-4 text-muted">No FAQs match your search on this page.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if($faqs->hasPages())
                    <div class="p-3">{{ $faqs->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h2 class="h5 fw-bold text-primary mb-3"><i class="fa-solid fa-plus-circle me-2" aria-hidden="true"></i> Add FAQ</h2>
            <form action="{{ route('admin.faqs.store') }}" method="POST" data-faq-form>
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold" for="faqQuestion">Question</label>
                    <input type="text" name="question" id="faqQuestion" class="form-control" value="{{ old('question') }}" required @if($errors->has('question')) aria-invalid="true" aria-describedby="faqQuestionError" @endif>
                    @error('question')<div id="faqQuestionError" class="form-text text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="faqCategory">Category</label>
                    <select name="category" id="faqCategory" class="form-select" required @if($errors->has('category')) aria-invalid="true" aria-describedby="faqCategoryError" @endif>
                        <option value="Membership" @selected(old('category', 'Membership') === 'Membership')>Membership</option>
                        <option value="Admissions" @selected(old('category') === 'Admissions')>Admissions</option>
                        <option value="Emergency Cell" @selected(old('category') === 'Emergency Cell')>Emergency Cell</option>
                        <option value="Hostel & PG" @selected(old('category') === 'Hostel & PG')>Hostel & PG</option>
                    </select>
                    @error('category')<div id="faqCategoryError" class="form-text text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold" for="faqAnswer">Answer</label>
                    <textarea name="answer" id="faqAnswer" class="form-control" rows="4" required @if($errors->has('answer')) aria-invalid="true" aria-describedby="faqAnswerError" @endif>{{ old('answer') }}</textarea>
                    @error('answer')<div id="faqAnswerError" class="form-text text-danger">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Save FAQ</button>
            </form>
        </div>
    </div>
</div>
</div>

@endsection
