@extends('layouts.app')

@section('title', 'Membership Registration | KSO Chandigarh')

@section('content')

<div class="membership-registration-page" x-data="{ memberType: @js(old('membership_category', 'Individual')) }">
<div class="public-page-banner bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h1 class="fw-black mb-1">KSO Chandigarh Membership Registration</h1>
        <p class="small text-light opacity-90 mb-0">Register online to get your official verified Digital Membership ID Card</p>
    </div>
</div>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h2 class="h4 fw-bold text-primary mb-0"><i class="fa-solid fa-id-card text-teal me-2" aria-hidden="true"></i> Student Membership Form (2025 - 2026)</h2>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fa-solid fa-clock me-1"></i> Quick Application</span>
                </div>
                <div class="card-body p-4 p-md-5">

                    <form action="{{ route('membership.store') }}" method="POST" enctype="multipart/form-data" id="membershipRegistrationForm">
                        @csrf
                        <div class="row g-3">

                            <!-- Section 1: Personal Information -->
                            <div class="col-12"><h3 class="h5 fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-user text-primary me-2" aria-hidden="true"></i> 1. Personal Details</h3></div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="membership-full-name">Full Name (as in College ID) <span class="text-danger">*</span></label>
                                <input type="text" id="membership-full-name" class="form-control @error('full_name') is-invalid @enderror" name="full_name" value="{{ old('full_name') }}" autocomplete="name" maxlength="255" @error('full_name') aria-invalid="true" aria-describedby="membership-full-name-error" @enderror placeholder="e.g. Seinthang Haokip" required>
                                @error('full_name')<div class="invalid-feedback" id="membership-full-name-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold" for="membership-gender">Gender <span class="text-danger">*</span></label>
                                <select id="membership-gender" class="form-select @error('gender') is-invalid @enderror" name="gender" @error('gender') aria-invalid="true" aria-describedby="membership-gender-error" @enderror required>
                                    <option value="Male" {{ old('gender', 'Male') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')<div class="invalid-feedback" id="membership-gender-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold" for="membership-dob">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" id="membership-dob" class="form-control @error('dob') is-invalid @enderror" name="dob" value="{{ old('dob') }}" autocomplete="bday" @error('dob') aria-invalid="true" aria-describedby="membership-dob-error" @enderror required>
                                @error('dob')<div class="invalid-feedback" id="membership-dob-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold" for="membership-phone">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" id="membership-phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" autocomplete="tel" maxlength="20" @error('phone') aria-invalid="true" aria-describedby="membership-phone-error" @enderror placeholder="+91 9876543210" required>
                                @error('phone')<div class="invalid-feedback" id="membership-phone-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-bold" for="membership-email">Email Address <span class="text-danger">*</span></label>
                                <input type="email" id="membership-email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" @error('email') aria-invalid="true" aria-describedby="membership-email-error" @enderror placeholder="name@gmail.com" required>
                                @error('email')<div class="invalid-feedback" id="membership-email-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold" for="membership-blood-group">Blood Group <span class="text-danger">*</span></label>
                                <select id="membership-blood-group" class="form-select @error('blood_group') is-invalid @enderror" name="blood_group" @error('blood_group') aria-invalid="true" aria-describedby="membership-blood-group-error" @enderror required>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bloodGroup)
                                        <option value="{{ $bloodGroup }}" {{ old('blood_group', 'A+') === $bloodGroup ? 'selected' : '' }}>{{ $bloodGroup }}</option>
                                    @endforeach
                                </select>
                                @error('blood_group')<div class="invalid-feedback" id="membership-blood-group-error">{{ $message }}</div>@enderror
                            </div>

                            <!-- Section 2: Academic Details -->
                            <div class="col-12 mt-4"><h3 class="h5 fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-graduation-cap text-primary me-2" aria-hidden="true"></i> 2. College / Academic Details in Chandigarh</h3></div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="membership-institution">College / Institution Name <span class="text-danger">*</span></label>
                                <select id="membership-institution" class="form-select @error('institution') is-invalid @enderror" name="institution" @error('institution') aria-invalid="true" aria-describedby="membership-institution-error" @enderror required>
                                    <option value="">-- Select Institution --</option>
                                    @foreach([
                                        'Panjab University, Sector 14' => 'Panjab University, Sector 14',
                                        'MCM DAV College for Women, Sector 36' => 'MCM DAV College for Women, Sector 36',
                                        'DAV College, Sector 10' => 'DAV College, Sector 10',
                                        'Post Graduate Govt College, Sector 11' => 'PGGC Sector 11 (Men)',
                                        'Post Graduate Govt College for Girls, Sector 11' => 'PGGCG Sector 11 (Women)',
                                        'Punjab Engineering College (PEC), Sector 12' => 'Punjab Engineering College (PEC)',
                                        'Sri Guru Gobind Singh College, Sector 26' => 'SGGS College Sector 26',
                                        'GGDSD College, Sector 32' => 'GGDSD College Sector 32',
                                        'Government Medical College & Hospital (GMCH 32)' => 'GMCH Sector 32',
                                        'PGIMER Chandigarh' => 'PGIMER Chandigarh',
                                        'Other Institution in Chandigarh/Mohali' => 'Other Institute in Tricity',
                                    ] as $institutionValue => $institutionLabel)
                                        <option value="{{ $institutionValue }}" {{ old('institution') === $institutionValue ? 'selected' : '' }}>{{ $institutionLabel }}</option>
                                    @endforeach
                                </select>
                                @error('institution')<div class="invalid-feedback" id="membership-institution-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold" for="membership-category">Membership Category <span class="text-danger">*</span></label>
                                <select id="membership-category" class="form-select @error('membership_category') is-invalid @enderror" name="membership_category" x-model="memberType" @error('membership_category') aria-invalid="true" aria-describedby="membership-category-error" @enderror required>
                                    <option value="Individual">Individual Member</option>
                                    <option value="Family">Family Membership</option>
                                </select>
                                @error('membership_category')<div class="invalid-feedback" id="membership-category-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3" x-cloak x-show="memberType === 'Family'">
                                <label class="form-label fw-bold" for="membership-family-count">Dependents Count</label>
                                <input type="number" id="membership-family-count" class="form-control @error('family_count') is-invalid @enderror" name="family_count" value="{{ old('family_count', 0) }}" min="0" @error('family_count') aria-invalid="true" aria-describedby="membership-family-count-error" @enderror>
                                @error('family_count')<div class="invalid-feedback" id="membership-family-count-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="membership-course">Course / Degree Program <span class="text-danger">*</span></label>
                                <input type="text" id="membership-course" class="form-control @error('course') is-invalid @enderror" name="course" value="{{ old('course') }}" @error('course') aria-invalid="true" aria-describedby="membership-course-error" @enderror placeholder="e.g. BA / BSc / BTech / MA" required>
                                @error('course')<div class="invalid-feedback" id="membership-course-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold" for="membership-department">Department</label>
                                <input type="text" id="membership-department" class="form-control @error('department') is-invalid @enderror" name="department" value="{{ old('department') }}" @error('department') aria-invalid="true" aria-describedby="membership-department-error" @enderror placeholder="e.g. Political Science / CSE">
                                @error('department')<div class="invalid-feedback" id="membership-department-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold" for="membership-year">Year of Study <span class="text-danger">*</span></label>
                                <select id="membership-year" class="form-select @error('year_of_study') is-invalid @enderror" name="year_of_study" @error('year_of_study') aria-invalid="true" aria-describedby="membership-year-error" @enderror required>
                                    <option value="1st Year" {{ old('year_of_study', '1st Year') === '1st Year' ? 'selected' : '' }}>1st Year (Fresher)</option>
                                    <option value="2nd Year" {{ old('year_of_study') === '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                    <option value="3rd Year" {{ old('year_of_study') === '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                    <option value="4th Year" {{ old('year_of_study') === '4th Year' ? 'selected' : '' }}>4th Year</option>
                                    <option value="Post Graduate" {{ old('year_of_study') === 'Post Graduate' ? 'selected' : '' }}>Post Graduate / MA / MSc</option>
                                    <option value="Research Scholar" {{ old('year_of_study') === 'Research Scholar' ? 'selected' : '' }}>PhD / Research Scholar</option>
                                </select>
                                @error('year_of_study')<div class="invalid-feedback" id="membership-year-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold" for="membership-roll-no">Student Roll No.</label>
                                <input type="text" id="membership-roll-no" class="form-control @error('roll_no') is-invalid @enderror" name="roll_no" value="{{ old('roll_no') }}" @error('roll_no') aria-invalid="true" aria-describedby="membership-roll-no-error" @enderror placeholder="e.g. PU2024-102">
                                @error('roll_no')<div class="invalid-feedback" id="membership-roll-no-error">{{ $message }}</div>@enderror
                            </div>

                            <!-- Section 3: Addresses & Emergency Contact -->
                            <div class="col-12 mt-4"><h3 class="h5 fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-location-dot text-primary me-2" aria-hidden="true"></i> 3. Address & Emergency Contact</h3></div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="membership-permanent-address">Permanent Address in Manipur / Home <span class="text-danger">*</span></label>
                                <textarea id="membership-permanent-address" class="form-control @error('permanent_address') is-invalid @enderror" name="permanent_address" rows="2" autocomplete="street-address" @error('permanent_address') aria-invalid="true" aria-describedby="membership-permanent-address-error" @enderror placeholder="Village / Ward, District, Pin Code, Manipur" required>{{ old('permanent_address') }}</textarea>
                                @error('permanent_address')<div class="invalid-feedback" id="membership-permanent-address-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="membership-current-address">Current Address / Hostel in Chandigarh <span class="text-danger">*</span></label>
                                <textarea id="membership-current-address" class="form-control @error('current_address') is-invalid @enderror" name="current_address" rows="2" autocomplete="street-address" @error('current_address') aria-invalid="true" aria-describedby="membership-current-address-error" @enderror placeholder="Hostel No. / PG House No., Sector, Chandigarh" required>{{ old('current_address') }}</textarea>
                                @error('current_address')<div class="invalid-feedback" id="membership-current-address-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="membership-emergency-contact">Emergency Contact Person & Relation <span class="text-danger">*</span></label>
                                <input type="text" id="membership-emergency-contact" class="form-control @error('emergency_contact') is-invalid @enderror" name="emergency_contact" value="{{ old('emergency_contact') }}" @error('emergency_contact') aria-invalid="true" aria-describedby="membership-emergency-contact-error" @enderror placeholder="e.g. Paotinthang Haokip (Father)" required>
                                @error('emergency_contact')<div class="invalid-feedback" id="membership-emergency-contact-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold" for="membership-emergency-phone">Emergency Contact Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" id="membership-emergency-phone" class="form-control @error('emergency_phone') is-invalid @enderror" name="emergency_phone" value="{{ old('emergency_phone') }}" autocomplete="tel" @error('emergency_phone') aria-invalid="true" aria-describedby="membership-emergency-phone-error" @enderror placeholder="+91 94360XXXXX" required>
                                @error('emergency_phone')<div class="invalid-feedback" id="membership-emergency-phone-error">{{ $message }}</div>@enderror
                            </div>

                            <!-- Photo Upload -->
                            <div class="col-12 mt-4">
                                <h3 class="h5 fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-camera text-primary me-2" aria-hidden="true"></i> 4. Student Photograph</h3>
                                <div class="p-3 bg-light rounded-3 border">
                                    <label class="form-label fw-bold" for="membership-photo">Upload Passport Size Profile Photo</label>
                                    <input type="file" id="membership-photo" class="form-control @error('photoFile') is-invalid @enderror" name="photoFile" accept="image/*" aria-describedby="membership-photo-help @error('photoFile')membership-photo-error @enderror membership-photo-status" @error('photoFile') aria-invalid="true" @enderror>
                                    @error('photoFile')<div class="invalid-feedback" id="membership-photo-error">{{ $message }}</div>@enderror
                                    <small class="d-block text-muted mt-2" id="membership-photo-help">Optional. Use a clear passport-size photo, up to 5 MB.</small>
                                    <span class="visually-hidden" id="membership-photo-status" role="status" aria-live="polite"></span>
                                </div>
                            </div>

                            <div class="col-12 mt-4 text-center">
                                <button type="submit" class="btn btn-accent btn-lg px-5 shadow fw-bold">
                                    <i class="fa-solid fa-paper-plane me-2" aria-hidden="true"></i> <span id="membership-submit-label">Submit Membership Application</span>
                                </button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</div>
@endsection
