@extends('layouts.app')

@section('title', 'Membership Registration | KSO Chandigarh')

@section('content')

<div class="bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h2 class="fw-black mb-1">KSO Chandigarh Membership Registration</h2>
        <p class="small text-light opacity-90 mb-0">Register online to get your official verified Digital Membership ID Card</p>
    </div>
</div>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold text-primary mb-0"><i class="fa-solid fa-id-card text-teal me-2"></i> Student Membership Form (2025 - 2026)</h4>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fa-solid fa-clock me-1"></i> Quick Application</span>
                </div>
                <div class="card-body p-4 p-md-5">

                    <form action="{{ route('membership.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">

                            <!-- Section 1: Personal Information -->
                            <div class="col-12"><h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-user text-primary me-2"></i> 1. Personal Details</h5></div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Full Name (as in College ID) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror" name="full_name" value="{{ old('full_name') }}" placeholder="e.g. Seinthang Haokip" required>
                                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" name="gender" required>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="dob" value="{{ old('dob') }}" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="+91 9876543210" required>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="name@gmail.com" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Blood Group <span class="text-danger">*</span></label>
                                <select class="form-select" name="blood_group" required>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                </select>
                            </div>

                            <!-- Section 2: Academic Details -->
                            <div class="col-12 mt-4"><h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-graduation-cap text-primary me-2"></i> 2. College / Academic Details in Chandigarh</h5></div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">College / Institution Name <span class="text-danger">*</span></label>
                                <select class="form-select" name="institution" required>
                                    <option value="">-- Select Institution --</option>
                                    <option value="Panjab University, Sector 14">Panjab University, Sector 14</option>
                                    <option value="MCM DAV College for Women, Sector 36">MCM DAV College for Women, Sector 36</option>
                                    <option value="DAV College, Sector 10">DAV College, Sector 10</option>
                                    <option value="Post Graduate Govt College, Sector 11">PGGC Sector 11 (Men)</option>
                                    <option value="Post Graduate Govt College for Girls, Sector 11">PGGCG Sector 11 (Women)</option>
                                    <option value="Punjab Engineering College (PEC), Sector 12">Punjab Engineering College (PEC)</option>
                                    <option value="Sri Guru Gobind Singh College, Sector 26">SGGS College Sector 26</option>
                                    <option value="GGDSD College, Sector 32">GGDSD College Sector 32</option>
                                    <option value="Government Medical College & Hospital (GMCH 32)">GMCH Sector 32</option>
                                    <option value="PGIMER Chandigarh">PGIMER Chandigarh</option>
                                    <option value="Other Institution in Chandigarh/Mohali">Other Institute in Tricity</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Course / Degree Program <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="course" value="{{ old('course') }}" placeholder="e.g. BA / BSc / BTech / MA" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Department</label>
                                <input type="text" class="form-control" name="department" value="{{ old('department') }}" placeholder="e.g. Political Science / CSE">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Year of Study <span class="text-danger">*</span></label>
                                <select class="form-select" name="year_of_study" required>
                                    <option value="1st Year">1st Year (Fresher)</option>
                                    <option value="2nd Year">2nd Year</option>
                                    <option value="3rd Year">3rd Year</option>
                                    <option value="4th Year">4th Year</option>
                                    <option value="Post Graduate">Post Graduate / MA / MSc</option>
                                    <option value="Research Scholar">PhD / Research Scholar</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Student Roll No.</label>
                                <input type="text" class="form-control" name="roll_no" value="{{ old('roll_no') }}" placeholder="e.g. PU2024-102">
                            </div>

                            <!-- Section 3: Addresses & Emergency Contact -->
                            <div class="col-12 mt-4"><h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-location-dot text-primary me-2"></i> 3. Address & Emergency Contact</h5></div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Permanent Address in Manipur / Home <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="permanent_address" rows="2" placeholder="Village / Ward, District, Pin Code, Manipur" required>{{ old('permanent_address') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Current Address / Hostel in Chandigarh <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="current_address" rows="2" placeholder="Hostel No. / PG House No., Sector, Chandigarh" required>{{ old('current_address') }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Emergency Contact Person & Relation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="emergency_contact" value="{{ old('emergency_contact') }}" placeholder="e.g. Paotinthang Haokip (Father)" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Emergency Contact Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="emergency_phone" value="{{ old('emergency_phone') }}" placeholder="+91 94360XXXXX" required>
                            </div>

                            <!-- Photo Upload -->
                            <div class="col-12 mt-4">
                                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-camera text-primary me-2"></i> 4. Student Photograph</h5>
                                <div class="p-3 bg-light rounded-3 border">
                                    <label class="form-label fw-bold">Upload Passport Size Profile Photo</label>
                                    <input type="file" class="form-control" name="photoFile" accept="image/*">
                                    <small class="text-muted">Passport size photo on clean background.</small>
                                </div>
                            </div>

                            <div class="col-12 mt-4 text-center">
                                <button type="submit" class="btn btn-accent btn-lg px-5 shadow fw-bold">
                                    <i class="fa-solid fa-paper-plane me-2"></i> Submit Membership Application
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
