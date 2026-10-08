@extends('layouts.admin')

@section('title', 'Edit Leader - ' . $committee->name)

@section('content')

<div class="admin-committee-edit-page">
    @if($errors->any())
        <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="committeeEditErrorsHeading">
            <h2 id="committeeEditErrorsHeading" class="h6 fw-bold">Review the executive member details</h2>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h1 class="h4 fw-bold text-primary mb-0"><i class="fa-solid fa-user-gear me-2" aria-hidden="true"></i> Edit Executive Leader</h1>
        <a href="{{ route('admin.committee.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Back to List</a>
    </div>

    <form action="{{ route('admin.committee.update', $committee->id) }}" method="POST" enctype="multipart/form-data" class="committee-edit-form">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label for="committeeEditName" class="form-label fw-bold">Full Name</label>
                <input id="committeeEditName" type="text" name="name" class="form-control" value="{{ old('name', $committee->name) }}" maxlength="255" required>
            </div>
            <div class="col-md-6">
                <label for="committeeEditDesignation" class="form-label fw-bold">Designation</label>
                <input id="committeeEditDesignation" type="text" name="designation" class="form-control" value="{{ old('designation', $committee->designation) }}" required>
            </div>
            <div class="col-md-6">
                <label for="committeeEditInstitution" class="form-label fw-bold">Institution / College</label>
                <input id="committeeEditInstitution" type="text" name="institution" class="form-control" value="{{ old('institution', $committee->institution) }}" required>
            </div>
            <div class="col-md-6">
                <label for="committeeEditPhone" class="form-label fw-bold">Phone Number</label>
                <input id="committeeEditPhone" type="tel" name="phone" class="form-control" value="{{ old('phone', $committee->phone) }}">
            </div>
            <div class="col-md-6">
                <label for="committeeEditTenure" class="form-label fw-bold">Tenure</label>
                <input id="committeeEditTenure" type="text" name="tenure" class="form-control" value="{{ old('tenure', $committee->tenure) }}" required>
            </div>
            <div class="col-md-6">
                <label for="committeeEditPhoto" class="form-label fw-bold">Photo</label>
                <input id="committeeEditPhoto" type="file" name="photoFile" class="form-control" accept="image/*" aria-describedby="committeeEditPhotoHelp">
                <div id="committeeEditPhotoHelp" class="form-text">Optional image, maximum 5 MB.</div>
                @if($committee->photo)
                    <div class="committee-current-photo mt-3">
                        <img src="{{ asset($committee->photo) }}" alt="Current photo of {{ $committee->name }}">
                    </div>
                @endif
                <div id="committeeEditPhotoPreview" class="committee-photo-preview mt-3" hidden>
                    <img id="committeeEditPhotoPreviewImage" alt="Selected executive member photo preview">
                    <p id="committeeEditPhotoPreviewStatus" class="small text-muted mb-0" role="status" aria-live="polite"></p>
                </div>
            </div>
            <div class="col-12 mt-4 text-end">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2" aria-hidden="true"></i> Update Leader Details</button>
            </div>
        </div>
    </form>
</div>

</div>
@endsection
