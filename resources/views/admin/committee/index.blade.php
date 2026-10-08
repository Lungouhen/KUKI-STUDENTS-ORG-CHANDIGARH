@extends('layouts.admin')

@section('title', 'Executive Body Directory | KSO CMS')

@section('content')

<div class="admin-committee-page">
    @if(session('success'))
        <div class="alert alert-success rounded-4 small" role="status" aria-live="polite">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger rounded-4 small" role="alert" aria-labelledby="committeeErrorsHeading">
            <h2 id="committeeErrorsHeading" class="h6 fw-bold">Review the executive member details</h2>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white p-3">
                <h2 class="h5 fw-bold text-primary mb-0"><i class="fa-solid fa-users-gear me-2" aria-hidden="true"></i> Executive Council Members</h2>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 extra-small">
                        <caption class="visually-hidden">Current executive council members</caption>
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Designation</th>
                                <th scope="col">Institution</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Tenure</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($committee as $c)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $c->name }}</td>
                                    <td><span class="badge text-white committee-designation">{{ $c->designation }}</span></td>
                                    <td>{{ $c->institution }}</td>
                                    <td>{{ $c->phone }}</td>
                                    <td>{{ $c->tenure }}</td>
                                    <td>
                                        <form action="{{ route('admin.committee.destroy', $c->id) }}" method="POST" class="d-inline committee-delete-form" data-confirm-message="Remove {{ $c->name }} from the executive council?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Remove {{ $c->name }} from the executive council"><i class="fa-solid fa-trash" aria-hidden="true"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No executive council members have been added.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h2 class="h5 fw-bold text-primary mb-3"><i class="fa-solid fa-user-plus me-2" aria-hidden="true"></i> Add Executive Member</h2>
            <form action="{{ route('admin.committee.store') }}" method="POST" enctype="multipart/form-data" class="committee-create-form">
                @csrf
                <div class="mb-3">
                    <label for="committeeName" class="form-label fw-bold">Full Name</label>
                    <input id="committeeName" type="text" name="name" class="form-control" value="{{ old('name') }}" maxlength="255" required>
                </div>
                <div class="mb-3">
                    <label for="committeeDesignation" class="form-label fw-bold">Designation</label>
                    <input id="committeeDesignation" type="text" name="designation" class="form-control" value="{{ old('designation') }}" placeholder="e.g. President / Gen Sec" required>
                </div>
                <div class="mb-3">
                    <label for="committeeInstitution" class="form-label fw-bold">Institution</label>
                    <input id="committeeInstitution" type="text" name="institution" class="form-control" value="{{ old('institution') }}" required>
                </div>
                <div class="mb-3">
                    <label for="committeePhone" class="form-label fw-bold">Phone</label>
                    <input id="committeePhone" type="tel" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="mb-3">
                    <label for="committeeTenure" class="form-label fw-bold">Tenure</label>
                    <input id="committeeTenure" type="text" name="tenure" class="form-control" value="{{ old('tenure', '2025 - 2026') }}" required>
                </div>
                <div class="mb-3">
                    <label for="committeePhoto" class="form-label fw-bold">Photo</label>
                    <input id="committeePhoto" type="file" name="photoFile" class="form-control" accept="image/*" aria-describedby="committeePhotoHelp">
                    <div id="committeePhotoHelp" class="form-text">Optional image, maximum 5 MB.</div>
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Save Leader</button>
            </form>
        </div>
    </div>
</div>

</div>
@endsection
