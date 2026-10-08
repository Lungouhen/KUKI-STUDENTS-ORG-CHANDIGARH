@extends('layouts.app')

@section('title', 'Contact Us | KSO Chandigarh')

@section('content')

<div class="contact-page">
<div class="public-page-banner bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h2 class="fw-black mb-1">Contact & Emergency Helplines</h2>
        <p class="small text-light opacity-90 mb-0">Get in touch with KSO Chandigarh executive body or reach out for assistance</p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-5">
        <div class="col-lg-5">
            <div class="bg-white p-4 rounded-4 shadow-sm border mb-4">
                <h4 class="fw-bold text-primary mb-3">Office Location & Contact</h4>
                
                <div class="d-flex mb-3">
                    <div class="icon-circle bg-primary-lt me-3 text-primary"><i class="fa-solid fa-location-dot"></i></div>
                    <div>
                        <div class="fw-bold text-dark">Address</div>
                        <div class="small text-muted">{{ \App\Models\Setting::get('address', 'Room 12, Student Centre, Panjab University, Sector 14, Chandigarh, 160014') }}</div>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <div class="icon-circle bg-success-lt me-3 text-success"><i class="fa-solid fa-phone"></i></div>
                    <div>
                        <div class="fw-bold text-dark">President Contact</div>
                        <div class="small text-muted"><a href="tel:{{ \App\Models\Setting::get('phone', '+91 98765 43210') }}" class="text-decoration-none text-dark fw-semibold">{{ \App\Models\Setting::get('phone', '+91 98765 43210') }}</a></div>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <div class="icon-circle bg-danger-lt me-3 text-danger"><i class="fa-solid fa-headset"></i></div>
                    <div>
                        <div class="fw-bold text-dark">24/7 Helpline Cell</div>
                        <div class="small text-muted"><a href="tel:{{ \App\Models\Setting::get('helpline', '+91 98765 43211') }}" class="text-decoration-none text-danger fw-bold">{{ \App\Models\Setting::get('helpline', '+91 98765 43211') }}</a></div>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="icon-circle bg-warning-lt me-3 text-warning"><i class="fa-solid fa-envelope"></i></div>
                    <div>
                        <div class="fw-bold text-dark">Email Inquiries</div>
                        <div class="small text-muted"><a href="mailto:{{ \App\Models\Setting::get('email', 'ksochandigarh@gmail.com') }}" class="text-decoration-none text-dark">{{ \App\Models\Setting::get('email', 'ksochandigarh@gmail.com') }}</a></div>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-danger text-white rounded-4 shadow-sm">
                <h5 class="fw-bold mb-2"><i class="fa-solid fa-triangle-exclamation me-2"></i> Medical Emergency Assistance</h5>
                <p class="extra-small opacity-90 mb-2">If you or a student member requires blood donation, hospital emergency admission at PGIMER or GMCH-32, contact us immediately.</p>
                <a href="tel:{{ \App\Models\Setting::get('helpline', '+91 98765 43211') }}" class="btn btn-light text-danger fw-bold btn-sm rounded-pill"><i class="fa-solid fa-phone me-1"></i> Call Medical Cell</a>
            </div>

            @if(\App\Models\Setting::get('mapEmbedUrl'))
                <div class="mt-4 rounded-4 overflow-hidden shadow-sm border" style="height: 250px;">
                    <iframe src="{{ \App\Models\Setting::get('mapEmbedUrl') }}" title="Map showing the KSO Chandigarh office location" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            @endif
        </div>

        <div class="col-lg-7">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                <h4 class="fw-bold text-primary mb-3">Send Us a Message</h4>
                <form action="{{ route('contact.store') }}" method="POST" id="contactForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="contact-name">Your Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="contact-name" name="name" value="{{ old('name') }}" autocomplete="name" maxlength="255" @error('name') aria-invalid="true" aria-describedby="contact-name-error" @enderror required>
                            @error('name')<div class="invalid-feedback" id="contact-name-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="contact-phone">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="contact-phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" maxlength="20" @error('phone') aria-invalid="true" aria-describedby="contact-phone-error" @enderror required>
                            @error('phone')<div class="invalid-feedback" id="contact-phone-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold" for="contact-email">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="contact-email" name="email" value="{{ old('email') }}" autocomplete="email" @error('email') aria-invalid="true" aria-describedby="contact-email-error" @enderror>
                            @error('email')<div class="invalid-feedback" id="contact-email-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold" for="contact-subject">Subject <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="contact-subject" name="subject" value="{{ old('subject') }}" placeholder="Inquiry / Hostel Help / Membership" maxlength="255" @error('subject') aria-invalid="true" aria-describedby="contact-subject-error" @enderror required>
                            @error('subject')<div class="invalid-feedback" id="contact-subject-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold" for="contact-message">Message Details <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="contact-message" name="message" rows="5" @error('message') aria-invalid="true" aria-describedby="contact-message-error" @enderror required>{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback" id="contact-message-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary btn-lg px-4 rounded-pill fw-bold">
                                <span id="contact-submit-label">Send Message</span> <i class="fa-solid fa-paper-plane ms-1" aria-hidden="true"></i>
                            </button>
                            <span id="contact-submit-status" class="visually-hidden" role="status" aria-live="polite"></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
