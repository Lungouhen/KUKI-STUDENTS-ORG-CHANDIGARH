@extends('layouts.app')

@section('title', 'Support KSO Student Welfare Fund | KSO Chandigarh')

@section('content')

<div class="donations-page">
<div class="public-page-banner bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h2 class="fw-black mb-1"><i class="fa-solid fa-hand-holding-heart me-2"></i> Support KSO Student Welfare Fund</h2>
        <p class="small text-light opacity-90 mb-0">Your generous contributions help fund student medical emergencies, books, and cultural events.</p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-5">
        <div class="col-lg-7">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border">
                <h4 class="fw-bold text-primary mb-3">Make a Contribution</h4>
                
                <form action="{{ route('donations.store') }}" method="POST" id="donationForm">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold" for="donation-cause">Select Welfare Cause</label>
                        <select class="form-select @error('cause') is-invalid @enderror" id="donation-cause" name="cause" @error('cause') aria-invalid="true" aria-describedby="donation-cause-error" @enderror required>
                            <option value="Student Emergency Welfare Fund" @selected(old('cause', 'Student Emergency Welfare Fund') === 'Student Emergency Welfare Fund')>Student Emergency Welfare Fund (Medical / Legal)</option>
                            <option value="Annual Cultural Meet" @selected(old('cause') === 'Annual Cultural Meet')>Annual Freshers & Cultural Extravaganza</option>
                            <option value="Academic & Book Bank" @selected(old('cause') === 'Academic & Book Bank')>Academic Book Bank & Scholarship Support</option>
                            <option value="General Support" @selected(old('cause') === 'General Support')>General Community Support</option>
                        </select>
                        @error('cause')<div class="invalid-feedback" id="donation-cause-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" for="donation-amount">Donation Amount (₹)</label>
                        <div class="donation-amount-options mb-2" role="group" aria-label="Suggested donation amounts">
                            @foreach([500, 1000, 2500, 5000] as $suggestedAmount)
                                <button type="button" class="btn btn-outline-primary donation-amount-option" data-donation-amount="{{ $suggestedAmount }}" aria-pressed="{{ (float) old('amount', 500) === (float) $suggestedAmount ? 'true' : 'false' }}">₹{{ number_format($suggestedAmount) }}</button>
                            @endforeach
                        </div>
                        <input type="number" class="form-control form-control-lg fw-bold text-primary @error('amount') is-invalid @enderror" id="donation-amount" name="amount" value="{{ old('amount', 500) }}" min="10" step="0.01" @error('amount') aria-invalid="true" aria-describedby="donation-amount-error" @enderror placeholder="Enter a custom amount" required>
                        @error('amount')<div class="invalid-feedback" id="donation-amount-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="donor-name">Donor Name</label>
                            <input type="text" class="form-control @error('donor_name') is-invalid @enderror" id="donor-name" name="donor_name" value="{{ old('donor_name') }}" autocomplete="name" maxlength="255" @error('donor_name') aria-invalid="true" aria-describedby="donor-name-error" @enderror placeholder="Full Name or Anonymous" required>
                            @error('donor_name')<div class="invalid-feedback" id="donor-name-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="donor-phone">Phone Number</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="donor-phone" name="phone" value="{{ old('phone') }}" autocomplete="tel" @error('phone') aria-invalid="true" aria-describedby="donor-phone-error" @enderror placeholder="+91 9876543210">
                            @error('phone')<div class="invalid-feedback" id="donor-phone-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="donor-email">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="donor-email" name="email" value="{{ old('email') }}" autocomplete="email" @error('email') aria-invalid="true" aria-describedby="donor-email-error" @enderror placeholder="donor@gmail.com">
                            @error('email')<div class="invalid-feedback" id="donor-email-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="payment-ref">Payment / UPI Reference ID</label>
                            <input type="text" class="form-control @error('payment_ref') is-invalid @enderror" id="payment-ref" name="payment_ref" value="{{ old('payment_ref') }}" aria-describedby="payment-ref-help @error('payment_ref') payment-ref-error @enderror" @error('payment_ref') aria-invalid="true" @enderror placeholder="UPI Ref / Transaction No." required>
                            <div id="payment-ref-help" class="form-text">Complete your UPI payment first, then enter its reference number.</div>
                            @error('payment_ref')<div class="invalid-feedback" id="payment-ref-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-accent btn-lg w-100 fw-bold shadow">
                        <i class="fa-solid fa-check-circle me-2"></i> Submit Donation Record
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="bg-white p-4 rounded-4 shadow-sm border text-center mb-4">
                <h5 class="fw-bold text-dark mb-2">Scan & Pay via UPI</h5>
                <p class="extra-small text-muted mb-3">Scan with Google Pay, PhonePe, Paytm, or BHIM</p>
                
                <div class="p-3 bg-light rounded-3 d-inline-block border mb-3">
                    <img src="{{ asset('images/upi-qr.png') }}" width="180" height="180" class="rounded border" alt="UPI QR">
                </div>

                <div class="alert alert-warning py-2 mb-0 extra-small fw-bold">
                    Official UPI ID: <span class="text-dark">{{ \App\Models\Setting::get('upiId', 'ksochandigarh@upi') }}</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-4 shadow-sm border">
                <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-heart text-danger me-2"></i> Recent Contributors</h5>
                @foreach($donations as $d)
                    <div class="d-flex justify-content-between align-items-center p-2 border-bottom extra-small">
                        <div>
                            <div class="fw-bold text-dark">{{ $d->donor_name }}</div>
                            <div class="text-muted extra-small">{{ $d->cause }}</div>
                        </div>
                        <div class="fw-bold text-success fs-6">+ ₹{{ number_format($d->amount) }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

</div>
@endsection
