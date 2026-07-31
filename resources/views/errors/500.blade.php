@extends('layouts.app')

@section('title', 'Server Error | KSO Chandigarh')

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 70vh; text-align: center;">
    <div class="display-1 fw-black text-danger mb-3" style="font-size: 8rem; letter-spacing: -0.05em;">500</div>
    <h2 class="fw-black mb-2">Internal Server Error</h2>
    <p class="text-muted mb-4">Something went wrong on our side. Please try again later.</p>
    <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-4">Go Home</a>
</div>
@endsection
