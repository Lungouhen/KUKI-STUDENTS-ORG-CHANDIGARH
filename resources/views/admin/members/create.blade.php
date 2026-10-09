@extends('layouts.admin')

@section('title', 'Add New Member Manually | KSO CMS')

@section('content')

<div class="admin-member-create-page">
<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h2 class="h4 fw-bold text-primary mb-0"><i class="fa-solid fa-user-plus me-2" aria-hidden="true"></i> Register Member Manually</h2>
        <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1" aria-hidden="true"></i> Back to List</a>
    </div>

    <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data" id="adminMemberCreateForm">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold" for="admin-member-full-name">Full Name <span class="text-danger">*</span></label>
                <input type="text" id="admin-member-full-name" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" autocomplete="name" maxlength="255" @error('full_name') aria-invalid="true" aria-describedby="admin-member-full-name-error" @enderror required>
                @error('full_name')<div class="invalid-feedback" id="admin-member-full-name-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold" for="admin-member-gender">Gender <span class="text-danger">*</span></label>
                <select id="admin-member-gender" name="gender" class="form-select @error('gender') is-invalid @enderror" @error('gender') aria-invalid="true" aria-describedby="admin-member-gender-error" @enderror required>
                    <option value="Male" {{ old('gender', 'Male') === 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender') === 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender')<div class="invalid-feedback" id="admin-member-gender-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold" for="admin-member-dob">Date of Birth</label>
                <input type="date" id="admin-member-dob" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob') }}" autocomplete="bday" @error('dob') aria-invalid="true" aria-describedby="admin-member-dob-error" @enderror>
                @error('dob')<div class="invalid-feedback" id="admin-member-dob-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold" for="admin-member-phone">Phone Number <span class="text-danger">*</span></label>
                <input type="tel" id="admin-member-phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" autocomplete="tel" @error('phone') aria-invalid="true" aria-describedby="admin-member-phone-error" @enderror required>
                @error('phone')<div class="invalid-feedback" id="admin-member-phone-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-5">
                <label class="form-label fw-bold" for="admin-member-email">Email Address <span class="text-danger">*</span></label>
                <input type="email" id="admin-member-email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" autocomplete="email" @error('email') aria-invalid="true" aria-describedby="admin-member-email-error" @enderror required>
                @error('email')<div class="invalid-feedback" id="admin-member-email-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold" for="admin-member-blood-group">Blood Group <span class="text-danger">*</span></label>
                <select id="admin-member-blood-group" name="blood_group" class="form-select @error('blood_group') is-invalid @enderror" @error('blood_group') aria-invalid="true" aria-describedby="admin-member-blood-group-error" @enderror required>
                    @foreach(['O+', 'A+', 'B+', 'AB+', 'O-', 'A-', 'B-', 'AB-'] as $bloodGroup)
                        <option value="{{ $bloodGroup }}" {{ old('blood_group', 'O+') === $bloodGroup ? 'selected' : '' }}>{{ $bloodGroup }}</option>
                    @endforeach
                </select>
                @error('blood_group')<div class="invalid-feedback" id="admin-member-blood-group-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="admin-member-institution">Institution / College <span class="text-danger">*</span></label>
                <input type="text" id="admin-member-institution" name="institution" class="form-control @error('institution') is-invalid @enderror" value="{{ old('institution') }}" @error('institution') aria-invalid="true" aria-describedby="admin-member-institution-error" @enderror required>
                @error('institution')<div class="invalid-feedback" id="admin-member-institution-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="admin-member-course">Course / Degree <span class="text-danger">*</span></label>
                <input type="text" id="admin-member-course" name="course" class="form-control @error('course') is-invalid @enderror" value="{{ old('course') }}" @error('course') aria-invalid="true" aria-describedby="admin-member-course-error" @enderror required>
                @error('course')<div class="invalid-feedback" id="admin-member-course-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold" for="admin-member-department">Department</label>
                <input type="text" id="admin-member-department" name="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department') }}" @error('department') aria-invalid="true" aria-describedby="admin-member-department-error" @enderror>
                @error('department')<div class="invalid-feedback" id="admin-member-department-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold" for="admin-member-year">Year of Study <span class="text-danger">*</span></label>
                <select id="admin-member-year" name="year_of_study" class="form-select @error('year_of_study') is-invalid @enderror" @error('year_of_study') aria-invalid="true" aria-describedby="admin-member-year-error" @enderror required>
                    @foreach(['1st Year', '2nd Year', '3rd Year', '4th Year', 'Post Graduate', 'Research Scholar'] as $year)
                        <option value="{{ $year }}" {{ old('year_of_study', '1st Year') === $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                @error('year_of_study')<div class="invalid-feedback" id="admin-member-year-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold" for="admin-member-status">Approval Status</label>
                <select id="admin-member-status" name="status" class="form-select @error('status') is-invalid @enderror" @error('status') aria-invalid="true" aria-describedby="admin-member-status-error" @enderror required>
                    <option value="Approved" {{ old('status', 'Approved') === 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Rejected" {{ old('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status')<div class="invalid-feedback" id="admin-member-status-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="admin-member-permanent-address">Permanent Address (Manipur)</label>
                <textarea id="admin-member-permanent-address" name="permanent_address" class="form-control @error('permanent_address') is-invalid @enderror" rows="2" autocomplete="street-address" @error('permanent_address') aria-invalid="true" aria-describedby="admin-member-permanent-address-error" @enderror required>{{ old('permanent_address') }}</textarea>
                @error('permanent_address')<div class="invalid-feedback" id="admin-member-permanent-address-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="admin-member-current-address">Current Address (Chandigarh)</label>
                <textarea id="admin-member-current-address" name="current_address" class="form-control @error('current_address') is-invalid @enderror" rows="2" autocomplete="street-address" @error('current_address') aria-invalid="true" aria-describedby="admin-member-current-address-error" @enderror required>{{ old('current_address') }}</textarea>
                @error('current_address')<div class="invalid-feedback" id="admin-member-current-address-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="admin-member-emergency-contact">Emergency Contact Person</label>
                <input type="text" id="admin-member-emergency-contact" name="emergency_contact" class="form-control @error('emergency_contact') is-invalid @enderror" value="{{ old('emergency_contact') }}" @error('emergency_contact') aria-invalid="true" aria-describedby="admin-member-emergency-contact-error" @enderror required>
                @error('emergency_contact')<div class="invalid-feedback" id="admin-member-emergency-contact-error">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold" for="admin-member-emergency-phone">Emergency Contact Phone</label>
                <input type="tel" id="admin-member-emergency-phone" name="emergency_phone" class="form-control @error('emergency_phone') is-invalid @enderror" value="{{ old('emergency_phone') }}" autocomplete="tel" @error('emergency_phone') aria-invalid="true" aria-describedby="admin-member-emergency-phone-error" @enderror required>
                @error('emergency_phone')<div class="invalid-feedback" id="admin-member-emergency-phone-error">{{ $message }}</div>@enderror
            </div>
            @foreach($customFields as $field)
                <div class="col-md-6">
                    @php
                        $name = 'custom_fields.' . $field->slug;
                        $value = old($name);
                    @endphp
                    @if($field->field_type === 'textarea')
                        <label class="form-label fw-bold" for="custom-field-{{ $field->slug }}">{{ $field->label }}{{ $field->is_required ? ' <span class="text-danger">*</span>' : '' }}</label>
                        <textarea id="custom-field-{{ $field->slug }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" rows="3" @if($field->is_required) required @endif>{{ $value }}</textarea>
                    @elseif($field->field_type === 'select' || $field->field_type === 'radio')
                        <label class="form-label fw-bold" for="custom-field-{{ $field->slug }}">{{ $field->label }}{{ $field->is_required ? ' <span class="text-danger">*</span>' : '' }}</label>
                        <select id="custom-field-{{ $field->slug }}" name="{{ $name }}" class="form-select @error($name) is-invalid @enderror" @if($field->is_required) required @endif>
                            <option value="">Select {{ $field->label }}</option>
                            @foreach($field->optionList() as $option)
                                <option value="{{ $option }}" {{ $value === $option ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>
                    @elseif($field->field_type === 'checkbox')
                        <div class="form-check mt-4">
                            <input id="custom-field-{{ $field->slug }}" type="checkbox" name="{{ $name }}" value="1" class="form-check-input @error($name) is-invalid @enderror" {{ $value == 1 ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="custom-field-{{ $field->slug }}">{{ $field->label }}{{ $field->is_required ? ' <span class="text-danger">*</span>' : '' }}</label>
                        </div>
                    @else
                        <label class="form-label fw-bold" for="custom-field-{{ $field->slug }}">{{ $field->label }}{{ $field->is_required ? ' <span class="text-danger">*</span>' : '' }}</label>
                        <input id="custom-field-{{ $field->slug }}" type="{{ $field->field_type === 'number' ? 'number' : ($field->field_type === 'date' ? 'date' : 'text') }}" name="{{ $name }}" value="{{ $value }}" class="form-control @error($name) is-invalid @enderror" @if($field->is_required) required @endif>
                    @endif
                    @error($name)
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            @endforeach
            <div class="col-12">
                <label class="form-label fw-bold" for="admin-member-photo">Passport Photo</label>
                <input type="file" id="admin-member-photo" name="photoFile" class="form-control @error('photoFile') is-invalid @enderror" accept="image/*" aria-describedby="admin-member-photo-help @error('photoFile')admin-member-photo-error @enderror admin-member-photo-status" @error('photoFile') aria-invalid="true" @enderror>
                @error('photoFile')<div class="invalid-feedback" id="admin-member-photo-error">{{ $message }}</div>@enderror
                <small class="d-block text-muted mt-2" id="admin-member-photo-help">Optional. Image files up to 5 MB.</small>
                <span class="visually-hidden" id="admin-member-photo-status" role="status" aria-live="polite"></span>
            </div>
            <div class="col-12 mt-4 text-end">
                <span class="visually-hidden" id="admin-member-form-status" role="status" aria-live="polite"></span>
                <button type="submit" id="admin-member-save" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2" aria-hidden="true"></i> <span id="admin-member-save-label">Save Member</span></button>
            </div>
        </div>
    </form>
</div>

</div>
@endsection
