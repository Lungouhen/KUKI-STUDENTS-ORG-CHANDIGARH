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
        <div class="bg-dark text-white min-vh-100 shadow" :class="sidebarOpen ? 'w-sidebar' : 'w-icon-sidebar'" style="transition: width 0.3s; width: 260px;">
            <div class="p-3 border-bottom border-secondary d-flex justify-content-between align-items-center">
                <div x-show="sidebarOpen" class="fw-bold text-warning">KSO ADMIN PANEL</div>
                <button @click="sidebarOpen = !sidebarOpen" class="btn btn-sm btn-outline-warning">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
            
            <div class="sidebar-nav py-3 overflow-auto" style="max-height: 90vh;">
                <ul class="nav flex-column gap-1">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-house me-2"></i> <span x-show="sidebarOpen">Home</span>
                        </a>
                    </li>
                    
                    <div class="px-3 small text-uppercase opacity-50 mt-3 mb-1" x-show="sidebarOpen">Content</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.content.index', ['type' => 'slider']) }}" class="nav-link text-white {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'slider'])) ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-images me-2"></i> <span x-show="sidebarOpen">Slider</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.pages.index') }}" class="nav-link text-white {{ request()->routeIs('admin.pages*') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-file-lines me-2"></i> <span x-show="sidebarOpen">About & Pages</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.gallery.index') }}" class="nav-link text-white {{ request()->routeIs('admin.gallery*') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-image me-2"></i> <span x-show="sidebarOpen">Gallery</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.content.index', ['type' => 'certificate']) }}" class="nav-link text-white {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'certificate'])) ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-certificate me-2"></i> <span x-show="sidebarOpen">Certificates</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.content.index', ['type' => 'achievement']) }}" class="nav-link text-white {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'achievement'])) ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-trophy me-2"></i> <span x-show="sidebarOpen">Achievements</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.content.index', ['type' => 'policy']) }}" class="nav-link text-white {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'policy'])) ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-shield-halved me-2"></i> <span x-show="sidebarOpen">Policies</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.news.index') }}" class="nav-link text-white {{ request()->routeIs('admin.news.index') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-newspaper me-2"></i> <span x-show="sidebarOpen">News</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.content.index', ['type' => 'notice']) }}" class="nav-link text-white {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'notice'])) ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-bullhorn me-2"></i> <span x-show="sidebarOpen">Notices</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.projects.index') }}" class="nav-link text-white {{ request()->routeIs('admin.projects*') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-diagram-project me-2"></i> <span x-show="sidebarOpen">Projects</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.content.index', ['type' => 'campaign']) }}" class="nav-link text-white {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'campaign'])) ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-bullseye me-2"></i> <span x-show="sidebarOpen">Campaigns</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.content.index', ['type' => 'career']) }}" class="nav-link text-white {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'career'])) ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-briefcase me-2"></i> <span x-show="sidebarOpen">Careers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.messages.index') }}" class="nav-link text-white {{ request()->routeIs('admin.messages*') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-envelope me-2"></i> <span x-show="sidebarOpen">Messages</span>
                        </a>
                    </li>
                    
                    <div class="px-3 small text-uppercase opacity-50 mt-3 mb-1" x-show="sidebarOpen">Members</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.members.index') }}" class="nav-link text-white {{ request()->routeIs('admin.members*') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-users me-2"></i> <span x-show="sidebarOpen">All Members</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.members.index', ['status' => 'Pending']) }}" class="nav-link text-white">
                            <i class="fa-solid fa-user-clock me-2"></i> <span x-show="sidebarOpen">Member Requests</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.members.fees') }}" class="nav-link text-white {{ request()->routeIs('admin.members.fees') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-money-bill me-2"></i> <span x-show="sidebarOpen">Membership Fees</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.committee.index') }}" class="nav-link text-white {{ request()->routeIs('admin.committee*') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-user-tag me-2"></i> <span x-show="sidebarOpen">Designations</span>
                        </a>
                    </li>
                    
                    <div class="px-3 small text-uppercase opacity-50 mt-3 mb-1" x-show="sidebarOpen">People</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.donations.index') }}" class="nav-link text-white {{ request()->routeIs('admin.donations*') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-hand-holding-heart me-2"></i> <span x-show="sidebarOpen">Donors</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.beneficiaries.index') }}" class="nav-link text-white {{ request()->routeIs('admin.beneficiaries*') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-users-viewfinder me-2"></i> <span x-show="sidebarOpen">Beneficiaries</span>
                        </a>
                    </li>
                    
                    <div class="px-3 small text-uppercase opacity-50 mt-3 mb-1" x-show="sidebarOpen">Finance</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.donations.index') }}" class="nav-link text-white {{ request()->routeIs('admin.donations*') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-money-check-dollar me-2"></i> <span x-show="sidebarOpen">Donations</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.financial.index', ['type' => 'Expense']) }}" class="nav-link text-white {{ request()->fullUrlIs(route('admin.financial.index', ['type' => 'Expense'])) ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-file-invoice-dollar me-2"></i> <span x-show="sidebarOpen">Expenses</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.financial.index') }}" class="nav-link text-white {{ request()->routeIs('admin.financial.index') && !request()->has('type') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-chart-pie me-2"></i> <span x-show="sidebarOpen">Reports</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.partners.index') }}" class="nav-link text-white {{ request()->routeIs('admin.partners*') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-handshake me-2"></i> <span x-show="sidebarOpen">Partners</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link text-white {{ request()->routeIs('admin.users*') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-user-shield me-2"></i> <span x-show="sidebarOpen">Users</span>
                        </a>
                    </li>

                    <div class="px-3 small text-uppercase opacity-50 mt-3 mb-1" x-show="sidebarOpen">Settings</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link text-white {{ request()->routeIs('admin.settings.index') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-building-ngo me-2"></i> <span x-show="sidebarOpen">Organization</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.smtp') }}" class="nav-link text-white {{ request()->routeIs('admin.settings.smtp') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-envelope-circle-check me-2"></i> <span x-show="sidebarOpen">SMTP Settings</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.gateways') }}" class="nav-link text-white {{ request()->routeIs('admin.settings.gateways') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-credit-card me-2"></i> <span x-show="sidebarOpen">Payment Gateways</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-white">
                            <i class="fa-solid fa-file-code me-2"></i> <span x-show="sidebarOpen">Templates</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.integrations') }}" class="nav-link text-white {{ request()->routeIs('admin.settings.integrations') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-plug me-2"></i> <span x-show="sidebarOpen">Integrations</span>
                        </a>
                    </li>

                    <div class="px-3 small text-uppercase opacity-50 mt-3 mb-1" x-show="sidebarOpen">Election & Logs</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.elections.index') }}" class="nav-link text-white {{ request()->routeIs('admin.elections*') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-check-to-slot me-2"></i> <span x-show="sidebarOpen">Election Module</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.audit.index') }}" class="nav-link text-white {{ request()->routeIs('admin.audit*') ? 'bg-primary' : '' }}">
                            <i class="fa-solid fa-clipboard-check me-2"></i> <span x-show="sidebarOpen">Audit Trail</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 overflow-hidden">
            <!-- Top Bar -->
            <div class="bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
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

            <div class="p-4 overflow-auto" style="height: calc(100vh - 70px);">
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
