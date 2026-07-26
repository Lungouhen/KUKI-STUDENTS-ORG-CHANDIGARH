<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kuki Students\' Organisation Chandigarh')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Custom Laravel Asset Styles -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>

    <!-- ─── TOPBAR ─── -->
    <div class="topbar d-none d-md-block">
        <div class="container-fluid px-lg-5">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="me-3"><i class="fa-solid fa-phone topbar-icon"></i> <a href="tel:{{ \App\Models\Setting::get('phone', '+91 98765 43210') }}">{{ \App\Models\Setting::get('phone', '+91 98765 43210') }}</a></span>
                    <span class="topbar-sep">|</span>
                    <span class="me-3"><i class="fa-solid fa-headset topbar-icon"></i> Helpline: <a href="tel:{{ \App\Models\Setting::get('helpline', '+91 98765 43211') }}" class="fw-bold text-warning">{{ \App\Models\Setting::get('helpline', '+91 98765 43211') }}</a></span>
                    <span class="topbar-sep">|</span>
                    <span><i class="fa-solid fa-envelope topbar-icon"></i> <a href="mailto:{{ \App\Models\Setting::get('email', 'ksochandigarh@gmail.com') }}">{{ \App\Models\Setting::get('email', 'ksochandigarh@gmail.com') }}</a></span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-2 extra-small">Follow Us:</span>
                    <div class="topbar-social">
                        <a href="{{ \App\Models\Setting::get('facebook', 'https://facebook.com') }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="{{ \App\Models\Setting::get('instagram', 'https://instagram.com') }}" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp', '919876543210')) }}" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ─── ANNOUNCEMENT TICKER ─── -->
    <div class="announcement-ticker">
        <div class="container-fluid px-lg-5 d-flex align-items-center">
            <span class="badge bg-dark me-3 px-2 py-1"><i class="fa-solid fa-bullhorn me-1"></i> NOTICE</span>
            <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
                {{ \App\Models\Setting::get('announcement', '📢 Welcome to KSO Chandigarh! Annual Membership Registration 2025-2026 is now OPEN. Get your official digital student ID card online!') }}
            </marquee>
        </div>
    </div>

    <!-- ─── MODERN GLASS NAVBAR ─── -->
    <nav class="navbar navbar-expand-lg navbar-modern sticky-top">
        <div class="container-fluid px-lg-5">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/kso-logo.jpg') }}" alt="KSO Logo" class="me-2 rounded-circle border border-warning" onerror="this.src='/images/default-avatar-m.png'">
                <div>
                    <span class="fw-extrabold fs-5 d-block text-dark lh-1">KSO CHANDIGARH</span>
                    <small class="text-muted extra-small fw-semibold">Kuki Students' Organisation</small>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"><i class="fa-solid fa-house me-1"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}"><i class="fa-solid fa-circle-info me-1"></i> About Us</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('membership*') ? 'active' : '' }}" href="#" data-bs-toggle="dropdown">
                            <i class="fa-solid fa-id-card me-1"></i> Membership <i class="fa-solid fa-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('membership.register') }}"><i class="fa-solid fa-user-plus text-teal me-2"></i> Register Member</a></li>
                            <li><a class="dropdown-item" href="{{ route('membership.verifyForm') }}"><i class="fa-solid fa-circle-check text-success me-2"></i> Verify Student ID</a></li>
                            <li><a class="dropdown-item" href="{{ route('membership.portal') }}"><i class="fa-solid fa-right-to-bracket text-primary me-2"></i> Member Portal Login</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('events.index') ? 'active' : '' }}" href="{{ route('events.index') }}"><i class="fa-solid fa-calendar-days me-1"></i> Events & News</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('gallery.index') ? 'active' : '' }}" href="{{ route('gallery.index') }}"><i class="fa-solid fa-images me-1"></i> Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('donations.index') ? 'active' : '' }}" href="{{ route('donations.index') }}"><i class="fa-solid fa-hand-holding-heart me-1"></i> Support Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact.index') ? 'active' : '' }}" href="{{ route('contact.index') }}"><i class="fa-solid fa-envelope me-1"></i> Contact</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0 navbar-nav.ms-auto">
                    <a href="{{ route('donations.index') }}" class="btn btn-accent btn-sm rounded-pill px-3 shadow-sm"><i class="fa-solid fa-heart me-1"></i> Donate</a>
                    <a href="{{ route('admin.login') }}" class="nav-link-admin"><i class="fa-solid fa-user-shield me-1"></i> CMS Admin</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ─── MAIN CONTENT ─── -->
    <main>
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- ─── FLOATING WHATSAPP BUTTON ─── -->
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp', '919876543210')) }}" target="_blank" class="whatsapp-float" title="Chat with KSO Support">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <!-- ─── FOOTER ─── -->
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
