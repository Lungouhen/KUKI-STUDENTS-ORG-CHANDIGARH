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
    <!-- Choices.js CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <!-- Custom Styles & Vite Bundle -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- CKEditor 5 CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    @stack('styles')
</head>
<body class="bg-light" x-data="{ sidebarOpen: true }">

    <!-- Admin Top Bar -->
    <div class="bg-dark text-white py-3 px-4 d-flex justify-content-between align-items-center shadow-sm">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/kso-logo.jpg') }}" class="rounded-circle border border-warning" width="36" height="36" onerror="this.src='/images/default-avatar-m.png'">
            <div>
                <span class="fw-bold fs-5 text-warning me-2"><i class="fa-solid fa-gauge me-2"></i> KSO CMS ADMIN CONTROL PANEL</span>
                <small class="text-light opacity-75">Laravel Framework</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-warning btn-sm rounded-pill"><i class="fa-solid fa-globe me-1"></i> Public Website</a>
            <span class="extra-small text-light d-none d-md-inline"><i class="fa-solid fa-user me-1"></i> Logged in: <strong>{{ Auth::user()->name ?? 'Admin' }}</strong></span>
            <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    <i class="fa-solid fa-power-off me-1"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Admin Navigation Bar -->
    <div class="bg-white border-bottom shadow-sm py-2 px-4 mb-4 overflow-auto">
        <div class="container-fluid px-0">
            <ul class="nav nav-pills gap-1 flex-nowrap extra-small">
                <li class="nav-item">
                    <a class="nav-link fw-bold text-nowrap {{ request()->routeIs('admin.dashboard') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge me-1"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-nowrap {{ request()->routeIs('admin.members*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.members.index') }}"><i class="fa-solid fa-id-card me-1"></i> Members</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-nowrap {{ request()->routeIs('admin.financial*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.financial.index') }}"><i class="fa-solid fa-file-invoice-dollar me-1"></i> Financial Ledger</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-nowrap {{ request()->routeIs('admin.pages*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.pages.index') }}"><i class="fa-solid fa-file-lines me-1"></i> Page Builder</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-nowrap {{ request()->routeIs('admin.events*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.events.index') }}"><i class="fa-solid fa-calendar-plus me-1"></i> Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-nowrap {{ request()->routeIs('admin.news*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.news.index') }}"><i class="fa-solid fa-newspaper me-1"></i> News</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-nowrap {{ request()->routeIs('admin.committee*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.committee.index') }}"><i class="fa-solid fa-users-gear me-1"></i> Executive Council</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-nowrap {{ request()->routeIs('admin.gallery*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.gallery.index') }}"><i class="fa-solid fa-images me-1"></i> Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-nowrap {{ request()->routeIs('admin.donations*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.donations.index') }}"><i class="fa-solid fa-hand-holding-dollar me-1"></i> Donations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-nowrap {{ request()->routeIs('admin.medical*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.medical.index') }}"><i class="fa-solid fa-notes-medical me-1"></i> Medical Desk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-nowrap {{ request()->routeIs('admin.faqs*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.faqs.index') }}"><i class="fa-solid fa-circle-question me-1"></i> FAQs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-nowrap {{ request()->routeIs('admin.messages*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.messages.index') }}"><i class="fa-solid fa-envelope-open-text me-1"></i> Messages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-nowrap {{ request()->routeIs('admin.audit*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.audit.index') }}"><i class="fa-solid fa-list-check me-1"></i> Audit Trail</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold text-nowrap {{ request()->routeIs('admin.settings*') ? 'active bg-primary' : 'text-dark' }}" href="{{ route('admin.settings.index') }}"><i class="fa-solid fa-sliders me-1"></i> Settings</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container-fluid px-lg-5 my-4">
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Admin Action Saved',
                        text: "{{ session('success') }}",
                        confirmButtonColor: '#003566'
                    });
                });
            </script>
        @endif

        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: "{{ session('error') }}",
                        confirmButtonColor: '#003566'
                    });
                });
            </script>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Choices.js JS -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script>
        function confirmDelete(formId, message = 'Are you sure you want to delete this item?') {
            Swal.fire({
                title: 'Confirm Delete',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
