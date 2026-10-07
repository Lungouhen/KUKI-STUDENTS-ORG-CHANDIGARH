@extends('layouts.admin')

@section('title', 'Add Accommodation | KSO Admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold text-dark mb-0">Add Verified Accommodation</h4>
    <a href="{{ route('admin.accommodations.index') }}" class="btn btn-light border shadow-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back to Listings</a>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4" style="max-width: 760px;">
    @include('admin.accommodations._form', ['accommodation' => null, 'action' => route('admin.accommodations.store'), 'submitLabel' => 'Add Listing'])
</div>

@endsection
