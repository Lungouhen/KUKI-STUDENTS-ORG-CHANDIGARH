@extends('layouts.app')

@section('title', 'Frequently Asked Questions | KSO Chandigarh')

@section('content')

<div class="faqs-page">
<div class="public-page-banner bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h1 class="fw-black mb-1">Frequently Asked Questions</h1>
        <p class="small text-light opacity-90 mb-0">Answers to common student questions about admissions, membership, and emergency relief</p>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @foreach($faqs as $category => $items)
                <h2 class="h4 fw-bold text-primary mb-3 mt-4"><i class="fa-solid fa-circle-question me-2" aria-hidden="true"></i> {{ $category }}</h2>
                <div class="accordion mb-4 shadow-sm rounded-4 overflow-hidden" id="faqAccordion-{{ Str::slug($category) }}">
                    @foreach($items as $idx => $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-{{ $faq->id }}">
                                <button class="accordion-button collapsed fw-bold text-dark" type="button" id="faq-trigger-{{ $faq->id }}" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $faq->id }}" aria-controls="collapse-{{ $faq->id }}" aria-expanded="false">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="collapse-{{ $faq->id }}" class="accordion-collapse collapse" role="region" aria-labelledby="faq-trigger-{{ $faq->id }}" data-bs-parent="#faqAccordion-{{ Str::slug($category) }}">
                                <div class="accordion-body text-secondary leading-relaxed">
                                    {{ $faq->answer }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</div>

</div>
@endsection
