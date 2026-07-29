@extends('layouts.admin')

@section('title', 'Edit Partner')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Edit Partner</h4>
        <p class="text-muted extra-small mb-0">Update the details for {{ $partner->name }}.</p>
    </div>
    <a href="{{ route('admin.partners.index') }}" class="btn btn-light border btn-sm rounded-pill px-3">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Partners
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Organization / Partner Name</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $partner->name) }}" required>
                        @error('name')<div class="text-danger extra-small mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label extra-small fw-bold">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control"
                                   value="{{ old('contact_person', $partner->contact_person) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label extra-small fw-bold">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $partner->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label extra-small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $partner->email) }}">
                            @error('email')<div class="text-danger extra-small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label extra-small fw-bold">Type</label>
                            <select name="type" class="form-select" required>
                                @foreach(['Donor', 'Collaborator', 'Sponsor'] as $type)
                                    <option value="{{ $type }}" @selected(old('type', $partner->type) === $type)>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label extra-small fw-bold">Category</label>
                            <select name="category" class="form-select" required>
                                @foreach(['NGO', 'Corporate', 'Government', 'Individual'] as $category)
                                    <option value="{{ $category }}" @selected(old('category', $partner->category) === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label extra-small fw-bold">Status</label>
                            <select name="status" class="form-select" required>
                                @foreach(['Active', 'Inactive'] as $status)
                                    <option value="{{ $status }}" @selected(old('status', $partner->status) === $status)>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label extra-small fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="2">{{ old('address', $partner->address) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label extra-small fw-bold">Internal Notes</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $partner->notes) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                        </button>
                        <a href="{{ route('admin.partners.index') }}" class="btn btn-light border rounded-pill px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
