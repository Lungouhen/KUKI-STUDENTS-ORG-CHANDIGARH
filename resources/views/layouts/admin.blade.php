<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KSO Admin CMS Control Panel')</title>
    <!-- Local Bootstrap 5 CSS -->
    <link href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Local FontAwesome 6 CSS -->
    <link href="{{ asset('vendor/fontawesome/all.min.css') }}" rel="stylesheet">
    <!-- Local Choices.js CSS -->
    <link href="{{ asset('vendor/choices/choices.min.css') }}" rel="stylesheet">
    <!-- Local SweetAlert2 CSS -->
    <link href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- Custom Styles & Vite Bundle -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- Local Alpine.js -->
    <script defer src="{{ asset('vendor/alpine/alpine.min.js') }}"></script>
    <!-- Local SweetAlert2 JS -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Local ApexCharts JS -->
    <script src="{{ asset('vendor/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Local CKEditor 5 JS -->
    <script src="{{ asset('vendor/ckeditor/ckeditor.js') }}"></script>

    @stack('styles')
</head>
<body class="bg-light" x-data="{ 
    sidebarOpen: true,
    darkMode: localStorage.getItem('theme') === 'dark',
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
    }
}" :data-bs-theme="darkMode ? 'dark' : 'light'">

    <div class="d-flex">
        <!-- Sidebar -->
        <div class="admin-sidebar" :class="sidebarOpen ? 'w-sidebar' : 'w-icon-sidebar'" style="transition: width 0.3s;">
            <div class="admin-sidebar-header">
                <div x-show="sidebarOpen" class="brand-title"><i class="fa-solid fa-user-shield me-2"></i> KSO ADMIN CMS</div>
                <button @click="sidebarOpen = !sidebarOpen" class="btn btn-sm btn-outline-warning">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
            
            <div class="sidebar-nav py-3 overflow-auto" style="max-height: 90vh;">
                <ul class="nav flex-column gap-1 p-0">
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-house"></i> <span x-show="sidebarOpen">Home Dashboard</span>
                        </a>
                    </li>
                    
                    <div class="admin-section-divider" x-show="sidebarOpen">Content</div>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.content.index', ['type' => 'slider']) }}" class="admin-nav-link {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'slider'])) ? 'active' : '' }}">
                            <i class="fa-solid fa-images"></i> <span x-show="sidebarOpen">Slider Banners</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.pages.index') }}" class="admin-nav-link {{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
                            <i class="fa-solid fa-file-lines"></i> <span x-show="sidebarOpen">About & Pages</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.gallery.index') }}" class="admin-nav-link {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">
                            <i class="fa-solid fa-image"></i> <span x-show="sidebarOpen">Gallery</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.content.index', ['type' => 'certificate']) }}" class="admin-nav-link {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'certificate'])) ? 'active' : '' }}">
                            <i class="fa-solid fa-certificate"></i> <span x-show="sidebarOpen">Certificates</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.content.index', ['type' => 'achievement']) }}" class="admin-nav-link {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'achievement'])) ? 'active' : '' }}">
                            <i class="fa-solid fa-trophy"></i> <span x-show="sidebarOpen">Achievements</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.content.index', ['type' => 'policy']) }}" class="admin-nav-link {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'policy'])) ? 'active' : '' }}">
                            <i class="fa-solid fa-shield-halved"></i> <span x-show="sidebarOpen">Policies</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.news.index') }}" class="admin-nav-link {{ request()->routeIs('admin.news.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-newspaper"></i> <span x-show="sidebarOpen">News & Feed</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.content.index', ['type' => 'notice']) }}" class="admin-nav-link {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'notice'])) ? 'active' : '' }}">
                            <i class="fa-solid fa-bullhorn"></i> <span x-show="sidebarOpen">Notices</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.projects.index') }}" class="admin-nav-link {{ request()->routeIs('admin.projects*') ? 'active' : '' }}">
                            <i class="fa-solid fa-diagram-project"></i> <span x-show="sidebarOpen">Projects</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.content.index', ['type' => 'campaign']) }}" class="admin-nav-link {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'campaign'])) ? 'active' : '' }}">
                            <i class="fa-solid fa-bullseye"></i> <span x-show="sidebarOpen">Campaigns</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.content.index', ['type' => 'career']) }}" class="admin-nav-link {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'career'])) ? 'active' : '' }}">
                            <i class="fa-solid fa-briefcase"></i> <span x-show="sidebarOpen">Careers</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.messages.index') }}" class="admin-nav-link {{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
                            <i class="fa-solid fa-envelope"></i> <span x-show="sidebarOpen">Messages</span>
                        </a>
                    </li>
                    
                    <div class="admin-section-divider" x-show="sidebarOpen">Members</div>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.members.index') }}" class="admin-nav-link {{ request()->routeIs('admin.members*') ? 'active' : '' }}">
                            <i class="fa-solid fa-users"></i> <span x-show="sidebarOpen">All Members</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.members.index', ['status' => 'Pending']) }}" class="admin-nav-link">
                            <i class="fa-solid fa-user-clock"></i> <span x-show="sidebarOpen">Member Requests</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.members.fees') }}" class="admin-nav-link {{ request()->routeIs('admin.members.fees') ? 'active' : '' }}">
                            <i class="fa-solid fa-money-bill"></i> <span x-show="sidebarOpen">Membership Fees</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.committee.index') }}" class="admin-nav-link {{ request()->routeIs('admin.committee*') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tag"></i> <span x-show="sidebarOpen">Designations</span>
                        </a>
                    </li>
                    
                    <div class="admin-section-divider" x-show="sidebarOpen">People</div>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.donations.index') }}" class="admin-nav-link {{ request()->routeIs('admin.donations*') ? 'active' : '' }}">
                            <i class="fa-solid fa-hand-holding-heart"></i> <span x-show="sidebarOpen">Donors</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.beneficiaries.index') }}" class="admin-nav-link {{ request()->routeIs('admin.beneficiaries*') ? 'active' : '' }}">
                            <i class="fa-solid fa-users-viewfinder"></i> <span x-show="sidebarOpen">Beneficiaries</span>
                        </a>
                    </li>
                    
                    <div class="admin-section-divider" x-show="sidebarOpen">Finance</div>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.donations.index') }}" class="admin-nav-link {{ request()->routeIs('admin.donations*') ? 'active' : '' }}">
                            <i class="fa-solid fa-money-check-dollar"></i> <span x-show="sidebarOpen">Donations</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.financial.index', ['type' => 'Expense']) }}" class="admin-nav-link {{ request()->fullUrlIs(route('admin.financial.index', ['type' => 'Expense'])) ? 'active' : '' }}">
                            <i class="fa-solid fa-file-invoice-dollar"></i> <span x-show="sidebarOpen">Expenses</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.financial.index') }}" class="admin-nav-link {{ request()->routeIs('admin.financial.index') && !request()->has('type') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-pie"></i> <span x-show="sidebarOpen">Reports</span>
                        </a>
                    </li>

                    <li class="admin-nav-item">
                        <a href="{{ route('admin.partners.index') }}" class="admin-nav-link {{ request()->routeIs('admin.partners*') ? 'active' : '' }}">
                            <i class="fa-solid fa-handshake"></i> <span x-show="sidebarOpen">Partners</span>
                        </a>
                    </li>

                    <li class="admin-nav-item">
                        <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-shield"></i> <span x-show="sidebarOpen">Users</span>
                        </a>
                    </li>

                    <div class="admin-section-divider" x-show="sidebarOpen">Settings</div>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.settings.index') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-building-ngo"></i> <span x-show="sidebarOpen">Organization</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.settings.smtp') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.smtp') ? 'active' : '' }}">
                            <i class="fa-solid fa-envelope-circle-check"></i> <span x-show="sidebarOpen">SMTP Settings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.gateways') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.gateways') ? 'active' : '' }}">
                            <i class="fa-solid fa-credit-card"></i> <span x-show="sidebarOpen">Payment Gateways</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="#" class="admin-nav-link">
                            <i class="fa-solid fa-file-code"></i> <span x-show="sidebarOpen">Templates</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.settings.integrations') }}" class="admin-nav-link {{ request()->routeIs('admin.settings.integrations') ? 'active' : '' }}">
                            <i class="fa-solid fa-plug"></i> <span x-show="sidebarOpen">Integrations</span>
                        </a>
                    </li>

                    <div class="admin-section-divider" x-show="sidebarOpen">Election & Logs</div>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.elections.index') }}" class="admin-nav-link {{ request()->routeIs('admin.elections*') ? 'active' : '' }}">
                            <i class="fa-solid fa-check-to-slot"></i> <span x-show="sidebarOpen">Election Module</span>
                        </a>
                    </li>
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.audit.index') }}" class="admin-nav-link {{ request()->routeIs('admin.audit*') ? 'active' : '' }}">
                            <i class="fa-solid fa-clipboard-check"></i> <span x-show="sidebarOpen">Audit Trail</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 overflow-hidden">
            <!-- Top Bar -->
            <div class="admin-topbar p-3 d-flex justify-content-between align-items-center">
                <div class="fw-bold">
                    @yield('title', 'Admin Panel')
                </div>
                <div class="d-flex align-items-center gap-3">
                    <button @click="toggleTheme()" class="btn btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center" :class="darkMode ? 'btn-warning' : 'btn-outline-dark'" style="width: 32px; height: 32px;">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button>
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">View Site</a>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-danger rounded-pill">Logout</button>
                    </form>
                </div>
            </div>

            <div class="admin-main-container p-4">
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
    </div>
    </div>

    <!-- Local Bootstrap 5 JS -->
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- Local Choices.js JS -->
    <script src="{{ asset('vendor/choices/choices.min.js') }}"></script>

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
