<footer>
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ asset('images/kso-logo.jpg') }}" alt="Logo" class="rounded-circle me-2 border border-warning" width="42" onerror="this.src='/images/default-avatar-m.png'">
                    <h5 class="fw-bold mb-0 text-white">KSO CHANDIGARH</h5>
                </div>
                <p class="small text-slate-300">
                    Apex student non-governmental organization serving and representing Kuki student scholars across educational institutions in Chandigarh UT.
                </p>
                <div class="d-flex gap-2">
                    <a href="{{ \App\Models\Setting::get('facebook', '#') }}" class="btn btn-outline-light btn-sm rounded-circle" style="width:36px;height:36px;padding:6px;"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="{{ \App\Models\Setting::get('instagram', '#') }}" class="btn btn-outline-light btn-sm rounded-circle" style="width:36px;height:36px;padding:6px;"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp', '919876543210')) }}" class="btn btn-outline-light btn-sm rounded-circle" style="width:36px;height:36px;padding:6px;"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold text-warning mb-3">Quick Links</h6>
                <ul class="list-unstyled extra-small">
                    <li class="mb-2"><a href="{{ route('about') }}" class="text-slate-300 text-decoration-none">About Us</a></li>
                    <li class="mb-2"><a href="{{ route('membership.register') }}" class="text-slate-300 text-decoration-none">Membership Form</a></li>
                    <li class="mb-2"><a href="{{ route('membership.verifyForm') }}" class="text-slate-300 text-decoration-none">Verify Student ID</a></li>
                    <li class="mb-2"><a href="{{ route('events.index') }}" class="text-slate-300 text-decoration-none">Upcoming Events</a></li>
                    <li class="mb-2"><a href="{{ route('donations.index') }}" class="text-slate-300 text-decoration-none">Support Us</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold text-warning mb-3">Emergency Support</h6>
                <ul class="list-unstyled extra-small text-slate-300">
                    <li class="mb-2"><i class="fa-solid fa-headset me-2 text-warning"></i> Helpline: {{ \App\Models\Setting::get('helpline', '+91 98765 43211') }}</li>
                    <li class="mb-2"><i class="fa-solid fa-hospital me-2 text-danger"></i> PGIMER Medical Desk</li>
                    <li class="mb-2"><i class="fa-solid fa-building-columns me-2 text-info"></i> PU Hostel Welfare Cell</li>
                    <li class="mb-2"><i class="fa-solid fa-phone me-2 text-success"></i> Chandigarh Helpline: 112</li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="fw-bold text-warning mb-3">Office Location</h6>
                <p class="extra-small text-slate-300 mb-2">
                    {{ \App\Models\Setting::get('address', 'Room 12, Student Centre, Panjab University, Sector 14, Chandigarh, 160014') }}
                </p>
                <div class="badge bg-secondary p-2 extra-small text-wrap">
                    Recognized by KSO General Headquarters
                </div>
            </div>
        </div>

        <hr class="border-secondary my-3">

        <div class="text-center extra-small text-slate-300">
            &copy; {{ date('Y') }} Kuki Students' Organisation Chandigarh. Built with Laravel 11. All Rights Reserved.
        </div>
    </div>
</footer>
