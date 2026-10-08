@extends('layouts.app')

@section('title', 'Member Portal Login | KSO Chandigarh')

@section('content')

<div class="member-login-page">
<div class="public-page-banner bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h1 class="fw-black mb-1"><i class="fa-solid fa-right-to-bracket me-2" aria-hidden="true"></i> Member Student Portal</h1>
        <p class="small text-light opacity-90 mb-0">Access your digital membership card, student notices, and updates</p>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/kso-logo.jpg') }}" alt="KSO Chandigarh emblem" class="rounded-circle mb-2 border border-warning" width="60">
                    <h2 class="h4 fw-bold text-primary mb-1">Member Login</h2>
                    <p class="text-muted extra-small">Enter your Membership ID or registered email and your date of birth</p>
                </div>

                <form action="{{ route('membership.portalLogin') }}" method="POST" id="memberPortalLoginForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="member-identifier">Membership ID or Email</label>
                        <input type="text" id="member-identifier" name="identifier" class="form-control @error('identifier') is-invalid @enderror" placeholder="e.g. KSO-CHD-2026-0001 or name@gmail.com" value="{{ old('identifier') }}" autocomplete="username" maxlength="255" @error('identifier') aria-invalid="true" aria-describedby="member-identifier-error" @enderror required>
                        @error('identifier')<div class="invalid-feedback" id="member-identifier-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="member-dob">Date of Birth</label>
                        <input type="date" id="member-dob" name="dob" class="form-control @error('dob') is-invalid @enderror" autocomplete="bday" @error('dob') aria-invalid="true" aria-describedby="member-dob-error" @enderror required>
                        @error('dob')<div class="invalid-feedback" id="member-dob-error">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                        <span id="member-login-label">Login to Portal</span> <i class="fa-solid fa-arrow-right ms-1" aria-hidden="true"></i>
                    </button>
                    <span class="visually-hidden" role="status" aria-live="polite" id="member-login-status"></span>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">Not registered yet? <a href="{{ route('membership.register') }}" class="text-primary fw-bold text-decoration-none">Apply Here</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
