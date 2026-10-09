@extends('layouts.admin')

@section('title', 'Custom Member Fields | KSO CMS')

@section('content')
<div class="admin-member-custom-fields-page">
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4 flex-wrap gap-2">
            <h2 class="h4 fw-bold text-primary mb-0"><i class="fa-solid fa-table-columns me-2" aria-hidden="true"></i> Custom Member Fields</h2>
            <div class="small text-muted">Add extra member attributes, select values, and enum-style fields.</div>
        </div>

        <form action="{{ route('admin.memberCustomFields.store') }}" method="POST" class="row g-3">
            @csrf
            <div class="col-md-4">
                <label class="form-label fw-bold" for="member-custom-field-label">Field Label</label>
                <input id="member-custom-field-label" type="text" name="label" class="form-control" value="{{ old('label') }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold" for="member-custom-field-type">Field Type</label>
                <select id="member-custom-field-type" name="field_type" class="form-select" required>
                    @foreach(['text', 'textarea', 'number', 'date', 'select', 'radio', 'checkbox'] as $type)
                        <option value="{{ $type }}" {{ old('field_type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold" for="member-custom-field-order">Sort Order</label>
                <input id="member-custom-field-order" type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="member-custom-field-required" name="is_required" value="1" {{ old('is_required') ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold" for="member-custom-field-required">Required</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold" for="member-custom-field-options">Choices (for select/radio)</label>
                <textarea id="member-custom-field-options" name="options" class="form-control" rows="3" placeholder="One option per line or comma-separated values">{{ old('options') }}</textarea>
                <small class="text-muted">Example: General, OBC, SC, ST</small>
            </div>
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold"><i class="fa-solid fa-plus me-2" aria-hidden="true"></i> Add field</button>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Field</th>
                        <th scope="col">Type</th>
                        <th scope="col">Required</th>
                        <th scope="col">Active</th>
                        <th scope="col">Choices</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fields as $field)
                        <tr>
                            <td>
                                <div class="fw-bold text-primary">{{ $field->label }}</div>
                                <div class="small text-muted">{{ $field->slug }}</div>
                            </td>
                            <td><span class="badge bg-secondary">{{ strtoupper($field->field_type) }}</span></td>
                            <td>{{ $field->is_required ? 'Yes' : 'No' }}</td>
                            <td>{{ $field->is_active ? 'Yes' : 'No' }}</td>
                            <td>{{ $field->optionList() ? implode(', ', $field->optionList()) : '—' }}</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <form action="{{ route('admin.memberCustomFields.update', $field->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="label" value="{{ $field->label }}">
                                        <input type="hidden" name="field_type" value="{{ $field->field_type }}">
                                        <input type="hidden" name="sort_order" value="{{ $field->sort_order }}">
                                        <input type="hidden" name="is_required" value="{{ $field->is_required ? 1 : 0 }}">
                                        <input type="hidden" name="is_active" value="{{ $field->is_active ? 1 : 0 }}">
                                        <input type="hidden" name="options" value="{{ implode(', ', $field->optionList()) }}">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Toggle status</button>
                                    </form>
                                    <form action="{{ route('admin.memberCustomFields.destroy', $field->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this field from all members?')">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No custom member fields yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
