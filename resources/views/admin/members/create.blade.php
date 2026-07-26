@extends('layouts.admin')

@section('title', 'Add New Member Manually | KSO CMS')

@section('content')

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <h4 class="fw-bold text-primary mb-0"><i class="fa-solid fa-user-plus me-2"></i> Register Member Manually (Admin)</h4>
        <a href="{{ route('admin.members.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Back to List</a>
    </div>

    <form action="{{ route('admin.members.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Gender <span class="text-danger">*</span></label>
                <select name="gender" class="form-select" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Date of Birth</label>
                <input type="date" name="dob" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
                <input type="tel" name="phone" class="form-control" required>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold">Blood Group</label>
                <select name="blood_group" class="form-select">
                    <option value="O+">O+</option>
                    <option value="A+">A+</option>
                    <option value="B+">B+</option>
                    <option value="AB+">AB+</option>
                    <option value="O-">O-</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Institution / College <span class="text-danger">*</span></label>
                <input type="text" name="institution" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Course / Degree <span class="text-danger">*</span></label>
                <input type="text" name="course" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Department</label>
                <input type="text" name="department" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Year of Study <span class="text-danger">*</span></label>
                <select name="year_of_study" class="form-select" required>
                    <option value="1st Year">1st Year</option>
                    <option value="2nd Year">2nd Year</option>
                    <option value="3rd Year">3rd Year</option>
                    <option value="Post Graduate">Post Graduate</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Approval Status</label>
                <select name="status" class="form-select">
                    <option value="Approved">Approved</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Permanent Address (Manipur)</label>
                <textarea name="permanent_address" class="form-control" rows="2" required></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Current Address (Chandigarh)</label>
                <textarea name="current_address" class="form-control" rows="2" required></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Emergency Contact Person</label>
                <input type="text" name="emergency_contact" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Emergency Contact Phone</label>
                <input type="tel" name="emergency_phone" class="form-control" required>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Passport Photo</label>
                <input type="file" name="photoFile" class="form-control" accept="image/*">
            </div>
            <div class="col-12 mt-4 text-end">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold"><i class="fa-solid fa-save me-2"></i> Save Member</button>
            </div>
        </div>
    </form>
</div>

@endsection
