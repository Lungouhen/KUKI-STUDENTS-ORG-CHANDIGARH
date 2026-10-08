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
    <!-- Local SweetAlert2 CSS -->
    <link href="{{ asset('vendor/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <!-- Custom Styles & Vite Bundle -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @if(request()->routeIs('admin.dashboard'))
        <link href="{{ asset('css/pages/admin-dashboard.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.members.index'))
        <link href="{{ asset('css/pages/admin-members.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.members.create'))
        <link href="{{ asset('css/pages/admin-member-create.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.members.edit'))
        <link href="{{ asset('css/pages/admin-member-edit.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.members.show'))
        <link href="{{ asset('css/pages/admin-member-details.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.members.fees'))
        <link href="{{ asset('css/pages/admin-member-fees.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.memberDocuments.index'))
        <link href="{{ asset('css/pages/admin-member-documents.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.memberDocumentTemplates.index'))
        <link href="{{ asset('css/pages/admin-member-document-templates.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.membershipForms.index'))
        <link href="{{ asset('css/pages/admin-membership-forms.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.resources.index'))
        <link href="{{ asset('css/pages/admin-resources.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.accommodations.index'))
        <link href="{{ asset('css/pages/admin-accommodations.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.accommodations.create', 'admin.accommodations.edit'))
        <link href="{{ asset('css/pages/admin-accommodation-form.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.committee.index'))
        <link href="{{ asset('css/pages/admin-committee.css') }}" rel="stylesheet">
    @endif
    @if(request()->routeIs('admin.committee.edit'))
        <link href="{{ asset('css/pages/admin-committee-edit.css') }}" rel="stylesheet">
    @endif
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <!-- Local Alpine.js -->
    <script defer src="{{ asset('vendor/alpine/alpine.min.js') }}"></script>
    <!-- Local SweetAlert2 JS -->
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
    @if(request()->routeIs('admin.dashboard'))
        <script src="{{ asset('vendor/apexcharts/apexcharts.min.js') }}"></script>
    @endif

    @stack('styles')
</head>
<body class="admin-app" :class="darkMode ? 'bg-dark' : 'bg-light'" x-data="{ 
    sidebarOpen: true,
    sidebarMobileOpen: false,
    darkMode: localStorage.getItem('theme') === 'dark',
    toggleSidebar() {
        if (window.matchMedia('(max-width: 767.98px)').matches) {
            this.sidebarOpen = true;
            this.sidebarMobileOpen = !this.sidebarMobileOpen;
        } else {
            this.sidebarOpen = !this.sidebarOpen;
        }
    },
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
    },
    // Dynamic initialization of folder states based on current route
    contentOpen: {{ request()->routeIs('admin.pages*') || request()->routeIs('admin.gallery*') || request()->routeIs('admin.media*') || request()->routeIs('admin.news*') || request()->routeIs('admin.content*') || request()->routeIs('admin.messages*') ? 'true' : 'false' }},
    membersOpen: {{ request()->routeIs('admin.members*') || request()->routeIs('admin.memberDocuments*') || request()->routeIs('admin.memberDocumentTemplates*') || request()->routeIs('admin.membershipForms*') || request()->routeIs('admin.committee*') || request()->routeIs('admin.accommodations*') || request()->routeIs('admin.resources*') ? 'true' : 'false' }},
    ngoOpen: {{ request()->routeIs('admin.partners*') || request()->routeIs('admin.projects*') || request()->routeIs('admin.beneficiaries*') ? 'true' : 'false' }},
    financeOpen: {{ request()->routeIs('admin.donations*') || request()->routeIs('admin.financial*') ? 'true' : 'false' }},
    settingsOpen: {{ request()->routeIs('admin.settings*') || request()->routeIs('admin.users*') ? 'true' : 'false' }},
    electionsOpen: {{ request()->routeIs('admin.elections*') || request()->routeIs('admin.audit*') ? 'true' : 'false' }}
}" :data-bs-theme="darkMode ? 'dark' : 'light'" @keydown.escape.window="sidebarMobileOpen = false">

    <a class="skip-link" href="#main-content">Skip to main content</a>

    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="admin-sidebar" :class="[sidebarOpen ? 'w-sidebar' : 'w-icon-sidebar', sidebarMobileOpen ? 'sidebar-mobile-open' : '']">
            <div class="admin-profile">
                <div class="admin-profile-avatar" aria-hidden="true">{{ \Illuminate\Support\Str::substr(auth()->user()->name, 0, 1) }}</div>
                <div class="admin-profile-copy" x-show="sidebarOpen">
                    <span class="admin-profile-eyebrow">KSO CHANDIGARH</span>
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>Administrator</span>
                </div>
            </div>
            
            <nav id="admin-sidebar-navigation" class="sidebar-nav overflow-auto" aria-label="Admin navigation">
                <ul class="nav flex-column p-0">
                    <!-- Home Dashboard (Direct) -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-gauge-high"></i> <span x-show="sidebarOpen">CMS Home Dashboard</span>
                        </a>
                    </li>
                    
                    <div class="admin-section-divider" x-show="sidebarOpen">Workspace Folders</div>

                    <!-- 1. Content Manager Folder -->
                    <li class="admin-nav-item">
                        <button type="button" @click="contentOpen = !contentOpen" class="admin-nav-link sidebar-dropdown-toggle" :class="contentOpen ? 'open' : ''" :aria-expanded="contentOpen" aria-controls="content-submenu" aria-label="Toggle content manager menu">
                            <span><i class="fa-solid fa-folder-open"></i> <span x-show="sidebarOpen">Content Manager</span></span>
                            <i class="fa-solid fa-chevron-right chevron-icon" x-show="sidebarOpen"></i>
                        </button>
                        <ul id="content-submenu" class="sidebar-submenu p-0 m-0 mt-1" x-show="contentOpen" x-transition x-cloak>
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
                                <a href="{{ route('admin.media.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.media*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-photo-film"></i> Media Library
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
                        <button type="button" @click="membersOpen = !membersOpen" class="admin-nav-link sidebar-dropdown-toggle" :class="membersOpen ? 'open' : ''" :aria-expanded="membersOpen" aria-controls="members-submenu" aria-label="Toggle member control menu">
                            <span><i class="fa-solid fa-user-gear"></i> <span x-show="sidebarOpen">Member Control</span></span>
                            <i class="fa-solid fa-chevron-right chevron-icon" x-show="sidebarOpen"></i>
                        </button>
                        <ul id="members-submenu" class="sidebar-submenu p-0 m-0 mt-1" x-show="membersOpen" x-transition x-cloak>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.membershipForms.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.membershipForms*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-file-invoice"></i> Membership Forms
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.memberDocuments.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.memberDocuments*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-award"></i> Certificates & Documents
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.memberDocumentTemplates.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.memberDocumentTemplates*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-file-lines"></i> Document Templates
                                </a>
                            </li>
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
                                <a href="{{ route('admin.accommodations.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.accommodations*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-hotel"></i> Hostels & PGs
                                </a>
                            </li>
                            <li class="sidebar-submenu-item">
                                <a href="{{ route('admin.resources.index') }}" class="sidebar-submenu-link {{ request()->routeIs('admin.resources*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-book-open"></i> Resource Library
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
                        <button type="button" @click="ngoOpen = !ngoOpen" class="admin-nav-link sidebar-dropdown-toggle" :class="ngoOpen ? 'open' : ''" :aria-expanded="ngoOpen" aria-controls="ngo-submenu" aria-label="Toggle NGO and people menu">
                            <span><i class="fa-solid fa-hands-holding-child"></i> <span x-show="sidebarOpen">NGO & People</span></span>
                            <i class="fa-solid fa-chevron-right chevron-icon" x-show="sidebarOpen"></i>
                        </button>
                        <ul id="ngo-submenu" class="sidebar-submenu p-0 m-0 mt-1" x-show="ngoOpen" x-transition x-cloak>
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
                        <button type="button" @click="financeOpen = !financeOpen" class="admin-nav-link sidebar-dropdown-toggle" :class="financeOpen ? 'open' : ''" :aria-expanded="financeOpen" aria-controls="finance-submenu" aria-label="Toggle financial ledger menu">
                            <span><i class="fa-solid fa-sack-dollar"></i> <span x-show="sidebarOpen">Financial Ledger</span></span>
                            <i class="fa-solid fa-chevron-right chevron-icon" x-show="sidebarOpen"></i>
                        </button>
                        <ul id="finance-submenu" class="sidebar-submenu p-0 m-0 mt-1" x-show="financeOpen" x-transition x-cloak>
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
                        <button type="button" @click="settingsOpen = !settingsOpen" class="admin-nav-link sidebar-dropdown-toggle" :class="settingsOpen ? 'open' : ''" :aria-expanded="settingsOpen" aria-controls="settings-submenu" aria-label="Toggle system settings menu">
                            <span><i class="fa-solid fa-sliders"></i> <span x-show="sidebarOpen">System Settings</span></span>
                            <i class="fa-solid fa-chevron-right chevron-icon" x-show="sidebarOpen"></i>
                        </button>
                        <ul id="settings-submenu" class="sidebar-submenu p-0 m-0 mt-1" x-show="settingsOpen" x-transition x-cloak>
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
                        <button type="button" @click="electionsOpen = !electionsOpen" class="admin-nav-link sidebar-dropdown-toggle" :class="electionsOpen ? 'open' : ''" :aria-expanded="electionsOpen" aria-controls="elections-submenu" aria-label="Toggle elections and logs menu">
                            <span><i class="fa-solid fa-check-to-slot"></i> <span x-show="sidebarOpen">Election & Logs</span></span>
                            <i class="fa-solid fa-chevron-right chevron-icon" x-show="sidebarOpen"></i>
                        </button>
                        <ul id="elections-submenu" class="sidebar-submenu p-0 m-0 mt-1" x-show="electionsOpen" x-transition x-cloak>
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
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="admin-workspace flex-grow-1">
            <!-- Top Bar -->
            <header class="admin-topbar d-flex justify-content-between align-items-center">
                <div class="admin-topbar-heading d-flex align-items-center gap-3">
                    <button @click="toggleSidebar()" class="admin-menu-toggle" type="button" aria-controls="admin-sidebar-navigation" :aria-expanded="window.matchMedia('(max-width: 767.98px)').matches ? sidebarMobileOpen : sidebarOpen" :aria-label="window.matchMedia('(max-width: 767.98px)').matches ? (sidebarMobileOpen ? 'Close admin navigation' : 'Open admin navigation') : (sidebarOpen ? 'Collapse admin navigation' : 'Expand admin navigation')">
                        <i class="fa-solid fa-bars" aria-hidden="true"></i>
                    </button>
                    <div>
                        <span class="admin-breadcrumb">KSO ADMIN / WORKSPACE</span>
                        <h1 class="admin-page-title">@yield('title', 'Admin Panel')</h1>
                    </div>
                </div>
                <div class="admin-topbar-actions d-flex align-items-center gap-2 gap-md-3">
                    <button @click="toggleTheme()" class="btn btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center admin-theme-toggle" :class="darkMode ? 'btn-warning' : 'btn-outline-dark'" aria-label="Toggle dark mode" style="width: 38px; height: 38px;">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button>
                    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-sm admin-site-link"><i class="fa-solid fa-arrow-up-right-from-square me-1" aria-hidden="true"></i><span>View site</span></a>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-sm admin-logout" aria-label="Log out of the admin panel"><i class="fa-solid fa-arrow-right-from-bracket me-1" aria-hidden="true"></i><span>Logout</span></button>
                    </form>
                </div>
            </header>

            <main id="main-content" class="admin-main-container p-4" tabindex="-1">
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
            </main>
    </div>
    </div>
    <button class="admin-sidebar-backdrop" type="button" x-show="sidebarMobileOpen" x-transition.opacity @click="sidebarMobileOpen = false" aria-label="Close admin navigation"></button>

    <!-- Local Bootstrap 5 JS -->
    <script src="{{ asset('vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

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

    @if(request()->routeIs('admin.dashboard'))
        <script src="{{ asset('js/pages/admin-dashboard.js') }}" defer></script>
    @endif
    @if(request()->routeIs('admin.members.index'))
        <script src="{{ asset('js/pages/admin-members.js') }}" defer></script>
    @endif
    @if(request()->routeIs('admin.members.create'))
        <script src="{{ asset('js/pages/admin-member-create.js') }}" defer></script>
    @endif
    @if(request()->routeIs('admin.members.edit'))
        <script src="{{ asset('js/pages/admin-member-edit.js') }}" defer></script>
    @endif
    @if(request()->routeIs('admin.members.show'))
        <script src="{{ asset('js/pages/admin-member-details.js') }}" defer></script>
    @endif
    @if(request()->routeIs('admin.members.fees'))
        <script src="{{ asset('js/pages/admin-member-fees.js') }}" defer></script>
    @endif
    @if(request()->routeIs('admin.memberDocuments.index'))
        <script src="{{ asset('js/pages/admin-member-documents.js') }}" defer></script>
    @endif
    @if(request()->routeIs('admin.memberDocumentTemplates.index'))
        <script src="{{ asset('js/pages/admin-member-document-templates.js') }}" defer></script>
    @endif
    @if(request()->routeIs('admin.membershipForms.index'))
        <script src="{{ asset('js/pages/admin-membership-forms.js') }}" defer></script>
    @endif
    @if(request()->routeIs('admin.resources.index'))
        <script src="{{ asset('js/pages/admin-resources.js') }}" defer></script>
    @endif
    @if(request()->routeIs('admin.accommodations.index'))
        <script src="{{ asset('js/pages/admin-accommodations.js') }}" defer></script>
    @endif
    @if(request()->routeIs('admin.accommodations.create', 'admin.accommodations.edit'))
        <script src="{{ asset('js/pages/admin-accommodation-form.js') }}" defer></script>
    @endif
    @if(request()->routeIs('admin.committee.index'))
        <script src="{{ asset('js/pages/admin-committee.js') }}" defer></script>
    @endif
    @if(request()->routeIs('admin.committee.edit'))
        <script src="{{ asset('js/pages/admin-committee-edit.js') }}" defer></script>
    @endif
    @stack('scripts')
</body>
</html>
