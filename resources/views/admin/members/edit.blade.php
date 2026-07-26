@extends('layouts.admin')

@section('title', 'Edit Member - ' . $member->full_name)

@section('content')

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h4 class="fw-bold text-primary mb-0"><i class="fa-solid fa-user-pen me-2"></i> Edit Member Profile (ID: {{ $member->id }})</h4>
        <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back to List</a>
    </div>

    <form action="{{ route('admin.members.update', $member->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Full Name</label>
                <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $member->full_name) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Gender</label>
                <select name="gender" class="form-select" required>
                    <option value="Male" {{ $member->gender == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $member->gender == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Date of Birth</label>
                <input type="date" name="dob" class="form-control" value="{{ $member->dob ? $member->dob->format('Y-m-d') : '' }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Phone Number</label>
                <input type="tel" name="phone" class="form-control" value="{{ old('phone', $member->phone) }}" required>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-bold">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $member->email) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Blood Group</label>
                <select name="blood_group" class="form-select">
                    <option value="O+" {{ $member->blood_group == 'O+' ? 'selected' : '' }}>O+</option>
                    <option value="A+" {{ $member->blood_group == 'A+' ? 'selected' : '' }}>A+</option>
                    <option value="B+" {{ $member->blood_group == 'B+' ? 'selected' : '' }}>B+</option>
                    <option value="AB+" {{ $member->blood_group == 'AB+' ? 'selected' : '' }}>AB+</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Institution / College</label>
                <input type="text" name="institution" class="form-control" value="{{ old('institution', $member->institution) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Course / Degree</label>
                <input type="text" name="course" class="form-control" value="{{ old('course', $member->course) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Department</label>
                <input type="text" name="department" class="form-control" value="{{ old('department', $member->department) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Year of Study</label>
                <select name="year_of_study" class="form-select" required>
                    <option value="1st Year" {{ $member->year_of_study == '1st Year' ? 'selected' : '' }}>1st Year</option>
                    <option value="2nd Year" {{ $member->year_of_study == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                    <option value="3rd Year" {{ $member->year_of_study == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                    <option value="Post Graduate" {{ $member->year_of_study == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Approval Status</label>
                <select name="status" class="form-select">
                    <option value="Approved" {{ $member->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Pending" {{ $member->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Rejected" {{ $member->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Permanent Address (Manipur)</label>
                <textarea name="permanent_address" class="form-control" rows="2" required>{{ old('permanent_address', $member->permanent_address) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Current Address (Chandigarh)</label>
                <textarea name="current_address" class="form-control" rows="2" required>{{ old('current_address', $member->current_address) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Emergency Contact Person</label>
                <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact', $member->emergency_contact) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Emergency Contact Phone</label>
                <input type="tel" name="emergency_phone" class="form-control" value="{{ old('emergency_phone', $member->emergency_phone) }}" required>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Passport Photo (Leave blank to keep current photo)</label>
                <input type="file" name="photoFile" class="form-control" accept="image/*">
            </div>
            <div class="col-12 mt-4 text-end">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2"></i> Update Member Record</button>
            </div>
        </div>
    </form>
</div>

@endsection
