@extends('layouts.app')

@section('title', 'Frequently Asked Questions | KSO Chandigarh')

@section('content')

<div class="bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h2 class="fw-black mb-1">Frequently Asked Questions</h2>
        <p class="small text-light opacity-90 mb-0">Answers to common student questions about admissions, membership, and emergency relief</p>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @foreach($faqs as $category => $items)
                <h4 class="fw-bold text-primary mb-3 mt-4"><i class="fa-solid fa-circle-question me-2"></i> {{ $category }}</h4>
                <div class="accordion mb-4 shadow-sm rounded-4 overflow-hidden" id="faqAccordion-{{ Str::slug($category) }}">
                    @foreach($items as $idx => $faq)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-{{ $faq->id }}">
                                <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $faq->id }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="collapse-{{ $faq->id }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion-{{ Str::slug($category) }}">
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

@endsection
