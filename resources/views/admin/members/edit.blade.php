@extends('layouts.admin')

@section('title', 'Edit Member - ' . $member->full_name)

@section('content')

<div class="admin-member-edit-page">
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h2 class="h4 fw-bold text-primary mb-0"><i class="fa-solid fa-user-pen me-2" aria-hidden="true"></i> Edit Member Profile (ID: {{ $member->id }})</h2>
        <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Back to List</a>
    </div>

    <form action="{{ route('admin.members.update', $member->id) }}" method="POST" enctype="multipart/form-data" id="adminMemberEditForm">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold" for="admin-member-edit-full-name">Full Name</label>
                <input type="text" id="admin-member-edit-full-name" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $member->full_name) }}" autocomplete="name" maxlength="255" @error('full_name') aria-invalid="true" aria-describedby="admin-member-edit-full-name-error" @enderror required>
                @error('full_name')<div class="invalid-feedback" id="admin-member-edit-full-name-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold" for="admin-member-edit-gender">Gender</label>
                <select id="admin-member-edit-gender" name="gender" class="form-select @error('gender') is-invalid @enderror" @error('gender') aria-invalid="true" aria-describedby="admin-member-edit-gender-error" @enderror required>
                    <option value="Male" {{ old('gender', $member->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $member->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender', $member->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender')<div class="invalid-feedback" id="admin-member-edit-gender-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold" for="admin-member-edit-dob">Date of Birth</label>
                <input type="date" id="admin-member-edit-dob" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob', $member->dob?->format('Y-m-d')) }}" autocomplete="bday" @error('dob') aria-invalid="true" aria-describedby="admin-member-edit-dob-error" @enderror>
                @error('dob')<div class="invalid-feedback" id="admin-member-edit-dob-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold" for="admin-member-edit-phone">Phone Number</label>
                <input type="tel" id="admin-member-edit-phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $member->phone) }}" autocomplete="tel" @error('phone') aria-invalid="true" aria-describedby="admin-member-edit-phone-error" @enderror required>
                @error('phone')<div class="invalid-feedback" id="admin-member-edit-phone-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-5">
                <label class="form-label fw-bold" for="admin-member-edit-email">Email Address</label>
                <input type="email" id="admin-member-edit-email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $member->email) }}" autocomplete="email" @error('email') aria-invalid="true" aria-describedby="admin-member-edit-email-error" @enderror required>
                @error('email')<div class="invalid-feedback" id="admin-member-edit-email-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold" for="admin-member-edit-blood-group">Blood Group</label>
                <select id="admin-member-edit-blood-group" name="blood_group" class="form-select @error('blood_group') is-invalid @enderror" @error('blood_group') aria-invalid="true" aria-describedby="admin-member-edit-blood-group-error" @enderror required>
                    @foreach(['O+', 'A+', 'B+', 'AB+', 'O-', 'A-', 'B-', 'AB-'] as $bloodGroup)
                        <option value="{{ $bloodGroup }}" {{ old('blood_group', $member->blood_group) === $bloodGroup ? 'selected' : '' }}>{{ $bloodGroup }}</option>
                    @endforeach
                </select>
                @error('blood_group')<div class="invalid-feedback" id="admin-member-edit-blood-group-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="admin-member-edit-institution">Institution / College</label>
                <input type="text" id="admin-member-edit-institution" name="institution" class="form-control @error('institution') is-invalid @enderror" value="{{ old('institution', $member->institution) }}" @error('institution') aria-invalid="true" aria-describedby="admin-member-edit-institution-error" @enderror required>
                @error('institution')<div class="invalid-feedback" id="admin-member-edit-institution-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="admin-member-edit-course">Course / Degree</label>
                <input type="text" id="admin-member-edit-course" name="course" class="form-control @error('course') is-invalid @enderror" value="{{ old('course', $member->course) }}" @error('course') aria-invalid="true" aria-describedby="admin-member-edit-course-error" @enderror required>
                @error('course')<div class="invalid-feedback" id="admin-member-edit-course-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold" for="admin-member-edit-department">Department</label>
                <input type="text" id="admin-member-edit-department" name="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department', $member->department) }}" @error('department') aria-invalid="true" aria-describedby="admin-member-edit-department-error" @enderror>
                @error('department')<div class="invalid-feedback" id="admin-member-edit-department-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold" for="admin-member-edit-year">Year of Study</label>
                <select id="admin-member-edit-year" name="year_of_study" class="form-select @error('year_of_study') is-invalid @enderror" @error('year_of_study') aria-invalid="true" aria-describedby="admin-member-edit-year-error" @enderror required>
                    @foreach(['1st Year', '2nd Year', '3rd Year', '4th Year', 'Post Graduate', 'Research Scholar'] as $year)
                        <option value="{{ $year }}" {{ old('year_of_study', $member->year_of_study) === $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                @error('year_of_study')<div class="invalid-feedback" id="admin-member-edit-year-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold" for="admin-member-edit-status">Approval Status</label>
                <select id="admin-member-edit-status" name="status" class="form-select @error('status') is-invalid @enderror" @error('status') aria-invalid="true" aria-describedby="admin-member-edit-status-error" @enderror required>
                    <option value="Approved" {{ old('status', $member->status) === 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Pending" {{ old('status', $member->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Rejected" {{ old('status', $member->status) === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status')<div class="invalid-feedback" id="admin-member-edit-status-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="admin-member-edit-permanent-address">Permanent Address (Manipur)</label>
                <textarea id="admin-member-edit-permanent-address" name="permanent_address" class="form-control @error('permanent_address') is-invalid @enderror" rows="2" autocomplete="street-address" @error('permanent_address') aria-invalid="true" aria-describedby="admin-member-edit-permanent-address-error" @enderror required>{{ old('permanent_address', $member->permanent_address) }}</textarea>
                @error('permanent_address')<div class="invalid-feedback" id="admin-member-edit-permanent-address-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="admin-member-edit-current-address">Current Address (Chandigarh)</label>
                <textarea id="admin-member-edit-current-address" name="current_address" class="form-control @error('current_address') is-invalid @enderror" rows="2" autocomplete="street-address" @error('current_address') aria-invalid="true" aria-describedby="admin-member-edit-current-address-error" @enderror required>{{ old('current_address', $member->current_address) }}</textarea>
                @error('current_address')<div class="invalid-feedback" id="admin-member-edit-current-address-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="admin-member-edit-emergency-contact">Emergency Contact Person</label>
                <input type="text" id="admin-member-edit-emergency-contact" name="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror" value="{{ old('emergency_contact', $member->emergency_contact) }}" @error('emergency_contact') aria-invalid="true" aria-describedby="admin-member-edit-emergency-contact-error" @enderror required>
                @error('emergency_contact')<div class="invalid-feedback" id="admin-member-edit-emergency-contact-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="admin-member-edit-emergency-phone">Emergency Contact Phone</label>
                <input type="tel" id="admin-member-edit-emergency-phone" name="emergency_phone" class="form-control @error('emergency_phone') is-invalid @enderror" value="{{ old('emergency_phone', $member->emergency_phone) }}" autocomplete="tel" @error('emergency_phone') aria-invalid="true" aria-describedby="admin-member-edit-emergency-phone-error" @enderror required>
                @error('emergency_phone')<div class="invalid-feedback" id="admin-member-edit-emergency-phone-error">{{ $message }}</div>@enderror
            </div>
            @foreach($customFields as $field)
                @php
                    $value = $member->customFieldValues()->where('field_id', $field->id)->value('value');
                    $name = 'custom_fields.' . $field->slug;
                @endphp
                <div class="col-md-6">
                    @if($field->field_type === 'textarea')
                        <label class="form-label fw-bold" for="custom-field-edit-{{ $field->slug }}">{{ $field->label }}{{ $field->is_required ? ' <span class="text-danger">*</span>' : '' }}</label>
                        <textarea id="custom-field-edit-{{ $field->slug }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" rows="3" @if($field->is_required) required @endif>{{ old($name, $value) }}</textarea>
                    @elseif($field->field_type === 'select' || $field->field_type === 'radio')
                        <label class="form-label fw-bold" for="custom-field-edit-{{ $field->slug }}">{{ $field->label }}{{ $field->is_required ? ' <span class="text-danger">*</span>' : '' }}</label>
                        <select id="custom-field-edit-{{ $field->slug }}" name="{{ $name }}" class="form-select @error($name) is-invalid @enderror" @if($field->is_required) required @endif>
                            <option value="">Select {{ $field->label }}</option>
                            @foreach($field->optionList() as $option)
                                <option value="{{ $option }}" {{ old($name, $value) === $option ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>
                    @elseif($field->field_type === 'checkbox')
                        <div class="form-check mt-4">
                            <input id="custom-field-edit-{{ $field->slug }}" type="checkbox" name="{{ $name }}" value="1" class="form-check-input @error($name) is-invalid @enderror" {{ old($name, $value) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="custom-field-edit-{{ $field->slug }}">{{ $field->label }}{{ $field->is_required ? ' <span class="text-danger">*</span>' : '' }}</label>
                        </div>
                    @else
                        <label class="form-label fw-bold" for="custom-field-edit-{{ $field->slug }}">{{ $field->label }}{{ $field->is_required ? ' <span class="text-danger">*</span>' : '' }}</label>
                        <input id="custom-field-edit-{{ $field->slug }}" type="{{ $field->field_type === 'number' ? 'number' : ($field->field_type === 'date' ? 'date' : 'text') }}" name="{{ $name }}" value="{{ old($name, $value) }}" class="form-control @error($name) is-invalid @enderror" @if($field->is_required) required @endif>
                    @endif
                    @error($name)
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach
            <div class="col-12">
                <label class="form-label fw-bold" for="admin-member-edit-photo">Passport Photo</label>
                <div class="admin-member-edit-photo-preview mb-2">
                    <img src="{{ asset($member->photo) }}" alt="Current photo for {{ $member->full_name }}" width="72" height="72" loading="lazy" onerror="this.src='/images/default-avatar-m.png'">
                    <small class="text-muted">Leave the upload blank to keep this photo.</small>
                </div>
                <input type="file" id="admin-member-edit-photo" name="photoFile" class="form-control @error('photoFile') is-invalid @enderror" accept="image/*" aria-describedby="admin-member-edit-photo-help @error('photoFile')admin-member-edit-photo-error @enderror admin-member-edit-photo-status" @error('photoFile') aria-invalid="true" @enderror>
                @error('photoFile')<div class="invalid-feedback" id="admin-member-edit-photo-error">{{ $message }}</div>@enderror
                <small class="d-block text-muted mt-2" id="admin-member-edit-photo-help">Optional. Image files up to 5 MB.</small>
                <span class="visually-hidden" id="admin-member-edit-photo-status" role="status" aria-live="polite"></span>
            </div>
            <div class="col-12 mt-4 text-end">
                <span class="visually-hidden" id="admin-member-edit-status-message" role="status" aria-live="polite"></span>
                <button type="submit" id="admin-member-edit-save" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2" aria-hidden="true"></i> <span id="admin-member-edit-save-label">Update Member Record</span></button>
            </div>
        </div>
    </form>
</div>

</div>
@endsection
