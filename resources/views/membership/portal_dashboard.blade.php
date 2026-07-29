@extends('layouts.app')

@section('title', 'Student Member Portal Dashboard | KSO Chandigarh')

@section('content')

<div class="bg-primary text-white py-4 mb-4">
    <div class="container text-center">
        <h2 class="fw-black mb-1">Welcome, {{ $member->full_name }}!</h2>
        <p class="small text-light opacity-90 mb-0">Member ID: <strong>{{ $member->id }}</strong> • Status: <span class="badge {{ $member->status === 'Approved' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $member->status }}</span></p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="text-center">
                <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-id-card me-2"></i> Your Official Digital ID Card</h5>
                
                <div class="id-card-wrapper shadow-lg text-start my-3 mx-auto" id="idCardPrintArea" style="max-width: 320px;">
                    <div class="id-card-header">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <img src="{{ asset('images/kso-logo.jpg') }}" onerror="this.src='/images/default-avatar-m.png'">
                            <div>
                                <h5 class="mb-0 text-white">KSO CHANDIGARH</h5>
                                <p class="text-warning fw-bold small">Kuki Students' Organisation</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="id-card-body">
                        <div class="id-card-photo-container">
                            <img src="{{ asset($member->photo) }}" onerror="this.src='/images/default-avatar-m.png'">
                        </div>

                        <div class="text-center">
                            <div class="id-card-name">{{ $member->full_name }}</div>
                            <div class="id-card-num">{{ $member->id }}</div>
                            <div class="badge bg-primary rounded-pill px-3 py-1 extra-small">{{ $member->designation ?? 'Student Member' }}</div>
                        </div>

                        <table class="id-card-details w-100 mt-2">
                            <tr><td class="label">College:</td><td class="fw-bold">{{ $member->institution }}</td></tr>
                            <tr><td class="label">Course:</td><td>{{ $member->course }}</td></tr>
                            <tr><td class="label">Category:</td><td class="fw-bold">{{ $member->membership_category }}</td></tr>
                            <tr><td class="label">Status:</td><td><span class="badge {{ $member->status === 'Approved' ? 'bg-success' : 'bg-warning text-dark' }} px-2 py-0 extra-small">{{ strtoupper($member->status) }}</span></td></tr>
                        </table>
                    </div>

                    <div class="id-card-footer">
                        <div>
                            <div class="fw-bold text-warning extra-small">VALID UNTIL: {{ $member->valid_until ? $member->valid_until->format('Y-m-d') : '2027-06-30' }}</div>
                            <div class="extra-small opacity-75">Recognized by KSO General HQ</div>
                        </div>
                        @php
                            // Generated server-side; the old Google Charts QR endpoint is defunct.
                            $verifyUrl = route('membership.verifyDirect', $member->id);
                        @endphp
                        <span class="rounded bg-white p-1 d-inline-flex" title="Scan to verify this membership">
                            {!! \App\Support\QrCode::svg($verifyUrl, 45) !!}
                        </span>
                    </div>
                </div>

                <div class="mt-3">
                    <button onclick="window.print()" class="btn btn-accent btn-sm rounded-pill px-4 fw-bold mb-2">
                        <i class="fa-solid fa-print me-1"></i> Print / Download
                    </button>
                    <a href="{{ route('membership.portalLogout') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 mb-2">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                    </a>
                </div>
            </div>
            
            <div class="card shadow-sm border-0 rounded-4 p-4 mt-4 bg-white text-center">
                <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-check-to-slot me-1"></i> Election Module</h6>
                <p class="extra-small text-muted">Upcoming executive body elections for the next term.</p>
                <button class="btn btn-sm btn-outline-primary rounded-pill w-100" disabled>No Active Elections</button>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-gauge text-primary me-2"></i> Dashboard Overview</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <div class="extra-small text-muted mb-1 text-uppercase fw-bold">Welcome Message</div>
                            <div class="fw-bold text-dark">Welcome, {{ $member->full_name }} ({{ $member->designation ?? 'Member' }})</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3">
                            <div class="extra-small text-muted mb-1 text-uppercase fw-bold">Member ID</div>
                            <div class="fw-bold text-primary">{{ $member->id }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 bg-light rounded-3">
                            <div class="extra-small text-muted mb-1 text-uppercase fw-bold">Status</div>
                            <span class="badge {{ $member->status === 'Approved' ? 'bg-success' : 'bg-warning' }}">{{ $member->status }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <div class="extra-small text-muted mb-1 text-uppercase fw-bold">Total Fees Paid</div>
                            <div class="fw-bold text-success">₹{{ number_format($totalFeesPaid) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <div class="extra-small text-muted mb-1 text-uppercase fw-bold">Payments Made</div>
                            <div class="fw-bold text-dark">{{ $paymentsCount }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <div class="extra-small text-muted mb-1 text-uppercase fw-bold">Type</div>
                            <div class="fw-bold text-dark">{{ $member->membership_category }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4 text-center">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="extra-small text-muted mb-1">Membership Type</div>
                        <div class="fw-bold text-primary">{{ $member->membership_category }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="extra-small text-muted mb-1">Active Term</div>
                        <div class="fw-bold text-success">{{ date('Y') }}-{{ date('Y')+1 }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <div class="extra-small text-muted mb-1">Status</div>
                        <div class="fw-bold text-{{ $member->status === 'Approved' ? 'success' : 'warning' }}">{{ $member->status }}</div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-user-gear text-primary me-2"></i> Student Account Details</h5>
                <div class="table-responsive">
                    <table class="table table-borderless table-sm extra-small">
                        <tr><th class="text-primary w-25">Membership ID:</th><td class="fw-bold">{{ $member->id }}</td></tr>
                        <tr><th class="text-primary">Designation:</th><td class="fw-bold text-warning">{{ $member->designation ?? 'Regular Student Member' }}</td></tr>
                        <tr><th class="text-primary">Join Date:</th><td>{{ $member->applied_date ? $member->applied_date->format('Y-m-d') : '2026-07-26' }}</td></tr>
                        <tr><th class="text-primary">Full Name:</th><td>{{ $member->full_name }}</td></tr>
                        <tr><th class="text-primary">Gender / DOB:</th><td>{{ $member->gender }} • {{ $member->dob ? $member->dob->format('Y-m-d') : '' }}</td></tr>
                        <tr><th class="text-primary">Phone / Email:</th><td>{{ $member->phone }} / {{ $member->email }}</td></tr>
                        <tr><th class="text-primary">Institution:</th><td>{{ $member->institution }}</td></tr>
                        <tr><th class="text-primary">Course & Dept:</th><td>{{ $member->course }} ({{ $member->department ?? 'N/A' }}) - {{ $member->year_of_study }}</td></tr>
                        <tr><th class="text-primary">Address:</th><td>{{ $member->current_address }}</td></tr>
                        <tr><th class="text-primary">Emergency:</th><td>{{ $member->emergency_contact }} ({{ $member->emergency_phone }})</td></tr>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-credit-card text-success me-2"></i> Fees & Payment History</h5>
                <div class="table-responsive">
                    <table class="table table-sm extra-small align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>Term</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2025-2026</td>
                                <td>Registration</td>
                                <td>₹250</td>
                                <td><span class="badge bg-success">Success</span></td>
                                <td>{{ $member->applied_date ? $member->applied_date->format('Y-m-d') : '2026-07-26' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 text-end">
                    <button class="btn btn-sm btn-success rounded-pill px-4 fw-bold shadow-sm">Pay Membership Fee</button>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 p-4 bg-white">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-bullhorn text-warning me-2"></i> KSO Student Notices & Resources</h5>
                @foreach(\App\Models\News::take(3)->get() as $n)
                    <div class="p-2 border-bottom extra-small">
                        <div class="fw-bold text-dark">{{ $n->title }}</div>
                        <div class="text-muted">{{ $n->content }}</div>
                        <small class="text-primary fw-semibold">{{ $n->date ? $n->date->format('Y-m-d') : '' }}</small>
                    </div>
                @endforeach
            </div>

            <div class="card shadow-sm border-0 rounded-4 p-4 mt-4 bg-white">
                <h5 class="fw-bold text-danger border-bottom pb-2 mb-3"><i class="fa-solid fa-notes-medical me-2"></i> Medical Relief Claim Desk</h5>
                
                @if($medicalClaims->count() > 0)
                    <div class="mb-4">
                        <h6 class="fw-bold extra-small text-muted text-uppercase mb-2">My Recent Claims</h6>
                        @foreach($medicalClaims as $claim)
                            <div class="d-flex justify-content-between align-items-center p-2 border-bottom extra-small">
                                <div>
                                    <div class="fw-bold">{{ $claim->hospital_name }}</div>
                                    <div class="text-muted">Requested: ₹{{ number_format($claim->amount_requested) }}</div>
                                </div>
                                <span class="badge {{ $claim->status === 'Approved' ? 'bg-success' : ($claim->status === 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                    {{ strtoupper($claim->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <p class="extra-small text-muted mb-3">If you are facing a medical emergency at PGIMER, GMCH-32, or any other hospital, you can submit a relief request here. KSO Chandigarh may provide partial financial assistance based on fund availability.</p>
                
                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#medicalClaimModal">
                    <i class="fa-solid fa-plus-circle me-1"></i> New Relief Request
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Medical Relief Claim Modal -->
<div class="modal fade" id="medicalClaimModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-danger">New Medical Relief Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('membership.submitMedicalClaim') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Patient Name</label>
                        <input type="text" name="patient_name" class="form-control" required placeholder="Full Name of Patient">
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Hospital Name</label>
                        <input type="text" name="hospital_name" class="form-control" required placeholder="e.g. PGIMER Sector 12">
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Nature of Illness / Emergency</label>
                        <textarea name="nature_of_illness" class="form-control" rows="2" required placeholder="Describe the medical situation..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label extra-small fw-bold">Amount Requested (₹)</label>
                        <input type="number" name="amount_requested" class="form-control" required min="1" placeholder="Estimated assistance needed">
                    </div>
                    <div class="mb-0">
                        <label class="form-label extra-small fw-bold">Support Document (Prescription/Bill)</label>
                        <input type="file" name="medical_document" class="form-control">
                        <div class="form-text extra-small">Max size 5MB (JPG, PNG, PDF)</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
