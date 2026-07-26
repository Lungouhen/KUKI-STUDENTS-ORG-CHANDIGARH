@extends('layouts.app')

@section('title', 'About Us | KSO Chandigarh')

@section('content')

<div class="bg-primary text-white py-5 mb-5">
    <div class="container text-center">
        <h1 class="fw-black display-5 mb-2">About KSO Chandigarh</h1>
        <p class="lead opacity-90 mx-auto" style="max-width: 700px;">
            Learn about our history, constitutional principles, student welfare services, and executive council.
        </p>
    </div>
</div>

<div class="container my-5">
    <div class="row g-5">
        <div class="col-lg-8">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border mb-4">
                <h3 class="fw-bold text-primary mb-3"><i class="fa-solid fa-landmark me-2"></i> Our History & Founding</h3>
                <p class="text-secondary leading-relaxed">
                    The Kuki Students' Organisation (KSO) Chandigarh branch was established to create a supportive community for youth leaving their home state of Manipur to pursue higher education in Chandigarh, known as "The City Beautiful".
                </p>
                <p class="text-secondary leading-relaxed">
                    Over the years, KSO Chandigarh has evolved into a premier student organization that bridges cultural gaps, safeguards student rights, assists in academic admissions, and provides round-the-clock emergency support.
                </p>

                <hr class="my-4">

                <h3 class="fw-bold text-teal mb-3" style="color:#0d9488;"><i class="fa-solid fa-bullseye me-2"></i> Mission & Vision</h3>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border-start border-4 border-primary">
                            <h5 class="fw-bold text-primary">Academic Excellence</h5>
                            <p class="small text-muted mb-0">Empowering every member to achieve academic distinction through mentorship, seminars, and book bank facilities.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3 border-start border-4 border-teal" style="border-color:#0d9488 !important;">
                            <h5 class="fw-bold text-teal" style="color:#0d9488;">Cultural Preservation</h5>
                            <p class="small text-muted mb-0">Promoting Kuki heritage, folk music, attire, and celebrating annual cultural festivals with unity and pride.</p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h3 class="fw-bold text-dark mb-3"><i class="fa-solid fa-shield-halved me-2"></i> Constitutional Aims</h3>
                <ul class="list-group list-group-flush text-secondary">
                    <li class="list-group-item bg-transparent"><i class="fa-solid fa-check-circle text-success me-2"></i> To foster unity, brotherhood, and discipline among all Kuki students in Chandigarh, Mohali, and Panchkula.</li>
                    <li class="list-group-item bg-transparent"><i class="fa-solid fa-check-circle text-success me-2"></i> To assist freshers during college admissions, hostel allocations, PG accommodations, and city orientation.</li>
                    <li class="list-group-item bg-transparent"><i class="fa-solid fa-check-circle text-success me-2"></i> To operate a 24/7 Emergency Cell for medical crises at PGIMER or GMCH Sector 32.</li>
                    <li class="list-group-item bg-transparent"><i class="fa-solid fa-check-circle text-success me-2"></i> To issue official verified Student Membership Digital ID Cards recognized by KSO General Headquarters.</li>
                </ul>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bg-primary text-white p-4 rounded-4 shadow-sm mb-4">
                <h4 class="fw-bold mb-3"><i class="fa-solid fa-phone-volume me-2"></i> Student Support Cell</h4>
                <p class="small opacity-90">Need urgent assistance with admissions, PG accommodation, or medical support?</p>
                <div class="d-grid gap-2">
                    <a href="tel:{{ \App\Models\Setting::get('helpline', '+91 98765 43211') }}" class="btn btn-warning fw-bold text-dark"><i class="fa-solid fa-headset me-2"></i> Call Helpline</a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp', '919876543210')) }}" target="_blank" class="btn btn-success fw-bold"><i class="fa-brands fa-whatsapp me-2"></i> WhatsApp Support</a>
                </div>
            </div>

            <div class="bg-white p-4 rounded-4 shadow-sm border">
                <h5 class="fw-bold text-dark mb-3">Quick Navigation</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('membership.register') }}" class="btn btn-outline-primary text-start"><i class="fa-solid fa-user-plus me-2"></i> Apply for Membership</a>
                    <a href="{{ route('membership.verifyForm') }}" class="btn btn-outline-secondary text-start"><i class="fa-solid fa-qrcode me-2"></i> Verify Student ID</a>
                    <a href="{{ route('donations.index') }}" class="btn btn-outline-warning text-dark text-start"><i class="fa-solid fa-heart me-2"></i> Support Student Welfare Fund</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Executive Committee Directory -->
    <div class="mt-5">
        <div class="text-center mb-4">
            <span class="badge bg-teal text-white px-3 py-1 rounded-pill fw-bold" style="background:#0d9488;">DIRECTORY</span>
            <h2 class="fw-bold text-primary mt-2">Executive Body Committee Members</h2>
        </div>
        <div class="row g-4">
            @foreach($committee as $c)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center bg-white hover-lift">
                        <img src="{{ asset($c->photo ?? '/images/default-avatar-m.png') }}" class="rounded-circle me-3 border border-2 border-primary" style="width: 85px; height: 80px; object-fit: cover;" onerror="this.src='/images/default-avatar-m.png'">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ $c->name }}</h6>
                            <span class="badge bg-teal text-white rounded-pill px-2 py-1 extra-small mb-1 d-inline-block" style="background:#0d9488;">{{ $c->designation }}</span>
                            <div class="text-muted extra-small mb-1"><i class="fa-solid fa-graduation-cap me-1"></i> {{ $c->institution }}</div>
                            <div class="extra-small text-primary fw-semibold"><i class="fa-solid fa-phone me-1"></i> {{ $c->phone }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
