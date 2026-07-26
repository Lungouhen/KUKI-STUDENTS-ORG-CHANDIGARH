@extends('layouts.admin')

@section('title', 'Edit Leader - ' . $committee->name)

@section('content')

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h4 class="fw-bold text-primary mb-0"><i class="fa-solid fa-user-gear me-2"></i> Edit Executive Leader</h4>
        <a href="{{ route('admin.committee.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back to List</a>
    </div>

    <form action="{{ route('admin.committee.update', $committee->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $committee->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Designation</label>
                <input type="text" name="designation" class="form-control" value="{{ old('designation', $committee->designation) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Institution / College</label>
                <input type="text" name="institution" class="form-control" value="{{ old('institution', $committee->institution) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $committee->phone) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Tenure</label>
                <input type="text" name="tenure" class="form-control" value="{{ old('tenure', $committee->tenure) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Photo</label>
                <input type="file" name="photoFile" class="form-control" accept="image/*">
            </div>
            <div class="col-12 mt-4 text-end">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2"></i> Update Leader Details</button>
            </div>
        </div>
    </form>
</div>

@endsection
