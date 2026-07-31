@extends('layouts.app')

@section('title', 'Page Not Found | KSO Chandigarh')

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 70vh; text-align: center;">
    <div class="display-1 fw-black text-primary mb-3" style="font-size: 8rem; letter-spacing: -0.05em;">404</div>
    <h2 class="fw-black mb-2">Page Not Found</h2>
    <p class="text-muted mb-4">The page you are looking for does not exist or has been moved.</p>
    <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-4">Go Home</a>
</div>
@endsection
