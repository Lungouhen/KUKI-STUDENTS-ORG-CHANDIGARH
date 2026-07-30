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
    },
    // Dynamic initialization of folder states based on current route
    contentOpen: {{ request()->routeIs('admin.pages*') || request()->routeIs('admin.gallery*') || request()->routeIs('admin.news*') || request()->routeIs('admin.content*') || request()->routeIs('admin.messages*') ? 'true' : 'false' }},
    membersOpen: {{ request()->routeIs('admin.members*') || request()->routeIs('admin.committee*') ? 'true' : 'false' }},
    ngoOpen: {{ request()->routeIs('admin.partners*') || request()->routeIs('admin.projects*') || request()->routeIs('admin.beneficiaries*') ? 'true' : 'false' }},
    financeOpen: {{ request()->routeIs('admin.donations*') || request()->routeIs('admin.financial*') ? 'true' : 'false' }},
    settingsOpen: {{ request()->routeIs('admin.settings*') || request()->routeIs('admin.users*') ? 'true' : 'false' }},
    electionsOpen: {{ request()->routeIs('admin.elections*') || request()->routeIs('admin.audit*') ? 'true' : 'false' }}
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
                <ul class="nav flex-column gap-2 p-0">
                    <!-- Home Dashboard (Direct) -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-gauge-high"></i> <span x-show="sidebarOpen">CMS Home Dashboard</span>
                        </a>
                    </li>
                    
                    <div class="admin-section-divider" x-show="sidebarOpen">Workspace Folders</div>

                    <!-- 1. Content Manager Folder -->
                    <li class="admin-nav-item">
                        <button @click="contentOpen = !contentOpen" class="admin-nav-link sidebar-dropdown-toggle" :class="contentOpen ? 'open' : ''">
                            <span><i class="fa-solid fa-folder-open"></i> <span x-show="sidebarOpen">Content Manager</span></span>
                            <i class="fa-solid fa-chevron-right chevron-icon" x-show="sidebarOpen"></i>
                        </button>
                        <ul class="sidebar-submenu p-0 m-0 mt-1" x-show="contentOpen" x-transition x-cloak>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.content.index', ['type' => 'slider']) }}" class="sidebar-submenu-link {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'slider'])) ? 'active' : '' }}">
                                    <i class="fa-solid fa-images"></i> Slider Banners
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.pages.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-file-invoice"></i> About & Pages
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.gallery.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-photo-film"></i> Gallery Items
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.content.index', ['type' => 'certificate']) }}" class="sidebar-submenu-link {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'certificate'])) ? 'active' : '' }}">
                                    <i class="fa-solid fa-medal"></i> Certificates
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.content.index', ['type' => 'achievement']) }}" class="sidebar-submenu-link {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'achievement'])) ? 'active' : '' }}">
                                    <i class="fa-solid fa-trophy"></i> Achievements
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.content.index', ['type' => 'policy']) }}" class="sidebar-submenu-link {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'policy'])) ? 'active' : '' }}">
                                    <i class="fa-solid fa-building-shield"></i> Policies
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.news.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.news.index') ? 'active' : '' }}">
                                    <i class="fa-solid fa-square-rss"></i> News & Feed
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.content.index', ['type' => 'notice']) }}" class="sidebar-submenu-link {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'notice'])) ? 'active' : '' }}">
                                    <i class="fa-solid fa-bullhorn"></i> Notices
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.content.index', ['type' => 'campaign']) }}" class="sidebar-submenu-link {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'campaign'])) ? 'active' : '' }}">
                                    <i class="fa-solid fa-bullseye"></i> Campaigns
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.content.index', ['type' => 'career']) }}" class="sidebar-submenu-link {{ request()->fullUrlIs(route('admin.content.index', ['type' => 'career'])) ? 'active' : '' }}">
                                    <i class="fa-solid fa-briefcase"></i> Careers
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.messages.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-envelope-open-text"></i> Messages
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- 2. Member Control Folder -->
                    <li class="admin-nav-item">
                        <button @click="membersOpen = !membersOpen" class="admin-nav-link sidebar-dropdown-toggle" :class="membersOpen ? 'open' : ''">
                            <span><i class="fa-solid fa-user-gear"></i> <span x-show="sidebarOpen">Member Control</span></span>
                            <i class="fa-solid fa-chevron-right chevron-icon" x-show="sidebarOpen"></i>
                        </button>
                        <ul class="sidebar-submenu p-0 m-0 mt-1" x-show="membersOpen" x-transition x-cloak>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.members.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.members*') && !request()->has('status') ? 'active' : '' }}">
                                    <i class="fa-solid fa-users"></i> All Members
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.members.index', ['status' => 'Pending']) }}" class="sidebar-submenu-link {{ request()->fullUrlIs(route('admin.members.index', ['status' => 'Pending'])) ? 'active' : '' }}">
                                    <i class="fa-solid fa-user-clock"></i> Member Requests
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.members.fees') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.members.fees') ? 'active' : '' }}">
                                    <i class="fa-solid fa-money-bill"></i> Membership Fees
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.committee.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.committee*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-id-badge"></i> Designations
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- 3. NGO & People Folder -->
                    <li class="admin-nav-item">
                        <button @click="ngoOpen = !ngoOpen" class="admin-nav-link sidebar-dropdown-toggle" :class="ngoOpen ? 'open' : ''">
                            <span><i class="fa-solid fa-hands-holding-child"></i> <span x-show="sidebarOpen">NGO & People</span></span>
                            <i class="fa-solid fa-chevron-right chevron-icon" x-show="sidebarOpen"></i>
                        </button>
                        <ul class="sidebar-submenu p-0 m-0 mt-1" x-show="ngoOpen" x-transition x-cloak>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.partners.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.partners*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-handshake"></i> Partners
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.projects.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.projects*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-diagram-project"></i> Projects
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.beneficiaries.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.beneficiaries*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-users-viewfinder"></i> Beneficiaries
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- 4. Financial Ledger Folder -->
                    <li class="admin-nav-item">
                        <button @click="financeOpen = !financeOpen" class="admin-nav-link sidebar-dropdown-toggle" :class="financeOpen ? 'open' : ''">
                            <span><i class="fa-solid fa-sack-dollar"></i> <span x-show="sidebarOpen">Financial Ledger</span></span>
                            <i class="fa-solid fa-chevron-right chevron-icon" x-show="sidebarOpen"></i>
                        </button>
                        <ul class="sidebar-submenu p-0 m-0 mt-1" x-show="financeOpen" x-transition x-cloak>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.donations.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.donations*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-hand-holding-dollar"></i> Donations List
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.financial.index', ['type' => 'Expense']) }}" class="sidebar-submenu-link {{ request()->fullUrlIs(route('admin.financial.index', ['type' => 'Expense'])) ? 'active' : '' }}">
                                    <i class="fa-solid fa-file-invoice-dollar"></i> Expenses Tracker
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.financial.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.financial.index') && !request()->has('type') ? 'active' : '' }}">
                                    <i class="fa-solid fa-chart-pie"></i> Ledger Reports
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- 5. System Settings Folder -->
                    <li class="admin-nav-item">
                        <button @click="settingsOpen = !settingsOpen" class="admin-nav-link sidebar-dropdown-toggle" :class="settingsOpen ? 'open' : ''">
                            <span><i class="fa-solid fa-sliders"></i> <span x-show="sidebarOpen">System Settings</span></span>
                            <i class="fa-solid fa-chevron-right chevron-icon" x-show="sidebarOpen"></i>
                        </button>
                        <ul class="sidebar-submenu p-0 m-0 mt-1" x-show="settingsOpen" x-transition x-cloak>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.settings.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                                    <i class="fa-solid fa-building-ngo"></i> Organization
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.settings.smtp') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.settings.smtp') ? 'active' : '' }}">
                                    <i class="fa-solid fa-envelope-circle-check"></i> SMTP Config
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.settings.gateways') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.settings.gateways') ? 'active' : '' }}">
                                    <i class="fa-solid fa-credit-card"></i> Payment Gateways
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.settings.integrations') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.settings.integrations') ? 'active' : '' }}">
                                    <i class="fa-solid fa-plug"></i> Integrations
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.users.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-shield-halved"></i> Admin Users
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- 6. Election & Logs Folder -->
                    <li class="admin-nav-item">
                        <button @click="electionsOpen = !electionsOpen" class="admin-nav-link sidebar-dropdown-toggle" :class="electionsOpen ? 'open' : ''">
                            <span><i class="fa-solid fa-check-to-slot"></i> <span x-show="sidebarOpen">Election & Logs</span></span>
                            <i class="fa-solid fa-chevron-right chevron-icon" x-show="sidebarOpen"></i>
                        </button>
                        <ul class="sidebar-submenu p-0 m-0 mt-1" x-show="electionsOpen" x-transition x-cloak>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.elections.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.elections*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-check-to-slot"></i> Election Module
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.audit.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.audit*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-clipboard-check"></i> System Audit Trail
                                </a>
                            </li>
                        </ul>
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
