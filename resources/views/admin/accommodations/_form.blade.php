@if($errors->any())
    <div class="alert alert-danger rounded-4 small">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($accommodation)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-8">
            <label class="form-label extra-small fw-bold">Property Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $accommodation->name ?? '') }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label extra-small fw-bold">Type</label>
            <select name="type" class="form-select" required>
                @foreach(\App\Models\Accommodation::TYPES as $type)
                    <option value="{{ $type }}" @selected(old('type', $accommodation->type ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <label class="form-label extra-small fw-bold">Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $accommodation->location ?? '') }}" placeholder="e.g. Sector 15-B, Chandigarh" required>
        </div>
        <div class="col-md-6">
            <label class="form-label extra-small fw-bold">Landmark / Distance <span class="text-muted">(optional)</span></label>
            <input type="text" name="landmark" class="form-control" value="{{ old('landmark', $accommodation->landmark ?? '') }}" placeholder="e.g. 5 mins to PU">
        </div>
    </div>

    <div class="row g-3 mt-1">
        <div class="col-md-6">
            <label class="form-label extra-small fw-bold">Monthly Rent (₹)</label>
            <input type="number" name="rent_monthly" step="0.01" min="0" class="form-control" value="{{ old('rent_monthly', $accommodation->rent_monthly ?? '') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label extra-small fw-bold">Contact Phone</label>
            <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $accommodation->contact_phone ?? '') }}" placeholder="+91 98765 43210" required>
        </div>
    </div>

    <div class="mt-3">
        <label class="form-label extra-small fw-bold">Description <span class="text-muted">(optional)</span></label>
        <textarea name="description" class="form-control" rows="3" placeholder="Amenities, meals, security, sharing options...">{{ old('description', $accommodation->description ?? '') }}</textarea>
    </div>

    <div class="mt-3">
        <label class="form-label extra-small fw-bold">Photo <span class="text-muted">(optional, max 5MB)</span></label>
        <input type="file" name="photoFile" class="form-control" accept="image/*">
        @if($accommodation && $accommodation->photo)
            <div class="form-text extra-small">Current photo: <a href="{{ asset($accommodation->photo) }}" target="_blank">view</a> — uploading a new file replaces it.</div>
        @endif
    </div>

    <div class="form-check form-switch mt-3">
        <input type="hidden" name="is_active" value="0">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $accommodation->is_active ?? true))>
        <label class="form-check-label extra-small fw-bold" for="is_active">Visible in member portal</label>
    </div>

    <button type="submit" class="btn btn-primary fw-bold px-4 mt-4 shadow">{{ $submitLabel }}</button>
</form>
