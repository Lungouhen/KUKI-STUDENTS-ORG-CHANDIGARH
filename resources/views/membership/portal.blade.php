@extends('layouts.app')

@section('title', 'Member Portal Login | KSO Chandigarh')

@section('content')

<div class="bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h2 class="fw-black mb-1"><i class="fa-solid fa-right-to-bracket me-2"></i> Member Student Portal</h2>
        <p class="small text-light opacity-90 mb-0">Access your digital membership card, student notices, and updates</p>
    </div>
</div>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/kso-logo.jpg') }}" class="rounded-circle mb-2 border border-warning" width="60" onerror="this.src='/images/default-avatar-m.png'">
                    <h4 class="fw-bold text-primary mb-1">Member Login</h4>
                    <p class="text-muted extra-small">Confirm your identity with your Membership ID and date of birth</p>
                </div>

                <form action="{{ route('membership.portalLogin') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Membership ID or Email</label>
                        <input type="text" name="identifier" class="form-control"
                               value="{{ old('identifier') }}"
                               placeholder="e.g. KSO-CHD-2026-0001 or name@gmail.com" required>
                        @error('identifier')<div class="text-danger extra-small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" required>
                        <div class="form-text extra-small">Used to confirm the account belongs to you.</div>
                        @error('dob')<div class="text-danger extra-small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">Login to Portal <i class="fa-solid fa-arrow-right ms-1"></i></button>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">Not registered yet? <a href="{{ route('membership.register') }}" class="text-primary fw-bold text-decoration-none">Apply Here</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
