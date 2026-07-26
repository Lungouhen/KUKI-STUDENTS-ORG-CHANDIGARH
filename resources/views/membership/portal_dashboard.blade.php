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
        <div class="col-lg-5">
            <div class="text-center">
                <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-id-card me-2"></i> Your Official Digital ID Card</h5>
                
                <div class="id-card-wrapper shadow-lg text-start my-3" id="idCardPrintArea">
                    <div class="id-card-header">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <img src="{{ asset('images/kso-logo.jpg') }}" onerror="this.src='/images/default-avatar-m.png'">
                            <div>
                                <h5 class="mb-0 text-white">KSO CHANDIGARH</h5>
                                <p class="text-warning fw-bold">Kuki Students' Organisation</p>
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
                        </div>

                        <table class="id-card-details w-100">
                            <tr><td class="label">College:</td><td class="fw-bold">{{ $member->institution }}</td></tr>
                            <tr><td class="label">Course:</td><td>{{ $member->course }} ({{ $member->year_of_study }})</td></tr>
                            <tr><td class="label">Blood Grp:</td><td class="fw-bold text-danger">{{ $member->blood_group }}</td></tr>
                            <tr><td class="label">Emergency:</td><td>{{ $member->emergency_phone }}</td></tr>
                            <tr><td class="label">Status:</td><td><span class="badge {{ $member->status === 'Approved' ? 'bg-success' : 'bg-warning text-dark' }} px-2 py-0 extra-small">{{ strtoupper($member->status) }}</span></td></tr>
                        </table>
                    </div>

                    <div class="id-card-footer">
                        <div>
                            <div class="fw-bold text-warning">VALID UNTIL: {{ $member->valid_until ? $member->valid_until->format('Y-m-d') : '2027-06-30' }}</div>
                            <div class="extra-small opacity-75">Recognized by KSO General HQ</div>
                        </div>
                        <i class="fa-solid fa-qrcode fs-2 text-white"></i>
                    </div>
                </div>

                <div class="mt-3">
                    <button onclick="window.print()" class="btn btn-accent rounded-pill px-4 fw-bold me-2">
                        <i class="fa-solid fa-print me-1"></i> Print / Download ID
                    </button>
                    <a href="{{ route('membership.portalLogout') }}" class="btn btn-outline-danger rounded-pill px-3">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 bg-white">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-user-gear text-primary me-2"></i> Student Account Details</h5>
                <div class="table-responsive">
                    <table class="table table-borderless table-sm extra-small">
                        <tr><th class="text-primary">Membership ID:</th><td class="fw-bold">{{ $member->id }}</td></tr>
                        <tr><th class="text-primary">Full Name:</th><td>{{ $member->full_name }}</td></tr>
                        <tr><th class="text-primary">Gender / DOB:</th><td>{{ $member->gender }} • {{ $member->dob ? $member->dob->format('Y-m-d') : '' }}</td></tr>
                        <tr><th class="text-primary">Phone / Email:</th><td>{{ $member->phone }} • {{ $member->email }}</td></tr>
                        <tr><th class="text-primary">College:</th><td>{{ $member->institution }}</td></tr>
                        <tr><th class="text-primary">Course & Dept:</th><td>{{ $member->course }} ({{ $member->department ?? 'N/A' }}) - {{ $member->year_of_study }}</td></tr>
                        <tr><th class="text-primary">Permanent Address:</th><td>{{ $member->permanent_address }}</td></tr>
                        <tr><th class="text-primary">Current PG Address:</th><td>{{ $member->current_address }}</td></tr>
                        <tr><th class="text-primary">Emergency Contact:</th><td>{{ $member->emergency_contact }} ({{ $member->emergency_phone }})</td></tr>
                        <tr><th class="text-primary">Application Status:</th><td><span class="badge {{ $member->status === 'Approved' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $member->status }}</span></td></tr>
                    </table>
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
        </div>
    </div>
</div>

@endsection
