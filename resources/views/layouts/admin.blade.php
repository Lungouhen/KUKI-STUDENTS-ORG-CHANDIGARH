<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KSO Admin CMS Control Panel')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">

    <!-- Admin Top Bar -->
    <div class="bg-dark text-white py-3 px-4 d-flex justify-content-between align-items-center shadow-sm">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/kso-logo.jpg') }}" class="rounded-circle border border-warning" width="36" height="36" onerror="this.src='/images/default-avatar-m.png'">
            <div>
                <span class="fw-bold fs-5 text-warning me-2"><i class="fa-solid fa-gauge me-2"></i> KSO CMS ADMIN CONTROL PANEL</span>
                <small class="text-light opacity-75">Laravel Backend Framework</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="extra-small text-light"><i class="fa-solid fa-user me-1"></i> Logged in: <strong>{{ Auth::user()->name ?? 'Admin' }}</strong></span>
            <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    <i class="fa-solid fa-power-off me-1"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Admin Navigation Bar -->
    <div class="bg-white border-bottom shadow-sm py-2 px-4 mb-4">
        <div class="container-fluid px-0">
            <ul class="nav nav-pills gap-2 extra-small">
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ request()->routeIs('admin.dashboard') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge me-1"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ request()->routeIs('admin.members*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.members.index') }}"><i class="fa-solid fa-id-card me-1"></i> Membership Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ request()->routeIs('admin.events*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.events.index') }}"><i class="fa-solid fa-calendar-plus me-1"></i> Events CMS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ request()->routeIs('admin.news*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.news.index') }}"><i class="fa-solid fa-newspaper me-1"></i> News & Notices</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ request()->routeIs('admin.committee*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.committee.index') }}"><i class="fa-solid fa-users-gear me-1"></i> Executive Body</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ request()->routeIs('admin.gallery*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.gallery.index') }}"><i class="fa-solid fa-images me-1"></i> Gallery CMS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ request()->routeIs('admin.donations*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.donations.index') }}"><i class="fa-solid fa-hand-holding-dollar me-1"></i> Donations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ request()->routeIs('admin.messages*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.messages.index') }}"><i class="fa-solid fa-envelope-open-text me-1"></i> Messages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ request()->routeIs('admin.settings*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.settings.index') }}"><i class="fa-solid fa-sliders me-1"></i> Settings</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container-fluid px-lg-5 my-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
