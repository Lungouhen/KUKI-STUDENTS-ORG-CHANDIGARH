@extends('layouts.app')

@section('title', 'Forbidden | KSO Chandigarh')

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 70vh; text-align: center;">
    <div class="display-1 fw-black text-warning mb-3" style="font-size: 8rem; letter-spacing: -0.05em;">403</div>
    <h2 class="fw-black mb-2">Access Forbidden</h2>
    <p class="text-muted mb-4">You don't have permission to access this resource.</p>
    <a href="{{ url('/') }}" class="btn btn-primary rounded-pill px-4">Go Home</a>
</div>
@endsection
